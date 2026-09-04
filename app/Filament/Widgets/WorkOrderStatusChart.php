<?php

namespace App\Filament\Widgets;

use App\Models\WorkOrder;
use Filament\Widgets\ChartWidget;

class WorkOrderStatusChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Work Order — by Status';

    protected ?string $description = 'Total Work Order per status.';

    protected ?string $maxHeight = '260px';

    protected int | string | array $columnSpan = ['default' => 12, 'lg' => 6];

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $statuses = ['open' => 'Open', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'overdue' => 'Overdue', 'cancelled' => 'Cancelled'];
        $colors = ['open' => '#f59e0b', 'in_progress' => '#3b82f6', 'completed' => '#22c55e', 'overdue' => '#ef4444', 'cancelled' => '#9ca3af'];
        $counts = [];
        foreach (array_keys($statuses) as $key) {
            $counts[$key] = WorkOrder::where('status', $key)->count();
        }

        return [
            'labels' => array_values($statuses),
            'datasets' => [[
                'label' => 'Work Orders',
                'data' => array_values($counts),
                'backgroundColor' => array_values(array_map(fn ($k) => $colors[$k], array_keys($statuses))),
                'borderWidth' => 0,
            ]],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => ['position' => 'bottom', 'labels' => ['usePointStyle' => true, 'boxWidth' => 8, 'padding' => 16]],
            ],
            'cutout' => '64%',
            'maintainAspectRatio' => false,
        ];
    }
}
