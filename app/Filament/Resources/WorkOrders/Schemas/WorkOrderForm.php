<?php

namespace App\Filament\Resources\WorkOrders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WorkOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Work Order')
                ->icon('heroicon-o-wrench-screwdriver')
                ->schema([
                    TextInput::make('wo_number')
                        ->label('No. WO')
                        ->placeholder('Auto generate jika kosong')
                        ->maxLength(50)
                        ->unique(ignoreRecord: true),

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
                        ->placeholder('Pilih unit'),

                    Select::make('reported_by')
                        ->label('Pelapor')
                        ->relationship('reportedBy', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),

                    Select::make('assigned_to')
                        ->label('Assigned To')
                        ->relationship('assignedTo', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false),
                ])
                ->columns(2),

            Section::make('Detail Pekerjaan')
                ->icon('heroicon-o-clipboard-document-list')
                ->schema([
                    Select::make('priority')
                        ->label('Prioritas')
                        ->options([
                            'low' => 'Low',
                            'medium' => 'Medium',
                            'high' => 'High',
                            'emergency' => 'Emergency',
                        ])
                        ->default('medium')
                        ->native(false)
                        ->required(),

                    Select::make('type')
                        ->label('Tipe')
                        ->options([
                            'corrective' => 'Corrective',
                            'preventive' => 'Preventive',
                            'breakdown' => 'Breakdown',
                            'request' => 'Request',
                        ])
                        ->default('corrective')
                        ->native(false)
                        ->required(),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'open' => 'Open',
                            'in_progress' => 'In Progress',
                            'completed' => 'Completed',
                            'overdue' => 'Overdue',
                            'cancelled' => 'Cancelled',
                        ])
                        ->default('open')
                        ->native(false)
                        ->required(),

                    DatePicker::make('scheduled_date')
                        ->label('Tanggal Jadwal')
                        ->native(false),

                    DateTimePicker::make('started_at')
                        ->label('Mulai')
                        ->native(false)
                        ->seconds(false),

                    DateTimePicker::make('completed_at')
                        ->label('Selesai')
                        ->native(false)
                        ->seconds(false),

                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->required()
                        ->rows(3)
                        ->placeholder('Detail kerusakan / pekerjaan...')
                        ->columnSpanFull(),

                    Textarea::make('notes')
                        ->label('Catatan')
                        ->rows(2)
                        ->placeholder('Catatan tambahan...')
                        ->columnSpanFull(),
                ])
                ->columns(['default' => 1, 'md' => 2, 'lg' => 3]),
        ]);
    }
}
