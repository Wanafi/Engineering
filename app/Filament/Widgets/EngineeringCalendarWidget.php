<?php

namespace App\Filament\Widgets;

use Guava\Calendar\Filament\CalendarWidget as GuavaCalendarWidget;
use Guava\Calendar\ValueObjects\CalendarEvent;
use Guava\Calendar\ValueObjects\FetchInfo;
use App\Models\Schedule;
use App\Models\Event;
use App\Models\WorkOrder;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class EngineeringCalendarWidget extends GuavaCalendarWidget
{
    protected int | string | array $columnSpan = 'full';

    protected bool $eventClickEnabled = true;

    public function getEvents(FetchInfo $info): Collection | array
    {
        $schedules = Schedule::with(['divisi', 'unit', 'assignedUser'])
            ->whereDate('schedule_date', '>=', $info->start)
            ->whereDate('schedule_date', '<=', $info->end)
            ->get()
            ->map(function (Schedule $s) {
                $date = $s->schedule_date?->format('Y-m-d');
                $startTime = $s->start_time ? Carbon::parse($s->start_time)->format('H:i:s') : null;
                $endTime = $s->end_time ? Carbon::parse($s->end_time)->format('H:i:s') : null;
                $start = $startTime ? Carbon::parse($date . ' ' . $startTime) : Carbon::parse($date);
                $end = $endTime ? Carbon::parse($date . ' ' . $endTime) : $start->copy()->addHour();
                $allDay = $startTime === null;

                return CalendarEvent::make($s)
                    ->title('[Schedule] ' . $s->title)
                    ->start($start)
                    ->end($end)
                    ->allDay($allDay)
                    ->backgroundColor($s->divisi?->warna ?? '#3498db')
                    ->extendedProps([
                        'type' => 'Schedule',
                        'status' => $s->status,
                        'division' => $s->divisi?->nama_divisi ?? 'Umum',
                        'unit' => $s->unit?->unit_name ?? '-',
                        'assigned' => $s->assignedUser?->name ?? '-',
                        'description' => $s->description ?? '',
                    ]);
            });

        $events = Event::with('divisi')
            ->whereDate('tanggal_mulai', '<=', $info->end)
            ->whereDate('tanggal_selesai', '>=', $info->start)
            ->get()
            ->map(function (Event $e) {
                $allDay = (bool) $e->all_day;
                return CalendarEvent::make($e)
                    ->title('[Event] ' . $e->judul)
                    ->start($e->tanggal_mulai)
                    ->end($e->tanggal_selesai ?? $e->tanggal_mulai->copy()->addHour())
                    ->allDay($allDay)
                    ->backgroundColor($e->divisi?->warna ?? '#9ca3af')
                    ->extendedProps([
                        'type' => 'Event',
                        'status' => 'Event',
                        'division' => $e->divisi?->nama_divisi ?? 'Umum',
                        'unit' => '-',
                        'assigned' => '-',
                        'description' => $e->deskripsi ?? '',
                    ]);
            });

        $workOrders = WorkOrder::with(['divisi', 'unit', 'assignedTo'])
            ->whereNotNull('scheduled_date')
            ->whereDate('scheduled_date', '>=', $info->start)
            ->whereDate('scheduled_date', '<=', $info->end)
            ->get()
            ->map(function (WorkOrder $wo) {
                $start = Carbon::parse($wo->scheduled_date);
                return CalendarEvent::make($wo)
                    ->title('[WO] ' . $wo->wo_number)
                    ->start($start)
                    ->end($start->copy()->addHour())
                    ->allDay(true)
                    ->backgroundColor('#e74c3c')
                    ->extendedProps([
                        'type' => 'Work Order',
                        'status' => $wo->status,
                        'division' => $wo->divisi?->nama_divisi ?? 'Umum',
                        'unit' => $wo->unit?->unit_name ?? '-',
                        'assigned' => $wo->assignedTo?->name ?? '-',
                        'description' => $wo->description ?? '',
                    ]);
            });

        return $schedules->concat($events)->concat($workOrders);
    }

    protected string | HtmlString | null | bool $heading = 'Monthly Calendar';

    public function config(): array
    {
        return [
            'firstDay' => 1,
            'headerToolbar' => [
                'left' => 'prev,next today',
                'center' => 'title',
                'right' => 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
            ],
            'initialView' => 'dayGridMonth',
            'selectable' => false,
            'editable' => false,
            'dayMaxEvents' => 3,
            'fixedWeekCount' => false,
            'showNonCurrentDates' => true,
            'height' => 'auto',
            'contentHeight' => 'auto',
            'expandRows' => true,
            'handleWindowResize' => true,
            'eventDisplay' => 'block',
            'displayEventTime' => false,
            'weekNumbers' => false,
            'navLinks' => false,
        ];
    }
}
