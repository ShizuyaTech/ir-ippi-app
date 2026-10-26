@extends('layouts.app')

@section('title', 'Detail Laporan Safety & Healthy')

@section('main')
    @php
        $user = auth()->user();
        $canPic = $user->hasSafetyReportRole('safety_pic');
        $canIr = $user->hasSafetyReportRole('ir_reviewer');
        $canShe = $user->hasSafetyReportRole('she');
        $canVerify = $canPic && in_array($safetyReport->status, ['awaiting_pic', 'returned_to_pic'], true);
        $canDecideIr = $canIr && $safetyReport->status === 'awaiting_ir';
        $canDecideShe = $canShe && $safetyReport->status === 'awaiting_she';
        $canPlan = $canShe && $safetyReport->status === 'awaiting_she_plan';
        $canClose = $canShe && $safetyReport->status === 'repair_open';
        $statusLabels = [
            'awaiting_pic' => 'Menunggu verifikasi PIC',
            'awaiting_ir' => 'Menunggu review IR',
            'returned_to_pic' => 'Perlu tindak lanjut PIC',
            'awaiting_she' => 'Menunggu verifikasi SHE',
            'awaiting_she_plan' => 'Menunggu rencana perbaikan SHE',
            'repair_open' => 'Perbaikan Open',
            'closed' => 'Selesai - Close',
        ];
        $timeline = [
            ['label' => 'Verifikasi laporan', 'due' => $safetyReport->verification_due_at, 'actual' => $safetyReport->verified_at],
            ['label' => 'Penetapan rencana', 'due' => $safetyReport->plan_sla_due_at, 'actual' => $safetyReport->plan_created_at],
            ['label' => 'Penyelesaian perbaikan', 'due' => $safetyReport->repair_sla_due_at, 'actual' => $safetyReport->repair_completed_on],
            ['label' => 'Closure', 'due' => $safetyReport->closure_sla_due_at, 'actual' => $safetyReport->closed_at],
        ];
    @endphp

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Laporan Safety</h1>
                <div class="section-header-button">
                    <a href="{{ route('safety-reports.index') }}" class="btn btn-outline-secondary">Kembali ke daftar</a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">{{ $safetyReport->report_number }}</h4>
                            <span class="badge badge-primary">{{ $statusLabels[$safetyReport->status] ?? $safetyReport->status }}</span>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-4">Tanggal laporan</dt>
                                <dd class="col-sm-8">{{ $safetyReport->created_at->format('d-m-Y H:i') }}</dd>
                                <dt class="col-sm-4">Nama / NPK</dt>
                                <dd class="col-sm-8">{{ $safetyReport->reporter_name }} / {{ $safetyReport->employee_number }}</dd>
                                <dt class="col-sm-4">Seksi / Lokasi</dt>
                                <dd class="col-sm-8">{{ $safetyReport->section }} / {{ $safetyReport->location }}</dd>
                                <dt class="col-sm-4">Sudah melapor kepada atasan?</dt>
                                <dd class="col-sm-8">{{ $safetyReport->reported_to_supervisor ? 'Sudah' : 'Belum' }}</dd>
                                <dt class="col-sm-4">KY sudah dibuat?</dt>
                                <dd class="col-sm-8">{{ $safetyReport->ky_created ? 'Sudah' : 'Belum' }}</dd>
                                <dt class="col-sm-4">Dugaan bahaya</dt>
                                <dd class="col-sm-8">{!! nl2br(e($safetyReport->hazard_description)) !!}</dd>
                                <dt class="col-sm-4">Level risiko</dt>
                                <dd class="col-sm-8">{{ $safetyReport->risk_level ? strtoupper($safetyReport->risk_level) : 'Belum ditentukan' }}</dd>
                                @if ($safetyReport->pic_note)
                                    <dt class="col-sm-4">Catatan PIC</dt>
                                    <dd class="col-sm-8">{{ $safetyReport->pic_note }}</dd>
                                @endif
                            </dl>
                            <h5>Foto kondisi awal</h5>
                            <a href="{{ route('safety-reports.image', [$safetyReport, 'original']) }}" target="_blank" rel="noopener">
                                <img src="{{ route('safety-reports.image', [$safetyReport, 'original']) }}" alt="Foto kondisi bahaya" class="img-fluid rounded" style="max-height: 420px;">
                            </a>
                            @if ($safetyReport->actual_condition_image_data || $safetyReport->actual_condition_image_path)
                                <h5 class="mt-4">Foto kondisi aktual oleh PIC</h5>
                                <a href="{{ route('safety-reports.image', [$safetyReport, 'actual']) }}" target="_blank" rel="noopener">
                                    <img src="{{ route('safety-reports.image', [$safetyReport, 'actual']) }}" alt="Foto kondisi aktual saat verifikasi PIC" class="img-fluid rounded" style="max-height: 420px;">
                                </a>
                            @endif
                            @if ($canVerify)
                                <div class="card">
                                    <div class="card-header"><h4 class="mb-0">Verifikasi PIC</h4></div>
                                    <div class="card-body">
                                        <p class="small text-muted">Centang semua kondisi yang ditemukan dari hasil pengecekan aktual. Kategori risiko tertinggi yang memiliki checklist terpilih akan digunakan sebagai level laporan.</p>
                                        <form method="POST" action="{{ route('safety-reports.verify', $safetyReport) }}" enctype="multipart/form-data">
                                            @csrf
                                            @foreach (\App\Models\SafetyReport::RISK_CHECKLISTS as $level => $items)
                                                <fieldset class="border rounded p-3 mb-3">
                                                    <legend class="h6 mb-2">{{ strtoupper($level) }} — {{ ['high' => 'Risiko Tinggi', 'medium' => 'Risiko Sedang', 'low' => 'Risiko Rendah'][$level] }}</legend>
                                                    @foreach ($items as $number => $description)
                                                        <div class="custom-control custom-checkbox mb-2">
                                                            <input class="custom-control-input" type="checkbox" id="checklist-{{ $level }}-{{ $number }}" name="checklist[{{ $level }}][]" value="{{ $number }}" @checked(in_array((string) $number, old("checklist.{$level}", array_map('strval', $safetyReport->pic_checklist[$level] ?? [])), true))>
                                                            <label class="custom-control-label" for="checklist-{{ $level }}-{{ $number }}">{{ $description }}</label>
                                                        </div>
                                                    @endforeach
                                                </fieldset>
                                            @endforeach
                                            @error('checklist')<div class="text-danger mb-3">{{ $message }}</div>@enderror
                                            <div class="form-group">
                                                <label for="actual_image">Foto kondisi aktual (wajib)</label>
                                                <input id="actual_image" type="file" name="actual_image" accept="image/jpeg,image/png,image/webp" class="form-control @error('actual_image') is-invalid @enderror" required>
                                                <small class="form-text text-muted">Foto harus menunjukkan kondisi aktual saat PIC melakukan pengecekan. Maksimal 5 MB.</small>
                                                @error('actual_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="pic-note">Catatan verifikasi</label>
                                                <textarea id="pic-note" name="note" rows="3" class="form-control">{{ old('note') }}</textarea>
                                            </div>
                                            <button class="btn btn-primary btn-block" type="submit">Simpan checklist &amp; verifikasi</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($safetyReport->ir_reviewed_at)
                        <div class="card">
                            <div class="card-header"><h4 class="mb-0">Review Industrial Relationship</h4></div>
                            <div class="card-body">
                                <p><strong>Keputusan:</strong> {{ strtoupper($safetyReport->ir_decision) }}</p>
                                <p class="mb-1"><strong>Reviewer:</strong> {{ $safetyReport->irReviewedBy?->name ?? 'Akun tidak tersedia' }}</p>
                                <p class="mb-0"><strong>Catatan:</strong> {{ $safetyReport->ir_note }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($safetyReport->she_reviewed_at)
                        <div class="card">
                            <div class="card-header"><h4 class="mb-0">Verifikasi SHE</h4></div>
                            <div class="card-body">
                                <p><strong>Keputusan:</strong> {{ strtoupper($safetyReport->she_decision) }}</p>
                                <p class="mb-1"><strong>Verifikator:</strong> {{ $safetyReport->sheReviewedBy?->name ?? 'Akun tidak tersedia' }}</p>
                                <p class="mb-0"><strong>Catatan:</strong> {{ $safetyReport->she_note }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($safetyReport->plan_description)
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4 class="mb-0">Rencana perbaikan SHE</h4>
                                <span class="badge {{ $safetyReport->repair_status === 'close' ? 'badge-success' : 'badge-warning' }}">
                                    {{ strtoupper($safetyReport->repair_status ?? 'belum dimulai') }}
                                </span>
                            </div>
                            <div class="card-body">
                                <p><strong>Rencana:</strong><br>{{ $safetyReport->plan_description }}</p>
                                <p><strong>Penanggung jawab:</strong> {{ $safetyReport->plan_owner }}</p>
                                <p><strong>Target perbaikan:</strong> {{ $safetyReport->plan_due_date?->format('d-m-Y') ?? '-' }}</p>
                                @if ($safetyReport->repair_proof_data || $safetyReport->repair_proof_path)
                                    <h5>Foto bukti perbaikan</h5>
                                    <a href="{{ route('safety-reports.image', [$safetyReport, 'proof']) }}" target="_blank" rel="noopener">
                                        <img src="{{ route('safety-reports.image', [$safetyReport, 'proof']) }}" alt="Foto bukti perbaikan" class="img-fluid rounded" style="max-height: 420px;">
                                    </a>
                                    <p class="mt-2 mb-0">
                                        Selesai diperbaiki: {{ $safetyReport->repair_completed_on?->format('d-m-Y') }}
                                        @if ($safetyReport->closed_at)
                                            · Ditutup: {{ $safetyReport->closed_at->format('d-m-Y H:i') }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    {{-- @if ($canVerify)
                        <div class="card">
                            <div class="card-header"><h4 class="mb-0">Verifikasi PIC</h4></div>
                            <div class="card-body">
                                <p class="small text-muted">Centang semua kondisi yang ditemukan dari hasil pengecekan aktual. Kategori risiko tertinggi yang memiliki checklist terpilih akan digunakan sebagai level laporan.</p>
                                <form method="POST" action="{{ route('safety-reports.verify', $safetyReport) }}" enctype="multipart/form-data">
                                    @csrf
                                    @foreach (\App\Models\SafetyReport::RISK_CHECKLISTS as $level => $items)
                                        <fieldset class="border rounded p-3 mb-3">
                                            <legend class="h6 mb-2">{{ strtoupper($level) }} — {{ ['high' => 'Risiko Tinggi', 'medium' => 'Risiko Sedang', 'low' => 'Risiko Rendah'][$level] }}</legend>
                                            @foreach ($items as $number => $description)
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input class="custom-control-input" type="checkbox" id="checklist-{{ $level }}-{{ $number }}" name="checklist[{{ $level }}][]" value="{{ $number }}" @checked(in_array((string) $number, old("checklist.{$level}", array_map('strval', $safetyReport->pic_checklist[$level] ?? [])), true))>
                                                    <label class="custom-control-label" for="checklist-{{ $level }}-{{ $number }}">{{ $description }}</label>
                                                </div>
                                            @endforeach
                                        </fieldset>
                                    @endforeach
                                    @error('checklist')<div class="text-danger mb-3">{{ $message }}</div>@enderror
                                    <div class="form-group">
                                        <label for="actual_image">Foto kondisi aktual (wajib)</label>
                                        <input id="actual_image" type="file" name="actual_image" accept="image/jpeg,image/png,image/webp" class="form-control @error('actual_image') is-invalid @enderror" required>
                                        <small class="form-text text-muted">Foto harus menunjukkan kondisi aktual saat PIC melakukan pengecekan. Maksimal 5 MB.</small>
                                        @error('actual_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="pic-note">Catatan verifikasi</label>
                                        <textarea id="pic-note" name="note" rows="3" class="form-control">{{ old('note') }}</textarea>
                                    </div>
                                    <button class="btn btn-primary btn-block" type="submit">Simpan checklist &amp; verifikasi</button>
                                </form>
                            </div>
                        </div>
                    @endif --}}

                    @if ($canDecideIr || $canDecideShe)
                        @php($isIrReview = $canDecideIr)
                        <div class="card">
                            <div class="card-header"><h4 class="mb-0">Keputusan {{ $isIrReview ? 'IR' : 'SHE' }}</h4></div>
                            <div class="card-body">
                                <form method="POST" action="{{ route($isIrReview ? 'safety-reports.ir-decision' : 'safety-reports.she-decision', $safetyReport) }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="decision">Status</label>
                                        <select id="decision" name="decision" class="form-control" required>
                                            <option value="">Pilih keputusan</option>
                                            <option value="ok" @selected(old('decision') === 'ok')>APPROVED</option>
                                            <option value="ng" @selected(old('decision') === 'ng')>REJECTED</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="review-note">Catatan (wajib)</label>
                                        <textarea id="review-note" name="note" rows="4" class="form-control" required minlength="3">{{ old('note') }}</textarea>
                                    </div>
                                    <button class="btn btn-primary btn-block" type="submit">Simpan keputusan</button>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if ($canPlan)
                        <div class="card">
                            <div class="card-header"><h4 class="mb-0">Rencana perbaikan SHE</h4></div>
                            <div class="card-body">
                                <p class="small text-muted">Batas penyelesaian: {{ $safetyReport->repair_sla_due_at?->format('d-m-Y H:i') }}</p>
                                <form method="POST" action="{{ route('safety-reports.plan', $safetyReport) }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="plan_description">Rencana tindakan</label>
                                        <textarea id="plan_description" name="plan_description" rows="4" class="form-control" required minlength="10">{{ old('plan_description') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="plan_owner">Penanggung jawab</label>
                                        <input id="plan_owner" name="plan_owner" class="form-control" value="{{ old('plan_owner') }}" required maxlength="255">
                                    </div>
                                    <div class="form-group">
                                        <label for="plan_due_date">Target penyelesaian</label>
                                        <input id="plan_due_date" name="plan_due_date" type="date" class="form-control" value="{{ old('plan_due_date', $safetyReport->repair_sla_due_at?->format('Y-m-d')) }}" required>
                                    </div>
                                    <button class="btn btn-primary btn-block" type="submit">Simpan rencana</button>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if ($canClose)
                        <div class="card">
                            <div class="card-header"><h4 class="mb-0">Tutup tindakan perbaikan</h4></div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('safety-reports.close', $safetyReport) }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="repair_completed_on">Tanggal perbaikan selesai</label>
                                        <input id="repair_completed_on" name="repair_completed_on" type="date" class="form-control" value="{{ old('repair_completed_on', now()->toDateString()) }}" max="{{ now()->toDateString() }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="proof">Foto bukti perbaikan</label>
                                        <input id="proof" type="file" name="proof" accept="image/jpeg,image/png,image/webp" class="form-control" required>
                                        <small class="form-text text-muted">JPG, PNG, atau WebP; maksimal 5 MB.</small>
                                    </div>
                                    <button class="btn btn-success btn-block" type="submit">Unggah bukti &amp; tutup</button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header"><h4 class="mb-0">Timeline SLA</h4></div>
                        <div class="card-body">
                            <ol class="pl-3 mb-0">
                                @foreach ($timeline as $item)
                                    <li class="mb-3">
                                        <strong>{{ $item['label'] }}</strong><br>
                                        @if ($item['due'])
                                            Tenggat: {{ $item['due']->format('d-m-Y H:i') }}<br>
                                        @else
                                            Tenggat dibuat setelah level risiko atau tahap sebelumnya ditetapkan.<br>
                                        @endif
                                        @if ($item['actual'])
                                            Selesai: {{ $item['actual'] instanceof \Carbon\CarbonInterface ? $item['actual']->format('d-m-Y H:i') : $item['actual']->format('d-m-Y') }}
                                            @if ($item['due'])
                                                @php($actualTime = \Carbon\Carbon::parse($item['actual']))
                                                <span class="badge {{ $actualTime->lessThanOrEqualTo($item['due']) ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $actualTime->lessThanOrEqualTo($item['due']) ? 'Tepat waktu' : 'Terlambat' }}
                                                </span>
                                            @endif
                                        @elseif ($item['due'])
                                            <span class="badge {{ now()->lessThanOrEqualTo($item['due']) ? 'badge-info' : 'badge-danger' }}">
                                                {{ now()->lessThanOrEqualTo($item['due']) ? 'Dalam SLA' : 'Terlambat' }}
                                            </span>
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h4 class="mb-0">Riwayat aktivitas</h4></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead><tr><th>Waktu</th><th>Aktivitas</th><th>Pelaku</th><th>Perubahan status</th><th>Catatan</th></tr></thead>
                            <tbody>
                                @forelse ($safetyReport->events as $event)
                                    <tr>
                                        <td>{{ $event->created_at->format('d-m-Y H:i') }}</td>
                                        <td>{{ str_replace('_', ' ', ucfirst($event->event_type)) }}</td>
                                        <td>{{ $event->actor?->name ?? 'Pelapor' }}</td>
                                        <td>{{ $event->from_status ? str_replace('_', ' ', $event->from_status).' → ' : '' }}{{ $event->to_status ? str_replace('_', ' ', $event->to_status) : '-' }}</td>
                                        <td>{{ $event->note ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">Belum ada aktivitas.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
