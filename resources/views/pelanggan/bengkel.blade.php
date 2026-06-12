{{-- views/pelanggan/bengkel/index.blade.php --}}

@extends('layout.app')
@section('title', 'Bengkel Motor')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/bengkel.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')

{{-- ===== HERO ===== --}}
<div class="bengkel-hero">
    <div class="hero-deco hero-deco-1"></div>
    <div class="hero-deco hero-deco-2"></div>

    <div class="hero-top">
        <div class="hero-left">
            <div class="hero-eyebrow">
                <i class="bi bi-tools"></i>
                Bengkel Motor Terpercaya
            </div>
            <h1 class="hero-title">Cari Bengkel <span>Pilihanmu!</span></h1>
            <p class="hero-subtitle">
                Temukan bengkel motor terpercaya di sekitarmu, baca ulasan rider lain, dan langsung booking servis.
            </p>
        </div>

        {{-- Ilustrasi motor SVG --}}
        <div class="hero-illustration">
            <svg viewBox="0 0 140 90" fill="none" xmlns="http://www.w3.org/2000/svg">
                <ellipse cx="48" cy="68" rx="16" ry="16" fill="rgba(255,255,255,0.08)" stroke="rgba(255,255,255,0.2)" stroke-width="1.5"/>
                <ellipse cx="48" cy="68" rx="8" ry="8" fill="rgba(255,106,0,0.5)"/>
                <ellipse cx="104" cy="68" rx="16" ry="16" fill="rgba(255,255,255,0.08)" stroke="rgba(255,255,255,0.2)" stroke-width="1.5"/>
                <ellipse cx="104" cy="68" rx="8" ry="8" fill="rgba(255,106,0,0.5)"/>
                <path d="M48 68 L70 40 L100 40 L104 68" stroke="rgba(255,255,255,0.25)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                <path d="M70 40 L60 55 L48 68" stroke="rgba(255,255,255,0.15)" stroke-width="2" fill="none"/>
                <rect x="68" y="33" width="30" height="14" rx="5" fill="rgba(255,106,0,0.35)" stroke="rgba(255,106,0,0.5)" stroke-width="1"/>
                <path d="M100 40 L112 36 M112 36 L116 38 M112 36 L116 34" stroke="rgba(255,255,255,0.4)" stroke-width="2" stroke-linecap="round"/>
                <path d="M60 58 L42 62" stroke="rgba(255,106,0,0.4)" stroke-width="3" stroke-linecap="round"/>
                <rect x="74" y="30" width="22" height="6" rx="3" fill="rgba(255,255,255,0.15)"/>
                <circle cx="122" cy="22" r="3" fill="rgba(255,106,0,0.6)"/>
                <circle cx="130" cy="35" r="2" fill="rgba(255,255,255,0.2)"/>
            </svg>
        </div>
    </div>

    <div class="hero-stats">
        <div class="hero-stat">
            <div class="hero-stat-num">{{ $totalBengkel ?? '24' }}<span>+</span></div>
            <div class="hero-stat-label">Bengkel motor</div>
        </div>
        <div class="hero-stat-divider"></div>
        <div class="hero-stat">
            <div class="hero-stat-num"><span>★</span> {{ number_format($avgRating ?? 4.8, 1) }}</div>
            <div class="hero-stat-label">Rating rata-rata</div>
        </div>
        <div class="hero-stat-divider"></div>
        <div class="hero-stat">
            <div class="hero-stat-num">{{ $totalReview ?? '1.2k' }}</div>
            <div class="hero-stat-label">Ulasan rider</div>
        </div>
    </div>
</div>

