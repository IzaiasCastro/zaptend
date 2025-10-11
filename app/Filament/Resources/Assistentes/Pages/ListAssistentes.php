<?php

namespace App\Filament\Resources\Assistentes\Pages;

use App\Filament\Resources\Assistentes\AssistenteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAssistentes extends ListRecords
{
    protected static string $resource = AssistenteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
