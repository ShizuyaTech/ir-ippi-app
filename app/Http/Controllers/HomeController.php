<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Feedback;
use App\Models\LksBipartit;
use App\Models\ProgramAchievement;
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

            // ✅ OPTIMIZED: Cache homepage data for 30 minutes
            $homepageData = Cache::remember('homepage_data_optimized', now()->addMinutes(30), function () {
                Log::info('HomeController: Calculating homepage stats');
                
                // ✅ OPTIMIZED: Single query untuk semua data ProgramAchievement
                $programAchievementData = $this->getOptimizedProgramAchievementData();
                
                return [
                    'totalAssessments' => Assessment::count(),
                    'totalFeedbacks' => Feedback::count(),
                    'totalSchedules' => Schedule::count(),
                    'totalLks' => LksBipartit::count(),
                    'activeAssessments' => Assessment::where('is_active', true)->count(),
                    'activeSchedules' => Schedule::where('status', 'active')->count(),
                    'completedLks' => LksBipartit::where('status', 'done')->count(),
                    'pendingFeedbacks' => Feedback::where('status', 'submitted')->count(),
                    
                    // ✅ OPTIMIZED ProgramAchievement data
                    'availableProgramYears' => $programAchievementData['years'],
                    'availableAchievementYears' => $programAchievementData['years'], // Sama saja
                    'programs' => $programAchievementData['programs'],
                    'achievements' => $programAchievementData['achievements'],
                    
                    'latestNews' => \App\Models\News::select('id', 'title', 'slug', 'created_at')
                        ->latest()
                        ->limit(5)
                        ->get() ?? collect(),
                ];
            });

            // ✅ OPTIMIZED: Cache active assessments
            $activeAssessments = Cache::remember(
                'active_assessments_nav',
                now()->addHour(),
                function () {
                    return Assessment::select('id', 'title')
                        ->where('is_active', true)
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();
                }
            );

            Log::info('HomeController: Returning optimized view');
            return view('homepage', array_merge($homepageData, compact('activeAssessments')));

        } catch (\Exception $e) {
            Log::error('HomeController Error: ' . $e->getMessage());
            return view('homepage', $this->getDefaultHomepageData());
        }
    }

    /**
     * Get optimized ProgramAchievement data in single query
     */
    private function getOptimizedProgramAchievementData()
    {
        // ✅ SINGLE QUERY untuk semua data
        $baseQuery = ProgramAchievement::where('is_active', true)
            ->select('id', 'title', 'image', 'year', 'category', 'type', 'short_description', 'created_at');
        
        // Get years sekali saja
        $years = ProgramAchievement::where('is_active', true)
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        // Get programs dan achievements dari cache query yang sama
        $allData = $baseQuery->latest()->limit(16)->get();
        
        return [
            'years' => $years,
            'programs' => $allData->where('type', 'program')->take(8),
            'achievements' => $allData->where('type', 'achievement')->take(6),
        ];
    }

    /**
     * Default data for error fallback
     */
    private function getDefaultHomepageData()
    {
        return [
            'totalAssessments' => 0,
            'totalFeedbacks' => 0,
            'totalSchedules' => 0,
            'totalLks' => 0,
            'activeAssessments' => collect(),
            'activeSchedules' => 0,
            'completedLks' => 0,
            'pendingFeedbacks' => 0,
            'latestNews' => collect(),
            'availableProgramYears' => collect(),
            'programs' => collect(),
            'availableAchievementYears' => collect(),
            'achievements' => collect(),
        ];
    }           
}
