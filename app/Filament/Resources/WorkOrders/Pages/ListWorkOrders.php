<?php

namespace App\Filament\Resources\WorkOrders\Pages;

use App\Filament\Resources\WorkOrders\WorkOrderResource;
use App\Support\ReportPrintActions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkOrders extends ListRecords
{
    protected static string $resource = WorkOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [ReportPrintActions::listPreview("work_orders"), ReportPrintActions::listPrint("work_orders"), CreateAction::make()];
    }
}
