<?php

namespace App\Filament\Resources\Capexes\Pages;

use App\Filament\Resources\Capexes\CapexResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCapex extends EditRecord
{
    protected static string $resource = CapexResource::class;

    protected function getHeaderActions(): array
    {
        return [ViewAction::make(), DeleteAction::make()];
    }
}
