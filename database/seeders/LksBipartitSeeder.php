<?php

namespace Database\Seeders;

use App\Models\LksBipartit;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LksBipartitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('lks_bipartits')->truncate();

        // Get all users
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $tasks = [
            // Review Tasks
            [
                'title' => 'Pembahasan Draft Peraturan Perusahaan',
                'description' => 'Menyusun dan mereview draft peraturan perusahaan untuk disampaikan dalam rapat bipartit.',
                'status' => 'review',
                'priority' => 4,
                'due_date' => now()->addDays(5)->format('Y-m-d'),
                'assigned_to' => $users->random()->id,
                'created_by' => $users->random()->id,
                'notes' => 'Perlu konsultasi dengan bagian legal untuk compliance check.',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(1),
            ],
            [
                'title' => 'Review Kesepakatan Upah Minimum',
                'description' => 'Melakukan analisis dan review terhadap usulan kenaikan upah minimum tahun ini.',
                'status' => 'review',
                'priority' => 3,
                'due_date' => now()->addDays(7)->format('Y-m-d'),
                'assigned_to' => $users->random()->id,
                'created_by' => $users->random()->id,
                'notes' => 'Data dari BPS sudah diterima, tinggal analisis lebih lanjut.',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(2),
            ],
            [
                'title' => 'Evaluasi Program K3',
                'description' => 'Review implementasi program Keselamatan dan Kesehatan Kerja periode sebelumnya.',
                'status' => 'review',
                'priority' => 2,
                'due_date' => now()->addDays(10)->format('Y-m-d'),
                'assigned_to' => $users->random()->id,
                'created_by' => $users->random()->id,
                'notes' => 'Menunggu laporan insiden dari departemen produksi.',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(4),
            ],

            // On Progress Tasks
            [
                'title' => 'Penyusunan Laporan Bipartit Triwulan',
                'description' => 'Menyusun laporan hasil rapat bipartit triwulan III tahun 2024 untuk disampaikan ke Disnaker.',
                'status' => 'on_progress',
                'priority' => 3,
                'due_date' => now()->addDays(3)->format('Y-m-d'),
                'assigned_to' => $users->random()->id,
                'created_by' => $users->random()->id,
                'notes' => 'Sudah 70% selesai, tinggal lampiran dan final review.',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(1),
            ],
            [
                'title' => 'Koordinasi Persiapan Rapat Bipartit',
                'description' => 'Mempersiapkan agenda, materi, dan undangan untuk rapat bipartit mendatang.',
                'status' => 'on_progress',
                'priority' => 4,
                'due_date' => now()->addDays(2)->format('Y-m-d'),
                'assigned_to' => $users->random()->id,
                'created_by' => $users->random()->id,
                'notes' => 'Menunggu konfirmasi availability dari seluruh peserta.',
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subHours(12),
            ],
            [
                'title' => 'Social Gathering Karyawan',
                'description' => 'Merencanakan acara gathering untuk meningkatkan hubungan industrial yang harmonis.',
                'status' => 'on_progress',
                'priority' => 2,
                'due_date' => now()->addDays(14)->format('Y-m-d'),
                'assigned_to' => $users->random()->id,
                'created_by' => $users->random()->id,
                'notes' => 'Survey venue sudah dilakukan, tinggal finalisasi budget.',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(2),
            ],
            [
                'title' => 'Mediasi Keluhan Karyawan',
                'description' => 'Menangani keluhan dari departemen produksi mengenai shift kerja.',
                'status' => 'on_progress',
                'priority' => 3,
                'due_date' => now()->addDays(4)->format('Y-m-d'),
                'assigned_to' => $users->random()->id,
                'created_by' => $users->random()->id,
                'notes' => 'Sudah dilakukan pertemuan awal, menunggu feedback dari kedua pihak.',
                'created_at' => now()->subDays(6),
                'updated_at' => now()->subHours(6),
            ],

            // Done Tasks
            [
                'title' => 'Pelatihan Hubungan Industrial',
                'description' => 'Menyelenggarakan pelatihan mengenai hubungan industrial bagi perwakilan karyawan.',
                'status' => 'done',
                'priority' => 2,
                'due_date' => now()->subDays(5)->format('Y-m-d'),
                'assigned_to' => $users->random()->id,
                'created_by' => $users->random()->id,
                'notes' => 'Acara berjalan lancar dengan tingkat partisipasi 95%.',
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(6),
            ],
            [
                'title' => 'Revisi Perjanjian Kerja Bersama',
                'description' => 'Merevisi beberapa pasal dalam Perjanjian Kerja Bersama berdasarkan masukan dari rapat.',
                'status' => 'done',
                'priority' => 3,
                'due_date' => now()->subDays(3)->format('Y-m-d'),
                'assigned_to' => $users->random()->id,
                'created_by' => $users->random()->id,
                'notes' => 'Revisi sudah disetujui oleh kedua belah pihak.',
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(4),
            ],
            [
                'title' => 'Pembentukan Panitia Bipartit',
                'description' => 'Membentuk panitia bipartit periode 2024-2025 dengan komposisi yang seimbang.',
                'status' => 'done',
                'priority' => 4,
                'due_date' => now()->subDays(8)->format('Y-m-d'),
                'assigned_to' => $users->random()->id,
                'created_by' => $users->random()->id,
                'notes' => 'Struktur panitia sudah disahkan oleh direksi.',
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(9),
            ],
            [
                'title' => 'Pengadaan Fasilitas Mushola',
                'description' => 'Koordinasi pengadaan dan perbaikan fasilitas mushola untuk karyawan.',
                'status' => 'done',
                'priority' => 1,
                'due_date' => now()->subDays(12)->format('Y-m-d'),
                'assigned_to' => $users->random()->id,
                'created_by' => $users->random()->id,
                'notes' => 'Fasilitas sudah bisa digunakan dengan baik.',
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(13),
            ],
        ];

        // Insert tasks
        foreach ($tasks as $task) {
            LksBipartit::create($task);
        }

        $this->command->info('LKS Bipartit Seeder created successfully:');
        $this->command->info('- ' . count($tasks) . ' tasks created');
        $this->command->info('- ' . count(array_filter($tasks, fn($task) => $task['status'] === 'review')) . ' review tasks');
        $this->command->info('- ' . count(array_filter($tasks, fn($task) => $task['status'] === 'on_progress')) . ' on progress tasks');
        $this->command->info('- ' . count(array_filter($tasks, fn($task) => $task['status'] === 'done')) . ' done tasks');
    }
}
