<?php

namespace App\Filament\Resources\Agendamentos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AgendamentosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('data')
                    ->date()
                    ->sortable(),
                TextColumn::make('horario')
                    ->time()
                    ->sortable(),
                TextColumn::make('nome')
                    ->searchable(),
                TextColumn::make('telefone')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('servico')
                    ->searchable(),
                TextColumn::make('observacao')
                    ->searchable(),
                TextColumn::make('profissional_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('servico_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('agenda_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('pagamento')
                    ->badge(),
                TextColumn::make('valor')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('metodo_pagamento')
                    ->searchable(),
                TextColumn::make('cliente_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
