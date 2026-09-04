<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Department')
                ->icon('heroicon-o-building-office')
                ->schema([
                    TextInput::make('name')
                        ->label('Nama Department')
                        ->required()
                        ->maxLength(100)
                        ->unique(ignoreRecord: true)
                        ->placeholder('Engineering'),

                    TextInput::make('code')
                        ->label('Kode')
                        ->maxLength(20)
                        ->unique(ignoreRecord: true)
                        ->placeholder('ENG'),

                    Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true)
                        ->columnSpanFull(),

                    Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(3)
                        ->placeholder('Deskripsi department...')
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }
}
