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
                    ->description('Kelola nama divisi engineering dan warna penanda.')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        TextInput::make('nama_divisi')
                            ->label('Nama Divisi')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->placeholder('Contoh: HVAC / Escalator & Lift')
                            ->columnSpan(1),

                        ColorPicker::make('warna')
                            ->label('Warna Indikator')
                            ->required()
                            ->default('#3498db')
                            ->columnSpan(1),

                        Toggle::make('is_active')
                            ->label('Divisi Aktif')
                            ->default(true)
                            ->helperText('Nonaktifkan jika divisi sudah tidak beroperasi.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
