@extends('layout.admin-cabang')

@section('content')

<!-- Header Section -->
<!-- Custom Animations -->
<style>
    @keyframes fadeSlideUp {
        0% { opacity: 0; transform: translateY(10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-slide-up {
        animation: fadeSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    .stagger-1 { animation-delay: 50ms; }
    .stagger-2 { animation-delay: 100ms; }
    .stagger-3 { animation-delay: 150ms; }
    .stagger-4 { animation-delay: 200ms; }
    .stagger-5 { animation-delay: 250ms; }
</style>

<div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4 animate-fade-slide-up">
    <div>
        <div class="flex items-center gap-3 mb-2">
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Manajemen Review</h2>
            <span class="bg-brand/10 text-brand font-bold px-3 py-1 rounded-full text-sm border border-brand/20">842 Total</span>
        </div>
        <p class="text-slate-500 text-sm font-medium">Monitoring kualitas layanan seluruh cabang bengkel berdasarkan ulasan pelanggan</p>
    </div>
    
    <button class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-5 py-2.5 rounded-xl shadow-sm transition-all font-semibold text-sm flex items-center gap-2">
        <i class="fas fa-file-export"></i> Export Laporan
    </button>
</div>

<!-- Statistic Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card 1 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 ease-out group relative overflow-hidden animate-fade-slide-up stagger-1">
        <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-br from-slate-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-500 text-sm font-semibold mb-1">Total Review</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="842">0</h3>
            </div>
            <div class="w-12 h-12 bg-slate-50 text-slate-600 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-slate-100">
                <i class="fas fa-comments"></i>
            </div>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 ease-out group relative overflow-hidden animate-fade-slide-up stagger-2">
        <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-br from-orange-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-500 text-sm font-semibold mb-1">Rating Rata-rata</p>
                <div class="flex items-center gap-2">
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="4.7" data-decimals="1" data-locale="en-US">0.0</h3>
                    <i class="fas fa-star text-amber-400 text-xl pb-1"></i>
                </div>
            </div>
            <div class="w-12 h-12 bg-orange-50 text-brand rounded-2xl flex items-center justify-center text-xl shadow-inner border border-orange-100">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 ease-out group relative overflow-hidden animate-fade-slide-up stagger-3">
        <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-br from-blue-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-500 text-sm font-semibold mb-1">Review Hari Ini</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="15">0</h3>
                    <span class="text-emerald-500 text-xs font-bold mb-1.5 flex items-center"><i class="fas fa-arrow-up mr-1"></i> 12%</span>
                </div>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-blue-100">
                <i class="fas fa-calendar-day"></i>
            </div>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 ease-out group relative overflow-hidden animate-fade-slide-up stagger-4">
        <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-br from-emerald-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-500 text-sm font-semibold mb-1">Cabang Terbaik</p>
                <h3 class="text-xl font-bold text-slate-800 tracking-tight mt-1 leading-tight">Cabang Sudirman</h3>
                <p class="text-emerald-600 text-xs font-bold mt-1">4.9/5.0</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-emerald-100">
                <i class="fas fa-award"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 mb-6 grid grid-cols-1 md:grid-cols-12 gap-4 items-center animate-fade-slide-up stagger-4">
    <!-- Search -->
    <div class="md:col-span-5 flex items-center bg-slate-50 rounded-xl px-4 py-2 border border-slate-200 focus-within:border-brand/50 focus-within:ring-2 focus-within:ring-brand/20 transition-all">
        <i class="fas fa-search text-slate-400"></i>
        <input type="text" placeholder="Cari nama atau isi komentar..." class="bg-transparent border-none outline-none ml-3 w-full text-sm font-medium text-slate-700">
    </div>
    
    <!-- Filter Bengkel -->
    <div class="md:col-span-3 relative">
        <select class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-brand focus:border-brand block w-full p-2.5 outline-none font-medium appearance-none pr-8">
            <option selected value="semua">Semua Bengkel</option>
            <option value="1">Cabang Sudirman</option>
            <option value="2">Cabang Kebon Jeruk</option>
            <option value="3">Cabang Kelapa Gading</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
            <i class="fas fa-chevron-down text-[10px]"></i>
        </div>
    </div>

    <!-- Filter Rating -->
    <div class="md:col-span-2 relative">
        <select class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-brand focus:border-brand block w-full p-2.5 outline-none font-medium appearance-none pr-8">
            <option selected value="semua">Semua Rating</option>
            <option value="5">5 Bintang</option>
            <option value="4">4 Bintang</option>
            <option value="3">3 Bintang</option>
            <option value="2">2 Bintang</option>
            <option value="1">1 Bintang</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
            <i class="fas fa-chevron-down text-[10px]"></i>
        </div>
    </div>

    <!-- Sorting -->
    <div class="md:col-span-2 relative">
        <select class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-brand focus:border-brand block w-full p-2.5 outline-none font-medium appearance-none pr-8">
            <option selected value="terbaru">Terbaru</option>
            <option value="tertinggi">Rating Tertinggi</option>
            <option value="terendah">Rating Terendah</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
            <i class="fas fa-chevron-down text-[10px]"></i>
        </div>
    </div>
</div>

<!-- List Review (Cards) -->
<div class="space-y-4 mb-8 animate-fade-slide-up stagger-5">
    
    <!-- Review Item 1: 5 Stars -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all flex flex-col md:flex-row gap-6">
        <!-- Avatar & Name -->
        <div class="flex-shrink-0 flex items-start gap-4 md:w-64 border-b md:border-b-0 md:border-r border-slate-100 pb-4 md:pb-0 md:pr-6">
            <div class="w-12 h-12 rounded-full bg-slate-200 overflow-hidden ring-2 ring-slate-100 flex-shrink-0">
                <img src="https://ui-avatars.com/api/?name=Andi+Saputra&background=f1f5f9&color=475569" alt="User" class="w-full h-full object-cover">
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-base leading-tight">Andi Saputra</h4>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1"><i class="fas fa-store text-slate-300"></i> Cabang Sudirman</p>
                <p class="text-xs text-slate-400 mt-1"><i class="far fa-calendar-alt mr-1"></i> 18 Mei 2026, 14:30</p>
            </div>
        </div>
        
        <!-- Content -->
        <div class="flex-1">
            <div class="flex justify-between items-start mb-3">
                <div class="flex items-center gap-3">
                    <div class="flex text-amber-400 text-sm gap-0.5">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-bold border border-slate-200">5.0</span>
                </div>
                <div class="bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100 text-xs font-bold text-slate-500">
                    #RSV-20260518-001
                </div>
            </div>
            <p class="text-slate-700 leading-relaxed font-medium text-sm">
                Pelayanan sangat memuaskan. Mekaniknya ramah dan menjelaskan kerusakan dengan detail sebelum melakukan perbaikan. Harga juga transparan. Ruang tunggu sangat nyaman ada kopi gratis. Sukses terus AutoNexa!
            </p>
        </div>
    </div>

    <!-- Review Item 2: 4 Stars -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all flex flex-col md:flex-row gap-6">
        <div class="flex-shrink-0 flex items-start gap-4 md:w-64 border-b md:border-b-0 md:border-r border-slate-100 pb-4 md:pb-0 md:pr-6">
            <div class="w-12 h-12 rounded-full bg-slate-200 overflow-hidden ring-2 ring-slate-100 flex-shrink-0">
                <img src="https://ui-avatars.com/api/?name=Budi+Setiawan&background=f1f5f9&color=475569" alt="User" class="w-full h-full object-cover">
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-base leading-tight">Budi Setiawan</h4>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1"><i class="fas fa-store text-slate-300"></i> Cabang Kebon Jeruk</p>
                <p class="text-xs text-slate-400 mt-1"><i class="far fa-calendar-alt mr-1"></i> 17 Mei 2026, 09:15</p>
            </div>
        </div>
        
        <div class="flex-1">
            <div class="flex justify-between items-start mb-3">
                <div class="flex items-center gap-3">
                    <div class="flex text-amber-400 text-sm gap-0.5">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star text-slate-300"></i>
                    </div>
                    <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-bold border border-slate-200">4.0</span>
                </div>
                <div class="bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100 text-xs font-bold text-slate-500">
                    #RSV-20260517-042
                </div>
            </div>
            <p class="text-slate-700 leading-relaxed font-medium text-sm">
                Servis cepat dan mesin kembali halus. Hanya saja antrian agak panjang padahal sudah reservasi. Mungkin manajemen antriannya bisa ditingkatkan lagi.
            </p>
        </div>
    </div>

    <!-- Review Item 3: 2 Stars (Needs Attention) -->
    <div class="bg-red-50/40 rounded-2xl p-6 shadow-sm border border-red-100 hover:shadow-md transition-all flex flex-col md:flex-row gap-6 relative overflow-hidden">
        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-red-500"></div>
        <div class="flex-shrink-0 flex items-start gap-4 md:w-64 border-b border-red-100 md:border-b-0 md:border-r pb-4 md:pb-0 md:pr-6">
            <div class="w-12 h-12 rounded-full bg-white overflow-hidden ring-2 ring-red-100 flex-shrink-0">
                <img src="https://ui-avatars.com/api/?name=Citra+Kirana&background=fff&color=ef4444" alt="User" class="w-full h-full object-cover">
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-base leading-tight">Citra Kirana</h4>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1"><i class="fas fa-store text-slate-300"></i> Cabang Kelapa Gading</p>
                <p class="text-xs text-slate-400 mt-1"><i class="far fa-calendar-alt mr-1"></i> 16 Mei 2026, 16:45</p>
            </div>
        </div>
        
        <div class="flex-1">
            <div class="flex flex-wrap justify-between items-start mb-3 gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex text-amber-400 text-sm gap-0.5">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="far fa-star text-slate-300"></i>
                        <i class="far fa-star text-slate-300"></i>
                        <i class="far fa-star text-slate-300"></i>
                    </div>
                    <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs font-bold border border-red-200">2.0</span>
                    <span class="bg-red-500 text-white px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider ml-1 shadow-sm"><i class="fas fa-exclamation-triangle mr-1"></i> Perlu Perhatian</span>
                </div>
                <div class="bg-white px-2.5 py-1 rounded-lg border border-red-100 text-xs font-bold text-slate-500 shadow-sm">
                    #RSV-20260516-088
                </div>
            </div>
            <p class="text-slate-800 leading-relaxed font-semibold text-sm">
                Kecewa dengan pelayanan kali ini. AC masih kurang dingin setelah diservis, dan interior mobil ada bekas tangan hitam. Tolong mekaniknya lebih teliti dan bersih dalam bekerja!
            </p>
            <div class="mt-4 pt-3 border-t border-red-100 flex items-center gap-3">
                <button class="text-xs font-bold bg-white text-slate-700 border border-slate-200 hover:border-brand hover:text-brand px-3 py-1.5 rounded transition-colors shadow-sm">
                    <i class="fas fa-reply mr-1"></i> Balas ke Cabang
                </button>
            </div>
        </div>
    </div>

    <!-- Review Item 4: 5 Stars -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all flex flex-col md:flex-row gap-6">
        <div class="flex-shrink-0 flex items-start gap-4 md:w-64 border-b md:border-b-0 md:border-r border-slate-100 pb-4 md:pb-0 md:pr-6">
            <div class="w-12 h-12 rounded-full bg-slate-200 overflow-hidden ring-2 ring-slate-100 flex-shrink-0">
                <img src="https://ui-avatars.com/api/?name=Dimas+Anggara&background=f1f5f9&color=475569" alt="User" class="w-full h-full object-cover">
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-base leading-tight">Dimas Anggara</h4>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1"><i class="fas fa-store text-slate-300"></i> Cabang Sudirman</p>
                <p class="text-xs text-slate-400 mt-1"><i class="far fa-calendar-alt mr-1"></i> 15 Mei 2026, 11:20</p>
            </div>
        </div>
        
        <div class="flex-1">
            <div class="flex justify-between items-start mb-3">
                <div class="flex items-center gap-3">
                    <div class="flex text-amber-400 text-sm gap-0.5">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-bold border border-slate-200">5.0</span>
                </div>
                <div class="bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100 text-xs font-bold text-slate-500">
                    #RSV-20260515-012
                </div>
            </div>
            <p class="text-slate-700 leading-relaxed font-medium text-sm">
                Mantap, ganti aki dan spooring cepat sekali prosesnya. Staff sangat helpful dan proaktif memberikan saran perawatan berkala. The best bengkel in town!
            </p>
        </div>
    </div>
</div>

<!-- Empty State (Hidden by default) -->
<div class="hidden flex-col items-center justify-center py-16 text-center bg-white rounded-2xl border border-slate-100 shadow-sm">
    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
        <i class="fas fa-comment-slash text-4xl text-slate-300"></i>
    </div>
    <h3 class="text-lg font-bold text-slate-800">Belum ada review tersedia</h3>
    <p class="text-slate-500 text-sm mt-1 max-w-sm">Saat ini belum ada ulasan pelanggan yang masuk atau sesuai dengan filter pencarian Anda.</p>
</div>

<!-- Pagination -->
<div class="flex flex-col md:flex-row gap-4 items-center justify-between mt-8">
    <span class="text-sm text-slate-500 font-medium">Menampilkan 1-4 dari 842 ulasan</span>
    <div class="flex items-center gap-2">
        <button class="w-10 h-10 rounded-xl flex items-center justify-center border border-slate-200 text-slate-400 hover:bg-slate-50 transition-colors disabled:opacity-50" disabled>
            <i class="fas fa-chevron-left text-sm"></i>
        </button>
        <button class="w-10 h-10 rounded-xl flex items-center justify-center bg-brand text-white font-bold text-sm shadow-md shadow-brand/20">1</button>
        <button class="w-10 h-10 rounded-xl flex items-center justify-center border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors font-bold text-sm">2</button>
        <button class="w-10 h-10 rounded-xl flex items-center justify-center border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors font-bold text-sm">3</button>
        <span class="text-slate-400 px-1">...</span>
        <button class="w-10 h-10 rounded-xl flex items-center justify-center border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors font-bold text-sm">12</button>
        <button class="w-10 h-10 rounded-xl flex items-center justify-center border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
            <i class="fas fa-chevron-right text-sm"></i>
        </button>
    </div>
</div>

<!-- Custom Script for Number Animation -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const counters = document.querySelectorAll(".counter-value");
    const duration = 1500; // 1.5 seconds

    counters.forEach(counter => {
        const target = parseFloat(counter.getAttribute("data-target"));
        const decimals = parseInt(counter.getAttribute("data-decimals")) || 0;
        const prefix = counter.getAttribute("data-prefix") || "";
        const suffix = counter.getAttribute("data-suffix") || "";
        const locale = counter.getAttribute("data-locale") || "id-ID";
        
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            
            // easeOutExpo easing function
            const easeProgress = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            const current = easeProgress * target;
            
            counter.innerText = prefix + current.toLocaleString(locale, { minimumFractionDigits: decimals, maximumFractionDigits: decimals }) + suffix;
            
            if (progress < 1) {
                window.requestAnimationFrame(step);
            } else {
                counter.innerText = prefix + target.toLocaleString(locale, { minimumFractionDigits: decimals, maximumFractionDigits: decimals }) + suffix;
            }
        };
        
        window.requestAnimationFrame(step);
    });
});
</script>
@endsection
