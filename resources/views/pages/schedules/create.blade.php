@extends('layouts.app')

@section('title', 'Tambah Jadwal Kegiatan')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Jadwal Kegiatan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route_encrypted('schedules.index') }}">Jadwal Kegiatan</a></div>
                    <div class="breadcrumb-item">Tambah Jadwal</div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Form Jadwal Kegiatan</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route_encrypted('schedules.store') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label>Judul Kegiatan *</label>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title') }}" placeholder="Masukkan judul kegiatan...">
                                    @error('title')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Deskripsi</label>
                                    <textarea name="description" class="form-control summernote-simple @error('description') is-invalid @enderror"
                                        placeholder="Masukkan deskripsi kegiatan...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tanggal Mulai *</label>
                                            <input type="date" name="start_date"
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
                                            <label>Tanggal Selesai *</label>
                                            <input type="date" name="end_date"
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

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Waktu Mulai</label>
                                            <input type="time" name="start_time"
                                                class="form-control @error('start_time') is-invalid @enderror"
                                                value="{{ old('start_time') }}">
                                            @error('start_time')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Waktu Selesai</label>
                                            <input type="time" name="end_time"
                                                class="form-control @error('end_time') is-invalid @enderror"
                                                value="{{ old('end_time') }}">
                                            @error('end_time')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tipe Kegiatan *</label>
                                            <select name="type"
                                                class="form-control selectric @error('type') is-invalid @enderror">
                                                <option value="">Pilih Tipe</option>
                                                @foreach (App\Models\Schedule::TYPES as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ old('type') == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Prioritas *</label>
                                            <select name="priority"
                                                class="form-control selectric @error('priority') is-invalid @enderror">
                                                <option value="">Pilih Prioritas</option>
                                                @foreach (App\Models\Schedule::PRIORITIES as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ old('priority') == $key ? 'selected' : '' }}>
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Status *</label>
                                            <select name="status"
                                                class="form-control selectric @error('status') is-invalid @enderror">
                                                @foreach (App\Models\Schedule::STATUSES as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ old('status') == $key ? 'selected' : '' }}>
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
                                </div>

                                <div class="form-group">
                                    <label>Lokasi</label>
                                    <input type="text" name="location"
                                        class="form-control @error('location') is-invalid @enderror"
                                        value="{{ old('location') }}" placeholder="Masukkan lokasi kegiatan...">
                                    @error('location')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Catatan</label>
                                    <textarea name="notes" class="form-control summernote-simple @error('notes') is-invalid @enderror"
                                        placeholder="Masukkan catatan tambahan...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="card-footer text-right">
                                    <a href="{{ route_encrypted('schedules.index') }}" class="btn btn-secondary">Batal</a>
                                    <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
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
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>

    <!-- Page Specific JS File -->
    {{-- <script>
        $(document).ready(function() {
            // Initialize summernote
            $('.summernote-simple').summernote({
                dialogsInBody: true,
                minHeight: 120,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough']],
                    ['para', ['paragraph']]
                ]
            });

            // Set minimum date for start_date to today
            $('input[name="start_date"]').attr('min', new Date().toISOString().split('T')[0]);
            
            // Update end_date min date when start_date changes
            $('input[name="start_date"]').on('change', function() {
                $('input[name="end_date"]').attr('min', $(this).val());
            });
        });
    </script> --}}

    <script>
        $(document).ready(function() {
            // Check if there's a date parameter for pre-filling
            const urlParams = new URLSearchParams(window.location.search);
            const dateParam = urlParams.get('date');

            if (dateParam) {
                $('input[name="start_date"]').val(dateParam);
                $('input[name="end_date"]').val(dateParam);
            }

            // Set minimum date for start_date to today
            $('input[name="start_date"]').attr('min', new Date().toISOString().split('T')[0]);

            // Update end_date min date when start_date changes
            $('input[name="start_date"]').on('change', function() {
                $('input[name="end_date"]').attr('min', $(this).val());
            });
        });
    </script>
@endpush
