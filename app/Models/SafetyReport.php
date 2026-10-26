<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SafetyReport extends Model
{
    use HasFactory;

    public const RISK_LEVELS = ['high', 'medium', 'low'];

    public const STATUSES = [
        'awaiting_pic',
        'awaiting_ir',
        'returned_to_pic',
        'awaiting_she',
        'awaiting_she_plan',
        'repair_open',
        'closed',
    ];

    public const RISK_CHECKLISTS = [
        'high' => [
            1 => 'Berpotensi menyebabkan kematian atau cedera berat/permanen.',
            2 => 'Terdapat potensi kecelakaan serius dalam kondisi kerja saat ini.',
            3 => 'Pengaman mesin, interlock, atau perangkat keselamatan kritis tidak berfungsi.',
            4 => 'Terdapat bahaya listrik, kendaraan industri, jatuh dari ketinggian, atau bahaya kritis lain tanpa pengendalian memadai.',
            5 => 'Pekerja terpapar bahaya serius dan belum tersedia pengendalian yang efektif.',
        ],
        'medium' => [
            1 => 'Berpotensi menyebabkan cedera yang memerlukan penanganan medis atau kehilangan waktu kerja.',
            2 => 'Terdapat kondisi tidak aman dengan potensi cedera yang nyata, tetapi dampak dan paparannya lebih terbatas.',
            3 => 'Jalur pejalan kaki licin/rusak, penyimpanan material tidak aman, atau potensi ergonomi yang bermakna.',
            4 => 'Pengendalian sudah ada tetapi belum memadai atau belum konsisten.',
            5 => 'Kondisi membutuhkan tindakan terencana, PIC, target, dan pemantauan.',
        ],
        'low' => [
            1 => 'Dampak yang diperkirakan ringan dan kemungkinan cedera rendah.',
            2 => 'Pengendalian bahaya utama sudah tersedia dan berfungsi.',
            3 => 'Temuan berupa label keselamatan pudar, rambu perlu diperbarui, atau housekeeping ringan tanpa bahaya langsung.',
            4 => 'Tidak ditemukan paparan signifikan atau potensi kecelakaan serius saat verifikasi.',
            5 => 'Perbaikan dapat dilakukan secara rutin tanpa mengabaikan ketentuan keselamatan.',
        ],
    ];

    protected $fillable = [
        'report_number',
        'reporter_name',
        'employee_number',
        'section',
        'location',
        'hazard_description',
        'reported_to_supervisor',
        'ky_created',
        'initial_image_path',
        'initial_image_data',
        'risk_level',
        'pic_checklist',
        'actual_condition_image_path',
        'actual_condition_image_data',
        'status',
        'return_to_stage',
        'pic_note',
        'verified_by',
        'verified_at',
        'verification_due_at',
        'ir_decision',
        'ir_note',
        'ir_reviewed_by',
        'ir_reviewed_at',
        'she_decision',
        'she_note',
        'she_reviewed_by',
        'she_reviewed_at',
        'plan_sla_due_at',
        'plan_description',
        'plan_owner',
        'plan_due_date',
        'plan_created_at',
        'repair_sla_due_at',
        'repair_status',
        'repair_proof_path',
        'repair_proof_data',
        'repair_completed_on',
        'closure_sla_due_at',
        'closed_by',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'reported_to_supervisor' => 'boolean',
            'ky_created' => 'boolean',
            'pic_checklist' => 'array',
            'verified_at' => 'datetime',
            'verification_due_at' => 'datetime',
            'ir_reviewed_at' => 'datetime',
            'she_reviewed_at' => 'datetime',
            'plan_sla_due_at' => 'datetime',
            'plan_due_date' => 'date',
            'plan_created_at' => 'datetime',
            'repair_sla_due_at' => 'datetime',
            'repair_completed_on' => 'date',
            'closure_sla_due_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function events(): HasMany
    {
        return $this->hasMany(SafetyReportEvent::class)->latest();
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function irReviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ir_reviewed_by');
    }

    public function sheReviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'she_reviewed_by');
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
}
