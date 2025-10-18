<?php

use App\Http\Controllers\WhatsAppWebhookController;
use App\Models\Agendamento;
use App\Models\Profissional;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/agendas', function (Request $request) {
    if ($request['data']) {
    // Converte a string da URL para Carbon
    $data = Carbon::parse($data)->format('Y-m-d');

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

    // JSON com agendamentos confirmados na data
    $agendamentosConfirmados = Agendamento::whereDate('data', $data)
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
        ],
    ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Antes de tudo pergunte a data que o cliente deseja fazer o agendamento.',
        ], 400);
    }
});