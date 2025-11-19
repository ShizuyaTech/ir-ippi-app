<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Otomotif Terkini</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .page-header {
            background: linear-gradient(135deg, #2b4c7e, #1a365d);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
            position: relative;
        }

        .back-home {
            display: inline-block;
            padding: 8px 20px;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .back-home:hover {
            background-color: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateX(-5px);
        }

        .back-home i {
            margin-right: 5px;
        }
        }

        .news-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            margin-bottom: 30px;
            overflow: hidden;
            height: 100%;
        }

        .news-card:hover {
            transform: translateY(-5px);
        }

        .news-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .news-content {
            padding: 20px;
            display: flex;
            flex-direction: column;
            height: calc(100% - 200px);
        }

        .news-title {
            color: #2b4c7e;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 10px;
            text-decoration: none;
        }

        .news-title:hover {
            color: #1a365d;
        }

        .news-meta {
            font-size: 0.875rem;
            color: #666;
            margin-bottom: 15px;
        }

        .news-summary {
            color: #444;
            font-size: 0.95rem;
            line-height: 1.6;
            flex-grow: 1;
        }

        .read-more {
            display: inline-block;
            padding: 8px 20px;
            background-color: #2b4c7e;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            align-self: flex-start;
            margin-top: 15px;
        }

        .read-more:hover {
            background-color: #1a365d;
            color: white;
        }

        .pagination {
            margin-top: 40px;
        }

        .pagination .page-link {
            color: #2b4c7e;
        }

        .pagination .active .page-link {
            background-color: #2b4c7e;
            border-color: #2b4c7e;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="page-header">
        <div class="container position-relative">
            <a href="{{ url('/') }}" class="back-home">
                <i class="bi bi-house-door"></i> Kembali ke Home
            </a>
            <h1 class="text-center">IR PT.IPPI</h1>
            <h3 class="text-center">Berita Otomotif Terkini</h3>
            <p class="text-center mb-0">Informasi terupdate seputar dunia otomotif</p>
        </div>
    </header>

    <!-- News Section -->
    <div class="container">
        <div class="row g-4">
            @foreach ($news as $item)
                <div class="col-lg-4 col-md-6">
                    <div class="news-card">
                        @if ($item->image_url)
                            <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="news-image">
                        @endif

                        <div class="news-content">
                            <a href="{{ route_encrypted('news.show', $item->id) }}" class="news-title">
                                {{ $item->title }}
                            </a>
                            <div class="news-meta">
                                <span class="me-3">
                                    <i class="bi bi-person"></i> {{ $item->author }}
                                </span>
                                <span>
                                    <i class="bi bi-calendar"></i>
                                    {{ $item->published_at->format('d M Y') }}
                                </span>
                            </div>
                            <div class="news-summary">
                                {{ Str::limit($item->summary, 150) }}
                            </div>
                            <a href="{{ route_encrypted('news.show', $item->id) }}" class="read-more">
                                Baca Selengkapnya
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $news->links() }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

@push('styles')
    <style>
        .section-header {
            background-color: #f8f9fa;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 10px;
        }

        .section-header h2 {
            color: #333;
            font-weight: bold;
        }

        .section-header p {
            color: #666;
            margin-bottom: 0;
        }

        .news .news-item {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            padding: 30px;
            height: 100%;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
        }

        .news .news-item .post-img {
            margin: -30px -30px 15px -30px;
            overflow: hidden;
        }

        .news .news-item .post-img img {
            width: 100%;
            max-height: 250px;
            object-fit: cover;
            transition: 0.3s;
        }

        .news .news-item .title {
            font-size: 20px;
            font-weight: 600;
            padding: 0;
            margin: 0 0 15px 0;
        }

        .news .news-item .title a {
            color: var(--color-default);
            transition: 0.3s;
        }

        .news .news-item .content p {
            margin: 0 0 15px 0;
            font-size: 15px;
            color: #555555;
        }

        .news .news-item .read-more a {
            color: var(--color-primary);
            transition: 0.3s;
            font-weight: 600;
            font-size: 16px;
        }

        .news .news-item .read-more a:hover {
            color: rgba(var(--color-primary-rgb), 0.8);
        }

        .news .news-item:hover .post-img img {
            transform: scale(1.1);
        }

        .news .news-item:hover .title a {
            color: var(--color-primary);
        }

        .news .post-meta {
            font-size: 14px;
            color: #6c757d;
        }

        .news .post-meta .post-author {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .news .post-meta .post-date {
            margin-bottom: 0;
        }
    </style>
@endpush
