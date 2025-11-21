@extends('layouts-web.app')

@section('content')
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

        <img src="{{ asset('img/hero-bg.jpg') }}" alt="" data-aos="fade-in">

        <div class="container d-flex flex-column align-items-center">
            <h2 data-aos="fade-up" data-aos-delay="100">IR ASSESSMENT</h2>
            <p data-aos="fade-up" data-aos-delay="200">Strong Forever Grow Together</p>
            <div class="d-flex mt-4" data-aos="fade-up" data-aos-delay="300">
                <a href="#services" class="btn-get-started">Get Started</a>
                <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8"
                    class="glightbox btn-watch-video d-flex align-items-center"><i class="bi bi-play-circle"></i><span>Watch
                        Video</span></a>
            </div>
        </div>

    </section><!-- /Hero Section -->

    <!-- Services Section -->
    <section id="assessment" class="services section">
        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <div class="mb-5">
                <p>All Assessment<br></p>
            </div>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row gy-5 mb-5 mt-2">
                @if (isset($assessments) && $assessments->count() > 0)
                    @foreach ($assessments as $index => $assessment)
                        <div class="col-xl-4 col-md-6 mb-5" data-aos="zoom-in" data-aos-delay="{{ ($index + 1) * 100 }}">
                            <div class="service-item">
                                <div class="details position-relative">
                                    <div class="icon">
                                        <i class="bi bi-clipboard-check"></i>
                                    </div>
                                    <a href="{{ route('assessment.validate.form', $assessment->id) }}"
                                        class="stretched-link">
                                        <h3>{{ $assessment->title }}</h3>
                                    </a>
                                    <p>
                                        @if ($assessment->description)
                                            {{ Str::limit($assessment->description, 120) }}
                                        @else
                                            Assessment terdiri dari {{ $assessment->questions_count }} pertanyaan untuk
                                            mengukur kualitas hubungan industrial.
                                        @endif
                                    </p>
                                    <div class="assessment-meta">
                                        <small class="text-muted">
                                            <i class="bi bi-question-circle me-1"></i>
                                            {{ $assessment->questions_count }} Pertanyaan
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Service Item -->
                    @endforeach
                @else
                    <!-- Fallback jika tidak ada assessment aktif -->
                    <div class="col-12 text-center py-5">
                        <div class="empty-state" data-aos="fade-up">
                            <i class="bi bi-clipboard-x display-1 text-muted"></i>
                            <h3 class="mt-3">Tidak Ada Assessment Aktif</h3>
                            <p class="text-muted">Saat ini tidak ada assessment yang tersedia. Silakan check kembali nanti.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

        </div>

    </section><!-- /Services Section -->

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('js/main.js') }}"></script>
@endsection

@push('styles')
    <style>
        .assessment-meta {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }

        .service-item .icon i {
            font-size: 1.5rem;
        }

        .empty-state {
            padding: 3rem 1rem;
        }

        .service-item {
            height: 100%;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .service-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush
