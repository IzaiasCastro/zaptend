<?php

namespace App\Filament\Resources\Horarios\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class HorarioInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('duracao_atendimento')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('domingo')
                    ->placeholder('-'),
                TextEntry::make('segunda')
                    ->placeholder('-'),
                TextEntry::make('terca')
                    ->placeholder('-'),
                TextEntry::make('quarta')
                    ->placeholder('-'),
                TextEntry::make('quinta')
                    ->placeholder('-'),
                TextEntry::make('sexta')
                    ->placeholder('-'),
                TextEntry::make('sabado')
                    ->placeholder('-'),
                TextEntry::make('profissional_id')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
