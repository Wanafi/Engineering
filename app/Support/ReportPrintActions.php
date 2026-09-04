<?php

namespace App\Support;

use Filament\Actions\Action;

class ReportPrintActions
{
    public static function listPreview(string $type, string $label = 'Print Preview'): Action
    {
        return Action::make('printPreview')
            ->label($label)
            ->icon('heroicon-o-printer')
            ->color('gray')
            ->url(fn () => route('reports.preview', ['type' => $type]), shouldOpenInNewTab: true)
            ->extraAttributes(['target' => '_blank']);
    }

    public static function listPrint(string $type): Action
    {
        return Action::make('printDirect')
            ->label('Cetak')
            ->icon('heroicon-o-document-arrow-down')
            ->color('primary')
            ->url(fn () => route('reports.preview', ['type' => $type, 'auto' => 1]), shouldOpenInNewTab: true);
    }

    public static function recordPreview(string $type): Action
    {
        return Action::make('printRecord')
            ->label('Print')
            ->icon('heroicon-o-printer')
            ->color('gray')
            ->url(fn ($record) => route('reports.single', ['type' => $type, 'id' => $record->getKey()]), shouldOpenInNewTab: true);
    }
}
