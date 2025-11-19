<?php

namespace Database\Seeders;

use App\Models\News;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run()
    {
        $sampleNews = [
            [
                'title' => 'Hyundai Stargazer X Resmi Mengaspal, Tampil Lebih Gagah',
                'summary' => 'PT Hyundai Motors Indonesia (HMID) resmi meluncurkan Hyundai Stargazer X, varian baru dari MPV andalannya yang tampil lebih gagah.',
                'content' => 'PT Hyundai Motors Indonesia (HMID) resmi meluncurkan Hyundai Stargazer X, varian baru dari MPV andalannya yang tampil lebih gagah. Dengan berbagai peningkatan fitur dan desain yang lebih modern, Stargazer X siap bersaing di segmen MPV Indonesia.',
                'source' => 'Sample News',
                'source_url' => 'https://example.com/news/1',
                'author' => 'Admin',
                'published_at' => Carbon::now()->subDays(1),
                'category' => 'car_news',
                'is_external' => false,
                'is_active' => true,
                'image_url' => 'https://source.unsplash.com/800x600/?hyundai,car',
            ],
            [
                'title' => 'BMW Seri 5 Generasi Terbaru Siap Meluncur di Indonesia',
                'summary' => 'BMW Indonesia bersiap meluncurkan Seri 5 generasi terbaru yang mengusung teknologi elektrifikasi dan fitur canggih.',
                'content' => 'BMW Indonesia bersiap meluncurkan Seri 5 generasi terbaru yang mengusung teknologi elektrifikasi dan fitur canggih. Model ini akan hadir dengan pilihan mesin konvensional dan hybrid untuk memenuhi kebutuhan konsumen premium Indonesia.',
                'source' => 'Sample News',
                'source_url' => 'https://example.com/news/2',
                'author' => 'Admin',
                'published_at' => Carbon::now()->subDays(2),
                'category' => 'car_news',
                'is_external' => false,
                'is_active' => true,
                'image_url' => 'https://source.unsplash.com/800x600/?bmw,luxury-car',
            ],
            [
                'title' => 'Toyota Innova Zenix Hybrid Tembus 10 Ribu Unit',
                'summary' => 'Penjualan Toyota Innova Zenix hybrid mencapai milestone 10 ribu unit, membuktikan tingginya minat konsumen terhadap kendaraan ramah lingkungan.',
                'content' => 'Penjualan Toyota Innova Zenix hybrid mencapai milestone 10 ribu unit, membuktikan tingginya minat konsumen terhadap kendaraan ramah lingkungan. Toyota terus berkomitmen menghadirkan inovasi berkelanjutan untuk pasar Indonesia.',
                'source' => 'Sample News',
                'source_url' => 'https://example.com/news/3',
                'author' => 'Admin',
                'published_at' => Carbon::now()->subDays(3),
                'category' => 'car_news',
                'is_external' => false,
                'is_active' => true,
                'image_url' => 'https://source.unsplash.com/800x600/?toyota,hybrid-car',
            ],
        ];

        foreach ($sampleNews as $news) {
            News::create($news);
        }
    }
}