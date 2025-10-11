<?php

namespace App\Filament\Resources\Agendamentos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AgendamentoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('data')
                    ->date(),
                TextEntry::make('horario')
                    ->time(),
                TextEntry::make('nome'),
                TextEntry::make('telefone'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('servico'),
                TextEntry::make('observacao')
                    ->placeholder('-'),
                TextEntry::make('profissional_id')
                    ->numeric(),
                TextEntry::make('servico_id')
                    ->numeric(),
                TextEntry::make('agenda_id')
                    ->numeric(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('pagamento')
                    ->badge(),
                TextEntry::make('valor')
                    ->numeric(),
                TextEntry::make('metodo_pagamento')
                    ->placeholder('-'),
                TextEntry::make('cliente_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
