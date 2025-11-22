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
                ];
            });

            Log::info('HomeController: Homepage stats loaded', $homepageData);

            // ✅ OPTIMIZED: Cache active assessments for navigation (shared with other views)
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
            return view('pages.home', array_merge($homepageData, compact('activeAssessments')));

        } catch (\Exception $e) {
            Log::error('HomeController Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return view with default values if error occurs
            return view('pages.home', [
                'totalAssessments' => 0,
                'totalFeedbacks' => 0,
                'totalSchedules' => 0,
                'totalLks' => 0,
                'activeAssessments' => 0,
                'activeSchedules' => 0,
                'completedLks' => 0,
                'pendingFeedbacks' => 0,
                'activeAssessments' => collect()
            ]);
        }
    }
}
