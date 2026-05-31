@extends('layout.admin-cabang')

@section('content')

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
            <span class="bg-brand/10 text-brand font-bold px-3 py-1 rounded-full text-sm border border-brand/20">{{ $totalReview }} Total</span>
        </div>
        <p class="text-slate-500 text-sm font-medium">Monitoring kualitas layanan bengkel berdasarkan ulasan pelanggan</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin-cabang.review.export-pdf') }}" target="_blank"
        class="bg-brand hover:bg-brand/90 text-white px-5 py-2.5 rounded-xl shadow-sm transition-all font-semibold text-sm flex items-center gap-2">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
        <a href="{{ route('admin-cabang.review.export') }}"
        class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl shadow-md shadow-emerald-500/20 hover:shadow-lg hover:-translate-y-0.5 transition-all font-bold text-sm flex items-center gap-2">
            <i class="fas fa-file-csv"></i> Export CSV
        </a>
    </div>
</div>

<!-- Statistic Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 ease-out group relative overflow-hidden animate-fade-slide-up stagger-1">
        <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-br from-slate-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-500 text-sm font-semibold mb-1">Total Review</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="{{ $totalReview }}">0</h3>
            </div>
            <div class="w-12 h-12 bg-slate-50 text-slate-600 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-slate-100">
                <i class="fas fa-comments"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 ease-out group relative overflow-hidden animate-fade-slide-up stagger-2">
        <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-br from-orange-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-500 text-sm font-semibold mb-1">Rating Rata-rata</p>
                <div class="flex items-center gap-2">
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="{{ $avgRating }}" data-decimals="1" data-locale="en-US">0.0</h3>
                    <i class="fas fa-star text-amber-400 text-xl pb-1"></i>
                </div>
            </div>
            <div class="w-12 h-12 bg-orange-50 text-brand rounded-2xl flex items-center justify-center text-xl shadow-inner border border-orange-100">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 ease-out group relative overflow-hidden animate-fade-slide-up stagger-3">
        <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-br from-blue-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-500 text-sm font-semibold mb-1">Review Hari Ini</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="{{ $reviewHariIni }}">0</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-blue-100">
                <i class="fas fa-calendar-day"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<form method="GET" action="{{ route('admin-cabang.review') }}">
