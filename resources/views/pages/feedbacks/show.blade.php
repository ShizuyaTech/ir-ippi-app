@extends('layouts.app')

@section('title', 'Detail Feedback')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Feedback</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route_encrypted('feedbacks.index') }}">Feedbacks</a></div>
                    <div class="breadcrumb-item">{{ $feedback->title }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Detail Feedback</h4>
                                <div class="card-header-action">
                                    <a href="{{ route_encrypted('feedbacks.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Judul Feedback</label>
                                            <input type="text" class="form-control" value="{{ $feedback->title }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kategori</label>
                                            <input type="text" class="form-control"
                                                value="{{ $feedback->category_name }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Prioritas</label>
                                            @php
                                                $priorityClass = [
                                                    'low' => 'secondary',
                                                    'medium' => 'info',
                                                    'high' => 'warning',
                                                    'urgent' => 'danger',
                                                ][$feedback->priority];
                                            @endphp
                                            <span class="badge badge-{{ $priorityClass }} p-2">
                                                {{ $feedback->priority_name }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status</label>
                                            @php
                                                $statusClass = [
                                                    'draft' => 'secondary',
                                                    'submitted' => 'primary',
                                                    'under_review' => 'warning',
                                                    'responded' => 'success',
                                                    'closed' => 'dark',
                                                ][$feedback->status];
                                            @endphp
                                            <span class="badge badge-{{ $statusClass }} p-2">
                                                {{ $feedback->status_name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Isi Feedback</label>
                                    <div class="card">
                                        <div class="card-body bg-light">
                                            {!! nl2br(e($feedback->content)) !!}
                                        </div>
                                    </div>
                                </div>

                                @if ($feedback->management_response)
                                    <div class="form-group">
                                        <label>Tanggapan Manajemen</label>
                                        <div class="card">
                                            <div class="card-body bg-light">
                                                {!! nl2br(e($feedback->management_response)) !!}
                                            </div>
                                        </div>
                                        @if ($feedback->responder)
                                            <small class="text-muted">
                                                Ditanggapi oleh: {{ $feedback->responder->name }}
                                                pada
                                                {{ $feedback->responded_at ? $feedback->responded_at->format('d F Y H:i') : 'N/A' }}
                                            </small>
                                        @endif
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Dibuat Oleh</label>
                                            <input type="text" class="form-control"
                                                value="{{ $feedback->author->name ?? 'N/A' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tanggal Dibuat</label>
                                            <input type="text" class="form-control"
                                                value="{{ $feedback->created_at ? $feedback->created_at->format('d F Y H:i') : 'N/A' }}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>

                                @if ($feedback->submitted_at)
                                    <div class="form-group">
                                        <label>Tanggal Dikirim ke Manajemen</label>
                                        <input type="text" class="form-control"
                                            value="{{ $feedback->submitted_at ? $feedback->submitted_at->format('d F Y H:i') : 'N/A' }}"
                                            readonly>
                                    </div>
                                @endif

                                @if ($feedback->responded_at)
                                    <div class="form-group">
                                        <label>Tanggal Ditanggapi</label>
                                        <input type="text" class="form-control"
                                            value="{{ $feedback->responded_at ? $feedback->responded_at->format('d F Y H:i') : 'Belum ditanggapi' }}"
                                            readonly>
                                    </div>
                                @endif

                                <!-- Form Tanggapan untuk Admin -->
                                @if ($isAdmin && $feedback->status !== 'draft' && $feedback->status !== 'closed')
                                    <div class="card mt-4">
                                        <div class="card-header">
                                            <h5>Berikan Tanggapan</h5>
                                        </div>
                                        <div class="card-body">
                                            <form action="{{ route_encrypted('feedbacks.respond', $feedback->id) }}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <label>Tanggapan Manajemen</label>
                                                    <textarea name="management_response" rows="5" class="form-control" placeholder="Tuliskan tanggapan manajemen...">{{ old('management_response', $feedback->management_response) }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select name="status" class="form-control">
                                                        <option value="under_review"
                                                            {{ $feedback->status == 'under_review' ? 'selected' : '' }}>
                                                            Dalam Review
                                                        </option>
                                                        <option value="responded"
                                                            {{ $feedback->status == 'responded' ? 'selected' : '' }}>
                                                            Ditanggapi
                                                        </option>
                                                        <option value="closed"
                                                            {{ $feedback->status == 'closed' ? 'selected' : '' }}>
                                                            Ditutup
                                                        </option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Simpan Tanggapan</button>
                                            </form>
                                        </div>
                                    </div>
                                @endif

                                <!-- Action untuk Pengurus Serikat -->
                                @if (!$isAdmin && $feedback->status === 'draft' && $feedback->created_by == Auth::id())
                                    <div class="card mt-4">
                                        <div class="card-body text-center">
                                            <form action="{{ route_encrypted('feedbacks.submit', $feedback->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success"
                                                    onclick="return confirm('Kirim feedback ke manajemen?')">
                                                    <i class="fas fa-paper-plane"></i> Kirim ke Manajemen
                                                </button>
                                            </form>
                                            <a href="{{ route_encrypted('feedbacks.edit', $feedback->id) }}"
                                                class="btn btn-warning ml-2">
                                                <i class="fas fa-edit"></i> Edit Feedback
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
