<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_code',
        'assessment_id',
        'has_completed',
        'completed_at'
    ];

    protected $casts = [
        'has_completed' => 'boolean',
        'completed_at' => 'datetime'
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function responses()
    {
        return $this->hasMany(ResponseAssessment::class, 'user_code', 'user_code');
    }
}
