<?php

namespace App\Http\Controllers;

use App\Models\LksBipartit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LksBipartitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = LksBipartit::with(['assignee', 'creator']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority') && $request->priority != '') {
            $query->where('priority', $request->priority);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tasks = $query->orderBy('priority', 'desc')
                      ->orderBy('due_date')
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);

        $isAdmin = $this->isAdmin(Auth::user()); // Using the helper method defined below

        return view('pages.lks-bipartit.index', compact('tasks', 'isAdmin'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all(); // Untuk assign task
        return view('pages.lks-bipartit.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|integer|between:1,4',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        LksBipartit::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'assigned_to' => $request->assigned_to,
            'created_by' => Auth::id(),
            'status' => 'review'
        ]);

        return redirect()->route('lks-bipartit.index')
                         ->with('success', 'Task LKS Bipartit berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LksBipartit $lksBipartit)
    {
        $lksBipartit->load(['assignee', 'creator']);
        $isAdmin = $this->isAdmin(Auth::user()); // Use controller helper to check admin
        
        return view('pages.lks-bipartit.show', compact('lksBipartit', 'isAdmin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LksBipartit $lksBipartit)
    {
        $users = User::all();
        return view('pages.lks-bipartit.edit', compact('lksBipartit', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,LksBipartit $lksBipartit)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:review,on_progress,done',
            'priority' => 'required|integer|between:1,4',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string'
        ]);

        $lksBipartit->update($request->all());

        return redirect()->route('lks-bipartit.index')
                         ->with('success', 'Task LKS Bipartit berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LksBipartit $lksBipartit)
    {
        $lksBipartit->delete();

        return redirect()->route('lks-bipartit.index')
                         ->with('success', 'Task LKS Bipartit berhasil dihapus.');
    }

    // public function updateStatus(Request $request, $id)
    // {
    //     // Gunakan $id sebagai parameter, bukan model binding
    //     $task = LksBipartit::findOrFail($id);
        
    //     $request->validate([
    //         'status' => 'required|in:review,on_progress,done'
    //     ]);

    //     $task->update(['status' => $request->status]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Status berhasil diperbarui',
    //         'new_status' => $request->status,
    //         'status_name' => $task->status_name
    //     ]);
    // }

    public function updateStatus(Request $request, LksBipartit $lksBipartit)
    {
        $request->validate([
            'status' => 'required|in:review,on_progress,done'
        ]);

        $lksBipartit->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui'
        ]);

        // return redirect()->route('lks-bipartit.index')
        //                  ->with('success', 'Task LKS Bipartit berhasil diperbarui.');
    }

    public function quickStatus(Request $request, $id)
{
    $task = LksBipartit::findOrFail($id);
    
    $request->validate([
        'status' => 'required|in:review,on_progress,done'
    ]);

    $oldStatus = $task->status;
    $task->update(['status' => $request->status]);

    return redirect()->route('lks-bipartit.show', $task->id)
                    ->with('success', "Status berhasil diubah dari {$task->getStatusName($oldStatus)} menjadi {$task->status_name}");
}

    /**
     * Helper method untuk cek jika user adalah admin
     */
    private function isAdmin($user)
    {
        return $user->email === 'admin@company.com' 
            || $user->email === 'administrator@company.com'
            || (isset($user->role) && $user->role === 'admin');
    }
}
