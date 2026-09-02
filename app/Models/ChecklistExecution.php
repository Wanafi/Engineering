<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistExecution extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'checklist_template_id',
        'unit_id',
        'event_id',
        'technician_id',
        'status',
        'notes',
        'rejection_reason',
        'submitted_at',
        'supervisor_id',
        'supervisor_approved_at',
        'head_id',
        'head_approved_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'supervisor_approved_at' => 'datetime',
        'head_approved_at' => 'datetime',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(ChecklistTemplate::class, 'checklist_template_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function head(): BelongsTo
    {
        return $this->belongsTo(User::class, 'head_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ChecklistAnswer::class)->orderBy('order');
    }
}
