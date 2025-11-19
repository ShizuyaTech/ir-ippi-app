@extends('layouts.app')

@section('title', 'Edit Assessment')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Assessment</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route_encrypted('assessments.index') }}">Assessments</a></div>
                    <div class="breadcrumb-item">Edit Assessment</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
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

                        <!-- Edit Assessment Details -->
                        <div class="card">
                            <div class="card-header">
                                <h4>Edit Assessment Details</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route_encrypted('assessments.update', $assessment->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label for="title">Title *</label>
                                        <input type="text" id="title" name="title"
                                            class="form-control @error('title') is-invalid @enderror"
                                            value="{{ old('title', $assessment->title) }}" required autofocus>
                                        @error('title')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror"
                                            rows="4" placeholder="Optional description">{{ old('description', $assessment->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <input type="hidden" name="is_active" value="0">
                                        <label class="custom-switch mt-2">
                                            <input type="checkbox" name="is_active" value="1"
                                                class="custom-switch-input"
                                                {{ old('is_active', $assessment->is_active) ? 'checked' : '' }}>
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">Active Assessment</span>
                                        </label>
                                        <small class="form-text text-muted">
                                            If inactive, this assessment will not be visible to users.
                                        </small>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Assessment
                                        </button>
                                        <a href="{{ route_encrypted('assessments.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Questions Management -->
                        <div class="card">
                            <div class="card-header">
                                <h4>Manage Questions</h4>
                                <div class="card-header-action">
                                    <span class="badge badge-primary">{{ $assessment->questions->count() }}
                                        Questions</span>
                                </div>
                            </div>
                            <div class="card-body">

                                <!-- Add New Question Form - Simple -->
                                <div class="mb-4 p-3 border rounded bg-light">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-plus-circle"></i> Add New Question
                                    </h6>
                                    <form action="{{ route_encrypted('assessments.questions.store', $assessment->id) }}"
                                        method="POST">
                                        @csrf
                                        <div class="row align-items-end">
                                            <div class="col-md-10">
                                                <div class="form-group mb-0">
                                                    <label class="form-label">Question Text *</label>
                                                    <input type="text" name="question_text"
                                                        class="form-control @error('question_text') is-invalid @enderror"
                                                        placeholder="Enter new question..." required>
                                                    @error('question_text')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="fas fa-plus"></i> Add
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Existing Questions Table -->
                                <h6 class="mb-3">
                                    <i class="fas fa-list"></i> Existing Questions
                                </h6>

                                @if ($assessment->questions->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th width="5%" class="text-center">No</th>
                                                    <th width="75%">Question</th>
                                                    <th width="10%" class="text-center">Status</th>
                                                    <th width="10%" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($assessment->questions as $index => $question)
                                                    <tr>
                                                        <td class="text-center align-middle">
                                                            <span class="fw-bold">{{ $index + 1 }}</span>
                                                        </td>
                                                        <td class="align-middle">
                                                            <input type="text" id="question_{{ $question->id }}"
                                                                class="form-control form-control-sm question-input"
                                                                value="{{ $question->question_text }}"
                                                                data-id="{{ $question->id }}">
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <div class="form-check form-switch d-inline-block">
                                                                <input type="checkbox"
                                                                    class="form-check-input status-toggle"
                                                                    data-id="{{ $question->id }}"
                                                                    {{ $question->is_active ? 'checked' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger delete-question"
                                                                data-id="{{ $question->id }}"
                                                                data-text="{{ $question->question_text }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Single Save Button -->
                                    <div class="text-right mt-4">
                                        <button type="button" id="saveAllQuestions" class="btn btn-success btn-lg">
                                            <i class="fas fa-save"></i> Save All Changes
                                        </button>
                                    </div>
                                @else
                                    <div class="alert alert-info text-center">
                                        <i class="fas fa-info-circle"></i> No questions added yet. Add your first question
                                        above.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Delete question
            $(document).on('click', '.delete-question', function() {
                var questionId = $(this).data('id');
                var questionText = $(this).data('text');

                swal({
                        title: 'Are you sure?',
                        text: 'You are about to delete: "' + questionText + '"',
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            // Create form untuk DELETE request
                            var form = $('<form>').attr({
                                method: 'POST',
                                action: '/questions/' + questionId
                            });

                            form.append($('<input>').attr({
                                type: 'hidden',
                                name: '_token',
                                value: '{{ csrf_token() }}'
                            }));

                            form.append($('<input>').attr({
                                type: 'hidden',
                                name: '_method',
                                value: 'DELETE'
                            }));

                            $('body').append(form);
                            form.submit();
                        }
                    });
            });

            // Save all questions
            $('#saveAllQuestions').on('click', function() {
                var saveButton = $(this);
                saveButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

                var updates = [];
                var successCount = 0;
                var totalUpdates = 0;

                // Collect all changes
                $('.question-input').each(function() {
                    var questionId = $(this).data('id');
                    var newText = $(this).val();
                    var isActive = $(this).closest('tr').find('.status-toggle').is(':checked');

                    updates.push({
                        id: questionId,
                        question_text: newText,
                        is_active: isActive
                    });
                });

                totalUpdates = updates.length;

                // Process updates one by one
                var processUpdate = function(index) {
                    if (index >= updates.length) {
                        // All updates completed
                        swal({
                            title: 'Success!',
                            text: 'All questions have been updated successfully!',
                            icon: 'success',
                            button: 'OK'
                        }).then(() => {
                            // Redirect ke halaman edit yang sama, bukan index
                            window.location.href =
                                '{{ route_encrypted('assessments.edit', $assessment->id) }}';
                        });
                        return;
                    }

                    var update = updates[index];

                    $.ajax({
                        url: '/questions/' + update.id,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'PUT',
                            question_text: update.question_text,
                            is_active: update.is_active ? 1 : 0
                        },
                        success: function(response) {
                            successCount++;
                            processUpdate(index + 1);
                        },
                        error: function(xhr) {
                            swal('Error!', 'Failed to update question ID: ' + update.id,
                                'error');
                            saveButton.prop('disabled', false).html(
                                '<i class="fas fa-save"></i> Save All Changes');
                        }
                    });
                };

                // Start processing updates
                if (totalUpdates > 0) {
                    processUpdate(0);
                } else {
                    swal('Info', 'No changes to save.', 'info');
                    saveButton.prop('disabled', false).html('<i class="fas fa-save"></i> Save All Changes');
                }
            });
        });
    </script>
@endpush
