<?php

namespace App\Filament\Resources\Opexes\Pages;

use App\Filament\Resources\Opexes\OpexResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOpex extends EditRecord
{
    protected static string $resource = OpexResource::class;
    protected function getHeaderActions(): array { return [ViewAction::make(), DeleteAction::make()]; }
}
