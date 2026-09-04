<?php

namespace App\Filament\Resources\MaintenanceReports\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MaintenanceReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make("title")->label("Judul Laporan")->searchable()->sortable()->weight("bold"),
            TextColumn::make("divisi.nama_divisi")->label("Divisi")->badge()->sortable(),
            TextColumn::make("unit.unit_name")->label("Unit")->placeholder("-"),
            TextColumn::make("workOrder.wo_number")->label("No WO")->placeholder("-"),
            TextColumn::make("report_date")->label("Tanggal")->date("d M Y")->sortable(),
            TextColumn::make("cost")->label("Biaya")->money("IDR")->sortable(),
            TextColumn::make("status")->label("Status")->badge()->color(fn (string $state): string => match ($state) { "published" => "success", default => "gray" }),
        ])->filters([
            SelectFilter::make("divisis_id")->label("Divisi")->relationship("divisi", "nama_divisi")->searchable()->preload(),
            SelectFilter::make("status")->label("Status")->options(["draft" => "Draft", "published" => "Published"]),
        ])->recordActions([
            \Filament\Actions\Action::make('print')
                ->label('Cetak')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->url(fn ($record) => route('reports.single', ['type' => 'maintenance_reports', 'id' => $record->id]), shouldOpenInNewTab: true),
            \Filament\Actions\ViewAction::make(),
            \Filament\Actions\EditAction::make(),
            \Filament\Actions\DeleteAction::make(),
        ])
          ->toolbarActions([\Filament\Actions\BulkActionGroup::make([\Filament\Actions\DeleteBulkAction::make()])])
          ->defaultSort("report_date", "desc");
    }
}
