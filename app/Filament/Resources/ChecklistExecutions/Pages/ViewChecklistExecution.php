<?php

namespace App\Filament\Resources\ChecklistExecutions\Pages;

use App\Filament\Resources\ChecklistExecutions\ChecklistExecutionResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;

class ViewChecklistExecution extends ViewRecord
{
    protected static string $resource = ChecklistExecutionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('submit')
                ->label('Submit')
                ->icon('heroicon-o-paper-airplane')
                ->color('info')
                ->visible(fn () => $this->record->status === 'draft')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'submitted', 'submitted_at' => now()]);
                }),
            Action::make('approveSupervisor')
                ->label('Approve (Supervisor)')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $this->record->status === 'submitted')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'supervisor_approved', 'supervisor_id' => auth()->id(), 'supervisor_approved_at' => now()]);
                }),
            Action::make('approveHead')
                ->label('Approve (Head)')
                ->icon('heroicon-o-shield-check')
                ->color('success')
                ->visible(fn () => $this->record->status === 'supervisor_approved')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'completed', 'head_id' => auth()->id(), 'head_approved_at' => now()]);
                }),
            Action::make('reject')
                ->label('Tolak')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn () => in_array($this->record->status, ['submitted', 'supervisor_approved']))
                ->form([Textarea::make('rejection_reason')->label('Alasan Penolakan')->required()->rows(3)])
                ->action(function (array $data) {
                    $this->record->update(['status' => 'rejected', 'rejection_reason' => $data['rejection_reason']]);
                }),
        ];
    }
}
