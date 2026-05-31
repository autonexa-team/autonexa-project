{{-- resources/views/admin-pusat/laporan/laporan-pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Operasional Bengkel — AutoNexa</title>
    <style>

        /* ── RESET ── */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #1f2937;
            background: #fff;
            line-height: 1.5;
        }

        /* ── WATERMARK ── */
        .watermark {
            position: fixed;
            top: 42%;
            left: 5%;
            width: 90%;
            text-align: center;
            font-size: 90px;
            font-weight: 900;
            color: #f97316;
            opacity: 0.045;
            transform: rotate(-35deg);
            z-index: -1;
            letter-spacing: 16px;
            pointer-events: none;
        }

        /* ── PAGE ── */
        @page {
            margin: 0mm 0mm 0mm 0mm;
            size: A4 portrait;
        }

        /* ── HEADER TOP (dark) ── */
        .hdr-top {
            background: #0f172a;
            padding: 26px 36px 22px;
        }

        .hdr-row {
            width: 100%;
        }

        .hdr-row table {
            width: 100%;
            border-collapse: collapse;
        }

        .hdr-row td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        /* Brand */
        .brand-table {
            border-collapse: collapse;
        }

        .brand-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .brand-icon-box {
            width: 44px;
            height: 44px;
            background: #f97316;
            border-radius: 8px;
            text-align: center;
            padding-top: 11px;
        }

        .brand-icon-box svg {
            display: inline-block;
        }

        .brand-name {
            font-size: 20px;
            font-weight: 900;
            color: #fff;
            letter-spacing: 0.5px;
            line-height: 1.1;
            padding-left: 10px;
        }

        .brand-tag {
            font-size: 8.5px;
            color: #64748b;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            padding-left: 10px;
            margin-top: 2px;
        }

        /* Meta kanan */
        .hdr-meta {
            text-align: right;
            font-size: 9px;
            color: #64748b;
            line-height: 2;
        }

        .hdr-meta strong { color: #e2e8f0; }

        /* Divider dalam header */
        .hdr-divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.08);
            margin: 18px 0 16px;
        }

        /* Title row */
        .hdr-title-table {
            width: 100%;
            border-collapse: collapse;
        }

        .hdr-title-table td {
            border: none;
            padding: 0;
            vertical-align: bottom;
        }

        .doc-title {
            font-size: 18px;
            font-weight: 900;
            color: #fff;
            letter-spacing: -0.3px;
        }

        .doc-subtitle {
            font-size: 9px;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: 3px;
        }

        .period-badge {
            background: #f97316;
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            padding: 5px 13px;
            border-radius: 4px;
            letter-spacing: 0.3px;
            white-space: nowrap;
            display: inline-block;
        }

        /* ── STATISTIK ── */
        .stat-section {
            padding: 20px 36px;
            border-bottom: 1px solid #f1f5f9;
        }

        .section-label {
            font-size: 8.5px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 10px;
        }

        .stat-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e2e8f0;
            border-radius: 7px;
            overflow: hidden;
        }

        .stat-table td {
            padding: 12px 14px;
            border-right: 1px solid #e2e8f0;
            width: 20%;
            vertical-align: top;
        }

        .stat-table td:last-child { border-right: none; }

        .stat-lbl {
            font-size: 8px;
            color: #94a3b8;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 5px;
        }

        .stat-val {
            font-size: 19px;
            font-weight: 900;
            color: #0f172a;
            line-height: 1;
        }

        .stat-val-sm { font-size: 12px; }
        .stat-orange { color: #f97316; }
        .stat-sub { font-size: 8px; color: #cbd5e1; margin-top: 3px; }
        .stars { color: #f59e0b; }

        /* ── DATA TABLE SECTION ── */
        .table-section {
            padding: 20px 36px;
        }

        .sec-hdr-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .sec-hdr-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .sec-title {
            font-size: 10px;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .sec-bar {
            display: inline-block;
            width: 3px;
            height: 12px;
            background: #f97316;
            border-radius: 2px;
            margin-right: 7px;
            vertical-align: middle;
        }

        .sec-count {
            font-size: 9px;
            color: #94a3b8;
            text-align: right;
        }

        /* Tabel data utama */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            table-layout: fixed;
        }

        .data-table thead tr { background: #0f172a; }

        .data-table th {
            padding: 8px 9px;
            text-align: left;
            font-size: 8px;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }

        .data-table th.th-r { text-align: right; }
        .data-table th.th-c { text-align: center; }

        .data-table tbody tr:nth-child(even) td { background: #f8fafc; }
        .data-table tbody tr:nth-child(odd)  td { background: #fff; }

        .data-table td {
            padding: 7px 9px;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            overflow: hidden;
        }

        .data-table tbody tr:last-child td { border-bottom: none; }

        .data-table tfoot tr  { background: #fefce8; }
        .data-table tfoot td  {
            padding: 9px 9px;
            font-weight: 700;
            font-size: 9px;
            border-top: 2px solid #f97316;
            border-bottom: none;
            color: #0f172a;
        }

        .td-r    { text-align: right; }
        .td-c    { text-align: center; }
        .td-mono { font-family: monospace; font-size: 8.5px; color: #64748b; }
        .td-nom  { text-align: right; font-weight: 700; color: #15803d; white-space: nowrap; }
        .td-bld  { font-weight: 700; color: #0f172a; }
        .td-dt   { white-space: nowrap; color: #64748b; }
        .tfl     { text-align: right; font-size: 8.5px; color: #64748b;
                   text-transform: uppercase; letter-spacing: 0.05em; }
        .tfv     { text-align: right; color: #f97316; font-size: 11px; }

        /* ── STATUS BADGES ── */
        .badge {
            display: inline;
            padding: 2px 7px;
            border-radius: 20px;
            font-size: 7.5px;
            font-weight: 700;
        }

        .b-selesai  { background: #dcfce7; color: #166534; }
        .b-proses   { background: #fef3c7; color: #92400e; }
        .b-konfirm  { background: #dbeafe; color: #1e3a8a; }
        .b-batal    { background: #fee2e2; color: #991b1b; }
        .b-pending  { background: #f1f5f9; color: #475569; }

        /* ── RINGKASAN AKHIR ── */
        .summary-section {
            padding: 16px 36px 20px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 0 6px;
            border: none;
            vertical-align: top;
            width: 33.33%;
        }

        .summary-table td:first-child { padding-left: 0; }
        .summary-table td:last-child  { padding-right: 0; }

        .sum-box {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 11px 13px;
            border-left-width: 3px;
        }

        .sum-accent { border-left-color: #f97316; }
        .sum-green  { border-left-color: #16a34a; }
        .sum-red    { border-left-color: #dc2626; }

        .sum-lbl {
            font-size: 8px;
            color: #94a3b8;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 5px;
        }

        .sum-val { font-size: 16px; font-weight: 900; line-height: 1.1; }
        .sum-val-orange { color: #f97316; font-size: 13px; }
        .sum-val-green  { color: #15803d; }
        .sum-val-red    { color: #dc2626; }
        .sum-val-muted  { font-size: 11px; color: #94a3b8; }
        .sum-sub        { font-size: 8px; color: #94a3b8; margin-top: 3px; }

        /* ── FOOTER ── */
        .doc-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #0f172a;
            padding: 10px 36px;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td { border: none; padding: 0; vertical-align: middle; }

        .footer-left  { font-size: 8px; color: #475569; }
        .footer-right { font-size: 8px; color: #475569; text-align: right; }

    </style>
</head>
<body>

{{-- WATERMARK --}}
<div class="watermark">AUTONEXA</div>

{{-- FOOTER (fixed, muncul di setiap halaman) --}}
<div class="doc-footer">
    <table class="footer-table">
        <tr>
            <td class="footer-left">
                Dokumen dibuat otomatis oleh sistem AutoNexa · Bersifat rahasia dan hanya untuk penggunaan internal
            </td>
            <td class="footer-right">
                Export: {{ $waktuDownload->format('d M Y, H:i') }} WIB
            </td>
        </tr>
    </table>
</div>

{{-- ═══════════════════════
     HEADER
═══════════════════════ --}}
<div class="hdr-top">

    {{-- Brand + Meta --}}
    <div class="hdr-row">
        <table>
            <tr>
                <td style="width:55%;">
                    <table class="brand-table">
                        <tr>
                            <td style="width:48px;">
                                <div class="brand-icon-box">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="#fff">
                                        <path d="M12 15.5A3.5 3.5 0 0 1 8.5 12 3.5 3.5 0 0 1 12
                                        8.5a3.5 3.5 0 0 1 3.5 3.5 3.5 3.5 0 0 1-3.5
                                        3.5m7.43-2.92c.04-.3.07-.62.07-.94s-.03-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24
                                        0-.44.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47
                                        0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03
                                        1.58c-.04.3-.07.63-.07.94s.03.64.07.94l-2.03
                                        1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38
                                        1.03.7 1.62.94l.36 2.54c.03.24.23.41.47.41h3.84c.24
                                        0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56
                                        1.62-.94l2.39.96c.22.08.47 0
                                        .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58z"/>
                                    </svg>
                                </div>
                            </td>
                            <td>
                                <div class="brand-name">AUTONEXA</div>
                                <div class="brand-tag">Sistem Reservasi Bengkel Online</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="hdr-meta">
                    No. Dokumen: <strong>RPT-{{ $waktuDownload->format('YmdHi') }}</strong><br>
                    Dicetak oleh: <strong>{{ $userDownload }}</strong><br>
                    Waktu export: <strong>{{ $waktuDownload->format('d M Y, H:i') }} WIB</strong>
                </td>
            </tr>
        </table>
    </div>

    <hr class="hdr-divider">

    {{-- Judul + Periode --}}
    <table class="hdr-title-table">
        <tr>
            <td>
                <div class="doc-title">Laporan Operasional Bengkel</div>
                <div class="doc-subtitle">Laporan Periodik · Admin Pusat</div>
            </td>
            <td style="text-align:right; vertical-align:bottom;">
                <span class="period-badge">
                    {{ \Carbon\Carbon::parse($dari)->format('d M Y') }}
                    —
                    {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}
                </span>
            </td>
        </tr>
    </table>

</div>

{{-- ═══════════════════════
     STATISTIK RINGKAS
═══════════════════════ --}}
<div class="stat-section">
    <div class="section-label">Ringkasan Statistik Periode Ini</div>
    <table class="stat-table">
        <tr>
            <td>
                <div class="stat-lbl">Total Reservasi</div>
                <div class="stat-val">{{ number_format($totalReservasi ?? 0) }}</div>
                <div class="stat-sub">Semua bengkel</div>
            </td>
            <td>
                <div class="stat-lbl">Total Pelanggan</div>
                <div class="stat-val">{{ number_format($totalPelanggan ?? 0) }}</div>
                <div class="stat-sub">Unik periode ini</div>
            </td>
            <td>
                <div class="stat-lbl">Total Pendapatan</div>
                <div class="stat-val stat-val-sm stat-orange">
                    Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                </div>
                <div class="stat-sub">Dari reservasi selesai</div>
            </td>
            <td>
                <div class="stat-lbl">Jumlah Review</div>
                <div class="stat-val">{{ number_format($totalReview ?? 0) }}</div>
                <div class="stat-sub">Ulasan pelanggan</div>
            </td>
            <td>
                <div class="stat-lbl">Rating Rata-rata</div>
                <div class="stat-val stat-orange">{{ number_format($avgRating ?? 0, 1) }}</div>
                <div class="stat-sub stars">★★★★★</div>
            </td>
        </tr>
    </table>
</div>

{{-- ═══════════════════════
     TABEL DATA UTAMA
═══════════════════════ --}}
<div class="table-section">

    <table class="sec-hdr-table">
        <tr>
            <td>
                <span class="sec-bar"></span>
                <span class="sec-title">Data Reservasi</span>
            </td>
            <td class="sec-count">{{ ($reservasis ?? collect())->count() }} data ditampilkan</td>
        </tr>
    </table>

    <table class="data-table">
        <colgroup>
            <col style="width:24px;">
            <col style="width:13%;">
            <col style="width:11%;">
            <col style="width:13%;">
            <col style="width:13%;">
            <col style="width:9%;">
            <col style="width:9%;">
            <col style="width:11%;">
        </colgroup>
        <thead>
            <tr>
                <th class="th-c">#</th>
                <th>Pelanggan</th>
                <th>Kendaraan</th>
                <th>Layanan</th>
                <th>Bengkel</th>
                <th class="th-c">Tanggal</th>
                <th class="th-c">Status</th>
                <th class="th-r">Total Biaya</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservasis ?? [] as $i => $r)
            @php
                $statusMap = [
                    'pending'     => ['Menunggu',     'b-pending'],
                    'dikonfirmasi'   => ['Dikonfirmasi', 'b-konfirm'],
                    'in_progress' => ['Dikerjakan',   'b-proses'],
                    'selesai'        => ['Selesai',       'b-selesai'],
                    'dibatalkan'   => ['Dibatalkan',   'b-batal'],
                ];
                [$statusLbl, $statusCls] = $statusMap[$r->status]
                    ?? [ucfirst($r->status), 'b-pending'];
            @endphp
            <tr>
                <td class="td-mono td-c">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</td>
                <td class="td-bld">{{ $r->user->name ?? '-' }}</td>
                <td>{{ $r->kendaraan ?? '-' }}</td>
                <td>{{ $r->layanan ?? $r->keluhan ?? '-' }}</td>
                <td>{{ $r->bengkel->nama ?? '-' }}</td>
                <td class="td-dt td-c">
                    {{ \Carbon\Carbon::parse($r->tanggal)->format('d/m/y') }}
                </td>
                <td class="td-c">
                    <span class="badge {{ $statusCls }}">{{ $statusLbl }}</span>
                </td>
                <td class="td-nom">
                    @if(($r->total_biaya ?? 0) > 0)
                        Rp {{ number_format($r->total_biaya, 0, ',', '.') }}
                    @else
                        —
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:16px;color:#9ca3af;font-style:italic;">
                    Tidak ada data untuk periode ini
                </td>
            </tr>
            @endforelse
        </tbody>
        @if(($reservasis ?? collect())->isNotEmpty())
        <tfoot>
            <tr>
                <td colspan="7" class="tfl">Total Pendapatan Periode Ini</td>
                <td class="tfv td-r">
                    Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
        @endif
    </table>

</div>

{{-- ═══════════════════════
     RINGKASAN AKHIR
═══════════════════════ --}}
<div class="summary-section">
    <table class="sec-hdr-table" style="margin-bottom:10px;">
        <tr>
            <td>
                <span class="sec-bar"></span>
                <span class="sec-title">Ringkasan Akhir</span>
            </td>
        </tr>
    </table>
    <table class="summary-table">
        <tr>
            <td>
                <div class="sum-box sum-accent">
                    <div class="sum-lbl">Total Seluruh Pendapatan</div>
                    <div class="sum-val sum-val-orange">
                        Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="sum-sub">Akumulasi semua bengkel periode ini</div>
                </div>
            </td>
            <td>
                <div class="sum-box sum-green">
                    <div class="sum-lbl">Reservasi Selesai</div>
                    <div class="sum-val sum-val-green">
                        {{ number_format($selesai ?? 0) }}
                        <span class="sum-val-muted">/ {{ number_format($totalReservasi ?? 0) }}</span>
                    </div>
                    <div class="sum-sub">
                        {{ ($totalReservasi ?? 0) > 0
                            ? round(($selesai / $totalReservasi) * 100)
                            : 0 }}% completion rate
                    </div>
                </div>
            </td>
            <td>
                <div class="sum-box sum-red">
                    <div class="sum-lbl">Reservasi Dibatalkan</div>
                    <div class="sum-val sum-val-red">
                        {{ number_format($dibatalkan ?? 0) }}
                    </div>
                    <div class="sum-sub">
                        {{ ($totalReservasi ?? 0) > 0
                            ? round(($dibatalkan / $totalReservasi) * 100)
                            : 0 }}% dari total reservasi
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>