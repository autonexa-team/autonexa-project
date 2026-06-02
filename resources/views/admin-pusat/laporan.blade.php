{{-- resources/views/admin-pusat/laporan/index.blade.php --}}
@extends('layout.admin')
@section('title', 'Laporan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/laporan.css') }}">
@endpush

@section('content')

{{-- ── HEADER ──────────────────────────────────────────────────── --}}
<div class="lp-header">
    <div>
        <h1 class="lp-title">Laporan</h1>
        <p class="lp-sub">
            Periode:
            <strong>{{ \Carbon\Carbon::parse($dari)->format('d M Y') }}</strong>
            s/d
            <strong>{{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}</strong>
        </p>
    </div>
    <div class="lp-header-actions">
        <a href="{{ url(route('admin-pusat.laporan-pdf')) . '?' . http_build_query(request()->all()) }}"
           class="btn-export-pdf" target="_blank">
            <i class="bi bi-file-earmark-pdf"></i>
            Export PDF
        </a>
    </div>
</div>

{{-- ── FILTER CARD ──────────────────────────────────────────────── --}}
<div class="lp-filter-card">
    <form method="GET" class="lp-filter-form">

        <div class="lp-filter-group">
            <label class="lp-filter-label">Periode</label>
            <select name="periode" class="lp-select">
                <option value="harian"  {{ request('periode') === 'harian'  ? 'selected' : '' }}>Harian</option>
                <option value="mingguan"{{ request('periode') === 'mingguan'? 'selected' : '' }}>Mingguan</option>
                <option value="bulanan" {{ request('periode','bulanan') === 'bulanan'  ? 'selected' : '' }}>Bulanan</option>
            </select>
        </div>

        <div class="lp-filter-group">
            <label class="lp-filter-label">Dari Tanggal</label>
            <input type="date" name="dari" class="lp-input-date"
                   value="{{ $dari }}">
        </div>

        <div class="lp-filter-group">
            <label class="lp-filter-label">Sampai Tanggal</label>
            <input type="date" name="sampai" class="lp-input-date"
                   value="{{ $sampai }}">
        </div>

        <div class="lp-filter-actions">
            <button type="submit" class="btn-filter">
                <i class="bi bi-search"></i> Filter
            </button>
            <a href="{{ route('admin-pusat.laporan') }}" class="btn-reset">
                <i class="bi bi-x-lg"></i> Reset
            </a>
        </div>

    </form>
</div>

{{-- ── SUMMARY CARDS ────────────────────────────────────────────── --}}
<div class="lp-summary-grid">

    <div class="lp-summary-card">
        <div class="lp-sc-icon si-blue">
            <i class="bi bi-calendar-check"></i>
        </div>
        <div class="lp-sc-body">
            <div class="lp-sc-label">Total Reservasi</div>
            <div class="lp-sc-value">{{ number_format($totalReservasi) }}</div>
            <div class="lp-sc-sub">Periode ini</div>
        </div>
    </div>

    <div class="lp-summary-card">
        <div class="lp-sc-icon si-green">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="lp-sc-body">
            <div class="lp-sc-label">Reservasi Selesai</div>
            <div class="lp-sc-value">{{ number_format($selesai) }}</div>
            <div class="lp-sc-sub">
                {{ $totalReservasi > 0 ? round(($selesai / $totalReservasi) * 100) : 0 }}% dari total
            </div>
        </div>
    </div>

    <div class="lp-summary-card lp-summary-card-highlight">
        <div class="lp-sc-icon si-orange">
            <i class="bi bi-cash-stack"></i>
        </div>
        <div class="lp-sc-body">
            <div class="lp-sc-label">Total Pendapatan</div>
            <div class="lp-sc-value lp-sc-value-sm">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </div>
            <div class="lp-sc-sub">Dari reservasi selesai</div>
        </div>
    </div>

    <div class="lp-summary-card">
        <div class="lp-sc-icon si-amber">
            <i class="bi bi-star-fill"></i>
        </div>
        <div class="lp-sc-body">
            <div class="lp-sc-label">Rata-rata Rating</div>
            <div class="lp-sc-value">{{ number_format($avgRating, 1) }}</div>
            <div class="lp-sc-sub">
                <span class="lp-stars">
                    @for($s = 1; $s <= 5; $s++)
                        <i class="bi bi-star{{ $s <= round($avgRating) ? '-fill' : '' }}"></i>
                    @endfor
                </span>
            </div>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════════════
     A. LAPORAN RESERVASI
══════════════════════════════════════════════════════════════ --}}
<div class="lp-section-card">
    <div class="lp-section-header">
        <div class="lp-section-icon si-blue"><i class="bi bi-clipboard-data"></i></div>
        <div>
            <h2 class="lp-section-title">Laporan Reservasi</h2>
            <p class="lp-section-sub">Detail seluruh transaksi reservasi pada periode ini</p>
        </div>
        <span class="lp-count-badge">{{ $totalReservasi }} data</span>
    </div>

    <div class="lp-table-wrap">
        <table class="lp-table">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Pelanggan</th>
                    <th>Bengkel</th>
                    <th>Layanan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th class="th-right">Total Biaya</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservasis as $i => $r)
                <tr>
                    <td class="td-num">{{ $i + 1 }}</td>
                    <td>
                        <div class="td-user">
                            <div class="td-avatar">{{ strtoupper(substr($r->user->name, 0, 2)) }}</div>
                            <span>{{ $r->user->name ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="td-bengkel">{{ $r->bengkel->nama ?? '-' }}</td>
                    <td>{{ $r->layanan->nama ?? '-' }}</td>
                    <td class="td-date">
                        {{ \Carbon\Carbon::parse($r->tanggal)->format('d/m/Y') }}
                    </td>
                    <td>
                        @php
                            $sMap = [
                                'pending'     => ['Menunggu',     'badge-pending'],
                                'dikonfirmasi'   => ['Dikonfirmasi', 'badge-confirmed'],
                                'in_progress' => ['Dikerjakan',   'badge-progress'],
                                'selesai'        => ['Selesai',      'badge-done'],
                                'dibatalkan'   => ['Dibatalkan',   'badge-cancel'],
                            ];
                            [$label, $cls] = $sMap[$r->status] ?? [ucfirst($r->status), 'badge-pending'];
                        @endphp
                        <span class="status-badge {{ $cls }}">{{ $label }}</span>
                    </td>
                    <td class="td-nominal">
                        @if($r->total_biaya > 0)
                            Rp {{ number_format($r->total_biaya, 0, ',', '.') }}
                        @else
                            <span style="color:#9ca3af;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="td-empty">
                        <i class="bi bi-inbox"></i><br>Tidak ada data untuk periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($reservasis->isNotEmpty())
            <tfoot>
                <tr class="lp-tfoot">
                    <td colspan="6" class="lp-tfoot-label">TOTAL PENDAPATAN PERIODE INI</td>
                    <td class="lp-tfoot-value">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     B. LAPORAN PENDAPATAN (AGREGASI)
══════════════════════════════════════════════════════════════ --}}
<div class="lp-section-card">
    <div class="lp-section-header">
        <div class="lp-section-icon si-green"><i class="bi bi-graph-up-arrow"></i></div>
        <div>
            <h2 class="lp-section-title">Laporan Pendapatan</h2>
            <p class="lp-section-sub">Agregasi pendapatan harian per bengkel</p>
        </div>
        <span class="lp-count-badge lp-badge-green">{{ $pendapatanHarian->count() }} entri</span>
    </div>

    <div class="lp-table-wrap">
        <table class="lp-table">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Tanggal</th>
                    <th>Bengkel</th>
                    <th class="th-center">Jumlah Transaksi</th>
                    <th class="th-right">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendapatanHarian as $i => $p)
                <tr>
                    <td class="td-num">{{ $i + 1 }}</td>
                    <td class="td-date">
                        {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                    </td>
                    <td class="td-bengkel">{{ $p->bengkel }}</td>
                    <td class="td-center">
                        <span class="lp-trx-badge">{{ $p->jumlah_transaksi }} trx</span>
                    </td>
                    <td class="td-nominal">
                        Rp {{ number_format($p->total, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="td-empty">
                        <i class="bi bi-inbox"></i><br>Tidak ada data
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($pendapatanHarian->isNotEmpty())
            <tfoot>
                <tr class="lp-tfoot">
                    <td colspan="3" class="lp-tfoot-label">TOTAL</td>
                    <td class="lp-tfoot-value td-center">
                        {{ $pendapatanHarian->sum('jumlah_transaksi') }} trx
                    </td>
                    <td class="lp-tfoot-value">
                        Rp {{ number_format($pendapatanHarian->sum('total'), 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     C. PERFORMA BENGKEL
══════════════════════════════════════════════════════════════ --}}
<div class="lp-section-card">
    <div class="lp-section-header">
        <div class="lp-section-icon si-orange"><i class="bi bi-shop"></i></div>
        <div>
            <h2 class="lp-section-title">Performa Bengkel</h2>
            <p class="lp-section-sub">Rangkuman kinerja masing-masing bengkel</p>
        </div>
        <span class="lp-count-badge lp-badge-orange">{{ $performaBengkel->count() }} bengkel</span>
    </div>

    <div class="lp-table-wrap">
        <table class="lp-table">
            <thead>
                <tr>
                    <th style="width:40px;">Rank</th>
                    <th>Nama Bengkel</th>
                    <th class="th-center">Total Reservasi</th>
                    <th class="th-right">Total Pendapatan</th>
                    <th class="th-center">Rating</th>
                    <th class="th-center">Skor Performa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($performaBengkel->sortByDesc('total_pendapatan')->values() as $i => $b)
                <tr>
                    <td class="td-center">
                        @if($i === 0) <span class="lp-rank">🥇</span>
                        @elseif($i === 1) <span class="lp-rank">🥈</span>
                        @elseif($i === 2) <span class="lp-rank">🥉</span>
                        @else <span class="td-num">{{ $i + 1 }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="lp-bengkel-name">{{ $b->nama }}</div>
                    </td>
                    <td class="td-center">
                        <strong>{{ number_format($b->total_reservasi) }}</strong>
                    </td>
                    <td class="td-nominal">
                        Rp {{ number_format($b->total_pendapatan, 0, ',', '.') }}
                    </td>
                    <td class="td-center">
                        <span class="lp-rating">
                            <i class="bi bi-star-fill" style="color:#f59e0b;font-size:11px;"></i>
                            {{ number_format($b->rating, 1) }}
                        </span>
                    </td>
                    <td class="td-center">
                        @php
                            $maxPendapatan = $performaBengkel->max('total_pendapatan');
                            $skor = $maxPendapatan > 0
                                ? round(($b->total_pendapatan / $maxPendapatan) * 100)
                                : 0;
                        @endphp
                        <div class="lp-perf-bar-wrap">
                            <div class="lp-perf-bar">
                                <div class="lp-perf-fill" style="width:{{ $skor }}%"></div>
                            </div>
                            <span class="lp-perf-pct">{{ $skor }}%</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="td-empty">
                        <i class="bi bi-inbox"></i><br>Tidak ada data
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     D. LAPORAN REVIEW
══════════════════════════════════════════════════════════════ --}}
<div class="lp-section-card">
    <div class="lp-section-header">
        <div class="lp-section-icon si-amber"><i class="bi bi-star-half"></i></div>
        <div>
            <h2 class="lp-section-title">Laporan Review</h2>
            <p class="lp-section-sub">Rekapitulasi ulasan pelanggan per bengkel</p>
        </div>
        <span class="lp-count-badge lp-badge-amber">{{ $reviews->count() }} bengkel</span>
    </div>

    <div class="lp-table-wrap">
        <table class="lp-table">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Bengkel</th>
                    <th class="th-center">Jumlah Review</th>
                    <th class="th-center">Rating Rata-rata</th>
                    <th class="th-center">Distribusi Bintang</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews->sortByDesc('rating_avg')->values() as $i => $rv)
                <tr>
                    <td class="td-num">{{ $i + 1 }}</td>
                    <td class="lp-bengkel-name">{{ $rv->bengkel }}</td>
                    <td class="td-center">
                        <span class="lp-review-count">{{ $rv->jumlah_review }}</span>
                    </td>
                    <td class="td-center">
                        <div class="lp-rating-wrap">
                            <span class="lp-rating-num">{{ number_format($rv->rating_avg, 1) }}</span>
                            <div class="lp-stars-sm">
                                @for($s = 1; $s <= 5; $s++)
                                    <i class="bi bi-star{{ $s <= round($rv->rating_avg) ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                        </div>
                    </td>
                    <td class="td-center">
                        @php $pct = round(($rv->rating_avg / 5) * 100); @endphp
                        <div class="lp-star-bar-wrap">
                            <div class="lp-star-bar">
                                <div class="lp-star-fill" style="width:{{ $pct }}%"></div>
                            </div>
                            <span class="lp-perf-pct">{{ $pct }}%</span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="td-empty">
                        <i class="bi bi-inbox"></i><br>Tidak ada data review
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($reviews->isNotEmpty())
            <tfoot>
                <tr class="lp-tfoot">
                    <td colspan="2" class="lp-tfoot-label">TOTAL REVIEW</td>
                    <td class="lp-tfoot-value td-center">{{ $reviews->sum('jumlah_review') }}</td>
                    <td class="lp-tfoot-value td-center">
                        {{ number_format($reviews->avg('rating_avg'), 1) }} ★
                    </td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/laporan.js') }}"></script>
@endpush