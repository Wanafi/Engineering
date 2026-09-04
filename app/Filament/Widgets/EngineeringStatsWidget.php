<?php

namespace App\Filament\Widgets;

use App\Models\ChecklistExecution;
use App\Models\MaintenanceReport;
use App\Models\Unit;
use App\Models\WorkOrder;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EngineeringStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected int | string | array $columnSpan = 'full';

    protected ?string $pollingInterval = '30s';

    protected function getColumns(): int | array | null
    {
        return [
            'default' => 1,
            'sm' => 2,
            'md' => 2,
            'lg' => 4,
            'xl' => 4,
        ];
    }

    protected function getStats(): array
    {
        $totalUnits = Unit::count();
        $activeWO = WorkOrder::whereIn('status', ['open', 'in_progress'])->count();
        $pendingChecklists = ChecklistExecution::whereIn('status', ['draft', 'submitted', 'supervisor_approved'])->count();
        $completedMaintenance = MaintenanceReport::where('status', 'published')->count() + ChecklistExecution::where('status', 'completed')->count();

        return [
            Stat::make('Total Units', (string) $totalUnits)
                ->description('Registered equipment')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('info')
                ->icon('heroicon-o-building-office-2')
                ->chart([2, 4, 6, 8, 7, $totalUnits]),

            Stat::make('Active Work Orders', (string) $activeWO)
                ->description($activeWO > 0 ? 'Requires attention' : 'All clear')
                ->descriptionIcon($activeWO > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($activeWO > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-wrench-screwdriver')
                ->chart([1, 3, 2, 5, 4, $activeWO]),

            Stat::make('Pending Checklists', (string) $pendingChecklists)
                ->description('Awaiting approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingChecklists > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-clipboard-document-check')
                ->chart([3, 2, 4, 3, 5, $pendingChecklists]),

            Stat::make('Completed Maintenance', (string) $completedMaintenance)
                ->description('Published reports + done checks')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->chart([1, 2, 3, 4, 5, $completedMaintenance]),
        ];
    }
}
