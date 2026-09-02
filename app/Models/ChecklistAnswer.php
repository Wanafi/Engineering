<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_execution_id',
        'question_snapshot',
        'type_snapshot',
        'options_snapshot',
        'is_required_snapshot',
        'order',
        'answer_text',
        'answer_photo_path',
        'notes',
    ];

    protected $casts = [
        'options_snapshot' => 'array',
        'is_required_snapshot' => 'boolean',
        'order' => 'integer',
    ];

    public function execution(): BelongsTo
    {
        return $this->belongsTo(ChecklistExecution::class, 'checklist_execution_id');
    }
}
