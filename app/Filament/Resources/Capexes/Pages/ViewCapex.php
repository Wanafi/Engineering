<?php

namespace App\Filament\Resources\Capexes\Pages;

use App\Filament\Resources\Capexes\CapexResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCapex extends ViewRecord
{
    protected static string $resource = CapexResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make()];
    }
}
