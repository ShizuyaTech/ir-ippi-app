<?php

namespace App\Services;

use App\Models\AssessmentUserCode;
use Illuminate\Support\Str;

class AssessmentCodeService
{
    /**
     * Generate multiple user codes untuk assessment
     */
    public function generateCodesForAssessment($assessmentId, $quantity = 10, $expiryMonths = 6)
    {
        $codes = [];

        for ($i = 0; $i < $quantity; $i++) {
            $codes[] = [
                'code' => $this->generateUniqueCode(),
                'assessment_id' => $assessmentId,
                'expires_at' => now()->addMonths($expiryMonths),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        AssessmentUserCode::insert($codes);

        return AssessmentUserCode::where('assessment_id', $assessmentId)
                                ->where('is_used', false)
                                ->get();
    }

    /**
     * Generate single unique code
     */
    private function generateUniqueCode(): string
    {
        do {
            $prefix = strtoupper(Str::random(3));
            $middle = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
            $suffix = strtoupper(Str::random(3));
            $code = "{$prefix}-{$middle}-{$suffix}";
        } while (AssessmentUserCode::where('code', $code)->exists());

        return $code;
    }

    /**
     * Validasi user code
     */
    public function validateCode($code, $assessmentId = null)
    {
        $query = AssessmentUserCode::where('code', $code)->active();

        if ($assessmentId) {
            $query->where('assessment_id', $assessmentId);
        }

        return $query->first();
    }

    /**
     * Mark code sebagai digunakan
     */
    public function markCodeAsUsed($code, $userId)
    {
        $userCode = AssessmentUserCode::where('code', $code)->first();
        
        if ($userCode) {
            $userCode->update([
                'is_used' => true,
                'used_by' => $userId,
            ]);
        }

        return $userCode;
    }
}