<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user yang akan sebagai pengurus serikat (bukan admin)
        $unionUsers = User::where('email', 'like', '%serikat%')
                         ->orWhere('email', 'like', '%sekretaris%')
                         ->orWhere('email', 'like', '%bendahara%')
                         ->get();

        // Jika tidak ada user serikat, buat default
        if ($unionUsers->isEmpty()) {
            $unionUsers = User::where('id', '!=', 1)->get(); // exclude admin
        }

        // Ambil admin
        $admin = User::where('email', 'admin@company.com')->first();

        // Sample data feedback yang realistis
        $sampleFeedbacks = [
            // Feedback dengan status submitted
            [
                'title' => 'Permintaan Review Kebijakan Work From Home',
                'content' => 'Kami menerima banyak keluhan dari anggota mengenai kebijakan WFH yang saat ini diterapkan. Banyak karyawan yang merasa kebijakan ini tidak fleksibel dan membatasi produktivitas. Mohon dipertimbangkan untuk melakukan review ulang kebijakan ini dengan mempertimbangkan kebutuhan masing-masing divisi.',
                'category' => 'kebijakan',
                'priority' => 'high',
                'status' => 'submitted',
                'submitted_at' => Carbon::now()->subDays(5)
            ],
            [
                'title' => 'Keluhan Kondisi AC di Area Produksi',
                'content' => 'Beberapa unit AC di area produksi sudah tidak berfungsi optimal selama 2 minggu terakhir. Suhu ruangan menjadi tidak nyaman dan dapat mempengaruhi kesehatan karyawan serta kualitas produk. Mohon segera dilakukan perbaikan atau penggantian unit AC yang rusak.',
                'category' => 'fasilitas',
                'priority' => 'urgent',
                'status' => 'submitted',
                'submitted_at' => Carbon::now()->subDays(3)
            ],
            [
                'title' => 'Usulan Improvement Sistem Komunikasi Internal',
                'content' => 'Sistem komunikasi internal saat ini dirasa masih kurang efektif. Banyak informasi penting yang terlambat sampai ke level karyawan. Kami mengusulkan implementasi platform komunikasi yang lebih terintegrasi dan real-time untuk meningkatkan transparansi informasi.',
                'category' => 'komunikasi',
                'priority' => 'medium',
                'status' => 'submitted',
                'submitted_at' => Carbon::now()->subDays(7)
            ],
            [
                'title' => 'Masalah Jam Kerja Lembur',
                'content' => 'Terdapat ketidakjelasan dalam pengaturan jam lembur dan kompensasinya. Beberapa karyawan melaporkan tidak menerima pembayaran lembur yang sesuai. Perlunya standardisasi yang jelas mengenai prosedur lembur dan pembayarannya.',
                'category' => 'kondisi_kerja',
                'priority' => 'high',
                'status' => 'submitted',
                'submitted_at' => Carbon::now()->subDays(10)
            ],

            // Feedback dengan status draft
            [
                'title' => 'Permintaan Fasilitas Ruang Istirahat yang Lebih Nyaman',
                'content' => 'Ruang istirahat saat ini dirasa kurang memadai untuk jumlah karyawan yang ada. Kami mengusulkan penambahan fasilitas seperti microwave, water dispenser, dan perbaikan kondisi kursi yang sudah rusak.',
                'category' => 'fasilitas',
                'priority' => 'medium',
                'status' => 'draft',
                'submitted_at' => null
            ],
            [
                'title' => 'Feedback Program Pelatihan Karyawan',
                'content' => 'Program pelatihan yang diadakan bulan lalu mendapatkan respon positif dari karyawan. Namun, banyak yang mengeluhkan jadwal pelatihan yang bentrok dengan pekerjaan rutin. Mohon dipertimbangkan penjadwalan yang lebih fleksible untuk pelatihan mendatang.',
                'category' => 'lainnya',
                'priority' => 'low',
                'status' => 'draft',
                'submitted_at' => null
            ],

            // Feedback yang sudah ditanggapi
            [
                'title' => 'Permintaan Transparansi dalam Promosi Jabatan',
                'content' => 'Proses promosi jabatan dirasa masih kurang transparan. Banyak karyawan yang tidak memahami kriteria dan proses seleksi yang dilakukan. Kami meminta adanya sosialisasi yang jelas mengenai mekanisme promosi di perusahaan.',
                'category' => 'kebijakan',
                'priority' => 'high',
                'status' => 'responded',
                'submitted_at' => Carbon::now()->subDays(15),
                'management_response' => 'Terima kasih atas masukannya. Kami sedang menyusun panduan promosi yang lebih transparan dan akan melakukan sosialisasi ke seluruh karyawan dalam waktu dekat. Timeline penyelesaian sekitar 1 bulan ke depan.',
                'responded_at' => Carbon::now()->subDays(5),
                'responded_by' => $admin ? $admin->id : null
            ],
            [
                'title' => 'Keluhan Kebersihan Toilet dan Area Umum',
                'content' => 'Kebersihan toilet dan area umum lainnya perlu ditingkatkan. Beberapa toilet sering dalam kondisi kotor dan kurang terawat. Hal ini mempengaruhi kenyamanan dan kesehatan karyawan.',
                'category' => 'fasilitas',
                'priority' => 'medium',
                'status' => 'responded',
                'submitted_at' => Carbon::now()->subDays(20),
                'management_response' => 'Kami akan meningkatkan frekuensi cleaning service dan melakukan monitoring ketat terhadap kebersihan area toilet. Selain itu, akan ditempelkan poster himbauan untuk menjaga kebersihan bersama.',
                'responded_at' => Carbon::now()->subDays(8),
                'responded_by' => $admin ? $admin->id : null
            ],

            // Feedback dalam review
            [
                'title' => 'Usulan Perbaikan Sistem Parkir',
                'content' => 'Area parkir karyawan saat ini sering penuh dan tidak tertata rapi. Banyak kendaraan yang parkir sembarangan sehingga menyulitkan akses keluar masuk. Mohon dipertimbangkan penambahan area parkir atau sistem penataan yang lebih baik.',
                'category' => 'fasilitas',
                'priority' => 'medium',
                'status' => 'under_review',
                'submitted_at' => Carbon::now()->subDays(12)
            ],
            [
                'title' => 'Permintaan Penyesuaian Jam Masuk',
                'content' => 'Dengan kondisi lalu lintas yang semakin padat, banyak karyawan yang mengusulkan penyesuaian jam masuk menjadi lebih fleksibel (flexible hours) untuk menghindari macet dan meningkatkan work-life balance.',
                'category' => 'kebijakan',
                'priority' => 'high',
                'status' => 'under_review',
                'submitted_at' => Carbon::now()->subDays(8)
            ]
        ];

        foreach ($sampleFeedbacks as $feedbackData) {
            $feedback = Feedback::create([
                'title' => $feedbackData['title'],
                'content' => $feedbackData['content'],
                'category' => $feedbackData['category'],
                'priority' => $feedbackData['priority'],
                'status' => $feedbackData['status'],
                'submitted_at' => $feedbackData['submitted_at'],
                'management_response' => $feedbackData['management_response'] ?? null,
                'responded_at' => $feedbackData['responded_at'] ?? null,
                'responded_by' => $feedbackData['responded_by'] ?? null,
                'created_by' => $unionUsers->random()->id,
                'created_at' => $feedbackData['submitted_at'] ?? Carbon::now()->subDays(rand(1, 25)),
                'updated_at' => $feedbackData['responded_at'] ?? ($feedbackData['submitted_at'] ?? Carbon::now()->subDays(rand(0, 10)))
            ]);
        }

        $this->command->info('Sample feedback data created successfully!');
        $this->command->info('Total Feedbacks: ' . Feedback::count() . ' feedbacks');
        $this->command->info('Status Distribution:');
        $this->command->info('- Draft: ' . Feedback::where('status', 'draft')->count());
        $this->command->info('- Submitted: ' . Feedback::where('status', 'submitted')->count());
        $this->command->info('- Under Review: ' . Feedback::where('status', 'under_review')->count());
        $this->command->info('- Responded: ' . Feedback::where('status', 'responded')->count());
    }
}
