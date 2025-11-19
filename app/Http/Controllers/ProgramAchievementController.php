<?php

namespace App\Http\Controllers;

use App\Models\ProgramAchievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProgramAchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->get('type');
        $year = $request->get('year');
        $search = $request->get('search');

        $programs = ProgramAchievement::with('author')
            ->when($type, function($query, $type) {
                return $query->where('type', $type);
            })
            ->when($year, function($query, $year) {
                return $query->where('year', $year);
            })
            ->when($search, function($query, $search) {
                return $query->where('title', 'like', "%{$search}%")
                           ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20);

        $availableProgramYears = ProgramAchievement::getAvailableYears();
        $types = ProgramAchievement::TYPES;

        return view('pages.program-achievements.index', compact('programs', 'availableProgramYears', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ProgramAchievement::CATEGORIES;
        $years = ProgramAchievement::getYears();
        $types = ProgramAchievement::TYPES;

        return view('pages.program-achievements.create', compact('categories', 'years', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
            'type' => 'required|in:program,achievement',
            'category' => 'required|string|max:255',
            'year' => 'required|digits:4|integer|min:2020|max:'.(date('Y')+1),
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('programs', 'public');
        }

        ProgramAchievement::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'type' => $request->type,
            'category' => $request->category,
            'year' => $request->year,
            'order' => $request->order ?? 0,
            'is_active' => $request->is_active ?? true,
            'published_at' => $request->published_at ?? now(),
            'created_by' => Auth::id()
        ]);

        return redirect()->route('pages.program-achievements.index')
            ->with('success', ucfirst($request->type) . ' berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $program = ProgramAchievement::active()
            ->published()
            ->findOrFail($id);

        // Get related programs
        $relatedPrograms = ProgramAchievement::active()
            ->published()
            ->where('type', $program->type)
            ->where('id', '!=', $program->id)
            ->where('year', $program->year)
            ->limit(6)
            ->get();

        return view('pages.program-achievements.show', compact('program', 'relatedPrograms'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $program = ProgramAchievement::findOrFail($id);
        $categories = ProgramAchievement::CATEGORIES;
        $years = ProgramAchievement::getYears();
        $types = ProgramAchievement::TYPES;

        return view('pages.program-achievements.edit', compact('program', 'categories', 'years', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $program = ProgramAchievement::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'type' => 'required|in:program,achievement',
            'category' => 'required|string|max:255',
            'year' => 'required|digits:4|integer|min:2020|max:'.(date('Y')+1),
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'published_at' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $imagePath = $program->image;
        if ($request->hasFile('image')) {
            // Delete old image
            if ($program->image) {
                Storage::disk('public')->delete($program->image);
            }
            $imagePath = $request->file('image')->store('programs', 'public');
        }

        $program->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath,
            'type' => $request->type,
            'category' => $request->category,
            'year' => $request->year,
            'order' => $request->order ?? 0,
            'is_active' => $request->is_active ?? true,
            'published_at' => $request->published_at
        ]);

        return redirect()->route('pages.program-achievements.index')
            ->with('success', ucfirst($program->type) . ' berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $program = ProgramAchievement::findOrFail($id);

        // Delete image
        if ($program->image) {
            Storage::disk('public')->delete($program->image);
        }

        $program->delete();

        return redirect()->route('pages.program-achievements.index')
            ->with('success', ucfirst($program->type) . ' berhasil dihapus!');
    }

    /**
     * Toggle status
     */
    public function toggleStatus($id)
    {
        $program = ProgramAchievement::findOrFail($id);
        $program->update(['is_active' => !$program->is_active]);

        $status = $program->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('pages.program-achievements.index')
            ->with('success', ucfirst($program->type) . " berhasil {$status}!");
    }

    /**
     * Get programs for landing page
     */
    public function getPrograms()
    {
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

        return compact('programs', 'achievements');
    }

    /**
     * Get programs for landing page
     */
    public function getLandingPageData(Request $request)
    {
        $programYear = $request->get('program_year', date('Y'));
        $achievementYear = $request->get('achievement_year', date('Y'));

        $programs = ProgramAchievement::active()
            ->programs()
            ->published()
            ->byYear($programYear)
            ->latestPrograms()
            ->limit(20)
            ->get();

        $achievements = ProgramAchievement::active()
            ->achievements()
            ->published()
            ->byYear($achievementYear)
            ->latestPrograms()
            ->limit(20)
            ->get();

        $availableProgramYears = ProgramAchievement::getAvailableYears('program');
        $availableAchievementYears = ProgramAchievement::getAvailableYears('achievement');

        return compact(
            'programs',
            'achievements',
            'programYear', 
            'achievementYear',
            'availableProgramYears',
            'availableAchievementYears'
        );
    }
}
