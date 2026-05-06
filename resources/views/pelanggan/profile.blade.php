{{-- views/pelanggan/profile.blade.php --}}

@extends('layout.app')
@section('title', 'Profil Saya')

@push('styles')
<style>
    /* Animasi Load Halaman */
    @keyframes slideUpFade {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-up {
        opacity: 0;
        animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    
    /* Smooth Tab Transition */
    .tab-content {
        display: none;
        opacity: 0;
    }
    .tab-content.active {
        display: block;
        animation: slideUpFade 0.4s ease-out forwards;
    }
</style>
@endpush

@php
    // DUMMY DATA PELANGGAN
    $user = [
        'name' => 'Budi Santoso',
        'email' => 'budi@email.com',
        'phone' => '0812-3456-7890',
        'joined' => '15 Januari 2025',
        'status' => 'Aktif',
        'loyalty' => 'Gold Member',
        'loyalty_progress' => 75, // Persentase progress ke tier selanjutnya
        'next_tier' => 'Platinum',
        'stats' => [
            'total_reservasi' => 12,
            'reservasi_selesai' => 10,
            'reservasi_aktif' => 1,
            'total_review' => 8
        ]
    ];

    // DUMMY DATA RESERVASI TERBARU
    $reservasis = [
        [
            'bengkel' => 'AutoNexa Cabang Kebon Jeruk',
            'tanggal' => '20 Mei 2026, 10:00 WIB',
            'status' => 'proses',
            'layanan' => 'Ganti Oli Reguler'
        ],
        [
            'bengkel' => 'AutoNexa Cabang Sudirman',
            'tanggal' => '18 Mei 2026, 09:00 WIB',
            'status' => 'selesai',
            'layanan' => 'Servis Berkala 10.000 KM'
        ],
        [
            'bengkel' => 'AutoNexa Cabang Kelapa Gading',
            'tanggal' => '05 Mei 2026, 14:00 WIB',
            'status' => 'batal',
            'layanan' => 'Pengecekan Kaki-kaki'
        ]
    ];

    // DUMMY DATA REVIEW
    $reviews = [
        [
            'bengkel' => 'AutoNexa Cabang Sudirman',
            'rating' => 5,
            'tanggal' => '19 Mei 2026',
            'komentar' => 'Pelayanan sangat memuaskan, mekanik ramah dan pengerjaan super cepat. Ruang tunggunya juga nyaman sekali dan dapat kopi gratis!'
        ],
        [
            'bengkel' => 'AutoNexa Cabang Kelapa Gading',
            'rating' => 4,
            'tanggal' => '12 Apr 2026',
            'komentar' => 'Hasil servis bagus mesin jadi halus lagi. Tapi antreannya lumayan padat walau sudah reservasi. Overall ok lah.'
        ]
    ];
@endphp

@section('content')

<div class="bg-slate-50 min-h-screen pb-20 pt-10">
    <div class="max-w-6xl mx-auto px-6">
        
        <!-- Breadcrumb / Back Link -->
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-brand font-bold text-sm mb-6 transition-colors animate-slide-up">
            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
        </a>

        <!-- 1. HEADER PROFILE (Card Besar) -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8 animate-slide-up" style="animation-delay: 100ms;">
            <!-- Cover Background -->
            <div class="h-32 bg-gradient-to-r from-slate-800 to-slate-700 relative">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20"></div>
                <!-- Dekorasi oranye -->
                <div class="absolute right-0 top-0 w-64 h-full bg-brand/20 blur-3xl"></div>
            </div>
            
            <div class="px-8 pb-8 relative flex flex-col md:flex-row gap-6 justify-between items-start md:items-end">
                <!-- Avatar & Info -->
                <div class="flex flex-col md:flex-row gap-6 items-center md:items-end -mt-12 md:-mt-16 relative z-10 w-full md:w-auto">
                    <!-- Avatar -->
                    <div class="w-32 h-32 rounded-full border-4 border-white shadow-lg bg-white overflow-hidden flex-shrink-0 relative group">
                        <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=ff6a00&color=fff&size=200&font-size=0.4" alt="Profil User" class="w-full h-full object-cover">
                        <!-- Edit Avatar Overlay (Hover) -->
                        <div class="absolute inset-0 bg-black/50 hidden group-hover:flex items-center justify-center cursor-pointer transition-all">
                            <i class="fas fa-camera text-white text-xl"></i>
                        </div>
                    </div>
                    
                    <!-- Text Info -->
                    <div class="text-center md:text-left pt-2 pb-2">
                        <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4 mb-1">
                            <h1 class="text-3xl font-black text-slate-800 tracking-tight">{{ $user['name'] }}</h1>
                            <span class="bg-amber-100 border border-amber-200 text-amber-700 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center justify-center gap-1.5 shadow-sm mx-auto md:mx-0 w-max">
                                <i class="fas fa-crown text-amber-500"></i> {{ $user['loyalty'] }}
                            </span>
                        </div>
                        <p class="text-slate-500 font-medium text-sm flex flex-col md:flex-row md:items-center gap-2 md:gap-4 mt-2 md:mt-0">
                            <span><i class="fas fa-envelope text-slate-300 mr-1.5"></i> {{ $user['email'] }}</span>
                            <span class="hidden md:inline text-slate-300">•</span>
                            <span><i class="fas fa-phone-alt text-slate-300 mr-1.5"></i> {{ $user['phone'] }}</span>
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row w-full md:w-auto gap-3 pt-4 md:pt-0">
                    <button class="bg-white border-2 border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-700 px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2 w-full sm:w-auto">
                        <i class="fas fa-lock text-slate-400"></i> Ubah Password
                    </button>
                    <button class="bg-brand border-2 border-brand hover:bg-brand-dark text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-md shadow-brand/20 flex items-center justify-center gap-2 w-full sm:w-auto">
                        <i class="fas fa-user-edit"></i> Edit Profil
                    </button>
                </div>
            </div>
        </div>

        <!-- 2. STATS & LOYALTY PROGRESS -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8 animate-slide-up" style="animation-delay: 200ms;">
            
            <!-- Ringkasan Aktivitas (Stats) - Mengambil 2 kolom -->
            <div class="lg:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Total Reservasi -->
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center text-lg mb-3 group-hover:bg-brand/10 group-hover:text-brand transition-colors">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Total Reservasi</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $user['stats']['total_reservasi'] }}</h3>
                </div>
                <!-- Selesai -->
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center text-lg mb-3 group-hover:bg-emerald-100 transition-colors">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Selesai</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $user['stats']['reservasi_selesai'] }}</h3>
                </div>
                <!-- Aktif -->
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-lg mb-3 group-hover:bg-blue-100 transition-colors">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Proses Aktif</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $user['stats']['reservasi_aktif'] }}</h3>
                </div>
                <!-- Review -->
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div class="w-10 h-10 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center text-lg mb-3 group-hover:bg-amber-100 transition-colors">
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Total Review</p>
                    <h3 class="text-2xl font-black text-slate-800">{{ $user['stats']['total_review'] }}</h3>
                </div>
            </div>

            <!-- Loyalty Progress Card - 1 Kolom -->
            <div class="bg-slate-800 rounded-2xl p-6 shadow-md relative overflow-hidden text-white flex flex-col justify-center">
                <!-- Background pattern -->
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-30 z-0"></div>
                <div class="absolute -right-10 -top-10 text-amber-500/10 text-9xl z-0 pointer-events-none">
                    <i class="fas fa-crown"></i>
                </div>
                
                <div class="relative z-10">
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="font-bold text-amber-400 uppercase tracking-wider text-xs">Level Loyalty</h4>
                        <span class="text-xs font-bold text-slate-300">Menuju {{ $user['next_tier'] }}</span>
                    </div>
                    <p class="text-2xl font-black mb-4">{{ $user['loyalty'] }}</p>
                    
                    <!-- Progress Bar -->
                    <div class="w-full bg-slate-700 rounded-full h-2.5 mb-2 overflow-hidden shadow-inner">
                        <div class="bg-gradient-to-r from-amber-500 to-amber-300 h-2.5 rounded-full" style="width: {{ $user['loyalty_progress'] }}%"></div>
                    </div>
                    <p class="text-xs text-slate-400"><strong>{{ $user['stats']['total_reservasi'] }}</strong> dari 16 reservasi dibutuhkan</p>
                </div>
            </div>

        </div>

        <!-- 3. TAB NAVIGATION -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden animate-slide-up" style="animation-delay: 300ms;">
            
            <!-- Tab Headers -->
            <div class="flex overflow-x-auto border-b border-slate-100 hide-scrollbar px-2 bg-slate-50/50">
                <button onclick="switchTab('informasi')" id="btn-informasi" class="px-6 py-4 text-sm font-bold border-b-2 border-brand text-brand whitespace-nowrap transition-all focus:outline-none flex items-center gap-2">
                    <i class="fas fa-id-card"></i> Informasi Akun
                </button>
                <button onclick="switchTab('reservasi')" id="btn-reservasi" class="px-6 py-4 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 whitespace-nowrap transition-all focus:outline-none flex items-center gap-2">
                    <i class="fas fa-history"></i> Riwayat Reservasi Terakhir
                </button>
                <button onclick="switchTab('review')" id="btn-review" class="px-6 py-4 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-700 whitespace-nowrap transition-all focus:outline-none flex items-center gap-2">
                    <i class="fas fa-star"></i> Review Saya
                </button>
            </div>

            <div class="p-6 md:p-8">
                
                <!-- TAB 1: INFORMASI AKUN -->
                <div id="tab-informasi" class="tab-content active">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-user-circle text-brand"></i> Detail Akun
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                        <!-- Data Item -->
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap</p>
                            <p class="text-sm font-bold text-slate-800 bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-100">{{ $user['name'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Alamat Email</p>
                            <p class="text-sm font-bold text-slate-800 bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-100">{{ $user['email'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor Handphone (WhatsApp)</p>
                            <p class="text-sm font-bold text-slate-800 bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-100">{{ $user['phone'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Bergabung</p>
                            <p class="text-sm font-bold text-slate-800 bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-100">{{ $user['joined'] }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Status Akun</p>
                            <div class="bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-100">
                                <span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded text-xs font-bold border border-emerald-200 shadow-sm flex items-center w-max gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ $user['status'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: RESERVASI TERBARU -->
                <div id="tab-reservasi" class="tab-content">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i class="fas fa-history text-brand"></i> Reservasi Terbaru
                        </h3>
                        <a href="{{ route('pelanggan.riwayat') }}" class="text-sm font-bold text-brand hover:underline">Lihat Semua &rarr;</a>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($reservasis as $res)
                        <div class="border border-slate-100 rounded-2xl p-5 hover:bg-slate-50 transition-colors flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0
                                    {{ $res['status'] == 'selesai' ? 'bg-emerald-50 text-emerald-500' : 
                                      ($res['status'] == 'proses' ? 'bg-amber-50 text-amber-500' : 'bg-red-50 text-red-500') }}">
                                    @if($res['status'] == 'selesai')
                                        <i class="fas fa-check-circle text-xl"></i>
                                    @elseif($res['status'] == 'proses')
                                        <i class="fas fa-cogs text-xl"></i>
                                    @else
                                        <i class="fas fa-times-circle text-xl"></i>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800">{{ $res['layanan'] }}</h4>
                                    <p class="text-sm text-slate-500 mt-0.5"><i class="fas fa-map-marker-alt text-slate-300 mr-1"></i> {{ $res['bengkel'] }}</p>
                                    <p class="text-xs font-bold text-slate-400 mt-1">{{ $res['tanggal'] }}</p>
                                </div>
                            </div>
                            
                            <div class="text-right flex md:flex-col items-center md:items-end justify-between md:justify-center">
                                <!-- Status Badge -->
                                @if($res['status'] == 'selesai')
                                    <span class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold shadow-sm">Selesai</span>
                                @elseif($res['status'] == 'proses')
                                    <span class="bg-amber-100 border border-amber-200 text-amber-700 px-3 py-1 rounded-full text-xs font-bold shadow-sm">Sedang Diproses</span>
                                @else
                                    <span class="bg-red-100 border border-red-200 text-red-700 px-3 py-1 rounded-full text-xs font-bold shadow-sm">Dibatalkan</span>
                                @endif
                                
                                <button class="mt-0 md:mt-2 text-sm font-bold text-slate-500 hover:text-brand transition-colors">Detail</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- TAB 3: REVIEW SAYA -->
                <div id="tab-review" class="tab-content">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-star text-brand"></i> Review & Ulasan
                    </h3>
                    
                    <div class="space-y-4">
                        @foreach($reviews as $rev)
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 relative group">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-bold text-slate-800">{{ $rev['bengkel'] }}</h4>
                                    <p class="text-xs font-bold text-slate-400 mt-0.5">{{ $rev['tanggal'] }}</p>
                                </div>
                                <div class="flex gap-1 text-amber-400 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rev['rating'])
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star text-slate-300"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <p class="text-sm text-slate-600 italic">"{{ $rev['komentar'] }}"</p>
                            
                            <!-- Action overlay (opsional) -->
                            <button class="absolute top-6 right-6 opacity-0 group-hover:opacity-100 text-slate-400 hover:text-brand transition-all">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
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
