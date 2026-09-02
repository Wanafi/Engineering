<?php

namespace App\Filament\Resources\ChecklistExecutions\Pages;

use App\Filament\Resources\ChecklistExecutions\ChecklistExecutionResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditChecklistExecution extends EditRecord
{
    protected static string $resource = ChecklistExecutionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('submitExecution')
                ->label('Kirim ke Supervisor')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Kirim Hasil Pemeriksaan')
                ->modalDescription('Kirim hasil ini ke Supervisor untuk direview?')
                ->visible(fn ($record) => in_array($record->status, ['draft', 'rejected']))
                ->action(function () {
                    $this->save();
                    $record = $this->getRecord();
                    $record->update([
                        'status' => 'submitted',
                        'submitted_at' => now(),
                        'rejection_reason' => null,
                    ]);
                    Notification::make()->title('Berhasil dikirim ke Supervisor')->success()->send();
                    return redirect(ChecklistExecutionResource::getUrl('index'));
                }),

            Action::make('approveSupervisor')
                ->label('Approve (Supervisor)')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Approve sebagai Supervisor')
                ->visible(fn ($record) => $record->status === 'submitted' && Auth::user()?->hasAnyRole(['Supervisor', 'super_admin']))
                ->action(function () {
                    $record = $this->getRecord();
                    $record->update([
                        'status' => 'supervisor_approved',
                        'supervisor_id' => Auth::id(),
                        'supervisor_approved_at' => now(),
                        'rejection_reason' => null,
                    ]);
                    Notification::make()->title('Disetujui Supervisor, lanjut ke Head')->success()->send();
                    return redirect(ChecklistExecutionResource::getUrl('index'));
                }),

            Action::make('rejectSupervisor')
                ->label('Reject (Supervisor)')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn ($record) => $record->status === 'submitted' && Auth::user()?->hasAnyRole(['Supervisor', 'super_admin']))
                ->form([
                    Textarea::make('rejection_reason')->label('Alasan Reject')->required()->rows(3),
                ])
                ->action(function (array $data) {
                    $record = $this->getRecord();
                    $record->update([
                        'status' => 'rejected',
                        'rejection_reason' => $data['rejection_reason'],
                    ]);
                    Notification::make()->title('Checklist di-reject')->danger()->send();
                    return redirect(ChecklistExecutionResource::getUrl('index'));
                }),

            Action::make('approveHead')
                ->label('Approve Final (Head)')
                ->icon('heroicon-o-shield-check')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Approve Final sebagai Head')
                ->visible(fn ($record) => $record->status === 'supervisor_approved' && Auth::user()?->hasAnyRole(['Head', 'super_admin']))
                ->action(function () {
                    $record = $this->getRecord();
                    $record->update([
                        'status' => 'completed',
                        'head_id' => Auth::id(),
                        'head_approved_at' => now(),
                        'rejection_reason' => null,
                    ]);
                    Notification::make()->title('Checklist selesai (Completed)')->success()->send();
                    return redirect(ChecklistExecutionResource::getUrl('index'));
                }),

            Action::make('rejectHead')
                ->label('Reject (Head)')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn ($record) => $record->status === 'supervisor_approved' && Auth::user()?->hasAnyRole(['Head', 'super_admin']))
                ->form([
                    Textarea::make('rejection_reason')->label('Alasan Reject')->required()->rows(3),
                ])
                ->action(function (array $data) {
                    $record = $this->getRecord();
                    $record->update([
                        'status' => 'rejected',
                        'rejection_reason' => $data['rejection_reason'],
                    ]);
                    Notification::make()->title('Checklist di-reject oleh Head')->danger()->send();
                    return redirect(ChecklistExecutionResource::getUrl('index'));
                }),

            DeleteAction::make()->visible(fn ($record) => in_array($record->status, ['draft', 'rejected'])),
        ];
    }
}
