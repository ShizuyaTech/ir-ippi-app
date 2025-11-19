<?php

namespace Database\Seeders;

use App\Models\DashboardScore;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DashboardScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari user dengan role admin atau email admin
        $admin = User::where('email', 'admin@company.com')
                    // ->orWhere('role', 'admin')
                    // ->orWhere('is_admin', true)
                    ->first();

        // Jika tidak ditemukan admin, gunakan user pertama
        if (!$admin) {
            $admin = User::first();
        }

        // Hapus data existing jika ada
        DashboardScore::truncate();

        DashboardScore::create([
            'ir_partnership' => 85.5,
            'conductive_working_climate' => 78.2,
            'ess' => 92.0,
            'airsi' => 88.7,
            'updated_by' => $admin->id,
        ]);

        $this->command->info('Dashboard Score created successfully for user ID: ' . $admin->id);
    }
}
