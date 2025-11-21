<div class="card task-card mb-3" data-task-id="{{ $task->id }}">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h6 class="card-title mb-0 text-dark">{{ $task->title }}</h6>
            <span
                class="badge badge-{{ [
                    1 => 'secondary',
                    2 => 'info',
                    3 => 'warning',
                    4 => 'danger',
                ][$task->priority] }}">{{ $task->priority_name }}</span>
        </div>

        @if ($task->description)
            <p class="card-text small text-muted mb-2">{{ Str::limit($task->description, 80) }}</p>
        @endif

        <div class="task-meta">
            @if ($task->due_date)
                <small class="text-muted d-block mb-1">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Due: {{ $task->formatted_due_date }}
                    @if ($task->isOverdue())
                        <span class="badge badge-danger ml-1">Overdue</span>
                    @endif
                </small>
            @endif

            @if ($task->assignee)
                <small class="text-muted d-block mb-1">
                    <i class="fas fa-user mr-1"></i>
                    {{ $task->assignee->name }}
                </small>
            @endif
        </div>

        <div class="d-flex justify-content-between align-items-center mt-2">
            <small class="text-muted">
                <i class="fas fa-clock mr-1"></i>
                {{ $task->created_at->format('d M') }}
            </small>
            <div class="task-actions">
                <a href="{{ route('lks-bipartit.show', $task->id) }}" class="btn btn-sm btn-outline-info"
                    title="Lihat Detail">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
        </div>
    </div>
</div>
