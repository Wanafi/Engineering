<?php

namespace App\Services;

use App\Models\ChecklistExecution;
use App\Models\ChecklistTemplate;
use App\Models\Event;
use App\Models\Unit;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class ChecklistExecutionService
{
    /**
     * Membuat ChecklistExecution baru dari Template dan melakukan snapshot pertanyaan.
     *
     * @param ChecklistTemplate $template
     * @param Unit $unit
     * @param User|int|null $technician
     * @param Event|int|null $event
     * @param string|null $notes
     * @return ChecklistExecution
     * @throws Exception
     */
    public function createFromTemplate(
        ChecklistTemplate $template,
        Unit $unit,
        User|int|null $technician = null,
        Event|int|null $event = null,
        ?string $notes = null
    ): ChecklistExecution {
        // 1. Validasi status template
        if (!$template->is_active) {
            throw new Exception("Template checklist '{$template->name}' sedang tidak aktif.");
        }

        // 2. Validasi ketersediaan pertanyaan
        $questions = $template->questions()->orderBy('order')->get();
        if ($questions->isEmpty()) {
            throw new Exception("Template checklist '{$template->name}' tidak memiliki pertanyaan.");
        }

        $technicianId = $technician instanceof User ? $technician->id : $technician;
        $eventId = $event instanceof Event ? $event->id : $event;

        // 3. Jalankan dalam DB Transaction
        return DB::transaction(function () use ($template, $unit, $technicianId, $eventId, $notes, $questions) {
            $execution = ChecklistExecution::create([
                'checklist_template_id' => $template->id,
                'unit_id' => $unit->id,
                'event_id' => $eventId,
                'technician_id' => $technicianId,
                'status' => 'draft',
                'notes' => $notes,
            ]);

            foreach ($questions as $question) {
                $execution->answers()->create([
                    'question_snapshot' => $question->label,
                    'type_snapshot' => $question->type,
                    'options_snapshot' => $question->options,
                    'is_required_snapshot' => $question->is_required,
                    'order' => $question->order,
                    'answer_text' => null,
                    'answer_photo_path' => null,
                    'notes' => null,
                ]);
            }

            return $execution;
        });
    }
}
