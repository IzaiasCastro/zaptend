<?php

namespace App\Filament\Resources\Horarios\Pages;

use App\Filament\Resources\Horarios\HorarioResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHorario extends CreateRecord
{
    protected static string $resource = HorarioResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
