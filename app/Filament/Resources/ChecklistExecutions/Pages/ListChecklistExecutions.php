<?php

namespace App\Filament\Resources\ChecklistExecutions\Pages;

use App\Filament\Resources\ChecklistExecutions\ChecklistExecutionResource;
use App\Models\ChecklistTemplate;
use App\Models\Unit;
use App\Services\ChecklistExecutionService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class ListChecklistExecutions extends ListRecords
{
    protected static string $resource = ChecklistExecutionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createExecution')
                ->label('Buat Pemeriksaan Baru')
                ->icon('heroicon-o-plus')
                ->form([
                    Select::make('template_id')
                        ->label('Template Checklist')
                        ->options(ChecklistTemplate::where('is_active', true)->pluck('name', 'id'))
                        ->required()
                        ->searchable()
                        ->preload()
                        ->live(),

                    Select::make('unit_id')
                        ->label('Unit / Equipment')
                        ->options(function (Get $get) {
                            $templateId = $get('template_id');
                            if (!$templateId) return [];
                            
                            $template = ChecklistTemplate::find($templateId);
                            $query = Unit::query();
                            if ($template && $template->division_id) {
                                $query->where('divisis_id', $template->division_id);
                            }
                            return $query->pluck('unit_name', 'id');
                        })
                        ->required()
                        ->searchable()
                        ->preload(),
                ])
                ->action(function (array $data) {
                    try {
                        $template = ChecklistTemplate::findOrFail($data['template_id']);
                        $unit = Unit::findOrFail($data['unit_id']);
                        $technicianId = Auth::id();

                        $service = new ChecklistExecutionService();
                        $execution = $service->createFromTemplate($template, $unit, $technicianId);

                        Notification::make()
                            ->title('Berhasil dibuat')
                            ->body('Pemeriksaan berhasil disiapkan. Silakan isi form.')
                            ->success()
                            ->send();

                        return redirect(ChecklistExecutionResource::getUrl('edit', ['record' => $execution->id]));
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal membuat pemeriksaan')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
        ];
    }
}
