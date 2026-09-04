<?php

namespace App\Filament\Widgets;

use App\Models\Schedule;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class UpcomingSchedulesWidget extends TableWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 12;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Schedule::query()
                    ->with(['divisi', 'unit'])
                    ->whereDate('schedule_date', '>=', today()->subDays(2))
                    ->latest('schedule_date')
            )
            ->heading('Upcoming Schedules')
            ->description('Jadwal terdekat — tanggal, unit, divisi, dan status.')
            ->columns([
                TextColumn::make('schedule_date')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable()
                    ->icon('heroicon-m-calendar')
                    ->extraAttributes(['class' => 'whitespace-nowrap']),

                TextColumn::make('title')
                    ->label('Task')
                    ->searchable()
                    ->weight('semibold')
                    ->limit(36)
                    ->wrap(),

                TextColumn::make('unit.unit_name')
                    ->label('Unit')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visibleFrom('md'),

                TextColumn::make('divisi.nama_divisi')
                    ->label('Division')
                    ->badge()
                    ->color('gray')
                    ->visibleFrom('lg'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'info',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'cancelled', 'overdue' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'scheduled' => 'Scheduled',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'overdue' => 'Overdue',
                        default => ucfirst($state),
                    }),
            ])
            ->striped()
            ->paginated([5, 10])
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading('No upcoming schedules')
            ->emptyStateDescription('All clear — no jobs scheduled in this window.')
            ->emptyStateIcon('heroicon-o-calendar-days');
    }
}
