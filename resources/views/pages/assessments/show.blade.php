@extends('layouts.app')

@section('title', 'Detail Assessment - ' . $assessment->title)

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Assessment</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('assessments.index') }}">Assessments</a></div>
                    <div class="breadcrumb-item">{{ $assessment->title }}</div>
                </div>
            </div>

            <div class="section-body">
                <!-- Assessment Info Card -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Informasi Assessment</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('assessments.edit', $assessment->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('assessments.toggle-status', $assessment->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-{{ $assessment->is_active ? 'danger' : 'success' }}">
                                            <i class="fas fa-{{ $assessment->is_active ? 'pause' : 'play' }}"></i>
                                            {{ $assessment->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5>{{ $assessment->title }}</h5>
                                        @if ($assessment->description)
                                            <p class="text-muted">{{ $assessment->description }}</p>
                                        @endif
                                        <div class="row mt-3">
                                            <div class="col-6">
                                                <small class="text-muted">Tanggal Mulai</small>
                                                <p class="mb-1">
                                                    {{ $assessment->start_date ? $assessment->start_date->format('d F Y H:i') : '-' }}
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">Tanggal Selesai</small>
                                                <p class="mb-1">
                                                    {{ $assessment->end_date ? $assessment->end_date->format('d F Y H:i') : '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <div class="mb-3">
                                                <span
                                                    class="badge badge-{{ $assessment->is_active ? 'success' : 'danger' }}">
                                                    {{ $assessment->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                                </span>
                                            </div>
                                            <div class="btn-group-vertical w-100">
                                                <a href="{{ route('assessments.download-codes', $assessment->id) }}"
                                                    class="btn btn-primary mb-2">
                                                    <i class="fas fa-download"></i> Download User Codes
                                                </a>
                                                <a href="{{ route('assessments.responses', $assessment->id) }}"
                                                    class="btn btn-info">
                                                    <i class="fas fa-chart-bar"></i> Lihat Responses
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total User Codes</h4>
                                </div>
                                <div class="card-body">
                                    {{ $assessment->participant_count }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Codes Digunakan</h4>
                                </div>
                                <div class="card-body">
                                    {{ $responseStats['used_codes'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Codes Tersedia</h4>
                                </div>
                                <div class="card-body">
                                    {{ $responseStats['available_codes'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-info">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Responses</h4>
                                </div>
                                <div class="card-body">
                                    {{ $responseStats['total_responses'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Codes Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>User Codes</h4>
                                <div class="card-header-action">
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#generateCodesModal">
                                        <i class="fas fa-plus"></i> Generate Codes Tambahan
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Kode User</th>
                                                <th>Status</th>
                                                {{-- <th>Digunakan Oleh</th> --}}
                                                <th>Tanggal Digunakan</th>
                                                <th>Kadaluarsa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($userCodes as $code)
                                                <tr>
                                                    <td>
                                                        <code>{{ $code->code }}</code>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-{{ $code->is_used ? 'success' : 'secondary' }}">
                                                            {{ $code->is_used ? 'Digunakan' : 'Tersedia' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ $code->used_at ? $code->used_at->format('d/m/Y H:i') : '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $code->expires_at ? $code->expires_at->format('d/m/Y') : '-' }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">Tidak ada user codes</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $userCodes->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Generate Codes Modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="generateCodesModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Generate User Codes Tambahan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('assessments.generate-codes', $assessment->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="additional_count">Jumlah Codes Tambahan</label>
                            <input type="number" class="form-control" id="additional_count" name="additional_count"
                                min="1" max="100" value="10" required>
                            <small class="form-text text-muted">Maksimal 100 codes per generate</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Generate Codes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto focus on modal input
        $('#generateCodesModal').on('shown.bs.modal', function() {
            $('#additional_count').focus();
        });
    </script>
@endpush
