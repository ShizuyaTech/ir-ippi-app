@extends('layouts.app')

@section('title', 'Safety')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Safety</h1>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row">
                <div class="col-xl-4 col-md-6">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary"><i class="fas fa-clipboard-list"></i></div>
                        <div class="card-wrap"><div class="card-header"><h3 class="text-primary">Total laporan</h3></div>
                        <div class="card-body">{{ number_format($summary->total ?? 0) }}</div></div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger"><i class="fas fa-user-check"></i></div>
                        <div class="card-wrap"><div class="card-header"><h3 class="text-danger">Open</h3></div>
                        <div class="card-body">{{ number_format($summary->awaiting_pic ?? 0) }}</div></div>
                    </div>
                </div>
                {{-- <div class="col-xl-4 col-md-6">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning"><i class="fas fa-tools"></i></div>
                        <div class="card-wrap"><div class="card-header"><h4>In Progress SHE</h4></div><div class="card-body">{{ number_format($summary->active_repairs ?? 0) }}</div></div>
                    </div>
                </div> --}}
                <div class="col-xl-4 col-md-6">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success"><i class="fas fa-check-circle"></i></div>
                        <div class="card-wrap"><div class="card-header"><h3 class="text-success">Closed</h3></div><div class="card-body">{{ number_format($summary->closed ?? 0) }}</div></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h4 class="badge badge-warning text-white">In Progress</h4></div>
                <div class="card-body">
                    <div class="row text-center font-weight-bold">
                        <div class="col-md-3 mb-3"><div class="border rounded p-3 h-100"><strong class="d-block h3 mb-1">{{ number_format($summary->awaiting_pic ?? 0) }}</strong><span>PIC Safety Union</span></div></div>
                        <div class="col-md-3 mb-3"><div class="border rounded p-3 h-100"><strong class="d-block h3 mb-1">{{ number_format($summary->awaiting_ir ?? 0) }}</strong><span>PIC IR</span></div></div>
                        <div class="col-md-3 mb-3"><div class="border rounded p-3 h-100"><strong class="d-block h3 mb-1">{{ number_format($summary->awaiting_she ?? 0) }}</strong><span>PIC SHE</span></div></div>
                        <div class="col-md-3 mb-3"><div class="border rounded p-3 h-100"><strong class="d-block h3 mb-1">{{ number_format($summary->active_repairs ?? 0) }}</strong><span>Perbaikan aktif</span></div></div>
                    </div>
                    <div class="d-flex flex-wrap align-items-center" style="gap: 1rem;">
                        <strong>Risiko aktif:</strong>
                        <span class="badge badge-danger">High {{ number_format($summary->active_high ?? 0) }}</span>
                        <span class="badge badge-warning">Medium {{ number_format($summary->active_medium ?? 0) }}</span>
                        <span class="badge badge-success">Low {{ number_format($summary->active_low ?? 0) }}</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('safety-reports.index') }}" class="form-row align-items-end mb-4">
                        <div class="form-group col-md-6">
                            <label for="search">Cari laporan</label>
                            <input id="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Nomor, nama, NPK, seksi, atau lokasi">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="status">Status laporan</label>
                            <select id="status" name="status" class="form-control">
                                <option value="">Semua status</option>
                                @foreach ([
                                    'awaiting_pic' => 'Menunggu verifikasi PIC',
                                    'awaiting_ir' => 'Menunggu review IR',
                                    'returned_to_pic' => 'Perlu tindak lanjut PIC',
                                    'awaiting_she' => 'Menunggu verifikasi SHE',
                                    'awaiting_she_plan' => 'Menunggu rencana SHE',
                                    'repair_open' => 'Perbaikan Open',
                                    'closed' => 'Selesai - Close',
                                ] as $value => $label)
                                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="risk_level">Risiko</label>
                            <select id="risk_level" name="risk_level" class="form-control">
                                <option value="">Semua level</option>
                                @foreach (['high' => 'High', 'medium' => 'Medium', 'low' => 'Low'] as $value => $label)
                                    <option value="{{ $value }}" @selected(request('risk_level') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="section">Seksi</label>
                            <select id="section" name="section" class="form-control">
                                <option value="">Semua seksi</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section }}" @selected(request('section') === $section)>{{ $section }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="from">Dari tanggal</label>
                            <input id="from" type="date" name="from" class="form-control" value="{{ request('from') }}">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="to">Sampai tanggal</label>
                            <input id="to" type="date" name="to" class="form-control" value="{{ request('to') }}">
                        </div>
                        <div class="form-group col-md-2">
                            <button class="btn btn-primary btn-block" type="submit">Filter</button>
                        </div>
                        <div class="form-group col-md-2">
                            <a href="{{ route('safety-reports.index') }}"
                               class="btn btn-primary btn-block">
                                <i class="fas fa-undo"></i> Reset
                            </a>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nomor</th>
                                    <th>Tanggal</th>
                                    <th>Pelapor</th>
                                    <th>Keterangan</th>
                                    <th>Seksi / Lokasi</th>
                                    <th>Risiko</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reports as $report)
                                    <tr>
                                        <td class="text-nowrap font-weight-bold">{{ $report->report_number ?: 'Belum tersedia' }}</td>
                                        <td>{{ $report->created_at->format('d-m-Y H:i') }}</td>
                                        <td>{{ $report->reporter_name }}<br><small>{{ $report->employee_number }}</small></td>
                                        <td style="min-width: 220px; max-width: 320px;">
                                            <span title="{{ $report->hazard_description }}">
                                                {{ \Illuminate\Support\Str::limit($report->hazard_description ?: 'Tidak ada keterangan', 120) }}
                                            </span>
                                        </td>
                                        <td>{{ $report->section }}<br><small>{{ $report->location }}</small></td>
                                        <td>{{ $report->risk_level ? strtoupper($report->risk_level) : '-' }}</td>
                                        <td>{{ str_replace('_', ' ', ucfirst($report->status)) }}</td>
                                        <td class="text-nowrap">
                                            <a href="{{ route('safety-reports.show', $report) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                            @if ($report->status === 'closed')
                                                <a href="{{ route('safety-reports.print', $report) }}" class="btn btn-sm btn-outline-danger" target="_blank" rel="noopener">Cetak / Simpan PDF</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center">Belum ada laporan yang sesuai dengan filter.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $reports->links() }}
                </div>
            </div>
        </section>
    </div>
@endsection
