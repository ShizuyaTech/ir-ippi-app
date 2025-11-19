<?php

namespace App\Console\Commands;

use App\Services\NewsService;
use Illuminate\Console\Command;

class FetchAutomotiveNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch-automotive {--source=all} {--limit=10}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch latest automotive news from external sources';

    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        parent::__construct();
        $this->newsService = $newsService;
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $source = $this->option('source');
        $limit = $this->option('limit');

        $this->info('Memulai pengambilan berita otomotif...');

        if ($source === 'all') {
            $savedCount = $this->newsService->fetchAllAutomotiveNews($limit);
        } else {
            $newsItems = $this->newsService->fetchAutomotiveNews($source, $limit);
            $savedCount = $this->newsService->saveFetchedNews($newsItems);
        }

        $this->info("Berhasil menyimpan {$savedCount} berita otomotif baru.");

        return Command::SUCCESS;
    }
}
