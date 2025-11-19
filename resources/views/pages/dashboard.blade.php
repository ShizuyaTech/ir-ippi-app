@extends('layouts.app')

@section('title', 'General Dashboard')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <style>
        .chart-container {
            position: relative;
            height: 400px;
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
                @if (isset($isAdmin) && $isAdmin)
                    <div class="section-header-button">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateScoresModal">
                            <i class="fas fa-edit"></i> Update Scores
                        </button>
                    </div>
                @endif
            </div>
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @php
                // Helper function untuk progress color yang aman
                function getProgressColor($score)
                {
                    $score = floatval($score);
                    if ($score >= 80) {
                        return 'success';
                    }
                    if ($score >= 60) {
                        return 'info';
                    }
                    if ($score >= 40) {
                        return 'warning';
                    }
                    return 'danger';
                }

                // Helper function untuk mendapatkan score value yang aman
                function getScoreValue($scores, $field)
                {
                    return isset($scores->$field) ? $scores->$field : 0;
                }
            @endphp

            <!-- 4 Score Cards -->
            <div class="row">
                <!-- IR Partnership Score -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>IR Partnership</h4>
                            </div>
                            <div class="card-body">
                                {{ isset($scores->ir_partnership) ? $scores->ir_partnership : 0 }}
                            </div>
                        </div>
                        <div class="card-progress">
                            <div class="progress" data-height="5">
                                @php
                                    $irScore = isset($scores->ir_partnership) ? floatval($scores->ir_partnership) : 0;
                                    $irColor =
                                        $irScore >= 80
                                            ? 'success'
                                            : ($irScore >= 60
                                                ? 'info'
                                                : ($irScore >= 40
                                                    ? 'warning'
                                                    : 'danger'));
                                @endphp
                                <div class="progress-bar bg-{{ $irColor }}" data-width="{{ $irScore }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Conductive Working Climate Score -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Working Climate</h4>
                            </div>
                            <div class="card-body">
                                {{ isset($scores->conductive_working_climate) ? $scores->conductive_working_climate : 0 }}
                            </div>
                        </div>
                        <div class="card-progress">
                            <div class="progress" data-height="5">
                                @php
                                    $climateScore = isset($scores->conductive_working_climate)
                                        ? floatval($scores->conductive_working_climate)
                                        : 0;
                                    $climateColor =
                                        $climateScore >= 80
                                            ? 'success'
                                            : ($climateScore >= 60
                                                ? 'info'
                                                : ($climateScore >= 40
                                                    ? 'warning'
                                                    : 'danger'));
                                @endphp
                                <div class="progress-bar bg-{{ $climateColor }}" data-width="{{ $climateScore }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ESS Score -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>ESS</h4>
                            </div>
                            <div class="card-body">
                                {{ isset($scores->ess) ? $scores->ess : 0 }}
                            </div>
                        </div>
                        <div class="card-progress">
                            <div class="progress" data-height="5">
                                @php
                                    $essScore = isset($scores->ess) ? floatval($scores->ess) : 0;
                                    $essColor =
                                        $essScore >= 80
                                            ? 'success'
                                            : ($essScore >= 60
                                                ? 'info'
                                                : ($essScore >= 40
                                                    ? 'warning'
                                                    : 'danger'));
                                @endphp
                                <div class="progress-bar bg-{{ $essColor }}" data-width="{{ $essScore }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AIRSI Score -->
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>AIRSI</h4>
                            </div>
                            <div class="card-body">
                                {{ isset($scores->airsi) ? $scores->airsi : 0 }}
                            </div>
                        </div>
                        <div class="card-progress">
                            <div class="progress" data-height="5">
                                @php
                                    $airsiScore = isset($scores->airsi) ? floatval($scores->airsi) : 0;
                                    $airsiColor =
                                        $airsiScore >= 80
                                            ? 'success'
                                            : ($airsiScore >= 60
                                                ? 'info'
                                                : ($airsiScore >= 40
                                                    ? 'warning'
                                                    : 'danger'));
                                @endphp
                                <div class="progress-bar bg-{{ $airsiColor }}" data-width="{{ $airsiScore }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- History Scores (Admin Only) -->
            @if (isset($isAdmin) && $isAdmin)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>History Scores</h4>
                                <div class="card-header-action">
                                    <a href="{{ route_encrypted('dashboard-scores.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Scores Baru
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                {{-- $allScores sekarang selalu collection, tidak perlu cek null --}}
                                @if ($allScores->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>IR Partnership</th>
                                                    <th>Working Climate</th>
                                                    <th>ESS</th>
                                                    <th>AIRSI</th>
                                                    <th>Updated By</th>
                                                    <th>Updated At</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($allScores as $score)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $score->ir_partnership }}</td>
                                                        <td>{{ $score->conductive_working_climate }}</td>
                                                        <td>{{ $score->ess }}</td>
                                                        <td>{{ $score->airsi }}</td>
                                                        <td>{{ $score->updater->name }}</td>
                                                        <td>{{ $score->created_at->format('d M Y H:i') }}</td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <a href="{{ route_encrypted('dashboard-scores.edit', $score->id) }}"
                                                                    class="btn btn-sm btn-warning mr-1">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <form
                                                                    action="{{ route_encrypted('dashboard-scores.destroy', $score->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-danger confirm-delete">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Belum ada data scores</p>
                                        <a href="{{ route_encrypted('dashboard-scores.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Scores Pertama
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Charts Section -->
            <div class="row mt-4">
                <!-- IR Assessment Chart -->
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>IR Assessment Activity</h4>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height:400px;">
                                <canvas id="irAssessmentChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feedback Chart -->
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Feedback Activity</h4>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height:400px;">
                                <canvas id="feedbackChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule Activity Chart -->
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Schedule Activity</h4>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height:400px;">
                                <canvas id="scheduleChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- LKS Chart -->
                <div class="col-md-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>LKS Bipartit Activity</h4>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height:400px;">
                                <canvas id="lksChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chart-container" style="position: relative; height:400px;">
                    <canvas id="activityTrendChart"></canvas>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('style')
    <style>
        .card-progress {
            padding: 0 20px 15px;
        }

        .progress[data-height] {
            height: 5px;
        }
    </style>
