{{-- resources/views/admin-pusat/bengkel/show.blade.php --}}
@extends('layout.admin')
@section('title', $bengkel->nama ?? 'Detail Bengkel')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-bengkel.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')

{{-- ===== PAGE HEADER ===== --}}
<div class="page-header">
    <div>
        <nav class="breadcrumb-nav">
            <a href="{{ route('admin-pusat.dashboard') }}" class="bc-link">Dashboard</a>
            <i class="bi bi-chevron-right bc-sep"></i>
            <a href="{{ route('admin-pusat.bengkel.index') }}" class="bc-link">Bengkel</a>
            <i class="bi bi-chevron-right bc-sep"></i>
            <span class="bc-current">{{ $bengkel->nama }}</span>
        </nav>
        <div class="detail-title-row">
            <h1 class="page-title">{{ $bengkel->nama }}</h1>
            <span class="status-badge-lg {{ $bengkel->status === 'aktif' ? 'badge-aktif-lg' : 'badge-nonaktif-lg' }}">
                <span class="status-dot-lg {{ $bengkel->status === 'aktif' ? 'sd-aktif' : 'sd-nonaktif' }}"></span>
                {{ $bengkel->status === 'aktif' ? 'Aktif' : 'Tidak Aktif' }}
            </span>
        </div>
        <div class="detail-meta">
            <span class="meta-item">
                <i class="bi bi-geo-alt"></i>
                {{ $bengkel->kota }}
            </span>
            <span class="meta-item">
                <i class="bi bi-house"></i>
                {{ $bengkel->alamat }}
            </span>
            <span class="meta-item">
                <i class="bi bi-calendar3"></i>
                Bergabung {{ \Carbon\Carbon::parse($bengkel->created_at)->translatedFormat('d M Y') }}
            </span>
        </div>
    </div>
    <div class="ph-actions">
        <a href="{{ route('admin-pusat.bengkel.edit', $bengkel->id) }}" class="btn-secondary">
            <i class="bi bi-pencil"></i> Edit Bengkel
        </a>
        <form action="{{ route('admin-pusat.bengkel.toggle-status', $bengkel->id) }}" method="POST"
              onsubmit="return confirm('{{ $bengkel->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }} bengkel ini?')">
            @csrf @method('PATCH')
            <button type="submit" class="{{ $bengkel->status === 'aktif' ? 'btn-danger-outline' : 'btn-success-outline' }}">
                <i class="bi bi-{{ $bengkel->status === 'aktif' ? 'slash-circle' : 'check-circle' }}"></i>
                {{ $bengkel->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
            </button>
        </form>
    </div>
</div>

{{-- ===== STAT CARDS ===== --}}
<div class="stat-grid stat-grid-4">
    <div class="stat-card">
        <div class="stat-icon-wrap si-orange"><i class="bi bi-calendar-check"></i></div>
        <div class="stat-body">
            <div class="stat-label">Total Reservasi</div>
            <div class="stat-value">{{ number_format($bengkel->reservasi_count ?? 0) }}</div>
            <div class="stat-trend trend-neutral">
                <i class="bi bi-plus-circle"></i>
                {{ $reservasiBulanIni ?? 0 }} bulan ini
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap si-blue"><i class="bi bi-calendar-day"></i></div>
        <div class="stat-body">
            <div class="stat-label">Reservasi Hari Ini</div>
            <div class="stat-value">{{ $reservasiHariIni ?? 0 }}</div>
            <div class="stat-trend trend-neutral">
                <i class="bi bi-circle-fill" style="font-size:7px;"></i>
                {{ $reservasiSelesaiHariIni ?? 0 }} selesai · {{ $reservasiProsesHariIni ?? 0 }} proses
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap si-amber"><i class="bi bi-star"></i></div>
        <div class="stat-body">
            <div class="stat-label">Rating Rata-rata</div>
            <div class="stat-value">{{ number_format($bengkel->avg_rating ?? 0, 1) }}</div>
            <div class="stat-trend trend-neutral">
                <i class="bi bi-chat-left-text"></i>
                {{ number_format($bengkel->reviews_count ?? 0) }} ulasan
            </div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap si-green"><i class="bi bi-grid-3x3-gap"></i></div>
        <div class="stat-body">
            <div class="stat-label">Kapasitas Antrian</div>
            <div class="stat-value">{{ $bengkel->kapasitas ?? 0 }}</div>
            <div class="stat-trend trend-neutral">
                <i class="bi bi-check-circle"></i>
                Slot tersedia hari ini
            </div>
        </div>
    </div>
</div>

{{-- ===== MAP + INFO ===== --}}
<div class="detail-two-col">

    {{-- Peta Leaflet --}}
    <div class="fcard">
        <div class="fcard-header">
            <div class="fcard-icon"><i class="bi bi-map"></i></div>
            <div>
                <div class="fcard-title">Peta Lokasi</div>
                <div class="fcard-sub">{{ number_format($bengkel->latitude, 6) }}, {{ number_format($bengkel->longitude, 6) }}</div>
            </div>
        </div>
        <div id="detailMap" style="width:100%;height:240px;border-radius:8px;border:1px solid var(--border);"></div>
        <script>
            window.bengkelLat = {{ $bengkel->latitude ?? -6.2088 }};
            window.bengkelLng = {{ $bengkel->longitude ?? 106.8456 }};
            window.bengkelNama = "{{ addslashes($bengkel->nama) }}";
        </script>
    </div>

    {{-- Info Detail --}}
    <div class="fcard">
        <div class="fcard-header">
            <div class="fcard-icon"><i class="bi bi-info-circle"></i></div>
            <div>
                <div class="fcard-title">Informasi Bengkel</div>
                <div class="fcard-sub">Data umum & pengelola</div>
            </div>
        </div>

        {{-- Admin Cabang --}}
        <div class="info-section-label">Admin Cabang</div>
        <div class="admin-profile-card">
            <div class="admin-profile-av">
                {{ strtoupper(substr($bengkel->adminCabang->name ?? 'A', 0, 2)) }}
            </div>
            <div>
                <div class="admin-profile-name">{{ $bengkel->adminCabang->name ?? '-' }}</div>
                <div class="admin-profile-email">{{ $bengkel->adminCabang->email ?? '-' }}</div>
            </div>
        </div>

        {{-- Detail grid --}}
        <div class="info-detail-grid">
            <div class="info-detail-item">
                <div class="info-detail-label">Jam Operasional</div>
                <div class="info-detail-val">{{ $bengkel->jam_buka ?? '07:00' }} – {{ $bengkel->jam_tutup ?? '17:00' }}</div>
                <div class="info-detail-sub">{{ $bengkel->hari_operasional ?? 'Senin – Sabtu' }}</div>
            </div>
            <div class="info-detail-item">
                <div class="info-detail-label">Kapasitas Bay</div>
                <div class="info-detail-val">{{ $bengkel->kapasitas ?? 0 }} Bay</div>
                <div class="info-detail-sub">Tersedia per sesi</div>
            </div>
            <div class="info-detail-item">
                <div class="info-detail-label">Telepon</div>
                <div class="info-detail-val" style="font-size:13px;">{{ $bengkel->telepon ?? '-' }}</div>
            </div>
            <div class="info-detail-item">
                <div class="info-detail-label">Kode Bengkel</div>
                <div class="info-detail-val info-detail-mono">#BGL-{{ str_pad($bengkel->id, 4, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>
    </div>

</div>

{{-- ===== LAYANAN ===== --}}
<div class="section-card">
    <div class="section-card-header">
        <div class="fcard-header" style="margin-bottom:0;border-bottom:none;padding-bottom:0;">
            <div class="fcard-icon"><i class="bi bi-wrench-adjustable"></i></div>
            <div>
                <div class="fcard-title">Layanan Tersedia</div>
                <div class="fcard-sub">{{ $bengkel->layanan_count ?? 0 }} layanan aktif</div>
            </div>
        </div>
    </div>
    <div class="layanan-grid">
        @forelse($bengkel->layanan ?? [] as $layanan)
        <div class="layanan-card">
            <div class="layanan-name">{{ $layanan->nama }}</div>
            <div class="layanan-price">Rp {{ number_format($layanan->harga ?? 0, 0, ',', '.') }}</div>
            <div class="layanan-dur">
                <i class="bi bi-clock"></i>
                {{ $layanan->durasi ?? 0 }} menit
            </div>
            @if($layanan->deskripsi)
                <div class="layanan-desc">{{ $layanan->deskripsi }}</div>
            @endif
        </div>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:24px;color:var(--foreground-t);font-size:13px;">
            Belum ada layanan terdaftar
        </div>
        @endforelse
    </div>
</div>

{{-- ===== SPAREPART MONITORING ===== --}}
<div class="section-card">
    <div class="section-card-header">
        <div class="fcard-header" style="margin-bottom:0;border-bottom:none;padding-bottom:0;">
            <div class="fcard-icon"><i class="bi bi-box-seam"></i></div>
            <div>
                <div class="fcard-title">Monitoring Stok Sparepart</div>
                <div class="fcard-sub">Read-only · Admin pusat tidak dapat mengubah stok</div>
            </div>
        </div>
        @if(($sparepartKritis ?? 0) > 0)
            <span class="badge-kritis">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ $sparepartKritis }} stok kritis
            </span>
        @endif
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nama Sparepart</th>
                    <th>Stok Saat Ini</th>
                    <th style="width:200px;">Indikator</th>
                    <th>Status Stok</th>
                </tr>
            </thead>
            <tbody>
                @forelse($spareparts ?? [] as $sp)
                @php
                    $stok      = $sp->pivot->stok ?? 0;
                    $stokMax   = 100; 
                    $pct       = min(100, ($stok / $stokMax) * 100); 
                    $isKritis  = $stok == 0;
                    $isWarn    = !$isKritis && $stok <= 5;
                    $barColor  = $isKritis ? '#dc2626' : ($isWarn ? '#ca8a04' : '#16a34a');
                    $pillClass = $isKritis ? 'pill-crit' : ($isWarn ? 'pill-warn' : 'pill-ok');
                    $pillLabel = $isKritis ? 'Habis' : ($isWarn ? 'Hampir habis' : 'Aman');
                @endphp
                <tr>
                    <td class="td-sparepart-name">{{ $sp->nama }}</td>
                    <td>{{ number_format($stok) }} {{ $sp->satuan ?? 'pcs' }}</td>
                    <td>
                        <div class="stok-bar-wrap">
                            <div class="stok-bar-track">
                                <div class="stok-bar-fill" style="width:{{ $pct }}%;background:{{ $barColor }};"></div>
                            </div>
                            <span class="stok-pct">{{ round($pct) }}%</span>
                        </div>
                    </td>
                    <td>
                        <span class="stok-pill {{ $pillClass }}">{{ $pillLabel }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="td-empty">Belum ada data sparepart</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="section-card-footer">
        <a href="{{ route('admin-pusat.sparepart', ['bengkel' => $bengkel->id]) }}"
           class="btn-secondary" style="font-size:12px;padding:7px 14px;">
            <i class="bi bi-box-seam"></i> Lihat &amp; Kelola Stok
        </a>
    </div>
</div>

{{-- ===== RESERVASI TERBARU ===== --}}
<div class="section-card">
    <div class="section-card-header">
        <div class="fcard-header" style="margin-bottom:0;border-bottom:none;padding-bottom:0;">
            <div class="fcard-icon"><i class="bi bi-calendar-check"></i></div>
            <div>
                <div class="fcard-title">Reservasi Terbaru</div>
                <div class="fcard-sub">5 reservasi terakhir di bengkel ini</div>
            </div>
        </div>
        <a href="{{ route('admin-pusat.reservasi', ['bengkel' => $bengkel->id]) }}" class="btn-chart-link">
            Lihat Semua <i class="bi bi-arrow-right"></i>
        </a>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Layanan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservasiTerbaru ?? [] as $r)
                @php
                    $statusMap = [
                        'pending'     => ['label' => 'Menunggu',     'class' => 'badge-pending'],
                        'confirmed'   => ['label' => 'Dikonfirmasi', 'class' => 'badge-confirmed'],
                        'in_progress' => ['label' => 'Dikerjakan',   'class' => 'badge-progress'],
                        'done'        => ['label' => 'Selesai',      'class' => 'badge-done'],
                        'cancelled'   => ['label' => 'Dibatalkan',   'class' => 'badge-cancel'],
                    ];
                    $s = $statusMap[$r->status] ?? ['label' => $r->status, 'class' => 'badge-pending'];
                @endphp
                <tr>
                    <td>
                        <div class="td-user">
                            <div class="td-avatar">{{ strtoupper(substr($r->user->name ?? 'U', 0, 1)) }}</div>
                            {{ $r->user_id }} — {{ $r->user->name ?? 'NULL' }}
                        </div>
                    </td>
                    <td>{{ $r->keluhan ?? '-' }}</td>
                    <td class="td-date">{{ \Carbon\Carbon::parse($r->tanggal)->format('d M Y') }}</td>
                    <td><span class="status-badge {{ $s['class'] }}">{{ $s['label'] }}</span></td>
                    <td>
                        <a href="{{ route('admin-pusat.reservasi', $r->id) }}" class="btn-aksi-detail">
                            <i class="bi bi-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="td-empty">Belum ada reservasi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/admin-bengkel.js') }}"></script>
@endpush