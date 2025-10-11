<?php

namespace App\Filament\Resources\Profissionals;

use App\Filament\Resources\Profissionals\Pages\CreateProfissional;
use App\Filament\Resources\Profissionals\Pages\EditProfissional;
use App\Filament\Resources\Profissionals\Pages\ListProfissionals;
use App\Filament\Resources\Profissionals\Pages\ViewProfissional;
use App\Filament\Resources\Profissionals\Schemas\ProfissionalForm;
use App\Filament\Resources\Profissionals\Schemas\ProfissionalInfolist;
use App\Filament\Resources\Profissionals\Tables\ProfissionalsTable;
use App\Models\Profissional;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProfissionalResource extends Resource
{
    protected static ?string $model = Profissional::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Profissional';

    public static function form(Schema $schema): Schema
    {
        return ProfissionalForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProfissionalInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProfissionalsTable::configure($table);
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
            'index' => ListProfissionals::route('/'),
            'create' => CreateProfissional::route('/create'),
            'view' => ViewProfissional::route('/{record}'),
            'edit' => EditProfissional::route('/{record}/edit'),
        ];
    }
}