@endpush

@push('scripts')
    <!-- Chart Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script> --}}
    <script src="{{ asset('js/scripts.js') }}"></script>

    <!-- Chart Data -->
    <script>
        // Initialize chart data dengan fallback
        window.chartData = {
            irAssessmentData: @json($irAssessmentData ?? []),
            feedbackData: @json($feedbackData ?? []),
            scheduleData: @json($scheduleData ?? []),
            lksData: @json($lksData ?? [])
        };

        console.log('=== DEBUG CHART DATA ===');
        console.log('IR Assessment Data:', window.chartData.irAssessmentData);
        console.log('Feedback Data:', window.chartData.feedbackData);
        console.log('Schedule Data:', window.chartData.scheduleData);
        console.log('LKS Data:', window.chartData.lksData);

        // Debug detail untuk IR Assessment dan Feedback
        console.log('=== DETAILED DEBUG ===');
        for (let i = 1; i <= 12; i++) {
            console.log(`Month ${i} - IR:`, window.chartData.irAssessmentData[i]);
            console.log(`Month ${i} - Feedback:`, window.chartData.feedbackData[i]);
        }
        console.log('=== END DEBUG ===');
    </script>

    <!-- Dashboard Charts Script - SIMPLIFIED -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Dashboard charts initializing...');

            // Pastikan Chart.js sudah dimuat
            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded!');
                return;
            }

            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const data = window.chartData;

            // Fungsi sederhana untuk create chart
            function createSimpleChart(canvasId, datasets) {
                const canvas = document.getElementById(canvasId);
                if (!canvas) {
                    console.error('Canvas not found:', canvasId);
                    return null;
                }

                console.log(`Creating simple chart for ${canvasId}`, datasets);

                const ctx = canvas.getContext('2d');

                try {
                    const chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: months,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                    console.log(`Chart ${canvasId} created successfully`);
                    return chart;
                } catch (error) {
                    console.error(`Error creating chart ${canvasId}:`, error);
                    return null;
                }
            }

            // IR Assessment Chart dengan data sederhana
            const irData = [];
            for (let i = 1; i <= 12; i++) {
                irData.push(data.irAssessmentData[i] || 0);
            }

            const irChart = createSimpleChart('irAssessmentChart', [{
                label: 'Total Assessments',
                data: irData,
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]);

            // Feedback Chart dengan data sederhana
            const feedbackSubmitted = [];
            const feedbackUnderReview = [];
            const feedbackResponded = [];
            const feedbackClosed = [];

            for (let i = 1; i <= 12; i++) {
                const monthData = data.feedbackData[i] || {};
                feedbackSubmitted.push(monthData.submitted || 0);
                feedbackUnderReview.push(monthData.under_review || 0);
                feedbackResponded.push(monthData.responded || 0);
                feedbackClosed.push(monthData.closed || 0);
            }

            const feedbackChart = createSimpleChart('feedbackChart', [{
                    label: 'Terkirim',
                    data: feedbackSubmitted,
                    backgroundColor: 'rgba(0, 123, 255, 0.7)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Dalam Review',
                    data: feedbackUnderReview,
                    backgroundColor: 'rgba(255, 193, 7, 0.7)',
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Ditanggapi',
                    data: feedbackResponded,
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Ditutup',
                    data: feedbackClosed,
                    backgroundColor: 'rgba(220, 53, 69, 0.7)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1
                }
            ]);

            // Schedule Chart
            const scheduleData = [];
            for (let i = 1; i <= 12; i++) {
                scheduleData.push(data.scheduleData[i] || 0);
            }

            const scheduleChart = createSimpleChart('scheduleChart', [{
                label: 'Total Schedules',
                data: scheduleData,
                backgroundColor: 'rgba(0, 123, 255, 0.7)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 1
            }]);

            // LKS Chart
            const lksReview = [];
            const lksProgress = [];
            const lksDone = [];

            for (let i = 1; i <= 12; i++) {
                const monthData = data.lksData[i] || {};
                lksReview.push(monthData.review || 0);
                lksProgress.push(monthData.on_progress || 0);
                lksDone.push(monthData.done || 0);
            }

            const lksChart = createSimpleChart('lksChart', [{
                    label: 'Review',
                    data: lksReview,
                    backgroundColor: 'rgba(255, 193, 7, 0.7)',
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 1
                },
                {
                    label: 'On Progress',
                    data: lksProgress,
                    backgroundColor: 'rgba(0, 123, 255, 0.7)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Done',
                    data: lksDone,
                    backgroundColor: 'rgba(40, 167, 69, 0.7)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                }
            ]);

        });
    </script>

    <!-- COMMENT SEMENTARA scripts.js -->
    {{-- <script src="{{ asset('js/scripts.js') }}"></script> --}}
@endpush
