<?php

namespace App\Filament\Resources\Units\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Unit')
                    ->description('Kode dan nama unit/equipment.')
                    ->icon('heroicon-o-building-office-2')
                    ->schema([
                        Select::make('divisis_id')
                            ->label('Divisi')
                            ->relationship('divisis', 'nama_divisi')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required(),

                        TextInput::make('unit_code')
                            ->label('Kode Unit')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50)
                            ->placeholder('ESC-001'),

                        TextInput::make('unit_name')
                            ->label('Nama Unit')
                            ->required()
                            ->maxLength(150)
                            ->placeholder('Escalator 1')
                            ->columnSpanFull(),

                        Select::make('status')
                            ->label('Status Operasional')
                            ->options([
                                'active' => 'Aktif',
                                'inactive' => 'Nonaktif',
                            ])
                            ->default('active')
                            ->native(false)
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Lokasi Penempatan')
                    ->description('Lokasi fisik unit berada.')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        TextInput::make('location')
                            ->label('Lokasi')
                            ->maxLength(150)
                            ->placeholder('Main Lobby'),

                        TextInput::make('floor')
                            ->label('Lantai')
                            ->maxLength(50)
                            ->placeholder('GF / L1'),

                        TextInput::make('area')
                            ->label('Area / Zona')
                            ->maxLength(100)
                            ->placeholder('East Wing')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Keterangan')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Textarea::make('description')
                            ->label('Deskripsi / Catatan')
                            ->rows(3)
                            ->placeholder('Keterangan tambahan unit...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
