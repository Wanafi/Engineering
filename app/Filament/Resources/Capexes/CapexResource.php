<?php

namespace App\Filament\Resources\Capexes;

use App\Filament\Resources\Capexes\Pages\CreateCapex;
use App\Filament\Resources\Capexes\Pages\EditCapex;
use App\Filament\Resources\Capexes\Pages\ListCapexes;
use App\Filament\Resources\Capexes\Pages\ViewCapex;
use App\Filament\Resources\Capexes\Schemas\CapexForm;
use App\Filament\Resources\Capexes\Tables\CapexesTable;
use App\Models\Capex;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CapexResource extends Resource
{
    protected static ?string $model = Capex::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;
    protected static string|UnitEnum|null $navigationGroup = "Finance";
    protected static ?string $modelLabel = "CAPEX";
    protected static ?string $pluralModelLabel = "CAPEX";
    protected static ?int $navigationSort = 0;
    public static function form(Schema $schema): Schema { return CapexForm::configure($schema); }
    public static function table(Table $table): Table { return CapexesTable::configure($table); }
    public static function getPages(): array { return ["index" => ListCapexes::route("/"), "create" => CreateCapex::route("/create"), "edit" => EditCapex::route("/{record}/edit"), "view" => ViewCapex::route("/{record}")]; }
    public static function getRelations(): array { return []; }
}
