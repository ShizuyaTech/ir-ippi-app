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
    <section class="section" style="min-height:100vh;padding:140px 0 80px;background:#f4f7fa;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm" style="border-radius:1rem;overflow:hidden;">
                        <div class="card-body p-4 p-lg-5">
                            <div class="d-flex align-items-start mb-4">
                                <div class="d-flex align-items-center justify-content-center rounded-circle bg-success text-white mr-3" style="width:3rem;height:3rem;flex:0 0 3rem;">
                                    <i class="bi bi-check-lg h4 mb-0" aria-hidden="true"></i>
                                </div>
                                <div>
                                    <h1 class="h3 mb-2">Laporan berhasil dikirim</h1>
                                    <p class="text-muted mb-0">Laporan Anda sudah tercatat dan menunggu verifikasi tim PIC Safety &amp; Healthy.</p>
                                </div>
                            </div>

                            <div class="alert alert-primary mb-4">
                                <div class="small text-uppercase font-weight-bold mb-1">Nomor laporan</div>
                                <div class="h4 mb-0" data-testid="submitted-report-number">{{ $reportNumber }}</div>
                                <div class="small mt-2">Simpan nomor ini sebagai referensi laporan Anda.</div>
                            </div>

                            <h2 class="h5 mb-3">Ringkasan laporan</h2>
                            <dl class="row mb-4">
                                <dt class="col-sm-4">Nama / NPK</dt>
                                <dd class="col-sm-8">{{ $safetyReport->reporter_name }} / {{ $safetyReport->employee_number }}</dd>
                                <dt class="col-sm-4">Tanggal laporan</dt>
                                <dd class="col-sm-8">{{ $safetyReport->created_at->format('d-m-Y H:i') }}</dd>
                                <dt class="col-sm-4">Seksi</dt>
                                <dd class="col-sm-8">{{ $safetyReport->section }}</dd>
                                <dt class="col-sm-4">Lokasi</dt>
                                <dd class="col-sm-8">{{ $safetyReport->location }}</dd>
                                <dt class="col-sm-4">Deskripsi bahaya</dt>
                                <dd class="col-sm-8">{!! nl2br(e($safetyReport->hazard_description)) !!}</dd>
                                <dt class="col-sm-4">Status awal</dt>
                                <dd class="col-sm-8"><span class="badge badge-warning">Menunggu verifikasi PIC</span></dd>
                            </dl>

                            <a href="{{ url('/') }}" class="btn btn-outline-primary">Kembali ke homepage</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
