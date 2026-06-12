{{-- views/pelanggan/profile.blade.php --}}

@extends('layout.app')
@section('title', 'Profil Saya')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pelanggan-profile.css') }}">
@endpush

@section('content')
@if(session('success'))
<div id="successModal"
     class="fixed top-20 left-0 right-0 bottom-0 z-[9999] flex items-center justify-center">

    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative bg-white rounded-xl px-5 py-4 shadow-2xl text-center max-w-[250px]"
         style="transform: translateY(60px);">

        <div class="text-green-500 text-4xl mb-2">
            <i class="fas fa-check-circle"></i>
        </div>

            <p class="text-sm font-semibold text-slate-700">
                {{ session('success') }}
            </p>
    </div>
</div>
@endif

<div class="profile-page bg-slate-50 min-h-screen py-10">
    <div class="profile-container max-w-5xl mx-auto px-4">
         <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8"></div>
        
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-brand font-bold text-sm mb-6 transition-colors animate-slide-up">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>

            <div class="profile-card mb-8 animate-slide-up">
                <div class="profile-cover h-32 bg-gradient-to-r from-orange-300 to-orange-500 relative">
                    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20"></div>
                    <div class="absolute right-0 top-0 w-64 h-full bg-brand/20 blur-3xl"></div>
                </div>
            
                <div class="profile-header-content">
                    <div class="profile-info-wrapper">
                        <div class="profile-avatar-wrapper">

                            <div class="profile-avatar">
                                <form action="{{ route('pelanggan.profile.update') }}"
                                    method="POST"
                                    enctype="multipart/form-data"
                                    id="form-foto">

                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="name" value="{{ $user->name }}">

                                    <img src="{{ asset('storage/' . $user->foto) }}" class="foto-profil">

                                    <input type="file" name="foto" id="fotoInput" hidden accept="image/*">
                                </form>
                            </div>
                            
                            <div class="avatar-camera" onclick="document.getElementById('fotoInput').click();">
                                <i class="fas fa-camera"></i>
                            </div>

                        </div>

                    <div class="profile-text-info">

                        <div class="profile-user-top">
                            <h1>{{ $user->name }}</h1>

                            <span class="member-badge">
                                <i class="fas fa-crown"></i>
                                Gold Member
                            </span>
                        </div>

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

                <div class="profile-action-buttons flex items-center gap-2 w-auto">
                    <a href="{{ route('profil.kirim-reset') }}" 
                    class="bg-orange-500 text-white px-3 py-2 rounded-xl font-semibold flex items-center gap-2 whitespace-nowrap">
                        <i class="fas fa-lock"></i>
                        Ubah Password
                    </a>

                    <a href="{{ route('pelanggan.profile.edit') }}"
                    class="bg-orange-500 text-white px-3 py-2 rounded-xl font-semibold flex items-center gap-2 whitespace-nowrap">

                        <i class="fas fa-pen"></i>
                        Edit Profil
                    </a>   
                </div>
            </div>
        </div>

        <div class="profile-summary animate-slide-up"> 
            <div class="stats-wrapper">
                 <div class="stats-card bg-white rounded-2xl shadow-sm border border-slate-100">
                    <div class="w-10 h-10 text-xl bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center text-lg mb-1 group-hover:bg-brand/10 group-hover:text-brand transition-colors">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                        <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider">Total Reservasi</p>
                        <h3 class="text-base font-black text-slate-800">{{ $stats['total_reservasi'] }}</h3>
                    </div>

                    <div class="stats-card bg-white rounded-2xl shadow-sm border border-slate-100">
                        <div class="w-10 h-10 text-xl bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center text-lg mb-1 group-hover:bg-emerald-100 transition-colors">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider">Selesai</p>
                        <h3 class="text-base font-black  text-slate-800">{{ $stats['reservasi_selesai'] }}</h3>
                    </div>

                    <div class="stats-card bg-white rounded-2xl shadow-sm border border-slate-100">
                        <div class="w-10 h-10 text-xl bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center text-lg mb-1 group-hover:bg-blue-100 transition-colors">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider">Proses Aktif</p>
                        <h3 class="text-base font-black text-slate-800">{{ $stats['reservasi_aktif'] }}</h3>
                    </div>

                    <div class="stats-card bg-white rounded-2xl shadow-sm border border-slate-100">
                        <div class="w-10 h-10 text-xl bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center text-lg mb-1 group-hover:bg-amber-100 transition-colors">
                            <i class="fas fa-star"></i>
                        </div>
                            <p class="text-slate-400 text-[11px] font-bold uppercase tracking-wider">Total Review</p>
                            <h3 class="text-base font-black text-slate-800">{{ $stats['total_review'] }}</h3>
                        </div>
                    </div>

                    <div class="loyalty-card relative overflow-hidden">
                        <div class="absolute -right-10 -top-10 text-amber-500/10 text-9xl z-0 pointer-events-none"></div>

                        <div class="flex flex-col h-full">

                            <div class="flex flex-col h-full justify-between">

                                <!-- ATAS -->
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-orange-500 font-bold">Level Loyalty</p>
                                            <p class="text-slate-600 text-xs">Menuju Platinum</p>
                                        </div>

                                        <div class="flex items-center gap-2 text-amber-500 font-semibold">
                                            <i class="fas fa-crown"></i>
                                            <span>Gold Member</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- BAWAH -->
                                <div class="space-y-2">
                                    <p class="text-slate-600 text-xs">
                                        <strong>{{ $stats['total_reservasi'] }}</strong> dari 16 reservasi dibutuhkan
                                    </p>

                                    <div class="w-full bg-orange-100 rounded-full h-2">
                                        <div class="bg-orange-500 h-2 rounded-full"
                                            @style(['width: ' . ($progress ?? 0) . '%'])>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-tabs bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden animate-slide-up" style="animation-delay: 300ms;">
                                
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

                    <div class="p-6 md:p-8">
                        
                        <div id="tab-informasi" class="tab-content active">
                            <h3 class="detail-title"><i class="fas fa-user-circle"></i>Detail Akun</h3>

                            <div class="profile-detail-card">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                    <div>
                                        <label class="profile-detail-label">Nama Lengkap</label>
                                        <div class="profile-detail-box flex items-center gap-3 pl-4">
                                            <i class="fas fa-user w-5 text-brand"></i>
                                            <span class="profile-detail-value">{{ $user->name }}</span>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="profile-detail-label">Alamat Email</label>
                                        <div class="profile-detail-box"><i class="fas fa-envelope"></i>
                                            <span class="profile-detail-value">{{ $user->email }}</span>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="profile-detail-label">Nomor Handphone (WhatsApp)</label>
                                        <div class="profile-detail-box">
                                            <i class="fas fa-phone-alt"></i>
                                            <span class="profile-detail-value">{{ $user->phone ?? '-' }}</span>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="profile-detail-label">Tanggal Bergabung</label>
                                        <div class="profile-detail-box"><i class="fas fa-calendar-alt"></i>
                                            <span class="profile-detail-value">{{ $user->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="profile-detail-label">Status Akun</label>
                                        <div class="profile-detail-box">
                                            <i class="fas fa-user-check"></i>
                                            <span class="profile-detail-value">Aktif</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tab-reservasi" class="tab-content">
                            <div class="mb-8">
                                <h3 class="detail-title section-title">
                                    <i class="fas fa-history"></i>
                                    Riwayat Reservasi Terakhir
                                </h3>
                            </div>

                            <div class="space-y-4">
                                @foreach($reservasis as $res)
                                    <div class="reservasi-card">
                                        <div class="reservasi-left">
                                            <div class="reservasi-icon {{ $res->status == 'selesai' ? 'icon-selesai' : ($res->status == 'proses' ? 'icon-proses' : 'icon-batal') }}">
                                                @if($res->status == 'selesai')
                                                    <i class="fas fa-check"></i>
                                                @elseif($res->status == 'proses')
                                                    <i class="fas fa-cog"></i>
                                                @else
                                                    <i class="fas fa-times"></i>
                                                @endif
                                            </div>
                                            <div class="reservasi-info">
                                                <h4>Reservasi #{{ $res->id }}</h4>
                                                <p><i class="fas fa-map-marker-alt"></i> {{ $res->bengkel->nama }}</p>
                                                <p class="tanggal"><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($res->tanggal)->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="reservasi-right">
                                            @if($res->status == 'selesai')
                                                <span class="status-badge badge-selesai"><i class="fas fa-check-circle"></i> Selesai</span>
                                            @elseif($res->status == 'proses')
                                                <span class="status-badge badge-proses"><i class="fas fa-spinner"></i> Sedang Diproses</span>
                                            @else
                                                <span class="status-badge badge-batal"><i class="fas fa-times-circle"></i> Dibatalkan</span>
                                            @endif
                                            <a href="{{ route('riwayat-detail', $res->id) }}" class="btn-detail">
                                                Detail <i class="fas fa-chevron-right"></i>
                                            </a>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="riwayat-footer">
                                    <a href="{{ route('pelanggan.riwayat') }}" class="btn-lihat-semua">
                                        <i class="fas fa-list"></i>
                                        Lihat Semua
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div id="tab-review" class="tab-content">
                            <div class="review-box">
                                <h3 class="detail-title section-title">
                                    <i class="fas fa-star"></i>
                                    Review & Ulasan
                                </h3>

                                <div class="review-list">
                                    @foreach($reviews as $rev)
                                    <div class="review-item">
                                        <div class="review-header">
                                            <div class="review-user">
                                                <div class="review-avatar"><i class="fas fa-user"></i></div>
                                                <div>
                                                    <h4 class="review-title">{{ $rev->bengkel->nama }}</h4>
                                                    <p class="review-date">{{ $rev->created_at->format('d M Y') }}</p>
                                                </div>
                                            </div>
                                            <div class="review-rating">
                                                <div class="review-stars">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        {{ $i <= $rev->rating ? '★' : '☆' }}
                                                    @endfor
                                                </div>
                                                <span class="rating-badge">{{ number_format($rev->rating,1) }}</span>
                                            </div>
                                        </div>
                                            <div class="review-content">
                                                <i class="fas fa-quote-left"></i>
                                                <p class="review-text">{{ $rev->komentar }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function () {

            function switchTab(tabId) {
                document.querySelectorAll('.tab-content').forEach(el => {
                    el.classList.remove('active');
                });

                document.querySelectorAll('[id^="btn-"]').forEach(btn => {
                    btn.classList.remove('border-brand', 'text-brand');
                    btn.classList.add('border-transparent', 'text-slate-500');
                });

                document.getElementById('tab-' + tabId).classList.add('active');

                const activeBtn = document.getElementById('btn-' + tabId);
                activeBtn.classList.remove('border-transparent', 'text-slate-500');
                activeBtn.classList.add('border-brand', 'text-brand');
            }

            window.switchTab = switchTab;

            const fotoInput = document.getElementById('fotoInput');
            const formFoto = document.getElementById('form-foto');

            if (fotoInput) {
                fotoInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];

                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const img = document.querySelector('.profile-avatar img');
                            if (img) img.src = e.target.result;
                        }
                        reader.readAsDataURL(file);
                    }

                    if (formFoto) {
                        formFoto.submit();
                    }
                });
            }

            setTimeout(() => {
                const modal = document.getElementById('successModal');
                if(modal){ modal.style.display = 'none'; }
            }, 3000);

        });
        </script>
        @endsection

