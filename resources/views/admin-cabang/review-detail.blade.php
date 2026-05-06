@extends('layout.admin-cabang')

@php
    // Ganti variable dummy ini (1.0 - 5.0) untuk melihat perubahan dinamis UI
    $rating = 2.0; 
    
    // Logic Tema Warna berdasarkan Rating agar Tailwind mendeteksi class penuh
    if($rating >= 4) {
        $sentiment = 'Pelanggan Puas';
        $icon = 'fa-smile-beam';
        
        $colorBgBtn = 'bg-emerald-500';
        $colorHoverBtn = 'hover:bg-emerald-600';
        $colorShadowBtn = 'shadow-emerald-500/20';
        $colorBgGradFrom = 'from-emerald-50';
        $colorBgGradBl = 'from-emerald-100';
        $colorBorder = 'border-emerald-100';
        $colorTextTitle = 'text-emerald-800';
        $colorTextBig = 'text-emerald-600';
        $colorBadgeBg = 'bg-emerald-500';
        $colorSentimentBg = 'bg-emerald-50';
        $colorSentimentText = 'text-emerald-600';
        $colorQuoteHover = 'group-hover:text-emerald-100';
    } elseif($rating >= 3) {
        $sentiment = 'Netral';
        $icon = 'fa-meh';
        
        $colorBgBtn = 'bg-amber-500';
        $colorHoverBtn = 'hover:bg-amber-600';
        $colorShadowBtn = 'shadow-amber-500/20';
        $colorBgGradFrom = 'from-amber-50';
        $colorBgGradBl = 'from-amber-100';
        $colorBorder = 'border-amber-100';
        $colorTextTitle = 'text-amber-800';
        $colorTextBig = 'text-amber-600';
        $colorBadgeBg = 'bg-amber-500';
        $colorSentimentBg = 'bg-amber-50';
        $colorSentimentText = 'text-amber-600';
        $colorQuoteHover = 'group-hover:text-amber-100';
    } else {
        $sentiment = 'Perlu Perhatian';
        $icon = 'fa-frown';
        
        $colorBgBtn = 'bg-red-500';
        $colorHoverBtn = 'hover:bg-red-600';
        $colorShadowBtn = 'shadow-red-500/20';
        $colorBgGradFrom = 'from-red-50';
        $colorBgGradBl = 'from-red-100';
        $colorBorder = 'border-red-100';
        $colorTextTitle = 'text-red-800';
        $colorTextBig = 'text-red-600';
        $colorBadgeBg = 'bg-red-500';
        $colorSentimentBg = 'bg-red-50';
        $colorSentimentText = 'text-red-600';
        $colorQuoteHover = 'group-hover:text-red-100';
    }
@endphp

@section('content')

<!-- Custom Animation Styles -->
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
</style>

