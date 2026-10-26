@extends('layouts.auth')

@section('title', 'Login Dashboard')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">
@endpush

@push('page-style')
    <style>
        body.dashboard-login-page {
            min-height: 100vh;
            background:
                linear-gradient(rgba(15, 27, 38, .66), rgba(15, 27, 38, .76)),
                url('{{ asset('img/login-page.png') }}') center / cover fixed;
        }

        .dashboard-login-page #app {
            min-height: 100vh;
            background: transparent;
        }

        .dashboard-login-page #app > .section {
            display: flex;
            min-height: 100vh;
            align-items: center;
            padding: 2rem 0;
        }

        .dashboard-login-page #app > .section > .container {
            width: 100%;
            margin-top: 0 !important;
        }

        .dashboard-login-page #app > .section > .container > .row > div {
            max-width: 460px;
            margin-right: auto;
            margin-left: auto;
        }

        .dashboard-login-page .login-brand {
            margin-bottom: 1.5rem;
            color: #fff;
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-align: center;
        }

        .dashboard-login-page .login-brand::before {
            content: "INDUSTRIAL RELATIONSHIP PT. IPPI";
        }

        .dashboard-login-page .card.card-primary {
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, .35);
            border-radius: 1rem;
            background: rgba(255, 255, 255, .97);
            box-shadow: 0 1.5rem 4rem rgba(0, 0, 0, .28);
        }

        .dashboard-login-page .card.card-primary .card-header {
            padding: 1.5rem 2rem .75rem;
            border-bottom: 0;
            background: transparent;
        }

        .dashboard-login-page .card.card-primary .card-header h4 {
            margin: 0;
            color: #172b3a;
            font-size: 1.45rem;
        }

        .dashboard-login-page .card.card-primary .card-body {
            padding: 1rem 2rem 2rem;
        }

        .dashboard-login-page .form-control {
            min-height: 46px;
            border-radius: .5rem;
        }

        .dashboard-login-page .btn-primary {
            border: 0;
            border-radius: .5rem;
            background: #ff4a17;
            box-shadow: 0 .4rem 1rem rgba(255, 74, 23, .22);
            font-weight: 600;
        }

        .dashboard-login-page .btn-primary:hover,
        .dashboard-login-page .btn-primary:focus {
            background: #e83c0d;
        }

        .dashboard-login-page .simple-footer {
            color: rgba(255, 255, 255, .8);
        }

        @media (max-width: 575.98px) {
            .dashboard-login-page .login-brand {
                font-size: 1.2rem;
            }

            .dashboard-login-page .card.card-primary .card-header {
                padding: 1.25rem 1.25rem .5rem;
            }

            .dashboard-login-page .card.card-primary .card-body {
                padding: 1rem 1.25rem 1.5rem;
            }
        }
    </style>
@endpush

@section('main')
    <div class="card card-primary">
        <div class="card-header">
            <h4>Login</h4>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible show fade" role="alert">
                    <div class="alert-body">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-circle mr-2 mt-1" aria-hidden="true"></i>
                            <div>
                                <strong>Login gagal</strong>
                                <p class="mb-0">{{ $errors->first() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate="">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        id="email"
                        type="email"
                        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                        name="email"
                        value="{{ old('email') }}"
                        aria-describedby="email-error"
                        autocomplete="username"
                        tabindex="1"
                        required
                        autofocus
                    >
                    @error('email')
                        <div id="email-error" class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input
                        id="password"
                        type="password"
                        class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                        name="password"
                        aria-describedby="password-error"
                        autocomplete="current-password"
                        tabindex="2"
                        required
                    >
                    @error('password')
                        <div id="password-error" class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                        <label class="custom-control-label" for="remember-me">Remember Me</label>
                    </div>
                </div> --}}

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                        Login
                    </button>
                </div>
            </form>
            {{-- <div class="mt-4 mb-3 text-center">
                <div class="text-job text-muted">Login With Social</div>
            </div>
            <div class="row sm-gutters">
                <div class="col-6">
                    <a class="btn btn-block btn-social btn-facebook">
                        <span class="fab fa-facebook"></span> Facebook
                    </a>
                </div>
                <div class="col-6">
                    <a class="btn btn-block btn-social btn-twitter">
                        <span class="fab fa-twitter"></span> Twitter
                    </a>
                </div>
            </div> --}}

        </div>
    </div>
    {{-- <div class="text-muted mt-5 text-center">
        Don't have an account? <a href="auth-register.html">Create One</a>
    </div> --}}
@endsection

@push('scripts')
    <!-- JS Libraies -->

    <!-- Page Specific JS File -->
@endpush
