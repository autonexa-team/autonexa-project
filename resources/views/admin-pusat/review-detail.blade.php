{{-- resources/views/admin-pusat/review-detail.blade.php --}}
@extends('layout.admin')
@section('title', 'Detail Review')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-review.css') }}">
@endpush

@section('content')

{{-- ── BREADCRUMB + HEADER ─────────────────────────────────────── --}}
<div class="rd-header">
    <div class="rd-header-left">
        <nav class="rd-breadcrumb">
            <a href="{{ route('admin-pusat.dashboard') }}" class="rd-bc-item">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
            <span class="rd-bc-sep"><i class="bi bi-chevron-right"></i></span>
            <a href="{{ route('admin-pusat.review') }}" class="rd-bc-item">Review</a>
            <span class="rd-bc-sep"><i class="bi bi-chevron-right"></i></span>
            <span class="rd-bc-active">Detail Review</span>
        </nav>
        <h1 class="rd-title" style="margin-top:8px;">Detail Review</h1>
        <p class="rd-sub">
            Review dari <strong style="color:var(--foreground);">{{ $review->user->name }}</strong>
            · {{ \Carbon\Carbon::parse($review->created_at)->translatedFormat('d M Y, H:i') }}
        </p>
    </div>
    <div class="rd-header-actions">
        <a href="{{ route('admin-pusat.review') }}" class="rd-btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

