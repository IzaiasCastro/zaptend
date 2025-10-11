<?php

namespace App\Observers;

use App\Models\Agendamento;
use App\Models\Horario;
use App\Services\ExternalApiService;
use Illuminate\Support\Facades\Log;

class AgendamentoObserver
{
  protected $api;

    public function __construct(ExternalApiService $api)
    {
        $this->api = $api;
    }

    public function created(Agendamento $agendamento)
    {
        Log::info('Criou agendamento: ' . $agendamento->id);
        //ir na tabela de horario com base no campo profissional_id e pegar o ultimo horario criado
        $horario = Horario::where('profissional_id', $agendamento->profissional_id)->latest()->first();
        //pegar o dia da semana do agendamento
        $diaDaSemana = date('w', strtotime($agendamento->data));

        $diasSemana = [
            0 => 'domingo',
            1 => 'segunda',
            2 => 'terça',
            3 => 'quarta',
            4 => 'quinta',
            5 => 'sexta',
            6 => 'sábado',
        ];

        $diaDaSemana = $diasSemana[$diaDaSemana];
        Log::info('Dia da semana: ' . $diaDaSemana);

        // Quebrar os horários em array
        $horariosSegunda = explode(',', $horario->segunda);

        // Remover o horário agendado
        $horariosSegunda = array_filter($horariosSegunda, fn($h) => trim($h) !== $agendamento->horario);

        // Reunir novamente em string
        $horarioAtualizado = implode(',', $horariosSegunda);

        // Atualizar a coluna
        $horario->update([
            'segunda' => $horarioAtualizado
        ]);

// Atualizar assistente
        $this->api->updateAssistent($horario);
    }
}
