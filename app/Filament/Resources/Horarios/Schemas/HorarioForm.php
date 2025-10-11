<?php

namespace App\Filament\Resources\Horarios\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Support\Carbon;

class HorarioForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Configuração Base')
                ->schema([
                    Group::make([
                        TimePicker::make('duracao_atendimento')
                            ->label('Duração do atendimento')
                            ->required(),

                        TimePicker::make('inicio_atendimento')
                            ->label('Início do expediente')
                            ->required(),

                        TimePicker::make('fim_atendimento')
                            ->label('Fim do expediente')
                            ->required(),
                    ])->columns(3),

                    Group::make([
                        TimePicker::make('inicio_intervalo')
                            ->label('Início do intervalo')
                            ->helperText('Horário de início da pausa (opcional)')
                            ->nullable(),

                        TimePicker::make('fim_intervalo')
                            ->label('Fim do intervalo')
                            ->helperText('Horário de término da pausa (opcional)')
                            ->nullable(),
                    ])->columns(2),
                ]),

            Section::make('Horários por dia da semana')
                ->schema([
                    static::horariosPorDia('domingo'),
                    static::horariosPorDia('segunda'),
                    static::horariosPorDia('terca'),
                    static::horariosPorDia('quarta'),
                    static::horariosPorDia('quinta'),
                    static::horariosPorDia('sexta'),
                    static::horariosPorDia('sabado'),
                ])
                ->visible(fn (\Filament\Schemas\Components\Utilities\Get $get) =>
                    $get('inicio_atendimento') &&
                    $get('fim_atendimento') &&
                    $get('duracao_atendimento')
                ),

            TextInput::make('profissional_id')
                ->label('Profissional')
                ->required()
                ->numeric(),
        ]);
    }

    private static function horariosPorDia(string $dia): CheckboxList
    {
        return CheckboxList::make($dia)
            ->label(ucfirst($dia))
            ->options(function (\Filament\Schemas\Components\Utilities\Get $get) {
                $inicio = $get('inicio_atendimento');
                $fim = $get('fim_atendimento');
                $duracao = $get('duracao_atendimento');
                $inicioIntervalo = $get('inicio_intervalo');
                $fimIntervalo = $get('fim_intervalo');

                if (! $inicio || ! $fim || ! $duracao) {
                    return [];
                }

                $inicioHora = Carbon::parse($inicio);
                $fimHora = Carbon::parse($fim);
                $minutos = self::getDuracaoEmMinutos($duracao);

                $intervalos = [];
                $hora = $inicioHora->copy();

                while ($hora->lte($fimHora)) {
                    $horaStr = $hora->format('H:i');

                    // pula os horários que estão dentro do intervalo
                    if ($inicioIntervalo && $fimIntervalo) {
                        $inicioInt = Carbon::parse($inicioIntervalo);
                        $fimInt = Carbon::parse($fimIntervalo);

                        if ($hora->between($inicioInt, $fimInt, true)) {
                            $hora->addMinutes($minutos);
                            continue;
                        }
                    }

                    $intervalos[$horaStr] = $horaStr;
                    $hora->addMinutes($minutos);
                }

                return $intervalos;
            })
            ->columns(4)
            ->afterStateHydrated(function ($component, $state, $get) {
                // Se não houver nada salvo, marca todos os horários como selecionados
                if (empty($state)) {
                    $options = $component->getOptions();
                    $component->state(array_keys($options));
                } elseif (is_string($state)) {
                    $component->state(explode(',', $state));
                }
            })
            ->dehydrateStateUsing(function ($state) {
                return is_array($state) ? implode(',', $state) : $state;
            });
    }

    private static function getDuracaoEmMinutos($duracao): int
    {
        if ($duracao instanceof \DateTimeInterface) {
            return (int) Carbon::instance($duracao)->format('i');
        }

        if (is_string($duracao)) {
            try {
                $time = Carbon::parse($duracao);
                return ($time->hour * 60) + $time->minute;
            } catch (\Exception $e) {
                return 30;
            }
        }

        return 30;
    }
}
