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
        User::factory(10)->create();

        // Buat admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@company.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Buat pengurus serikat (kita anggap sebagai user biasa tapi punya akses khusus)
        User::create([
            'name' => 'Ketua Serikat Karyawan',
            'email' => 'serikat@company.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Sekretaris Serikat',
            'email' => 'sekretaris@company.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Bendahara Serikat',
            'email' => 'bendahara@company.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Seed berita
        $this->call(NewsSeeder::class);

        // Buat user biasa (karyawan)
        User::create([
            'name' => 'Karyawan Contoh',
            'email' => 'karyawan@company.com',
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
