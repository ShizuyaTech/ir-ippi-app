<?php

namespace Database\Seeders;

use App\Models\ProgramAchievement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class ProgramAchievementSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'title' => 'Safety First Program 2025',
                'description' => 'Program peningkatan kesadaran keselamatan kerja di industri otomotif melalui pelatihan dan sertifikasi K3.',
                'type' => 'program',
                'category' => 'safety',
                'year' => 2025,
                'image' => 'programs/program-placeholder.jpg',
                'order' => 1,
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'title' => 'Green Manufacturing Initiative',
                'description' => 'Program pengurangan limbah produksi dan implementasi teknologi ramah lingkungan di lini produksi.',
                'type' => 'program',
                'category' => 'environment',
                'year' => 2025,
                'image' => 'programs/program-placeholder.jpg',
                'order' => 2,
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'title' => 'Digital Transformation Workshop',
                'description' => 'Program pelatihan transformasi digital untuk meningkatkan efisiensi proses produksi.',
                'type' => 'program',
                'category' => 'technology',
                'year' => 2025,
                'image' => 'program-placeholder.jpg',
                'order' => 3,
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'title' => 'Best Safety Performance 2025',
                'description' => 'Penghargaan atas pencapaian 1000 hari tanpa kecelakaan kerja di area produksi.',
                'type' => 'achievement',
                'category' => 'safety',
                'year' => 2025,
                'image' => 'achievements/achievement-placeholder.jpg',
                'order' => 1,
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'title' => 'ISO 14001:2025 Certification',
                'description' => 'Pencapaian sertifikasi ISO 14001:2025 untuk standar manajemen lingkungan.',
                'type' => 'achievement',
                'category' => 'environment',
                'year' => 2025,
                'image' => 'achievements/achievement-placeholder.jpg',
                'order' => 2,
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'title' => 'Innovation Excellence Award',
                'description' => 'Penghargaan atas inovasi dalam pengembangan sistem produksi berbasis AI.',
                'type' => 'achievement',
                'category' => 'innovation',
                'year' => 2025,
                'image' => 'achievements/achievement-placeholder.jpg',
                'order' => 3,
                'is_active' => true,
                'created_by' => 1
            ]
        ];

        foreach ($programs as $program) {
            ProgramAchievement::create($program);
        }

        // Tambah beberapa data untuk tahun sebelumnya
        $previousYearPrograms = [
            [
                'title' => 'Employee Training Program 2024',
                'description' => 'Program pengembangan kompetensi karyawan melalui sertifikasi dan pelatihan teknis.',
                'type' => 'program',
                'category' => 'training',
                'year' => 2024,
                'image' => 'programs/program-placeholder.jpg',
                'order' => 1,
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'title' => 'Quality Management Award 2024',
                'description' => 'Penghargaan atas implementasi sistem manajemen mutu terbaik di industri otomotif.',
                'type' => 'achievement',
                'category' => 'award',
                'year' => 2024,
                'image' => 'achievements/quality-award-2024.jpg',
                'order' => 1,
                'is_active' => true,
                'created_by' => 1
            ]
        ];

        foreach ($previousYearPrograms as $program) {
            ProgramAchievement::create($program);
        }
    }
}