<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\EngineeringCalendarWidget;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class EngineeringCalendar extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;

    protected static string|UnitEnum|null $navigationGroup = 'Operations';

    protected static ?string $navigationLabel = 'Calendar';

    protected static ?string $title = 'Engineering Calendar';

    protected static ?int $navigationSort = 0;

    protected function getHeaderWidgets(): array
    {
        return [
            EngineeringCalendarWidget::class,
        ];
    }
}
