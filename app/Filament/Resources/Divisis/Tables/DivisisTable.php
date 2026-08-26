<?php

namespace App\Filament\Resources\Divisis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DivisisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ColorColumn::make('warna')
                    ->label('Warna'),
 
                TextColumn::make('nama_divisi')
                    ->label('Nama Divisi')
                    ->searchable()
                    ->sortable(),
 
                TextColumn::make('events_count')
                    ->label('Jumlah Event')
                    ->counts('events')
                    ->sortable(),
 
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
 
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
