<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $this->isAdmin($user);

        // Filter berdasarkan status jika ada
        $status = request('status');
        $type = request('type');
        
        $schedules = Schedule::with('author')
            ->when($status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($type, function($query, $type) {
                return $query->where('type', $type);
            })
            ->latest()
            ->paginate(10);

        return view('pages.schedules.index', compact('schedules', 'isAdmin'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        if (!$this->isAdmin($user)) {
            abort(403, 'Hanya admin yang dapat membuat jadwal kegiatan.');
        }

        return view('pages.schedules.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$this->isAdmin($user)) {
            abort(403, 'Hanya admin yang dapat membuat jadwal kegiatan.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:meeting,training,event,deadline,other',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        Schedule::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'type' => $request->type,
            'priority' => $request->priority,
            'status' => $request->status,
            'notes' => $request->notes,
            'created_by' => $user->id
        ]);

        return redirect()->route('schedules.index')
                        ->with('success', 'Jadwal kegiatan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schedule = Schedule::with('author')->findOrFail($id);
        $isAdmin = $this->isAdmin(Auth::user());

        return view('pages.schedules.show', compact('schedule', 'isAdmin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        
        if (!$this->isAdmin($user)) {
            abort(403, 'Hanya admin yang dapat mengedit jadwal kegiatan.');
        }

        $schedule = Schedule::findOrFail($id);

        return view('pages.schedules.edit', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        
        if (!$this->isAdmin($user)) {
            abort(403, 'Hanya admin yang dapat mengedit jadwal kegiatan.');
        }

        $schedule = Schedule::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:meeting,training,event,deadline,other',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        $schedule->update([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'type' => $request->type,
            'priority' => $request->priority,
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        return redirect()->route('schedules.index')
                        ->with('success', 'Jadwal kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        
        if (!$this->isAdmin($user)) {
            abort(403, 'Hanya admin yang dapat menghapus jadwal kegiatan.');
        }

        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('schedules.index')
                        ->with('success', 'Jadwal kegiatan berhasil dihapus.');
    }

    /**
 * Calendar view
 */
public function calendar()
{
    $schedules = Schedule::where('start_date', '>=', now()->subMonths(1))
                        ->where('end_date', '<=', now()->addMonths(3))
                        ->get();

    $calendarEvents = $schedules->map(function($schedule) {
        $color = $this->getEventColor($schedule->type);
        
        return [
            'id' => $schedule->id,
            'title' => $schedule->title,
            'start' => $schedule->start_date->format('Y-m-d') . ($schedule->start_time ? 'T' . $schedule->start_time->format('H:i:s') : ''),
            'end' => $schedule->end_date->format('Y-m-d') . ($schedule->end_time ? 'T' . $schedule->end_time->format('H:i:s') : ''),
            'color' => $color,
            'textColor' => '#ffffff',
            'className' => 'fc-event-' . $schedule->type,
            'url' => route('schedules.show', $schedule->id)
        ];
    });

    $isAdmin = $this->isAdmin(Auth::user());

    return view('pages.schedules.calendar', compact('calendarEvents', 'isAdmin'));
}

    /**
     * Get event color based on type
     */
    private function getEventColor($type)
    {
        $colors = [
            'meeting' => '#3498db',      // Blue
            'training' => '#2ecc71',     // Green
            'event' => '#9b59b6',        // Purple
            'deadline' => '#e74c3c',     // Red
            'other' => '#f39c12'         // Orange
        ];

        return $colors[$type] ?? '#95a5a6'; // Default gray
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