{{-- ── MAIN LAYOUT ─────────────────────────────────────────────── --}}
<div class="rd-layout">

    {{-- ════════════════════ KOLOM KIRI ════════════════════ --}}
    <div class="rd-col-main">

        {{-- ── RATING HERO CARD ── --}}
        <div class="rd-hero-card {{ $ratingClass }}-card">
            <div class="rd-hero-body">

                {{-- Avatar + User --}}
                <div class="rd-user-row">
                    <div class="rd-avatar">{{ $initials }}</div>
                    <div class="rd-user-info">
                        <div class="rd-user-name">{{ $review->user->name }}</div>
                        <div class="rd-user-meta">
                            <i class="bi bi-envelope"></i> {{ $review->user->email }}
                        </div>
                        <div class="rd-user-meta">
                            <i class="bi bi-telephone"></i> {{ $review->user->phone ?? '-' }}
                        </div>
                    </div>
                    <div class="rd-rating-big">
                        <div class="rd-rating-num">{{ $rating }}.0</div>
                        <div class="rd-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="rd-star {{ $i <= $rating ? 'rd-star-on' : 'rd-star-off' }}">★</span>
                            @endfor
                        </div>
                        <div class="rd-rating-label {{ $ratingClass }}-label">{{ $ratingLabel }}</div>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="rd-divider"></div>

                {{-- Komentar --}}
                <div class="rd-comment-section">
                    <div class="rd-section-label">
                        <i class="bi bi-chat-quote-fill"></i> Komentar
                    </div>
                    @if($review->komentar)
                        <blockquote class="rd-comment">
                            "{{ $review->komentar }}"
                        </blockquote>
                    @else
                        <div class="rd-comment-empty">
                            <i class="bi bi-chat-left-dots"></i>
                            Pelanggan tidak meninggalkan komentar
                        </div>
                    @endif
                </div>

                {{-- Foto (jika ada) --}}
                @if($review->foto)
                    <div class="rd-foto-section">
                        <div class="rd-section-label">
                            <i class="bi bi-images"></i> Foto Ulasan
                        </div>
                        <img
                            src="{{ asset('storage/' . $review->foto) }}"
                            alt="Foto review"
                            class="rd-foto"
                        >
                    </div>
                @endif

                {{-- Meta footer --}}
                <div class="rd-meta-footer">
                    <span class="rd-meta-item">
                        <i class="bi bi-clock"></i>
                        Ditulis {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}
                    </span>
                    <span class="rd-meta-item">
                        <i class="bi bi-hash"></i>
                        ID Review: RVW-{{ str_pad($review->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

            </div>
        </div>

        {{-- ── INFORMASI BENGKEL ── --}}
        <div class="rd-info-card">
            <div class="rd-info-card-header">
                <div class="rd-info-icon si-orange"><i class="bi bi-shop"></i></div>
                <h2 class="rd-info-title">Informasi Bengkel</h2>
            </div>
            <div class="rd-info-body">
                <div class="rd-info-row">
                    <span class="rd-info-label">Nama Bengkel</span>
                    <span class="rd-info-value rd-info-bold">{{ $review->bengkel->nama ?? '-' }}</span>
                </div>
                <div class="rd-info-row">
                    <span class="rd-info-label">Alamat</span>
                    <span class="rd-info-value">{{ $review->bengkel->alamat ?? '-' }}</span>
                </div>
                <div class="rd-info-row">
                    <span class="rd-info-label">Telepon</span>
                    <span class="rd-info-value">{{ $review->bengkel->telepon ?? '-' }}</span>
                </div>
            </div>
        </div>

        {{-- ── DETAIL RESERVASI ── --}}
        @if(isset($review->reservasi))
        <div class="rd-info-card">
            <div class="rd-info-card-header">
                <div class="rd-info-icon si-blue"><i class="bi bi-calendar-check"></i></div>
                <h2 class="rd-info-title">Detail Reservasi</h2>
                <span class="rd-reservasi-id">{{ $review->reservasi->id }}</span>
            </div>
            <div class="rd-info-body">
                <div class="rd-info-row">
                    <span class="rd-info-label">Layanan</span>
                    <span class="rd-info-value rd-info-bold">{{ $review->reservasi->keluhan }}</span>
                </div>
                <div class="rd-info-row">
                    <span class="rd-info-label">Tanggal Servis</span>
                    <span class="rd-info-value">
                        {{ \Carbon\Carbon::parse($review->reservasi->tanggal)->translatedFormat('l, d M Y') }}
                    </span>
                </div>
                <div class="rd-info-row">
                    <span class="rd-info-label">Mekanik</span>
                    <span class="rd-info-value">{{ $review->reservasi->mekanik->name ?? '-' }}</span>
                </div>
                <div class="rd-info-row">
                    <span class="rd-info-label">Status</span>
                    <span class="rd-info-value">
                        <span class="status-badge badge-done">Selesai</span>
                    </span>
                </div>
                <div class="rd-info-row rd-info-row-total">
                    <span class="rd-info-label">Total Biaya</span>
                    <span class="rd-info-value rd-info-nominal">
                        Rp {{ number_format($review->reservasi->total_biaya, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- ════════════════════ KOLOM KANAN ════════════════════ --}}
    <div class="rd-col-side">

        {{-- ── SKOR VISUAL ── --}}
        <div class="rd-score-card">
            <div class="rd-score-title">Skor Rating</div>
            <div class="rd-score-num {{ $ratingClass }}-text">{{ $rating }}.0</div>
            <div class="rd-score-stars">
                @for($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= $rating ? 'rd-star-on' : 'rd-star-off' }}">★</span>
                @endfor
            </div>
            <div class="rd-score-label">dari 5 bintang</div>

            {{-- Bar bintang per level --}}
            <div class="rd-star-breakdown">
                @for($s = 5; $s >= 1; $s--)
                    <div class="rd-star-row">
                        <span class="rd-star-lv">{{ $s }} <span class="rd-star-on" style="font-size:11px;">★</span></span>
                        <div class="rd-star-bar-track">
                            <div class="rd-star-bar-fill {{ $s === $rating ? 'bar-fill-active' : 'bar-fill-dim' }}"
                                 style="width:{{ $s === $rating ? '100' : '0' }}%"></div>
                        </div>
                        <span class="rd-star-cnt">{{ $s === $rating ? 1 : 0 }}</span>
                    </div>
                @endfor
            </div>
        </div>

        {{-- ── INFO PELANGGAN ── --}}
        <div class="rd-side-card">
            <div class="rd-side-header">
                <i class="bi bi-person-circle"></i> Info Pelanggan
            </div>
            <div class="rd-side-body">
                <div class="rd-side-avatar">{{ $initials }}</div>
                <div class="rd-side-name">{{ $review->user->name }}</div>
                <div class="rd-side-meta">{{ $review->user->email }}</div>
                <div class="rd-side-meta">{{ $review->user->phone ?? '-' }}</div>
                <div class="rd-side-since">
                    <i class="bi bi-calendar3"></i>
                    Bergabung {{ \Carbon\Carbon::parse($review->user->created_at)->translatedFormat('M Y') }}
                </div>
                <div class="rd-side-divider"></div>
                <div class="rd-side-stat-row">
                    <div class="rd-side-stat">
                        <span class="rd-side-stat-num">{{ $riwayatReview->count() + 1 }}</span>
                        <span class="rd-side-stat-lbl">Total Review</span>
                    </div>
                    <div class="rd-side-stat">
                        <span class="rd-side-stat-num">
                            {{ number_format($riwayatReview->push((object)['rating'=>$rating])->avg('rating'), 1) }}
                        </span>
                        <span class="rd-side-stat-lbl">Avg Rating</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── RIWAYAT REVIEW ── --}}
        <div class="rd-side-card">
            <div class="rd-side-header">
                <i class="bi bi-clock-history"></i> Riwayat Review
            </div>
            <div class="rd-history-list">
                @forelse($riwayatReview->take(3) as $rv)
                <a href="{{ route('admin-pusat.review.show', $rv->id) }}" class="rd-history-item">
                    <div class="rd-history-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $rv->rating ? 'rd-star-on' : 'rd-star-off' }}"
                                  style="font-size:11px;">★</span>
                        @endfor
                    </div>
                    <div class="rd-history-bengkel">{{ $rv->bengkel->nama }}</div>
                    <div class="rd-history-comment">{{ Str::limit($rv->komentar, 55) }}</div>
                    <div class="rd-history-date">
                        {{ \Carbon\Carbon::parse($rv->created_at)->translatedFormat('d M Y') }}
                    </div>
                </a>
                @empty
                <div class="rd-history-empty">Belum ada riwayat review lain</div>
                @endforelse
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/admin-review.js') }}"></script>
@endpush