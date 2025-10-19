<?php

namespace App\Filament\Widgets;

use App\Models\Agendamento;
use Filament\Widgets\ChartWidget;

class AgendamentosCount extends ChartWidget
{
    protected ?string $heading = 'Agendamentos Confirmados';

    protected function getType(): string
    {
        return 'bar'; // pode ser 'line' ou 'bar'
    }

    protected function getData(): array
    {
        // Conta quantos agendamentos estão confirmados e finalizados
        $confirmados = Agendamento::where('status', 'confirmado')->count();
        $finalizados = Agendamento::where('status', 'finalizado')->count();

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
