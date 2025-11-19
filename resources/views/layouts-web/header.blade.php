<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

        <a href="{{ url('/') }}" class="logo d-flex align-items-center me-auto">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <!-- <img src="asset img/logo.png" alt=""> -->
            <h1 class="sitename">IR PT. IPPI</h1>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="{{ url('/#hero') }}" class="active">Home</a></li>
                <li><a href="{{ url('/#about') }}">About</a></li>
                <li><a href="{{ url('/#' . random_hash('news')) }}"
                        onclick="UrlObfuscation.navigateTo('news'); return false;">News</a></li>
                {{-- <li><a href="#portfolio">Grand Design</a></li> --}}
                {{-- <li><a href="#team">Team</a></li> --}}
                <!-- Program Dropdown -->
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <span>Assessment</span>
                        <i class="bi bi-chevron-down toggle-dropdown"></i>
                    </a>
                    <ul>
                        <li><a href="{{ url('/assessments') }}">
                                <i class="bi bi-clipboard-check me-2"></i>
                                IR Assessment
                            </a></li>
                        {{-- <li><a href="javascript:void(0)">
                                <i class="bi bi-graph-up me-2"></i>
                                Engagement Assessment
                            </a></li> --}}
                    </ul>
                </li>
                {{-- <li class="dropdown"><a href="#"><span>Program</span> <i
                            class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        <li><a href="#">IR Assessment</a></li> --}}
                {{-- <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i
                                    class="bi bi-chevron-down toggle-dropdown"></i></a>
                            <ul>
                                <li><a href="#">Deep Dropdown 1</a></li>
                                <li><a href="#">Deep Dropdown 2</a></li>
                                <li><a href="#">Deep Dropdown 3</a></li>
                                <li><a href="#">Deep Dropdown 4</a></li>
                                <li><a href="#">Deep Dropdown 5</a></li>
                            </ul>
                        </li> --}}
                {{-- <li><a href="#">Enggament Assessment</a></li> --}}
                {{-- <li><a href="#">Dropdown 3</a></li>
                        <li><a href="#">Dropdown 4</a></li> --}}
                {{-- </ul>
                </li> --}}
                <li><a href="{{ url('/#programs') }}">Program</a></li>
                <li><a href="{{ url('/#achievements') }}">Achievement</a></li>
                {{-- <li><a href="#portfolio">Feedback</a></li>
                <li><a href="#contact">Contact</a></li> --}}
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        {{-- <a class="cta-btn" href="index.html#about">Login</a> --}}
        <a class="cta-btn" href="{{ route('login') }}">Login</a>

    </div>
</header>
