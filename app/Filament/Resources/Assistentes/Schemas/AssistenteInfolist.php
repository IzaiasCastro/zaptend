<?php

namespace App\Filament\Resources\Assistentes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AssistenteInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nome'),
                TextEntry::make('gpt_api_key')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('gpt_assistent_id')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('prompt')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
