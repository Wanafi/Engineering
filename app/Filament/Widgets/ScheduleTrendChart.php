<?php

namespace App\Filament\Widgets;

use App\Models\ChecklistExecution;
use App\Models\WorkOrder;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ScheduleTrendChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Work Orders & Checklists — 14-Day Trend';

    protected ?string $description = 'Line trend per hari (area).';

    protected ?string $maxHeight = '260px';

    protected int | string | array $columnSpan = ['default' => 12, 'lg' => 6];

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $days = collect(range(13, 0))->map(fn ($i) => Carbon::today()->subDays($i));
        $labels = $days->map(fn (Carbon $d) => $d->format('d M'))->all();
        $wo = $days->map(fn (Carbon $d) => WorkOrder::whereDate('created_at', $d)->count())->all();
        $cl = $days->map(fn (Carbon $d) => ChecklistExecution::whereDate('created_at', $d)->count())->all();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Work Orders',
                    'data' => $wo,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59,130,246,0.12)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 0,
                ],
                [
                    'label' => 'Checklists',
                    'data' => $cl,
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34,197,94,0.10)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 0,
                ],
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['position' => 'bottom', 'labels' => ['usePointStyle' => true, 'boxWidth' => 8, 'padding' => 16]]],
            'scales' => [
                'x' => ['grid' => ['display' => false]],
                'y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]],
            ],
            'maintainAspectRatio' => false,
        ];
    }
}
