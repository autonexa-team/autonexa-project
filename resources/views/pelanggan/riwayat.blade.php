{{-- views/pelanggan/riwayat.blade.php --}}

@extends('layout.app')
@section('title', 'Riwayat Reservasi')

@push('styles')
    <!-- Mempertahankan CSS reservasi jika ada styling global yang digunakan (seperti font atau layout dasar) -->
    <link rel="stylesheet" href="{{ asset('css/reservasi.css') }}">
    <style>
        /* Animasi Progress Bar saat Halaman Load */
        @keyframes fillProgress {
            from {
                width: 0%;
            }
        }

        .progress-line-animate {
            animation: fillProgress 1.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Animasi Fade In Card */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-card {
            opacity: 0;
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
@endpush

@php
    // DUMMY DATA RESERVASI
    $riwayats = [
        [
            'id' => 'RSV-002',
            'bengkel' => 'AutoNexa Cabang Kebon Jeruk',
            'tanggal' => '20 Mei 2026, 10:00 WIB',
            'layanan' => 'Ganti Oli Reguler',
            'kendaraan' => 'Toyota Avanza - B 5678 DEF',
            'status' => 'diproses',
            'step' => 3,
        ],
        [
            'id' => 'RSV-003',
            'bengkel' => 'AutoNexa Cabang Kelapa Gading',
            'tanggal' => '22 Mei 2026, 13:30 WIB',
            'layanan' => 'Pengecekan Kaki-kaki',
            'kendaraan' => 'Mitsubishi Xpander - B 9012 GHI',
            'status' => 'menunggu',
            'step' => 2,
        ],
        [
            'id' => 'RSV-001',
            'bengkel' => 'AutoNexa Cabang Sudirman',
            'tanggal' => '18 Mei 2026, 09:00 WIB',
            'layanan' => 'Servis Berkala 10.000 KM',
            'kendaraan' => 'Honda Brio - B 1234 ABC',
            'status' => 'selesai',
            'step' => 4,
        ],
        [
            'id' => 'RSV-004',
            'bengkel' => 'AutoNexa Cabang Sudirman',
            'tanggal' => '05 Mei 2026, 14:00 WIB',
            'layanan' => 'Ganti Aki',
            'kendaraan' => 'Honda Brio - B 1234 ABC',
            'status' => 'dibatalkan',
            'step' => 0,
        ],
    ];
@endphp

@section('content')

    <div class="bg-slate-50 min-h-screen pb-20">
        <!-- Header Section (Hero-like) -->
        <div class="bg-white border-b border-slate-200 pt-10 pb-16 px-6 lg:px-20 relative overflow-hidden">
            <div
                class="absolute right-0 top-0 w-64 h-64 bg-orange-50 rounded-full blur-3xl opacity-50 pointer-events-none -translate-y-1/2 translate-x-1/3">
            </div>
            <div class="max-w-5xl mx-auto relative z-10">
                <a href="{{ url('/reservasi') }}"
                    class="inline-flex items-center gap-2 text-slate-400 hover:text-brand font-bold text-sm mb-6 transition-colors">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
                <h1 class="text-4xl font-black text-slate-800 tracking-tight mb-3">Riwayat <span
                        class="text-brand">Reservasi</span></h1>
                <p class="text-slate-500 font-medium max-w-lg">Pantau status servis kendaraan Anda dan lihat riwayat
                    pelayanan dari bengkel kami sebelumnya.</p>
            </div>
        </div>

        <!-- Main Content Container -->
        <div class="max-w-5xl mx-auto px-6 lg:px-0 -mt-8 relative z-20">

            <!-- Filter & Search Bar -->
            <div
                class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4 justify-between items-center mb-8">
                <!-- Search -->
                <div
                    class="w-full md:w-1/2 flex items-center bg-slate-50 rounded-xl px-4 py-3 border border-slate-200 focus-within:border-brand/50 focus-within:ring-2 focus-within:ring-brand/20 transition-all">
                    <i class="fas fa-search text-slate-400"></i>
                    <input type="text" placeholder="Cari nomor resi atau nama bengkel..."
                        class="bg-transparent border-none outline-none ml-3 w-full text-sm font-medium text-slate-700">
                </div>

                <!-- Filters -->
                <div class="w-full md:w-auto flex gap-2 overflow-x-auto pb-2 md:pb-0 hide-scrollbar">
                    <button
                        class="bg-slate-800 text-white px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap shadow-sm hover:bg-slate-900 transition-colors">Semua</button>
                    <button
                        class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-brand hover:border-brand/30 px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-colors">Menunggu</button>
                    <button
                        class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-brand hover:border-brand/30 px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-colors">Diproses</button>
                    <button
                        class="bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 px-5 py-2.5 rounded-xl text-sm font-bold whitespace-nowrap transition-colors">Selesai</button>
                </div>
            </div>

            @if (count($riwayats) > 0)
                <!-- List Riwayat (Cards) -->
                <div class="space-y-6">
                    @foreach ($riwayats as $index => $item)
                        <!-- Individual Card -->
                        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow border border-slate-100 overflow-hidden animate-card"
                            style="animation-delay: {{ $index * 150 }}ms;">

                            <!-- Card Header -->
                            <div
                                class="px-6 py-4 border-b border-slate-100 flex flex-wrap justify-between items-center gap-4 bg-slate-50/50">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-orange-50 text-brand flex items-center justify-center font-black">
                                        <i class="fas fa-wrench"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-800 text-lg">{{ $item['bengkel'] }}</h3>
                                        <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">
                                            {{ $item['id'] }} <span class="mx-2 normal-case text-slate-300">•</span>
                                            {{ $item['tanggal'] }}</p>
                                    </div>
                                </div>

                                <!-- Status Badge Dinamis -->
                                @if ($item['status'] == 'selesai')
                                    <span
                                        class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-3 py-1.5 rounded-lg text-xs font-bold inline-flex items-center gap-1.5 shadow-sm">
                                        <i class="fas fa-check-circle"></i> Reservasi Selesai
                                    </span>
                                @elseif($item['status'] == 'menunggu')
                                    <span
                                        class="bg-amber-100 border border-amber-200 text-amber-700 px-3 py-1.5 rounded-lg text-xs font-bold inline-flex items-center gap-1.5 shadow-sm">
                                        <i class="fas fa-clock"></i> Menunggu Konfirmasi
                                    </span>
                                @elseif($item['status'] == 'diproses')
                                    <span
                                        class="bg-blue-100 border border-blue-200 text-blue-700 px-3 py-1.5 rounded-lg text-xs font-bold inline-flex items-center gap-1.5 shadow-sm">
                                        <i class="fas fa-cog fa-spin"></i> Sedang Dikerjakan
                                    </span>
                                @else
                                    <span
                                        class="bg-red-100 border border-red-200 text-red-700 px-3 py-1.5 rounded-lg text-xs font-bold inline-flex items-center gap-1.5 shadow-sm">
                                        <i class="fas fa-times-circle"></i> Dibatalkan
                                    </span>
                                @endif
                            </div>

                            <!-- Card Body (Progress Bar & Info) -->
                            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-8">

                                <!-- Service Details -->
                                <div class="md:col-span-1 space-y-4">
                                    <div>
                                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Kendaraan
                                        </p>
                                        <p class="text-slate-800 font-bold flex items-center gap-2">
                                            <i class="fas fa-car text-slate-300"></i> {{ $item['kendaraan'] }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Layanan
                                            Utama</p>
                                        <p class="text-slate-800 font-bold flex items-start gap-2">
                                            <i class="fas fa-tools text-slate-300 mt-1"></i> {{ $item['layanan'] }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Progress Bar Tracker -->
                                <div class="md:col-span-2 pt-2 pb-6 md:pb-2">
                                    <p
                                        class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-8 text-center md:text-left">
                                        Status Progres</p>

                                    <div class="relative flex justify-between items-center w-full px-4 sm:px-8">
                                        <!-- Background Line -->
                                        <div
                                            class="absolute left-4 right-4 sm:left-8 sm:right-8 top-1/2 transform -translate-y-1/2 h-1 bg-slate-100 rounded-full z-0">
                                        </div>

                                        <!-- Animated Progress Line -->
                                        @php
                                            // Calculate width percentage based on step (4 steps total, so 3 intervals of 33.33%)
                                            $progressWidth = 0;
                                            if ($item['step'] > 1) {
                                                $progressWidth = ($item['step'] - 1) * 33.33;
                                            }
                                            $barColor = $item['status'] == 'dibatalkan' ? 'bg-red-500' : 'bg-[#ff6a00]';
                                        @endphp
                                        <div class="absolute left-4 sm:left-8 top-1/2 transform -translate-y-1/2 h-1 {{ $barColor }} rounded-full z-0 progress-line-animate"
                                            style="width: {{ $progressWidth }}%; max-width: calc(100% - 2rem);"></div>

                                        <!-- Step 1: Dibuat -->
                                        <div class="relative z-10 flex flex-col items-center group">
                                            <div
                                                class="w-8 h-8 rounded-full flex items-center justify-center {{ $item['step'] >= 1 ? ($item['status'] == 'dibatalkan' ? 'bg-red-500 text-white' : 'bg-[#ff6a00] text-white') : 'bg-slate-200 text-slate-400' }} border-4 border-white shadow-sm transition-all duration-500">
                                                @if ($item['step'] > 1 && $item['status'] != 'dibatalkan')
                                                    <i class="fas fa-check text-xs"></i>
                                                @else
                                                    <i class="fas fa-file-signature text-xs"></i>
                                                @endif
                                            </div>
                                            <span
                                                class="text-[10px] sm:text-xs font-bold {{ $item['step'] >= 1 ? 'text-slate-700' : 'text-slate-400' }} mt-3 text-center absolute top-8 whitespace-nowrap">Dibuat</span>
                                        </div>

                                        <!-- Step 2: Dikonfirmasi -->
                                        <div class="relative z-10 flex flex-col items-center group">
                                            <div
                                                class="w-8 h-8 rounded-full flex items-center justify-center {{ $item['step'] >= 2 ? ($item['status'] == 'dibatalkan' ? 'bg-red-500 text-white' : 'bg-[#ff6a00] text-white') : 'bg-slate-200 text-slate-400' }} border-4 border-white shadow-sm transition-all duration-500 delay-100">
                                                @if ($item['step'] > 2 && $item['status'] != 'dibatalkan')
                                                    <i class="fas fa-check text-xs"></i>
                                                @else
                                                    <i class="fas fa-calendar-check text-xs"></i>
                                                @endif
                                            </div>
                                            <span
                                                class="text-[10px] sm:text-xs font-bold {{ $item['step'] >= 2 ? 'text-slate-700' : 'text-slate-400' }} mt-3 text-center absolute top-8 whitespace-nowrap">Dikonfirmasi</span>
                                        </div>

                                        <!-- Step 3: Dikerjakan -->
                                        <div class="relative z-10 flex flex-col items-center group">
                                            <div
                                                class="w-8 h-8 rounded-full flex items-center justify-center {{ $item['step'] >= 3 ? ($item['status'] == 'dibatalkan' ? 'bg-red-500 text-white' : 'bg-[#ff6a00] text-white') : 'bg-slate-200 text-slate-400' }} border-4 border-white shadow-sm transition-all duration-500 delay-200">
                                                @if ($item['step'] > 3 && $item['status'] != 'dibatalkan')
                                                    <i class="fas fa-check text-xs"></i>
                                                @else
                                                    <i class="fas fa-cogs text-xs"></i>
                                                @endif
                                            </div>
                                            <span
                                                class="text-[10px] sm:text-xs font-bold {{ $item['step'] >= 3 ? 'text-slate-700' : 'text-slate-400' }} mt-3 text-center absolute top-8 whitespace-nowrap">Dikerjakan</span>
                                        </div>

                                        <!-- Step 4: Selesai -->
                                        <div class="relative z-10 flex flex-col items-center group">
                                            <div
                                                class="w-8 h-8 rounded-full flex items-center justify-center {{ $item['step'] >= 4 ? ($item['status'] == 'dibatalkan' ? 'bg-red-500 text-white' : 'bg-[#ff6a00] text-white') : 'bg-slate-200 text-slate-400' }} border-4 border-white shadow-sm transition-all duration-500 delay-300">
                                                @if ($item['step'] >= 4 && $item['status'] != 'dibatalkan')
                                                    <i class="fas fa-flag-checkered text-xs"></i>
                                                @else
                                                    <i class="fas fa-flag text-xs"></i>
                                                @endif
                                            </div>
                                            <span
                                                class="text-[10px] sm:text-xs font-bold {{ $item['step'] >= 4 ? 'text-slate-700' : 'text-slate-400' }} mt-3 text-center absolute top-8 whitespace-nowrap">Selesai</span>
                                        </div>
                                    </div>

                                    @if ($item['status'] == 'dibatalkan')
                                        <p class="text-center text-xs font-bold text-red-500 mt-10"><i
                                                class="fas fa-exclamation-triangle"></i> Reservasi dibatalkan oleh pengguna
                                            / admin.</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Footer & Actions -->
                            <div class="px-6 py-4 bg-slate-50 flex justify-end gap-3 border-t border-slate-100">
                                <button
                                    class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-100 hover:border-slate-300 text-slate-700 font-bold rounded-xl text-sm transition-all shadow-sm">
                                    Lihat Detail
                                </button>

                                @if ($item['status'] == 'selesai')
                                    <button
                                        class="px-4 py-2 bg-[#ff6a00] hover:bg-[#e65c00] text-white font-bold rounded-xl text-sm transition-all shadow-md shadow-orange-500/20 flex items-center gap-2">
                                        <i class="fas fa-star text-amber-200"></i> Beri Review
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- EMPTY STATE -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 py-16 px-6 text-center animate-card">
                    <div class="w-32 h-32 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-clipboard-list text-5xl text-slate-300"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 mb-2">Belum Ada Riwayat Reservasi</h3>
                    <p class="text-slate-500 text-sm max-w-md mx-auto mb-8">Anda belum pernah melakukan pemesanan servis di
                        AutoNexa. Segera jadwalkan perawatan kendaraan Anda sekarang juga.</p>
                    <a href="{{ url('/reservasi') }}"
                        class="inline-flex bg-[#ff6a00] hover:bg-[#e65c00] text-white px-6 py-3 rounded-xl font-bold shadow-md shadow-orange-500/20 transition-transform hover:-translate-y-1">
                        Buat Reservasi Baru
                    </a>
                </div>
            @endif

        </div>
    </div>

@endsection
