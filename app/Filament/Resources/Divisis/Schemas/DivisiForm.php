<?php

namespace App\Filament\Resources\Divisis\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DivisiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Divisi')
                    ->schema([
                        TextInput::make('nama_divisi')
                            ->label('Nama Divisi')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->live(onBlur: true),
 
                       ColorPicker::make('warna')
                            ->label('Warna Divisi')
                            ->required()
                            ->default('#3498db')
                            ->helperText('Warna ini akan dipakai untuk menandai event divisi ini di kalender.'),
 
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->helperText('Nonaktifkan jika divisi tidak lagi dipakai, tanpa menghapus datanya.'),
                    ])
                    ->columns(2),
            ]);
    }
}
