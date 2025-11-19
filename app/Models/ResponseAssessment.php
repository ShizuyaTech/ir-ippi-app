<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_code',
        'user_id',
        'assessment_id', 
        'question_id',
        'rating'
    ];

    // Rating options with labels and values
    const RATINGS = [
        'sangat_buruk' => 'Sangat Buruk',
        'buruk' => 'Buruk', 
        'cukup' => 'Cukup',
        'baik' => 'Baik',
        'sangat_baik' => 'Sangat Baik'
    ];

    const RATING_VALUES = [
        'sangat_buruk' => 1,
        'buruk' => 2,
        'cukup' => 3, 
        'baik' => 4,
        'sangat_baik' => 5
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function assessmentUser()
    {
        return $this->belongsTo(AssessmentUser::class, 'user_code', 'user_code');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // Helper method to get rating label
    public function getRatingLabelAttribute()
    {
        return self::RATINGS[$this->rating] ?? 'Tidak Diketahui';
    }

    // Helper method to get numeric value
    public function getRatingValueAttribute()
    {
        return self::RATING_VALUES[$this->rating] ?? 0;
    }

    /**
     * Validasi user code sebelum menyimpan
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Validasi bahwa user_code ada dan valid
            if ($model->user_code) {
                $validCode = AssessmentUserCode::where('code', $model->user_code)
                    ->active()
                    ->exists();

                if (!$validCode) {
                    throw new \Exception('User code tidak valid atau sudah digunakan');
                }
            }
        });
    }
}
