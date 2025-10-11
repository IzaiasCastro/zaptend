<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use App\Models\Horario;
use Illuminate\Container\Attributes\DB;
use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Facades\Log as FacadesLog;

class ConfirmarAgendamentoController extends Controller
{
public function show(Request $request)
    {
        $profissional = $request->query('profissional');
        $data = $request->query('data');
        $horario = $request->query('horario');

        // Verifica se veio tudo certo
        if (!$profissional || !$data || !$horario) {
            return response()->view('agendamentos.erro', [
                'mensagem' => 'Informações do agendamento incompletas.'
            ], 400);
        }

        // Aqui você pode buscar o profissional ou o horário real do banco
        $horarioModel = Horario::whereHas('profissional', function ($q) use ($profissional) {
            $q->where('nome', $profissional);
        })->first();

        if (!$horarioModel) {
            return response()->view('agendamentos.erro', [
                'mensagem' => 'Profissional não encontrado.'
            ], 404);
        }

        // Exemplo: salvar o agendamento
        Agendamento::create([
            'profissional_id' => $horarioModel->profissional_id,
            'data' => $data,
            'horario' => $horario
        ]);

        FacadesLog::info("Agendamento confirmado: {$profissional} - {$data} {$horario}");

        return view('confirmar_agendamento', compact('profissional', 'data', 'horario'));
    }
}
