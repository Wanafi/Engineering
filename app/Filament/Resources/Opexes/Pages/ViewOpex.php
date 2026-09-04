<?php

namespace App\Filament\Resources\Opexes\Pages;

use App\Filament\Resources\Opexes\OpexResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOpex extends ViewRecord
{
    protected static string $resource = OpexResource::class;
    protected function getHeaderActions(): array { return [EditAction::make()]; }
}
