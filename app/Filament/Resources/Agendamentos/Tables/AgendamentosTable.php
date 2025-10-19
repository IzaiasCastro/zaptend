<?php

namespace App\Filament\Resources\Agendamentos\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup as ActionsBulkActionGroup;
use Filament\Actions\DeleteBulkAction as ActionsDeleteBulkAction;
use Filament\Actions\EditAction as ActionsEditAction;
use Filament\Actions\ViewAction as ActionsViewAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class AgendamentosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 🗓️ Data e horário
                TextColumn::make('data')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable()
                    ->icon('heroicon-o-calendar'),
                    
                TextColumn::make('horario')
                    ->label('Hora')
                    ->time('H:i')
                    ->sortable()
                    ->icon('heroicon-o-clock'),

                // 👤 Cliente
                TextColumn::make('cliente.nome')
                ->label('Cliente')
                ->sortable()
                ->searchable(),


                // ✂️ Serviço e profissional
                TextColumn::make('servico.nome')
                ->label('Serviço')
                ->sortable()
                ->searchable(),

                TextColumn::make('profissional.nome')
                    ->label('Profissional')
                    ->icon('heroicon-o-user-circle')
                    ->toggleable(),

                // 💰 Valor e pagamento
                TextColumn::make('valor')
                    ->label('Valor')
                    ->money('BRL', true)
                    ->alignEnd()
                    ->sortable(),


                // TextColumn::make('pagamento')
                //     ->badge()
                //     ->label('Pagamento')
                //     ->color(fn (string $state): string => match (strtolower($state)) {
                //         'pago', 'confirmado' => 'success',
                //         'pendente' => 'warning',
                //         'cancelado', 'falhou' => 'danger',
                //         default => 'gray',
                //     }),

                TextColumn::make('status')
                    ->badge()
                    ->label('Status')
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'pendente' => 'warning',
                        'confirmado' => 'success',
                        'cancelado' => 'danger',
                        default => 'gray',
                    }),
            ])

            ->defaultSort('data', 'desc')

            ->filters([
                // Exemplo: filtro rápido
                // SelectFilter::make('status')->options([
                //     'pendente' => 'Pendente',
                //     'confirmado' => 'Confirmado',
                //     'cancelado' => 'Cancelado',
                // ]),
            ])

            ->recordActions([
                // ActionsViewAction::make()->icon('heroicon-o-eye'),
                ActionsEditAction::make()->icon('heroicon-o-pencil-square'),


   Action::make('mudar_status')
    ->label('Mudar Status')
    ->form([
        Select::make('status')
            ->label('Novo Status')
            ->options([
                'pendente' => 'Pendente',
                'confirmado' => 'Confirmado',
                'cancelado' => 'Cancelado',
                'ausente' => 'Ausente',
                'finalizado' => 'Finalizado',
            ])
            ->required(),
    ])
    ->action(function ($record, array $data) {
        $record->status = $data['status'];
        $record->save();
    }),
            ])

            ->toolbarActions([
                ActionsBulkActionGroup::make([
                    ActionsDeleteBulkAction::make()
                        ->label('Excluir selecionados')
                        ->icon('heroicon-o-trash'),
                ]),
            ]);
    }
}