<div class="container-bengkel">
    {{-- ===== SEARCH & FILTER ===== --}}
    <div class="bengkel-search-section">
        <div class="search-row">
            <div class="search-input-wrap">
                <i class="bi bi-search search-icon"></i>
                <input
                    type="text"
                    id="searchBengkel"
                    class="search-input"
                    placeholder="Cari nama bengkel atau lokasi..."
                    value="{{ request('q') }}"
                    autocomplete="off">
            </div>
            <button type="button" class="btn-terdekat" id="btnTerdekat">
                <i class="bi bi-geo-alt-fill"></i>
                Bengkel Terdekat
            </button>
        </div>

        {{-- Filter chip khusus layanan motor --}}
        <div class="filter-chips" id="filterChips">
            <button class="chip active" data-filter="semua">
                <i class="bi bi-grid-3x3-gap"></i> Semua
            </button>
            <button class="chip" data-filter="terdekat" id="chipTerdekat">
                <i class="bi bi-geo-alt"></i> Terdekat
            </button>
            <button class="chip" data-filter="rating">
                <i class="bi bi-star"></i> Rating Tertinggi
            </button>
            <button class="chip" data-filter="ganti_oli">
                <i class="bi bi-droplet"></i> Ganti Oli
            </button>
            <button class="chip" data-filter="tune_up">
                <i class="bi bi-wrench"></i> Tune Up
            </button>
            <button class="chip" data-filter="ban">
                <i class="bi bi-circle"></i> Ganti Ban
            </button>
            <button class="chip" data-filter="kelistrikan">
                <i class="bi bi-lightning"></i> Kelistrikan
            </button>
            <button class="chip" data-filter="injeksi">
                <i class="bi bi-cpu"></i> Servis Injeksi
            </button>
        </div>
    </div>

    {{-- ===== MAIN LAYOUT ===== --}}
    <div class="bengkel-page-layout">

        {{-- KIRI: Grid Bengkel --}}
        <div class="bengkel-content">

            <div class="result-info">
                <span id="resultCount">{{ $bengkels->count() }} bengkel motor ditemukan</span>
                <div class="result-sort">
                    <label for="sortBy">Urutkan:</label>
                    <select id="sortBy" class="sort-select">
                        <option value="default">Default</option>
                        <option value="rating">Rating Tertinggi</option>
                        <option value="jarak">Jarak Terdekat</option>
                        <option value="ulasan">Ulasan Terbanyak</option>
                    </select>
                </div>
            </div>

            <div id="nearbyLoader" class="nearby-loader" style="display:none;">
                <div class="loader-spinner"></div>
                <span>Mencari bengkel motor terdekat...</span>
            </div>

            <div class="bengkel-grid" id="bengkelGrid">
                @forelse($bengkels as $bengkel)

                @php
                    $jamSekarang = now()->format('H:i');
                    $isOpen      = ($jamSekarang >= '08:00' && $jamSekarang <= '17:00');
                    $namaLayanan = $bengkel->layanan
                        ? strtolower(implode(',', $bengkel->layanan->pluck('nama')->toArray()))
                        : '';
                @endphp

                <div class="bengkel-card {{ $loop->index >= 4 ? 'bengkel-card--extra' : '' }}"
                    data-id="{{ $bengkel->id }}"
                    data-nama="{{ strtolower($bengkel->nama) }}"
                    data-alamat="{{ strtolower($bengkel->alamat) }}"
                    data-rating="{{ $bengkel->reviews_avg_rating ?? 0 }}"
                    data-ulasan="{{ $bengkel->reviews_count ?? 0 }}"
                    data-lat="{{ $bengkel->latitude ?? '' }}"
                    data-lng="{{ $bengkel->longitude ?? '' }}"
                    data-jarak=""
                    data-layanan="{{ $namaLayanan }}">

                    <div class="card-img-wrap">
                        <img
                            src="{{ $bengkel->foto
                                ? asset('assets/'.$bengkel->foto)
                                : asset('img/bengkel-default.jpg') }}"
                            class="card-img"
                            alt="{{ $bengkel->nama }}"
                            loading="lazy">
                        <span class="card-badge {{ $isOpen ? 'badge-open' : 'badge-closed' }}">
                            ● {{ $isOpen ? 'Buka' : 'Tutup' }}
                        </span>
                        <span class="card-badge-jarak" id="jarak-{{ $bengkel->id }}" style="display:none;"></span>
                    </div>

                    <div class="card-body">
                        <h3 class="card-nama">{{ $bengkel->nama }}</h3>
                        <p class="card-alamat">
                            <i class="bi bi-geo-alt"></i>
                            {{ $bengkel->alamat }}
                        </p>

                        <div class="card-meta">
                            <div class="card-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= round($bengkel->review_avg_rating ?? 0) ? '-fill star-on' : ' star-off' }}"></i>
                                @endfor
                                <span class="rating-val">
                                    {{ number_format($bengkel->review_avg_rating ?? 0, 1) }}
                                </span>
                                <span class="review-count">({{ $bengkel->reviews_count ?? 0 }} ulasan)</span>
                            </div>
                        </div>

                        @if($bengkel->layanan && $bengkel->layanan->count() > 0)
                        <div class="card-tags">
                            @foreach($bengkel->layanan->take(3) as $layanan)
                                <span class="card-tag">{{ $layanan->nama }}</span>
                            @endforeach
                            @if($bengkel->layanan->count() > 3)
                                <span class="card-tag card-tag-more">+{{ $bengkel->layanan->count() - 3 }} lainnya</span>
                            @endif
                        </div>
                        @endif
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('pelanggan.bengkel-detail', $bengkel->id) }}" class="btn-card-detail">
                            <i class="bi bi-info-circle"></i> Detail
                        </a>
                        <a
                            href="{{ $isOpen ? route('pelanggan.reservasi', ['bengkel_id' => $bengkel->id]) : '#' }}"
                            class="btn-card-booking {{ !$isOpen ? 'disabled' : '' }}"
                            {{ !$isOpen ? 'tabindex=-1 aria-disabled=true' : '' }}>
                            <i class="bi bi-calendar-plus"></i>
                            {{ $isOpen ? 'Booking Servis' : 'Sedang Tutup' }}
                        </a>
                    </div>
                </div>

                @empty
                <div class="empty-state">
                    <i class="bi bi-shop-window"></i>
                    <h4>Belum ada bengkel motor terdaftar</h4>
                    <p>Silakan coba lagi nanti</p>
                </div>
                @endforelse
            </div>

            <div class="empty-state" id="emptyStateFilter" style="display:none;">
                <i class="bi bi-search"></i>
                <h4>Bengkel motor tidak ditemukan</h4>
                <p>Coba kata kunci lain atau hilangkan filter</p>
            </div>

        </div>

        {{-- KANAN: Sidebar peta --}}
        <div class="bengkel-sidebar">
            <div class="map-panel">
                <div class="map-panel-header">
                    <div>
                        <div class="map-panel-title">
                            <i class="bi bi-map"></i> Peta Bengkel Motor
                        </div>
                        <div class="map-panel-sub" id="mapSubtitle">
                            Klik "Bengkel Terdekat" untuk navigasi
                        </div>
                    </div>
                    <button class="btn-expand-map" id="btnExpandMap" title="Perbesar peta">
                        <i class="bi bi-arrows-fullscreen"></i>
                    </button>
                </div>

                <div id="mapBengkel" class="map-area"></div>

                <div id="nearbyPanel" style="display:none;">
                    <div class="nearby-header">
                        <i class="bi bi-geo-alt-fill" style="color:var(--primary);"></i>
                        Terdekat dari lokasimu
                    </div>
                    <div class="nearby-list" id="nearbyList"></div>
                </div>
            </div>
        </div>

    </div>    
