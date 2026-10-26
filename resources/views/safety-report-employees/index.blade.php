@extends('layouts.app')

@section('title', 'Data Karyawan')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Data Karyawan</h1>
                <div class="section-header-button">
                    <a href="{{ route('safety-report-employees.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus mr-1"></i> Tambah Karyawan
                    </a>
                </div>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Data Karyawan</div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible show fade" role="alert">
                                    <div class="alert-body">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        {{ session('success') }}
                                    </div>
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible show fade" role="alert">
                                    <div class="alert-body">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <strong>Import tidak dapat diproses.</strong>
                                        <ul class="mb-0 mt-2">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            <div class="row align-items-center mb-4">
                                <div class="col-lg-6 mb-3 mb-lg-0">
                                    <p class="text-muted mb-0">
                                        Daftar karyawan yang dapat diverifikasi untuk mengirim laporan Safety.
                                        <span class="font-weight-bold">{{ $employees->total() }}</span> karyawan
                                    </p>
                                </div>
                                <div class="col-lg-6 ml-auto">
                                    <form method="GET" action="{{ route('safety-report-employees.index') }}">
                                        <label for="employee-search" class="sr-only">Cari NPK atau nama</label>
                                        <div class="input-group">
                                            <input
                                                id="employee-search"
                                                type="search"
                                                name="search"
                                                value="{{ request('search') }}"
                                                class="form-control"
                                                placeholder="Cari NPK atau nama"
                                            >
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary" aria-label="Cari karyawan">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                            @if (request('search'))
                                                <div class="input-group-append">
                                                    <a href="{{ route('safety-report-employees.index') }}" class="btn btn-light border">
                                                        Reset
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="border rounded bg-light p-3 mb-4">
                                <div class="row align-items-center">
                                    <div class="col-lg-5 mb-3 mb-lg-0">
                                        <h6 class="mb-1"><i class="fas fa-file-import mr-1"></i> Import Excel</h6>
                                        <small class="text-muted">Gunakan file .xlsx atau .xls dengan header kolom NPK dan Nama. Maksimal 5.000 baris dan 10 MB.</small>
                                    </div>
                                    <div class="col-lg-7">
                                        <form method="POST" action="{{ route('safety-report-employees.import') }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="input-group">
                                                <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required aria-label="Pilih file Excel">
                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-upload mr-1"></i> Import
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <a href="{{ route('safety-report-employees.template') }}" class="small d-inline-block mt-2">
                                            <i class="fas fa-download mr-1"></i> Unduh template Excel
                                        </a>
                                        <small class="d-block text-muted">NPK yang sudah terdaftar akan diperbarui namanya tanpa mengubah status aktifnya.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-nowrap" style="width: 70px;">No.</th>
                                            <th scope="col" class="text-nowrap">NPK</th>
                                            <th scope="col">Nama Karyawan</th>
                                            <th scope="col" class="text-nowrap">Status</th>
                                            <th scope="col" class="text-center text-nowrap" style="width: 180px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($employees as $employee)
                                            <tr>
                                                <td>{{ $employees->firstItem() + $loop->index }}</td>
                                                <td class="font-weight-bold">{{ $employee->nrp }}</td>
                                                <td>{{ $employee->name }}</td>
                                                <td>
                                                    @if ($employee->is_active)
                                                        <span class="badge badge-success">Aktif</span>
                                                    @else
                                                        <span class="badge badge-secondary">Nonaktif</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center flex-nowrap">
                                                        <a
                                                            href="{{ route('safety-report-employees.edit', $employee) }}"
                                                            class="btn btn-sm btn-outline-primary mr-2"
                                                        >
                                                            <i class="fas fa-edit mr-1"></i> Ubah
                                                        </a>
                                                        <form
                                                            action="{{ route('safety-report-employees.destroy', $employee) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Hapus data karyawan ini?')"
                                                        >
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                <i class="fas fa-trash-alt mr-1"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="py-5 text-center">
                                                    <i class="fas fa-users fa-2x text-muted mb-3 d-block"></i>
                                                    <strong class="d-block">
                                                        {{ request('search') ? 'Karyawan tidak ditemukan' : 'Belum ada data karyawan' }}
                                                    </strong>
                                                    <span class="text-muted">
                                                        {{ request('search') ? 'Coba kata kunci NPK atau nama yang lain.' : 'Tambahkan data karyawan agar mereka dapat memverifikasi identitas saat melapor.' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if ($employees->hasPages())
                                <div class="d-flex justify-content-end mt-4">
                                    {{ $employees->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
