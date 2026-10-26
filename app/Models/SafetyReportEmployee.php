<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafetyReportEmployee extends Model
{
    protected $fillable = [
        'nrp',
        'name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
