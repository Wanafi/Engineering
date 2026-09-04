<?php

namespace App\Filament\Resources\MaintenanceReports;

use App\Filament\Resources\MaintenanceReports\Pages\CreateMaintenanceReport;
use App\Filament\Resources\MaintenanceReports\Pages\EditMaintenanceReport;
use App\Filament\Resources\MaintenanceReports\Pages\ListMaintenanceReports;
use App\Filament\Resources\MaintenanceReports\Pages\ViewMaintenanceReport;
use App\Filament\Resources\MaintenanceReports\Schemas\MaintenanceReportForm;
use App\Filament\Resources\MaintenanceReports\Tables\MaintenanceReportsTable;
use App\Models\MaintenanceReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MaintenanceReportResource extends Resource
{
    protected static ?string $model = MaintenanceReport::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;
    protected static string|UnitEnum|null $navigationGroup = "Maintenance";
    protected static ?string $modelLabel = "Maintenance Report";
    protected static ?string $pluralModelLabel = "Maintenance Reports";
    protected static ?int $navigationSort = 0;
    public static function form(Schema $schema): Schema { return MaintenanceReportForm::configure($schema); }
    public static function table(Table $table): Table { return MaintenanceReportsTable::configure($table); }
    public static function getPages(): array { return ["index" => ListMaintenanceReports::route("/"), "create" => CreateMaintenanceReport::route("/create"), "edit" => EditMaintenanceReport::route("/{record}/edit"), "view" => ViewMaintenanceReport::route("/{record}")]; }
    public static function getRelations(): array { return []; }
}
