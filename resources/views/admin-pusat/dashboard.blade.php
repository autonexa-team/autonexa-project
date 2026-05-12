{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layout.admin')
@section('title', 'Dashboard Admin Pusat')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush

@section('content')

{{-- ===== PAGE HEADER ===== --}}
<div class="dash-header">
    <div>
        <h1 class="dash-title">Dashboard</h1>
        <p class="dash-sub">Selamat datang kembali, <strong>{{ auth()->user()->name ?? 'Admin' }}</strong> · Terakhir diperbarui {{ now()->format('d M Y, H:i') }} WIB</p>
    </div>
    <div class="dash-header-actions">
        <div class="date-range-wrap">
            <i class="bi bi-calendar3"></i>
            <select class="chart-select" id="globalPeriod">
                <option value="7">7 Hari</option>
                <option value="30" selected>30 Hari</option>
                <option value="90">3 Bulan</option>
                <option value="365">1 Tahun</option>
            </select>
        </div>
        <a href="{{ route('admin-pusat.laporan') }}" class="btn-export">
            <i class="bi bi-download"></i> Export Laporan
        </a>
    </div>
</div>

{{-- ===== STAT CARDS ROW 1 ===== --}}
<div class="stat-grid">

    <div class="stat-card">
        <div class="stat-icon-wrap si-orange">
            <i class="bi bi-calendar-check"></i>
        </div>

    </div>

    <div class="stat-card">
        <div class="stat-icon-wrap si-blue">
            <i class="bi bi-shop"></i>
        </div>
        <div class="stat-body">
            <div class="stat-label">Bengkel Aktif</div>
            <div class="stat-value">{{ $totalBengkel ?? 0 }}</div>
            <div class="stat-trend trend-neutral">
                <i class="bi bi-plus-circle"></i>
                {{ $bengkelBaru ?? 0 }} baru bulan ini
            </div>
        </div>
    </div>

    <div class="stat-card stat-card-highlight">
        <div class="stat-icon-wrap si-green">
            <i class="bi bi-cash-coin"></i>
        </div>
        <div class="stat-body">
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-value stat-value-sm">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</div>
            <div class="stat-trend {{ ($trendPendapatan ?? 0) >= 0 ? 'trend-up' : 'trend-down' }}">
                <i class="bi bi-arrow-{{ ($trendPendapatan ?? 0) >= 0 ? 'up' : 'down' }}-short"></i>
                {{ abs($trendPendapatan ?? 0) }}% vs bulan lalu
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrap si-purple">
            <i class="bi bi-star"></i>
        </div>
        <div class="stat-body">
            <div class="stat-label">Total Review</div>
            <div class="stat-value">{{ $totalReview ?? 0 }}</div>
            <div class="stat-trend trend-neutral">
                <i class="bi bi-star-fill"></i>
                {{ $avgRating ?? 0 }} rating
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrap si-amber">
            <i class="bi bi-box-seam"></i>
        </div>
        <div class="stat-body">
            <div class="stat-label">Bengkel Bermasalah</div>
            <div class="stat-value">{{ $bengkelBermasalah ?? 0 }}</div>
            <div class="stat-trend trend-down">
                <i class="bi bi-exclamation-triangle-fill"></i>
                Rating rendah / nonaktif
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrap si-teal">
            <i class="bi bi-people"></i>
        </div>
        <div class="stat-body">
            <div class="stat-label">Total Pelanggan</div>
            <div class="stat-value">{{ number_format($totalPelanggan ?? 0) }}</div>
            <div class="stat-trend {{ ($trendPelanggan ?? 0) >= 0 ? 'trend-up' : 'trend-down' }}">
                <i class="bi bi-arrow-{{ ($trendPelanggan ?? 0) >= 0 ? 'up' : 'down' }}-short"></i>
                {{ abs($trendPelanggan ?? 0) }}% bulan ini
            </div>
        </div>
    </div>

</div>

{{-- ===== GRAFIK ROW 1: Pendapatan (besar) + Status Donut ===== --}}
<div class="charts-row">

    {{-- Grafik Pendapatan --}}
    <div class="chart-card">
        <div class="chart-card-header">
            <div>
                <h3 class="chart-title">Grafik Pendapatan</h3>
                <p class="chart-subtitle">Semua bengkel · dapat di-toggle per periode</p>
            </div>
            <div class="chart-tab-group">
                <button class="chart-tab active" data-period="mingguan">Minggu</button>
                <button class="chart-tab" data-period="bulanan">Bulan</button>
                <button class="chart-tab" data-period="tahunan">Tahun</button>
            </div>
        </div>
        <div class="chart-legend">
            <span class="legend-item">
                <span class="legend-dot" style="background:#ff6a00;"></span>
                Pendapatan (Rp juta)
            </span>
            <span class="legend-item">
                <span class="legend-dot" style="background:#d1d5db;"></span>
                Target
            </span>
        </div>
        <div class="chart-wrap" style="height:240px;">
            <canvas id="chartPendapatan"></canvas>
        </div>
    </div>


</div>

    {{-- Performa Bengkel --}}
    <div class="chart-card">
        <div class="chart-card-header">
            <div>
                <h3 class="chart-title">Performa Bengkel</h3>

            </div>
            <a href="{{ route('admin-pusat.laporan') }}" class="btn-chart-link">
                Laporan <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="chart-wrap" style="height:{{ max(180, ($totalBengkel ?? 5) * 40) }}px;">
            <canvas id="chartBengkel"></canvas>
        </div>
    </div>

</div>

{{-- ===== TABEL SECTION ===== --}}
<div class="tables-grid">


    {{-- Tabel 2: Transaksi/Pembayaran Terbaru --}}
    <div class="admin-table-card">
        <div class="table-card-header">
            <div class="table-card-title-wrap">
                <h3 class="table-card-title">Transaksi Terbaru</h3>
            </div>
            <a href="{{ route('admin-pusat.laporan') }}" class="btn-export-sm">
                <i class="bi bi-file-earmark-arrow-down"></i> Export
            </a>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Bengkel</th>
                        <th>Pelanggan</th>
                        <th>Nominal</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksiTerbaru ?? [] as $t)
                    <tr>
                        <td class="td-mono">#T-{{ str_pad($t->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $t->bengkel->nama ?? '-' }}</td>
                        <td>{{ $t->user->name ?? '-' }}</td>
                        <td class="td-nominal">Rp {{ number_format($t->total ?? 0, 0, ',', '.') }}</td>
                        <td class="td-date">{{ \Carbon\Carbon::parse($t->created_at)->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin-pusat.transaksi.show', $t->id) }}" class="btn-aksi-detail">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="td-empty">Belum ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tabel 3: Performa Bengkel Terbaik & Terburuk --}}
    <div class="admin-table-card">
        <div class="table-card-header">
            <div class="table-card-title-wrap">
                <h3 class="table-card-title">Ranking Bengkel</h3>
                <span class="table-badge table-badge-neutral">Bulan Ini</span>
            </div>
            <a href="{{ route('admin-pusat.bengkel.index') }}" class="btn-table-link">
                Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Bengkel</th>
                        <th>Rating</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rankingBengkel ?? [] as $i => $b)
                    <tr>
                        <td>
                            <span class="rank-badge rank-{{ $i + 1 <= 3 ? ($i + 1) : 'other' }}">
                                {{ $i + 1 <= 3 ? ['🥇','🥈','🥉'][$i] : ($i + 1) }}
                            </span>
                        </td>
                        <td>
                            <div class="td-bengkel-name">{{ $b->nama ?? '-' }}</div>
                            <div class="td-bengkel-loc">{{ $b->kota ?? '-' }}</div>
                        </td>
                        <td>
                            <span class="rating-star">★</span>
                            {{ number_format($b->avg_rating ?? 0, 1) }}
                        </td>
                        <td>
                            <a href="{{ route('admin-pusat.bengkel.show', $b->id) }}" class="btn-aksi-detail">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="td-empty">Belum ada data bengkel</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
<script>
window.dashboardData = {!! json_encode([
    // Status donut
    'statusLabels'      => $statusLabels      ?? ['Selesai','Dikonfirmasi','Dikerjakan','Dibatalkan'],
    'statusData'        => $statusData        ?? [40, 25, 22, 13],
    // Bengkel horizontal bar
    'bengkelLabels'     => $bengkelLabels     ?? ['Maju Jaya','Auto Prima','Bintang Motor','Karya Mandiri','Prima Teknik'],
    'bengkelData'       => $bengkelData       ?? [184, 162, 145, 110, 92],
    // Pendapatan per periode
    'pendapatanMingguan'=> $pendapatanMingguan ?? [12,18,14,22,19,25,28,21,30,26,33,35,29,38],
    'pendapatanBulanan' => $pendapatanBulanan  ?? [42,55,48,72,81,94,88,110,102,125,118,140],
    'pendapatanTahunan' => $pendapatanTahunan  ?? [480,620,750,890,1050],
    'targetMingguan'    => $targetMingguan     ?? [20,20,20,25,25,25,30,30,30,35,35,35,35,40],
    'targetBulanan'     => $targetBulanan      ?? [50,60,65,75,85,95,100,115,110,130,125,150],
    'targetTahunan'     => $targetTahunan      ?? [500,650,800,950,1100],
    'pendapatanLabMingguan' => $pendapatanLabMingguan ?? ['M1','M2','M3','M4','M5','M6','M7','M8','M9','M10','M11','M12','M13','M14'],
    'pendapatanLabBulanan'  => $pendapatanLabBulanan  ?? ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
    'pendapatanLabTahunan'  => $pendapatanLabTahunan  ?? ['2021','2022','2023','2024','2025'],
]) !!};
</script>
<script src="{{ asset('js/admin-dashboard.js') }}"></script>
@endpush