<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Review – {{ $bengkel->nama ?? 'Bengkel' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand:       #f97316;
            --brand-light: #fff7ed;
            --brand-mid:   #fed7aa;
            --slate-900:   #0f172a;
            --slate-800:   #1e293b;
            --slate-600:   #475569;
            --slate-400:   #94a3b8;
            --slate-200:   #e2e8f0;
            --slate-100:   #f1f5f9;
            --slate-50:    #f8fafc;
            --green:       #10b981;
            --green-light: #d1fae5;
            --yellow:      #f59e0b;
            --yellow-light:#fef3c7;
            --red:         #ef4444;
            --red-light:   #fee2e2;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #fff;
            color: var(--slate-800);
            font-size: 13px;
        }

        /* ── PRINT SETTINGS ── */
        @page {
            size: A4;
            margin: 0;
        }
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
            .no-break { page-break-inside: avoid; }
        }

        /* ── COVER PAGE ── */
        .cover {
            width: 100%;
            min-height: auto;
            padding-bottom: 20px; 
            background: var(--slate-900);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }
        .cover-accent-top {
            position: absolute;
            top: -120px; right: -80px;
            width: 300px; height: 300px; /* Diperkecil */
            background: radial-gradient(circle, rgba(249,115,22,0.25) 0%, transparent 70%);
            border-radius: 50%;
        }
        .cover-accent-bottom {
            position: absolute;
            bottom: -180px; left: -120px;
            width: 350px; height: 350px; /* Diperkecil */
            background: radial-gradient(circle, rgba(249,115,22,0.1) 0%, transparent 65%);
            border-radius: 50%;
        }
        .cover-grid {
            pposition: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 30px 30px;
        }
        .cover-inner {
            position: relative;
            z-index: 10;
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 20px 32px;
        }
        .cover-logo-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }
        .cover-logo-icon {
            width: 36px; height: 36px; /* Diperkecil */
            background: var(--brand);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .cover-logo-text {
            font-size: 16px; /* Diperkecil */
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.3px;
        }
        .cover-logo-sub {
            font-size: 10px;
            color: rgba(255,255,255,0.45);
            font-weight: 500;
            margin-top: 1px;
        }
        .cover-body {
            padding: 6px 0;
        }
        .cover-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(249,115,22,0.15);
            border: 1px solid rgba(249,115,22,0.3);
            color: var(--brand);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 100px;
            margin-bottom: 8px;
        }
        .cover-title {
            font-size: 24px;
            font-weight: 900;
            color: #fff;
            line-height: 1.1;
            letter-spacing: -2px;
            margin-bottom: 10px;
        }
        .cover-title br { display: none; } 
        .cover-title span { color: var(--brand); margin-left: 6px; margin-right: 6px; }
        .cover-subtitle {
            font-size: 13px;
            color: rgba(255,255,255,0.5);
            font-weight: 500;
            margin-bottom: 16px;
        }
        .cover-divider {
            width: 64px; height: 3px;
            background: var(--brand);
            border-radius: 2px;
            margin-bottom: 48px;
            display: none;
        }
        .cover-meta-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px 24px;
            max-width: 100%;
            margin-bottom: 14px;
        }
        .cover-meta-item label {
            display: block;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            margin-bottom: 2px;
        }
        .cover-meta-item p {
            font-size: 12px; /* Diperkecil */
            font-weight: 700;
            color: #fff;
        }
        .cover-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 16px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
        .cover-footer-left {
            font-size: 10px;
            color: rgba(255,255,255,0.3);
            font-weight: 500;
        }
        .cover-footer-badge {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.4);
            font-size: 9px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 100px;
            letter-spacing: 0.5px;
        }

        /* ── CONTENT PAGES ── */
        .content-pages {
            padding: 48px 56px;
        }

        /* Section header */
        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }
        .section-dot {
            width: 4px; height: 28px;
            background: var(--brand);
            border-radius: 2px;
            flex-shrink: 0;
        }
        .section-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--slate-900);
            letter-spacing: -0.3px;
        }
        .section-subtitle {
            font-size: 13px; /* Diperkecil */
            color: rgba(255,255,255,0.5);
            font-weight: 500;
            margin-bottom: 20px;
        }

        /* ── SUMMARY STATS ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 36px;
        }
        .stat-card {
            background: var(--slate-50);
            border: 1px solid var(--slate-200);
            border-radius: 14px;
            padding: 18px 16px;
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            border-radius: 14px 14px 0 0;
        }
        .stat-card.orange::before { background: var(--brand); }
        .stat-card.green::before  { background: var(--green); }
        .stat-card.yellow::before { background: var(--yellow); }
        .stat-card.blue::before   { background: #3b82f6; }
        .stat-card-icon {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .stat-card.orange .stat-card-icon { color: var(--brand); }
        .stat-card.green  .stat-card-icon { color: var(--green); }
        .stat-card.yellow .stat-card-icon { color: var(--yellow); }
        .stat-card.blue   .stat-card-icon { color: #3b82f6; }
        .stat-value {
            font-size: 28px;
            font-weight: 900;
            color: var(--slate-900);
            letter-spacing: -1px;
            line-height: 1;
            margin-bottom: 4px;
        }
        .stat-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--slate-400);
        }

        /* ── RATING DISTRIBUTION ── */
        .rating-dist {
            background: var(--slate-50);
            border: 1px solid var(--slate-200);
            border-radius: 14px;
            padding: 20px 24px;
            margin-bottom: 36px;
        }
        .rating-dist-title {
            font-size: 12px;
            font-weight: 800;
            color: var(--slate-600);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }
        .rating-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }
        .rating-row:last-child { margin-bottom: 0; }
        .rating-stars-label {
            display: flex;
            align-items: center;
            gap: 4px;
            width: 72px;
            flex-shrink: 0;
        }
        .star-filled { color: var(--yellow); font-size: 11px; }
        .star-empty  { color: var(--slate-200); font-size: 11px; }
        .rating-bar-wrap {
            flex: 1;
            height: 8px;
            background: var(--slate-200);
            border-radius: 100px;
            overflow: hidden;
        }
        .rating-bar-fill {
            height: 100%;
            border-radius: 100px;
            background: var(--yellow);
        }
        .rating-count {
            width: 28px;
            text-align: right;
            font-size: 11px;
            font-weight: 700;
            color: var(--slate-600);
        }
        .rating-pct {
            width: 36px;
            text-align: right;
            font-size: 10px;
            font-weight: 600;
            color: var(--slate-400);
        }

        /* ── TABLE ── */
        .table-wrap { margin-bottom: 36px; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead th {
            background: var(--slate-900);
            color: rgba(255,255,255,0.7);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 10px 14px;
            text-align: left;
        }
        thead th:first-child { border-radius: 10px 0 0 0; }
        thead th:last-child  { border-radius: 0 10px 0 0; }
        tbody tr { border-bottom: 1px solid var(--slate-100); }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:nth-child(even) td { background: var(--slate-50); }
        tbody td {
            padding: 11px 14px;
            font-size: 12px;
            font-weight: 500;
            color: var(--slate-800);
            vertical-align: top;
        }
        .td-name { font-weight: 700; color: var(--slate-900); }
        .td-comment {
            color: var(--slate-600);
            line-height: 1.5;
            max-width: 240px;
        }
        .td-date { color: var(--slate-400); font-size: 11px; white-space: nowrap; }

        /* Rating badge */
        .badge-rating {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 800;
        }
        .badge-5, .badge-4 { background: var(--green-light);  color: #065f46; }
        .badge-3           { background: var(--yellow-light); color: #92400e; }
        .badge-2, .badge-1 { background: var(--red-light);   color: #991b1b; }

        /* Sentiment pill */
        .pill {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 100px;
            font-size: 10px;
            font-weight: 700;
        }
        .pill-pos { background: var(--green-light); color: #065f46; }
        .pill-neu { background: var(--yellow-light); color: #92400e; }
        .pill-neg { background: var(--red-light);   color: #991b1b; }

        /* row flag for low rating */
        tr.row-alert td:first-child {
            border-left: 3px solid var(--red);
        }

        /* ── PAGE FOOTER ── */
        .page-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 20px;
            border-top: 1px solid var(--slate-200);
            margin-top: 20px;
        }
        .page-footer-left {
            font-size: 10px;
            color: var(--slate-400);
            font-weight: 600;
        }
        .page-footer-right {
            font-size: 10px;
            color: var(--slate-400);
            font-weight: 600;
        }

        /* ── PRINT BUTTON (screen only) ── */
        .print-bar {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 9999;
            display: flex;
            gap: 10px;
        }
        .btn-print {
            background: var(--slate-900);
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 100px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 8px 24px rgba(15,23,42,0.25);
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(15,23,42,0.3);
        }
        .btn-print.orange { background: var(--brand); }
        .btn-close {
            background: #fff;
            color: var(--slate-800);
            border: 1px solid var(--slate-200);
        }
    </style>
</head>
<body>

{{-- ══════════════════════════════════
     COVER PAGE
══════════════════════════════════ --}}
<div class="cover">
    <div class="cover-grid"></div>
    <div class="cover-accent-top"></div>
    <div class="cover-accent-bottom"></div>
    <div class="cover-inner">

        {{-- Logo --}}
        <div class="cover-logo-row">
            <div class="cover-logo-icon">🔧</div>
            <div>
                <div class="cover-logo-text">{{ $bengkel->nama ?? 'Nama Bengkel' }}</div>
                <div class="cover-logo-sub">{{ $bengkel->kota ?? '' }}</div>
            </div>
        </div>

        {{-- Main title --}}
        <div class="cover-body">
            <div class="cover-tag">📋 Laporan Resmi</div>
            <div class="cover-title">Laporan<br><span>Review</span><br>Pelanggan</div>
            <div class="cover-subtitle">Analisis ulasan & kualitas layanan bengkel</div>
            <div class="cover-divider"></div>
            <div class="cover-meta-grid">
                <div class="cover-meta-item">
                    <label>Cabang</label>
                    <p>{{ $bengkel->nama ?? '-' }}</p>
                </div>
                <div class="cover-meta-item">
                    <label>Total Ulasan</label>
                    <p>{{ $totalReview }} Review</p>
                </div>
                <div class="cover-meta-item">
                    <label>Rating Rata-rata</label>
                    <p>⭐ {{ number_format($avgRating, 1) }} / 5.0</p>
                </div>
                <div class="cover-meta-item">
                    <label>Periode</label>
                    <p>{{ $periode }}</p>
                </div>
                <div class="cover-meta-item">
                    <label>Dicetak Oleh</label>
                    <p>{{ auth()->user()->name ?? 'Admin' }}</p>
                </div>
                <div class="cover-meta-item">
                    <label>Tanggal Export</label>
                    <p>{{ now()->translatedFormat('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="cover-footer">
            <div class="cover-footer-left">Dokumen ini digenerate otomatis oleh sistem · Bersifat rahasia</div>
            <div class="cover-footer-badge">CONFIDENTIAL</div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════
     CONTENT PAGES
══════════════════════════════════ --}}
<div class="content-pages">

    {{-- ── SUMMARY STATS ── --}}
    <div class="section-header" style="margin-bottom:16px;">
        <div class="section-dot"></div>
        <div>
            <div class="section-title">Ringkasan Statistik</div>
            <div class="section-subtitle">Overview performa ulasan pelanggan secara keseluruhan</div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card orange">
            <div class="stat-card-icon">💬</div>
            <div class="stat-value">{{ $totalReview }}</div>
            <div class="stat-label">Total Review</div>
        </div>
        <div class="stat-card green">
            <div class="stat-card-icon">⭐</div>
            <div class="stat-value">{{ number_format($avgRating, 1) }}</div>
            <div class="stat-label">Rating Rata-rata</div>
        </div>
        <div class="stat-card yellow">
            <div class="stat-card-icon">😊</div>
            <div class="stat-value">{{ $positifCount }}</div>
            <div class="stat-label">Review Positif (≥4)</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-card-icon">⚠️</div>
            <div class="stat-value">{{ $negatifCount }}</div>
            <div class="stat-label">Perlu Perhatian (≤2)</div>
        </div>
    </div>

    {{-- ── RATING DISTRIBUTION ── --}}
    <div class="rating-dist no-break">
        <div class="rating-dist-title">📊 Distribusi Rating</div>
        @for($star = 5; $star >= 1; $star--)
            @php
                $count = $ratingDist[$star] ?? 0;
                $pct = $totalReview > 0 ? round($count / $totalReview * 100) : 0;
            @endphp
            <div class="rating-row">
                <div class="rating-stars-label">
                    @for($s = 1; $s <= 5; $s++)
                        <span class="{{ $s <= $star ? 'star-filled' : 'star-empty' }}">★</span>
                    @endfor
                </div>
                <div class="rating-bar-wrap">
                    <div class="rating-bar-fill" style="width:{{ $pct }}%;
                        background: {{ $star >= 4 ? 'var(--green)' : ($star == 3 ? 'var(--yellow)' : 'var(--red)') }};"></div>
                </div>
                <div class="rating-count">{{ $count }}</div>
                <div class="rating-pct">{{ $pct }}%</div>
            </div>
        @endfor
    </div>

    {{-- ── REVIEW TABLE ── --}}
    <div class="section-header">
        <div class="section-dot"></div>
        <div>
            <div class="section-title">Daftar Ulasan Pelanggan</div>
            <div class="section-subtitle">{{ $totalReview }} ulasan · Diurutkan terbaru</div>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:28px">#</th>
                    <th style="width:130px">Pelanggan</th>
                    <th style="width:64px">Rating</th>
                    <th style="width:72px">Sentimen</th>
                    <th>Komentar</th>
                    <th style="width:84px">Tanggal</th>
                    <th style="width:64px">Reservasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $i => $review)
                @php
                    $r = $review->rating;
                    $sentClass = $r >= 4 ? 'pill-pos' : ($r == 3 ? 'pill-neu' : 'pill-neg');
                    $sentLabel = $r >= 4 ? 'Positif' : ($r == 3 ? 'Netral' : 'Negatif');
                @endphp
                <tr class="no-break {{ $r <= 2 ? 'row-alert' : '' }}">
                    <td style="color:var(--slate-400);font-size:11px;">{{ $i + 1 }}</td>
                    <td class="td-name">{{ $review->user->name ?? '-' }}</td>
                    <td>
                        <span class="badge-rating badge-{{ $r }}">
                            ★ {{ number_format($r, 1) }}
                        </span>
                    </td>
                    <td><span class="pill {{ $sentClass }}">{{ $sentLabel }}</span></td>
                    <td class="td-comment">{{ Str::limit($review->komentar ?? 'Tidak ada komentar.', 120) }}</td>
                    <td class="td-date">{{ \Carbon\Carbon::parse($review->created_at)->format('d/m/Y') }}<br>{{ \Carbon\Carbon::parse($review->created_at)->format('H:i') }}</td>
                    <td style="font-size:11px;color:var(--slate-400);font-weight:700;">
                        {{ $review->reservasi ? '#'.$review->reservasi->id : '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:24px;color:var(--slate-400);">Tidak ada data review.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── PAGE FOOTER ── --}}
    <div class="page-footer">
        <div class="page-footer-left">
            {{ $bengkel->nama ?? 'Bengkel' }} · {{ $bengkel->kota ?? '' }} · Digenerate {{ now()->format('d/m/Y H:i') }}
        </div>
        <div class="page-footer-right">
            Laporan Review Pelanggan · Halaman <span id="page-num"></span>
        </div>
    </div>

</div>

{{-- Print button (screen only) --}}
<div class="print-bar no-print">
    <button class="btn-print btn-close" onclick="window.close()">✕ Tutup</button>
    <button class="btn-print orange" onclick="window.print()">🖨️ Simpan / Print PDF</button>
</div>

<script>
    // Auto open print dialog after fonts load
    window.addEventListener('load', () => {
        // Small delay to ensure fonts/styles rendered
        setTimeout(() => {
            // uncomment line below if want auto-trigger print:
            // window.print();
        }, 800);
    });
</script>

</body>
</html>