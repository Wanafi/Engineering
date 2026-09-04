<?php

namespace App\Filament\Resources\Opexes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OpexForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi OPEX')
                ->description('Detail belanja operasional rutin.')
                ->icon('heroicon-o-currency-dollar')
                ->schema([
                    Select::make('divisis_id')->label('Divisi')->relationship('divisi', 'nama_divisi')->searchable()->preload()->native(false)->required(),
                    TextInput::make('title')->label('Judul')->required()->maxLength(255)->placeholder('Ganti filter AHU bulanan')->columnSpanFull(),
                    Textarea::make('description')->label('Rincian Belanja')->rows(3)->placeholder('Uraian item, qty, kebutuhan...')->columnSpanFull(),
                ])->columns(2),

            Section::make('Keuangan & Status')
                ->icon('heroicon-o-banknotes')
                ->schema([
                    TextInput::make('amount')->label('Nilai (Rp)')->numeric()->prefix('Rp')->required()->default(0),
                    DatePicker::make('expense_date')->label('Tanggal Kebutuhan')->native(false)->placeholder('Pilih tanggal'),
                    Select::make('status')->label('Status')->options(['draft' => 'Draft', 'submitted' => 'Submitted', 'approved' => 'Approved', 'rejected' => 'Rejected'])->default('draft')->native(false)->required(),
                ])->columns(3),
        ]);
    }
}
