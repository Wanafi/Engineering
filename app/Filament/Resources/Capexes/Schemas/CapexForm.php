<?php

namespace App\Filament\Resources\Capexes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CapexForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi CAPEX')
                ->description('Detail pengajuan belanja modal.')
                ->icon('heroicon-o-banknotes')
                ->schema([
                    Select::make('divisis_id')->label('Divisi')->relationship('divisi', 'nama_divisi')->searchable()->preload()->native(false)->required(),
                    TextInput::make('title')->label('Judul')->required()->maxLength(255)->placeholder('Ganti Chiller Unit A')->columnSpanFull(),
                    Textarea::make('description')->label('Deskripsi / Justifikasi')->rows(3)->placeholder('Alasan investasi, urgensi, estimasi ROI...')->columnSpanFull(),
                ])->columns(2),

            Section::make('Keuangan & Status')
                ->icon('heroicon-o-currency-dollar')
                ->schema([
                    TextInput::make('amount')->label('Nilai Pengajuan (Rp)')->numeric()->prefix('Rp')->required()->default(0),
                    DatePicker::make('expense_date')->label('Tanggal Rencana')->native(false)->placeholder('Pilih tanggal'),
                    Select::make('status')->label('Status Approval')->options(['draft' => 'Draft', 'submitted' => 'Submitted', 'approved' => 'Approved', 'rejected' => 'Rejected'])->default('draft')->native(false)->required(),
                ])->columns(3),
        ]);
    }
}
