<?php

namespace App\Filament\Resources\Opexes\Pages;

use App\Filament\Resources\Opexes\OpexResource;
use App\Support\ReportPrintActions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOpexes extends ListRecords
{
    protected static string $resource = OpexResource::class;
    protected function getHeaderActions(): array { return [ReportPrintActions::listPreview("opex"), ReportPrintActions::listPrint("opex"), CreateAction::make()]; }
}
