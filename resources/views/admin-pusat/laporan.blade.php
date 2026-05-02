{{-- resources/views/admin-pusat/laporan/index.blade.php --}}
@extends('layout.admin')
@section('title', 'Laporan')
@section('page-title', 'Laporan')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/laporan.css') }}">
@endpush

@section('content')

{{-- ===== FILTER ===== --}}
<div class="laporan-filter-card">
    <form method="GET" class="row g-3 align-items-end">

        <div class="col-md-2">
            <label class="form-label">Periode</label>
            <select name="periode" class="form-select">
                <option value="bulan" {{ request('periode','bulan') === 'bulan' ? 'selected' : '' }}>Bulanan</option>
                <option value="tahun" {{ request('periode') === 'tahun' ? 'selected' : '' }}>Tahunan</option>
            </select>
        </div>

        <div class="col-md-3">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="dari" class="form-control"
                   value="{{ request('dari', now()->startOfMonth()->format('Y-m-d')) }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="sampai" class="form-control"
                   value="{{ request('sampai', now()->format('Y-m-d')) }}">
        </div>

        <div class="col-md-4 d-flex gap-2 align-items-end">
            <button type="submit" class="btn-filter-submit flex-grow-1">
                <i class="bi bi-search"></i> Filter
            </button>

            {{-- FIX: gunakan url() + http_build_query() bukan route() dengan array --}}
            <a href="{{ url(route('admin-pusat.laporan.pdf')) . '?' . http_build_query(request()->all()) }}"
               class="btn-export-pdf" target="_blank">
                <i class="bi bi-file-pdf"></i> Export PDF
            </a>
        </div>

    </form>
</div>

{{-- ===== LAPORAN PREVIEW ===== --}}
<div class="laporan-preview">

    {{-- Watermark --}}
    <div class="laporan-watermark">AUTONEXA</div>

    {{-- Header --}}
    <div class="laporan-header">
        <div class="laporan-logo">
            <i class="bi bi-gear-wide-connected"></i>
            AUTONEXA
        </div>
        <div class="laporan-meta">
            <h4>Laporan Reservasi &amp; Pendapatan</h4>
            <p>
                Periode:
                {{ \Carbon\Carbon::parse(request('dari', now()->startOfMonth()))->format('d M Y') }}
                s/d
                {{ \Carbon\Carbon::parse(request('sampai', now()))->format('d M Y') }}
            </p>
            <p class="laporan-meta-detail">
                Dicetak oleh: <strong>{{ auth()->user()->name }}</strong>
                &nbsp;|&nbsp;
                {{ now()->format('d M Y, H:i') }} WIB
            </p>
        </div>
    </div>

    <hr class="laporan-divider">

    {{-- Ringkasan --}}
    <div class="laporan-summary-row">
        <div class="laporan-summary-item">
            <div class="ls-label">Total Reservasi</div>
            <div class="ls-value">{{ number_format($totalReservasi ?? 0) }}</div>
        </div>
        <div class="laporan-summary-item">
            <div class="ls-label">Reservasi Selesai</div>
            <div class="ls-value">{{ number_format($selesai ?? 0) }}</div>
        </div>
        <div class="laporan-summary-item">
            <div class="ls-label">Total Pendapatan</div>
            <div class="ls-value">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</div>
        </div>
    </div>

    <hr class="laporan-divider">

    {{-- Tabel Detail --}}
    <div class="laporan-section-title">Detail Reservasi</div>

    <table class="laporan-table">
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Pelanggan</th>
                <th>Bengkel</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th style="text-align:right;">Total Biaya</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservasis ?? [] as $i => $r)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $r->user->name ?? '-' }}</td>
                <td>{{ $r->bengkel->nama ?? '-' }}</td>
                <td style="white-space:nowrap;">
                    {{ \Carbon\Carbon::parse($r->tanggal)->format('d/m/Y') }}
                </td>
                <td>
                    @php
                        $sMap = [
                            'pending'     => ['Menunggu',    'badge-pending'],
                            'confirmed'   => ['Dikonfirmasi','badge-confirmed'],
                            'in_progress' => ['Dikerjakan',  'badge-progress'],
                            'done'        => ['Selesai',     'badge-done'],
                            'cancelled'   => ['Dibatalkan',  'badge-cancel'],
                        ];
                        $s = $sMap[$r->status] ?? [ucfirst($r->status), 'badge-pending'];
                    @endphp
                    <span class="status-badge {{ $s[1] }}">{{ $s[0] }}</span>
                </td>
                <td style="text-align:right;">
                    Rp {{ number_format($r->total_biaya ?? 0, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:24px;color:#9ca3af;">
                    <i class="bi bi-inbox" style="font-size:20px;display:block;margin-bottom:6px;"></i>
                    Tidak ada data untuk periode ini
                </td>
            </tr>
            @endforelse
        </tbody>
        @if(($reservasis ?? collect())->isNotEmpty())
        <tfoot>
            <tr>
                <td colspan="5" style="text-align:right;">TOTAL PENDAPATAN</td>
                <td style="text-align:right;">
                    Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- Footer Dokumen --}}
    <div class="laporan-footer">
        <p>Dokumen ini digenerate otomatis oleh sistem Autonexa dan berlaku sebagai laporan resmi.</p>
        <p>© {{ date('Y') }} Autonexa — Sistem Reservasi Bengkel Online</p>
    </div>

</div>

@endsection