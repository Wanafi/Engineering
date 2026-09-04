<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'wo_number',
        'divisis_id',
        'unit_id',
        'reported_by',
        'assigned_to',
        'priority',
        'type',
        'description',
        'scheduled_date',
        'started_at',
        'completed_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $wo) {
            if (empty($wo->wo_number)) {
                $wo->wo_number = 'WO-'.now()->format('Ymd').'-'.strtoupper(substr(uniqid(), -5));
            }
        });
    }

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'divisis_id');
    }

    public function division(): BelongsTo
    {
        return $this->divisi();
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
