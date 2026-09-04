<?php

namespace App\Filament\Resources\Capexes\Pages;

use App\Filament\Resources\Capexes\CapexResource;
use App\Support\ReportPrintActions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCapexes extends ListRecords
{
    protected static string $resource = CapexResource::class;

    protected function getHeaderActions(): array
    {
        return [ReportPrintActions::listPreview("capex"), ReportPrintActions::listPrint("capex"), CreateAction::make()];
    }
}
