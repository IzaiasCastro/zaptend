<?php

namespace App\Filament\Resources\Agendas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AgendaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('domingo')
                    ->columnSpanFull(),
                Textarea::make('segunda')
                    ->columnSpanFull(),
                Textarea::make('terca')
                    ->columnSpanFull(),
                Textarea::make('quarta')
                    ->columnSpanFull(),
                Textarea::make('quinta')
                    ->columnSpanFull(),
                Textarea::make('sexta')
                    ->columnSpanFull(),
                Textarea::make('sabado')
                    ->columnSpanFull(),
                TextInput::make('profissional_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
