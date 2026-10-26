<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SafetyReportEvent extends Model
{
    protected $fillable = [
        'safety_report_id',
        'actor_id',
        'event_type',
        'from_status',
        'to_status',
        'note',
        'metadata',
    ];

    protected function casts(): array
    {
        return ['metadata' => 'array'];
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(SafetyReport::class, 'safety_report_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
