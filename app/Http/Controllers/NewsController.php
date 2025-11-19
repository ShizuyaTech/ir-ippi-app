<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Services\NewsService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $category = $request->get('category');
        $search = $request->get('search');
        
        $news = News::active()
                    ->automotive() // Hanya berita otomotif
                    ->latestNews()
                    ->when($category, function($query, $category) {
                        return $query->where('category', $category);
                    })
                    ->when($search, function($query, $search) {
                        return $query->where('title', 'like', "%{$search}%")
                                   ->orWhere('summary', 'like', "%{$search}%");
                    })
                    ->paginate(12);

        $categories = News::CATEGORIES;
        $popularNews = News::active()
                          ->automotive()
                          ->latestNews()
                          ->orderBy('view_count', 'desc')
                          ->limit(5)
                          ->get();

        return view('news.index', compact('news', 'categories', 'popularNews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $news = News::active()->automotive()->findOrFail($id);
        
        // Increment view count
        $news->incrementViewCount();
        
        $relatedNews = News::active()
                          ->automotive()
                          ->where('category', $news->category)
                          ->where('id', '!=', $news->id)
                          ->latestNews()
                          ->limit(4)
                          ->get();

        return view('news.show', compact('news', 'relatedNews'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Admin: Manual fetch automotive news
     */
    public function fetchNews()
    {
        try {
            $savedCount = $this->newsService->fetchAllAutomotiveNews(5);
            
            return redirect()->route('news.index')
                           ->with('success', "Berhasil mengambil {$savedCount} berita otomotif terbaru.");
        } catch (\Exception $e) {
            return redirect()->route('news.index')
                           ->with('error', 'Gagal mengambil berita otomotif: ' . $e->getMessage());
        }
    }

    /**
     * Search automotive news by specific topics
     */
    public function searchByTopic(Request $request)
    {
        $topic = $request->get('topic', 'industri otomotif');
        
        $keywords = [
            'industri otomotif' => ['industri', 'otomotif', 'mobil', 'motor', 'produksi'],
            'kendaraan listrik' => ['listrik', 'elektrik', 'EV', 'baterai', 'charger'],
            'harga kendaraan' => ['harga', 'price', 'terbaru', 'diskron', 'promo'],
            'teknologi' => ['teknologi', 'fitur', 'digital', 'connected', 'smart'],
            'safety' => ['safety', 'keamanan', 'NCAP', 'crash test', 'airbag']
        ];

        $selectedKeywords = $keywords[$topic] ?? $keywords['industri otomotif'];
        
        $news = News::active()
                    ->automotive()
                    ->where(function($query) use ($selectedKeywords) {
                        foreach ($selectedKeywords as $keyword) {
                            $query->orWhere('title', 'like', "%{$keyword}%")
                                  ->orWhere('summary', 'like', "%{$keyword}%");
                        }
                    })
                    ->latestNews()
                    ->paginate(12);

        $categories = News::CATEGORIES;

        return view('news.topic', compact('news', 'categories', 'topic'));
    }

    /**
     * Show industry insights (special section)
     */
    public function industryInsights()
    {
        $insightNews = News::active()
                          ->automotive()
                          ->where('category', 'industry')
                          ->orWhere(function($query) {
                              $query->where('title', 'like', '%industri%')
                                    ->orWhere('title', 'like', '%market%')
                                    ->orWhere('title', 'like', '%ekonomi%');
                          })
                          ->latestNews()
                          ->paginate(10);

        $marketTrends = News::active()
                           ->automotive()
                           ->where(function($query) {
                               $query->where('title', 'like', '%trend%')
                                     ->orWhere('title', 'like', '%market%')
                                     ->orWhere('title', 'like', '%analisis%');
                           })
                           ->latestNews()
                           ->limit(5)
                           ->get();

        return view('news.industry-insights', compact('insightNews', 'marketTrends'));
    }

    public function home()
    {
        // Get featured automotive news for landing page
        $featuredNews = News::active()
                           ->automotive()
                           ->latestNews()
                           ->limit(6)
                           ->get();

        // Get latest news for news section
        $latestNews = News::active()
                         ->automotive()
                         ->latestNews()
                         ->limit(9) // Untuk 3x3 grid
                         ->get();

        return view('welcome', compact('featuredNews', 'latestNews'));
    }
}
