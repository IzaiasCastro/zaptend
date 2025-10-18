<?php

use App\Http\Controllers\WhatsAppWebhookController;
use App\Models\Agendamento;
use App\Models\Profissional;
use App\Models\Servico;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/agendas', function (Request $request) {

    // JSON com agendas dos profissionais
    $profissionais = Profissional::with('agenda')->get();

    $jsonAgendas = $profissionais->map(function($profissional) {
        $agenda = $profissional->agenda;

        // Se não houver agenda ou status for falso, profissional não está disponível
        $disponivel = $agenda && $agenda->status;

        return [
            'profissional' => $profissional->nome,
            'disponivel' => $disponivel,
            'status' => $agenda->status ?? false, // status da agenda
            'inicio_expediente' => $disponivel ? $agenda->inicio_expediente : null,
            'fim_expediente' => $disponivel ? $agenda->fim_expediente : null,
            'inicio_almoco' => $disponivel ? $agenda->inicio_almoco : null,
            'fim_almoco' => $disponivel ? $agenda->fim_almoco : null,
            'tempo_medio' => $disponivel ? $agenda->tempo_medio : null,
        ];
    });

    // JSON com agendamentos confirmados  na data maior ou igual a hoje
    $agendamentosConfirmados = Agendamento::whereBetween('data', [Carbon::now()->format('Y-m-d'), Carbon::now()->addWeek()->format('Y-m-d')])
        ->get()
        ->map(function($agendamento) {
            return [
                'profissional' => $agendamento->profissional->nome,
                'data' => $agendamento->data,
                'horario' => $agendamento->horario,
                'cliente' => $agendamento->cliente->nome ?? null,
                'servico' => $agendamento->servico->nome ?? null,
            ];
        });

    return response()->json([
        'success' => true,
        'data' => [
            'profissionais' => $jsonAgendas,
            'agendamentos_confirmados' => $agendamentosConfirmados,
            'data_hoje' => Carbon::now()->format('Y-m-d'),
            'horario_atual' => Carbon::now()->format('H:i'),
            'servicos_disponiveis' => Servico::where('status', true)->get()
            ->map(function($servico) {
                return [
                    'nome' => $servico->nome,
                    'preco' => $servico->preco,
                    'descricao' => $servico->descricao,
                ];
            })
            ,
        ],
    ]);
});