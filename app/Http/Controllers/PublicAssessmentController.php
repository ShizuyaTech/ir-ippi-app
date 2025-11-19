<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentUser;
use App\Models\AssessmentUserCode;
use App\Models\Question;
use App\Models\ResponseAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PublicAssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assessments = Assessment::withCount('questions')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.assessments.public-index', compact('assessments'));
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

    public function showForm(Assessment $assessment, $page = 1)
{
    // Cek validasi session
    $validatedCode = session('validated_user_code');
    $validatedAssessmentId = session('validated_assessment_id');
    $validationExpires = session('validation_expires_at');

    if (!$validatedCode || $validatedAssessmentId != $assessment->id || now()->gt($validationExpires)) {
        return redirect()->route('assessment.validate.form', $assessment)
            ->with('error', 'Silakan validasi kode user terlebih dahulu atau sesi validasi telah berakhir.');
    }

    // Cek apakah assessment aktif
    if (!$assessment->is_active) {
        return redirect()->route('assessments.public')
            ->with('error', 'Assessment ini tidak aktif.');
    }

    // Get questions dengan pagination
    $questions = $assessment->questions()
                          ->where('is_active', true)
                          ->orderBy('order')
                          ->paginate(10, ['*'], 'page', $page);

    $totalQuestions = $assessment->questions()->where('is_active', true)->count();

    if ($questions->isEmpty()) {
        return redirect()->route('assessments.public')
            ->with('error', 'Tidak ada pertanyaan yang tersedia untuk assessment ini.');
    }

    return view('pages.assessments.public-form', compact('assessment', 'questions', 'validatedCode', 'totalQuestions'));
}

/**
 * Submit assessment response with pagination support
 */
    public function submitResponse(Request $request, Assessment $assessment)
{
    // Validasi session terlebih dahulu
    $validatedCode = session('validated_user_code');
    $validatedAssessmentId = session('validated_assessment_id');

    if (!$validatedCode || $validatedAssessmentId != $assessment->id) {
        return redirect()->route('assessment.validate.form', $assessment)
            ->with('error', 'Sesi validasi telah berakhir. Silakan validasi ulang kode user.');
    }

    // Validasi input
    $request->validate([
        'responses' => 'required|array',
        'responses.*.question_id' => 'required|exists:questions,id',
        'responses.*.rating' => 'required|in:sangat_buruk,buruk,cukup,baik,sangat_baik',
        'responses.*.comment' => 'nullable|string|max:500'
    ]);

    try {
        DB::beginTransaction();

        // Validasi user code di database
        $userCode = AssessmentUserCode::where('code', $validatedCode)
            ->where('assessment_id', $assessment->id)
            ->active()
            ->first();

        if (!$userCode) {
            DB::rollBack();
            return redirect()->route('assessment.validate.form', $assessment)
                ->with('error', 'Kode user tidak valid atau sudah digunakan.');
        }

        // Simpan semua responses
        foreach ($request->responses as $responseData) {
            ResponseAssessment::create([
                'user_code' => $validatedCode,
                'user_id' => Auth::id(), // Jika ada user login
                'assessment_id' => $assessment->id,
                'question_id' => $responseData['question_id'],
                'rating' => $responseData['rating'],
                'comment' => $responseData['comment'] ?? null
            ]);
        }

        // Tandai user code sebagai digunakan
        $userCode->markAsUsed(Auth::id());

        // Hapus session validation
        session()->forget([
            'validated_assessment_id',
            'validated_user_code', 
            'validation_expires_at'
        ]);

        DB::commit();

        return redirect()->route('assessment.thankyou', $assessment)
            ->with('success', 'Terima kasih! Assessment Anda telah berhasil disimpan.');

    } catch (\Exception $e) {
        DB::rollBack();
        // \Log::error('Submit response error: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Gagal menyimpan assessment: ' . $e->getMessage())
            ->withInput();
    }
}

    // Tambahkan di AssessmentController jika ingin mempertahankan responses
    private function isResponseSelected($questionId, $ratingValue)
    {
        $responses = session('assessment_responses_' . request()->assessment->id, []);
        foreach ($responses as $response) {
            if ($response['question_id'] == $questionId && $response['rating'] == $ratingValue) {
                return true;
            }
        }
        return false;
    }

    private function getResponseComment($questionId)
    {
        $responses = session('assessment_responses_' . request()->assessment->id, []);
        foreach ($responses as $response) {
            if ($response['question_id'] == $questionId && isset($response['comment'])) {
                return $response['comment'];
            }
        }
        return '';
    }

    public function thankYou(Assessment $assessment)
    {
        return view('pages.assessments.thank-you', compact('assessment'));
    }
}
