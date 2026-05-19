    {{-- ═══════════════════════════════════════
        NAVBAR
    ════════════════════════════════════════ --}}
    <header id="navbar" class="nav-autonexa">
        <div class="nav-container">

            {{-- LOGO --}}
            <a href="{{ route('landing') }}" class="logo">
                <img src="{{ asset('assets/logo.png') }}" alt="Autonexa Logo" class="logo-img">
                Auotonexa
            </a>

            {{-- HAMBURGER (Mobile) --}}
            <button class="menu-toggle d-lg-none" id="menuToggle" aria-label="Toggle menu">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>

            {{-- MENU --}}
            <ul class="nav-menu" id="navMenu">
                <li>
                    <a href="{{ route('landing') }}"
                       class="{{ request()->routeIs('landing') ? 'active' : '' }}">
                        Beranda
                    </a>
                </li>
                <li>
                    <a href="{{ route('reservasi.public') }}"
                       class="{{ request()->routeIs('reservasi.public') ? 'active' : '' }}">
                        Reservasi
                    </a>
                </li>
                <li>
                    <a href="{{ route('pelanggan.bengkel') }}"
                       class="{{ request()->routeIs('pelanggan.bengkel') ? 'active' : '' }}">
                        Bengkel
                    </a>
                </li>
 
                {{-- Mobile Auth --}}
                <li class="d-lg-none nav-mobile-auth">
                    @auth
                        <div class="mobile-user-info">
                            <div class="mobile-user-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <span class="mobile-user-name">{{ auth()->user()->name }}</span>
                        </div>
                        <a href="{{ route('pelanggan.riwayat') }}" class="mobile-menu-link">
                            <i class="bi bi-calendar-check"></i> Reservasi Saya
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-logout-mobile">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    @endauth
 
                    @guest
                        <a href="{{ route('login') }}" class="btn-login">Masuk</a>
                        <a href="{{ route('register') }}" class="btn-daftar">Daftar</a>
                    @endguest
                </li>
            </ul>

            {{-- RIGHT: Auth Buttons (Desktop) --}}
            <div class="nav-right d-none d-lg-flex">
 
                @auth
                    {{-- User Pill + Dropdown --}}
                    <div class="user-dropdown dropdown">
                        <button class="user-pill dropdown-toggle"
                                id="userDropdown"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <div class="user-pill-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <span class="user-pill-name">{{ auth()->user()->name }}</span>
                            <i class="bi bi-chevron-down user-pill-chevron"></i>
                        </button>
 
                        <ul class="dropdown-menu dropdown-menu-end nav-dropdown-menu"
                            aria-labelledby="userDropdown">
 
                            <li class="dropdown-header-info">
                                <div class="dh-avatar">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="dh-name">{{ auth()->user()->name }}</div>
                                    <div class="dh-email">{{ auth()->user()->email }}</div>
                                </div>
                            </li>
 
                            <li><hr class="dropdown-divider nav-dd-divider"></li>
 
                            <li>
                                <a class="dropdown-item nav-dd-item"
                                   href="{{ route('pelanggan.profile') ?? '#' }}">
                                    <i class="bi bi-person-circle"></i>
                                    Profil Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item nav-dd-item"
                                   href="{{ route('pelanggan.riwayat') }}">
                                    <i class="bi bi-calendar-check"></i>
                                    Reservasi Saya
                                </a>
                            </li>
 
                            <li><hr class="dropdown-divider nav-dd-divider"></li>
 
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item nav-dd-item nav-dd-danger">
                                        <i class="bi bi-box-arrow-right"></i>
                                        Logout
                                    </button>
                                </form>
                            </li>
 
                        </ul>
                    </div>
                @endauth
 
                @guest
                    <a href="{{ route('login') }}" class="btn-login">Masuk</a>
                    {{-- Uncomment jika ingin tampilkan tombol daftar:
                    <a href="{{ route('register') }}" class="btn-daftar">Daftar</a>
                    --}}
                @endguest
 
            </div>
        </div>
    </header>