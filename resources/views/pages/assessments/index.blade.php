@extends('layouts.app')

@section('title', 'Assessments')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Assessments</h1>
                <div class="section-header-button">
                    <a href="{{ route('assessments.create') }}" class="btn btn-primary">Add New</a>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('assessments.index') }}">Assessments</a></div>
                    <div class="breadcrumb-item">All Assessments</div>
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

                            <div class="float-right">
                                <form method="GET" action="{{ route('assessments.index') }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search" name="title"
                                            value="{{ request('title') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="clearfix mb-3"></div>

                            <div class="table-responsive">
                                <!-- Dalam tabel di index.blade.php -->
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Status</th>
                                            <th>Pertanyaan</th>
                                            <th>Responses</th>
                                            <th>Participant</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($assessments as $assessment)
                                            <tr>
                                                <td>{{ $loop->iteration + ($assessments->currentPage() - 1) * $assessments->perPage() }}
                                                </td>
                                                <td>
                                                    <strong>{{ $assessment->title }}</strong>
                                                    @if ($assessment->description)
                                                        <br><small
                                                            class="text-muted">{{ Str::limit($assessment->description, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $assessment->is_active ? 'success' : 'danger' }}">
                                                        {{ $assessment->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>{{ $assessment->questions_count }}</td>
                                                <td>{{ $assessment->responses_count }}</td>
                                                <td>{{ $assessment->participant_count }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('assessments.show', $assessment->id) }}"
                                                            class="btn btn-info btn-sm" title="Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('assessments.edit', $assessment->id) }}"
                                                            class="btn btn-warning btn-sm" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="{{ route('assessments.responses', $assessment->id) }}"
                                                            class="btn btn-success btn-sm" title="Responses">
                                                            <i class="fas fa-chart-bar"></i>
                                                        </a>
                                                        <form action="{{ route('assessments.destroy', $assessment->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                title="Delete" onclick="return confirm('Are you sure?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="float-right">
                                {{ $assessments->withQueryString()->links() }}
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
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-posts.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.confirm-delete').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');

                swal({
                        title: 'Are you sure?',
                        text: 'Once deleted, you will not be able to recover this assessment!',
                        icon: 'warning',
                        buttons: true,
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
