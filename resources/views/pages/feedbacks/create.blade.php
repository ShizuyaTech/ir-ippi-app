@extends('layouts.app')

@section('title', 'Buat Feedback Baru')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Create Feecback</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('feedbacks.index') }}">Feedback</a></div>
                    <div class="breadcrumb-item">Create Feedback</div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Form Feedback untuk Manajemen</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('feedbacks.store') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label>Judul Feedback *</label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                                        placeholder="Masukkan judul feedback yang jelas dan deskriptif...">
                                    @error('title')
                                        <div class="invalid-feedback" style="display: block;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kategori *</label>
                                            <select name="category" class="form-control selectric">
                                                <option value="">Pilih Kategori</option>
                                                @foreach (App\Models\Feedback::CATEGORIES as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ old('category') == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category')
                                                <div class="invalid-feedback" style="display: block;">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Prioritas *</label>
                                            <select name="priority" class="form-control selectric">
                                                <option value="">Pilih Prioritas</option>
                                                @foreach (App\Models\Feedback::PRIORITIES as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ old('priority') == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('priority')
                                                <div class="invalid-feedback" style="display: block;">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Isi Feedback *</label>
                                    <textarea name="content" rows="8" class="form-control summernote"
                                        placeholder="Tuliskan detail feedback/masukan untuk manajemen...">{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback" style="display: block;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Status *</label>
                                    <select name="status" class="form-control selectric">
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Simpan
                                            sebagai Draft</option>
                                        <option value="submitted" {{ old('status') == 'submitted' ? 'selected' : '' }}>
                                            Kirim ke Manajemen</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback" style="display: block;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="card-footer text-right">
                                    <a href="{{ route('feedbacks.index') }}" class="btn btn-secondary">Batal</a>
                                    <button type="submit" class="btn btn-primary">Simpan Feedback</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/features-post-create.js') }}"></script>
@endpush
