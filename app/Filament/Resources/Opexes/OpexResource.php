<?php

namespace App\Filament\Resources\Opexes;

use App\Filament\Resources\Opexes\Pages\CreateOpex;
use App\Filament\Resources\Opexes\Pages\EditOpex;
use App\Filament\Resources\Opexes\Pages\ListOpexes;
use App\Filament\Resources\Opexes\Pages\ViewOpex;
use App\Filament\Resources\Opexes\Schemas\OpexForm;
use App\Filament\Resources\Opexes\Tables\OpexesTable;
use App\Models\Opex;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class OpexResource extends Resource
{
    protected static ?string $model = Opex::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;
    protected static string|UnitEnum|null $navigationGroup = "Finance";
    protected static ?string $modelLabel = "OPEX";
    protected static ?string $pluralModelLabel = "OPEX";
    protected static ?int $navigationSort = 1;
    public static function form(Schema $schema): Schema { return OpexForm::configure($schema); }
    public static function table(Table $table): Table { return OpexesTable::configure($table); }
    public static function getPages(): array { return ["index" => ListOpexes::route("/"), "create" => CreateOpex::route("/create"), "edit" => EditOpex::route("/{record}/edit"), "view" => ViewOpex::route("/{record}")]; }
    public static function getRelations(): array { return []; }
}
