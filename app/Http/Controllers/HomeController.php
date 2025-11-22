<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Feedback;
use App\Models\LksBipartit;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Display the home page with statistics
     */
public function index()
{
    try {
        Log::info('HomeController: Starting homepage load');

        // ✅ OPTIMIZED: Cache homepage data for 10 minutes
        $homepageData = Cache::remember('homepage_data', now()->addMinutes(10), function () {
            Log::info('HomeController: Calculating homepage stats');
            return [
                'totalAssessments' => Assessment::count(),
                'totalFeedbacks' => Feedback::count(),
                'totalSchedules' => Schedule::count(),
                'totalLks' => LksBipartit::count(),
                'activeAssessments' => Assessment::where('is_active', true)->count(),
                'activeSchedules' => Schedule::where('status', 'active')->count(),
                'completedLks' => LksBipartit::where('status', 'done')->count(),
                'pendingFeedbacks' => Feedback::where('status', 'submitted')->count(),
                // ✅ ADD LATEST NEWS
                'latestNews' => \App\Models\News::latest()->limit(5)->get(), // Ganti dengan model yang sesuai
                // ✅ ADD AVAILABLE PROGRAM YEARS
                'availableProgramYears' => \App\Models\ProgramAchievement::distinct()->pluck('year')->sortDesc(), // Ganti dengan model yang sesuai
                'programs' => \App\Models\ProgramAchievement::where('is_active', true)->latest()->limit(10)->get() ?? collect(),
                'availableAchievementYears' => \App\Models\ProgramAchievement::distinct()->pluck('year')->sortDesc(), // Ganti dengan model yang sesuai
                'achievements' => \App\Models\ProgramAchievement::where('is_active', true)->latest()->limit(6)->get() ?? collect(),
            ];
        });

        Log::info('HomeController: Homepage stats loaded', $homepageData);

        // ✅ OPTIMIZED: Cache active assessments for navigation
        $activeAssessments = Cache::remember(
            'active_assessments_nav',
            now()->addHour(),
            function () {
                Log::info('HomeController: Loading active assessments');
                return Assessment::select('id', 'title')
                    ->where('is_active', true)
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
            }
        );

        Log::info('HomeController: Active assessments loaded, count: ' . $activeAssessments->count());

        Log::info('HomeController: Returning view');
        return view('homepage', array_merge($homepageData, compact('activeAssessments')));

    } catch (\Exception $e) {
        Log::error('HomeController Error: ' . $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        // Return view with default values if error occurs
        return view('homepage', [
            'totalAssessments' => 0,
            'totalFeedbacks' => 0,
            'totalSchedules' => 0,
            'totalLks' => 0,
            'activeAssessments' => collect(),
            'activeSchedules' => 0,
            'completedLks' => 0,
            'pendingFeedbacks' => 0,
            'latestNews' => collect(), // ✅ ADD EMPTY COLLECTION
            'availableProgramYears' => collect(), // ✅ ADD EMPTY COLLECTION
            'programs' => collect(), // ✅ ADD EMPTY COLLECTION
            'availableAchievementYears' => collect(), // ✅ ADD EMPTY COLLECTION
            'achievements' => collect(), // ✅ ADD EMPTY COLLECTION
        ]);
    }
}           
}
