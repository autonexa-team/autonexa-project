{{-- views/pelanggan/profile.blade.php --}}

@extends('layout.app')
@section('title', 'Profil Saya')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pelanggan-profile.css') }}">
@endpush

@section('content')

<div class="profile-page bg-slate-50 min-h-screen pb-20 pt-10">
    <div class="profile-container max-w-6xl mx-auto px-6">
        
        <!-- Breadcrumb / Back Link -->
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-brand font-bold text-sm mb-6 transition-colors animate-slide-up">
            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
        </a>

        <!-- 1. HEADER PROFILE (Card Besar) -->
       <div class="profile-card mb-8 animate-slide-up">
            <!-- Cover Background -->
            <div class="profile-cover h-32 bg-gradient-to-r from-slate-800 to-slate-700 relative">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20"></div>
                <!-- Dekorasi oranye -->
                <div class="absolute right-0 top-0 w-64 h-full bg-brand/20 blur-3xl"></div>
            </div>
            
            <div class="profile-header-content">
                <!-- Avatar & Info -->
                <div class="profile-info-wrapper">

                    <!-- Avatar -->
                    <div class="profile-avatar">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=ff6a00&color=fff&size=200">

                        <div class="avatar-camera">
                            <i class="fas fa-camera"></i>
                        </div>
                    </div>

                    <!-- Text Info -->
                    <div class="profile-text-info">

                        <!-- BARIS ATAS -->
                        <div class="profile-user-top">
                            <h1>{{ $user->name }}</h1>

                            <span class="member-badge">
                                <i class="fas fa-crown"></i>
                                Gold Member
                            </span>
                        </div>

                    <!-- BARIS BAWAH -->
                    <div class="profile-user-bottom">
                        <span>
                            <i class="fas fa-envelope"></i>
                            {{ $user->email }}
                        </span>

                        <span class="dot-separator">•</span>

                        <span>
                            <i class="fas fa-phone-alt"></i>
                            {{ $user->phone ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="profile-action-buttons">
                <button class="btn-profile bg-white border-2 border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-700 px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2 w-full sm:w-auto">
                    <i class="fas fa-lock text-slate-400"></i>
                    Ubah Password
                </button>
                <button class="btn-profile btn-brand bg-brand border-2 border-brand hover:bg-brand-dark text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-md shadow-brand/20 flex items-center justify-center gap-2 w-full sm:w-auto">
                    <i class="fas fa-user-edit"></i> 
                    Edit Profil
                </button>
            </div>
        </div>
    </div>

    <!-- 2. STATS & LOYALTY PROGRESS -->
    <div class="profile-summary animate-slide-up"> 
        <!-- Ringkasan Aktivitas (Stats) - Mengambil 2 kolom -->
        <div class="stats-wrapper">
             <!-- Total Reservasi -->
            <div class="stats-card bg-white rounded-2xl shadow-sm border border-slate-100">
                <div class="w-10 h-10 text-xl bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center text-lg mb-1 group-hover:bg-brand/10 group-hover:text-brand transition-colors">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                    <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider">Total Reservasi</p>
                    <h3 class="text-base font-black text-slate-800">{{ $stats['total_reservasi'] }}</h3>
            </div>

            <!-- Selesai -->
            <div class="stats-card bg-white rounded-2xl shadow-sm border border-slate-100">
                <div class="w-10 h-10 text-xl bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center text-lg mb-1 group-hover:bg-emerald-100 transition-colors">
                    <i class="fas fa-check-double"></i>
                </div>
                    <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider">Selesai</p>
                    <h3 class="text-base font-black  text-slate-800">{{ $stats['reservasi_selesai'] }}</h3>
            </div>

            <!-- Aktif -->
            <div class="stats-card bg-white rounded-2xl shadow-sm border border-slate-100">
                <div class="w-10 h-10 text-xl bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-lg mb-1 group-hover:bg-blue-100 transition-colors">
                    <i class="fas fa-cogs"></i>
                </div>
                    <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider">Proses Aktif</p>
                    <h3 class="text-base font-black text-slate-800">{{ $stats['reservasi_aktif'] }}</h3>
            </div>

                <!-- Review -->
            <div class="stats-card bg-white rounded-2xl shadow-sm border border-slate-100">
                <div class="w-10 h-10 text-xl bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center text-lg mb-1 group-hover:bg-amber-100 transition-colors">
                    <i class="fas fa-star"></i>
                </div>
                    <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider">Total Review</p>
                    <h3 class="text-base font-black text-slate-800">{{ $stats['total_review'] }}</h3>
            </div>
        </div>

        <!-- Loyalty Progress Card - 1 Kolom -->
        <div class="loyalty-card relative overflow-hidden">

        <!-- Background pattern -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-30 z-0"></div>
        <div class="absolute -right-10 -top-10 text-amber-500/10 text-9xl z-0 pointer-events-none"></div>

        <div class="flex items-center gap-1 text-slate-300 font-bold text-xs">
            
            <div class="flex justify-between items-center text-xs" style="color: #ff7605ff; width: 100%;">
                <span style="font-weight: 800 !important;">
                    Level Loyaltyㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤㅤ Menuju Platinum
                </span>
                <i class="fas fa-crown" style="color: #000000ff;"></i>
            </div>
            <p class="text-xs text-slate-600">
                Gold Member</p>

            <p class="text-xs text-slate-600">
                <strong>{{ $stats['total_reservasi'] }}</strong>
                dari 16 reservasi dibutuhkan</p>
            <div class="w-full bg-slate-600 rounded-full h-2.5 mb-2 overflow-hidden shadow-inner"></div>
        </div>
    </div>
</div>

 <!-- 3. TAB NAVIGATION -->
        <div class="profile-tabs bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden animate-slide-up" style="animation-delay: 300ms;">
                    
        <!-- Tab Headers -->
        <div class="profile-tab-header flex flex-row flex-nowrap overflow-x-auto hide-scrollbar">
            <button onclick="switchTab('informasi')" id="btn-informasi"
                class="tab-btn px-6 py-4 text-sm font-bold border-b-2 border-brand text-brand whitespace-nowrap transition-all focus:outline-none">
                    <i class="fas fa-id-card"></i> Informasi Akun
            </button>

            <button onclick="switchTab('reservasi')" id="btn-reservasi"
                class="tab-btn px-6 py-4 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 whitespace-nowrap transition-all focus:outline-none">
                    <i class="fas fa-history"></i> Riwayat Reservasi Terakhir
            </button>

            <button onclick="switchTab('review')" id="btn-review"
                class="tab-btn px-6 py-4 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 whitespace-nowrap transition-all focus:outline-none">
                    <i class="fas fa-star"></i> Review & Ulasan
            </button>
        </div>

        <!-- TAB 1: INFORMASI AKUN -->
        <div class="p-6 md:p-8">
            <div id="tab-informasi" class="tab-content active">
                <h3 class="detail-title"><i class="fas fa-user-circle"></i>Detail Akun</h3>

            <!-- CARD DETAIL AKUN -->
            <div class="profile-detail-card">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Nama -->
            <div>
                <label class="profile-detail-label">Nama Lengkap</label>
                <div class="profile-detail-box"><i class="fas fa-user"></i>
                    <span class="profile-detail-value">{{ $user->name }}</span>
                </div>
            </div>

            <!-- Email -->
            <div>
                <label class="profile-detail-label">Alamat Email</label>
                    <div class="profile-detail-box"><i class="fas fa-envelope"></i>
                        <span class="profile-detail-value">{{ $user->email }}</span>
                    </div>
            </div>

            <!-- Phone -->
            <div>
                <label class="profile-detail-label">Nomor Handphone (WhatsApp)</label>
                    <div class="profile-detail-box">
                        <i class="fas fa-phone-alt"></i>
                            <span class="profile-detail-value">{{ $user->phone ?? '-' }}</span>
                    </div>
            </div>

            <!-- Joined -->
            <div>
                <label class="profile-detail-label">Tanggal Bergabung</label>
                <div class="profile-detail-box"><i class="fas fa-calendar-alt"></i>
                        <span class="profile-detail-value">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
            </div>

            <!-- Status -->
            <div class="md:col-span-2">
                <label class="profile-detail-label">Status Akun</label>

                <div class="profile-detail-box">
                    <i class="fas fa-user-check"></i>
                    <span class="profile-detail-value">
                        Aktif
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
 

<!-- TAB 2: RESERVASI TERBARU -->
<div id="tab-reservasi" class="tab-content">
    <div class="flex justify-between items-center mb-6">
        <h3 class="detail-title"><i class="fas fa-history"></i> Riwayat Reservasi Terakhir</h3>
    <a href="{{ route('pelanggan.riwayat') }}" 
    class="text-sm font-bold text-black hover:underline">
    Lihat Semua &rarr;
    </a>
        </div>
        <div class="space-y-4">
            @foreach($reservasis as $res)
                <div class="reservasi-card">

                <!-- LEFT -->
                <div class="reservasi-left">

                    <!-- ICON -->
                    <div class="reservasi-icon
                        {{ $res->status == 'selesai' ? 'icon-selesai' : 
                        ($res->status == 'proses' ? 'icon-proses' : 'icon-batal') }}">

                        @if($res->status == 'selesai')
                            <i class="fas fa-check"></i>

                        @elseif($res->status == 'proses')
                            <i class="fas fa-cog"></i>

                        @else
                            <i class="fas fa-times"></i>
                        @endif
                    </div>

                    <!-- INFO -->
                    <div class="reservasi-info">

                        <h4>Reservasi #{{ $res->id }}</h4>

                        <p>
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $res->bengkel->nama }}
                        </p>

                        <p class="tanggal">
                            <i class="fas fa-calendar-alt"></i>
                            {{ \Carbon\Carbon::parse($res->tanggal)->format('d M Y') }}
                        </p>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="reservasi-right">
                    <!-- STATUS -->
                    @if($res->status == 'selesai')

                        <span class="status-badge badge-selesai">
                            <i class="fas fa-check-circle"></i>
                            Selesai
                        </span>

                   @elseif($res->status == 'proses')

                        <span class="status-badge badge-proses">
                            <i class="fas fa-spinner"></i>
                            Sedang Diproses
                        </span>

                    @else

                        <span class="status-badge badge-batal">
                            <i class="fas fa-times-circle"></i>
                            Dibatalkan
                        </span>

                    @endif

                    <!-- BUTTON -->
                    <a href="{{ route('pelanggan.riwayat-detail', $res->id) }}" class="btn-detail">
                        Detail
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- TAB 3: REVIEW & ULASAN -->
    <div id="tab-review" class="tab-content">
        <div class="review-box">
            <h3 class="detail-title">
                <i class="fas fa-star text-brand"></i>
                Review & Ulasan
            </h3>

            <div class="review-list">
                @foreach($reviews as $rev)
                <div class="review-item">

                    <!-- Header -->
                    <div class="review-header">
                        <div class="review-user">
                            <div class="review-avatar">
                                <i class="fas fa-user"></i>
                            </div>

                            <div>
                                <h4 class="review-title">{{ $rev->bengkel->nama }}</h4>
                                <p class="review-date">{{ $rev->created_at->format('d M Y') }}</p>
                            </div>
                        </div>

                        <div class="review-rating">
                            <div class="review-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $rev->rating)
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </div>

                            <span class="rating-badge">
                                {{ number_format($rev->rating,1) }}
                            </span>
                        </div>
                    </div>

                    <!-- Isi Review -->
                    <div class="review-content">
                        <i class="fas fa-quote-left"></i>
                        <p class="review-text">
                            {{ $rev->komentar }}
                        </p>
                    </div>

                    <!-- Action -->
                    <div class="review-action">
                        <button class="btn-edit-review">
                            <i class="fas fa-pen"></i>
                            Edit Review
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>


    <!-- Script untuk Navigasi Tab Sederhana -->
    <script>
        function switchTab(tabId) {
            // Hide all content
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.remove('active');
            });
            
            // Reset all buttons style
            document.querySelectorAll('[id^="btn-"]').forEach(btn => {
                btn.classList.remove('border-brand', 'text-brand');
                btn.classList.add('border-transparent', 'text-slate-500');
            });
            
            // Show active content
            document.getElementById('tab-' + tabId).classList.add('active');
            
            // Style active button
            const activeBtn = document.getElementById('btn-' + tabId);
            activeBtn.classList.remove('border-transparent', 'text-slate-500');
            activeBtn.classList.add('border-brand', 'text-brand');
        }
    </script>

    @endsection
