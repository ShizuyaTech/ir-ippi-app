@extends('layouts.app')

@section('title', 'Tambah Scores Baru')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Scores Baru</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route_encrypted('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Tambah Scores</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Form Tambah Scores</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route_encrypted('dashboard-scores.store') }}" method="POST">
                                    @csrf
                                    
                                    <div class="form-group">
                                        <label for="ir_partnership">IR Partnership Score <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('ir_partnership') is-invalid @enderror" 
                                               id="ir_partnership" name="ir_partnership" 
                                               value="{{ old('ir_partnership') }}" min="0" max="100" step="0.01" required>
                                        @error('ir_partnership')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="form-text text-muted">Masukkan score antara 0 - 100</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="conductive_working_climate">Conductive Working Climate Score <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('conductive_working_climate') is-invalid @enderror" 
                                               id="conductive_working_climate" name="conductive_working_climate" 
                                               value="{{ old('conductive_working_climate') }}" min="0" max="100" step="0.01" required>
                                        @error('conductive_working_climate')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="ess">ESS Score <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('ess') is-invalid @enderror" 
                                               id="ess" name="ess" 
                                               value="{{ old('ess') }}" min="0" max="100" step="0.01" required>
                                        @error('ess')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="airsi">AIRSI Score <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('airsi') is-invalid @enderror" 
                                               id="airsi" name="airsi" 
                                               value="{{ old('airsi') }}" min="0" max="100" step="0.01" required>
                                        @error('airsi')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Simpan Scores
                                        </button>
                                        <a href="{{ route_encrypted('dashboard') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
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