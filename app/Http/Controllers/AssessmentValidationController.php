<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentUser;
use App\Models\AssessmentUserCode;
use Illuminate\Http\Request;

class AssessmentValidationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
     * Show validation form
     */
    public function showValidationForm(Assessment $assessment)
    {
        return view('pages.assessments.validation-form', compact('assessment'));
    }

    /**
     * Validate user code
     */
    public function validateUser(Request $request, Assessment $assessment)
{
    $request->validate([
        'user_code' => 'required|string|max:20'
    ]);

    try {
        // Cari user code yang valid
        $userCode = AssessmentUserCode::where('code', $request->user_code)
            ->where('assessment_id', $assessment->id)
            ->active()
            ->first();

        if (!$userCode) {
            return redirect()->back()
                ->with('error', 'Kode user tidak valid, sudah digunakan, atau sudah kedaluwarsa.')
                ->withInput();
        }

        // Simpan user code yang valid di session
        session([
            'validated_assessment_id' => $assessment->id,
            'validated_user_code' => $userCode->code,
            'validation_expires_at' => now()->addHours(2) // Valid selama 2 jam
        ]);

        return redirect()->route('assessment.form', $assessment)
            ->with('success', 'Kode user valid! Silakan mengisi assessment.');

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
            ->withInput();
    }
}
}
