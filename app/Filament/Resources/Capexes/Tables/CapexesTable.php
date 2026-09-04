<?php

namespace App\Filament\Resources\Capexes\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CapexesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Judul')->searchable()->sortable()->weight('bold'),
                TextColumn::make('divisi.nama_divisi')->label('Divisi')->badge()->sortable(),
                TextColumn::make('amount')->label('Amount')->money('IDR')->sortable(),
                TextColumn::make('expense_date')->label('Tanggal')->date('d M Y')->sortable()->placeholder('-'),
                TextColumn::make('status')->label('Status')->badge()->color(fn (string $state): string => match ($state) { 'approved' => 'success', 'rejected' => 'danger', 'submitted' => 'warning', default => 'gray' }),
            ])
            ->filters([
                SelectFilter::make('divisis_id')->label('Divisi')->relationship('divisi', 'nama_divisi')->searchable()->preload(),
                SelectFilter::make('status')->label('Status')->options(['draft' => 'Draft', 'submitted' => 'Submitted', 'approved' => 'Approved', 'rejected' => 'Rejected']),
            ])
            ->recordActions([\Filament\Actions\Action::make('print')->label('Cetak')->icon('heroicon-o-printer')->color('gray')->url(fn ($record) => route('reports.single', ['type' => 'capex', 'id' => $record->id]), shouldOpenInNewTab: true), \Filament\Actions\ViewAction::make(), \Filament\Actions\EditAction::make(), \Filament\Actions\DeleteAction::make()])
            ->toolbarActions([\Filament\Actions\BulkActionGroup::make([\Filament\Actions\DeleteBulkAction::make()])])
            ->defaultSort('created_at', 'desc');
    }
}
