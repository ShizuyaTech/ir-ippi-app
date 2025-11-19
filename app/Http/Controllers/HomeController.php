<?php

namespace App\Http\Controllers;

use App\Models\ProgramAchievement;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Get available years for programs and achievements
        $availableProgramYears = ProgramAchievement::where('type', 'program')
            ->distinct()
            ->pluck('year')
            ->sortDesc()
            ->values()
            ->toArray();

        $availableAchievementYears = ProgramAchievement::where('type', 'achievement')
            ->distinct()
            ->pluck('year')
            ->sortDesc()
            ->values()
            ->toArray();

        // Get selected year from query parameters or use 'all'
        $programYear = $request->query('program_year', 'all');
        $achievementYear = $request->query('achievement_year', 'all');

        // Query for programs
        $programsQuery = ProgramAchievement::where('type', 'program')
            ->where('is_active', true)
            ->orderBy('year', 'desc')
            ->orderBy('created_at', 'desc');

        if ($programYear !== 'all') {
            $programsQuery->where('year', $programYear);
        }

        $programs = $programsQuery->limit(20)->get();

        // Query for achievements
        $achievementsQuery = ProgramAchievement::where('type', 'achievement')
            ->where('is_active', true)
            ->orderBy('year', 'desc')
            ->orderBy('created_at', 'desc');

        if ($achievementYear !== 'all') {
            $achievementsQuery->where('year', $achievementYear);
        }

        $achievements = $achievementsQuery->limit(20)->get();

        // Get latest news
        $latestNews = News::where('is_active', true)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('homepage', compact(
            'programs',
            'achievements',
            'availableProgramYears',
            'availableAchievementYears',
            'programYear',
            'achievementYear',
            'latestNews'
        ));
    }
}