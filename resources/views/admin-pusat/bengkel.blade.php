{{-- resources/views/admin-pusat/bengkel.blade.php --}}
@extends('layout.admin')
@section('title', 'Manajemen Bengkel')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-bengkel.css') }}">
@endpush

@section('content')

<!-- dummy data disimpen di BengkelController -->

{{-- ===== PAGE HEADER ===== --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Bengkel</h1>
        <p class="page-sub">Kelola semua bengkel yang terdaftar di sistem Autonexa</p>
    </div>
    <a href="{{ route('admin-pusat.bengkel.create') }}" class="btn-add">
        <i class="bi bi-plus-lg"></i> Tambah Bengkel
    </a>
</div>

{{-- ===== TOOLBAR: SEA RCH + FILTER ===== --}}
<div class="toolbar">
    <div class="search-wrap">
        <i class="bi bi-search search-icon"></i>
        <input
            type="text"
            class="search-input"
            id="searchInput"
            placeholder="Cari nama bengkel, kota, atau alamat..."
            value="{{ request('search') }}"
        >
    </div>

    <select class="filter-select" id="filterStatus">
        <option value="">Semua Status</option>
        <option value="aktif"     {{ request('status') === 'aktif'     ? 'selected' : '' }}>Aktif</option>
        <option value="nonaktif"  {{ request('status') === 'nonaktif'  ? 'selected' : '' }}>Tidak Aktif</option>
    </select>

    <select class="filter-select" id="filterKota">
        <option value="">Semua Kota</option>
        @foreach($kotaList ?? [] as $kota)
            <option value="{{ $kota }}" {{ request('kota') === $kota ? 'selected' : '' }}>
                {{ $kota }}
            </option>
        @endforeach
    </select>

    <div class="view-toggle">
        <button class="view-btn active" id="btnGrid" title="Grid View">
            <i class="bi bi-grid-3x3-gap"></i>
        </button>
        <button class="view-btn" id="btnList" title="List View">
            <i class="bi bi-list-ul"></i>
        </button>
    </div>
</div>

{{-- ===== STATS PILLS ===== --}}
<div class="stats-bar" id="statsBar">
    <span class="stat-pill sp-all">
        Semua <strong id="statSemua">{{ $bengkels->total() ?? 0 }}</strong>
    </span>
    <span class="stat-pill">
        Aktif <strong class="text-green" id="statAktif">{{ $totalAktif ?? 0 }}</strong>
    </span>
    <span class="stat-pill">
        Tidak Aktif <strong class="text-red" id="statNonaktif">{{ $totalNonaktif ?? 0 }}</strong>
    </span>
    <span class="stat-pill">
        Kota Tercakup <strong id="statKota">{{ $totalKota ?? 0 }}</strong>
    </span>
</div>

{{-- ===== BENGKEL GRID ===== --}}
<div class="bengkel-grid" id="bengkelGrid">

    @forelse($bengkels as $b)
    <div class="bengkel-card" data-status="{{ $b->status }}" data-kota="{{ $b->kota }}">

        {{-- Thumbnail --}}
        <div class="card-thumb" style="--warna: {{ $b->warna ?? '#ff6a00' }}">
            @if($b->foto)
                <img src="{{ asset('storage/' . $b->foto) }}" alt="{{ $b->nama }}" class="card-thumb-img">
            @else
                <div class="card-thumb-initials" style="color:var(--warna);">
                    {{ strtoupper(implode('', array_map(fn($w) => $w[0], explode(' ', $b->nama)))) }}
                </div>
            @endif
            <div class="card-thumb-overlay">
                <span class="card-status {{ $b->status === 'aktif' ? 'status-aktif' : 'status-nonaktif' }}">
                    <span class="status-dot {{ $b->status === 'aktif' ? 'sd-aktif' : 'sd-nonaktif' }}"></span>
                    {{ $b->status === 'aktif' ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </div>
        </div>

        {{-- Body --}}
        <div class="card-body">
            <div class="card-name" title="{{ $b->nama }}">{{ $b->nama }}</div>
            <div class="card-addr">
                <i class="bi bi-geo-alt card-addr-icon"></i>
                <span>{{ $b->alamat }}, {{ $b->kota }}</span>
            </div>

            <div class="card-phone">
                <i class="bi bi-telephone"></i>
                <span>{{ $b->telepon }}</span>
            </div>            

            {{-- Layanan tags --}}
            @if($b->layanan && count($b->layanan) > 0)
            <div class="card-meta">
                @foreach(array_slice($b->layanan->toArray(), 0, 3) as $layanan)
                    <span class="meta-tag">{{ $layanan['nama'] ?? $layanan }}</span>
                @endforeach
                @if(count($b->layanan) > 3)
                    <span class="meta-tag meta-tag-more">+{{ count($b->layanan) - 3 }}</span>
                @endif
            </div>
            @endif

            {{-- Stats --}}
            <div class="card-stats">
                <div class="cs-item">
                    <div class="cs-val">{{ $b->reservasi_count ?? 0 }}</div>
                    <div class="cs-lbl">Reservasi</div>
                </div>
                <div class="cs-item">
                    <div class="cs-val">
                        <span class="rating-stars">★</span>
                        {{ number_format($b->avg_rating ?? 0, 1) }}
                    </div>
                    <div class="cs-lbl">Rating</div>
                </div>
            </div>
        </div>

        {{-- Footer aksi --}}
        <div class="card-footer">
            <a href="{{ route('admin-pusat.bengkel.show', $b->id) }}" class="btn-card-detail">
                <i class="bi bi-eye"></i> Detail
            </a>
            <a href="{{ route('admin-pusat.bengkel.edit', $b->id) }}" class="btn-card-edit" title="Edit">
                <i class="bi bi-pencil"></i>
            </a>
            <form action="{{ route('admin-pusat.bengkel.destroy', $b->id) }}" method="POST"
                    onsubmit="return confirm('Hapus bengkel {{ $b->nama }}?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-card-del" title="Hapus">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>

    </div>
    @empty
    <div class="empty-state">
        <i class="bi bi-shop empty-icon"></i>
        <p class="empty-title">Belum ada bengkel terdaftar</p>
        <p class="empty-sub">Mulai tambahkan bengkel pertama Anda</p>
        <a href="{{ route('admin-pusat.bengkel.create') }}" class="btn-add" style="margin-top:16px;">
            <i class="bi bi-plus-lg"></i> Tambah Bengkel
        </a>
    </div>
    @endforelse

</div>

{{-- Pagination --}}
@if(isset($bengkels) && $bengkels->hasPages())
<div class="pagination-wrap">
    {{ $bengkels->withQueryString()->links('vendor.pagination.simple-tailwind') }}
</div>
@endif

@endsection

@push('scripts')
<script src="{{ asset('js/admin-bengkel.js') }}"></script>
@endpush