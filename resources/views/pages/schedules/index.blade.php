@extends('layouts.app')

@section('title', 'Jadwal Kegiatan')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Schedule Activity</h1>
                @if ($isAdmin)
                    <div class="section-header-button">
                        <a href="{{ route_encrypted('schedules.create') }}" class="btn btn-primary">Tambah Jadwal</a>
                    </div>
                @endif
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route_encrypted('schedules.index') }}">Jadwal Kegiatan</a></div>
                    <div class="breadcrumb-item">All Schedule</div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                        {{ session('success') }}
                                    </div>
                                </div>
                            @endif

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <a href="{{ route_encrypted('schedules.calendar') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-calendar-alt"></i> Tampilan Kalender
                                    </a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <form method="GET" action="{{ route_encrypted('schedules.index') }}">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Cari judul kegiatan..."
                                                name="search" value="{{ request('search') }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="clearfix mb-3"></div>

                            <!-- Filter Section -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <form method="GET" action="{{ route_encrypted('schedules.index') }}">
                                        <select name="type" class="form-control selectric" onchange="this.form.submit()">
                                            <option value="">Semua Tipe</option>
                                            @foreach (App\Models\Schedule::TYPES as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ request('type') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                                <div class="col-md-3">
                                    <form method="GET" action="{{ route_encrypted('schedules.index') }}">
                                        <select name="status" class="form-control selectric" onchange="this.form.submit()">
                                            <option value="">Semua Status</option>
                                            @foreach (App\Models\Schedule::STATUSES as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ request('status') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                                <div class="col-md-3">
                                    <form method="GET" action="{{ route_encrypted('schedules.index') }}">
                                        <select name="priority" class="form-control selectric"
                                            onchange="this.form.submit()">
                                            <option value="">Semua Prioritas</option>
                                            @foreach (App\Models\Schedule::PRIORITIES as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ request('priority') == $key ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route_encrypted('schedules.index') }}" class="btn btn-secondary btn-block">Reset
                                        Filter</a>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Judul Kegiatan</th>
                                            <th>Tipe</th>
                                            <th>Tanggal & Waktu</th>
                                            <th>Lokasi</th>
                                            <th>Prioritas</th>
                                            <th>Status</th>
                                            <th>Dibuat Oleh</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($schedules as $schedule)
                                            <tr>
                                                <td>{{ $loop->iteration + ($schedules->currentPage() - 1) * $schedules->perPage() }}
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <strong class="text-dark">{{ $schedule->title }}</strong>
                                                        @if ($schedule->description)
                                                            <small class="text-muted mt-1">
                                                                {{ Str::limit($schedule->description, 50) }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">{{ $schedule->type_name }}</span>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <div><strong>Mulai:</strong>
                                                            {{ $schedule->formatted_start_date_time }}
                                                        </div>
                                                        <div><strong>Selesai:</strong>
                                                            {{ $schedule->formatted_end_date_time }}
                                                        </div>
                                                    </small>
                                                </td>
                                                <td>
                                                    @if ($schedule->location)
                                                        <span class="badge badge-light">
                                                            <i class="fas fa-map-marker-alt"></i> {{ $schedule->location }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $priorityClass = [
                                                            'low' => 'secondary',
                                                            'medium' => 'info',
                                                            'high' => 'danger',
                                                        ][$schedule->priority];
                                                    @endphp
                                                    <span class="badge badge-{{ $priorityClass }}">
                                                        {{ $schedule->priority_name }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusClass = [
                                                            'scheduled' => 'primary',
                                                            'in_progress' => 'warning',
                                                            'completed' => 'success',
                                                            'cancelled' => 'dark',
                                                        ][$schedule->status];
                                                    @endphp
                                                    <span class="badge badge-{{ $statusClass }}">
                                                        {{ $schedule->status_name }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="ml-2">
                                                            {{ $schedule->author->name }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <!-- Tombol View - selalu tampil -->
                                                        <a href="{{ route_encrypted('schedules.show', $schedule->id) }}"
                                                            class="btn btn-sm btn-info mr-1" title="Lihat Detail"
                                                            data-toggle="tooltip">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        <!-- Tombol Edit & Delete - hanya untuk admin -->
                                                        @if ($isAdmin)
                                                            <a href="{{ route_encrypted('schedules.edit', $schedule->id) }}"
                                                                class="btn btn-sm btn-warning mr-1" title="Edit Jadwal"
                                                                data-toggle="tooltip">
                                                                <i class="fas fa-edit"></i>
                                                            </a>

                                                            <form action="{{ route_encrypted('schedules.destroy', $schedule->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-danger confirm-delete"
                                                                    title="Hapus Jadwal" data-toggle="tooltip">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="float-right">
                                {{ $schedules->withQueryString()->links() }}
                            </div>

                            <!-- Empty State -->
                            @if ($schedules->count() == 0)
                                <div class="text-center py-4">
                                    <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">
                                        Belum ada jadwal kegiatan.
                                        @if ($isAdmin)
                                            <a href="{{ route_encrypted('schedules.create') }}">Buat jadwal pertama</a>.
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-posts.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // SweetAlert for delete confirmation
            $('.confirm-delete').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');

                swal({
                        title: 'Apakah Anda yakin?',
                        text: 'Jadwal kegiatan yang dihapus tidak dapat dikembalikan!',
                        icon: 'warning',
                        buttons: {
                            cancel: {
                                text: "Batal",
                                value: null,
                                visible: true,
                                className: "btn-secondary",
                                closeModal: true,
                            },
                            confirm: {
                                text: "Ya, Hapus!",
                                value: true,
                                visible: true,
                                className: "btn-danger",
                                closeModal: false
                            }
                        },
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            form.submit();
                        }
                    });
            });
        });
    </script>
@endpush
