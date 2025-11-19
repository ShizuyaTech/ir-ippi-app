<?php
namespace App\Services;

use App\Models\News;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NewsService
{
    public function fetchAutomotiveNews($source = 'cnn', $limit = 10)
    {
        // ✅ OPTIMIZED: Use caching to avoid repeated API calls
        // Cache for 1 hour
        $cacheKey = "automotive_news_{$source}_{$limit}";
        
        return \Illuminate\Support\Facades\Cache::remember(
            $cacheKey,
            now()->addHour(),
            function () use ($source, $limit) {
                try {
                    switch ($source) {
                        case 'cnn':
                            return $this->fetchCnnOtoNews($limit);
                        case 'oto':
                            return $this->fetchOtoNews($limit);
                        case 'republika':
                            return $this->fetchRepublikaOtoNews($limit);
                        case 'tribun':
                            return $this->fetchTribunOtoNews($limit);
                        default:
                            return $this->fetchCnnOtoNews($limit);
                    }
                } catch (\Exception $e) {
                    Log::error('Error fetching automotive news from ' . $source . ': ' . $e->getMessage());
                    return [];
                }
            }
        );
    }

    private function fetchCnnOtoNews($limit = 10)
    {
        // ✅ OPTIMIZED: Add timeout and retry logic
        $apiUrl = 'https://newsdata.io/api/1/news';
        $apiKey = config('services.news.api_key');
        
        try {
            $response = Http::timeout(10)  // ✅ Reduced from 30s to 10s
                ->retry(2, 100)             // ✅ Retry if fails
                ->get($apiUrl, [
                    'apikey' => $apiKey,
                    'q' => 'otomotif,mobil,motor',
                    'language' => 'id',
                    'country' => 'id',
                    'size' => $limit,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $newsItems = [];

                if (isset($data['results']) && is_array($data['results'])) {
                    foreach ($data['results'] as $article) {
                        // Skip if no title
                        if (empty($article['title'])) continue;

                        $newsItems[] = [
                            'title' => $article['title'],
                            'summary' => $article['description'] ?? $article['title'],
                            'content' => $article['content'] ?? $article['description'] ?? $article['title'],
                            'source' => $article['source_id'] ?? 'NewsData.io',
                            'source_url' => $article['link'] ?? '',
                            'author' => $article['creator'][0] ?? $article['source_id'] ?? 'Editor',
                            'image_url' => $article['image_url'] ?? null,
                            'published_at' => Carbon::parse($article['pubDate']),
                            'category' => 'automotive',
                            'is_external' => true,
                            'is_active' => true
                        ];
                    }
                }

                return $newsItems;
            }

            return [];
        } catch (\Exception $e) {
            Log::warning('NewsData.io API call failed: ' . $e->getMessage());
            return [];
        }
    }

    private function fetchOtoNews($limit = 10)
    {
        $rssUrl = 'https://www.oto.com/rss/news';
        
        try {
            $response = Http::timeout(15)  // ✅ Reduced from 30s
                ->retry(1, 50)
                ->get($rssUrl);

            if ($response->successful()) {
                $xml = simplexml_load_string($response->body());
                $newsItems = [];

                $count = 0;
                foreach ($xml->channel->item as $item) {
                    if ($count >= $limit) break;

                    // Extract image from content if available
                    $imageUrl = $this->extractImageFromContent((string) $item->description);

                    $newsItems[] = [
                        'title' => (string) $item->title,
                        'summary' => strip_tags((string) $item->description),
                        'content' => strip_tags((string) $item->description),
                        'source' => 'Oto.com',
                        'source_url' => (string) $item->link,
                        'author' => 'Oto.com',
                        'image_url' => $imageUrl,
                        'published_at' => Carbon::parse((string) $item->pubDate),
                        'category' => 'car_news',
                        'is_external' => true,
                        'is_active' => true
                    ];
                    $count++;
                }

                return $newsItems;
            }

            return [];
        } catch (\Exception $e) {
            Log::warning('Oto.com RSS feed fetch failed: ' . $e->getMessage());
            return [];
        }
    }

    private function fetchRepublikaOtoNews($limit = 10)
    {
        $rssUrl = 'https://republika.co.id/rss/otomotif';
        
        try {
            $response = Http::timeout(15)  // ✅ Reduced from 30s
                ->retry(1, 50)
                ->get($rssUrl);

            if ($response->successful()) {
                $xml = simplexml_load_string($response->body());
                $newsItems = [];

                $count = 0;
                foreach ($xml->channel->item as $item) {
                    if ($count >= $limit) break;

                    // Extract image URL if available
                    $imageUrl = $this->extractImageFromContent((string) $item->description);

                    $newsItems[] = [
                        'title' => (string) $item->title,
                        'summary' => strip_tags((string) $item->description),
                        'content' => strip_tags((string) $item->description),
                        'source' => 'Republika Otomotif',
                        'source_url' => (string) $item->link,
                        'author' => 'Republika',
                        'image_url' => $imageUrl,
                        'published_at' => Carbon::parse((string) $item->pubDate),
                        'category' => 'automotive',
                        'is_external' => true,
                        'is_active' => true
                    ];
                    $count++;
                }

                return $newsItems;
            }

            return [];
        } catch (\Exception $e) {
            Log::warning('Republika RSS feed fetch failed: ' . $e->getMessage());
            return [];
        }
    }

    private function fetchTribunOtoNews($limit = 10)
    {
        $rssUrl = 'https://www.tribunnews.com/rss/otomotif';
        
        try {
            $response = Http::timeout(15)  // ✅ Reduced from 30s
                ->retry(1, 50)
                ->get($rssUrl);

            if ($response->successful()) {
                $xml = simplexml_load_string($response->body());
                $newsItems = [];

                $count = 0;
                foreach ($xml->channel->item as $item) {
                    if ($count >= $limit) break;

                    // Extract image URL if available
                    $imageUrl = $this->extractImageFromContent((string) $item->description);

                    $newsItems[] = [
                        'title' => (string) $item->title,
                        'summary' => strip_tags((string) $item->description),
                        'content' => strip_tags((string) $item->description),
                        'source' => 'Tribun Otomotif',
                        'source_url' => (string) $item->link,
                        'author' => 'Tribun',
                        'image_url' => $imageUrl,
                        'published_at' => Carbon::parse((string) $item->pubDate),
                        'category' => 'automotive',
                        'is_external' => true,
                        'is_active' => true
                    ];
                    $count++;
                }

                return $newsItems;
            }

            return [];
        } catch (\Exception $e) {
            Log::warning('Tribun RSS feed fetch failed: ' . $e->getMessage());
            return [];
        }
    }

    private function extractImageFromContent($content)
    {
        preg_match('/<img[^>]+src="([^">]+)"/', $content, $matches);
        return $matches[1] ?? null;
    }

    public function saveFetchedNews($newsItems)
    {
        $savedCount = 0;

        foreach ($newsItems as $newsData) {
            $existingNews = News::where('title', $newsData['title'])
                ->where('source', $newsData['source'])
                ->first();

            if (!$existingNews) {
                News::create($newsData);
                $savedCount++;
            }
        }

        return $savedCount;
    }

    public function fetchAllAutomotiveNews($limitPerSource = 5)
    {
        $totalSaved = 0;
        $sources = ['cnn', 'oto', 'republika', 'tribun'];

        foreach ($sources as $source) {
            try {
                $newsItems = $this->fetchAutomotiveNews($source, $limitPerSource);
                $saved = $this->saveFetchedNews($newsItems);
                $totalSaved += $saved;
                
                Log::info("Successfully fetched and saved {$saved} news items from {$source}");
            } catch (\Exception $e) {
                Log::error("Error fetching news from {$source}: " . $e->getMessage());
            }
        }

        return $totalSaved;
    }

    /**
     * Fungsi untuk menjalankan pengambilan berita otomatis
     * Dipanggil oleh scheduler setiap 12 jam
     */
    public function runAutoFetch()
    {
        Log::info('Starting automatic news fetch');
        
        try {
            $totalSaved = $this->fetchAllAutomotiveNews(10);
            Log::info("Auto fetch completed. Total {$totalSaved} news saved.");
            
            return [
                'status' => 'success',
                'total_saved' => $totalSaved,
                'message' => "Successfully fetched and saved {$totalSaved} news items"
            ];
        } catch (\Exception $e) {
            Log::error('Auto fetch failed: ' . $e->getMessage());
            
            return [
                'status' => 'error',
                'message' => 'Auto fetch failed: ' . $e->getMessage()
            ];
        }
    }

    public function searchAutomotiveNews($keywords, $limit = 10)
    {
        $newsItems = $this->fetchAutomotiveNews('autocar', 20);

        $filteredNews = array_filter($newsItems, function ($item) use ($keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($item['title'], $keyword) !== false || 
                    stripos($item['summary'], $keyword) !== false) {
                    return true;
                }
            }
            return false;
        });

        return array_slice($filteredNews, 0, $limit);
    }
}