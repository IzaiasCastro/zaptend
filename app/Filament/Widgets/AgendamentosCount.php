<?php

namespace App\Filament\Widgets;

use App\Models\Agendamento;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class AgendamentosCount extends ChartWidget
{
    protected ?string $heading = 'Agendamentos De Hoje';

    protected function getType(): string
    {
        return 'bar'; // pode ser 'line' ou 'bar'
    }

    protected function getData(): array
    {
        // Conta quantos agendamentos estão confirmados e finalizados
        $confirmados = Agendamento::where('status', 'confirmado')->where('data' ,Carbon::now()->format('Y-m-d'))->count();
        $finalizados = Agendamento::where('status', 'finalizado')->where('data' ,Carbon::now()->format('Y-m-d'))->count();

        return [
            'labels' => ['Confirmados', 'Finalizados'],
            'datasets' => [
                [
                    'label' => 'Quantidade',
                    'data' => [$confirmados, $finalizados],
                    'backgroundColor' => ['#34D399', '#3B82F6'], // cores das barras
                ],
            ],
        ];
    }
}
