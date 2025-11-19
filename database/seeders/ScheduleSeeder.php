<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@company.com')->first();

        if (!$admin) {
            return;
        }

        $schedules = [
            // Meeting schedules
            [
                'title' => 'Rapat Koordinasi Bulanan',
                'description' => 'Rapat koordinasi untuk membahas progress bulanan dan rencana ke depan',
                'start_date' => Carbon::now()->addDays(2),
                'end_date' => Carbon::now()->addDays(2),
                'start_time' => '09:00',
                'end_time' => '11:00',
                'location' => 'Ruang Rapat Utama',
                'type' => 'meeting',
                'priority' => 'high',
                'status' => 'scheduled',
                'notes' => 'Mohon bawa laporan progress masing-masing divisi'
            ],
            [
                'title' => 'Briefing Tim HR',
                'description' => 'Briefing mengenai program pengembangan karyawan',
                'start_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(5),
                'start_time' => '14:00',
                'end_time' => '15:30',
                'location' => 'Ruang Meeting HR',
                'type' => 'meeting',
                'priority' => 'medium',
                'status' => 'scheduled'
            ],

            // Training schedules
            [
                'title' => 'Training Leadership',
                'description' => 'Program pelatihan leadership untuk level manager',
                'start_date' => Carbon::now()->addDays(7),
                'end_date' => Carbon::now()->addDays(8),
                'start_time' => '08:00',
                'end_time' => '17:00',
                'location' => 'Training Center',
                'type' => 'training',
                'priority' => 'high',
                'status' => 'scheduled',
                'notes' => 'Dress code: business casual'
            ],

            // Event schedules
            [
                'title' => 'Company Anniversary',
                'description' => 'Perayaan ulang tahun perusahaan ke-15',
                'start_date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addDays(15),
                'start_time' => '18:00',
                'end_time' => '22:00',
                'location' => 'Ballroom Hotel Grand',
                'type' => 'event',
                'priority' => 'medium',
                'status' => 'scheduled'
            ],

            // Deadline schedules
            [
                'title' => 'Deadline Laporan Kuartalan',
                'description' => 'Batas akhir pengumpulan laporan performa kuartalan',
                'start_date' => Carbon::now()->addDays(3),
                'end_date' => Carbon::now()->addDays(3),
                'type' => 'deadline',
                'priority' => 'high',
                'status' => 'scheduled'
            ],

            // Completed schedules
            [
                'title' => 'Rapat Evaluasi Project',
                'description' => 'Evaluasi project yang sudah selesai',
                'start_date' => Carbon::now()->subDays(2),
                'end_date' => Carbon::now()->subDays(2),
                'start_time' => '10:00',
                'end_time' => '12:00',
                'location' => 'Ruang Rapat 2',
                'type' => 'meeting',
                'priority' => 'medium',
                'status' => 'completed',
                'notes' => 'Project sudah selesai sesuai timeline'
            ],

            // In progress schedules
            [
                'title' => 'Training Software Baru',
                'description' => 'Pelatihan penggunaan software management terbaru',
                'start_date' => Carbon::now()->subDays(1),
                'end_date' => Carbon::now()->addDays(2),
                'start_time' => '09:00',
                'end_time' => '16:00',
                'location' => 'Lab Komputer',
                'type' => 'training',
                'priority' => 'high',
                'status' => 'in_progress'
            ]
        ];

        foreach ($schedules as $scheduleData) {
            Schedule::create(array_merge($scheduleData, [
                'created_by' => $admin->id,
                'created_at' => Carbon::now()->subDays(rand(1, 10)),
                'updated_at' => Carbon::now()->subDays(rand(0, 5))
            ]));
        }

        $this->command->info('Sample schedule data created successfully!');
    }
}
