<?php

namespace App\Filament\Resources\MaintenanceReports\Pages;

use App\Filament\Resources\MaintenanceReports\MaintenanceReportResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMaintenanceReport extends ViewRecord
{
    protected static string $resource = MaintenanceReportResource::class;
    protected function getHeaderActions(): array { return [EditAction::make()]; }
}
