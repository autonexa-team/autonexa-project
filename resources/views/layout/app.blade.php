<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Autonexa — Reservasi Bengkel Online')</title>

    {{-- Bootstrap & Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    {{-- AOS Animation --}}
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    {{-- App CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body class="font-sans">

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

    {{-- ═══════════════════════════════════════
        MAIN CONTENT
    ════════════════════════════════════════ --}}
    <main>
        @yield('content')
    </main>

    {{-- ═══════════════════════════════════════
        FOOTER
    ════════════════════════════════════════ --}}
    <footer class="footer-main">
        <div class="footer-inner">
            <div class="footer-grid">

                {{-- Brand --}}
                <div class="footer-col">
                    <div class="footer-brand-name">
                        
                        <img src="{{ asset('assets/logo.png') }}" alt="Autonexa Logo" class="logo-img">
                        Autonexa
                    </div>                 
                    <p class="footer-desc">
                        Reservasi online, pantau proses dan dapatkan informasi service dengan mudah dan transparan.
                    </p>
                    <div class="footer-social">
                        <a href="https://github.com/autonexa" target="_blank" class="social-btn" title="GitHub">
                            <i class="bi bi-github"></i>
                        </a>
                        <a href="#" class="social-btn" title="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="social-btn" title="WhatsApp">
                            <i class="bi bi-whatsapp"></i>
                        </a>
                    </div>
                </div>

                {{-- Navigation --}}
                <div class="footer-col">
                    <h6 class="footer-heading">Navigasi</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('landing') }}">Beranda</a></li>
                        <li><a href="{{ route('about') }}">Tentang Kami</a></li>
                        <li>
                            <a href="{{ route('pelanggan.bengkel') }}"
                                class="{{ request()->routeIs('pelanggan.bengkel') ? 'active' : '' }}">
                                Bengkel
                            </a>
                        </li>
                        <li><a href="{{ route('login') }}">Login</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div class="footer-col">
                    <h6 class="footer-heading">Hubungi Kami</h6>
                    <ul class="footer-links">
                        <li>
                            <i class="bi bi-envelope"></i>
                            <a href="mailto:hello@autonexa.id">hello@autonexa.id</a>
                        </li>
                        <li>
                            <i class="bi bi-telephone"></i>
                            <span>+62 812-3456-7890</span>
                        </li>
                        <li>
                            <i class="bi bi-geo-alt"></i>
                            <span>Bandung, Jawa Barat</span>
                        </li>
                    </ul>
                </div>

            </div>

            <hr class="footer-divider">
            <p class="footer-copy">&copy; {{ date('Y') }} Autonexa. All rights reserved.</p>
        </div>
    </footer>

    {{-- ═══════════════════════════════════════
        SCRIPTS
    ════════════════════════════════════════ --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>
