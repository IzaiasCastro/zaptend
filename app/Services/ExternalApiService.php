<?php

namespace App\Services;

use App\Models\Agendamento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ExternalApiService
{
    private $apiKey;
    private $assistant_id;

    public function __construct()
    {
        $this->apiKey = config('services.api_key_gpt'); // chave da OpenAI
        $this->assistant_id = config('services.assistant_id_gpt'); // chave da OpenAI
    }

    /**
     * Retorna o header de autenticação para a API
     */
    public function getHeaders()
    {
        Log::info('Usando API Key: ' . substr($this->apiKey, 0, 10) . '...'); // Log parcial da chave para verificação
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Faz uma requisição GET à OpenAI (exemplo: listar modelos)
     */

    public function updateAssistent($horario, $diaSemana = null)
    {

        // prompt

        // Mapear números do PHP para nomes dos dias
        $diasSemana = [
            0 => 'domingo',
            1 => 'segunda',
            2 => 'terça',
            3 => 'quarta',
            4 => 'quinta',
            5 => 'sexta',
            6 => 'sábado',
        ];

        // Dia atual
        $hoje = $diasSemana[date('w')];

        // Gerar datas correspondentes aos próximos dias da semana
        $datasSemana = [];
        $datasSemanaOcupados = [];
        // for ($i = 0; $i < 7; $i++) {
        //     $datasSemana[$diasSemana[$i]] = Carbon::now()->startOfWeek()->addDays($i)->format('Y-m-d');
        //     // dd($datasSemana[$diasSemana[$i]]);
        //     //consultar agendamentos pra cada data
        //     $datasSemanaOcupados[$diasSemana[$i]] = Agendamento::where('data', $datasSemana[$diasSemana[$i]])
        //     ->where('profissional_id', $horario->profissional_id)->get()->pluck('horario');
            
        // }

        for ($i = 0; $i < 7; $i++) {
            // Se hoje for domingo, começa a contagem hoje
            if (Carbon::now()->isSunday()) {
                $dataBase = Carbon::now()->startOfWeek(Carbon::SUNDAY);
            } else {
                // Caso contrário, começa no PRÓXIMO domingo
                $dataBase = Carbon::now()->next(Carbon::SUNDAY);
            }

            $datasSemana[$diasSemana[$i]] = $dataBase
                ->copy()
                ->addDays($i)
                ->format('Y-m-d');

            // Consulta agendamentos desse dia
            $datasSemanaOcupados[$diasSemana[$i]] = Agendamento::where('data', $datasSemana[$diasSemana[$i]])
                ->where('profissional_id', $horario->profissional_id)
                ->pluck('horario');
        }


       $prompt = <<<EOT
                        Você é um assistente virtual especializado em organizar agendas de profissionais.

                        Profissional: **{$horario->profissional->nome}**

                        ---

                        ### 🕒 Horários disponíveis:

                        - Domingo ({$datasSemana['domingo']}): {$horario->domingo}
                        - Segunda ({$datasSemana['segunda']}): {$horario->segunda}
                        - Terça ({$datasSemana['terça']}): {$horario->terca}
                        - Quarta ({$datasSemana['quarta']}): {$horario->quarta}
                        - Quinta ({$datasSemana['quinta']}): {$horario->quinta}
                        - Sexta ({$datasSemana['sexta']}): {$horario->sexta}
                        - Sábado ({$datasSemana['sábado']}): {$horario->sabado}


                        ---

                        ### 🚫 Horários ocupados (não disponíveis):

                        - Domingo ({$datasSemanaOcupados['domingo']})
                        - Segunda ({$datasSemanaOcupados['segunda']})
                        - Terça ({$datasSemanaOcupados['terça']})
                        - Quarta ({$datasSemanaOcupados['quarta']})
                        - Quinta ({$datasSemanaOcupados['quinta']})
                        - Sexta ({$datasSemanaOcupados['sexta']})
                        - Sábado ({$datasSemanaOcupados['sábado']})

                        Esses horários **não podem ser agendados**.  
                        Nunca confirme nem ofereça nenhum desses horários.

                        Todos os outros horários listados acima **estão disponíveis** para agendamento.

                        ---

                        ### 🧠 Regras de interpretação de datas:
                        - Quando o cliente disser um dia da semana (ex: "terça", "sexta"), associe à **data correta da semana atual**.
                        - Se disser "amanhã" ou "depois de amanhã", **calcule automaticamente** a data correspondente.

                        ---

                        ### 📅 Ao receber um pedido de agendamento:

                        1. Identifique o **dia e a data exata**.
                        2. Verifique se o **horário pedido está disponível**.
                        3. Se estiver disponível, **confirme de forma amigável** e envie o link de confirmação:
                        {$horario->link}?profissional={urlencode($horario->profissional->nome)}&data={DATA_ENCONTRADA}&horario={HORARIO_PEDIDO}

                        4. Se **não estiver disponível**, avise gentilmente e **sugira até 3 horários alternativos**.

                        ---

                        ### ⚠️ IMPORTANTE:
                        - **Você deve SEMPRE incluir o link de confirmação** em sua resposta, mesmo quando estiver apenas sugerindo horários alternativos.
                        - **Sem o link, o agendamento não será confirmado.**
                        - Mantenha sempre um **tom profissional, empático e acolhedor**.

                        EOT;

                Log::info($prompt);

                //

                $result = [
                    'instructions' => $prompt
                ];

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                    'OpenAI-Beta' => 'assistants=v2'
                ])->post("https://api.openai.com/v1/assistants/{$this->assistant_id}", $result);

                if ($response->successful()) {
                    Log::info('Resposta da API GPT: ' . $response->body());
                    return $response->json();
                }

                throw new \Exception('Erro ao modificar assistente: ' . $response->body());
    }

    public function complement() {
             $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-5',
                'messages' => [
                    [
                        'role' => 'developer',
                        'content' => 'You are a helpful assistant.',
                    ],
                    [
                        'role' => 'user',
                        'content' => 'Hello!',
                    ],
                ],
            ]);

            // Exibir ou tratar resposta
            if ($response->successful()) {
                $data = $response->json();
                dd($data['choices'][0]['message']['content']);
            } else {
                dd('Erro: ' . $response->body());
            }
    }
   
}