</div>

@endsection

@php
$bengkelData = $bengkels->map(function ($b) {
    return [
        'id' => $b->id,
        'nama' => $b->nama,
        'alamat' => $b->alamat,
        'rating' => round($b->reviews_avg_rating ?? 0, 1),
        'ulasan' => $b->reviews_count ?? 0,
        'lat' => $b->latitude,
        'lng' => $b->longitude,
        'layanan' => $b->layanan->isNotEmpty()
            ? strtolower($b->layanan->pluck('nama')->implode(','))
            : '',
        'url' => route('pelanggan.bengkel-detail', $b->id),
        'booking_url' => route('pelanggan.reservasi', ['bengkel_id' => $b->id]),
    ];
})->values();
@endphp

@push('scripts')

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const bengkelData = JSON.parse('{!! json_encode($bengkelData) !!}');

    // ── MAP ──
    const map = L.map('mapBengkel', { zoomControl: true }).setView([-6.9175, 107.6191], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    const orangeIcon = L.divIcon({
        className: '',
        html: `<div style="width:26px;height:26px;background:#ff6a00;
            border-radius:50% 50% 50% 0;transform:rotate(-45deg);
            border:2px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,.3);
            display:flex;align-items:center;justify-content:center;">
            <div style="width:7px;height:7px;background:#fff;border-radius:50%;transform:rotate(45deg);"></div>
        </div>`,
        iconSize: [26, 26], iconAnchor: [13, 26], popupAnchor: [0, -28],
    });

    bengkelData.forEach(b => {
        if (!b.lat || !b.lng) return;
        L.marker([b.lat, b.lng], { icon: orangeIcon }).addTo(map).bindPopup(`
            <div style="min-width:160px;font-family:system-ui,sans-serif;">
                <strong style="font-size:13px;color:#0f172a;">${b.nama}</strong><br>
                <small style="color:#666;">${b.alamat}</small><br>
                <div style="margin:5px 0;font-size:12px;color:#f59e0b;">
                    ★ ${b.rating} <span style="color:#999;">(${b.ulasan} ulasan)</span>
                </div>
                <a href="${b.booking_url}" style="display:inline-block;padding:5px 12px;
                    background:#ff6a00;color:#fff;border-radius:7px;
                    font-size:12px;font-weight:600;text-decoration:none;">
                    Booking Servis
                </a>
            </div>`);
    });

    // ── GEOLOKASI ──
    let userMarker = null;

    function haversineKm(lat1, lng1, lat2, lng2) {
        const R    = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a    = Math.sin(dLat/2)**2
                   + Math.cos(lat1*Math.PI/180) * Math.cos(lat2*Math.PI/180) * Math.sin(dLng/2)**2;
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }

    function showNearby() {
        if (!navigator.geolocation) {
            alert('Browser Anda tidak mendukung geolokasi.');
            return;
        }
        document.getElementById('nearbyLoader').style.display = 'flex';

        navigator.geolocation.getCurrentPosition(pos => {
            const uLat = pos.coords.latitude;
            const uLng = pos.coords.longitude;

            document.getElementById('nearbyLoader').style.display = 'none';
            document.getElementById('nearbyPanel').style.display  = 'block';
            document.getElementById('mapSubtitle').textContent    = 'Menampilkan bengkel motor terdekat';

            if (userMarker) map.removeLayer(userMarker);
            userMarker = L.circleMarker([uLat, uLng], {
                radius: 10, fillColor: '#3b82f6', color: '#fff', weight: 2, fillOpacity: 0.9
            }).addTo(map).bindPopup('📍 Lokasi Anda').openPopup();
            map.setView([uLat, uLng], 14);

            const withDist = bengkelData
                .filter(b => b.lat && b.lng)
                .map(b => ({ ...b, jarak: haversineKm(uLat, uLng, b.lat, b.lng) }))
                .sort((a, b) => a.jarak - b.jarak);

            withDist.forEach(b => {
                const badge = document.getElementById('jarak-' + b.id);
                const card  = document.querySelector(`.bengkel-card[data-id="${b.id}"]`);
                if (badge) { badge.textContent = b.jarak.toFixed(1) + ' km'; badge.style.display = 'block'; }
                if (card)  { card.dataset.jarak = b.jarak.toFixed(2); }
            });

            const nearbyList = document.getElementById('nearbyList');
            nearbyList.innerHTML = '';
            withDist.slice(0, 5).forEach(b => {
                nearbyList.innerHTML += `
                    <div class="nearby-item" onclick="window.location='${b.url}'" role="button" tabindex="0">
                        <div class="nearby-dot"></div>
                        <div class="nearby-info">
                            <div class="nearby-nama">${b.nama}</div>
                            <div class="nearby-meta">${b.jarak.toFixed(1)} km &bull; ${b.alamat.split(',')[0]}</div>
                        </div>
                        <div class="nearby-rating">★ ${b.rating}</div>
                    </div>`;
            });

            document.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
            document.getElementById('chipTerdekat').classList.add('active');

        }, () => {
            document.getElementById('nearbyLoader').style.display = 'none';
            alert('Tidak dapat mengakses lokasi. Pastikan izin lokasi diaktifkan.');
        });
    }

    document.getElementById('btnTerdekat').addEventListener('click', showNearby);

    // ── SEARCH ──
    document.getElementById('searchBengkel').addEventListener('input', filterCards);

    // Kata kunci tiap filter → dicek terhadap data-layanan
    const filterKeywords = {
        ganti_oli   : 'oli',
        tune_up     : 'tune',
        ban         : 'ban',
        kelistrikan : 'listrik',
        injeksi     : 'injeksi',
    };

    // ── FILTER CHIPS ──
    document.getElementById('filterChips').addEventListener('click', function (e) {
        const chip = e.target.closest('.chip');
        if (!chip) return;
        if (chip.dataset.filter === 'terdekat') { showNearby(); return; }
        document.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
        chip.classList.add('active');
        filterCards();
    });

    function filterCards() {
        const q      = document.getElementById('searchBengkel').value.toLowerCase().trim();
        const filter = document.querySelector('.chip.active')?.dataset?.filter ?? 'semua';
        const cards  = document.querySelectorAll('.bengkel-card');
        let visible  = 0;

        // Apakah user sedang melakukan pencarian/filter aktif?
        const isSearching = q.length > 0 || filter !== 'semua';

        cards.forEach((card, index) => {
            const nama    = card.dataset.nama    || '';
            const alamat  = card.dataset.alamat  || '';
            const layanan = card.dataset.layanan || '';
            const rating  = parseFloat(card.dataset.rating) || 0;

            const matchQ = !q || nama.includes(q) || alamat.includes(q);

            let matchFilter = true;
            if (filter === 'rating') {
                matchFilter = rating >= 4;
            } else if (filterKeywords[filter]) {
                matchFilter = layanan.includes(filterKeywords[filter]);
            }

            let show = matchQ && matchFilter;

            // Jika tidak sedang searching/filter, batasi hanya 4 card pertama
            if (!isSearching) {
                show = index < 4;
            }

            card.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        document.getElementById('resultCount').textContent =
            visible + ' bengkel motor ditemukan';
        document.getElementById('emptyStateFilter').style.display =
            visible === 0 ? 'flex' : 'none';
    }

    // ── SORT ──
    document.getElementById('sortBy').addEventListener('change', function () {
        const by    = this.value;
        const grid  = document.getElementById('bengkelGrid');
        const cards = Array.from(grid.querySelectorAll('.bengkel-card'));

        cards.sort((a, b) => {
            if (by === 'rating') return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
            if (by === 'ulasan') return parseInt(b.dataset.ulasan)   - parseInt(a.dataset.ulasan);
            if (by === 'jarak')  return parseFloat(a.dataset.jarak || 999) - parseFloat(b.dataset.jarak || 999);
            return 0;
        });
        cards.forEach(card => grid.appendChild(card));
    });

    filterCards();


});
</script>
@endpush