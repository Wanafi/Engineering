<?php

namespace App\Filament\Resources\Capexes\Pages;

use App\Filament\Resources\Capexes\CapexResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCapexes extends ListRecords
{
    protected static string $resource = CapexResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
