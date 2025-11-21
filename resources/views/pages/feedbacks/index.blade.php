@extends('layouts.app')

@section('title', 'Feedback untuk Manajemen')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Feedback untuk Manajemen</h1>
                @if (!$isAdmin)
                    <div class="section-header-button">
                        <a href="{{ route('feedbacks.create') }}" class="btn btn-primary">Buat Feedback Baru</a>
                    </div>
                @endif
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('feedbacks.index') }}">Feedback</a></div>
                    <div class="breadcrumb-item">All Feedback</div>
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

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                        {{ session('error') }}
                                    </div>
                                </div>
                            @endif

                            <div class="float-right">
                                <form method="GET" action="{{ route('feedbacks.index') }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari judul feedback..."
                                            name="search" value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="clearfix mb-3"></div>

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Judul</th>
                                            <th>Kategori</th>
                                            <th>Prioritas</th>
                                            <th>Status</th>
                                            <th>Dibuat Oleh</th>
                                            <th>Tanggal Dibuat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($feedbacks as $feedback)
                                            <tr>
                                                <td>{{ $loop->iteration + ($feedbacks->currentPage() - 1) * $feedbacks->perPage() }}
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <strong class="text-dark">{{ $feedback->title }}</strong>
                                                        @if ($feedback->status === 'draft')
                                                            <small class="text-muted mt-1">
                                                                <i class="fas fa-edit"></i> Draft
                                                            </small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">{{ $feedback->category_name }}</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $priorityClass = [
                                                            'low' => 'secondary',
                                                            'medium' => 'info',
                                                            'high' => 'warning',
                                                            'urgent' => 'danger',
                                                        ][$feedback->priority];
                                                    @endphp
                                                    <span class="badge badge-{{ $priorityClass }}">
                                                        {{ $feedback->priority_name }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusClass = [
                                                            'draft' => 'secondary',
                                                            'submitted' => 'primary',
                                                            'under_review' => 'warning',
                                                            'responded' => 'success',
                                                            'closed' => 'dark',
                                                        ][$feedback->status];
                                                    @endphp
                                                    <span class="badge badge-{{ $statusClass }}">
                                                        {{ $feedback->status_name }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="ml-2">
                                                            {{ $feedback->author->name }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $feedback->created_at->format('d/m/Y') }}<br>
                                                        <span
                                                            class="text-xs">{{ $feedback->created_at->format('H:i') }}</span>
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-start">
                                                        <!-- Tombol View - selalu tampil -->
                                                        <a href="{{ route('feedbacks.show', $feedback->id) }}"
                                                            class="btn btn-sm btn-info mr-1" title="Lihat Detail"
                                                            data-toggle="tooltip">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        <!-- Tombol Edit & Delete - hanya untuk pengurus serikat dan status draft -->
                                                        @if (!$isAdmin && $feedback->status === 'draft' && $feedback->created_by == Auth::id())
                                                            <a href="{{ route('feedbacks.edit', $feedback->id) }}"
                                                                class="btn btn-sm btn-warning mr-1" title="Edit Feedback"
                                                                data-toggle="tooltip">
                                                                <i class="fas fa-edit"></i>
                                                            </a>

                                                            <form action="{{ route('feedbacks.destroy', $feedback->id) }}"
                                                                method="POST" class="d-inline mr-1">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-danger confirm-delete"
                                                                    title="Hapus Feedback" data-toggle="tooltip">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>

                                                            <!-- Tombol Submit untuk draft (pengurus serikat) -->
                                                            <form action="{{ route('feedbacks.submit', $feedback->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-success"
                                                                    onclick="return confirm('Kirim feedback ke manajemen? Feedback tidak dapat diedit setelah dikirim.')"
                                                                    title="Kirim ke Manajemen" data-toggle="tooltip">
                                                                    <i class="fas fa-paper-plane"></i>
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
                                {{ $feedbacks->withQueryString()->links() }}
                            </div>

                            <!-- Empty State -->
                            @if ($feedbacks->count() == 0)
                                <div class="text-center py-4">
                                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">
                                        @if ($isAdmin)
                                            Belum ada feedback yang dikirim ke manajemen.
                                        @else
                                            Anda belum membuat feedback.
                                            <a href="{{ route('feedbacks.create') }}">Buat feedback pertama Anda</a>.
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
                        text: 'Feedback yang dihapus tidak dapat dikembalikan!',
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

            // Search functionality
            $('input[name="search"]').on('keyup', function(e) {
                if (e.key === 'Enter') {
                    $(this).closest('form').submit();
                }
            });
        });
    </script>
@endpush
