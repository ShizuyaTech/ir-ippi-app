@extends('layouts-web.app')

@section('content')
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

        <img src="{{ asset('img/hero-bg.jpg') }}" alt="" data-aos="fade-in">

        <div class="container d-flex flex-column align-items-center">
            <h2 data-aos="fade-up" data-aos-delay="100">INDUSTRIAL RELATIONSHIP PT. IPPI</h2>
            {{-- <p data-aos="fade-up" data-aos-delay="200">We are team of talented designers making websites with
                Bootstrap</p> --}}
            <div class="d-flex mt-4" data-aos="fade-up" data-aos-delay="300">
                <a href="#about" class="btn-get-started">Get Started</a>
                <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8"
                    class="glightbox btn-watch-video d-flex align-items-center"><i class="bi bi-play-circle"></i><span>Watch
                        Video</span></a>
            </div>
        </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

        <div class="container">

            <div class="row gy-4">
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <h3>Profile IR PT. IPPI</h3>
                    <img src="{{ asset('img/about.jpg') }}" class="img-fluid rounded-4 mb-4" alt="">
                    <p>Ut fugiat ut sunt quia veniam. Voluptate perferendis perspiciatis quod nisi et. Placeat
                        debitis quia recusandae odit et consequatur voluptatem. Dignissimos pariatur consectetur
                        fugiat voluptas ea.</p>
                    <p>Temporibus nihil enim deserunt sed ea. Provident sit expedita aut cupiditate nihil vitae quo
                        officia vel. Blanditiis eligendi possimus et in cum. Quidem eos ut sint rem veniam qui. Ut
                        ut repellendus nobis tempore doloribus debitis explicabo similique sit. Accusantium sed ut
                        omnis beatae neque deleniti repellendus.</p>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
                    <div class="content ps-0 ps-lg-5">
                        <p class="fst-italic">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                            incididunt ut labore et dolore
                            magna aliqua.
                        </p>
                        <ul>
                            <li><i class="bi bi-check-circle-fill"></i> <span>Ullamco laboris nisi ut aliquip ex ea
                                    commodo consequat.</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>Duis aute irure dolor in
                                    reprehenderit in voluptate velit.</span></li>
                            <li><i class="bi bi-check-circle-fill"></i> <span>Ullamco laboris nisi ut aliquip ex ea
                                    commodo consequat. Duis aute irure dolor in reprehenderit in voluptate trideta
                                    storacalaperda mastiro dolore eu fugiat nulla pariatur.</span></li>
                        </ul>
                        <p>
                            Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
                            reprehenderit in voluptate
                            velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                            proident
                        </p>

                        <div class="position-relative mt-4">
                            <img src="{{ asset('img/about-2.jpg') }}" class="img-fluid rounded-4" alt="">
                            <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox pulsating-play-btn"></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section><!-- /About Section -->

    <!-- Stats Section -->
    {{-- <section id="stats" class="stats section light-background">

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row gy-4">

                <div class="col-lg-3 col-md-6">
                    <div class="stats-item d-flex align-items-center w-100 h-100">
                        <i class="bi bi-emoji-smile color-blue flex-shrink-0"></i>
                        <div>
                            <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="1"
                                class="purecounter"></span>
                            <p>Happy Clients</p>
                        </div>
                    </div>
                </div><!-- End Stats Item -->

                <div class="col-lg-3 col-md-6">
                    <div class="stats-item d-flex align-items-center w-100 h-100">
                        <i class="bi bi-journal-richtext color-orange flex-shrink-0"></i>
                        <div>
                            <span data-purecounter-start="0" data-purecounter-end="521" data-purecounter-duration="1"
                                class="purecounter"></span>
                            <p>Projects</p>
                        </div>
                    </div>
                </div><!-- End Stats Item -->

                <div class="col-lg-3 col-md-6">
                    <div class="stats-item d-flex align-items-center w-100 h-100">
                        <i class="bi bi-headset color-green flex-shrink-0"></i>
                        <div>
                            <span data-purecounter-start="0" data-purecounter-end="1463" data-purecounter-duration="1"
                                class="purecounter"></span>
                            <p>Hours Of Support</p>
                        </div>
                    </div>
                </div><!-- End Stats Item -->

                <div class="col-lg-3 col-md-6">
                    <div class="stats-item d-flex align-items-center w-100 h-100">
                        <i class="bi bi-people color-pink flex-shrink-0"></i>
                        <div>
                            <span data-purecounter-start="0" data-purecounter-end="15" data-purecounter-duration="1"
                                class="purecounter"></span>
                            <p>Hard Workers</p>
                        </div>
                    </div>
                </div><!-- End Stats Item -->

            </div>

        </div>

    </section><!-- /Stats Section --> --}}

    {{-- <!-- News Section -->
    <section id="{{ random_hash('news') }}" class="news section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <p>News<br></p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row gy-5">

                <div class="col-xl-4 col-md-6" data-aos="zoom-in" data-aos-delay="200">
                    <div class="news-item">
                        <div class="img">
                            <img src="{{ asset('img/services-1.jpg') }}" class="img-fluid" alt="">
                        </div>
                        <div class="details position-relative">
                            <div class="icon">
                                <i class="bi bi-activity"></i>
                            </div>
                            <a href="news-details.html" class="stretched-link">
                                <h3>Nesciunt Mete</h3>
                            </a>
                            <p>Provident nihil minus qui consequatur non omnis maiores. Eos accusantium minus
                                dolores iure perferendis.</p>
                        </div>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-xl-4 col-md-6" data-aos="zoom-in" data-aos-delay="300">
                    <div class="news-item">
                        <div class="img">
                            <img src="{{ asset('img/services-2.jpg') }}" class="img-fluid" alt="">
                        </div>
                        <div class="details position-relative">
                            <div class="icon">
                                <i class="bi bi-broadcast"></i>
                            </div>
                            <a href="news-details.html" class="stretched-link">
                                <h3>Eosle Commodi</h3>
                            </a>
                            <p>Ut autem aut autem non a. Sint sint sit facilis nam iusto sint. Libero corrupti neque
                                eum hic non ut nesciunt dolorem.</p>
                        </div>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-xl-4 col-md-6" data-aos="zoom-in" data-aos-delay="400">
                    <div class="news-item">
                        <div class="img">
                            <img src="{{ asset('img/services-3.jpg') }}" class="img-fluid" alt="">
                        </div>
                        <div class="details position-relative">
                            <div class="icon">
                                <i class="bi bi-easel"></i>
                            </div>
                            <a href="news-details.html" class="stretched-link">
                                <h3>Ledo Markt</h3>
                            </a>
                            <p>Ut excepturi voluptatem nisi sed. Quidem fuga consequatur. Minus ea aut. Vel qui id
                                voluptas adipisci eos earum corrupti.</p>
                        </div>
                    </div>
                </div><!-- End Service Item -->

            </div>

        </div>

    </section><!-- /News Section --> --}}

    <!-- News Section -->
    <section id="news" class="news section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <p>News<br></p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="row gy-5">

                @foreach ($latestNews as $news)
                    <div class="col-xl-4 col-md-6" data-aos="zoom-in" data-aos-delay="{{ $loop->iteration * 100 + 200 }}">
                        <div class="news-item">
                            <div class="img">
                                @if ($news->image_url)
                                    <img src="{{ $news->image_url }}" class="img-fluid" alt="{{ $news->title }}"
                                        style="height: 250px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('img/services-1.jpg') }}" class="img-fluid" alt="{{ $news->title }}"
                                        style="height: 250px; object-fit: cover;">
                                @endif
                            </div>
                            <div class="details position-relative">
                                <div class="icon">
                                    <i class="bi bi-activity"></i>
                                </div>
                                <a href="{{ route_encrypted('news.show', $news->id) }}" class="stretched-link">
                                    <h3>{{ $news->title }}</h3>
                                </a>
                                <p>{{ $news->short_summary ?? 'Berita terbaru seputar industri otomotif.' }}</p>

                                <!-- News Meta -->
                                <div class="news-meta mt-3 pt-3 border-top">
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $news->published_at->diffForHumans() }}
                                    </small>
                                    <small class="text-muted ms-3">
                                        <i class="bi bi-eye me-1"></i>
                                        {{ $news->view_count }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div><!-- End News Item -->
                @endforeach

            </div>

            <!-- View More Button -->
            @if ($latestNews->count() > 0)
                <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="500">
                    <a href="{{ route_encrypted('news.index') }}" class="btn btn-primary">
                        Lihat Semua Berita
                    </a>
                </div>
            @endif

        </div>

    </section><!-- /News Section -->

    <!-- Clients Section -->
    {{-- <section id="clients" class="clients section light-background">

        <div class="container" data-aos="fade-up">

            <div class="row gy-4">

                <div class="col-xl-2 col-md-3 col-6 client-logo">
                    <img src="{{ asset('img/clients/client-1.png') }}" class="img-fluid" alt="">
                </div><!-- End Client Item -->

                <div class="col-xl-2 col-md-3 col-6 client-logo">
                    <img src="{{ asset('img/clients/client-2.png') }}" class="img-fluid" alt="">
                </div><!-- End Client Item -->

                <div class="col-xl-2 col-md-3 col-6 client-logo">
                    <img src="{{ asset('img/clients/client-3.png') }}" class="img-fluid" alt="">
                </div><!-- End Client Item -->

                <div class="col-xl-2 col-md-3 col-6 client-logo">
                    <img src="{{ asset('img/clients/client-4.png') }}" class="img-fluid" alt="">
                </div><!-- End Client Item -->

                <div class="col-xl-2 col-md-3 col-6 client-logo">
                    <img src="{{ asset('img/clients/client-5.png') }}" class="img-fluid" alt="">
                </div><!-- End Client Item -->

                <div class="col-xl-2 col-md-3 col-6 client-logo">
                    <img src="{{ asset('img/clients/client-6.png') }}" class="img-fluid" alt="">
                </div><!-- End Client Item -->

            </div>

        </div>

    </section><!-- /Clients Section --> --}}

    <!-- Features Section -->
    {{-- <section id="features" class="features section">

        <div class="container">

            <ul class="nav nav-tabs row  d-flex" data-aos="fade-up" data-aos-delay="100">
                <li class="nav-item col-3">
                    <a class="nav-link active show" data-bs-toggle="tab" data-bs-target="#features-tab-1">
                        <i class="bi bi-binoculars"></i>
                        <h4 class="d-none d-lg-block">Modi sit est dela pireda nest</h4>
                    </a>
                </li>
                <li class="nav-item col-3">
                    <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-2">
                        <i class="bi bi-box-seam"></i>
                        <h4 class="d-none d-lg-block">Unde praesenti mara setra le</h4>
                    </a>
                </li>
                <li class="nav-item col-3">
                    <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-3">
                        <i class="bi bi-brightness-high"></i>
                        <h4 class="d-none d-lg-block">Pariatur explica nitro dela</h4>
                    </a>
                </li>
                <li class="nav-item col-3">
                    <a class="nav-link" data-bs-toggle="tab" data-bs-target="#features-tab-4">
                        <i class="bi bi-command"></i>
                        <h4 class="d-none d-lg-block">Nostrum qui dile node</h4>
                    </a>
                </li>
            </ul><!-- End Tab Nav -->

            <div class="tab-content" data-aos="fade-up" data-aos-delay="200">

                <div class="tab-pane fade active show" id="features-tab-1">
                    <div class="row">
                        <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0">
                            <h3>Voluptatem dignissimos provident quasi corporis voluptates sit assumenda.</h3>
                            <p class="fst-italic">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore
                                magna aliqua.
                            </p>
                            <ul>
                                <li><i class="bi bi-check2-all"></i>
                                    <spab>Ullamco laboris nisi ut aliquip ex ea commodo consequat.</spab>
                                </li>
                                <li><i class="bi bi-check2-all"></i> <span>Duis aute irure dolor in reprehenderit
                                        in voluptate velit</span>.</li>
                                <li><i class="bi bi-check2-all"></i> <span>Ullamco laboris nisi ut aliquip ex ea
                                        commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                                        trideta storacalaperda mastiro dolore eu fugiat nulla pariatur.</span></li>
                            </ul>
                            <p>
                                Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
                                reprehenderit in voluptate
                                velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat
                                non proident, sunt in
                                culpa qui officia deserunt mollit anim id est laborum
                            </p>
                        </div>
                        <div class="col-lg-6 order-1 order-lg-2 text-center">
                            <img src="{{ asset('img/working-1.jpg') }}" alt="" class="img-fluid">
                        </div>
                    </div>
                </div><!-- End Tab Content Item -->

                <div class="tab-pane fade" id="features-tab-2">
                    <div class="row">
                        <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0">
                            <h3>Neque exercitationem debitis soluta quos debitis quo mollitia officia est</h3>
                            <p>
                                Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
                                reprehenderit in voluptate
                                velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat
                                non proident, sunt in
                                culpa qui officia deserunt mollit anim id est laborum
                            </p>
                            <p class="fst-italic">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore
                                magna aliqua.
                            </p>
                            <ul>
                                <li><i class="bi bi-check2-all"></i> <span>Ullamco laboris nisi ut aliquip ex ea
                                        commodo consequat.</span></li>
                                <li><i class="bi bi-check2-all"></i> <span>Duis aute irure dolor in reprehenderit
                                        in voluptate velit.</span></li>
                                <li><i class="bi bi-check2-all"></i> <span>Provident mollitia neque rerum
                                        asperiores dolores quos qui a. Ipsum neque dolor voluptate nisi sed.</span>
                                </li>
                                <li><i class="bi bi-check2-all"></i> <span>Ullamco laboris nisi ut aliquip ex ea
                                        commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                                        trideta storacalaperda mastiro dolore eu fugiat nulla pariatur.</span></li>
                            </ul>
                        </div>
                        <div class="col-lg-6 order-1 order-lg-2 text-center">
                            <img src="{{ asset('img/working-2.jpg') }}" alt="" class="img-fluid">
                        </div>
                    </div>
                </div><!-- End Tab Content Item -->

                <div class="tab-pane fade" id="features-tab-3">
                    <div class="row">
                        <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0">
                            <h3>Voluptatibus commodi ut accusamus ea repudiandae ut autem dolor ut assumenda</h3>
                            <p>
                                Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
                                reprehenderit in voluptate
                                velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat
                                non proident, sunt in
                                culpa qui officia deserunt mollit anim id est laborum
                            </p>
                            <ul>
                                <li><i class="bi bi-check2-all"></i> <span>Ullamco laboris nisi ut aliquip ex ea
                                        commodo consequat.</span></li>
                                <li><i class="bi bi-check2-all"></i> <span>Duis aute irure dolor in reprehenderit
                                        in voluptate velit.</span></li>
                                <li><i class="bi bi-check2-all"></i> <span>Provident mollitia neque rerum
                                        asperiores dolores quos qui a. Ipsum neque dolor voluptate nisi sed.</span>
                                </li>
                            </ul>
                            <p class="fst-italic">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore
                                magna aliqua.
                            </p>
                        </div>
                        <div class="col-lg-6 order-1 order-lg-2 text-center">
                            <img src="{{ asset('img/working-3.jpg') }}" alt="" class="img-fluid">
                        </div>
                    </div>
                </div><!-- End Tab Content Item -->

                <div class="tab-pane fade" id="features-tab-4">
                    <div class="row">
                        <div class="col-lg-6 order-2 order-lg-1 mt-3 mt-lg-0">
                            <h3>Omnis fugiat ea explicabo sunt dolorum asperiores sequi inventore rerum</h3>
                            <p>
                                Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
                                reprehenderit in voluptate
                                velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat
                                non proident, sunt in
                                culpa qui officia deserunt mollit anim id est laborum
                            </p>
                            <p class="fst-italic">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore
                                magna aliqua.
                            </p>
                            <ul>
                                <li><i class="bi bi-check2-all"></i> <span>Ullamco laboris nisi ut aliquip ex ea
                                        commodo consequat.</span></li>
                                <li><i class="bi bi-check2-all"></i> <span>Duis aute irure dolor in reprehenderit
                                        in voluptate velit.</span></li>
                                <li><i class="bi bi-check2-all"></i> <span>Ullamco laboris nisi ut aliquip ex ea
                                        commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                                        trideta storacalaperda mastiro dolore eu fugiat nulla pariatur.</span></li>
                            </ul>
                        </div>
                        <div class="col-lg-6 order-1 order-lg-2 text-center">
                            <img src="{{ asset('img/working-4.jpg') }}" alt="" class="img-fluid">
                        </div>
                    </div>
                </div><!-- End Tab Content Item -->

            </div>

        </div>

    </section><!-- /Features Section --> --}}

    <!-- Services 2 Section -->
    {{-- <section id="services-2" class="services-2 section light-background">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Services</h2>
            <p>CHECK OUR SERVICES</p>
        </div><!-- End Section Title -->

        <div class="container">

            <div class="row gy-4">

                <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-item d-flex position-relative h-100">
                        <i class="bi bi-briefcase icon flex-shrink-0"></i>
                        <div>
                            <h4 class="title"><a href="#" class="stretched-link">Lorem Ipsum</a></h4>
                            <p class="description">Voluptatum deleniti atque corrupti quos dolores et quas
                                molestias excepturi sint occaecati cupiditate non provident</p>
                        </div>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-item d-flex position-relative h-100">
                        <i class="bi bi-card-checklist icon flex-shrink-0"></i>
                        <div>
                            <h4 class="title"><a href="#" class="stretched-link">Dolor Sitema</a></h4>
                            <p class="description">Minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                                aliquip ex ea commodo consequat tarad limino ata</p>
                        </div>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="service-item d-flex position-relative h-100">
                        <i class="bi bi-bar-chart icon flex-shrink-0"></i>
                        <div>
                            <h4 class="title"><a href="#" class="stretched-link">Sed ut perspiciatis</a>
                            </h4>
                            <p class="description">Duis aute irure dolor in reprehenderit in voluptate velit esse
                                cillum dolore eu fugiat nulla pariatur</p>
                        </div>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="service-item d-flex position-relative h-100">
                        <i class="bi bi-binoculars icon flex-shrink-0"></i>
                        <div>
                            <h4 class="title"><a href="#" class="stretched-link">Magni Dolores</a></h4>
                            <p class="description">Excepteur sint occaecat cupidatat non proident, sunt in culpa
                                qui officia deserunt mollit anim id est laborum</p>
                        </div>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="service-item d-flex position-relative h-100">
                        <i class="bi bi-brightness-high icon flex-shrink-0"></i>
                        <div>
                            <h4 class="title"><a href="#" class="stretched-link">Nemo Enim</a></h4>
                            <p class="description">At vero eos et accusamus et iusto odio dignissimos ducimus qui
                                blanditiis praesentium voluptatum deleniti atque</p>
                        </div>
                    </div>
                </div><!-- End Service Item -->

                <div class="col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="service-item d-flex position-relative h-100">
                        <i class="bi bi-calendar4-week icon flex-shrink-0"></i>
                        <div>
                            <h4 class="title"><a href="#" class="stretched-link">Eiusmod Tempor</a></h4>
                            <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam
                                libero tempore, cum soluta nobis est eligendi</p>
                        </div>
                    </div>
                </div><!-- End Service Item -->

            </div>

        </div>

    </section><!-- /Services 2 Section --> --}}

    <!-- Testimonials Section -->
    {{-- <section id="testimonials" class="testimonials section dark-background">

        <img src="{{ asset('img/testimonials-bg.jpg') }}" class="testimonials-bg" alt="">

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <div class="swiper init-swiper">
                <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              }
            }
          </script>
                <div class="swiper-wrapper">

                    <div class="swiper-slide">
                        <div class="testimonial-item">
                            <img src="{{ asset('img/testimonials/testimonials-1.jpg') }}" class="testimonial-img"
                                alt="">
                            <h3>Saul Goodman</h3>
                            <h4>Ceo &amp; Founder</h4>
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i>
                            </div>
                            <p>
                                <i class="bi bi-quote quote-icon-left"></i>
                                <span>Proin iaculis purus consequat sem cure digni ssim donec porttitora entum
                                    suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et.
                                    Maecen aliquam, risus at semper.</span>
                                <i class="bi bi-quote quote-icon-right"></i>
                            </p>
                        </div>
                    </div><!-- End testimonial item -->

                    <div class="swiper-slide">
                        <div class="testimonial-item">
                            <img src="{{ asset('img/testimonials/testimonials-2.jpg') }}" class="testimonial-img"
                                alt="">
                            <h3>Sara Wilsson</h3>
                            <h4>Designer</h4>
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i>
                            </div>
                            <p>
                                <i class="bi bi-quote quote-icon-left"></i>
                                <span>Export tempor illum tamen malis malis eram quae irure esse labore quem cillum
                                    quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat
                                    irure amet legam anim culpa.</span>
                                <i class="bi bi-quote quote-icon-right"></i>
                            </p>
                        </div>
                    </div><!-- End testimonial item -->

                    <div class="swiper-slide">
                        <div class="testimonial-item">
                            <img src="{{ asset('img/testimonials/testimonials-3.jpg') }}" class="testimonial-img"
                                alt="">
                            <h3>Jena Karlis</h3>
                            <h4>Store Owner</h4>
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i>
                            </div>
                            <p>
                                <i class="bi bi-quote quote-icon-left"></i>
                                <span>Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla
                                    quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore
                                    quis sint minim.</span>
                                <i class="bi bi-quote quote-icon-right"></i>
                            </p>
                        </div>
                    </div><!-- End testimonial item -->

                    <div class="swiper-slide">
                        <div class="testimonial-item">
                            <img src="{{ asset('img/testimonials/testimonials-4.jpg') }}" class="testimonial-img"
                                alt="">
                            <h3>Matt Brandon</h3>
                            <h4>Freelancer</h4>
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i>
                            </div>
                            <p>
                                <i class="bi bi-quote quote-icon-left"></i>
                                <span>Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim
                                    fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore
                                    quem dolore labore illum veniam.</span>
                                <i class="bi bi-quote quote-icon-right"></i>
                            </p>
                        </div>
                    </div><!-- End testimonial item -->

                    <div class="swiper-slide">
                        <div class="testimonial-item">
                            <img src="{{ asset('img/testimonials/testimonials-5.jpg') }}" class="testimonial-img"
                                alt="">
                            <h3>John Larson</h3>
                            <h4>Entrepreneur</h4>
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                    class="bi bi-star-fill"></i>
                            </div>
                            <p>
                                <i class="bi bi-quote quote-icon-left"></i>
                                <span>Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor
                                    noster veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat legam
                                    esse veniam culpa fore nisi cillum quid.</span>
                                <i class="bi bi-quote quote-icon-right"></i>
                            </p>
                        </div>
                    </div><!-- End testimonial item -->

                </div>
                <div class="swiper-pagination"></div>
            </div>

        </div>

    </section><!-- /Testimonials Section --> --}}

    <!-- Portfolio Section -->
    {{-- <section id="portfolio" class="portfolio section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Portfolio</h2>
            <p>CHECK OUR PORTFOLIO</p>
        </div><!-- End Section Title -->

        <div class="container">

            <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

                <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
                    <li data-filter="*" class="filter-active">All</li>
                    <li data-filter=".filter-app">App</li>
                    <li data-filter=".filter-product">Product</li>
                    <li data-filter=".filter-branding">Branding</li>
                    <li data-filter=".filter-books">Books</li>
                </ul><!-- End Portfolio Filters -->

                <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-app">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/app-1.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>App 1</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/app-1.jpg') }}" title="App 1"
                                    data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Portfolio Item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-product">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/product-1.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Product 1</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/product-1.jpg') }}" title="Product 1"
                                    data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Portfolio Item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-branding">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/branding-1.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Branding 1</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/branding-1.jpg') }}" title="Branding 1"
                                    data-gallery="portfolio-gallery-branding" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Portfolio Item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-books">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/books-1.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Books 1</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/books-1.jpg') }}" title="Branding 1"
                                    data-gallery="portfolio-gallery-book" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Portfolio Item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-app">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/app-2.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>App 2</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/app-2.jpg') }}" title="App 2"
                                    data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Portfolio Item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-product">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/product-2.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Product 2</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/product-2.jpg') }}" title="Product 2"
                                    data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Portfolio Item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-branding">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/branding-2.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Branding 2</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/branding-2.jpg') }}" title="Branding 2"
                                    data-gallery="portfolio-gallery-branding" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Portfolio Item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-books">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/books-2.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Books 2</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/books-2.jpg') }}" title="Branding 2"
                                    data-gallery="portfolio-gallery-book" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Portfolio Item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-app">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/app-3.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>App 3</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/app-3.jpg') }}" title="App 3"
                                    data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Portfolio Item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-product">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/product-3.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Product 3</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/product-3.jpg') }}" title="Product 3"
                                    data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Portfolio Item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-branding">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/branding-3.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Branding 3</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/branding-3.jpg') }}" title="Branding 2"
                                    data-gallery="portfolio-gallery-branding" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Portfolio Item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-books">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/books-3.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Books 3</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/books-3.jpg') }}" title="Branding 3"
                                    data-gallery="portfolio-gallery-book" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Portfolio Item -->

                </div><!-- End Portfolio Container -->

            </div>

        </div>

    </section><!-- /Portfolio Section --> --}}

    <!-- Program Section -->
    {{-- <section id="program" class="program section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <p>OUR PROGRAM</p>
        </div><!-- End Section Title -->

        <div class="container">

            <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

                <ul class="program-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
                    <li data-filter="*" class="filter-active">All</li>
                    <li data-filter=".filter-app">App</li>
                    <li data-filter=".filter-product">Product</li>
                    <li data-filter=".filter-branding">Branding</li>
                    <li data-filter=".filter-books">Books</li>
                </ul><!-- End program Filters -->

                <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">

                    <div class="col-lg-4 col-md-6 program-item isotope-item filter-app">
                        <div class="program-content h-100">
                            <img src="{{ asset('img/portfolio/app-1.jpg') }}" class="img-fluid" alt="">
                            <div class="program-info">
                                <h4>App 1</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/app-1.jpg') }}" title="App 1"
                                    data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="program-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Program item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-product">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/product-1.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Product 1</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/product-1.jpg') }}" title="Product 1"
                                    data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Program item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-branding">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/branding-1.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Branding 1</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/branding-1.jpg') }}" title="Branding 1"
                                    data-gallery="portfolio-gallery-branding" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Program item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-books">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/books-1.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Books 1</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/books-1.jpg') }}" title="Branding 1"
                                    data-gallery="portfolio-gallery-book" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Program item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-app">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/app-2.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>App 2</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/app-2.jpg') }}" title="App 2"
                                    data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Program item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-product">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/product-2.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Product 2</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/product-2.jpg') }}" title="Product 2"
                                    data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Program item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-branding">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/branding-2.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Branding 2</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/branding-2.jpg') }}" title="Branding 2"
                                    data-gallery="portfolio-gallery-branding" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Program item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-books">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/books-2.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Books 2</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/books-2.jpg') }}" title="Branding 2"
                                    data-gallery="portfolio-gallery-book" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Program item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-app">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/app-3.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>App 3</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/app-3.jpg') }}" title="App 3"
                                    data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Program item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-product">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/product-3.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Product 3</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/product-3.jpg') }}" title="Product 3"
                                    data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Program item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-branding">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/branding-3.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Branding 3</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/branding-3.jpg') }}" title="Branding 2"
                                    data-gallery="portfolio-gallery-branding" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Program item -->

                    <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-books">
                        <div class="portfolio-content h-100">
                            <img src="{{ asset('img/portfolio/books-3.jpg') }}" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Books 3</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/books-3.jpg') }}" title="Branding 3"
                                    data-gallery="portfolio-gallery-book" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Program item -->

                </div><!-- End Program Container -->

            </div>

        </div>

    </section><!-- /Program Section -->

    <!-- Achievement Section -->
    <section id="achievement" class="achievement section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <p>OUR ACHIEVEMENT</p>
        </div><!-- End Section Title -->

        <div class="container">

            <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

                <ul class="achievement-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
                    <li data-filter="*" class="filter-active">All</li>
                    <li data-filter=".filter-app">App</li>
                    <li data-filter=".filter-product">Product</li>
                    <li data-filter=".filter-branding">Branding</li>
                    <li data-filter=".filter-books">Books</li>
                </ul><!-- End achievement Filters -->

                <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">

                    <div class="col-lg-4 col-md-6 achievement-item isotope-item filter-app">
                        <div class="achievement-content h-100">
                            <img src="{{ asset('img/portfolio/app-1.jpg') }}" class="img-fluid" alt="">
                            <div class="achievement-info">
                                <h4>App 1</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/app-1.jpg') }}" title="App 1"
                                    data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="achievement-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Portfolio Item -->

                    <div class="col-lg-4 col-md-6 achievement-item isotope-item filter-product">
                        <div class="achievement-content h-100">
                            <img src="{{ asset('img/portfolio/product-1.jpg') }}" class="img-fluid" alt="">
                            <div class="achievement-info">
                                <h4>Product 1</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/product-1.jpg') }}" title="Product 1"
                                    data-gallery="achievement-gallery-product" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="achievement-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End achievement Item -->

                    <div class="col-lg-4 col-md-6 achievement-item isotope-item filter-branding">
                        <div class="achievement-content h-100">
                            <img src="{{ asset('img/portfolio/branding-1.jpg') }}" class="img-fluid" alt="">
                            <div class="achievement-info">
                                <h4>Branding 1</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/branding-1.jpg') }}" title="Branding 1"
                                    data-gallery="achievement-gallery-branding" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="achievement-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End Portfolio Item -->

                    <div class="col-lg-4 col-md-6 achievement-item isotope-item filter-books">
                        <div class="achievement-content h-100">
                            <img src="{{ asset('img/portfolio/books-1.jpg') }}" class="img-fluid" alt="">
                            <div class="achievement-info">
                                <h4>Books 1</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/books-1.jpg') }}" title="Branding 1"
                                    data-gallery="achievement-gallery-book" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="achievement-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End achievement Item -->

                    <div class="col-lg-4 col-md-6 achievement-item isotope-item filter-app">
                        <div class="achievement-content h-100">
                            <img src="{{ asset('img/portfolio/app-2.jpg') }}" class="img-fluid" alt="">
                            <div class="achievement-info">
                                <h4>App 2</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/app-2.jpg') }}" title="App 2"
                                    data-gallery="achievement-gallery-app" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="achievement-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End achievement Item -->

                    <div class="col-lg-4 col-md-6 achievement-item isotope-item filter-product">
                        <div class="achievement-content h-100">
                            <img src="{{ asset('img/portfolio/product-2.jpg') }}" class="img-fluid" alt="">
                            <div class="achievement-info">
                                <h4>Product 2</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/product-2.jpg') }}" title="Product 2"
                                    data-gallery="achievement-gallery-product" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="achievement-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End achievement Item -->

                    <div class="col-lg-4 col-md-6 achievement-item isotope-item filter-branding">
                        <div class="achievement-content h-100">
                            <img src="{{ asset('img/portfolio/branding-2.jpg') }}" class="img-fluid" alt="">
                            <div class="achievement-info">
                                <h4>Branding 2</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/branding-2.jpg') }}" title="Branding 2"
                                    data-gallery="achievement-gallery-branding" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="achievement-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End achievement Item -->

                    <div class="col-lg-4 col-md-6 achievement-item isotope-item filter-books">
                        <div class="achievement-content h-100">
                            <img src="{{ asset('img/portfolio/books-2.jpg') }}" class="img-fluid" alt="">
                            <div class="achievement-info">
                                <h4>Books 2</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/books-2.jpg') }}" title="Branding 2"
                                    data-gallery="achievement-gallery-book" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="achievement-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End achievement Item -->

                    <div class="col-lg-4 col-md-6 achievement-item isotope-item filter-app">
                        <div class="achievement-content h-100">
                            <img src="{{ asset('img/portfolio/app-3.jpg') }}" class="img-fluid" alt="">
                            <div class="achievement-info">
                                <h4>App 3</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/app-3.jpg') }}" title="App 3"
                                    data-gallery="achievement-gallery-app" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="achievement-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End achievement Item -->

                    <div class="col-lg-4 col-md-6 achievement-item isotope-item filter-product">
                        <div class="achievement-content h-100">
                            <img src="{{ asset('img/portfolio/product-3.jpg') }}" class="img-fluid" alt="">
                            <div class="achievement-info">
                                <h4>Product 3</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/product-3.jpg') }}" title="Product 3"
                                    data-gallery="achievement-gallery-product" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="achievement-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End achievement Item -->

                    <div class="col-lg-4 col-md-6 achievement-item isotope-item filter-branding">
                        <div class="achievement-content h-100">
                            <img src="{{ asset('img/portfolio/branding-3.jpg') }}" class="img-fluid" alt="">
                            <div class="achievement-info">
                                <h4>Branding 3</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/branding-3.jpg') }}" title="Branding 2"
                                    data-gallery="achievement-gallery-branding" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="achievement-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End achievement Item -->

                    <div class="col-lg-4 col-md-6 achievement-item isotope-item filter-books">
                        <div class="achievement-content h-100">
                            <img src="{{ asset('img/portfolio/books-3.jpg') }}" class="img-fluid" alt="">
                            <div class="achievement-info">
                                <h4>Books 3</h4>
                                <p>Lorem ipsum, dolor sit amet consectetur</p>
                                <a href="{{ asset('img/portfolio/books-3.jpg') }}" title="Branding 3"
                                    data-gallery="achievement-gallery-book" class="glightbox preview-link"><i
                                        class="bi bi-zoom-in"></i></a>
                                <a href="achievement-details.html" title="More Details" class="details-link"><i
                                        class="bi bi-link-45deg"></i></a>
                            </div>
                        </div>
                    </div><!-- End achievement Item -->

                </div><!-- End achievement Container -->

            </div>

        </div>

    </section><!-- /achievement Section --> --}}

    <!-- Program Section -->
    <section id="programs" class="programs section bg-light">

        <div class="container" data-aos="fade-up">

            <!-- Section Title -->
            <div class="section-title">
                <h2>Our Programs</h2>
                <p>Program-program unggulan yang kami selenggarakan untuk kemajuan industri otomotif</p>
            </div>

            <!-- Year Filter -->
            <div class="row justify-content-center mb-5" data-aos="fade-up" data-aos-delay="100">
                <div class="col-lg-8 text-center">
                    <div class="year-filter">
                        <span class="filter-label me-3">Filter by Year:</span>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="?program_year=all{{ request('achievement_year') ? '&achievement_year=' . request('achievement_year') : '' }}#programs"
                                class="btn btn-outline-primary {{ ($programYear ?? 'all') == 'all' ? 'active' : '' }}">
                                All Years
                            </a>
                            @foreach ($availableProgramYears as $year)
                                <a href="?program_year={{ $year }}{{ request('achievement_year') ? '&achievement_year=' . request('achievement_year') : '' }}#programs"
                                    class="btn btn-outline-primary {{ ($programYear ?? 'all') == $year ? 'active' : '' }}">
                                    {{ $year }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Programs Grid -->
            <div class="row gy-4 programs-grid" data-aos="fade-up" data-aos-delay="200">

                @forelse($programs as $program)
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 program-item">
                        <div class="program-card">
                            <div class="program-image">
                                <img src="{{ $program->image_url }}" class="img-fluid" alt="{{ $program->title }}"
                                    onerror="this.src='{{ asset('images/program-placeholder.jpg') }}'">
                                <div class="program-overlay">
                                    <div class="program-actions">
                                        <a href="{{ $program->image_url }}" title="{{ $program->title }}"
                                            data-gallery="program-gallery" class="glightbox preview-link">
                                            <i class="bi bi-zoom-in"></i>
                                        </a>
                                        <a href="{{ route_encrypted('programs.show', $program->id) }}" title="More Details"
                                            class="details-link">
                                            <i class="bi bi-link-45deg"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="program-content">
                                <h5 class="program-title">{{ Str::limit($program->title, 50) }}</h5>
                                <p class="program-description">{{ $program->short_description }}</p>
                                <div class="program-meta">
                                    <span class="program-year">{{ $program->year }}</span>
                                    <span class="program-category">{{ $program->category_name }}</span>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Program Item -->
                @empty
                    <!-- Empty State -->
                    <div class="col-12 text-center py-5">
                        <div class="empty-program">
                            <i class="bi bi-calendar-event display-1 text-muted mb-3"></i>
                            <h4 class="text-muted">Tidak Ada Program</h4>
                            <p class="text-muted">Belum ada program untuk tahun {{ $programYear ?? date('Y') }}</p>
                        </div>
                    </div>
                @endforelse

            </div><!-- End Programs Grid -->

            <!-- View All Button -->
            @if ($programs->count() >= 20)
                <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="300">
                    <a href="{{ route_encrypted('programs.index') }}?type=program" class="btn btn-primary">
                        <i class="bi bi-grid-3x3-gap me-2"></i>Lihat Semua Program
                    </a>
                </div>
            @endif

        </div>

    </section><!-- /Program Section -->

    <!-- Achievement Section -->
    <section id="achievements" class="achievements section">

        <div class="container" data-aos="fade-up">

            <!-- Section Title -->
            <div class="section-title">
                <h2>Our Achievements</h2>
                <p>Prestasi dan pencapaian yang membanggakan dalam industri otomotif</p>
            </div>

            <!-- Year Filter -->
            <div class="row justify-content-center mb-5" data-aos="fade-up" data-aos-delay="100">
                <div class="col-lg-8 text-center">
                    <div class="year-filter">
                        <span class="filter-label me-3">Filter by Year:</span>
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="?achievement_year=all{{ request('program_year') ? '&program_year=' . request('program_year') : '' }}#achievements"
                                class="btn btn-outline-success {{ ($achievementYear ?? 'all') == 'all' ? 'active' : '' }}">
                                All Years
                            </a>
                            @foreach ($availableAchievementYears as $year)
                                <a href="?achievement_year={{ $year }}{{ request('program_year') ? '&program_year=' . request('program_year') : '' }}#achievements"
                                    class="btn btn-outline-success {{ ($achievementYear ?? 'all') == $year ? 'active' : '' }}">
                                    {{ $year }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Achievements Grid -->
            <div class="row gy-4 achievements-grid" data-aos="fade-up" data-aos-delay="200">

                @forelse($achievements as $achievement)
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 achievement-item">
                        <div class="achievement-card">
                            <div class="achievement-image">
                                <img src="{{ $achievement->image_url }}" class="img-fluid"
                                    alt="{{ $achievement->title }}"
                                    onerror="this.src='{{ asset('images/achievement-placeholder.jpg') }}'">
                                <div class="achievement-badge">
                                    <i class="bi bi-trophy-fill"></i>
                                </div>
                                <div class="achievement-overlay">
                                    <div class="achievement-actions">
                                        <a href="{{ $achievement->image_url }}" title="{{ $achievement->title }}"
                                            data-gallery="achievement-gallery" class="glightbox preview-link">
                                            <i class="bi bi-zoom-in"></i>
                                        </a>
                                        <a href="{{ route_encrypted('programs.show', $achievement->id) }}" title="More Details"
                                            class="details-link">
                                            <i class="bi bi-link-45deg"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="achievement-content">
                                <h5 class="achievement-title">{{ Str::limit($achievement->title, 50) }}</h5>
                                <p class="achievement-description">{{ $achievement->short_description }}</p>
                                <div class="achievement-meta">
                                    <span class="achievement-year">{{ $achievement->year }}</span>
                                    <span class="achievement-category">{{ $achievement->category_name }}</span>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Achievement Item -->
                @empty
                    <!-- Empty State -->
                    <div class="col-12 text-center py-5">
                        <div class="empty-achievement">
                            <i class="bi bi-trophy display-1 text-muted mb-3"></i>
                            <h4 class="text-muted">Tidak Ada Achievement</h4>
                            <p class="text-muted">Belum ada achievement untuk tahun {{ $achievementYear ?? date('Y') }}
                            </p>
                        </div>
                    </div>
                @endforelse

            </div><!-- End Achievements Grid -->

            <!-- View All Button -->
            @if ($achievements->count() >= 20)
                <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="300">
                    <a href="{{ route_encrypted('programs.index') }}?type=achievement" class="btn btn-success">
                        <i class="bi bi-trophy me-2"></i>Lihat Semua Achievement
                    </a>
                </div>
            @endif

        </div>

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
