<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'divisis_id',
        'unit_id',
        'assigned_user_id',
        'title',
        'description',
        'schedule_date',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'divisis_id');
    }

    public function divisi(): BelongsTo
    {
        return $this->division();
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}
