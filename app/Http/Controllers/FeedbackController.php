<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $this->isAdmin($user);
        
        if ($isAdmin) {
            // Admin: lihat semua feedback yang sudah disubmit (bukan draft)
            $feedbacks = Feedback::with(['author', 'responder'])
                                ->where('status', '!=', 'draft')
                                ->latest()
                                ->paginate(10);
        } else {
            // Pengurus serikat: hanya lihat feedback mereka sendiri
            $feedbacks = Feedback::with(['author', 'responder'])
                                ->where('created_by', $user->id)
                                ->latest()
                                ->paginate(10);
        }
        
        return view('pages.feedbacks.index', compact('feedbacks', 'isAdmin'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $this->isAdmin($user);
        
        if ($isAdmin) {
            abort(403, 'Hanya pengurus serikat yang dapat membuat feedback.');
        }
        
        return view('pages.feedbacks.create', compact('isAdmin'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if ($this->isAdmin($user)) {
            abort(403, 'Hanya pengurus serikat yang dapat membuat feedback.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:20',
            'category' => 'required|in:kebijakan,kondisi_kerja,fasilitas,komunikasi,lainnya',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:draft,submitted'
        ]);

        $feedbackData = [
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => $request->status,
            'created_by' => $user->id
        ];

        // Jika status submitted, set submitted_at
        if ($request->status === 'submitted') {
            $feedbackData['submitted_at'] = now();
        }

        $feedback = Feedback::create($feedbackData);

        $message = $request->status === 'submitted' 
            ? 'Feedback berhasil dikirim ke manajemen.' 
            : 'Feedback berhasil disimpan sebagai draft.';

        return redirect()->route('feedbacks.index')
                        ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $isAdmin = $this->isAdmin($user);
        
        $feedback = Feedback::with(['author', 'responder'])->findOrFail($id);

        // Cek akses: admin bisa lihat semua yang bukan draft, user hanya lihat miliknya
        if ($isAdmin) {
            // Admin tidak bisa lihat draft
            if ($feedback->status === 'draft') {
                abort(403, 'Admin tidak dapat melihat feedback yang masih draft.');
            }
        } else {
            // User hanya bisa lihat feedback mereka sendiri
            if ($feedback->created_by !== $user->id) {
                abort(403, 'Anda tidak memiliki akses untuk melihat feedback ini.');
            }
        }

        return view('pages.feedbacks.show', compact('feedback', 'isAdmin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        $isAdmin = $this->isAdmin($user);
        
        $feedback = Feedback::findOrFail($id);

        // Hanya pembuat yang bisa edit, dan hanya jika status draft
        if ($feedback->created_by !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit feedback ini.');
        }

        if ($feedback->status !== 'draft') {
            return redirect()->route('feedback.show', $feedback->id)
                            ->with('error', 'Feedback sudah dikirim dan tidak dapat diedit.');
        }

        return view('pages.feedbacks.edit', compact('feedback', 'isAdmin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $feedback = Feedback::findOrFail($id);

        if ($feedback->created_by !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit feedback ini.');
        }

        if ($feedback->status !== 'draft') {
            return redirect()->route('feedbacks.show', $feedback->id)
                            ->with('error', 'Feedback sudah dikirim dan tidak dapat diedit.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:20',
            'category' => 'required|in:kebijakan,kondisi_kerja,fasilitas,komunikasi,lainnya',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:draft,submitted'
        ]);

        $updateData = [
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => $request->status
        ];

        // Jika mengubah dari draft ke submitted, set submitted_at
        if ($feedback->status === 'draft' && $request->status === 'submitted') {
            $updateData['submitted_at'] = now();
        }

        $feedback->update($updateData);

        $message = $request->status === 'submitted' 
            ? 'Feedback berhasil dikirim ke manajemen.' 
            : 'Feedback berhasil diperbarui.';

        return redirect()->route('feedbacks.index')
                        ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $feedback = Feedback::findOrFail($id);

        if ($feedback->created_by !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus feedback ini.');
        }

        if ($feedback->status !== 'draft') {
            return redirect()->route('feedbacks.show', $feedback->id)
                            ->with('error', 'Feedback sudah dikirim dan tidak dapat dihapus.');
        }

        $feedback->delete();

        return redirect()->route('feedbacks.index')
                        ->with('success', 'Feedback berhasil dihapus.');
    }

    //  public function userIndex()
    // {
    //     $feedbacks = Feedback::with(['assessment', 'author'])
    //                         ->where('user_id', Auth::id())
    //                         ->published()
    //                         ->latest()
    //                         ->paginate(10);
        
    //     return view('user.feedback.index', compact('feedbacks'));
    // }

    // public function assessableAssessments()
    // {
    //     $user = Auth::user();
        
    //     if ($user->role === 'user') {
    //         abort(403, 'Anda tidak memiliki akses.');
    //     }

    //     $assessments = Assessment::whereDoesntHave('feedbacks', function($query) use ($user) {
    //         $query->where('created_by', $user->id);
    //     })->with('user')->latest()->paginate(10);

    //     return view('feedback.assessable-assessments', compact('assessments'));
    // }

    public function respond(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$this->isAdmin($user)) {
            abort(403, 'Hanya admin yang dapat memberikan tanggapan.');
        }

        $request->validate([
            'management_response' => 'required|string|min:10',
            'status' => 'required|in:under_review,responded,closed'
        ]);

        $feedback = Feedback::findOrFail($id);
        
        // Pastikan feedback sudah submitted (bukan draft)
        if ($feedback->status === 'draft') {
            return redirect()->route('feedbacks.show', $feedback->id)
                            ->with('error', 'Tidak dapat menanggapi feedback yang masih draft.');
        }

        $updateData = [
            'management_response' => $request->management_response,
            'status' => $request->status,
            'responded_by' => $user->id
        ];

        // Jika status berubah ke responded/closed, set responded_at
        if (in_array($request->status, ['responded', 'closed'])) {
            $updateData['responded_at'] = now();
        }

        $feedback->update($updateData);

        return redirect()->route('feedbacks.index', $feedback->id)
                        ->with('success', 'Tanggapan berhasil disimpan.');

    }

    /**
     * Pengurus serikat: Submit draft ke manajemen
     */
    public function submit($id)
    {
        $user = Auth::user();
        $feedback = Feedback::findOrFail($id);

        if ($feedback->created_by !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk feedback ini.');
        }

        if ($feedback->status !== 'draft') {
            return redirect()->route('feedbacks.show', $feedback->id)
                            ->with('error', 'Feedback sudah dikirim sebelumnya.');
        }

        $feedback->update([
            'status' => 'submitted',
            'submitted_at' => now()
        ]);

        return redirect()->route('feedbacks.index')
                        ->with('success', 'Feedback berhasil dikirim ke manajemen.');
    }

    /**
     * Check if the given user is an admin.
     */
    private function isAdmin($user)
    {
        // Ganti dengan logic pengecekan admin yang sesuai
        // Contoh: cek email, atau field role jika ada
        return $user->email === 'admin@company.com' 
            || $user->email === 'administrator@company.com'
            || (isset($user->role) && $user->role === 'admin'); // jika ada field role
    }

    private function isUnionMember($user)
    {
        // Ganti dengan logic pengecekan pengurus serikat yang sesuai
        return !$this->isAdmin($user);
    }
}
