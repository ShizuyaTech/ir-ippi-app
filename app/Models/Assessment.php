<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'participant_count',
        'is_active',
        'start_date',
        'end_date',
        'validation_token'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'participant_count' => 'integer'
    ];

    /**
     * Relasi ke user codes
     */
    public function userCodes()
    {
        return $this->hasMany(AssessmentUserCode::class);
    }

    /**
     * Relasi ke questions
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Relasi ke responses
     */
    public function responses()
    {
        return $this->hasMany(ResponseAssessment::class);
    }

    /**
     * ✅ OPTIMIZED: Use scopes instead of attributes
     * Get active questions - use like: Assessment::find(1)->activeQuestions
     */
    public function activeQuestions()
    {
        return $this->questions()->where('is_active', true);
    }

    /**
     * ✅ OPTIMIZED: Use scopes for counting
     * These are eager-load friendly
     */
    public function scopeWithStats($query)
    {
        return $query->withCount(['questions', 'userCodes', 'responses']);
    }

    /**
     * Route model binding using slug instead of ID
     * Prevents users from guessing IDs via random numbers
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Generate unique slug from title
     */
    public static function generateSlug($title)
    {
        $slug = \Illuminate\Support\Str::slug($title);
        $count = 1;
        $originalSlug = $slug;

        // Check for uniqueness
        while (self::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Generate unique validation token
     */
    public static function generateValidationToken()
    {
        return \Illuminate\Support\Str::random(32);
    }

    /**
     * Boot the model - auto-generate slug and token
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->slug) {
                $model->slug = self::generateSlug($model->title);
            }
            if (!$model->validation_token) {
                $model->validation_token = self::generateValidationToken();
            }
        });

        static::updating(function ($model) {
            // Update slug if title changed
            if ($model->isDirty('title') && !$model->isDirty('slug')) {
                $model->slug = self::generateSlug($model->title);
            }
        });
    }
}