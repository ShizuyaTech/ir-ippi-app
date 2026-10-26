@extends('layouts-web.app')

@section('content')
    <style>
        #header.fixed-top {
            position: absolute;
            background: #172b3a;
        }

        .safety-report-page {
            min-height: 100vh;
            padding: 140px 0 80px;
            background: #f4f7fa;
        }

        .safety-report-page .card {
            overflow: hidden;
            border: 0;
            border-radius: 1rem;
        }

        .safety-report-page .card-body {
            background: #fff;
        }

        .safety-report-page .form-control {
            min-height: 46px;
        }

        .safety-report-page textarea.form-control {
            min-height: auto;
        }

        .safety-report-page .btn-primary {
            padding: .7rem 1.2rem;
            font-weight: 600;
        }

        #preflight {
            z-index: 1080;
            background: rgba(12, 25, 36, .72);
            backdrop-filter: blur(4px);
        }

        #preflight > div {
            border: 0 !important;
            border-radius: 1rem;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, .2) !important;
        }

        #preflight[hidden],
        #report-form[hidden],
        #question-supervisor[hidden],
        #question-ky[hidden],
        #preflight-warning[hidden],
        #preflight-ready[hidden] {
            display: none !important;
        }
    </style>

    <section class="section safety-report-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="card shadow-sm">
                        <div class="card-body p-4 p-lg-5">
                            <h1 class="h3 mb-2">Laporan Safety</h1>
                            <p class="text-muted mb-4">Laporkan kondisi atau tindakan yang berpotensi menimbulkan bahaya. Pastikan informasi dan foto yang dikirim sudah sesuai.</p>

                            @if ($errors->any())
                                <div class="alert alert-danger d-flex align-items-start" role="alert">
                                    <i class="bi bi-exclamation-circle-fill mr-2 mt-1" aria-hidden="true"></i>
                                    <div>
                                        <strong>Data belum dapat dikirim</strong>
                                        <div>Periksa kembali kolom yang ditandai, lalu kirim ulang laporan Anda.</div>
                                    </div>
                                </div>
                            @endif

                            @if (! $employee)
                                <form action="{{ route('safety-reports.verify-employee') }}" method="POST">
                                    @csrf
                                    <div class="alert alert-light border d-flex align-items-start mb-4" role="note">
                                        <i class="bi bi-shield-check text-primary mr-2 mt-1" aria-hidden="true"></i>
                                        <div>
                                            <strong>Verifikasi identitas karyawan</strong>
                                            <div class="text-muted">Masukkan NPK dan nama persis seperti yang terdaftar. Data hanya digunakan untuk memastikan pelapor merupakan karyawan aktif.</div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="npk" class="form-label">Nomor Pokok Karyawan (NPK)</label>
                                        <input id="npk" name="npk" value="{{ old('npk') }}" class="form-control @error('npk') is-invalid @enderror" required maxlength="50" autocomplete="off">
                                        @error('npk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="mb-4">
                                        <label for="name" class="form-label">Nama</label>
                                        <input id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required maxlength="255" autocomplete="name">
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">Verifikasi karyawan</button>
                                </form>
                            @else
                            <div id="preflight" class="d-grid" role="dialog" aria-modal="true" aria-labelledby="preflight-title" style="position:fixed;inset:0;place-items:center;padding:1rem;" @if (old('reported_to_supervisor') && old('ky_created')) hidden @endif>
                                <div class="p-4 p-md-5 bg-white" style="width:100%;max-width:640px;">
                                    <span class="badge badge-primary mb-3">Pemeriksaan awal</span>
                                    <h2 id="preflight-title" class="h5">Sebelum membuat laporan</h2>
                                    <p class="text-muted">Jawab kedua pertanyaan secara berurutan untuk melanjutkan.</p>
                                    <fieldset id="question-supervisor" class="mb-3">
                                        <legend class="form-label">Apakah Anda sudah melaporkan kepada atasan?</legend>
                                        @foreach (['sudah' => 'Sudah', 'belum' => 'Belum'] as $value => $label)
                                            <button type="button" class="btn btn-outline-primary mr-2 preflight-answer" data-question="supervisor" data-answer="{{ $value }}">{{ $label }}</button>
                                        @endforeach
                                    </fieldset>
                                    <fieldset id="question-ky" class="mb-3" hidden>
                                        <legend class="form-label">Apakah Anda sudah membuat Kiken Yochi (KY)?</legend>
                                        @foreach (['sudah' => 'Sudah', 'belum' => 'Belum'] as $value => $label)
                                            <button type="button" class="btn btn-outline-primary mr-2 preflight-answer" data-question="ky" data-answer="{{ $value }}">{{ $label }}</button>
                                        @endforeach
                                    </fieldset>
                                    <div id="preflight-warning" class="alert alert-warning mb-0" role="alert" hidden>
                                        <p id="preflight-warning-message" class="mb-3"></p>
                                        <button id="return-home" type="button" class="btn btn-primary">OK</button>
                                    </div>
                                    <div id="preflight-ready" hidden>
                                        <p class="mb-3">Terima kasih. Silakan lanjutkan pengisian laporan.</p>
                                        <button id="continue-report" type="button" class="btn btn-primary">Lanjutkan pengisian</button>
                                    </div>
                                </div>
                            </div>

                            <form id="report-form" action="{{ route('safety-reports.store') }}" method="POST" enctype="multipart/form-data" @if (! (old('reported_to_supervisor') && old('ky_created'))) hidden @endif>
                                @csrf
                                <input type="hidden" name="reported_to_supervisor" id="reported_to_supervisor" value="{{ old('reported_to_supervisor') }}">
                                <input type="hidden" name="ky_created" id="ky_created" value="{{ old('ky_created') }}">

                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <div class="alert alert-light border mb-0">
                                            <strong>Pelapor terverifikasi:</strong>
                                            {{ $employee['name'] }} (NPK {{ $employee['employee_number'] }})
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="section" class="form-label">Seksi</label>
                                        <input id="section" name="section" value="{{ old('section') }}" class="form-control @error('section') is-invalid @enderror" required maxlength="255">
                                        @error('section')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="location" class="form-label">Lokasi</label>
                                        <input id="location" name="location" value="{{ old('location') }}" class="form-control @error('location') is-invalid @enderror" required maxlength="255">
                                        @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="hazard_description" class="form-label">Deskripsi dugaan bahaya</label>
                                        <textarea id="hazard_description" name="hazard_description" rows="5" class="form-control @error('hazard_description') is-invalid @enderror" required minlength="10" maxlength="10000">{{ old('hazard_description') }}</textarea>
                                        @error('hazard_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12 mb-4">
                                        <label for="image" class="form-label">Foto kondisi bahaya</label>
                                        <input id="image" type="file" name="image" accept="image/jpeg,image/png,image/webp" class="form-control @error('image') is-invalid @enderror" required>
                                        <div class="form-text">Format JPG, PNG, atau WebP; maksimal 5 MB.</div>
                                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Kirim laporan</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($employee)
    <script>
        (() => {
            const supervisor = document.getElementById('reported_to_supervisor');
            const ky = document.getElementById('ky_created');
            const continueButton = document.getElementById('continue-report');
            const form = document.getElementById('report-form');
            const preflight = document.getElementById('preflight');
            const supervisorQuestion = document.getElementById('question-supervisor');
            const kyQuestion = document.getElementById('question-ky');
            const warning = document.getElementById('preflight-warning');
            const ready = document.getElementById('preflight-ready');
            const warningMessage = document.getElementById('preflight-warning-message');

            document.querySelectorAll('.preflight-answer').forEach((button) => {
                button.addEventListener('click', () => {
                    const { question, answer } = button.dataset;

                    if (answer === 'belum') {
                        supervisorQuestion.hidden = true;
                        kyQuestion.hidden = true;
                        ready.hidden = true;
                        warningMessage.textContent = question === 'supervisor'
                            ? 'Silakan lapor kepada atasan terlebih dahulu.'
                            : 'Silakan mengisi Kiken Yochi (KY) terlebih dahulu.';
                        warning.hidden = false;

                        return;
                    }

                    if (question === 'supervisor') {
                        supervisor.value = answer;
                        supervisorQuestion.hidden = true;
                        kyQuestion.hidden = false;

                        return;
                    }

                    ky.value = answer;
                    kyQuestion.hidden = true;
                    ready.hidden = false;
                });
            });

            document.getElementById('return-home').addEventListener('click', () => {
                window.location.assign(@json(url('/')));
            });

            continueButton.addEventListener('click', () => {
                preflight.hidden = true;
                form.hidden = false;
                form.querySelector('input[name="section"]').focus();
            });
        })();
    </script>
    @endif
@endsection
