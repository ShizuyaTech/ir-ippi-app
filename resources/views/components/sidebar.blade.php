{{-- Debug info --}}
{{-- Auth::check() = {{ Auth::check() ? 'yes' : 'no' }} --}}
{{-- Auth::user()->email = {{ Auth::user()->email ?? 'not logged in' }} --}}
{{-- isAdmin = {{ $isAdmin ?? 'not set' }} --}}
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">IR-IPPI</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">IR</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item dropdown">
                <a href="{{ route('home') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i
                        class="fas fa-sitemap"></i><span>Organisasi</span></a>
                <ul class="dropdown-menu">
                    {{-- <li class='{{ Request::is('serikats') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ route('serikat.index') }}">Struktur IKA IPPI</a>
                    </li> --}}
                    {{-- <li class='{{ Request::is('spsi-ippi') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ route('spsi.index') }}">Struktur SPSI IPPI</a>
                    </li> --}}
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-chart-line"></i><span>Fitur</span></a>
                <ul class="dropdown-menu">
                    <li class='{{ Request::is('feedbacks') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ route('feedbacks.index') }}">Feedback</a>
                    </li>
                    <li class='{{ Request::is('schedules') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ route('schedules.index') }}">Schedule Activity</a>
                    </li>
                    <li class='{{ Request::is('lks-bipartit') ? 'active' : '' }}'>
                        <a class="nav-link" href="{{ route('lks-bipartit.index') }}">LKS Bipartit</a>
                    </li>
                </ul>
            </li>
            {{-- Menu Setting hanya untuk Admin --}}
            @if (isset($isAdmin) && $isAdmin)
                <li class="menu-header">Administrator</li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-cog"></i><span>Setting</span></a>
                    <ul class="dropdown-menu">
                        <li class='{{ Request::is('users*') ? 'active' : '' }}'>
                            <a class="nav-link" href="{{ route('users.index') }}">User Management</a>
                        </li>
                        <li class='{{ Request::is('assessments') ? 'active' : '' }}'>
                            <a class="nav-link" href="{{ route('assessments.index') }}">IR Assessment</a>
                        </li>
                        <li class='{{ Request::is('pages/program-achievements*') ? 'active' : '' }}'>
                            <a class="nav-link" href="{{ route('pages.program-achievements.index') }}">Program &
                                Achievement</a>
                        </li>
                        {{-- <li>
                            <a class="nav-link" href="{{ route('programs.index') }}">Program Management</a>
                        </li>
                        <li class='{{ Request::is('achievements*') ? 'active' : '' }}'>
                            <a class="nav-link" href="{{ route('achievements.index') }}">Achievement Management</a>
                        </li> --}}
                    </ul>
                </li>
            @endif
        </ul>
    </aside>
</div>
