<?php

namespace App\Filament\Resources\Schedules\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Judul')->searchable()->sortable()->weight('bold'),
                TextColumn::make('divisi.nama_divisi')->label('Divisi')->badge()->sortable(),
                TextColumn::make('unit.unit_name')->label('Unit')->placeholder('-')->sortable(),
                TextColumn::make('assignedUser.name')->label('Assigned')->placeholder('-'),
                TextColumn::make('schedule_date')->label('Tanggal')->date('d M Y')->sortable(),
                TextColumn::make('start_time')->label('Jam')->time('H:i')->placeholder('-'),
                TextColumn::make('status')->label('Status')->badge()->color(fn (string $state): string => match ($state) {
                    'scheduled' => 'info',
                    'in_progress' => 'warning',
                    'completed' => 'success',
                    'cancelled' => 'gray',
                    'overdue' => 'danger',
                    default => 'gray',
                })->formatStateUsing(fn (string $state): string => match ($state) {
                    'scheduled' => 'Terjadwal',
                    'in_progress' => 'Proses',
                    'completed' => 'Selesai',
                    'cancelled' => 'Batal',
                    'overdue' => 'Terlambat',
                    default => ucfirst($state),
                }),
            ])
            ->filters([
                SelectFilter::make('divisis_id')->label('Divisi')->relationship('divisi', 'nama_divisi')->searchable()->preload(),
                SelectFilter::make('status')->label('Status')->options([
                    'scheduled' => 'Terjadwal',
                    'in_progress' => 'Proses',
                    'completed' => 'Selesai',
                    'cancelled' => 'Batal',
                    'overdue' => 'Terlambat',
                ]),
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('schedule_date', 'desc');
    }
}
