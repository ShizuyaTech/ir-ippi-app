@extends('layouts.app')

@section('title', 'Create Assessment')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Create Assessment</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('assessments.index') }}">Assessments</a></div>
                    <div class="breadcrumb-item">Create Assessment</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Create New Assessment</h4>
                            </div>
                            <div class="card-body">
                                <!-- Display Validation Errors -->
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Display Success Message -->
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <!-- Display Error Message -->
                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <form action="{{ route('assessments.store') }}" method="POST">
                                    @csrf

                                    <div class="form-group">
                                        <label for="title">Title *</label>
                                        <input type="text" id="title" name="title"
                                            class="form-control @error('title') is-invalid @enderror"
                                            value="{{ old('title') }}" required autofocus>
                                        @error('title')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror"
                                            rows="4" placeholder="Optional description">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="participant_count">Number of Participants *</label>
                                                <input type="number" id="participant_count" name="participant_count"
                                                    class="form-control @error('participant_count') is-invalid @enderror"
                                                    value="{{ old('participant_count', 10) }}" min="1" max="1000"
                                                    required>
                                                @error('participant_count')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <small class="form-text text-muted">
                                                    Number of user codes to generate automatically
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="hidden" name="is_active" value="0">
                                                <label class="custom-switch mt-4">
                                                    <input type="checkbox" name="is_active" value="1"
                                                        class="custom-switch-input"
                                                        {{ old('is_active', true) ? 'checked' : '' }}>
                                                    <span class="custom-switch-indicator"></span>
                                                    <span class="custom-switch-description">Active Assessment</span>
                                                </label>
                                                <small class="form-text text-muted">
                                                    If inactive, this assessment will not be visible to users.
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="start_date">Start Date</label>
                                                <input type="datetime-local" id="start_date" name="start_date"
                                                    class="form-control @error('start_date') is-invalid @enderror"
                                                    value="{{ old('start_date') }}">
                                                @error('start_date')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="end_date">End Date</label>
                                                <input type="datetime-local" id="end_date" name="end_date"
                                                    class="form-control @error('end_date') is-invalid @enderror"
                                                    value="{{ old('end_date') }}">
                                                @error('end_date')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-info-circle"></i> Information</h6>
                                        <ul class="mb-0">
                                            <li>User codes will be generated automatically after creating this assessment
                                            </li>
                                            <li>You can add questions after creating the assessment</li>
                                            <li>Participants will need a valid user code to access this assessment</li>
                                        </ul>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-save"></i> Create Assessment & Generate User Codes
                                        </button>
                                        <a href="{{ route('assessments.index') }}" class="btn btn-secondary btn-lg">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    </div>
                                </form>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Set default dates hanya jika belum ada nilai old()
            @if (!old('start_date'))
                const now = new Date();
                const startDate = document.getElementById('start_date');
                const localDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(
                    0, 16);
                startDate.value = localDateTime;
            @endif

            @if (!old('end_date'))
                const endDate = document.getElementById('end_date');
                const oneMonthLater = new Date();
                oneMonthLater.setMonth(oneMonthLater.getMonth() + 1);
                const localEndDateTime = new Date(oneMonthLater.getTime() - oneMonthLater.getTimezoneOffset() *
                    60000).toISOString().slice(0, 16);
                endDate.value = localEndDateTime;
            @endif

            // Validation
            const startDateElem = document.getElementById('start_date');
            const endDateElem = document.getElementById('end_date');

            startDateElem.addEventListener('change', function() {
                if (endDateElem.value && endDateElem.value < this.value) {
                    alert('End date must be after start date');
                    endDateElem.value = '';
                }
            });

            endDateElem.addEventListener('change', function() {
                if (startDateElem.value && this.value < startDateElem.value) {
                    alert('End date must be after start date');
                    this.value = '';
                }
            });
        });
    </script>
@endpush
