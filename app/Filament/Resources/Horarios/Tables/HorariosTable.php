<?php

namespace App\Filament\Resources\Horarios\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HorariosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('duracao_atendimento')
                    ->time()
                    ->sortable(),
                TextColumn::make('domingo')
                    ->searchable(),
                TextColumn::make('segunda')
                    ->searchable(),
                TextColumn::make('terca')
                    ->searchable(),
                TextColumn::make('quarta')
                    ->searchable(),
                TextColumn::make('quinta')
                    ->searchable(),
                TextColumn::make('sexta')
                    ->searchable(),
                TextColumn::make('sabado')
                    ->searchable(),
                TextColumn::make('profissional_id')
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
