@extends('layouts.app')

@section('title', 'Detail Task LKS Bipartit')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Task LKS Bipartit</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('lks-bipartit.index') }}">LKS Bipartit</a></div>
                    <div class="breadcrumb-item">Detail Task</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ $lksBipartit->title }}</h4>
                                <div class="card-header-action">
                                    @if (isset($isAdmin) && $isAdmin)
                                        <a href="{{ route('lks-bipartit.edit', $lksBipartit->id) }}"
                                            class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    @endif
                                    <a href="{{ route('lks-bipartit.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <!-- Task Information -->
                                        <div class="form-group">
                                            <label class="font-weight-bold">Deskripsi Task</label>
                                            <div class="border rounded p-3 bg-light">
                                                @if ($lksBipartit->description)
                                                    {{ $lksBipartit->description }}
                                                @else
                                                    <span class="text-muted">Tidak ada deskripsi</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Notes -->
                                        @if ($lksBipartit->notes)
                                            <div class="form-group">
                                                <label class="font-weight-bold">Catatan</label>
                                                <div class="border rounded p-3 bg-light">
                                                    {{ $lksBipartit->notes }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <!-- Task Metadata -->
                                        <div class="card">
                                            <div class="card-header">
                                                <h6>Informasi Task</h6>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <td class="text-muted">Status</td>
                                                        <td>
                                                            @php
                                                                $statusColors = [
                                                                    'review' => 'primary',
                                                                    'on_progress' => 'warning',
                                                                    'done' => 'success',
                                                                ];
                                                            @endphp
                                                            <span
                                                                class="badge badge-{{ $statusColors[$lksBipartit->status] }}">
                                                                {{ $lksBipartit->status_name }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Prioritas</td>
                                                        <td>
                                                            @php
                                                                $priorityColors = [
                                                                    1 => 'secondary',
                                                                    2 => 'info',
                                                                    3 => 'warning',
                                                                    4 => 'danger',
                                                                ];
                                                            @endphp
                                                            <span
                                                                class="badge badge-{{ $priorityColors[$lksBipartit->priority] }}">
                                                                {{ $lksBipartit->priority_name }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Due Date</td>
                                                        <td>
                                                            @if ($lksBipartit->due_date)
                                                                <span
                                                                    class="{{ $lksBipartit->isOverdue() ? 'text-danger font-weight-bold' : '' }}">
                                                                    {{ $lksBipartit->formatted_due_date }}
                                                                    @if ($lksBipartit->isOverdue())
                                                                        <br><small class="text-danger">(Overdue)</small>
                                                                    @endif
                                                                </span>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Assignee</td>
                                                        <td>
                                                            @if ($lksBipartit->assignee)
                                                                {{ $lksBipartit->assignee->name }}
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Dibuat Oleh</td>
                                                        <td>{{ $lksBipartit->creator->name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Dibuat Pada</td>
                                                        <td>{{ $lksBipartit->created_at->format('d M Y H:i') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Terakhir Diupdate</td>
                                                        <td>{{ $lksBipartit->updated_at->format('d M Y H:i') }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
