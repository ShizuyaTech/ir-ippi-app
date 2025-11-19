@extends('layouts.app')

@section('title', $program->title)

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $program->title }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route_encrypted('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item">{{ $program->type_name }}</div>
                <div class="breadcrumb-item">{{ $program->title }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{ $program->image_url }}" alt="{{ $program->title }}"
                                        class="img-fluid rounded mb-3">
                                </div>
                                <div class="col-md-8">
                                    <h2>{{ $program->title }}</h2>
                                    <div class="text-muted mb-3">
                                        <span class="badge badge-primary">{{ $program->type_name }}</span>
                                        <span class="badge badge-info">{{ $program->category_name }}</span>
                                        <span class="badge badge-secondary">{{ $program->year }}</span>
                                    </div>
                                    <div class="mb-4">
                                        {!! $program->description !!}
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted">Published:
                                                {{ $program->formatted_published_at }}</small>
                                            <br>
                                            <small class="text-muted">Created by:
                                                {{ $program->author->name ?? 'Unknown' }}</small>
                                        </div>
                                        @auth
                                            <div>
                                                <a href="{{ route_encrypted('pages.program-achievements.edit', $program->id) }}"
                                                    class="btn btn-warning">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($relatedPrograms->isNotEmpty())
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Related {{ Str::plural($program->type_name) }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($relatedPrograms as $related)
                                        <div class="col-md-4 mb-4">
                                            <div class="card h-100">
                                                <img src="{{ $related->image_url }}" class="card-img-top"
                                                    alt="{{ $related->title }}" style="height: 200px; object-fit: cover;">
                                                <div class="card-body">
                                                    <h5 class="card-title">{{ $related->title }}</h5>
                                                    <p class="card-text">{{ $related->short_description }}</p>
                                                    <a href="{{ route_encrypted('programs.show', $related->id) }}"
                                                        class="btn btn-primary btn-sm">Read More</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
