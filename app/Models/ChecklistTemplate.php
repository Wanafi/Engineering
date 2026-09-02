<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'division_id',
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Divisi.
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'division_id');
    }

    /**
     * Daftar pertanyaan dalam template ini.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(ChecklistQuestion::class)->orderBy('order');
    }

    /**
     * Riwayat pelaksanaan checklist dari template ini.
     */
    public function executions(): HasMany
    {
        return $this->hasMany(ChecklistExecution::class);
    }
}
