@extends('layouts.app')

@section('title', 'Edit Scores')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Scores</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Edit Scores</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Form Edit Scores</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('dashboard-scores.update', $dashboardScore->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label for="ir_partnership">IR Partnership Score <span
                                                class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('ir_partnership') is-invalid @enderror"
                                            id="ir_partnership" name="ir_partnership"
                                            value="{{ old('ir_partnership', $dashboardScore->ir_partnership) }}"
                                            min="0" max="100" step="0.01" required>
                                        @error('ir_partnership')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="conductive_working_climate">Conductive Working Climate Score <span
                                                class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('conductive_working_climate') is-invalid @enderror"
                                            id="conductive_working_climate" name="conductive_working_climate"
                                            value="{{ old('conductive_working_climate', $dashboardScore->conductive_working_climate) }}"
                                            min="0" max="100" step="0.01" required>
                                        @error('conductive_working_climate')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="ess">ESS Score <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('ess') is-invalid @enderror"
                                            id="ess" name="ess" value="{{ old('ess', $dashboardScore->ess) }}"
                                            min="0" max="100" step="0.01" required>
                                        @error('ess')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="airsi">AIRSI Score <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('airsi') is-invalid @enderror"
                                            id="airsi" name="airsi" value="{{ old('airsi', $dashboardScore->airsi) }}"
                                            min="0" max="100" step="0.01" required>
                                        @error('airsi')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Scores
                                        </button>
                                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                                        </a>
                                        <form action="{{ route('dashboard-scores.destroy', $dashboardScore->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger confirm-delete">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
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
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.confirm-delete').on('click', function(e) {
                e.preventDefault();
                var form = $(this).closest('form');

                swal({
                    title: 'Apakah Anda yakin?',
                    text: 'Scores yang dihapus tidak dapat dikembalikan!',
                    icon: 'warning',
                    buttons: {
                        cancel: {
                            text: "Batal",
                            value: null,
                            visible: true,
                            className: "btn-secondary",
                        },
                        confirm: {
                            text: "Ya, Hapus!",
                            value: true,
                            visible: true,
                            className: "btn-danger",
                        }
                    },
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
