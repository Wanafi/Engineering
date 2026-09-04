<?php

namespace App\Filament\Resources\Schedules\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Jadwal')
                ->icon('heroicon-o-calendar')
                ->schema([
                    TextInput::make('title')
                        ->label('Judul')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('PM Escalator 01')
                        ->columnSpanFull(),

                    Select::make('divisis_id')
                        ->label('Divisi')
                        ->relationship('divisi', 'nama_divisi')
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->required(),

                    Select::make('unit_id')
                        ->label('Unit')
                        ->relationship('unit', 'unit_name')
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->placeholder('Semua Unit'),

                    Select::make('assigned_user_id')
                        ->label('Teknisi / Assigned')
                        ->relationship('assignedUser', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->placeholder('Belum ditugaskan'),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'scheduled' => 'Terjadwal',
                            'in_progress' => 'Dalam Proses',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                            'overdue' => 'Terlambat',
                        ])
                        ->default('scheduled')
                        ->native(false)
                        ->required(),
                ])
                ->columns(2),

            Section::make('Waktu & Detail')
                ->icon('heroicon-o-clock')
                ->schema([
                    DatePicker::make('schedule_date')
                        ->label('Tanggal')
                        ->required()
                        ->native(false),

                    TimePicker::make('start_time')
                        ->label('Jam Mulai')
                        ->seconds(false)
                        ->native(false),

                    TimePicker::make('end_time')
                        ->label('Jam Selesai')
                        ->seconds(false)
                        ->native(false),

                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(3)
                        ->placeholder('Detail pekerjaan...')
                        ->columnSpanFull(),
                ])
                ->columns(3),
        ]);
    }
}
