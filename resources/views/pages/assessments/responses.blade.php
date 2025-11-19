@extends('layouts.app')

@section('title', 'Responses - ' . $assessment->title)

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Responses Assessment</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route_encrypted('assessments.index') }}">Assessments</a></div>
                    <div class="breadcrumb-item"><a
                            href="{{ route_encrypted('assessments.show', $assessment->id) }}">{{ $assessment->title }}</a></div>
                    <div class="breadcrumb-item">Responses</div>
                </div>
            </div>

            <div class="section-body">
                <!-- Statistics -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4>Statistik Responses</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <h3>{{ $respondents->count() }}</h3>
                                        <p class="text-muted">Total Responden</p>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <h3>{{ $responses->total() }}</h3>
                                        <p class="text-muted">Total Responses</p>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <h3>{{ $assessment->questions->count() }}</h3>
                                        <p class="text-muted">Total Pertanyaan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Distribusi Rating</h4>
                            </div>
                            <div class="card-body">
                                @php
                                    $ratingLabels = [
                                        'sangat_buruk' => 'Sangat Buruk',
                                        'buruk' => 'Buruk',
                                        'cukup' => 'Cukup',
                                        'baik' => 'Baik',
                                        'sangat_baik' => 'Sangat Baik',
                                    ];
                                @endphp
                                @foreach ($ratingLabels as $key => $label)
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between">
                                            <span>{{ $label }}</span>
                                            <span class="font-weight-bold">{{ $ratingStats[$key] ?? 0 }}</span>
                                        </div>
                                        @php
                                            $total = array_sum($ratingStats->toArray());
                                            $percentage = $total > 0 ? (($ratingStats[$key] ?? 0) / $total) * 100 : 0;
                                        @endphp
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Responses Table -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Detail Responses</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>User Code</th>
                                                <th>Pertanyaan</th>
                                                <th>Rating</th>
                                                <th>User</th>
                                                <th>Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($responses as $response)
                                                <tr>
                                                    <td>{{ $loop->iteration + ($responses->currentPage() - 1) * $responses->perPage() }}
                                                    </td>
                                                    <td><code>{{ $response->user_code }}</code></td>
                                                    <td>
                                                        <div style="max-width: 300px;">
                                                            {{ Str::limit($response->question->question_text, 80) }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $ratingColors = [
                                                                'sangat_buruk' => 'danger',
                                                                'buruk' => 'warning',
                                                                'cukup' => 'secondary',
                                                                'baik' => 'info',
                                                                'sangat_baik' => 'success',
                                                            ];
                                                        @endphp
                                                        <span class="badge badge-{{ $ratingColors[$response->rating] }}">
                                                            {{ $ratingLabels[$response->rating] }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if ($response->user)
                                                            {{ $response->user->name }}
                                                            <br><small
                                                                class="text-muted">{{ $response->user->email }}</small>
                                                        @else
                                                            Anonymous
                                                        @endif
                                                    </td>
                                                    <td>{{ $response->created_at->format('d/m/Y H:i') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $responses->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
