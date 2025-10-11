<?php

namespace App\Services;

use App\Models\Agendamento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalApiService
{
    private $apiKey;
    private $assistant_id;

    public function __construct()
    {
        $this->apiKey = config('services.api_key_gpt'); 
        $this->assistant_id = config('services.assistant_id_gpt'); 
    }

    /**
     * Atualiza a assistente usando um prompt dinâmico com os horários do profissional
     */
    public function updateAssistant($horario)
    {
        // Regras permanentes do assistente
        $instrucoesPersistentes = <<<EOT
Você é um assistente virtual especializado em organizar agendas de profissionais.

- Sempre envie o link de confirmação ao usuário. Sem ele, o agendamento não será confirmado.
- Nunca confirme horários que não estejam na lista de horários disponíveis.
- Sempre mantenha um tom profissional, empático e acolhedor.
- Se o horário solicitado estiver ocupado, sugira até 3 alternativas.
EOT;

        // Mapear números do PHP para nomes dos dias
        $diasSemana = ['domingo','segunda','terça','quarta','quinta','sexta','sábado'];
        $hoje = $diasSemana[date('w')];

        // Datas da próxima semana
        $datasSemana = [];
        foreach ($diasSemana as $i => $dia) {
            $datasSemana[$dia] = Carbon::now()->next($i)->format('Y-m-d'); 
        }

        // Buscar horários ocupados do banco
        $datasSemanaOcupados = [];
        foreach ($diasSemana as $dia) {
            $datasSemanaOcupados[$dia] = Agendamento::where('profissional_id', $horario->profissional_id)
                ->where('data', $datasSemana[$dia])
                ->get()
                ->pluck('horario')
                ->toArray();
        }

        // Função para formatar horários bonitos
        $formatarHorarios = fn($arr) => empty($arr) ? 'Nenhum horário ocupado' : implode(', ', $arr);

        // Gerar prompt dinâmico
        $prompt = <<<EOT
Profissional: {$horario->profissional->nome}

🕒 Horários disponíveis:
- Domingo ({$datasSemana['domingo']}): {$horario->domingo}
- Segunda ({$datasSemana['segunda']}): {$horario->segunda}
- Terça ({$datasSemana['terça']}): {$horario->terca}
- Quarta ({$datasSemana['quarta']}): {$horario->quarta}
- Quinta ({$datasSemana['quinta']}): {$horario->quinta}
- Sexta ({$datasSemana['sexta']}): {$horario->sexta}
- Sábado ({$datasSemana['sábado']}): {$horario->sabado}

🚫 Horários ocupados:
- Domingo: {$formatarHorarios($datasSemanaOcupados['domingo'])}
- Segunda: {$formatarHorarios($datasSemanaOcupados['segunda'])}
- Terça: {$formatarHorarios($datasSemanaOcupados['terça'])}
- Quarta: {$formatarHorarios($datasSemanaOcupados['quarta'])}
- Quinta: {$formatarHorarios($datasSemanaOcupados['quinta'])}
- Sexta: {$formatarHorarios($datasSemanaOcupados['sexta'])}
- Sábado: {$formatarHorarios($datasSemanaOcupados['sábado'])}

- Nunca confirme horários que não estejam na lista de horários disponíveis.
- você sempre vai ver os horarios ocupados e não vai autorizar o agendamento para esses horários.
- Se alguem solicitar um horário ocupado, sugira até 3 alternativas.

EOT;

        Log::info("Prompt para o assistente:\n" . $prompt);

        // Enviar para a API de completions
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("https://api.openai.com/v1/chat/completions", [
            'model' => 'gpt-4-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $instrucoesPersistentes
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
        ]);

        if ($response->successful()) {
            Log::info('Resposta da API GPT: ' . $response->body());
            return $response->json();
        }

        throw new \Exception('Erro ao chamar assistente: ' . $response->body());
    }
}
