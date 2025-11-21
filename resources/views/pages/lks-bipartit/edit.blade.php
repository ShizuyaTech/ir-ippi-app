@extends('layouts.app')

@section('title', 'Edit Task LKS Bipartit')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Task LKS Bipartit</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('lks-bipartit.index') }}">LKS Bipartit</a></div>
                    <div class="breadcrumb-item"><a
                            href="{{ route('lks-bipartit.show', $lksBipartit->id) }}">{{ Str::limit($lksBipartit->title, 20) }}</a>
                    </div>
                    <div class="breadcrumb-item">Edit Task</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Form Edit Task</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('lks-bipartit.update', $lksBipartit->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Judul Task
                                            <span class="text-danger">*</span></label>
                                        <div class="col-sm-12 col-md-7">
                                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                                name="title" value="{{ old('title', $lksBipartit->title) }}"
                                                placeholder="Masukkan judul task..." required>
                                            @error('title')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label
                                            class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Deskripsi</label>
                                        <div class="col-sm-12 col-md-7">
                                            <textarea class="form-control @error('description') is-invalid @enderror" name="description"
                                                placeholder="Masukkan deskripsi task..." rows="4">{{ old('description', $lksBipartit->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-12 col-md-7">
                                            <select class="form-control selectric @error('status') is-invalid @enderror"
                                                name="status" required>
                                                @foreach (App\Models\LksBipartit::STATUSES as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ old('status', $lksBipartit->status) == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Prioritas <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-12 col-md-7">
                                            <select class="form-control selectric @error('priority') is-invalid @enderror"
                                                name="priority" required>
                                                @foreach (App\Models\LksBipartit::PRIORITIES as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ old('priority', $lksBipartit->priority) == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('priority')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tanggal Jatuh
                                            Tempo</label>
                                        <div class="col-sm-12 col-md-7">
                                            <input type="date"
                                                class="form-control @error('due_date') is-invalid @enderror" name="due_date"
                                                value="{{ old('due_date', $lksBipartit->due_date) }}">
                                            @error('due_date')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Kosongkan jika tidak ada tanggal jatuh tempo
                                            </small>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Assign
                                            To</label>
                                        <div class="col-sm-12 col-md-7">
                                            <select
                                                class="form-control selectric @error('assigned_to') is-invalid @enderror"
                                                name="assigned_to">
                                                <option value="">Pilih Assignee</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ old('assigned_to', $lksBipartit->assigned_to) == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }} ({{ $user->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('assigned_to')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Catatan</label>
                                        <div class="col-sm-12 col-md-7">
                                            <textarea class="form-control @error('notes') is-invalid @enderror" name="notes"
                                                placeholder="Masukkan catatan tambahan..." rows="3">{{ old('notes', $lksBipartit->notes) }}</textarea>
                                            @error('notes')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            <small class="form-text text-muted">
                                                Catatan internal untuk task ini
                                            </small>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                        <div class="col-sm-12 col-md-7">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Update Task
                                            </button>
                                            <a href="{{ route('lks-bipartit.index') }}" class="btn btn-secondary">
                                                <i class="fas fa-arrow-left"></i> Kembali
                                            </a>
                                            <a href="{{ route('lks-bipartit.show', $lksBipartit->id) }}"
                                                class="btn btn-info">
                                                <i class="fas fa-eye"></i> Lihat Detail
                                            </a>
                                        </div>
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
    <!-- JS Libraies -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

    <!-- Page Specific JS File -->
    <script>
        $(document).ready(function() {
            // Initialize selectric
            $('.selectric').selectric();
        });
    </script>
@endpush
