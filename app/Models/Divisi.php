<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Divisi extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'nama_divisi',
        'warna',
        'slug',
        'is_active',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Divisi $divisi) {
            $divisi->slug = Str::slug($divisi->nama_divisi);
        });
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class, 'divisis_id');
    }
}
