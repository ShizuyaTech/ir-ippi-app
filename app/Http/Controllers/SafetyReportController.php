<?php

namespace App\Http\Controllers;

use App\Http\Requests\CloseSafetyReportRequest;
use App\Http\Requests\DecideSafetyReportRequest;
use App\Http\Requests\FilterSafetyReportsRequest;
use App\Http\Requests\StoreSafetyReportPlanRequest;
use App\Http\Requests\StoreSafetyReportRequest;
use App\Http\Requests\VerifySafetyReportEmployeeRequest;
use App\Http\Requests\VerifySafetyReportRequest;
use App\Models\SafetyReport;
use App\Models\SafetyReportEmployee;
use App\Services\SafetyReportNotificationService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class SafetyReportController extends Controller
{
    public function create(Request $request): View
    {
        return view('safety-reports.create', [
            'employee' => $request->session()->get('safety_report_employee'),
        ]);
    }

    public function verifyEmployee(VerifySafetyReportEmployeeRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $employee = SafetyReportEmployee::query()
            ->select(['nrp', 'name'])
            ->where('nrp', trim($validated['npk']))
            ->where('is_active', true)
            ->first();

        if (! $employee || mb_strtolower(trim($employee->name)) !== mb_strtolower(trim($validated['name']))) {
            $request->session()->forget('safety_report_employee');

            return redirect('/')
                ->with('employee_verification_error', 'NPK atau nama tidak sesuai dengan data karyawan aktif.');
        }

        $request->session()->put('safety_report_employee', [
            'employee_number' => (string) $employee->nrp,
            'name' => $employee->name,
        ]);

        return redirect()->route('safety-reports.create');
    }

    private function imageAsDataUri(string $path): string
    {
        abort_unless(Storage::disk('local')->exists($path), 404);

        $image = Storage::disk('local')->get($path);
        $mimeType = (new \finfo(FILEINFO_MIME_TYPE))->buffer($image);

        abort_unless(in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'], true), 404);

        return 'data:'.$mimeType.';base64,'.base64_encode($image);
    }

    private function reportImageAsDataUri(?string $imageData, ?string $legacyPath): ?string
    {
        if ($imageData !== null && $imageData !== '') {
            $mimeType = (new \finfo(FILEINFO_MIME_TYPE))->buffer($imageData);

            abort_unless(in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'], true), 404);

            return 'data:'.$mimeType.';base64,'.base64_encode($imageData);
        }

        return $legacyPath ? $this->imageAsDataUri($legacyPath) : null;
    }

    /**
     * @param  array<string, array<int, int>>  $checklist
     */
    private function determineRiskLevel(array $checklist): string
    {
        foreach (SafetyReport::RISK_LEVELS as $level) {
            if ($checklist[$level] !== []) {
                return $level;
            }
        }

        throw ValidationException::withMessages([
            'checklist' => 'Pilih minimal satu kondisi checklist.',
        ]);
    }

    public function store(StoreSafetyReportRequest $request)
    {
        $employee = $request->session()->get('safety_report_employee');

        if (! is_array($employee) || ! isset($employee['employee_number'], $employee['name'])) {
            return redirect()
                ->route('safety-reports.create')
                ->withErrors(['employee' => 'Verifikasi NPK dan nama karyawan terlebih dahulu.']);
        }

        $validated = $request->validated();
        $imageData = $request->file('image')->get();

        $report = DB::transaction(function () use ($validated, $imageData, $employee): SafetyReport {
            $report = SafetyReport::create([
                'reporter_name' => $employee['name'],
                'employee_number' => $employee['employee_number'],
                'section' => $validated['section'],
                'location' => $validated['location'],
                'hazard_description' => $validated['hazard_description'],
                'reported_to_supervisor' => $validated['reported_to_supervisor'] === 'sudah',
                'ky_created' => $validated['ky_created'] === 'sudah',
                'initial_image_path' => '',
                'initial_image_data' => $imageData,
                'status' => 'awaiting_pic',
            ]);

            $report->update([
                'report_number' => sprintf('SH-%s-%06d', $report->created_at->format('Y'), $report->id),
            ]);

            $this->recordEvent($report, null, 'submitted', null, 'awaiting_pic');

            return $report;
        });

        app(SafetyReportNotificationService::class)->notify(
            $report,
            ['safety_pic'],
            'Laporan Safety Baru - '.$report->report_number,
            'Laporan safety baru telah dikirim dan menunggu verifikasi PIC Safety.'
        );

        $request->session()->forget('safety_report_employee');

        return redirect()
            ->route('safety-reports.confirmation')
            ->with('submitted_report_number', $report->report_number);
    }

    public function confirmation(Request $request)
    {
        $reportNumber = $request->session()->get('submitted_report_number');

        if (! $reportNumber) {
            return redirect()->route('safety-reports.create');
        }

        $safetyReport = SafetyReport::query()
            ->where('report_number', $reportNumber)
            ->firstOrFail();

        return view('safety-reports.confirmation', compact('safetyReport', 'reportNumber'));
    }

    public function index(FilterSafetyReportsRequest $request)
    {
        $filters = $request->validated();
        $summary = SafetyReport::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN status IN ('awaiting_pic', 'returned_to_pic') THEN 1 ELSE 0 END) as awaiting_pic")
            ->selectRaw("SUM(CASE WHEN status = 'awaiting_ir' THEN 1 ELSE 0 END) as awaiting_ir")
            ->selectRaw("SUM(CASE WHEN status = 'awaiting_she' THEN 1 ELSE 0 END) as awaiting_she")
            ->selectRaw("SUM(CASE WHEN status IN ('awaiting_she_plan', 'repair_open') THEN 1 ELSE 0 END) as active_repairs")
            ->selectRaw("SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed")
            ->selectRaw("SUM(CASE WHEN risk_level = 'high' AND status != 'closed' THEN 1 ELSE 0 END) as active_high")
            ->selectRaw("SUM(CASE WHEN risk_level = 'medium' AND status != 'closed' THEN 1 ELSE 0 END) as active_medium")
            ->selectRaw("SUM(CASE WHEN risk_level = 'low' AND status != 'closed' THEN 1 ELSE 0 END) as active_low")
            ->first();
        $reports = SafetyReport::query()
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['risk_level'] ?? null, fn ($query, $riskLevel) => $query->where('risk_level', $riskLevel))
            ->when($filters['section'] ?? null, fn ($query, $section) => $query->where('section', $section))
            ->when($filters['from'] ?? null, fn ($query, $from) => $query->whereDate('created_at', '>=', $from))
            ->when($filters['to'] ?? null, fn ($query, $to) => $query->whereDate('created_at', '<=', $to))
            ->when($filters['search'] ?? null, function ($query, $value): void {
                $search = '%'.trim($value).'%';
                $query->where(function ($query) use ($search): void {
                    $query->where('report_number', 'like', $search)
                        ->orWhere('reporter_name', 'like', $search)
                        ->orWhere('employee_number', 'like', $search)
                        ->orWhere('section', 'like', $search)
                        ->orWhere('location', 'like', $search);
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();
        $sections = SafetyReport::query()->select('section')->distinct()->orderBy('section')->pluck('section');

        return view('safety-reports.index', compact('reports', 'sections', 'summary'));
    }

    public function show(SafetyReport $safetyReport)
    {
        $safetyReport->load([
            'events.actor',
            'verifiedBy',
            'irReviewedBy',
            'sheReviewedBy',
            'closedBy',
        ]);

        return view('safety-reports.show', compact('safetyReport'));
    }

    public function print(SafetyReport $safetyReport)
    {
        abort_unless($safetyReport->status === 'closed', 404);

        $safetyReport->load(['events.actor', 'verifiedBy', 'irReviewedBy', 'sheReviewedBy', 'closedBy']);
        $images = collect([
            'initial' => [$safetyReport->initial_image_data, $safetyReport->initial_image_path],
            'actual' => [$safetyReport->actual_condition_image_data, $safetyReport->actual_condition_image_path],
            'repair' => [$safetyReport->repair_proof_data, $safetyReport->repair_proof_path],
        ])->map(fn (array $image): ?string => $this->reportImageAsDataUri($image[0], $image[1]));

        return view('safety-reports.print', compact('safetyReport', 'images'));
    }

    public function image(SafetyReport $safetyReport, string $type)
    {
        [$imageData, $path] = match ($type) {
            'original' => [$safetyReport->initial_image_data, $safetyReport->initial_image_path],
            'actual' => [$safetyReport->actual_condition_image_data, $safetyReport->actual_condition_image_path],
            'proof' => [$safetyReport->repair_proof_data, $safetyReport->repair_proof_path],
            default => abort(404),
        };

        if ($imageData !== null && $imageData !== '') {
            $mimeType = (new \finfo(FILEINFO_MIME_TYPE))->buffer($imageData);

            abort_unless(in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp'], true), 404);

            return response($imageData)->header('Content-Type', $mimeType)->header('Cache-Control', 'private, no-store');
        }

        abort_if(! $path || ! Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->response($path, null, ['Cache-Control' => 'private, no-store']);
    }

    public function verify(VerifySafetyReportRequest $request, SafetyReport $safetyReport)
    {
        $validated = $request->validated();
        $checklist = collect(SafetyReport::RISK_LEVELS)
            ->mapWithKeys(fn (string $level): array => [
                $level => array_values(array_unique(array_map('intval', $validated['checklist'][$level] ?? []))),
            ])
            ->all();
        $riskLevel = $this->determineRiskLevel($checklist);
        $actualImageData = $request->file('actual_image')->get();

        if ($actualImageData === '') {
            throw new RuntimeException('Gagal menyimpan foto kondisi aktual.');
        }

        DB::transaction(function () use ($request, $safetyReport, $validated, $checklist, $riskLevel, $actualImageData): void {
            $report = SafetyReport::query()->lockForUpdate()->findOrFail($safetyReport->id);
            abort_unless(in_array($report->status, ['awaiting_pic', 'returned_to_pic'], true), 409, 'Laporan tidak sedang menunggu verifikasi PIC.');

            $fromStatus = $report->status;
            $nextStatus = $report->return_to_stage === 'she' ? 'awaiting_she' : 'awaiting_ir';
            $verifiedAt = $report->verified_at ?? now();
            $days = $this->businessDaysForRisk($riskLevel);

            $report->update([
                'risk_level' => $riskLevel,
                'pic_checklist' => $checklist,
                'actual_condition_image_path' => null,
                'actual_condition_image_data' => $actualImageData,
                'status' => $nextStatus,
                'return_to_stage' => null,
                'pic_note' => $validated['note'] ?? null,
                'verified_by' => $request->user()->id,
                'verified_at' => $verifiedAt,
                'verification_due_at' => $report->verification_due_at ?? $report->created_at->copy()->addWeekdays($days),
                'plan_sla_due_at' => $verifiedAt->copy()->addWeekdays($days),
            ]);

            $this->recordEvent($report, $request->user()->id, 'pic_verified', $fromStatus, $nextStatus, $validated['note'] ?? null, [
                'risk_level' => $riskLevel,
                'checklist' => $checklist,
                'verification_due_at' => $report->verification_due_at?->toIso8601String(),
                'plan_sla_due_at' => $report->plan_sla_due_at?->toIso8601String(),
            ]);
        });

        $report = $safetyReport->fresh();

            $recipientRole = $report->status === 'awaiting_she'
                ? ['she']
                : ['ir_reviewer'];

            app(SafetyReportNotificationService::class)->notify(
                $report,
                $recipientRole,
                'Laporan Safety Menunggu Review - '.$report->report_number,
                'PIC Safety telah menyelesaikan verifikasi. Laporan menunggu tindak lanjut tim terkait.'
        );
        return redirect()->route('safety-reports.show', $safetyReport)->with('success', 'Verifikasi PIC berhasil disimpan.');
    }

    public function decideAsIr(DecideSafetyReportRequest $request, SafetyReport $safetyReport)
    {
        return $this->recordDecision($request, $safetyReport, 'ir');
    }

    public function decideAsShe(DecideSafetyReportRequest $request, SafetyReport $safetyReport)
    {
        return $this->recordDecision($request, $safetyReport, 'she');
    }

    public function storePlan(StoreSafetyReportPlanRequest $request, SafetyReport $safetyReport)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($request, $safetyReport, $validated): void {
            $report = SafetyReport::query()->lockForUpdate()->findOrFail($safetyReport->id);
            abort_unless($report->status === 'awaiting_she_plan', 409, 'Rencana hanya dapat dibuat setelah laporan disetujui SHE.');

            if (Carbon::parse($validated['plan_due_date'])->greaterThan($report->repair_sla_due_at)) {
                throw ValidationException::withMessages([
                    'plan_due_date' => 'Tanggal target rencana tidak boleh melewati tenggat penyelesaian berdasarkan level risiko.',
                ]);
            }

            $report->update([
                'plan_description' => $validated['plan_description'],
                'plan_owner' => $validated['plan_owner'],
                'plan_due_date' => $validated['plan_due_date'],
                'plan_created_at' => now(),
                'repair_status' => 'open',
                'status' => 'repair_open',
            ]);

            $this->recordEvent($report, $request->user()->id, 'repair_plan_created', 'awaiting_she_plan', 'repair_open', null, [
                'plan_owner' => $report->plan_owner,
                'plan_due_date' => $report->plan_due_date?->toDateString(),
                'repair_sla_due_at' => $report->repair_sla_due_at?->toIso8601String(),
            ]);
        });

        return redirect()->route('safety-reports.show', $safetyReport)->with('success', 'Rencana perbaikan SHE berhasil disimpan.');
    }

    public function close(CloseSafetyReportRequest $request, SafetyReport $safetyReport)
    {
        $validated = $request->validated();
        $completedOn = Carbon::parse($validated['repair_completed_on']);

        if (
            ($safetyReport->she_reviewed_at && $completedOn->lt($safetyReport->she_reviewed_at->copy()->startOfDay()))
            || ($safetyReport->plan_created_at && $completedOn->lt($safetyReport->plan_created_at->copy()->startOfDay()))
        ) {
            throw ValidationException::withMessages([
                'repair_completed_on' => 'Tanggal perbaikan tidak boleh sebelum rencana perbaikan dibuat.',
            ]);
        }

        $proofData = $request->file('proof')->get();

        if ($proofData === '') {
            throw new RuntimeException('Gagal menyimpan foto bukti perbaikan.');
        }

        DB::transaction(function () use ($request, $safetyReport, $validated, $completedOn, $proofData): void {
            $report = SafetyReport::query()->lockForUpdate()->findOrFail($safetyReport->id);
            abort_unless($report->status === 'repair_open', 409, 'Hanya tindakan perbaikan berstatus Open yang dapat ditutup.');

            $closureDays = match ($report->risk_level) {
                'high' => 0,
                'medium' => 2,
                'low' => 3,
            };
            $closureDeadline = $completedOn->copy()->addWeekdays($closureDays)->endOfDay();

            $report->update([
                'repair_status' => 'close',
                'repair_proof_path' => null,
                'repair_proof_data' => $proofData,
                'repair_completed_on' => $validated['repair_completed_on'],
                'closure_sla_due_at' => $closureDeadline,
                'closed_by' => $request->user()->id,
                'closed_at' => now(),
                'status' => 'closed',
            ]);

            $this->recordEvent($report, $request->user()->id, 'repair_closed', 'repair_open', 'closed', null, [
                'repair_completed_on' => $report->repair_completed_on?->toDateString(),
                'closure_sla_due_at' => $report->closure_sla_due_at?->toIso8601String(),
            ]);
        });

        app(SafetyReportNotificationService::class)->notify(
            $safetyReport->fresh(),
            ['safety_pic'],
            'Laporan Safety Ditutup - '.$safetyReport->report_number,
            'Laporan telah ditutup setelah bukti perbaikan disimpan.');

        return redirect()->route('safety-reports.show', $safetyReport)->with('success', 'Bukti perbaikan tersimpan dan laporan telah ditutup.');
    }

    private function recordDecision(DecideSafetyReportRequest $request, SafetyReport $safetyReport, string $stage)
    {
        $validated = $request->validated();
        $expectedStatus = $stage === 'ir' ? 'awaiting_ir' : 'awaiting_she';
        $approvedStatus = $stage === 'ir' ? 'awaiting_she' : 'awaiting_she_plan';
        $stageLabel = $stage === 'ir' ? 'IR' : 'SHE';

        DB::transaction(function () use ($request, $safetyReport, $validated, $stage, $expectedStatus, $approvedStatus, $stageLabel): void {
            $report = SafetyReport::query()->lockForUpdate()->findOrFail($safetyReport->id);
            abort_unless($report->status === $expectedStatus, 409, "Laporan tidak sedang menunggu keputusan {$stageLabel}.");

            $fromStatus = $report->status;
            $approved = $validated['decision'] === 'ok';
            $toStatus = $approved ? $approvedStatus : 'returned_to_pic';
            $update = [
                "{$stage}_decision" => $validated['decision'],
                "{$stage}_note" => $validated['note'],
                "{$stage}_reviewed_by" => $request->user()->id,
                "{$stage}_reviewed_at" => now(),
                'status' => $toStatus,
                'return_to_stage' => $approved ? null : $stage,
            ];

            if ($stage === 'she' && $approved) {
                $update['repair_sla_due_at'] = now()->addDays($this->calendarDaysForRisk($report->risk_level));
            }

            $report->update($update);

            $this->recordEvent($report, $request->user()->id, "{$stage}_{$validated['decision']}", $fromStatus, $toStatus, $validated['note'], [
                'decision' => $validated['decision'],
            ]);
        });

        $report = $safetyReport->fresh();

        if ($validated['decision'] === 'ok' && $stage === 'ir') {
            app(SafetyReportNotificationService::class)->notify(
                $report,
                ['she'],
                'Laporan Safety Menunggu Persetujuan SHE - '.$report->report_number,
                'IR telah menyetujui laporan. Silakan lakukan review oleh Tim SHE.'
            );
        } elseif ($validated['decision'] !== 'ok') {
            app(SafetyReportNotificationService::class)->notify(
                $report,
                ['safety_pic'],
                'Laporan Safety Dikembalikan ke PIC - '.$report->report_number,
                "Laporan dikembalikan oleh tahap {$stageLabel}. Silakan periksa catatan pada aplikasi."
            );
        }
        return redirect()->route('safety-reports.show', $safetyReport)->with('success', "Keputusan {$stageLabel} berhasil disimpan.");
    }

    private function recordEvent(
        SafetyReport $report,
        ?int $actorId,
        string $eventType,
        ?string $fromStatus,
        ?string $toStatus,
        ?string $note = null,
        array $metadata = [],
    ): void {
        $report->events()->create([
            'actor_id' => $actorId,
            'event_type' => $eventType,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'note' => $note,
            'metadata' => $metadata === [] ? null : $metadata,
        ]);
    }

    private function businessDaysForRisk(string $riskLevel): int
    {
        return match ($riskLevel) {
            'high' => 1,
            'medium' => 3,
            'low' => 5,
        };
    }

    private function calendarDaysForRisk(?string $riskLevel): int
    {
        return match ($riskLevel) {
            'high' => 7,
            'medium' => 14,
            'low' => 30,
            default => throw new \LogicException('Risiko harus ditentukan sebelum laporan disetujui SHE.'),
        };
    }
}
