<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Capex extends Model
{
    use SoftDeletes;

    protected $fillable = ['divisis_id', 'title', 'description', 'amount', 'expense_date', 'status'];

    protected $casts = ['amount' => 'decimal:2', 'expense_date' => 'date'];

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'divisis_id');
    }
}
