<?php

namespace App\Filament\Resources\MaintenanceReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MaintenanceReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Laporan Maintenance')
                ->description('Ringkas hasil pekerjaan lapangan dan tindak lanjut.')
                ->icon('heroicon-o-document-chart-bar')
                ->schema([
                    Select::make('divisis_id')->label('Divisi')->relationship('divisi', 'nama_divisi')->searchable()->preload()->native(false)->required(),
                    Select::make('unit_id')->label('Unit / Equipment')->relationship('unit', 'unit_name')->searchable()->preload()->native(false)->placeholder('Opsional'),
                    Select::make('work_order_id')->label('Work Order Terkait')->relationship('workOrder', 'wo_number')->searchable()->preload()->native(false)->placeholder('Opsional'),
                    TextInput::make('title')->label('Judul Laporan')->required()->maxLength(255)->placeholder('Perbaikan Pompa Transfer #2')->columnSpanFull(),
                    Textarea::make('description')->label('Isi Laporan')->rows(4)->placeholder('Uraian temuan, tindakan, dan rekomendasi...')->columnSpanFull(),
                ])->columns(2),

            Section::make('Biaya & Publikasi')
                ->icon('heroicon-o-banknotes')
                ->schema([
                    DatePicker::make('report_date')->label('Tanggal Laporan')->required()->native(false),
                    TextInput::make('cost')->label('Biaya (Rp)')->numeric()->prefix('Rp')->default(0),
                    Select::make('status')->label('Status')->options(['draft' => 'Draft', 'published' => 'Published'])->default('draft')->native(false)->required(),
                ])->columns(3),
        ]);
    }
}
