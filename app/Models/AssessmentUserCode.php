<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AssessmentUserCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'assessment_id',
        'used_by',
        'is_used',
        'used_at',
        'expires_at'
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Relasi ke assessment
     */
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * Relasi ke user yang menggunakan code
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    /**
     * Scope untuk code yang masih aktif
     */
    /**
 * Scope untuk code yang masih aktif
 */
public function scopeActive($query)
{
    return $query->where('is_used', false)
                ->where(function($q) {
                    $q->where('expires_at', '>', now())
                      ->orWhereNull('expires_at');
                });
}

    /**
     * Scope untuk code berdasarkan assessment
     */
    public function scopeForAssessment($query, $assessmentId)
    {
        return $query->where('assessment_id', $assessmentId);
    }

    /**
     * Generate multiple user codes untuk assessment
     */
    public static function generateForAssessment($assessmentId, $quantity, $expiryMonths = 6)
    {
        // Debug: Pastikan assessmentId valid
        if (empty($assessmentId)) {
            throw new \Exception("Assessment ID cannot be null");
        }

        $codes = [];

        for ($i = 0; $i < $quantity; $i++) {
            $codes[] = [
                'code' => self::generateUniqueCode(),
                'assessment_id' => $assessmentId, // Pastikan ini tidak null
                'is_used' => false,
                'used_by' => null,
                'used_at' => null,
                'expires_at' => now()->addMonths($expiryMonths),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Debug: Cek data sebelum insert
        foreach ($codes as $code) {
            if (empty($code['assessment_id'])) {
                throw new \Exception("Found null assessment_id in code data");
            }
        }

        self::insert($codes);

        return self::where('assessment_id', $assessmentId)->get();
    }

    /**
     * Generate unique code format: ABC-123-XYZ
     */
    private static function generateUniqueCode(): string
    {
        do {
            $prefix = strtoupper(Str::random(3));
            $middle = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
            $suffix = strtoupper(Str::random(3));
            $code = "{$prefix}-{$middle}-{$suffix}";
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Cek apakah code masih valid
     */
    public function isValid(): bool
    {
        return !$this->is_used && 
               ($this->expires_at === null || $this->expires_at->isFuture());
    }

    /**
     * Mark code sebagai digunakan
     */
    public function markAsUsed($userId)
    {
        $this->update([
            'is_used' => true,
            'used_by' => $userId,
            'used_at' => now(),
        ]);
    }

    /**
     * Get formatted status
     */
    public function getStatusAttribute()
    {
        if ($this->is_used) {
            return 'Used';
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return 'Expired';
        }

        return 'Available';
    }

    /**
     * Get status color untuk UI
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Used' => 'danger',
            'Expired' => 'warning',
            'Available' => 'success',
            default => 'secondary'
        };
    }
}