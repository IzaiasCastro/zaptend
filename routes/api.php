<?php

use App\Http\Controllers\WhatsAppWebhookController;
use App\Models\Agendamento;
use App\Models\Cliente;
use App\Models\Profissional;
use App\Models\Servico;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//criar cliente via whatsapp
Route::post('/criar-cliente', function (Request $request) {
    $dados = [
        'nome' => $request->input('nome'),
        'telefone' => $request->input('telefone'),
    ];
    $cliente = Cliente::create($dados);
    return response()->json([
        'success' => true,
        'data' => $cliente,
    ]);
});


//buscar cliente pelo whatsapp
Route::get('/buscar-cliente', function (Request $request) {
    $cliente = Cliente::where('telefone', 'like', $request->input('whatsapp'))->first();
    if( !$cliente ) {
        Log::error('Cliente nao encontrado pelo whatsapp: ' . $request->input('whatsapp'));
        return response()->json([
            'success' => false,
            'message' => 'Cliente nao encontrado',
        ], 404);
    }
    return response()->json([
        'success' => true,
        'data' => $cliente,
    ]);
});

Route::get('/agendas', function (Request $request) {

    // JSON com agendas dos profissionais
    $profissionais = Profissional::with('agenda')->get();

    $jsonAgendas = $profissionais->map(function($profissional) {
        $agenda = $profissional->agenda;

        // Se não houver agenda ou status for falso, profissional não está disponível
        $disponivel = $agenda && $agenda->status;

        $dias_de_trabalho = [];

        if( $disponivel && $agenda ) {
            $dias_de_trabalho = [
                'domingo' => $agenda->domingo ? 'disponivel' : 'nao disponivel',
                'segunda' => $agenda->segunda ? 'disponivel' : 'nao disponivel',
                'terca' => $agenda->terca ? 'disponivel' : 'nao disponivel',
                'quarta' => $agenda->quarta ? 'disponivel' : 'nao disponivel',
                'quinta' => $agenda->quinta ? 'disponivel' : 'nao disponivel',
                'sexta' => $agenda->sexta ? 'disponivel' : 'nao disponivel',
                'sabado' => $agenda->sabado ? 'disponivel' : 'nao disponivel',
                ];
        }

        $diaIngles = Carbon::now()->format('l');

        $diasSemana = [
            'Monday'    => 'segunda',
            'Tuesday'   => 'terca',
            'Wednesday' => 'quarta',
            'Thursday'  => 'quinta',
            'Friday'    => 'sexta',
            'Saturday'  => 'sabado',
            'Sunday'    => 'domingo',
        ];

        $diaPortugues = $diasSemana[$diaIngles] ?? $diaIngles;

        //dias da semana pre calculados

        $hoje = Carbon::now();
        $referencia_semanal = [
            'data_hoje' => $hoje->format('Y-m-d'),
            'dia_semana_hoje' => $diaPortugues,
            'amanha' => $hoje->copy()->addDay()->format('Y-m-d'),
            'segunda' => $hoje->copy()->next(Carbon::MONDAY)->format('Y-m-d'),
            'terca' => $hoje->copy()->next(Carbon::TUESDAY)->format('Y-m-d'),
            'quarta' => $hoje->copy()->next(Carbon::WEDNESDAY)->format('Y-m-d'),
            'quinta' => $hoje->copy()->next(Carbon::THURSDAY)->format('Y-m-d'),
            'sexta' => $hoje->copy()->next(Carbon::FRIDAY)->format('Y-m-d'),
            'sabado' => $hoje->copy()->next(Carbon::SATURDAY)->format('Y-m-d'),
            'domingo' => $hoje->copy()->next(Carbon::SUNDAY)->format('Y-m-d'),
        ];

        return [
            'profissional' => $profissional->nome,
            'whatsapp' => $profissional->telefone,
            'disponivel' => $disponivel,
            'status' => $agenda->status ?? false, // status da agenda
            'inicio_expediente' => $disponivel ? $agenda->inicio_expediente : null,
            'fim_expediente' => $disponivel ? $agenda->fim_expediente : null,
            'inicio_almoco' => $disponivel ? $agenda->inicio_almoco : null,
            'fim_almoco' => $disponivel ? $agenda->fim_almoco : null,
            'tempo_medio' => $disponivel ? $agenda->tempo_medio : null,
            'dias_de_trabalho' => $dias_de_trabalho,
            'dia_da_semana_atual' => $diaPortugues,
            'referencia_semanal' => $referencia_semanal,
            'nome_estabelecimento' => ''
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

Route::post('/agendamento', function (Request $request) {
    //remover hora e criar somente a data
    $dataInicio = Carbon::parse($request->input('data_inicio'))->format('Y-m-d');

    //remover data e criar somente a hora
    $horario = Carbon::parse($request->input('data_inicio'))->format('H:i');

    $profissional = Profissional::where('nome', 'like', $request->input('profissional'))->first();
    if( !$profissional ) {
        Log::error('Profissional não encontrado: ' . $request->input('profissional'));
        return response()->json([
            'success' => false,
            'message' => 'Profissional não encontrado',
        ], 404);
    }

    //cliente
    $cliente = Cliente::where('nome', 'like', $request->input('cliente'))->first();
    if( !$cliente ) {
        Log::error('Cliente nao encontrado: ' . $request->input('cliente'));
        return response()->json([
            'success' => false,
            'message' => 'Cliente nao encontrado',
        ], 404);
    }

    //servico
    $servico = Servico::where('nome', 'like', $request->input('servico'))->first();
    if( !$servico ) {
        Log::error('Servico nao encontrado: ' . $request->input('servico'));
        return response()->json([
            'success' => false,
            'message' => 'Servico nao encontrado',
        ], 404);
    }

    $dadosFormatados = [
        'profissional_id' => $profissional->id,
        'cliente_id' => $cliente->id,
        'servico_id' => $servico->id,
        'data' => $dataInicio,
        'horario' => $horario,
        'status' => 'confirmado',
    ];

    //nao permitir agendamento duplicado
    $agendamentoExistente = Agendamento::where('profissional_id', $profissional->id)
        ->where('data', $dataInicio)
        ->where('horario', $horario)
        ->first();
    if( $agendamentoExistente ) {
        Log::error('Agendamento duplicado para o profissional: ' . $profissional->nome . ' na data: ' . $dataInicio . ' horario: ' . $horario);
        return response()->json([
            'success' => false,
            'message' => 'Agendamento duplicado para o profissional na data e horario informados',
        ], 409);
    }
    
    // Converte DD/MM/YYYY → YYYY-MM-DD
    $dados['data'] = Carbon::createFromFormat('d/m/Y', $dadosFormatados['data'])
                                        ->format('Y-m-d');

    $agendamento = Agendamento::create($dadosFormatados);
    return response()->json([
        'success' => true,
        'data' => $agendamento,
    ]);
});