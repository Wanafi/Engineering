<?php

namespace App\Filament\Resources\ChecklistExecutions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ChecklistExecutionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('template.name')
                    ->label('Template')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('unit.unit_name')
                    ->label('Unit / Equipment')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('technician.name')
                    ->label('Teknisi')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'submitted' => 'info',
                        'supervisor_approved' => 'success',
                        'completed' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Draft',
                        'submitted' => 'Menunggu Supervisor',
                        'supervisor_approved' => 'Menunggu Head',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                        default => ucfirst($state),
                    }),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Menunggu Supervisor',
                        'supervisor_approved' => 'Menunggu Head',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                    ]),

                SelectFilter::make('unit_id')
                    ->label('Unit')
                    ->relationship('unit', 'unit_name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
