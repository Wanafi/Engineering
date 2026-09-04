<?php

namespace App\Filament\Resources\MaintenanceReports\Pages;

use App\Filament\Resources\MaintenanceReports\MaintenanceReportResource;
use App\Support\ReportPrintActions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMaintenanceReports extends ListRecords
{
    protected static string $resource = MaintenanceReportResource::class;
    protected function getHeaderActions(): array { return [ReportPrintActions::listPreview("maintenance_reports"), ReportPrintActions::listPrint("maintenance_reports"), CreateAction::make()]; }
}
