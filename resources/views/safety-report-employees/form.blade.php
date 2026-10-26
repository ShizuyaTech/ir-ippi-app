@extends('layouts.app')

@section('title', $employee->exists ? 'Ubah Karyawan' : 'Tambah Karyawan')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ $employee->exists ? 'Ubah Karyawan' : 'Tambah Karyawan' }}</h1>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ $employee->exists ? route('safety-report-employees.update', $employee) : route('safety-report-employees.store') }}">
                    @csrf
                    @if ($employee->exists)
                        @method('PUT')
                    @endif

                    <div class="form-group">
                        <label for="nrp">Nomor Pokok Karyawan (NPK)</label>
                        <input id="nrp" name="nrp" value="{{ old('nrp', $employee->nrp) }}" class="form-control @error('nrp') is-invalid @enderror" required maxlength="50">
                        @error('nrp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Nama sesuai data karyawan</label>
                        <input id="name" name="name" value="{{ old('name', $employee->name) }}" class="form-control @error('name') is-invalid @enderror" required maxlength="255">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input id="is_active" type="checkbox" name="is_active" value="1" class="form-check-input" @checked(old('is_active', $employee->is_active))>
                        <label class="form-check-label" for="is_active">Karyawan aktif dan dapat melaporkan Safety &amp; Healthy</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('safety-report-employees.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
