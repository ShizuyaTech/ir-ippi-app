<?php

namespace Database\Seeders;

use App\Models\Assessment;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class AssessmentUserCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Hapus data existing
        DB::table('assessment_user_codes')->truncate();

        // Cari assessment yang sudah ada, jika tidak ada buat baru
        $assessment = Assessment::first();
        
        if (!$assessment) {
            $assessment = Assessment::create([
                'title' => 'Default Assessment for Seeder',
                'description' => 'This assessment is automatically created for user codes seeding',
                'participant_count' => 50,
                'is_active' => true,
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
            ]);
            
            $this->command->info('Created default assessment with ID: ' . $assessment->id);
        }

        $userCodes = [];

        // Generate 50 user codes dengan assessment_id yang valid
        for ($i = 0; $i < 50; $i++) {
            $userCodes[] = [
                'code' => $this->generateUniqueCode(),
                'assessment_id' => $assessment->id, // PASTIKAN assessment_id tidak null
                'used_by' => null,
                'is_used' => false,
                'used_at' => null,
                'expires_at' => now()->addMonths(6),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('assessment_user_codes')->insert($userCodes);

        $this->command->info('Successfully generated 50 assessment user codes for assessment: ' . $assessment->title);
        $this->command->info('Assessment ID: ' . $assessment->id);
        $this->command->info('Sample codes: ' . implode(', ', array_slice(array_column($userCodes, 'code'), 0, 5)));
    }

    /**
     * Generate unique code format: ABC-123-XYZ
     */
    private function generateUniqueCode(): string
    {
        do {
            $prefix = strtoupper(Str::random(3));
            $middle = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
            $suffix = strtoupper(Str::random(3));
            $code = "{$prefix}-{$middle}-{$suffix}";
        } while (DB::table('assessment_user_codes')->where('code', $code)->exists());

        return $code;
    }
}
