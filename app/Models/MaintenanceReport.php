<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceReport extends Model
{
    use SoftDeletes;

    protected $fillable = ['divisis_id', 'unit_id', 'work_order_id', 'title', 'description', 'report_date', 'cost', 'status'];

    protected $casts = ['report_date' => 'date', 'cost' => 'decimal:2'];

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'divisis_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }
}
