<?php

namespace App\Filament\Resources\Assistentes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AssistenteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->required(),
                Textarea::make('gpt_api_key')
                    ->columnSpanFull(),
                Textarea::make('gpt_assistent_id')
                    ->columnSpanFull(),
                Textarea::make('prompt')
                    ->columnSpanFull(),
            ]);
    }
}
