<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Question;
use App\Models\ResponseAssessment;
use App\Models\AssessmentUserCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get all assessments with pagination and search
        $assessments = Assessment::withCount(['questions', 'responses', 'userCodes'])
            ->when($request->input('title'), function ($query, $title) {
                $searchTerm = '%' . $title . '%';
                
                $query->where(function($q) use ($searchTerm) {
                    $q->where('title', 'like', $searchTerm)
                      ->orWhere('description', 'like', $searchTerm);
                });

            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $isAdmin = $this->isAdmin(Auth::user()); // Using the helper method defined below


        return view('pages.assessments.index', compact('assessments', 'isAdmin'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.assessments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'participant_count' => 'required|integer|min:1|max:1000',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after:start_date',
        'is_active' => 'sometimes|boolean'
    ]);

    try {
        DB::beginTransaction();

        // Create assessment
        $assessment = Assessment::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'participant_count' => $validated['participant_count'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => $request->boolean('is_active', true)
        ]);

        // Generate user codes otomatis
        $codes = AssessmentUserCode::generateForAssessment(
            $assessment->id,
            $validated['participant_count']
        );

        DB::commit();

        return redirect()->route('assessments.edit', $assessment->id)
            ->with('success', "Assessment created successfully! {$codes->count()} user codes generated.");

    } catch (\Exception $e) {
        DB::rollBack();
        
        return redirect()->back()
            ->with('error', 'Failed to create assessment: ' . $e->getMessage())
            ->withInput();
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // ✅ OPTIMIZED: Use withCount() instead of eager loading all relationships
        // This is much faster for getting statistics
        $assessment = Assessment::withCount([
            'questions',
            'responses', 
            'userCodes'
        ])
        ->findOrFail($id);

        // ✅ OPTIMIZED: Get specific counts using single query
        $userCodes = $assessment->userCodes()
            ->select('code', 'is_used', 'used_at', 'used_by', 'expires_at')
            ->with('user:id,name,email')
            ->latest()
            ->paginate(20);
        
        // Get response statistics - now using aggregation instead of extra queries
        $responseStats = [
            'total_responses' => $assessment->responses_count,
            'used_codes' => $assessment->userCodes()->where('is_used', true)->count(),
            'available_codes' => $assessment->userCodes()->where('is_used', false)->count(),
        ];

        return view('pages.assessments.show', compact('assessment', 'userCodes', 'responseStats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $assessment = Assessment::with('questions')->findOrFail($id);
        return view('pages.assessments.edit', compact('assessment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'sometimes|boolean'
        ]);

        $assessment = Assessment::findOrFail($id);
        
        $assessment->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->route('assessments.index')->with('success', 'Assessment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $assessment = Assessment::findOrFail($id);
        $assessment->delete();

        return redirect()->route('assessments.index')->with('success', 'Assessment deleted successfully');
    }

    // USER CODE MANAGEMENT METHODS

    /**
     * Generate additional user codes
     */
    public function generateAdditionalCodes(Request $request, Assessment $assessment)
    {
        $request->validate([
            'additional_count' => 'required|integer|min:1|max:100'
        ]);

        try {
            $additionalCount = $request->input('additional_count');
            
            // Generate codes tambahan
            $newCodes = AssessmentUserCode::generateForAssessment($assessment->id, $additionalCount);

            // Update participant count
            $assessment->update([
                'participant_count' => $assessment->participant_count + $additionalCount
            ]);

            return back()->with('success', "Berhasil menambahkan {$additionalCount} user codes!");

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate additional codes: ' . $e->getMessage());
        }
    }

    /**
     * Download user codes sebagai CSV
     */
    public function downloadCodes(Assessment $assessment)
    {
        $codes = $assessment->userCodes()
            ->select('code', 'is_used', 'used_at', 'used_by', 'expires_at')
            ->with('user:id,name,email')
            ->get();

        $fileName = "user-codes-assessment-{$assessment->id}-" . now()->format('Y-m-d') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($codes) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Header CSV
            fputcsv($file, ['Kode User', 'Status', 'Digunakan Oleh', 'Email', 'Tanggal Digunakan', 'Kadaluarsa']);
            
            // Data
            foreach ($codes as $code) {
                fputcsv($file, [
                    $code->code,
                    $code->is_used ? 'Digunakan' : 'Tersedia',
                    $code->user ? $code->user->name : '-',
                    $code->user ? $code->user->email : '-',
                    $code->used_at ? $code->used_at->format('d/m/Y H:i') : '-',
                    $code->expires_at ? $code->expires_at->format('d/m/Y') : '-'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Toggle assessment status
     */
    public function toggleStatus(Assessment $assessment)
    {
        $assessment->update([
            'is_active' => !$assessment->is_active
        ]);

        $status = $assessment->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "Assessment successfully {$status}");
    }

    // QUESTION MANAGEMENT METHODS

    /**
     * Store a new question
     */
    public function storeQuestion(Request $request, Assessment $assessment)
    {
        $request->validate([
            'question_text' => 'required|string|max:1000'
        ]);

        try {
            $question = Question::create([
                'assessment_id' => $assessment->id,
                'question_text' => $request->question_text,
                'description' => null,
                'order' => $assessment->questions()->count() + 1,
                'is_active' => true
            ]);

            return redirect()->route('assessments.edit', $assessment->id)
                ->with('success', 'Question added successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add question: ' . $e->getMessage());
        }
    }

    /**
     * Update a question
     */
    public function updateQuestion(Request $request, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string|max:1000',
            'is_active' => 'sometimes|boolean'
        ]);

        try {
            $question->update([
                'question_text' => $request->question_text,
                'is_active' => $request->boolean('is_active')
            ]);

            // Jika request AJAX, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Question updated successfully'
                ]);
            }

            return redirect()->route('assessments.edit', $question->assessment_id)
                ->with('success', 'Question updated successfully!');

        } catch (\Exception $e) {
            // Jika request AJAX, return JSON error
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update question: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to update question: ' . $e->getMessage());
        }
    }

    /**
     * Delete a question
     */
    public function destroyQuestion(Question $question)
    {
        try {
            $assessmentId = $question->assessment_id;
            $question->delete();

            return redirect()->route('assessments.edit', $assessmentId)
                ->with('success', 'Question deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete question: ' . $e->getMessage());
        }
    }

    // PUBLIC ASSESSMENT FORM METHOD (untuk route yang sudah ada)
    public function showForm(Assessment $assessment, $page = null)
    {
        // Method ini untuk handle route yang sudah ada
        // Anda bisa redirect ke PublicAssessmentController atau implementasi sederhana
        return redirect()->route('assessment.form', $assessment);
    }

    public function responses(Assessment $assessment)
    {
        $responses = ResponseAssessment::where('assessment_id', $assessment->id)
            ->with(['user', 'question'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get unique respondents
        $respondents = ResponseAssessment::where('assessment_id', $assessment->id)
            ->select('user_code', 'user_id', DB::raw('MAX(created_at) as last_response'))
            ->groupBy('user_code', 'user_id')
            ->with('user')
            ->get();

        // Response statistics by rating
        $ratingStats = ResponseAssessment::where('assessment_id', $assessment->id)
            ->select('rating', DB::raw('COUNT(*) as count'))
            ->groupBy('rating')
            ->get()
            ->pluck('count', 'rating');

        return view('pages.assessments.responses', compact('assessment', 'responses', 'respondents', 'ratingStats'));
    }

    /**
     * Determine if the given user is an admin.
     */
    protected function isAdmin($user): bool
    {
        if (!$user) {
            return false;
        }

        // If the user model exposes an is_admin attribute
        if (isset($user->is_admin)) {
            return (bool) $user->is_admin;
        }

        // If the user model uses a role attribute
        if (isset($user->role) && $user->role === 'admin') {
            return true;
        }

        // If using a roles package like Spatie
        if (method_exists($user, 'hasRole')) {
            return (bool) $user->hasRole('admin');
        }

        return false;
    }
}