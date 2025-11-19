<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id', 
        'question_text', 
        'description', 
        'order', 
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function responses()
    {
        return $this->hasMany(ResponseAssessment::class);
    }
}
