<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $news->title }} - Berita Otomotif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .page-header {
            background: linear-gradient(135deg, #2b4c7e, #1a365d);
            color: white;
            padding: 40px 0;
            margin-bottom: 40px;
        }

        .news-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 40px;
        }

        .news-image {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .news-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2b4c7e;
            margin-bottom: 20px;
        }

        .news-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            color: #666;
            font-size: 0.9rem;
        }

        .news-content {
            line-height: 1.8;
            font-size: 1.1rem;
            color: #444;
        }

        .news-source {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 0.9rem;
            color: #666;
        }

        .related-news {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .related-news h3 {
            color: #2b4c7e;
            margin-bottom: 25px;
            font-size: 1.5rem;
        }

        .related-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            height: 100%;
            transition: transform 0.3s ease;
        }

        .related-card:hover {
            transform: translateY(-5px);
        }

        .related-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .related-content {
            padding: 15px;
        }

        .related-title {
            color: #2b4c7e;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
        }

        .related-title:hover {
            color: #1a365d;
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2b4c7e;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #1a365d;
            color: white;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="page-header">
        <div class="container">
            <div class="d-flex gap-3">
                <a href="{{ url('/') }}" class="back-button">
                    <i class="bi bi-house-door"></i> Kembali ke Home
                </a>
                <a href="{{ route_encrypted('news.index') }}" class="back-button">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Berita
                </a>
            </div>
        </div>
    </header>

    <div class="container" data-aos="fade-up">

        <div class="news-container">
            @if ($news->image_url)
                <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="news-image">
            @endif

            <h1 class="news-title">{{ $news->title }}</h1>

            <div class="news-meta">
                <span>
                    <i class="bi bi-person"></i> {{ $news->author }}
                </span>
                <span>
                    <i class="bi bi-calendar"></i>
                    {{ $news->published_at->format('d M Y') }}
                </span>
                <span>
                    <i class="bi bi-folder2"></i>
                    {{ \App\Models\News::CATEGORIES[$news->category] ?? 'Otomotif' }}
                </span>
            </div>

            <div class="news-content">
                {!! nl2br(e($news->content)) !!}
            </div>

            <div class="news-source">
                <i class="bi bi-link"></i>
                <a href="{{ $news->source_url }}" target="_blank" class="text-decoration-none">
                    Sumber: {{ $news->source }}
                </a>
            </div>
        </div>

        @if ($relatedNews->count() > 0)
            <div class="related-news">
                <h3>Berita Terkait</h3>
                <div class="row g-4">
                    @foreach ($relatedNews as $related)
                        <div class="col-lg-3 col-md-6">
                            <div class="related-card">
                                @if ($related->image_url)
                                    <img src="{{ $related->image_url }}" alt="{{ $related->title }}"
                                        class="related-image">
                                @endif
                                <div class="related-content">
                                    <a href="{{ route_encrypted('news.show', $related->id) }}" class="related-title">
                                        {{ $related->title }}
                                    </a>
                                    <div class="small text-muted">
                                        <i class="bi bi-calendar"></i>
                                        {{ $related->published_at->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

@push('styles')
    <style>
        /* ... style sebelumnya ... */

        .related-posts .related-post {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            padding: 20px;
            height: 100%;
            border-radius: 10px;
        }

        .related-posts .related-post .post-img {
            margin: -20px -20px 15px -20px;
            overflow: hidden;
        }

        .related-posts .related-post .post-img img {
            width: 100%;
            max-height: 150px;
            object-fit: cover;
            transition: 0.3s;
        }

        .related-posts .related-post:hover .post-img img {
            transform: scale(1.1);
        }

        .related-posts .related-post .title {
            font-size: 16px;
            font-weight: 600;
            padding: 0;
            margin: 0 0 15px 0;
        }

        .related-posts .related-post .title a {
            color: var(--color-default);
            transition: 0.3s;
        }

        .related-posts .related-post:hover .title a {
            color: var(--color-primary);
        }

        .related-posts .post-meta {
            font-size: 12px;
            color: #6c757d;
        }
    </style>
@endpush

@push('styles')
    <style>
        .news .news-details {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border-radius: 10px;
        }

        .news .news-details .post-img {
            margin: -30px -30px 20px -30px;
            overflow: hidden;
        }

        .news .news-details .post-img img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
        }

        .news .news-details .title {
            font-size: 28px;
            font-weight: 700;
            padding: 0;
            margin: 20px 0;
            color: var(--color-default);
        }

        .news .news-details .content {
            margin-top: 20px;
        }

        .news .news-details .content p {
            font-size: 16px;
            margin-bottom: 15px;
        }

        .news .news-details .meta-top {
            margin-bottom: 20px;
            color: #6c757d;
        }

        .news .news-details .meta-top ul {
            display: flex;
            flex-wrap: wrap;
            list-style: none;
            align-items: center;
            padding: 0;
            margin: 0;
        }

        .news .news-details .meta-top ul li+li {
            padding-left: 20px;
        }

        .news .news-details .meta-top i {
            font-size: 16px;
            margin-right: 8px;
            line-height: 0;
            color: var(--color-primary);
        }

        .news .news-details .meta-top a {
            color: #6c757d;
            font-size: 14px;
            display: inline-block;
            line-height: 1;
        }

        .news .news-details .meta-bottom {
            padding-top: 10px;
            border-top: 1px solid #e6e6e6;
        }

        .news .news-details .meta-bottom i {
            color: var(--color-primary);
            display: inline;
        }

        .news .news-details .meta-bottom a {
            color: #555555;
            transition: 0.3s;
        }

        .news .news-details .meta-bottom a:hover {
            color: var(--color-primary);
        }

        .news .news-details .meta-bottom .cats {
            list-style: none;
            display: inline;
            padding: 0 20px 0 0;
            font-size: 14px;
        }

        .news .news-details .meta-bottom .cats li {
            display: inline-block;
        }
    </style>
@endpush
