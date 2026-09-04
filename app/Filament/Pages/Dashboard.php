<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\EngineeringCalendarWidget;
use App\Filament\Widgets\EngineeringStatsWidget;
use App\Filament\Widgets\ScheduleTrendChart;
use App\Filament\Widgets\UpcomingSchedulesWidget;
use App\Filament\Widgets\WorkOrderStatusChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Engineering Overview';

    public function getColumns(): int | array
    {
        return 12;
    }

    public function getWidgets(): array
    {
        return [
            EngineeringStatsWidget::class,
            WorkOrderStatusChart::class,
            ScheduleTrendChart::class,
            EngineeringCalendarWidget::class,
            UpcomingSchedulesWidget::class,
        ];
    }

    public function getVisibleWidgets(): array
    {
        return $this->filterVisibleWidgets($this->getWidgets());
    }
}
