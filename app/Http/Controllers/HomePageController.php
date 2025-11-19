<?php

namespace App\Http\Controllers;

use App\Models\ProgramAchievement;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get programs and achievements
        $programs = ProgramAchievement::active()
                          ->programs()
                          ->published()
                          ->latest()
                          ->limit(6)
                          ->get();

        $achievements = ProgramAchievement::active()
                             ->achievements()
                             ->published()
                             ->latest()
                             ->limit(6)
                             ->get();

        return view('homepage', compact('latestNews', 'programs', 'achievements'));
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
        //
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
     * Programs Page
     */
    public function programs(Request $request)
    {
        $year = $request->get('year', 'all');
        $type = $request->get('type', 'program');
        
        $programs = ProgramAchievement::active()
            ->published()
            ->byType($type)
            ->byYear($year)
            ->latestPrograms()
            ->paginate(20);

        $availableYears = ProgramAchievement::getAvailableYears($type);
        $types = ProgramAchievement::TYPES;

        return view('programs.index', compact('programs', 'availableYears', 'types', 'year', 'type'));
    }
}
