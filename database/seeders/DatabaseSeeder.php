<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Create additional users
        // User::factory(10)->create();

        // Buat admin
        User::updateOrCreate([
            'email' => 'admin@cmsippi.com',
        ], [
            'name' => 'Administrator',
            'password' => Hash::make('2026ippi1234'),
            'email_verified_at' => now(),
            'role' => 'admin',
        ]);

        // Buat pengurus serikat (kita anggap sebagai user biasa tapi punya akses khusus)
        User::create([
            'name' => 'Ketua SPSI',
            'email' => 'spsi@cmsippi.com',
            'password' => Hash::make('2026spsi1234'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Ketua IKA',
            'email' => 'ika@cmsippi.com',
            'password' => Hash::make('2026ika1234'),
            'email_verified_at' => now(),
        ]);

        // User::create([
        //     'name' => 'Bendahara Serikat',
        //     'email' => 'bendahara@cmsippi.com',
        //     'password' => Hash::make('password'),
        //     'email_verified_at' => now(),
        // ]);

        // Seed berita
        $this->call(NewsSeeder::class);

        // Buat user biasa (karyawan)
        User::create([
            'name' => 'Karyawan Contoh',
            'email' => 'karyawan@cmsippi.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $this->command->info('Sample users created successfully!');

        $this->call(AssessmentSeeder::class);
        $this->call(AssessmentUserCodeSeeder::class);
        $this->call(FeedbackSeeder::class);
        $this->call(ScheduleSeeder::class);
        $this->call(LksBipartitSeeder::class);
        $this->call(DashboardScoreSeeder::class);
        $this->call(NewsSeeder::class);
        $this->call(ProgramAchievementSeeder::class);
    }
}
