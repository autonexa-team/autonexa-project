<!DOCTYPE html>
<html lang="id">
@php
    $bengkel = auth()->user()->bengkel()->first();
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Cabang - AutoNexa</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#ff6a00',
                        'brand-dark': '#e65c00',
                        'brand-light': '#ff8533',
                    }
                }
            }
        }
    </script>
    
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admincabang-dashboard.css') }}">

    <style>
        @page {
            /* Kosongkan margin agar header/footer browser tidak muncul */
            /* Catatan: ini hanya bekerja di beberapa browser */
            margin-top: 15mm;
            margin-bottom: 20mm;
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-slate-800">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-[4px_0_24px_rgba(0,0,0,0.02)] flex flex-col h-full z-20">
        <!-- Logo -->
        <div class="h-20 flex items-center justify-center border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand to-brand-light flex items-center justify-center text-white shadow-lg shadow-brand/30">
                    <i class="fas fa-wrench"></i>
                </div>
                <h1 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-600 tracking-tight">AutoNexa</h1>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4 px-3">Menu Utama</p>
            
            <!-- dashboard -->
            <a href="{{ route('admin-cabang.dashboard') }}"
            class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all group
            {{ request()->routeIs('admin-cabang.dashboard')
                    ? 'bg-brand text-white shadow-md shadow-brand/20'
                    : 'text-slate-500 hover:bg-orange-50 hover:text-brand' }}">

                <i class="fas fa-border-all w-5 text-lg
                    {{ request()->routeIs('admin-cabang.dashboard')
                        ? 'text-white'
                        : 'text-slate-400 group-hover:text-brand' }}"></i>

                <span class="font-medium">Dashboard</span>
            </a>
            
            <!-- reservasi -->
            <a href="{{ route('admin-cabang.reservasi') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all group
            {{ request()->routeIs('admin-cabang.reservasi')
                    ? 'bg-brand text-white shadow-md shadow-brand/20'
                    : 'text-slate-500 hover:bg-orange-50 hover:text-brand' }}">

                <i class="fas fa-calendar-alt w-5 text-lg
                    {{ request()->routeIs('admin-cabang.reservasi')
                        ? 'text-white'
                        : 'text-slate-400 group-hover:text-brand' }}"></i>

                <span class="font-medium">Reservasi</span>
                <span class="ml-auto bg-brand/10 text-brand py-0.5 px-2 rounded-md text-xs font-bold">4</span>
            </a>

            <!-- layanan -->
            <a href="{{ route('admin-cabang.layanan') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all group
            {{ request()->routeIs('admin-cabang.layanan')
                    ? 'bg-brand text-white shadow-md shadow-brand/20'
                    : 'text-slate-500 hover:bg-orange-50 hover:text-brand' }}">

                <i class="fas fa-screwdriver-wrench w-5 text-lg
                    {{ request()->routeIs('admin-cabang.layanan')
                        ? 'text-white'
                        : 'text-slate-400 group-hover:text-brand' }}"></i>

                <span class="font-medium">Layanan</span>
            </a>            
            
            {{-- Sparepart --}}
            <a href="{{ route('admin-cabang.sparepart') }}"
            class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all group
            {{ request()->routeIs('admin-cabang.sparepart*')
                    ? 'bg-brand text-white shadow-md shadow-brand/20'
                    : 'text-slate-500 hover:bg-orange-50 hover:text-brand' }}">

                <i class="fas fa-box-open w-5 text-lg
                    {{ request()->routeIs('admin-cabang.sparepart*')
                        ? 'text-white'
                        : 'text-slate-400 group-hover:text-brand' }}"></i>

                <span class="font-medium">Sparepart</span>
            </a>
            
            <!-- pelanggan -->
            <a href="{{ route('admin-cabang.pelanggan-cabang') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all group
            {{ request()->routeIs('admin-cabang.pelanggan-cabang')
                    ? 'bg-brand text-white shadow-md shadow-brand/20'
                    : 'text-slate-500 hover:bg-orange-50 hover:text-brand' }}">

                <i class="fas fa-users w-5 text-lg
                    {{ request()->routeIs('admin-cabang.pelanggan-cabang')
                        ? 'text-white'
                        : 'text-slate-400 group-hover:text-brand' }}"></i>

                <span class="font-medium">Pelanggan</span>
            </a>
            
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-8 mb-4 px-3">Lainnya</p>
            
            <!-- laporan -->
            <a href="{{ route('admin-cabang.laporan') }}"
            class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all group
            {{ request()->routeIs('admin-cabang.laporan*')
                    ? 'bg-brand text-white shadow-md shadow-brand/20'
                    : 'text-slate-500 hover:bg-orange-50 hover:text-brand' }}">

                <i class="fas fa-chart-pie w-5 text-lg
                    {{ request()->routeIs('admin-cabang.laporan*')
                        ? 'text-white'
                        : 'text-slate-400 group-hover:text-brand' }}"></i>

                <span class="font-medium">Laporan</span>
            </a>

            <!-- review -->
            <a href="{{ route('admin-cabang.review') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-xl transition-all group
            {{ request()->routeIs('admin-cabang.review')
                    ? 'bg-brand text-white shadow-md shadow-brand/20'
                    : 'text-slate-500 hover:bg-orange-50 hover:text-brand' }}">

                <i class="fas fa-tools w-5 text-lg
                    {{ request()->routeIs('admin-cabang.review')
                        ? 'text-white'
                        : 'text-slate-400 group-hover:text-brand' }}"></i>

                <span class="font-medium">Review</span>
            </a>        
        </nav>

        <!-- User Info Bottom -->
        <div class="relative p-4 m-4 border border-slate-100 rounded-2xl bg-slate-50 shadow-sm">

            <div class="flex items-center gap-3">

                <!-- Logo Bengkel -->
                <div class="w-11 h-11 rounded-xl bg-white overflow-hidden ring-2 ring-white shadow-sm flex-shrink-0">
                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->bengkel->nama ?? auth()->user()->name) }}&background=ff6a00&color=fff"
                        alt="Bengkel"
                        class="w-full h-full object-cover"
                    >
                </div>

                <!-- Info Bengkel -->
                <div class="flex-1 overflow-hidden">
                    <p class="text-sm font-bold text-slate-800 truncate">
                        {{ auth()->user()->bengkel->nama ?? auth()->user()->name }}
                    </p>

                    <p class="text-xs text-slate-500 truncate flex items-center gap-1">
                        <i class="fas fa-location-dot text-[10px]"></i>
                        {{ auth()->user()->bengkel->alamat ?? 'Alamat tidak tersedia' }}
                    </p>
                </div>

                <!-- Dropdown Trigger -->
                <div class="relative">

                    <button
                        onclick="toggleProfileMenu()"
                        class="w-9 h-9 rounded-xl hover:bg-slate-200/70 transition-all flex items-center justify-center text-slate-500 hover:text-slate-700"
                    >
                        <i class="fas fa-ellipsis-vertical"></i>
                    </button>

                    <!-- Dropdown -->
                    <div
                        id="profileMenu"
                        class="hidden absolute bottom-12 right-0 w-56 bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden z-50 animate-fadeIn"
                    >

                        <a href="{{ route('admin-cabang.profile') }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-slate-600 hover:bg-orange-50 hover:text-brand transition-all">

                            <i class="fas fa-store w-4"></i>
                            Profil Bengkel
                        </a>

                        <div class="border-t border-slate-100"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-500 hover:bg-red-50 transition-all">

                                <i class="fas fa-right-from-bracket w-4"></i>
                                Keluar
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full relative overflow-hidden bg-[#f8fafc]">
        
        <!-- Topbar -->
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-100 flex items-center justify-between px-8 sticky top-0 z-10">
            <div class="flex items-center bg-slate-100/80 rounded-2xl px-4 py-2.5 w-96 focus-within:ring-2 focus-within:ring-brand/50 focus-within:bg-white transition-all border border-transparent focus-within:border-brand/20">
                <i class="fas fa-search text-slate-400"></i>
                <input type="text" placeholder="Cari data pelanggan, reservasi, nopol..." class="bg-transparent border-none outline-none ml-3 w-full text-sm font-medium placeholder-slate-400">
            </div>
            
            <div class="flex items-center gap-5">

                {{-- NOTIFIKASI --}}
                <a href="{{ route('admin-cabang.notifikasi') }}"
                class="w-10 h-10 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-brand transition-colors relative">

                    <i class="far fa-bell text-xl"></i>

                    <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                </a>

                <div class="h-6 w-px bg-slate-200"></div>

                {{-- LOGOUT --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-2 text-slate-500 hover:text-brand font-semibold transition-colors px-3 py-2 rounded-xl hover:bg-orange-50">

                        <span class="text-sm">Keluar</span>
                        <i class="fas fa-sign-out-alt ml-1"></i>
                    </button>
                </form>

            </div>
        </header>

        <!-- Main Scrollable Area -->
        <div class="flex-1 overflow-y-auto p-8">
            
            <!-- Dynamic Content -->
            @yield('content')
            
            <!-- Footer -->
            <div class="mt-8 text-center text-sm font-medium text-slate-400">
                &copy; {{ date('Y') }} AutoNexa Admin Panel. All rights reserved.
            </div>

        </div>
    </main>

    <script>
        // Hilangkan header/footer browser saat print
        // dengan mengosongkan title dan inject style khusus
        window.addEventListener('beforeprint', function () {
            // Simpan title asli
            window._originalTitle = document.title;
            // Kosongkan title → header browser jadi kosong
            document.title = '';
        });

        window.addEventListener('afterprint', function () {
            // Kembalikan title setelah selesai print
            document.title = window._originalTitle;
        });
    </script>
</body>

<script>
function toggleProfileMenu() {
    document
        .getElementById('profileMenu')
        .classList.toggle('hidden');
}

document.addEventListener('click', function(e) {

    const menu = document.getElementById('profileMenu');

    if (!e.target.closest('#profileMenu') &&
        !e.target.closest('button')) {

        menu.classList.add('hidden');
    }
});
</script>

</html>

