{{-- resources/views/admin-pusat/laporan/laporan-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Reservasi &amp; Pendapatan — Autonexa</title>
    <style>

        /* ── RESET & BASE ──────────────────────────────── */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #1f2937;
            background: #fff;
            line-height: 1.5;
        }

        /* ── WATERMARK ─────────────────────────────────── */
        .watermark {
            position: fixed;
            top: 38%;
            left: 10%;
            width: 80%;
            text-align: center;
            font-size: 90px;
            font-weight: 900;
            color: #ff6a00;
            opacity: 0.055;
            transform: rotate(-30deg);
            z-index: -1;
            letter-spacing: 12px;
            pointer-events: none;
        }

        /* ── DOCUMENT HEADER ───────────────────────────── */
        .doc-header {
            padding-bottom: 14px;
            border-bottom: 2px solid #ff6a00;
            margin-bottom: 18px;
        }

        .doc-header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .doc-brand {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .doc-brand-icon {
            width: 36px;
            height: 36px;
            background: #ff6a00;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .doc-brand-icon svg {
            width: 20px;
            height: 20px;
            fill: #fff;
        }

        .doc-brand-name {
            font-size: 18px;
            font-weight: 900;
            color: #ff6a00;
            letter-spacing: 1px;
        }

        .doc-brand-tagline {
            font-size: 9px;
            color: #6b7280;
            letter-spacing: 0.5px;
        }

        .doc-meta {
            text-align: right;
            font-size: 10px;
            color: #6b7280;
            line-height: 1.7;
        }

        .doc-meta strong { color: #1f2937; }

        .doc-title-row {
            margin-top: 6px;
        }

        .doc-title {
            font-size: 15px;
            font-weight: 800;
            color: #1f2937;
            letter-spacing: -0.2px;
            text-transform: uppercase;
        }

        .doc-periode {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
        }

        .doc-periode strong { color: #ff6a00; }

        /* ── SUMMARY STRIP ─────────────────────────────── */
        .summary-strip {
            display: flex;
            gap: 0;
            margin-bottom: 18px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .summary-item {
            flex: 1;
            padding: 10px 14px;
            border-right: 1px solid #e5e7eb;
        }

        .summary-item:last-child { border-right: none; }

        .summary-label {
            font-size: 9px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 3px;
        }

        .summary-value {
            font-size: 16px;
            font-weight: 800;
            color: #1f2937;
            line-height: 1.1;
        }

        .summary-value-sm {
            font-size: 12px;
        }

        .summary-sub {
            font-size: 9px;
            color: #9ca3af;
            margin-top: 2px;
        }

        /* ── SECTION TITLE ─────────────────────────────── */
        .section-wrap {
            margin-bottom: 20px;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px solid #f3f4f6;
        }

        .section-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .dot-blue   { background: #2563eb; }
        .dot-green  { background: #16a34a; }
        .dot-orange { background: #ff6a00; }
        .dot-amber  { background: #d97706; }

        .section-title {
            font-size: 12px;
            font-weight: 800;
            color: #1f2937;
            letter-spacing: -0.1px;
        }

        .section-sub {
            font-size: 9px;
            color: #9ca3af;
            margin-left: auto;
        }

        /* ── TABLE ─────────────────────────────────────── */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        thead tr {
            background: #ff6a00;
        }

        th {
            padding: 7px 10px;
            text-align: left;
            font-size: 9px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        th.th-right { text-align: right; }
        th.th-center { text-align: center; }

        td {
            padding: 7px 10px;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
            vertical-align: middle;
        }

        tbody tr:nth-child(even) td {
            background: #fafafa;
        }

        tbody tr:last-child td { border-bottom: none; }

        .td-right  { text-align: right; }
        .td-center { text-align: center; }
        .td-mono   { font-family: monospace; font-size: 10px; color: #6b7280; }
        .td-nominal { text-align: right; font-weight: 700; color: #16a34a; white-space: nowrap; }
        .td-date    { white-space: nowrap; color: #6b7280; }
        .td-bengkel { font-weight: 600; }

        tfoot tr { background: #fff7f0; }

        tfoot td {
            padding: 8px 10px;
            font-weight: 700;
            font-size: 10px;
            border-top: 2px solid #ff6a00;
        }

        .tfoot-label { text-align: right; color: #6b7280; font-size: 9px; text-transform: uppercase; letter-spacing: 0.04em; }
        .tfoot-value { text-align: right; color: #1f2937; }

        /* ── STATUS BADGES ─────────────────────────────── */
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-pending   { background: #fef9c3; color: #854d0e; }
        .badge-confirmed { background: #dbeafe; color: #1d4ed8; }
        .badge-progress  { background: #fff7ed; color: #c2410c; }
        .badge-done      { background: #dcfce7; color: #15803d; }
        .badge-cancel    { background: #fee2e2; color: #b91c1c; }

        /* ── RATING STARS ──────────────────────────────── */
        .stars { color: #f59e0b; font-size: 10px; }

        /* ── PERFORMANCE BAR ───────────────────────────── */
        .perf-bar-outer {
            display: inline-block;
            width: 60px;
            height: 6px;
            background: #f3f4f6;
            border-radius: 3px;
            vertical-align: middle;
            margin-right: 4px;
        }

        .perf-bar-inner {
            height: 100%;
            background: #ff6a00;
            border-radius: 3px;
        }

        /* ── DOCUMENT FOOTER ───────────────────────────── */
        .doc-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 8px 20px;
            border-top: 1px solid #e5e7eb;
            background: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            color: #9ca3af;
        }

        .doc-footer strong { color: #6b7280; }

        /* ── PRINT UTILS ───────────────────────────────── */
        .page-break { page-break-after: always; }

        @page {
            margin: 18mm 15mm 22mm 15mm;
        }

        @media print {
            .watermark { position: fixed; }
        }

    </style>
</head>
<body>

{{-- WATERMARK --}}
<div class="watermark">AUTONEXA</div>

{{-- DOCUMENT FOOTER (fixed, muncul di setiap halaman) --}}
<div class="doc-footer">
    <span>Dokumen ini digenerate otomatis oleh sistem Autonexa dan berlaku sebagai laporan resmi.</span>
    <strong>© {{ date('Y') }} Autonexa — Sistem Reservasi Bengkel Online</strong>
</div>

{{-- ── DOCUMENT HEADER ──────────────────────────────────────────── --}}
<div class="doc-header">
    <div class="doc-header-top">
        <div class="doc-brand">
            <div class="doc-brand-icon">
                {{-- Gear icon inline SVG agar tidak butuh font eksternal --}}
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 15.5A3.5 3.5 0 0 1 8.5 12 3.5 3.5 0 0 1 12 8.5a3.5 3.5 0 0 1 3.5 3.5 3.5 3.5 0 0 1-3.5 3.5m7.43-2.92c.04-.3.07-.62.07-.94s-.03-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.44.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.04.3-.07.63-.07.94s.03.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.03.24.23.41.47.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58z"/>
                </svg>
            </div>
            <div>
                <div class="doc-brand-name">AUTONEXA</div>
                <div class="doc-brand-tagline">Sistem Reservasi Bengkel Online</div>
            </div>
        </div>
        <div class="doc-meta">
            Dicetak oleh: <strong>{{ $userDownload }}</strong><br>
            Waktu generate: <strong>{{ $waktuDownload->format('d M Y, H:i') }} WIB</strong><br>
            No. Dokumen: <strong>RPT-{{ $waktuDownload->format('YmdHi') }}</strong>
        </div>
    </div>
    <div class="doc-title-row">
        <div class="doc-title">Laporan Reservasi &amp; Pendapatan</div>
        <div class="doc-periode">
            Periode: <strong>{{ \Carbon\Carbon::parse($dari)->format('d M Y') }}</strong>
            s/d <strong>{{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}</strong>
        </div>
    </div>
</div>

{{-- ── SUMMARY STRIP ────────────────────────────────────────────── --}}
<div class="summary-strip">
    <div class="summary-item">
        <div class="summary-label">Total Reservasi</div>
        <div class="summary-value">{{ number_format($totalReservasi ?? 0) }}</div>
        <div class="summary-sub">Periode ini</div>
    </div>
    <div class="summary-item">
        <div class="summary-label">Reservasi Selesai</div>
        <div class="summary-value">{{ number_format($selesai ?? 0) }}</div>
        <div class="summary-sub">
            {{ ($totalReservasi ?? 0) > 0 ? round(($selesai / $totalReservasi) * 100) : 0 }}% dari total
        </div>
    </div>
    <div class="summary-item">
        <div class="summary-label">Total Pendapatan</div>
        <div class="summary-value summary-value-sm">
            Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
        </div>
        <div class="summary-sub">Dari reservasi selesai</div>
    </div>
    <div class="summary-item">
        <div class="summary-label">Rata-rata Rating</div>
        <div class="summary-value">{{ number_format($avgRating ?? 0, 1) }}</div>
        <div class="summary-sub stars">★★★★★</div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     A. LAPORAN RESERVASI
══════════════════════════════════════════════════════════════ --}}
<div class="section-wrap">
    <div class="section-header">
        <div class="section-dot dot-blue"></div>
        <div class="section-title">A. Laporan Reservasi</div>
        <div class="section-sub">Detail transaksi periode ini</div>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width:26px;">#</th>
                <th>Pelanggan</th>
                <th>Bengkel</th>
                <th>Layanan</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th class="th-right">Total Biaya</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservasis ?? [] as $i => $r)
            <tr>
                <td class="td-center td-mono">{{ $i + 1 }}</td>
                <td>{{ $r->user->name ?? '-' }}</td>
                <td class="td-bengkel">{{ $r->bengkel->nama ?? '-' }}</td>
                <td>{{ $r->layanan ?? '-' }}</td>
                <td class="td-date">{{ \Carbon\Carbon::parse($r->tanggal)->format('d/m/Y') }}</td>
                <td>
                    @php
                        $sMap = [
                            'pending'     => ['Menunggu',     'badge-pending'],
                            'confirmed'   => ['Dikonfirmasi', 'badge-confirmed'],
                            'in_progress' => ['Dikerjakan',   'badge-progress'],
                            'done'        => ['Selesai',      'badge-done'],
                            'cancelled'   => ['Dibatalkan',   'badge-cancel'],
                        ];
                        [$lbl, $cls] = $sMap[$r->status] ?? [ucfirst($r->status), 'badge-pending'];
                    @endphp
                    <span class="badge {{ $cls }}">{{ $lbl }}</span>
                </td>
                <td class="td-nominal">
                    @if(($r->total_biaya ?? 0) > 0)
                        Rp {{ number_format($r->total_biaya, 0, ',', '.') }}
                    @else —
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:16px;color:#9ca3af;">
                    Tidak ada data untuk periode ini
                </td>
            </tr>
            @endforelse
        </tbody>
        @if(($reservasis ?? collect())->isNotEmpty())
        <tfoot>
            <tr>
                <td colspan="6" class="tfoot-label">TOTAL PENDAPATAN</td>
                <td class="tfoot-value">
                    Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>

{{-- ══════════════════════════════════════════════════════════════
     B. LAPORAN PENDAPATAN
══════════════════════════════════════════════════════════════ --}}
<div class="section-wrap">
    <div class="section-header">
        <div class="section-dot dot-green"></div>
        <div class="section-title">B. Laporan Pendapatan (Agregasi)</div>
        <div class="section-sub">Pendapatan harian per bengkel</div>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width:26px;">#</th>
                <th>Tanggal</th>
                <th>Bengkel</th>
                <th class="th-center">Jumlah Transaksi</th>
                <th class="th-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendapatanHarian ?? [] as $i => $p)
            <tr>
                <td class="td-center td-mono">{{ $i + 1 }}</td>
                <td class="td-date">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                <td class="td-bengkel">{{ $p->bengkel }}</td>
                <td class="td-center">{{ $p->jumlah_transaksi }} trx</td>
                <td class="td-nominal">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:16px;color:#9ca3af;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
        @if(($pendapatanHarian ?? collect())->isNotEmpty())
        <tfoot>
            <tr>
                <td colspan="3" class="tfoot-label">TOTAL</td>
                <td class="tfoot-value td-center">{{ ($pendapatanHarian ?? collect())->sum('jumlah_transaksi') }} trx</td>
                <td class="tfoot-value">Rp {{ number_format(($pendapatanHarian ?? collect())->sum('total'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>

{{-- ══════════════════════════════════════════════════════════════
     C. PERFORMA BENGKEL
══════════════════════════════════════════════════════════════ --}}
<div class="section-wrap">
    <div class="section-header">
        <div class="section-dot dot-orange"></div>
        <div class="section-title">C. Performa Bengkel</div>
        <div class="section-sub">Rangkuman kinerja masing-masing bengkel</div>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width:26px;">#</th>
                <th>Nama Bengkel</th>
                <th class="th-center">Total Reservasi</th>
                <th class="th-right">Total Pendapatan</th>
                <th class="th-center">Rating</th>
                <th class="th-center">Skor</th>
            </tr>
        </thead>
        <tbody>
            @php $maxPendapatan = ($performaBengkel ?? collect())->max('total_pendapatan') ?: 1; @endphp
            @forelse(($performaBengkel ?? collect())->sortByDesc('total_pendapatan')->values() as $i => $b)
            <tr>
                <td class="td-center">
                    @if($i===0) 🥇 @elseif($i===1) 🥈 @elseif($i===2) 🥉 @else {{ $i+1 }} @endif
                </td>
                <td class="td-bengkel">{{ $b->nama }}</td>
                <td class="td-center">{{ number_format($b->total_reservasi) }}</td>
                <td class="td-nominal">Rp {{ number_format($b->total_pendapatan, 0, ',', '.') }}</td>
                <td class="td-center">
                    <span class="stars">★</span> {{ number_format($b->rating, 1) }}
                </td>
                <td class="td-center">
                    @php $skor = round(($b->total_pendapatan / $maxPendapatan) * 100); @endphp
                    <span class="perf-bar-outer">
                        <span class="perf-bar-inner" style="width:{{ $skor }}%;display:block;"></span>
                    </span>
                    {{ $skor }}%
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:16px;color:#9ca3af;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ══════════════════════════════════════════════════════════════
     D. LAPORAN REVIEW
══════════════════════════════════════════════════════════════ --}}
<div class="section-wrap">
    <div class="section-header">
        <div class="section-dot dot-amber"></div>
        <div class="section-title">D. Laporan Review</div>
        <div class="section-sub">Rekapitulasi ulasan pelanggan</div>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width:26px;">#</th>
                <th>Bengkel</th>
                <th class="th-center">Jumlah Review</th>
                <th class="th-center">Rating Rata-rata</th>
            </tr>
        </thead>
        <tbody>
            @forelse(($reviews ?? collect())->sortByDesc('rating_avg')->values() as $i => $rv)
            <tr>
                <td class="td-center td-mono">{{ $i + 1 }}</td>
                <td class="td-bengkel">{{ $rv->bengkel }}</td>
                <td class="td-center">{{ $rv->jumlah_review }}</td>
                <td class="td-center">
                    <span class="stars">★</span>
                    {{ number_format($rv->rating_avg, 1) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center;padding:16px;color:#9ca3af;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
        @if(($reviews ?? collect())->isNotEmpty())
        <tfoot>
            <tr>
                <td colspan="2" class="tfoot-label">TOTAL / RATA-RATA</td>
                <td class="tfoot-value td-center">{{ ($reviews ?? collect())->sum('jumlah_review') }}</td>
                <td class="tfoot-value td-center">
                    <span class="stars">★</span> {{ number_format(($reviews ?? collect())->avg('rating_avg'), 1) }}
                </td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>

</body>
</html>