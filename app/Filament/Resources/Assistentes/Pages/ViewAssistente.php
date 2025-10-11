<?php

namespace App\Filament\Resources\Assistentes\Pages;

use App\Filament\Resources\Assistentes\AssistenteResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAssistente extends ViewRecord
{
    protected static string $resource = AssistenteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
