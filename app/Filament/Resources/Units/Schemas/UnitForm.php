<?php

namespace App\Filament\Resources\Units\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('divisis_id')
                    ->label('Divisi')
                    ->relationship('divisis', 'nama_divisi')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('unit_code')
                    ->label('Kode Unit')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50)
                    ->placeholder('Contoh: ESC-001'),

                TextInput::make('unit_name')
                    ->label('Nama Unit')
                    ->required()
                    ->maxLength(150)
                    ->placeholder('Contoh: Escalator 1'),

                TextInput::make('location')
                    ->label('Lokasi')
                    ->maxLength(150)
                    ->placeholder('Contoh: Main Lobby'),

                TextInput::make('floor')
                    ->label('Lantai')
                    ->maxLength(50)
                    ->placeholder('Contoh: GF'),

                TextInput::make('area')
                    ->label('Area')
                    ->maxLength(100)
                    ->placeholder('Contoh: East Wing'),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active')
                    ->required(),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}