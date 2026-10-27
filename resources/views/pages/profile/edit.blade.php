@extends('layouts.app')

@section('title', 'Profil Saya')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Profil Saya</h1>
            </div>

            <div class="section-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-header">
                            <h4>Informasi akun</h4>
                        </div>

                        <div class="card-body">
                            <p class="text-muted">Perbarui nama, email, atau password Anda. Role akun tidak dapat diubah dari halaman ini.</p>

                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    value="{{ old('name', $user->name) }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    required
                                    maxlength="255"
                                    autocomplete="name"
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email', $user->email) }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    required
                                    maxlength="255"
                                    autocomplete="email"
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>
                            <h5>Ganti password</h5>
                            <p class="text-muted">Kosongkan bagian password jika tidak ingin menggantinya. Password baru minimal 8 karakter.</p>

                            <div class="form-group">
                                <label for="current_password">Password saat ini</label>
                                <input
                                    id="current_password"
                                    name="current_password"
                                    type="password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    autocomplete="current-password"
                                >
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">Password baru</label>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    autocomplete="new-password"
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label for="password_confirmation">Konfirmasi password baru</label>
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    class="form-control"
                                    autocomplete="new-password"
                                >
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Simpan perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection
