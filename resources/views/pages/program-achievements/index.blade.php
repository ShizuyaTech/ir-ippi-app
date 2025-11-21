@extends('layouts.app')

@section('title', 'Program & Achievement Management')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Program & Achievement Management</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Program & Achievement</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row mb-3">
                    <div class="col">
                        <a href="{{ route('pages.program-achievements.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New
                        </a>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('pages.program-achievements.index') }}" method="GET">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Type</label>
                                        <select name="type" class="form-control">
                                            <option value="">All Types</option>
                                            @foreach (App\Models\ProgramAchievement::TYPES as $key => $label)
                                                <option value="{{ $key }}"
                                                    {{ request('type') == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Year</label>
                                        <select name="year" class="form-control">
                                            <option value="">All Years</option>
                                            @foreach (App\Models\ProgramAchievement::getYears() as $year)
                                                <option value="{{ $year }}"
                                                    {{ request('year') == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Search</label>
                                        <input type="text" name="search" class="form-control"
                                            value="{{ request('search') }}" placeholder="Search by title or description...">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('pages.program-achievements.index') }}"
                                        class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Type</th>
                                                <th>Category</th>
                                                <th>Year</th>
                                                <th>Status</th>
                                                <th>Created By</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($programs as $program)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @if ($program->image)
                                                                <img src="{{ asset('storage/' . $program->image) }}"
                                                                    alt="{{ $program->title }}" class="mr-3"
                                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                                            @endif
                                                            {{ $program->title }}
                                                        </div>
                                                    </td>
                                                    <td>{{ App\Models\ProgramAchievement::TYPES[$program->type] ?? $program->type }}
                                                    </td>
                                                    <td>{{ App\Models\ProgramAchievement::CATEGORIES[$program->category] ?? $program->category }}
                                                    </td>
                                                    <td>{{ $program->year }}</td>
                                                    <td>
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox"
                                                                class="custom-control-input status-switch"
                                                                id="status{{ $program->id }}"
                                                                {{ $program->is_active ? 'checked' : '' }}
                                                                data-id="{{ $program->id }}"
                                                                data-url="{{ route('pages.program-achievements.toggle-status', $program->id) }}">
                                                            <label class="custom-control-label"
                                                                for="status{{ $program->id }}"></label>
                                                        </div>
                                                    </td>
                                                    <td>{{ $program->author->name ?? 'Unknown' }}</td>
                                                    <td>
                                                        <a href="{{ route('pages.program-achievements.edit', $program->id) }}"
                                                            class="btn btn-sm btn-info" data-toggle="tooltip"
                                                            title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form
                                                            action="{{ route('pages.program-achievements.destroy', $program->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are you sure you want to delete this item?')"
                                                                data-toggle="tooltip" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">No data available</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-4">
                                    {{ $programs->withQueryString()->links() }}
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
    <script>
        $(document).ready(function() {
            // Status toggle handling
            $('.status-switch').on('change', function() {
                const url = $(this).data('url');
                const id = $(this).data('id');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                        } else {
                            toastr.error('Failed to update status');
                        }
                    },
                    error: function() {
                        toastr.error('Failed to update status');
                    }
                });
            });
        });
    </script>
@endpush
