@extends('layouts.app')

@section('title', 'LKS Bipartit - To Do List')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/dragula/dragula.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            {{-- <div class="section-header">
                <h1>LKS Bipartit</h1>
                <div class="section-header-button">
                    <a href="{{ route_encrypted('lks-bipartit.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Task
                    </a>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">LKS Bipartit</div>
                </div>
            </div> --}}
            <div class="section-header">
                <h1>LKS Bipartit</h1>
                <div class="section-header-button">
                    @if (isset($isAdmin) && $isAdmin)
                        <a href="{{ route_encrypted('lks-bipartit.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Task
                        </a>
                    @endif
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item">LKS Bipartit</div>
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

                            <!-- Kanban Board -->
                            <div class="row">
                                <!-- Review Column -->
                                <div class="col-md-4">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h4 class="mb-0">
                                                <i class="fas fa-search mr-2"></i>Review
                                                <span
                                                    class="badge badge-light float-right">{{ $tasks->where('status', 'review')->count() }}</span>
                                            </h4>
                                        </div>
                                        <div class="card-body kanban-column" id="review-column" data-status="review">
                                            {{-- @foreach ($tasks->where('status', 'review') as $task)
                                                @include('lks-bipartit.partials.task-card', [
                                                    'task' => $task,
                                                ])
                                            @endforeach --}}
                                            @foreach ($tasks->where('status', 'review') as $task)
                                                @if (View::exists('lks-bipartit.partials.task-card'))
                                                    @include('lks-bipartit.partials.task-card', [
                                                        'task' => $task,
                                                    ])
                                                @else
                                                    <!-- Fallback: render task card manually -->
                                                    <div class="card mb-3">
                                                        <div class="card-body">
                                                            <h6>{{ $task->title }}</h6>
                                                            <span
                                                                class="badge badge-secondary">{{ $task->priority_name }}</span>
                                                            @if ($task->description)
                                                                <p class="small text-muted mt-2">
                                                                    {{ Str::limit($task->description, 80) }}</p>
                                                            @endif
                                                            <div
                                                                class="d-flex justify-content-between align-items-center mt-2">
                                                                <small
                                                                    class="text-muted">{{ $task->created_at->format('d M') }}</small>
                                                                <a href="{{ route_encrypted('lks-bipartit.show', $task->id) }}"
                                                                    class="btn btn-sm btn-outline-info">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                            @if ($tasks->where('status', 'review')->count() == 0)
                                                <div class="text-center text-muted py-4">
                                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                                    <p>Tidak ada task</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- On Progress Column -->
                                <div class="col-md-4">
                                    <div class="card card-warning">
                                        <div class="card-header">
                                            <h4 class="mb-0">
                                                <i class="fas fa-sync-alt mr-2"></i>On Progress
                                                <span
                                                    class="badge badge-light float-right">{{ $tasks->where('status', 'on_progress')->count() }}</span>
                                            </h4>
                                        </div>
                                        <div class="card-body kanban-column" id="progress-column" data-status="on_progress">
                                            {{-- @foreach ($tasks->where('status', 'on_progress') as $task)
                                                @include('lks-bipartit.partials.task-card', [
                                                    'task' => $task,
                                                ])
                                            @endforeach --}}
                                            @foreach ($tasks->where('status', 'on_progress') as $task)
                                                @if (View::exists('lks-bipartit.partials.task-card'))
                                                    @include('lks-bipartit.partials.task-card', [
                                                        'task' => $task,
                                                    ])
                                                @else
                                                    <!-- Fallback: render task card manually -->
                                                    <div class="card mb-3">
                                                        <div class="card-body">
                                                            <h6>{{ $task->title }}</h6>
                                                            <span
                                                                class="badge badge-secondary">{{ $task->priority_name }}</span>
                                                            @if ($task->description)
                                                                <p class="small text-muted mt-2">
                                                                    {{ Str::limit($task->description, 80) }}</p>
                                                            @endif
                                                            <div
                                                                class="d-flex justify-content-between align-items-center mt-2">
                                                                <small
                                                                    class="text-muted">{{ $task->created_at->format('d M') }}</small>
                                                                <a href="{{ route_encrypted('lks-bipartit.show', $task->id) }}"
                                                                    class="btn btn-sm btn-outline-info">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                            @if ($tasks->where('status', 'on_progress')->count() == 0)
                                                <div class="text-center text-muted py-4">
                                                    <i class="fas fa-cogs fa-2x mb-2"></i>
                                                    <p>Tidak ada task</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Done Column -->
                                <div class="col-md-4">
                                    <div class="card card-success">
                                        <div class="card-header">
                                            <h4 class="mb-0">
                                                <i class="fas fa-check-circle mr-2"></i>Done
                                                <span
                                                    class="badge badge-light float-right">{{ $tasks->where('status', 'done')->count() }}</span>
                                            </h4>
                                        </div>
                                        <div class="card-body kanban-column" id="done-column" data-status="done">
                                            {{-- @foreach ($tasks->where('status', 'done') as $task)
                                                @include('lks-bipartit.partials.task-card', [
                                                    'task' => $task,
                                                ])
                                            @endforeach --}}
                                            @foreach ($tasks->where('status', 'done') as $task)
                                                @if (View::exists('lks-bipartit.partials.task-card'))
                                                    @include('lks-bipartit.partials.task-card', [
                                                        'task' => $task,
                                                    ])
                                                @else
                                                    <!-- Fallback: render task card manually -->
                                                    <div class="card mb-3">
                                                        <div class="card-body">
                                                            <h6>{{ $task->title }}</h6>
                                                            <span
                                                                class="badge badge-secondary">{{ $task->priority_name }}</span>
                                                            @if ($task->description)
                                                                <p class="small text-muted mt-2">
                                                                    {{ Str::limit($task->description, 80) }}</p>
                                                            @endif
                                                            <div
                                                                class="d-flex justify-content-between align-items-center mt-2">
                                                                <small
                                                                    class="text-muted">{{ $task->created_at->format('d M') }}</small>
                                                                <a href="{{ route_encrypted('lks-bipartit.show', $task->id) }}"
                                                                    class="btn btn-sm btn-outline-info">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                            @if ($tasks->where('status', 'done')->count() == 0)
                                                <div class="text-center text-muted py-4">
                                                    <i class="fas fa-flag-checkered fa-2x mb-2"></i>
                                                    <p>Tidak ada task</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- List View (Alternative) -->
                            <div class="row mt-5">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>
                                                <i class="fas fa-list mr-2"></i>List View
                                            </h4>
                                        </div>
                                        <div class="card-body">
                                            <!-- Filter Section -->
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <form method="GET" action="{{ route_encrypted('lks-bipartit.index') }}">
                                                        <select name="status" class="form-control selectric"
                                                            onchange="this.form.submit()">
                                                            <option value="">Semua Status</option>
                                                            @foreach (App\Models\LksBipartit::STATUSES as $key => $value)
                                                                <option value="{{ $key }}"
                                                                    {{ request('status') == $key ? 'selected' : '' }}>
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </form>
                                                </div>
                                                <div class="col-md-4">
                                                    <form method="GET" action="{{ route_encrypted('lks-bipartit.index') }}">
                                                        <select name="priority" class="form-control selectric"
                                                            onchange="this.form.submit()">
                                                            <option value="">Semua Prioritas</option>
                                                            @foreach (App\Models\LksBipartit::PRIORITIES as $key => $value)
                                                                <option value="{{ $key }}"
                                                                    {{ request('priority') == $key ? 'selected' : '' }}>
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </form>
                                                </div>
                                                <div class="col-md-4">
                                                    <form method="GET" action="{{ route_encrypted('lks-bipartit.index') }}">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control"
                                                                placeholder="Cari task..." name="search"
                                                                value="{{ request('search') }}">
                                                            <div class="input-group-append">
                                                                <button class="btn btn-primary"><i
                                                                        class="fas fa-search"></i></button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Task</th>
                                                            <th>Prioritas</th>
                                                            <th>Due Date</th>
                                                            <th>Assignee</th>
                                                            <th>Status</th>
                                                            @if (isset($isAdmin) && $isAdmin)
                                                                <th>Aksi</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($tasks as $task)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>
                                                                    <div>
                                                                        <strong>{{ $task->title }}</strong>
                                                                        @if ($task->description)
                                                                            <br><small
                                                                                class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $priorityColors = [
                                                                            1 => 'secondary',
                                                                            2 => 'info',
                                                                            3 => 'warning',
                                                                            4 => 'danger',
                                                                        ];
                                                                    @endphp
                                                                    <span
                                                                        class="badge badge-{{ $priorityColors[$task->priority] }}">
                                                                        {{ $task->priority_name }}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    @if ($task->due_date)
                                                                        <span
                                                                            class="{{ $task->isOverdue() ? 'text-danger font-weight-bold' : 'text-muted' }}">
                                                                            {{ $task->formatted_due_date }}
                                                                            @if ($task->isOverdue())
                                                                                <br><small
                                                                                    class="text-danger">Overdue</small>
                                                                            @endif
                                                                        </span>
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($task->assignee)
                                                                        {{ $task->assignee->name }}
                                                                    @else
                                                                        <span class="text-muted">-</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        $statusColors = [
                                                                            'review' => 'primary',
                                                                            'on_progress' => 'warning',
                                                                            'done' => 'success',
                                                                        ];
                                                                    @endphp
                                                                    <span
                                                                        class="badge badge-{{ $statusColors[$task->status] }}">
                                                                        {{ $task->status_name }}
                                                                    </span>
                                                                </td>
                                                                @if (isset($isAdmin) && $isAdmin)
                                                                    <td>
                                                                        <div class="d-flex">
                                                                            <a href="{{ route_encrypted('lks-bipartit.show', $task->id) }}"
                                                                                class="btn btn-sm btn-info mr-1"
                                                                                title="Lihat Detail">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>
                                                                            <a href="{{ route_encrypted('lks-bipartit.edit', $task->id) }}"
                                                                                class="btn btn-sm btn-warning mr-1"
                                                                                title="Edit">
                                                                                <i class="fas fa-edit"></i>
                                                                            </a>
                                                                            <form
                                                                                action="{{ route_encrypted('lks-bipartit.destroy', $task->id) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="btn btn-sm btn-danger confirm-delete"
                                                                                    title="Hapus">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="float-right">
                                                {{ $tasks->withQueryString()->links() }}
                                            </div>
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
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('library/dragula/dragula.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Initialize selectric
            $('.selectric').selectric();

            // Drag and drop functionality
            var drake = dragula([
                document.getElementById('review-column'),
                document.getElementById('progress-column'),
                document.getElementById('done-column')
            ]);

            drake.on('drop', function(el, target, source, sibling) {
                var newStatus = $(target).data('status');
                var taskId = $(el).data('task-id');

                if (newStatus) {
                    $.ajax({
                        url: '{{ url('lks-bipartit') }}/' + taskId + '/update-status',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: newStatus
                        },
                        success: function(response) {
                            if (response.success) {
                                // Update badge counts jika perlu
                                console.log('Status updated to:', response.new_status);

                                // Optional: Show success message
                                swal('Sukses!', response.message, 'success');
                            }
                        },
                        error: function(xhr) {
                            console.error('Error updating status:', xhr);
                            // Revert visual changes if error
                            source.appendChild(el);
                            swal('Error!', 'Gagal mengupdate status', 'error');
                        }
                    });
                }
            });

            // Delete confirmation
            $('.confirm-delete').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');

                swal({
                    title: 'Apakah Anda yakin?',
                    text: 'Task yang dihapus tidak dapat dikembalikan!',
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
                }).then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
            });

            // Initialize tooltips
            $('[title]').tooltip();
        });
    </script>

    <style>
        .kanban-column {
            min-height: 500px;
            max-height: 700px;
            overflow-y: auto;
        }

        .gu-mirror {
            position: fixed !important;
            margin: 0 !important;
            z-index: 9999 !important;
            opacity: 0.8;
            transform: rotate(5deg);
        }

        .gu-hide {
            display: none !important;
        }

        .gu-unselectable {
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            user-select: none !important;
        }

        .gu-transit {
            opacity: 0.2;
        }

        /* Custom scrollbar for kanban columns */
        .kanban-column::-webkit-scrollbar {
            width: 6px;
        }

        .kanban-column::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .kanban-column::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .kanban-column::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
@endpush
