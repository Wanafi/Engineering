<?php

namespace App\Filament\Resources\WorkOrders\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class WorkOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('wo_number')->label('WO Number')->searchable()->sortable()->copyable()->weight('bold'),
                TextColumn::make('divisi.nama_divisi')->label('Divisi')->badge()->sortable(),
                TextColumn::make('unit.unit_name')->label('Unit')->placeholder('-'),
                TextColumn::make('priority')->label('Priority')->badge()->color(fn (string $state): string => match ($state) {
                    'low' => 'gray',
                    'medium' => 'info',
                    'high' => 'warning',
                    'emergency' => 'danger',
                    default => 'gray',
                }),
                TextColumn::make('type')->label('Tipe')->badge(),
                TextColumn::make('status')->label('Status')->badge()->color(fn (string $state): string => match ($state) {
                    'open' => 'info',
                    'in_progress' => 'warning',
                    'completed' => 'success',
                    'overdue' => 'danger',
                    'cancelled' => 'gray',
                    default => 'gray',
                }),
                TextColumn::make('scheduled_date')->label('Jadwal')->date('d M Y')->sortable()->placeholder('-'),
                TextColumn::make('assignedTo.name')->label('Assigned')->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('divisis_id')->label('Divisi')->relationship('divisi', 'nama_divisi')->searchable()->preload(),
                SelectFilter::make('priority')->label('Priority')->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                    'emergency' => 'Emergency',
                ]),
                SelectFilter::make('status')->label('Status')->options([
                    'open' => 'Open',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                    'overdue' => 'Overdue',
                    'cancelled' => 'Cancelled',
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
            ->defaultSort('created_at', 'desc');
    }
}
