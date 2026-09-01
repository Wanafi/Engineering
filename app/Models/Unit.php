<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'divisis_id',
        'division_id',
        'unit_code',
        'unit_name',
        'location',
        'floor',
        'area',
        'description',
        'status',
    ];

    public function divisis(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'divisis_id');
    }

    public function division(): BelongsTo
    {
        return $this->divisis();
    }
}