<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4 animate-fade-in-up">
    <div>
        <a href="{{ url('/admin-cabang/review') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-brand font-bold text-sm mb-4 transition-all duration-300 bg-white px-3 py-1.5 rounded-lg border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Review
        </a>
        <h2 class="text-3xl font-bold text-slate-800 tracking-tight flex items-center gap-3">
            Detail Review <span class="text-slate-400 font-medium text-xl bg-slate-100 px-2 py-0.5 rounded-lg">#RVW-882</span>
        </h2>
        <p class="text-slate-500 mt-2 text-sm font-medium">Analisis mendalam ulasan dan feedback pelanggan untuk perbaikan layanan</p>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex flex-wrap items-center gap-3">
        <button class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-4 py-2.5 rounded-xl shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 font-semibold text-sm flex items-center gap-2">
            <i class="fas fa-store"></i> Lihat Profil Cabang
        </button>
        <!-- Dynamic Action Button -->
        <button class="{{ $colorBgBtn }} {{ $colorHoverBtn }} text-white px-4 py-2.5 rounded-xl shadow-md {{ $colorShadowBtn }} hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 font-semibold text-sm flex items-center gap-2">
            <i class="fas {{ $rating <= 2 ? 'fa-exclamation-triangle' : 'fa-check-double' }}"></i> 
            {{ $rating <= 2 ? 'Tandai Perlu Tindakan' : 'Tandai Selesai' }}
        </button>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    
    <!-- Left Column: Rating, Customer, Bengkel, Reservasi -->
    <div class="xl:col-span-1 space-y-6">
        
        <!-- Highlight Rating Card (Dynamic Theme) -->
        <div class="bg-gradient-to-br {{ $colorBgGradFrom }} to-white rounded-2xl p-6 shadow-sm border {{ $colorBorder }} relative overflow-hidden text-center hover:shadow-lg hover:-translate-y-1 transition-all duration-300 animate-fade-in-up delay-100 group cursor-default">
            <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-bl {{ $colorBgGradBl }} to-transparent rounded-bl-full opacity-50 group-hover:scale-125 transition-transform duration-700 ease-out"></div>
            
            <p class="{{ $colorTextTitle }} font-bold uppercase tracking-widest text-xs mb-3 transition-colors">Tingkat Kepuasan</p>
            
            <div class="flex items-center justify-center gap-2 text-3xl mb-2">
                @for($i=1; $i<=5; $i++)
                    @if($i <= $rating)
                        <i class="fas fa-star text-amber-400 drop-shadow-sm hover:scale-125 hover:text-amber-500 transition-all duration-300 origin-center"></i>
                    @elseif($i - 0.5 == $rating)
                        <i class="fas fa-star-half-alt text-amber-400 drop-shadow-sm hover:scale-125 hover:text-amber-500 transition-all duration-300 origin-center"></i>
                    @else
                        <i class="far fa-star text-slate-300 drop-shadow-sm hover:scale-125 hover:text-amber-400 transition-all duration-300 origin-center"></i>
                    @endif
                @endfor
            </div>
            
            <h1 class="text-6xl font-black {{ $colorTextBig }} tracking-tighter mb-2 transition-colors">{{ number_format($rating, 1) }}</h1>
            
            <div class="inline-flex items-center gap-1.5 {{ $colorBadgeBg }} text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm mt-2 transform group-hover:scale-105 transition-transform duration-300">
                <i class="fas {{ $icon }}"></i> {{ $sentiment }}
            </div>
        </div>

        <!-- Customer Card -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-slate-100 overflow-hidden animate-fade-in-up delay-100">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center">
                    <i class="fas fa-user"></i>
                </div>
                <h3 class="font-bold text-slate-800">Informasi Pelanggan</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-4 mb-5">
                    <div class="w-14 h-14 rounded-full bg-slate-200 overflow-hidden ring-4 ring-slate-50 flex-shrink-0 group-hover:ring-blue-50 transition-all duration-300">
                        <img src="https://ui-avatars.com/api/?name=Citra+Kirana&background=fff&color=ef4444" alt="User" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-lg">Citra Kirana</h4>
                        <p class="text-slate-500 text-xs font-bold">Member sejak Jan 2025</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="group/item">
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1 group-hover/item:text-emerald-500 transition-colors">Nomor HP</p>
                        <p class="text-slate-800 font-bold text-sm">
                            <a href="#" class="text-emerald-500 hover:text-emerald-600 transition-colors flex items-center gap-2">
                                <i class="fab fa-whatsapp"></i> 0855-1122-3344
                            </a>
                        </p>
                    </div>
                    <div class="group/item">
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1 group-hover/item:text-brand transition-colors">Tanggal Review</p>
                        <p class="text-slate-800 font-bold text-sm flex items-center gap-2">
                            <i class="far fa-calendar-alt text-slate-400 group-hover/item:text-brand transition-colors"></i> 16 Mei 2026, 16:45 WIB
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bengkel Card -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-slate-100 overflow-hidden animate-fade-in-up delay-200">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center">
                    <i class="fas fa-store"></i>
                </div>
                <h3 class="font-bold text-slate-800">Lokasi Bengkel</h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="group/item hover:bg-slate-50 p-2 -mx-2 rounded-lg transition-colors cursor-pointer">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Nama Cabang</p>
                    <p class="text-slate-800 font-bold text-base">Cabang Kelapa Gading</p>
                </div>
                <div class="group/item hover:bg-slate-50 p-2 -mx-2 rounded-lg transition-colors cursor-pointer">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Kota</p>
                    <p class="text-slate-800 font-bold text-sm">Jakarta Utara</p>
                </div>
                <div class="pt-3 border-t border-slate-100">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Rating Rata-rata Cabang</p>
                    <div class="flex items-center gap-2 group/star cursor-pointer">
                        <span class="text-slate-800 font-bold text-lg group-hover/star:text-amber-500 transition-colors">4.2</span>
                        <div class="flex text-amber-400 text-xs group-hover/star:scale-110 transition-transform origin-left">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    <!-- Right Column: Komentar, Foto, Info Reservasi -->
    <div class="xl:col-span-2 space-y-6">
        
        <!-- Komentar Utama -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-slate-100 overflow-hidden animate-fade-in-up delay-200">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-orange-50 text-brand flex items-center justify-center transform group-hover:rotate-12 transition-transform duration-500">
                        <i class="fas fa-comment-dots text-lg"></i>
                    </div>
                    <h3 class="font-bold text-slate-800">Komentar Pelanggan</h3>
                </div>
                <!-- Dynamic Badge Sentiment -->
                <span class="{{ $colorSentimentBg }} border {{ $colorBorder }} {{ $colorSentimentText }} px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition-colors duration-300">
                    Sentimen {{ $rating <= 2 ? 'Negatif' : ($rating == 3 ? 'Netral' : 'Positif') }}
                </span>
            </div>
            <div class="p-8 group">
                <div class="relative">
                    <i class="fas fa-quote-left absolute -top-4 -left-4 text-slate-100 text-5xl {{ $colorQuoteHover }} transition-colors duration-500"></i>
                    <p class="relative z-10 text-slate-700 leading-relaxed font-medium text-lg italic pl-4 group-hover:text-slate-800 transition-colors">
                        "Kecewa dengan pelayanan kali ini. AC masih kurang dingin setelah diservis, dan interior mobil ada bekas tangan hitam di bagian dashboard. Tolong mekaniknya lebih teliti dan menjaga kebersihan saat bekerja!"
                    </p>
                </div>
            </div>
        </div>

        <!-- Foto Review -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-slate-100 overflow-hidden animate-fade-in-up delay-300">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-500 flex items-center justify-center">
                    <i class="fas fa-camera text-lg"></i>
                </div>
                <h3 class="font-bold text-slate-800">Lampiran Foto</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    <!-- Dummy Image 1 -->
                    <div class="aspect-square rounded-xl overflow-hidden shadow-sm border border-slate-200 group/img relative cursor-pointer hover:shadow-lg hover:ring-2 hover:ring-brand/50 transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1542261686-3023fb15822b?w=400&q=80" alt="Bekas kotoran" class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-700 ease-out">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white backdrop-blur-[1px]">
                            <i class="fas fa-search-plus text-3xl transform scale-50 group-hover/img:scale-100 transition-transform duration-300"></i>
                        </div>
                    </div>
                    <!-- Dummy Image 2 -->
                    <div class="aspect-square rounded-xl overflow-hidden shadow-sm border border-slate-200 group/img relative cursor-pointer hover:shadow-lg hover:ring-2 hover:ring-brand/50 transition-all duration-300">
                        <img src="https://images.unsplash.com/photo-1616423640778-28d1b53229bd?w=400&q=80" alt="Detail interior" class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-700 ease-out">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/img:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white backdrop-blur-[1px]">
                            <i class="fas fa-search-plus text-3xl transform scale-50 group-hover/img:scale-100 transition-transform duration-300"></i>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-4 font-bold flex items-center"><i class="fas fa-info-circle mr-1 text-slate-300"></i> Klik gambar untuk memperbesar resolusi penuh.</p>
            </div>
        </div>

        <!-- Info Reservasi Referensi -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-slate-100 overflow-hidden animate-fade-in-up delay-300">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                    <i class="fas fa-file-invoice text-lg"></i>
                </div>
                <h3 class="font-bold text-slate-800">Referensi Reservasi Terkait</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
                    <div class="hover:bg-slate-50 p-2 -mx-2 rounded-lg transition-colors">
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">ID Reservasi</p>
                        <a href="#" class="text-brand font-bold text-base hover:text-brand-dark hover:underline transition-all inline-flex items-center gap-1 group">
                            #RSV-20260516-088 <i class="fas fa-external-link-alt text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </a>
                    </div>
                    <div class="hover:bg-slate-50 p-2 -mx-2 rounded-lg transition-colors">
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Tanggal Servis</p>
                        <p class="text-slate-800 font-bold text-base">16 Mei 2026</p>
                    </div>
                    <div class="hover:bg-slate-50 p-2 -mx-2 rounded-lg transition-colors">
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">Layanan Utama</p>
                        <p class="text-slate-800 font-bold text-base">Servis AC & Interior</p>
                    </div>
                </div>
                
                <div class="bg-slate-50 rounded-xl p-5 border border-slate-100 hover:border-slate-300 transition-colors duration-300">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Keluhan Awal Pelanggan (Sebelum Servis)</p>
                    <div class="text-slate-700 font-medium text-sm flex gap-3">
                        <i class="fas fa-comment-alt text-slate-300 mt-0.5"></i>
                        <p>
                            "AC mobil terasa kurang dingin jika siang hari, anginnya pelan."
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

@endsection
