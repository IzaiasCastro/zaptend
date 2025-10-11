<?php

namespace App\Filament\Resources\Assistentes\Pages;

use App\Filament\Resources\Assistentes\AssistenteResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAssistente extends EditRecord
{
    protected static string $resource = AssistenteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
