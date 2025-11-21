@extends('layouts.app')

@section('title', 'Edit Program/Achievement')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Program/Achievement</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('pages.program-achievements.index') }}">Program &
                            Achievement</a></div>
                    <div class="breadcrumb-item">Edit</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('pages.program-achievements.update', $program->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Type <span class="text-danger">*</span></label>
                                                <select name="type"
                                                    class="form-control @error('type') is-invalid @enderror" required>
                                                    <option value="">Select Type</option>
                                                    @foreach (App\Models\ProgramAchievement::TYPES as $key => $label)
                                                        <option value="{{ $key }}"
                                                            {{ old('type', $program->type) == $key ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Category <span class="text-danger">*</span></label>
                                                <select name="category"
                                                    class="form-control @error('category') is-invalid @enderror" required>
                                                    <option value="">Select Category</option>
                                                    @foreach (App\Models\ProgramAchievement::CATEGORIES as $key => $label)
                                                        <option value="{{ $key }}"
                                                            {{ old('category', $program->category) == $key ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('category')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title"
                                            class="form-control @error('title') is-invalid @enderror"
                                            value="{{ old('title', $program->title) }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Description <span class="text-danger">*</span></label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description', $program->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Year <span class="text-danger">*</span></label>
                                                <select name="year"
                                                    class="form-control @error('year') is-invalid @enderror" required>
                                                    <option value="">Select Year</option>
                                                    @foreach (App\Models\ProgramAchievement::getYears() as $year)
                                                        <option value="{{ $year }}"
                                                            {{ old('year', $program->year) == $year ? 'selected' : '' }}>
                                                            {{ $year }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('year')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Published Date</label>
                                                <input type="datetime-local" name="published_at"
                                                    class="form-control @error('published_at') is-invalid @enderror"
                                                    value="{{ old('published_at', $program->published_at ? $program->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                                                @error('published_at')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Image</label>
                                        @if ($program->image)
                                            <div class="mb-3">
                                                <img src="{{ asset('storage/' . $program->image) }}"
                                                    alt="{{ $program->title }}" class="img-thumbnail"
                                                    style="max-height: 200px;">
                                            </div>
                                        @endif
                                        <div class="custom-file">
                                            <input type="file" name="image"
                                                class="custom-file-input @error('image') is-invalid @enderror"
                                                id="image" accept="image/*">
                                            <label class="custom-file-label" for="image">Choose new image</label>
                                            <small class="form-text text-muted">
                                                Leave empty to keep current image. Maximum file size: 5MB.
                                                Allowed file types: JPEG, PNG, JPG, GIF, WEBP
                                            </small>
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Order</label>
                                                <input type="number" name="order"
                                                    class="form-control @error('order') is-invalid @enderror"
                                                    value="{{ old('order', $program->order) }}" min="0">
                                                @error('order')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="d-block">Status</label>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="is_active"
                                                        name="is_active" value="1"
                                                        {{ old('is_active', $program->is_active) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_active">Active</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update
                                        </button>
                                        <a href="{{ route('pages.program-achievements.index') }}"
                                            class="btn btn-secondary">
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
        $(document).ready(function() {
            // Update file input label with selected filename
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });
        });
    </script>
@endpush
