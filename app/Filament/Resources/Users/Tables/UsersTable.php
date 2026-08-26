<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // ========================================
                // NAMA
                // ========================================
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-user')
                    ->copyable()
                    ->copyMessage('Nama berhasil disalin'),

                // ========================================
                // EMAIL
                // ========================================
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email berhasil disalin'),

                TextColumn::make('phone')
                    ->label('Telepon')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('division.nama_divisi')
                    ->label('Divisi')
                    ->badge()
                    ->color(fn ($record) => $record->division?->warna)
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'tetap' => 'Tetap',
                        'kontrak' => 'Kontrak',
                        'magang' => 'Magang',
                        'lepas' => 'Lepas/Freelance',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'tetap' => 'success',
                        'kontrak' => 'warning',
                        'magang' => 'info',
                        'lepas' => 'gray',
                        default => 'gray',
                    }),


                // ========================================
                // ROLE
                // ========================================
                TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                // ========================================
                // CREATED AT
                // ========================================
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // ========================================
                // UPDATED AT
                // ========================================
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            // ========================================
            // FILTER
            // ========================================
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Aktif',
                        'inactive' => 'Tidak Aktif',
                    ]),

                SelectFilter::make('roles')
                    ->label('Role')
                    ->relationship('roles', 'name')
                    ->preload()
                    ->searchable(),
            ])

            // ========================================
            // ACTION
            // ========================================
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])

            // ========================================
            // BULK ACTION
            // ========================================
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            // ========================================
            // DEFAULT SORT
            // ========================================
            ->defaultSort('created_at', 'desc');
    }
}