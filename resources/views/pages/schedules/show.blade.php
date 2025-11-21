@extends('layouts.app')

@section('title', 'Detail Jadwal Kegiatan')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Jadwal Kegiatan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('schedules.index') }}">Jadwal Kegiatan</a></div>
                    <div class="breadcrumb-item">Detail Jadwal</div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Informasi Jadwal</h4>
                            <div class="card-header-action">
                                <a href="{{ route('schedules.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                @if ($isAdmin)
                                    <a href="{{ route('schedules.edit', $schedule->id) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label><strong>Judul Kegiatan</strong></label>
                                        <div class="border-bottom pb-2">
                                            <p class="mb-0 text-dark font-weight-bold">{{ $schedule->title }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><strong>Tipe Kegiatan</strong></label>
                                        <div class="border-bottom pb-2">
                                            <span class="badge badge-info">{{ $schedule->type_name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($schedule->description)
                                <div class="form-group">
                                    <label><strong>Deskripsi</strong></label>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            {!! nl2br(e($schedule->description)) !!}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Tanggal & Waktu Mulai</strong></label>
                                        <div class="border-bottom pb-2">
                                            <p class="mb-0">
                                                <i class="fas fa-calendar text-primary mr-2"></i>
                                                {{ $schedule->formatted_start_date_time }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Tanggal & Waktu Selesai</strong></label>
                                        <div class="border-bottom pb-2">
                                            <p class="mb-0">
                                                <i class="fas fa-calendar text-primary mr-2"></i>
                                                {{ $schedule->formatted_end_date_time }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><strong>Prioritas</strong></label>
                                        <div class="border-bottom pb-2">
                                            @php
                                                $priorityClass = [
                                                    'low' => 'secondary',
                                                    'medium' => 'info',
                                                    'high' => 'danger',
                                                ][$schedule->priority];
                                            @endphp
                                            <span class="badge badge-{{ $priorityClass }} p-2">
                                                {{ $schedule->priority_name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><strong>Status</strong></label>
                                        <div class="border-bottom pb-2">
                                            @php
                                                $statusClass = [
                                                    'scheduled' => 'primary',
                                                    'in_progress' => 'warning',
                                                    'completed' => 'success',
                                                    'cancelled' => 'dark',
                                                ][$schedule->status];
                                            @endphp
                                            <span class="badge badge-{{ $statusClass }} p-2">
                                                {{ $schedule->status_name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><strong>Lokasi</strong></label>
                                        <div class="border-bottom pb-2">
                                            <p class="mb-0">
                                                @if ($schedule->location)
                                                    <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                                                    {{ $schedule->location }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($schedule->notes)
                                <div class="form-group">
                                    <label><strong>Catatan</strong></label>
                                    <div class="card border-warning">
                                        <div class="card-body bg-light">
                                            {!! nl2br(e($schedule->notes)) !!}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Dibuat Oleh</strong></label>
                                        <div class="border-bottom pb-2">
                                            <p class="mb-0">
                                                <i class="fas fa-user text-primary mr-2"></i>
                                                {{ $schedule->author->name }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Tanggal Dibuat</strong></label>
                                        <div class="border-bottom pb-2">
                                            <p class="mb-0">
                                                <i class="fas fa-clock text-primary mr-2"></i>
                                                {{ $schedule->created_at->format('d F Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
@endpush