<div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 mb-6 grid grid-cols-1 md:grid-cols-12 gap-4 items-center animate-fade-slide-up stagger-4">
    <div class="md:col-span-6 flex items-center bg-slate-50 rounded-xl px-4 py-2 border border-slate-200 focus-within:border-brand/50 focus-within:ring-2 focus-within:ring-brand/20 transition-all">
        <i class="fas fa-search text-slate-400"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau isi komentar..." class="bg-transparent border-none outline-none ml-3 w-full text-sm font-medium text-slate-700">
    </div>

    <div class="md:col-span-3 relative">
        <select name="rating" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-brand focus:border-brand block w-full p-2.5 outline-none font-medium appearance-none pr-8" onchange="this.form.submit()">
            <option value="">Semua Rating</option>
            @for($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
            @endfor
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
            <i class="fas fa-chevron-down text-[10px]"></i>
        </div>
    </div>

    <div class="md:col-span-3 relative">
        <select name="sort" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-brand focus:border-brand block w-full p-2.5 outline-none font-medium appearance-none pr-8" onchange="this.form.submit()">
            <option value="terbaru" {{ request('sort', 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
            <option value="tertinggi" {{ request('sort') === 'tertinggi' ? 'selected' : '' }}>Rating Tertinggi</option>
            <option value="terendah" {{ request('sort') === 'terendah' ? 'selected' : '' }}>Rating Terendah</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
            <i class="fas fa-chevron-down text-[10px]"></i>
        </div>
    </div>
</div>
</form>

<!-- List Review -->
<div class="space-y-4 mb-8 animate-fade-slide-up stagger-5">
    @forelse($reviews as $review)
    @php
        $r = $review->rating;
        $cardBg = $r <= 2 ? 'bg-red-50/40 border-red-100' : 'bg-white border-slate-100';
        $ringColor = $r <= 2 ? 'ring-red-100' : 'ring-slate-100';
        $initials = strtoupper(substr($review->user->name ?? 'U', 0, 1))
                  . strtoupper(substr(explode(' ', $review->user->name ?? 'U ')[1] ?? '', 0, 1));
    @endphp
    <div class="{{ $cardBg }} rounded-2xl p-6 shadow-sm border hover:shadow-md transition-all flex flex-col md:flex-row gap-6 relative overflow-hidden">
        @if($r <= 2)
            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-red-500"></div>
        @endif

        <!-- Avatar & Name -->
        <div class="flex-shrink-0 flex items-start gap-4 md:w-64 border-b md:border-b-0 md:border-r {{ $r <= 2 ? 'border-red-100' : 'border-slate-100' }} pb-4 md:pb-0 md:pr-6">
            <div class="w-12 h-12 rounded-full bg-slate-100 overflow-hidden ring-2 {{ $ringColor }} flex-shrink-0 flex items-center justify-center font-bold text-slate-600">
                {{ $initials }}
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-base leading-tight">{{ $review->user->name ?? '-' }}</h4>
                <p class="text-xs text-slate-500 mt-1"><i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($review->created_at)->translatedFormat('d M Y, H:i') }}</p>
            </div>
        </div>

        <!-- Content -->
        <div class="flex-1">
            <div class="flex flex-wrap justify-between items-start mb-3 gap-2">
                <div class="flex items-center gap-2">
                    <div class="flex text-amber-400 text-sm gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= $r ? 'fas' : 'far' }} fa-star {{ $i > $r ? 'text-slate-300' : '' }}"></i>
                        @endfor
                    </div>
                    <span class="{{ $r <= 2 ? 'bg-red-100 text-red-700 border-red-200' : 'bg-slate-100 text-slate-600 border-slate-200' }} px-2 py-0.5 rounded text-xs font-bold border">{{ number_format($r, 1) }}</span>
                    @if($r <= 2)
                        <span class="bg-red-500 text-white px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider shadow-sm">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Perlu Perhatian
                        </span>
                    @endif
                </div>
                @if($review->reservasi)
                    <div class="{{ $r <= 2 ? 'bg-white border-red-100' : 'bg-slate-50 border-slate-100' }} px-2.5 py-1 rounded-lg border text-xs font-bold text-slate-500">
                        #{{ $review->reservasi->id }}
                    </div>
                @endif
            </div>

            <p class="text-slate-700 leading-relaxed font-medium text-sm">
                {{ $review->komentar ?? 'Tidak ada komentar.' }}
            </p>

            <!-- Klik ke detail -->
            <div class="mt-3">
                <a href="{{ route('admin-cabang.review.detail', $review->id) }}" class="text-xs font-bold text-brand hover:underline">
                    Lihat Detail →
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="flex flex-col items-center justify-center py-16 text-center bg-white rounded-2xl border border-slate-100 shadow-sm">
        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
            <i class="fas fa-comment-slash text-4xl text-slate-300"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-800">Belum ada review tersedia</h3>
        <p class="text-slate-500 text-sm mt-1 max-w-sm">Belum ada ulasan pelanggan yang masuk untuk bengkel ini.</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="flex flex-col md:flex-row gap-4 items-center justify-between mt-8">
    <span class="text-sm text-slate-500 font-medium">
        Menampilkan {{ $reviews->firstItem() ?? 0 }}–{{ $reviews->lastItem() ?? 0 }} dari {{ $reviews->total() }} ulasan
    </span>
    {{ $reviews->links() }}
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const counters = document.querySelectorAll(".counter-value");
    const duration = 1500;
    counters.forEach(counter => {
        const target = parseFloat(counter.getAttribute("data-target"));
        const decimals = parseInt(counter.getAttribute("data-decimals")) || 0;
        const locale = counter.getAttribute("data-locale") || "id-ID";
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const easeProgress = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            const current = easeProgress * target;
            counter.innerText = current.toLocaleString(locale, { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
            if (progress < 1) window.requestAnimationFrame(step);
            else counter.innerText = target.toLocaleString(locale, { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
        };
        window.requestAnimationFrame(step);
    });
});
</script>

@endsection