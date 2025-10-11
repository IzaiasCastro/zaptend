<?php

namespace App\Filament\Resources\Assistentes;

use App\Filament\Resources\Assistentes\Pages\CreateAssistente;
use App\Filament\Resources\Assistentes\Pages\EditAssistente;
use App\Filament\Resources\Assistentes\Pages\ListAssistentes;
use App\Filament\Resources\Assistentes\Pages\ViewAssistente;
use App\Filament\Resources\Assistentes\Schemas\AssistenteForm;
use App\Filament\Resources\Assistentes\Schemas\AssistenteInfolist;
use App\Filament\Resources\Assistentes\Tables\AssistentesTable;
use App\Models\Assistente;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AssistenteResource extends Resource
{
    protected static ?string $model = Assistente::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Assistente';

    public static function form(Schema $schema): Schema
    {
        return AssistenteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AssistenteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssistentesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAssistentes::route('/'),
            'create' => CreateAssistente::route('/create'),
            'view' => ViewAssistente::route('/{record}'),
            'edit' => EditAssistente::route('/{record}/edit'),
        ];
    }
}
