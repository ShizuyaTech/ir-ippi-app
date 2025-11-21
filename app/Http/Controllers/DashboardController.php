<?php

namespace App\Http\Controllers;

use App\Models\DashboardScore;
use App\Models\LksBipartit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Assessment;
use App\Models\AssessmentUser;
use App\Models\Feedback;
use App\Models\Schedule;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ✅ OPTIMIZED: Cache dashboard data for 5 minutes
        // Check if user is admin first
        $isAdmin = $this->checkAdminStatus();
        
        // Cache key includes user ID to differentiate between admin/non-admin
        $cacheKey = 'dashboard_data_' . ($isAdmin ? 'admin' : 'user');
        $cacheDuration = 5; // minutes
        
        $dashboardData = \Illuminate\Support\Facades\Cache::remember(
            $cacheKey,
            now()->addMinutes($cacheDuration),
            function () {
                // Get latest scores
                $scores = DashboardScore::latest()->first();
                
                if (!$scores) {
                    $scores = (object) [
                        'ir_partnership' => 0,
                        'conductive_working_climate' => 0,
                        'ess' => 0,
                        'airsi' => 0,
                    ];
                }

                // Get all chart data with optimized queries
                return [
                    'scores' => $scores,
                    'irAssessmentData' => $this->getIrAssessmentData(),
                    'feedbackData' => $this->getFeedbackData(),
                    'scheduleData' => $this->getScheduleData(),
                    'lksData' => $this->getLksData(),
                    'lksStats' => $this->getLksStats(),
                ];
            }
        );
        
        // ✅ OPTIMIZED: Only fetch admin scores if needed (lazy load)
        $allScores = $isAdmin ? DashboardScore::with('updater')->latest()->limit(50)->get() : collect();

        // ✅ OPTIMIZED: Cache admin count for 1 hour (rarely changes)
        $totalAdmin = \Illuminate\Support\Facades\Cache::remember(
            'total_admin_count',
            now()->addHour(),
            function () {
                $adminEmails = array_map('trim', explode(',', env('ADMIN_EMAILS', 'admin@company.com')));
                return User::whereIn('email', $adminEmails)->count();
            }
        );

        return view('pages.dashboard', array_merge($dashboardData, compact(
            'allScores',
            'isAdmin',
            'totalAdmin',
        )));
    }

    /**
     * Get LKS statistics - optimized to single query
     */
    private function getLksStats()
    {
        return LksBipartit::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = "on_progress" THEN 1 ELSE 0 END) as in_progress,
            SUM(CASE WHEN status = "done" THEN 1 ELSE 0 END) as completed
        ')
        ->first()
        ->toArray();
    }

    /**
     * Get IR Assessment data dengan format yang sesuai untuk chart
     */
    private function getIrAssessmentData()
    {
        $data = Assessment::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Format data untuk memastikan semua bulan ada (1-12)
        return $this->formatMonthlyData($data);
    }

    /**
     * Get Feedback data dengan format yang sesuai untuk chart
     */
    private function getFeedbackData()
    {
        $data = Feedback::selectRaw('MONTH(created_at) as month, 
            SUM(CASE WHEN status = "submitted" THEN 1 ELSE 0 END) as submitted,
            SUM(CASE WHEN status = "under_review" THEN 1 ELSE 0 END) as under_review,
            SUM(CASE WHEN status = "responded" THEN 1 ELSE 0 END) as responded,
            SUM(CASE WHEN status = "closed" THEN 1 ELSE 0 END) as closed')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $formattedData = [];
        
        // Format data untuk memastikan semua bulan ada
        for ($month = 1; $month <= 12; $month++) {
            $monthData = $data->firstWhere('month', $month);
            
            $formattedData[$month] = [
                'closed' => $monthData ? (int)$monthData->closed : 0,
                'under_review' => $monthData ? (int)$monthData->under_review : 0,
                'responded' => $monthData ? (int)$monthData->responded : 0,
                'submitted' => $monthData ? (int)$monthData->submitted : 0,
            ];
        }

        return $formattedData;
    }

    /**
     * Get Schedule data dengan format yang sesuai untuk chart
     */
    private function getScheduleData()
    {
        $data = Schedule::selectRaw('MONTH(start_date) as month, COUNT(*) as total')
            ->whereYear('start_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Format data untuk memastikan semua bulan ada (1-12)
        return $this->formatMonthlyData($data);
    }

    /**
     * Get LKS Bipartit data dengan format yang sesuai untuk chart
     */
    private function getLksData()
    {
        $data = LksBipartit::selectRaw('MONTH(created_at) as month, 
            SUM(CASE WHEN status = "review" THEN 1 ELSE 0 END) as review,
            SUM(CASE WHEN status = "on_progress" THEN 1 ELSE 0 END) as on_progress,
            SUM(CASE WHEN status = "done" THEN 1 ELSE 0 END) as done')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $formattedData = [];
        
        // Format data untuk memastikan semua bulan ada
        for ($month = 1; $month <= 12; $month++) {
            $monthData = $data->firstWhere('month', $month);
            
            $formattedData[$month] = [
                'review' => $monthData ? (int)$monthData->review : 0,
                'on_progress' => $monthData ? (int)$monthData->on_progress : 0,
                'done' => $monthData ? (int)$monthData->done : 0,
            ];
        }

        return $formattedData;
    }

    /**
     * Format monthly data untuk memastikan semua bulan ada
     */
    private function formatMonthlyData($data)
    {
        $formattedData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $formattedData[$month] = isset($data[$month]) ? (int)$data[$month] : 0;
        }

        return $formattedData;
    }

    /**
     * Check if user is admin
     */
    private function checkAdminStatus()
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        // Method 1: Check using isAdmin method if exists
        if (method_exists($user, 'isAdmin')) {
            // return $user->isAdmin();
        }

        // Method 2: Check by email in ADMIN_EMAILS
        $adminEmails = array_map('trim', explode(',', env('ADMIN_EMAILS', 'admin@company.com')));
        return in_array(strtolower($user->email), array_map('strtolower', $adminEmails));
    }

    /**
     * Debug chart data
     */
    private function debugChartData($irAssessmentData, $feedbackData, $scheduleData, $lksData)
    {
        Log::info('=== CHART DATA DEBUG ===');
        Log::info('IR Assessment Data:', $irAssessmentData);
        Log::info('Feedback Data:', $feedbackData);
        Log::info('Schedule Data:', $scheduleData);
        Log::info('LKS Data:', $lksData);
        Log::info('=== END CHART DATA DEBUG ===');
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        if (!$user || !$this->checkAdminStatus()) {
            return redirect()->route('dashboard')
                             ->with('error', 'Anda tidak memiliki akses untuk membuat scores.');
        }

        return view('pages.dashboard-scores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$this->checkAdminStatus()) {
            return redirect()->route('dashboard')
                             ->with('error', 'Anda tidak memiliki akses untuk membuat scores.');
        }

        $request->validate([
            'ir_partnership' => 'required|numeric|min:0|max:100',
            'conductive_working_climate' => 'required|numeric|min:0|max:100',
            'ess' => 'required|numeric|min:0|max:100',
            'airsi' => 'required|numeric|min:0|max:100',
        ]);

        DashboardScore::create([
            'ir_partnership' => $request->ir_partnership,
            'conductive_working_climate' => $request->conductive_working_climate,
            'ess' => $request->ess,
            'airsi' => $request->airsi,
            'updated_by' => Auth::id(),
        ]);

        // Clear cache agar data terbaru langsung muncul
        \Illuminate\Support\Facades\Cache::forget('dashboard_data_admin');
        \Illuminate\Support\Facades\Cache::forget('dashboard_data_user');

        return redirect()->route('dashboard')
                         ->with('success', 'Scores berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        if (!$user || !$this->checkAdminStatus()) {
            return redirect()->route('dashboard')
                             ->with('error', 'Anda tidak memiliki akses untuk mengedit scores.');
        }

        $dashboardScore = DashboardScore::findOrFail($id);
        return view('pages.dashboard-scores.edit', compact('dashboardScore'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DashboardScore $dashboardScore)
    {
        $user = Auth::user();
        if (!$user || !$this->checkAdminStatus()) {
            return redirect()->route('dashboard')
                             ->with('error', 'Anda tidak memiliki akses untuk mengupdate scores.');
        }

        $request->validate([
            'ir_partnership' => 'required|numeric|min:0|max:100',
            'conductive_working_climate' => 'required|numeric|min:0|max:100',
            'ess' => 'required|numeric|min:0|max:100',
            'airsi' => 'required|numeric|min:0|max:100',
        ]);

        $dashboardScore->update([
            'ir_partnership' => $request->ir_partnership,
            'conductive_working_climate' => $request->conductive_working_climate,
            'ess' => $request->ess,
            'airsi' => $request->airsi,
            'updated_by' => Auth::id(),
        ]);

        // Clear cache agar data terbaru langsung muncul
        \Illuminate\Support\Facades\Cache::forget('dashboard_data_admin');
        \Illuminate\Support\Facades\Cache::forget('dashboard_data_user');

        return redirect()->route('dashboard')
                         ->with('success', 'Scores berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DashboardScore $dashboardScore)
    {
        $user = Auth::user();
        if (!$user || !$this->checkAdminStatus()) {
            return redirect()->route('dashboard')
                             ->with('error', 'Anda tidak memiliki akses untuk menghapus scores.');
        }

        $dashboardScore->delete();

        // Clear cache agar data terbaru langsung muncul
        \Illuminate\Support\Facades\Cache::forget('dashboard_data_admin');
        \Illuminate\Support\Facades\Cache::forget('dashboard_data_user');

        return redirect()->route('dashboard')
                         ->with('success', 'Scores berhasil dihapus.');
    }
    
    public function updateScores(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$this->checkAdminStatus()) {
            return redirect()->route('dashboard')
                             ->with('error', 'Anda tidak memiliki akses untuk mengupdate scores.');
        }

        $request->validate([
            'ir_partnership' => 'required|numeric|min:0|max:100',
            'conductive_working_climate' => 'required|numeric|min:0|max:100',
            'ess' => 'required|numeric|min:0|max:100',
            'airsi' => 'required|numeric|min:0|max:100',
        ]);

        DashboardScore::create([
            'ir_partnership' => $request->ir_partnership,
            'conductive_working_climate' => $request->conductive_working_climate,
            'ess' => $request->ess,
            'airsi' => $request->airsi,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('dashboard')
                         ->with('success', 'Scores berhasil diperbarui.');
    }
}