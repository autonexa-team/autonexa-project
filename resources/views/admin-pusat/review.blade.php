{{-- resources/views/admin-pusat/review.blade.php --}}
@extends('layout.admin')
@section('title', 'Manajemen Review')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-review.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/admin-review.js') }}"></script>
@endpush

@section('content')

{{-- Base URL untuk navigasi ke detail (jangan hapus) --}}
<input type="hidden" id="reviewBaseUrl" value="{{ url('admin-pusat/review') }}">

{{-- ===== HEADER ===== --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Review</h1>
        <p class="page-sub">Monitoring kualitas layanan bengkel berdasarkan ulasan pelanggan</p>
    </div>
    <div class="review-total-badge">
        <i class="bi bi-star-fill"></i>
        {{ number_format($totalReview) }} Total Review
    </div>
</div>

{{-- ===== STAT CARDS ===== --}}
<div class="stat-grid stat-grid-4">
    <div class="stat-card">
        <div class="stat-icon-wrap si-orange"><i class="bi bi-chat-square-text"></i></div>
        <div class="stat-body">
            <div class="stat-label">Total Review</div>
            <div class="stat-value count-up" data-target="{{ $totalReview }}">0</div>
            <div class="stat-trend trend-neutral">Dari semua bengkel</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap si-amber"><i class="bi bi-star-half"></i></div>
        <div class="stat-body">
            <div class="stat-label">Rating Rata-rata</div>
            <div class="stat-value count-up" data-target="{{ $avgRating }}">0</div>
            <div class="stat-trend trend-neutral">Skala 1–5 bintang</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap si-blue"><i class="bi bi-calendar-day"></i></div>
        <div class="stat-body">
            <div class="stat-label">Review Hari Ini</div>
            <div class="stat-value count-up" data-target="{{ $reviewHariIni }}">0</div>
            <div class="stat-trend trend-neutral">Masuk hari ini</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap si-green"><i class="bi bi-trophy"></i></div>
        <div class="stat-body">
            <div class="stat-label">Bengkel Terbaik</div>
            <div class="stat-value stat-value-sm">
                {{ Str::limit($bengkelTerbaik->nama ?? '-', 14) }}
            </div>
            <div class="stat-trend trend-neutral">
                Rating {{ number_format($bengkelTerbaik->reviews_avg_rating ?? 0, 1) }} ⭐
            </div>
        </div>
    </div>
</div>

{{-- ===== FILTER ===== --}}
<div class="filter-card">
    <form method="GET" action="{{ route('admin-pusat.review') }}" id="filterForm">
        <div class="filter-row">

            {{-- Search --}}
            <div class="search-wrap">
                <i class="bi bi-search search-icon"></i>
                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="Cari nama pelanggan atau komentar..."
                    value="{{ request('search') }}"
                >
            </div>

            {{-- Filter Bengkel --}}
            <select name="bengkel_id" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Bengkel</option>
                @foreach($bengkels as $b)
                    <option value="{{ $b->id }}"
                        {{ request('bengkel_id') == $b->id ? 'selected' : '' }}>
                        {{ $b->nama }}
                    </option>
                @endforeach
            </select>

            {{-- Filter Rating --}}
            <select name="rating" class="filter-select" onchange="this.form.submit()">
                <option value="">Semua Rating</option>
                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                        {{ str_repeat('★', $i) . str_repeat('☆', 5 - $i) }} ({{ $i }})
                    </option>
                @endfor
            </select>

            {{-- Sort --}}
            <select name="sort" class="filter-select" onchange="this.form.submit()">
                <option value="terbaru"  {{ request('sort', 'terbaru') === 'terbaru'  ? 'selected' : '' }}>Terbaru</option>
                <option value="tertinggi"{{ request('sort') === 'tertinggi' ? 'selected' : '' }}>Rating Tertinggi</option>
                <option value="terendah" {{ request('sort') === 'terendah'  ? 'selected' : '' }}>Rating Terendah</option>
            </select>

            {{-- Reset --}}
            <a href="{{ route('admin-pusat.review') }}" class="btn-reset">
                <i class="bi bi-arrow-counterclockwise"></i> Reset
            </a>

            <button type="submit" class="btn-filter-submit">
                <i class="bi bi-search"></i> Cari
            </button>
        </div>
    </form>
</div>

{{-- ===== COUNT INFO ===== --}}
<div class="review-count-bar">
    <span class="review-count-text">
        Menampilkan
        <strong>
            {{ $reviews->count() > 0 ? 1 : 0 }}–{{ $reviews->count() }}
        </strong>
        dari <strong>{{ $reviews->count() }}</strong> review
    </span>
    @if(request()->hasAny(['search', 'bengkel_id', 'rating', 'sort']))
        <span class="filter-active-badge">
            <i class="bi bi-funnel-fill"></i> Filter aktif
        </span>
    @endif
</div>

{{-- ===== REVIEW LIST ===== --}}
<div class="review-list">
    @forelse($reviews as $review)
    @php
        $rating     = $review->rating;
        $ratingClass = $rating >= 4 ? 'rating-hi' : ($rating >= 3 ? 'rating-mid' : 'rating-low');
        $commentShort = Str::limit($review->komentar ?? '', 120);
        $initials   = strtoupper(substr($review->user->name ?? 'U', 0, 1))
                    . strtoupper(substr(explode(' ', $review->user->name ?? 'U ')[1] ?? '', 0, 1));
    @endphp
    <!-- klik detail review -->
    <div class="review-card {{ $ratingClass }}-card" 
        onclick="window.location='{{ route('admin-pusat.review.show', $review->id) }}'"style="cursor:pointer;">

        {{-- Avatar --}}
        <div class="review-avatar">{{ $initials }}</div>

        {{-- Body --}}
        <div class="review-body">
            <div class="review-top">
                <div class="review-user-info">
                    <div class="review-user-name">{{ $review->user->name ?? '-' }}</div>
                    <div class="review-meta">
                        <span class="review-bengkel-tag">
                            <i class="bi bi-shop"></i>
                            {{ $review->bengkel->nama ?? '-' }}
                        </span>
                        <span class="review-date">
                            · {{ \Carbon\Carbon::parse($review->created_at)->translatedFormat('d M Y, H:i') }}
                        </span>
                    </div>
                </div>

                {{-- Stars + Badge --}}
                <div class="review-stars-wrap">
                    <div class="review-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= $rating ? 'star-filled' : 'star-empty' }}">★</span>
                        @endfor
                    </div>
                    <span class="rating-pill {{ $ratingClass }}-pill">
                        {{ number_format($rating, 1) }}
                    </span>
                </div>
            </div>

            {{-- Komentar --}}
            @if($review->komentar)
                <div class="review-comment">
                    "{{ $commentShort }}"
                </div>
            @else
                <div class="review-comment review-comment-empty">
                    <i class="bi bi-chat-left-dots"></i> Tidak ada komentar
                </div>
            @endif

            {{-- Reservasi info --}}
            @if(isset($review->reservasi))
                <div class="review-reservasi-tag">
                    <i class="bi bi-calendar-check"></i>
                    Layanan: {{ $review->reservasi->keluhan ?? 'Servis Umum' }}
                </div>
            @endif

            {{-- Foto --}}
            @if($review->fotos && $review->fotos->isNotEmpty())
                <div style="display:flex; flex-wrap:wrap; gap:6px; margin-top:8px;">
                    @foreach($review->fotos->take(5) as $foto)
                        <img src="{{ asset('storage/' . $foto->foto) }}"
                            alt="Foto review"
                            style="width:60px; height:60px; object-fit:cover; border-radius:6px; border:1px solid #e5e7eb;">
                    @endforeach
                </div>
            @endif

        </div>
    </div>
    @empty

    {{-- Empty State --}}
    <div class="empty-state-card">
        <div class="empty-state-icon">
            <i class="bi bi-star"></i>
        </div>
        <p class="empty-state-title">Belum ada review</p>
        <p class="empty-state-sub">
            @if(request()->hasAny(['search', 'bengkel_id', 'rating']))
                Tidak ada review yang sesuai filter. Coba ubah atau reset filter.
            @else
                Review dari pelanggan akan muncul di sini setelah mereka menyelesaikan servis.
            @endif
        </p>
        @if(request()->hasAny(['search', 'bengkel_id', 'rating']))
            <a href="{{ route('admin-pusat.review') }}" class="btn-reset" style="margin-top:12px;">
                <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
            </a>
        @endif
    </div>

    @endforelse
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    const reviewList = document.querySelector('.review-list');

    searchInput.addEventListener('input', function() {
        const params = new URLSearchParams(new FormData(document.getElementById('filterForm')));
        
        fetch('{{ route("admin-pusat.review") }}?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newList = doc.querySelector('.review-list');
            if (newList) reviewList.innerHTML = newList.innerHTML;
        });
    });
});
</script>
@endpush

@endsection