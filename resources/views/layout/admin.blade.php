{{-- resources/views/layout/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Autonexa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/admin-sidebar.css') }}">
    @stack('styles')
</head>
<body class="admin-body">

<div class="admin-shell">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar" id="sidebar">

        {{-- Brand --}}
        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="bi bi-gear-wide-connected"></i>
            </div>
            <div class="brand-text">
                <div class="brand-name">Autonexa</div>
                <div class="brand-role">
                    @auth
                        @if(auth()->user()->role === 'admin_pusat')
                            Admin Pusat
                        @elseif(auth()->user()->role === 'admin_cabang')
                            Admin Cabang
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        {{-- Navigasi per role --}}
        <nav class="sidebar-nav">

            {{-- ===== ADMIN PUSAT ===== --}}
            @if(auth()->user()->role === 'admin_pusat')

            <div class="nav-section-label">Utama</div>

            <a href="{{ route('admin-pusat.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin-pusat.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i>
                <span>Dashboard</span>
            </a>

            <div class="nav-section-label">Manajemen</div>

            <a href="{{ route('admin-pusat.bengkel.index') }}"
               class="nav-item {{ request()->routeIs('admin-pusat.bengkel.index') ? 'active' : '' }}">
                <i class="bi bi-shop"></i>
                <span>Bengkel</span>
                @if($totalBengkel ?? false)
                    <span class="nav-badge">{{ $totalBengkel }}</span>
                @endif
            </a>

            <a href="{{ route('admin-pusat.sparepart') }}"
               class="nav-item {{ request()->routeIs('admin-pusat.sparepart*') ? 'active' : '' }}">
                <i class="bi bi-wrench"></i>
                <span>Sparepart</span>
            </a>

            <a href="{{ route('admin-pusat.layanan') }}"  
               class="nav-item {{ request()->routeIs('admin-pusat.layanan*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>Layanan</span>
                @if(($sparepartKritis ?? 0) > 0)
                    <span class="nav-badge nav-badge-danger">{{ $sparepartKritis }}</span>
                @endif
            </a>

            <a href="{{ route('admin-pusat.user') }}"
               class="nav-item {{ request()->routeIs('admin-pusat.user') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>User</span>
            </a>

            <a href="{{ route('admin-pusat.reservasi') }}"
               class="nav-item {{ request()->routeIs('admin-pusat.reservasi') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Reservasi</span>
            </a>            

            <div class="nav-section-label">Analitik</div>

            <a href="{{ route('admin-pusat.review') }}"
               class="nav-item {{ request()->routeIs('admin-pusat.review') ? 'active' : '' }}">
                <i class="bi bi-star-half"></i>
                <span>Review</span>
            </a>

            <a href="{{ route('admin-pusat.laporan') }}"
               class="nav-item {{ request()->routeIs('admin-pusat.laporan') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i>
                <span>Laporan</span>
            </a>
            @endif

        </nav>

        {{-- User footer --}}
        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">
                        @if(auth()->user()->role === 'admin_pusat') Super Admin
                        @elseif(auth()->user()->role === 'admin_cabang') Admin Cabang
                        @else Mekanik
                        @endif
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="ms-auto">
                    @csrf
                    <button type="submit" class="btn-logout" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>

    </aside>

    {{-- ===== MAIN AREA ===== --}}
    <div class="admin-main">

        {{-- Topbar --}}
        <header class="admin-topbar">
            <div class="topbar-left">
                <button class="btn-toggle-sidebar" id="btnToggleSidebar">
                    <i class="bi bi-list"></i>
                </button>
                <div class="topbar-breadcrumb">
                    <span class="breadcrumb-page">@yield('title', 'Dashboard')</span>
                </div>
            </div>
            <div class="topbar-right">
                <div class="topbar-date">
                    <i class="bi bi-calendar3"></i>
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
                <div class="topbar-notif" id="btnNotif">
                    <i class="bi bi-bell"></i>
                    @if(($unreadNotif ?? 0) > 0)
                        <span class="notif-dot"></span>
                    @endif
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
        <div class="admin-flash flash-success">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button class="flash-close" onclick="this.parentElement.remove()">
                <i class="bi bi-x"></i>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="admin-flash flash-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
            <button class="flash-close" onclick="this.parentElement.remove()">
                <i class="bi bi-x"></i>
            </button>
        </div>
        @endif

        {{-- Content --}}
        <main class="admin-content">
            @yield('content')
        </main>

    </div>
</div>

<script>
document.getElementById('btnToggleSidebar')?.addEventListener('click', function () {
    document.getElementById('sidebar').classList.toggle('sidebar-collapsed');
    document.querySelector('.admin-shell').classList.toggle('sidebar-collapsed');
});
</script>

@stack('scripts')
</body>
</html>