@extends('layout.admin-cabang')

@section('content')

<style>
    @media print {
        ::-webkit-scrollbar { display: none !important; }
        aside, header, nav, footer,
        .no-print, button, input, select,
        .sidebar, [class*="sidebar"] { display: none !important; }

        html, body {
            background: white !important;
            padding: 0 !important; margin: 0 !important;
            width: 100% !important; height: auto !important;
            overflow: visible !important;
            font-family: 'Inter', Arial, sans-serif !important;
            color: #1e293b !important;
            font-size: 11px !important;
        }

        main, [class*="main"], #main-content {
            padding: 0 !important; margin: 0 !important;
            width: 100% !important; max-width: 100% !important;
            overflow: visible !important;
        }

        *, *::before, *::after {
            overflow: visible !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .print-only  { display: block !important; }
        .print-flex  { display: flex  !important; }
        .print-header{ display: block !important; }

        .print-header {
            margin-bottom: 16px !important;
            padding-bottom: 10px !important;
            border-bottom: 2px solid #f97316 !important;
        }

        .print-watermark {
            position: fixed !important;
            top: 50% !important; left: 50% !important;
            transform: translate(-50%, -50%) rotate(-35deg) !important;
            font-size: 100px !important; font-weight: 900 !important;
            color: rgba(0,0,0,0.04) !important;
            z-index: 9999 !important; pointer-events: none !important;
            white-space: nowrap !important;
        }

        .print-footer {
            position: fixed !important;
            bottom: 0 !important; left: 0 !important; right: 0 !important;
            display: flex !important;
            justify-content: space-between !important;
            font-size: 9px !important; color: #64748b !important;
            border-top: 1px solid #e2e8f0 !important;
            padding: 5px 0 0 !important;
            background: white !important;
        }

        /* Cards jadi tabel horizontal saat print */
        .summary-cards-grid {
            display: table !important;
            width: 100% !important;
            border-collapse: collapse !important;
            margin-bottom: 16px !important;
            border: 1px solid #cbd5e1 !important;
        }
        .summary-cards-grid > div {
            display: table-cell !important;
            width: 25% !important;
            padding: 10px 14px !important;
            border: 1px solid #cbd5e1 !important;
            vertical-align: top !important;
            background: white !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }
        .summary-cards-grid .card-icon { display: none !important; }
        .summary-cards-grid .card-trend { display: none !important; }

        .grid.lg\:grid-cols-2 {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 10px !important;
            break-inside: avoid !important;
        }

        .bg-white {
            border: 1px solid #e2e8f0 !important;
            border-radius: 4px !important;
            box-shadow: none !important;
            break-inside: avoid !important;
        }

        table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 10px !important;
        }
        thead { display: table-header-group !important; }
        tfoot { display: table-footer-group !important; }
        tr    { page-break-inside: avoid !important; }

        th {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
            font-size: 8.5px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.4px !important;
            padding: 6px 10px !important;
            border: 1px solid #cbd5e1 !important;
        }
        td {
            padding: 6px 10px !important;
            border: 1px solid #e2e8f0 !important;
            color: #334155 !important;
            font-size: 10px !important;
        }

        tfoot tr td {
            background-color: #f8fafc !important;
            font-weight: 700 !important;
            border-top: 2px solid #94a3b8 !important;
        }

        .space-y-8 > * + * { margin-top: 12px !important; }
        .mb-8 { margin-bottom: 14px !important; }
        .no-print { display: none !important; }

        [style*="opacity: 0"] { opacity: 1 !important; animation: none !important; }
        .animate-fade { animation: none !important; opacity: 1 !important; }
    }

    @media screen {
        .print-only,
        .print-flex,
        .print-watermark,
        .print-footer,
        .print-header {
            display: none !important;
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fade { animation: fadeIn 0.5s ease-out forwards; }
</style>

{{-- ── PRINT-ONLY ELEMENTS ─────────────────────────────────────── --}}
<div class="print-header mb-8 pb-4 border-b-2 border-brand">
    <div class="print-flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-brand mb-1">AUTONEXA</h1>
            <p class="text-sm font-bold text-gray-800">Laporan Analisis Data Operasional Bengkel</p>
            <p class="text-xs text-gray-500">
                Periode: <span>{{ \Carbon\Carbon::parse($dari)->format('d M Y') }} - {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}</span>
            </p>
        </div>
        <div class="text-right">
            <h2 class="text-lg font-bold text-gray-800">{{ $bengkel->nama ?? 'Admin Cabang' }}</h2>
            <p class="text-xs text-gray-500">Dokumen internal bengkel</p>
        </div>
    </div>
</div>

<div class="print-watermark">AUTONEXA</div>

<div class="print-footer">
    <div>Diunduh oleh: <strong>{{ auth()->user()->name }}</strong>
        <span style="font-weight:normal;color:#94a3b8;">(Admin Cabang)</span>
    </div>
    <div>Tanggal Cetak: <span id="printDateValue"></span> WIB</div>
</div>

{{-- ── HEADER ───────────────────────────────────────────────────── --}}
<div class="animate-fade no-print">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-brand p-6 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-3">
                Laporan
                <span class="bg-brand/10 text-brand text-sm px-3 py-1 rounded-full font-bold border border-brand/20">
                    {{ $bengkel->nama ?? 'Cabang' }}
                </span>
            </h2>
            <p class="text-slate-500 mt-1 text-sm font-medium">
                Analisis data operasional bengkel cabang Anda
            </p>
            <p class="text-xs text-slate-400 mt-1">
                Periode:
                <strong class="text-slate-600">{{ \Carbon\Carbon::parse($dari)->format('d M Y') }}</strong>
                s/d
                <strong class="text-slate-600">{{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}</strong>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()"
                    class="bg-brand hover:bg-brand-dark text-white px-5 py-2.5 rounded-xl shadow-md shadow-brand/20 hover:shadow-lg hover:-translate-y-0.5 transition-all font-bold text-sm flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> Export PDF
            </button>

            <button onclick="exportToExcel()"
                    class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl shadow-md shadow-emerald-500/20 hover:shadow-lg hover:-translate-y-0.5 transition-all font-bold text-sm flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>
    </div>
</div>

{{-- ── FILTER ───────────────────────────────────────────────────── --}}
<div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 mb-6 no-print animate-fade" style="animation-delay:50ms;opacity:0;">
    <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
        <i class="fas fa-filter text-slate-400"></i> Filter Periode Laporan
    </h3>
    <form method="GET" action="{{ route('admin-cabang.laporan') }}">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-3">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Mulai</label>
                <input type="date" name="dari"
                       class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand block w-full p-2.5 outline-none font-medium transition-all"
                       value="{{ $dari }}">
            </div>
            <div class="md:col-span-3">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Selesai</label>
                <input type="date" name="sampai"
                       class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand block w-full p-2.5 outline-none font-medium transition-all"
                       value="{{ $sampai }}">
            </div>
            <div class="md:col-span-4 flex items-center gap-2">
                <button type="button" onclick="setFilter('today')"
                        class="flex-1 bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200 text-xs font-bold py-3 rounded-xl transition-colors">
                    Hari Ini
                </button>
                <button type="button" onclick="setFilter('week')"
                        class="flex-1 bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200 text-xs font-bold py-3 rounded-xl transition-colors">
                    Minggu Ini
                </button>
                <button type="button" onclick="setFilter('month')"
                        class="flex-1 bg-brand/10 text-brand border border-brand/20 shadow-sm text-xs font-bold py-3 rounded-xl transition-colors">
                    Bulan Ini
                </button>
            </div>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit"
                        class="flex-1 bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 rounded-xl text-sm transition-all shadow-sm">
                    Terapkan
                </button>
            </div>
        </div>
    </form>
</div>

{{-- ══════════════════════════════════════════════════════
     SUMMARY CARDS — 4 kolom dalam 1 baris (seperti dashboard)
══════════════════════════════════════════════════════ --}}
<div class="summary-cards-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6 animate-fade" style="animation-delay:100ms;opacity:0;">

    {{-- Card 1: Total Reservasi --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-500 text-sm font-bold mb-1">Total Reservasi</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight">
                    {{ number_format($totalReservasi ?? 0) }}
                </h3>
            </div>
            <div class="card-icon w-12 h-12 bg-blue-50 text-blue-500 border border-blue-100 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <div class="card-trend mt-4 text-xs font-bold text-slate-400">Periode ini</div>
    </div>

    {{-- Card 2: Reservasi Selesai --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-500 text-sm font-bold mb-1">Reservasi Selesai</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight">
                    {{ number_format($selesai ?? 0) }}
                </h3>
            </div>
            <div class="card-icon w-12 h-12 bg-emerald-50 text-emerald-500 border border-emerald-100 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-check-double"></i>
            </div>
        </div>
        <div class="card-trend mt-4 flex items-center text-xs font-bold">
            <span class="text-emerald-700 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded">
                Success Rate:
                {{ ($totalReservasi ?? 0) > 0 ? round(($selesai / $totalReservasi) * 100) : 0 }}%
            </span>
        </div>
    </div>

    {{-- Card 3: Total Pendapatan --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-500 text-sm font-bold mb-1">Total Pendapatan</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight mt-1">
                    Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                </h3>
            </div>
            <div class="card-icon w-12 h-12 bg-orange-50 text-brand border border-orange-100 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
        <div class="card-trend mt-4 text-xs font-bold text-slate-400">Dari reservasi selesai</div>
    </div>

    {{-- Card 4: Rating --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-500 text-sm font-bold mb-1">Rating Rata-rata</p>
                <div class="flex items-center gap-2">
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight">
                        {{ number_format($avgRating ?? 0, 1) }}
                    </h3>
                    <i class="fas fa-star text-amber-400 text-xl pb-1"></i>
                </div>
            </div>
            <div class="card-icon w-12 h-12 bg-amber-50 text-amber-500 border border-amber-100 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-star"></i>
            </div>
        </div>
        <div class="card-trend mt-4 text-xs font-bold text-slate-400">
            Dari {{ number_format($totalReview ?? 0) }} ulasan
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════
     TABEL UTAMA
══════════════════════════════════════════════════════ --}}
<div class="space-y-8 animate-fade" style="animation-delay:200ms;opacity:0;">

    {{-- PERFORMA BENGKEL (data dari DB, hanya 1 bengkel ini) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                <i class="fas fa-chart-pie text-brand"></i> Ringkasan Performa Bengkel
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500">
                        <th class="p-4 font-bold">Nama Bengkel</th>
                        <th class="p-4 font-bold text-center">Total Reservasi</th>
                        <th class="p-4 font-bold text-center">Selesai</th>
                        <th class="p-4 font-bold text-center">Dibatalkan</th>
                        <th class="p-4 font-bold text-right">Total Pendapatan</th>
                        <th class="p-4 font-bold text-center">Rating</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="p-4 font-bold text-slate-800">
                            {{ $bengkel->nama ?? '-' }}
                        </td>
                        <td class="p-4 text-center text-slate-600 font-medium">
                            {{ number_format($totalReservasi ?? 0) }}
                        </td>
                        <td class="p-4 text-center">
                            <span class="text-emerald-700 font-bold bg-emerald-50 border border-emerald-100 px-2.5 py-1 rounded text-xs shadow-sm">
                                {{ number_format($selesai ?? 0) }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <span class="text-red-600 font-bold bg-red-50 border border-red-100 px-2.5 py-1 rounded text-xs shadow-sm">
                                {{ number_format($dibatalkan ?? 0) }}
                            </span>
                        </td>
                        <td class="p-4 text-right font-black text-slate-800">
                            Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="p-4 text-center">
                            @if(($avgRating ?? 0) >= 4.5)
                                <span class="inline-flex items-center gap-1 bg-amber-50 border border-amber-100 text-amber-600 font-bold px-2.5 py-1 rounded text-xs shadow-sm">
                                    {{ number_format($avgRating ?? 0, 1) }} <i class="fas fa-star text-[10px]"></i>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-red-50 border border-red-100 text-red-600 font-bold px-2.5 py-1 rounded text-xs shadow-sm">
                                    {{ number_format($avgRating ?? 0, 1) }} <i class="fas fa-star text-[10px]"></i>
                                </span>
                            @endif
                        </td>
                    </tr>
                </tbody>
                <tfoot class="bg-slate-50 border-t-2 border-slate-200 font-black text-slate-800">
                    <tr>
                        <td class="p-4 text-xs uppercase tracking-wider text-slate-500">Total Periode Ini</td>
                        <td class="p-4 text-center">{{ number_format($totalReservasi ?? 0) }}</td>
                        <td class="p-4 text-center text-emerald-600">{{ number_format($selesai ?? 0) }}</td>
                        <td class="p-4 text-center text-red-500">{{ number_format($dibatalkan ?? 0) }}</td>
                        <td class="p-4 text-right text-brand text-base">
                            Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="p-4 text-center text-amber-500">{{ number_format($avgRating ?? 0, 1) }} ★</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- TABEL RESERVASI — dari DB --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                    <i class="fas fa-list-alt text-brand"></i> Data Reservasi
                </h3>
                <span class="text-xs text-slate-400 font-medium">{{ ($reservasis ?? collect())->count() }} data</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500">
                            <th class="p-4 font-bold">Pelanggan</th>
                            <th class="p-4 font-bold">Layanan</th>
                            <th class="p-4 font-bold">Status</th>
                            <th class="p-4 font-bold text-right">Biaya</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse(($reservasis ?? collect())->take(5) as $r)
                        @php
                            $sMap = [
                                'pending'      => ['Menunggu',     'bg-slate-100 text-slate-600'],
                                'dikonfirmasi' => ['Dikonfirmasi', 'bg-blue-100 text-blue-700'],
                                'in_progress'  => ['Dikerjakan',   'bg-amber-100 text-amber-700'],
                                'done'         => ['Selesai',      'bg-emerald-100 text-emerald-700'],
                                'cancelled'    => ['Dibatalkan',   'bg-red-100 text-red-600'],
                            ];
                            [$label, $cls] = $sMap[$r->status] ?? [ucfirst($r->status), 'bg-slate-100 text-slate-600'];
                        @endphp
                        <tr class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors">
                            <td class="p-4">
                                <p class="font-bold text-slate-800">{{ $r->user->name ?? '-' }}</p>
                                <p class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($r->tanggal)->format('d M Y') }}</p>
                            </td>
                            <td class="p-4 text-slate-600 font-medium text-sm">
                                {{ $r->layanan ?? $r->keluhan ?? '-' }}
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded text-xs font-bold border {{ $cls }} border-opacity-30">
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="p-4 text-right font-bold text-slate-800">
                                @if(($r->total_biaya ?? 0) > 0)
                                    Rp {{ number_format($r->total_biaya, 0, ',', '.') }}
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-slate-400 text-sm italic">
                                Tidak ada reservasi pada periode ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if(($reservasis ?? collect())->isNotEmpty())
                    <tfoot class="bg-slate-50 border-t-2 border-slate-200">
                        <tr>
                            <td colspan="3" class="p-4 text-xs uppercase tracking-wider text-slate-500 font-bold text-right">
                                Total Pendapatan
                            </td>
                            <td class="p-4 text-right font-black text-brand">
                                Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            @if(($reservasis ?? collect())->count() > 5)
            <div class="p-4 border-t border-slate-100 text-center no-print">
                <span class="text-brand font-bold text-sm">
                    + {{ ($reservasis ?? collect())->count() - 5 }} data lainnya (lihat di Export PDF)
                </span>
            </div>
            @endif
        </div>

        {{-- TABEL PENDAPATAN HARIAN — dari DB --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                    <i class="fas fa-chart-bar text-brand"></i> Pendapatan Harian
                </h3>
                <span class="text-xs text-slate-400 font-medium">{{ ($pendapatanHarian ?? collect())->count() }} hari</span>
            </div>

            @if(($pendapatanHarian ?? collect())->isEmpty())
            <div class="p-6 text-center text-slate-400 text-sm italic flex-1 flex items-center justify-center">
                Belum ada transaksi selesai pada periode ini
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500">
                            <th class="p-4 font-bold">Tanggal</th>
                            <th class="p-4 font-bold text-center">Transaksi</th>
                            <th class="p-4 font-bold text-right">Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach(($pendapatanHarian ?? collect())->take(7) as $p)
                        <tr class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors">
                            <td class="p-4 text-slate-600 font-medium">
                                {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                            </td>
                            <td class="p-4 text-center">
                                <span class="bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded text-xs font-bold">
                                    {{ $p->jumlah_transaksi }} trx
                                </span>
                            </td>
                            <td class="p-4 text-right font-bold text-slate-800">
                                Rp {{ number_format($p->total ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50 border-t-2 border-slate-200">
                        <tr>
                            <td class="p-4 text-xs uppercase tracking-wider text-slate-500 font-bold text-right" colspan="2">
                                Total
                            </td>
                            <td class="p-4 text-right font-black text-brand">
                                Rp {{ number_format(($pendapatanHarian ?? collect())->sum('total'), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif
        </div>

    </div>

    {{-- TABEL REVIEW — dari DB --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                <i class="fas fa-star text-brand"></i> Review Pelanggan
            </h3>
            <span class="text-xs text-slate-400 font-medium">{{ ($reviews ?? collect())->count() }} ulasan</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500">
                        <th class="p-4 font-bold">Pelanggan</th>
                        <th class="p-4 font-bold text-center">Rating</th>
                        <th class="p-4 font-bold">Komentar</th>
                        <th class="p-4 font-bold">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse(($reviews ?? collect())->take(5) as $rv)
                    <tr class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors">
                        <td class="p-4 font-bold text-slate-800">
                            {{ $rv->user->name ?? '-' }}
                        </td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center gap-1 bg-amber-50 border border-amber-100 text-amber-600 font-bold px-2.5 py-1 rounded text-xs">
                                {{ $rv->rating }} <i class="fas fa-star text-[10px]"></i>
                            </span>
                        </td>
                        <td class="p-4 text-slate-500 text-xs">
                            {{ $rv->komentar ?? $rv->ulasan ?? '-' }}
                        </td>
                        <td class="p-4 text-slate-400 text-xs">
                            {{ \Carbon\Carbon::parse($rv->created_at)->format('d M Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-slate-400 text-sm italic">
                            Belum ada review pada periode ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
// Quick filter buttons
function setFilter(type) {
    const dari   = document.querySelector('input[name="dari"]');
    const sampai = document.querySelector('input[name="sampai"]');
    const now    = new Date();
    const pad = n => String(n).padStart(2, '0');
    const fmt = d => `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
    if (type === 'today') {
        dari.value = sampai.value = fmt(now);
    } else if (type === 'week') {
        const day  = now.getDay() || 7;
        const mon  = new Date(now); mon.setDate(now.getDate() - day + 1);
        const sun  = new Date(mon); sun.setDate(mon.getDate() + 6);
        dari.value = fmt(mon); sampai.value = fmt(sun);
    } else if (type === 'month') {
        dari.value   = fmt(new Date(now.getFullYear(), now.getMonth(), 1));
        sampai.value = fmt(new Date(now.getFullYear(), now.getMonth() + 1, 0));
    }
}

// ── Inject data dari PHP dulu sebagai variabel terpisah ──
var _reservasis     = {!! json_encode(($reservasis ?? collect())->map(fn($r) => [
    'nama'        => $r->user->name ?? '-',
    'tanggal'     => \Carbon\Carbon::parse($r->tanggal)->format('d M Y'),
    'layanan'     => $r->layanan ?? $r->keluhan ?? '-',
    'status'      => $r->status,
    'total_biaya' => $r->total_biaya ?? 0,
])->values()) !!};

var _pendapatanHarian = {!! json_encode(($pendapatanHarian ?? collect())->map(fn($p) => [
    'tanggal'          => \Carbon\Carbon::parse($p->tanggal)->format('d M Y'),
    'jumlah_transaksi' => $p->jumlah_transaksi,
    'total'            => $p->total ?? 0,
])->values()) !!};

var _reviews = {!! json_encode(($reviews ?? collect())->map(fn($rv) => [
    'nama'     => $rv->user->name ?? '-',
    'rating'   => $rv->rating,
    'komentar' => $rv->komentar ?? $rv->ulasan ?? '-',
    'tanggal'  => \Carbon\Carbon::parse($rv->created_at)->format('d M Y'),
])->values()) !!};

// ── Baru assign ke laporanData ──
const laporanData = {
    bengkel:         "{{ addslashes($bengkel->nama ?? '-') }}",
    dari:            "{{ \Carbon\Carbon::parse($dari)->format('d M Y') }}",
    sampai:          "{{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}",
    totalReservasi:  {{ $totalReservasi ?? 0 }},
    selesai:         {{ $selesai ?? 0 }},
    dibatalkan:      {{ $dibatalkan ?? 0 }},
    totalPendapatan: {{ $totalPendapatan ?? 0 }},
    totalReview:     {{ $totalReview ?? 0 }},
    avgRating:       {{ number_format($avgRating ?? 0, 1, '.', '') }},
    reservasis:      _reservasis,
    pendapatanHarian:_pendapatanHarian,
    reviews:         _reviews,
};

function updatePrintDate() {
    const now   = new Date();
    const pad   = n => String(n).padStart(2, '0');
    const bulan = ['Januari','Februari','Maret','April','Mei','Juni',
                   'Juli','Agustus','September','Oktober','November','Desember'];
    const el    = document.getElementById('printDateValue');
    if (el) el.textContent =
        `${pad(now.getDate())} ${bulan[now.getMonth()]} ${now.getFullYear()}, ${pad(now.getHours())}:${pad(now.getMinutes())}`;
}
updatePrintDate();
window.addEventListener('beforeprint', updatePrintDate);
</script>

<script>
'use strict';

const COLOR = {
    brand:     '#ff6a00',
    brandDark: '#e65c00',
    brandLight:'#fff7ed',
    emerald:   '#059669',
    emeraldBg: '#d1fae5',
    amber:     '#d97706',
    amberBg:   '#fef3c7',
    red:       '#dc2626',
    redBg:     '#fee2e2',
    slate800:  '#1e293b',
    slate500:  '#64748b',
    slate200:  '#e2e8f0',
    white:     '#ffffff',
};

function getTodayString() {
    const d = new Date();
    return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
}

function fmt(num) {
    return 'Rp ' + Number(num).toLocaleString('id-ID');
}

function cssHeader() {
    return `background-color:${COLOR.brand};color:#fff;font-weight:bold;font-size:13px;
            text-align:center;padding:12px 16px;border:1px solid ${COLOR.brandDark};white-space:nowrap;`;
}

function cssData(isEven = false, extra = '') {
    return `background-color:${isEven ? COLOR.brandLight : COLOR.white};font-size:12px;
            padding:10px 16px;border:1px solid ${COLOR.slate200};${extra}`;
}

function cssTotal(extra = '') {
    return `background-color:${COLOR.brandLight};font-size:12px;font-weight:bold;color:${COLOR.brand};
            padding:10px 16px;border-top:2px solid ${COLOR.brand};border:1px solid ${COLOR.slate200};${extra}`;
}

function cssKop(size, color, extra = '') {
    return `font-size:${size}px;font-weight:bold;color:${color};padding:8px 0;border:none;background:none;${extra}`;
}

function cssInfo(bold = false) {
    return `font-size:12px;${bold ? 'font-weight:bold;' : ''}color:${COLOR.slate500};
            padding:4px 0;border:none;background:none;`;
}

const statusMap = {
    'pending':      { label: 'Menunggu',     color: COLOR.slate500, bg: '#f1f5f9' },
    'dikonfirmasi': { label: 'Dikonfirmasi', color: '#1e40af',      bg: '#dbeafe' },
    'in_progress':  { label: 'Dikerjakan',   color: COLOR.amber,    bg: COLOR.amberBg },
    'done':         { label: 'Selesai',      color: COLOR.emerald,  bg: COLOR.emeraldBg },
    'cancelled':    { label: 'Dibatalkan',   color: COLOR.red,      bg: COLOR.redBg },
};

function exportToExcel() {
    if (typeof laporanData === 'undefined') {
        alert('Data laporan tidak tersedia.');
        return;
    }

    const d       = laporanData;
    const periode = `${d.dari} - ${d.sampai}`;
    const dicetak = document.querySelector('.print-footer strong')?.innerText ?? '-';
    const now     = new Date().toLocaleString('id-ID');
    const COLS    = 6;

    const sukses = d.totalReservasi > 0
        ? Math.round((d.selesai / d.totalReservasi) * 100)
        : 0;

    // ── KOP ──────────────────────────────────────────
    const kop = `
        <tr><td colspan="${COLS}" style="${cssKop(22, COLOR.brand)}">AUTONEXA</td></tr>
        <tr><td colspan="${COLS}" style="${cssKop(13, COLOR.slate500)}">Laporan Operasional Bengkel — ${d.bengkel}</td></tr>
        <tr><td colspan="${COLS}" style="border:none;border-bottom:2px solid ${COLOR.brand};padding:0;background:none;"></td></tr>
        <tr><td style="${cssInfo(true)}">Bengkel</td>
            <td colspan="${COLS-1}" style="${cssInfo()}">${d.bengkel}</td></tr>
        <tr><td style="${cssInfo(true)}">Periode</td>
            <td colspan="${COLS-1}" style="${cssInfo()}">${periode}</td></tr>
        <tr><td style="${cssInfo(true)}">Dicetak oleh</td>
            <td colspan="${COLS-1}" style="${cssInfo()}">${dicetak} (Admin Cabang)</td></tr>
        <tr><td style="${cssInfo(true)}">Tanggal Cetak</td>
            <td colspan="${COLS-1}" style="${cssInfo()}">${now}</td></tr>
        <tr><td colspan="${COLS}" style="border:none;padding:8px;background:none;"></td></tr>
    `;

    // ── SECTION 1: RINGKASAN ─────────────────────────
    const ringkasan = [
        { metrik: 'Total Reservasi',      nilai: d.totalReservasi,                      ket: 'Periode ini',              color: COLOR.slate800 },
        { metrik: 'Reservasi Selesai',    nilai: d.selesai,                             ket: `Success Rate: ${sukses}%`, color: COLOR.emerald  },
        { metrik: 'Reservasi Dibatalkan', nilai: d.dibatalkan,                          ket: 'Periode ini',              color: COLOR.red      },
        { metrik: 'Total Pendapatan',     nilai: fmt(d.totalPendapatan),                ket: 'Dari reservasi selesai',   color: COLOR.brand    },
        { metrik: 'Total Review',         nilai: d.totalReview,                         ket: 'Ulasan pelanggan',         color: COLOR.slate800 },
        { metrik: 'Rating Rata-rata',     nilai: `${Number(d.avgRating).toFixed(1)} ★`, ket: `Dari ${d.totalReview} ulasan`, color: COLOR.amber },
    ];

    const ringkasanRows = ringkasan.map((r, i) => `
        <tr>
            <td colspan="3" style="${cssData(i%2===1,'font-weight:bold;')}">${r.metrik}</td>
            <td style="${cssData(i%2===1,`text-align:center;font-weight:bold;font-size:14px;color:${r.color};`)}">${r.nilai}</td>
            <td colspan="2" style="${cssData(i%2===1)}">${r.ket}</td>
        </tr>
    `).join('');

    const secRingkasan = `
        <tr><td colspan="${COLS}" style="font-size:15px;font-weight:bold;color:${COLOR.brand};
            padding:16px 0 8px;border:none;background:none;">📊 Ringkasan Periode</td></tr>
        <tr>
            <th colspan="3" style="${cssHeader()}">Metrik</th>
            <th style="${cssHeader()}">Nilai</th>
            <th colspan="2" style="${cssHeader()}">Keterangan</th>
        </tr>
        ${ringkasanRows}
        <tr><td colspan="${COLS}" style="border:none;padding:8px;background:none;"></td></tr>
    `;

    // ── SECTION 2: PERFORMA BENGKEL ──────────────────
    const secPerforma = `
        <tr><td colspan="${COLS}" style="font-size:15px;font-weight:bold;color:${COLOR.brand};
            padding:16px 0 8px;border:none;background:none;">🏪 Ringkasan Performa Bengkel</td></tr>
        <tr>
            <th colspan="2" style="${cssHeader()}">Nama Bengkel</th>
            <th style="${cssHeader()}">Total Reservasi</th>
            <th style="${cssHeader()}">Selesai</th>
            <th style="${cssHeader()}">Dibatalkan</th>
            <th style="${cssHeader()}">Total Pendapatan</th>
        </tr>
        <tr>
            <td colspan="2" style="${cssData(false,'font-weight:bold;')}">${d.bengkel}</td>
            <td style="${cssData(false,'text-align:center;')}">${d.totalReservasi}</td>
            <td style="${cssData(false,`text-align:center;font-weight:bold;color:${COLOR.emerald};`)}">${d.selesai}</td>
            <td style="${cssData(false,`text-align:center;font-weight:bold;color:${COLOR.red};`)}">${d.dibatalkan}</td>
            <td style="${cssData(false,'text-align:right;font-weight:bold;')}">${fmt(d.totalPendapatan)}</td>
        </tr>
        <tr><td colspan="${COLS}" style="border:none;padding:8px;background:none;"></td></tr>
    `;

    // ── SECTION 3: RESERVASI ─────────────────────────
    const resRows = d.reservasis.length
        ? d.reservasis.map((r, i) => {
            const st = statusMap[r.status] ?? { label: r.status, color: COLOR.slate500, bg: '#f1f5f9' };
            return `
                <tr>
                    <td style="${cssData(i%2===1,'font-weight:bold;')}">${r.nama}</td>
                    <td style="${cssData(i%2===1)}">${r.tanggal}</td>
                    <td colspan="2" style="${cssData(i%2===1)}">${r.layanan}</td>
                    <td style="${cssData(i%2===1,`text-align:center;font-weight:bold;color:${st.color};background-color:${st.bg};`)}">${st.label}</td>
                    <td style="${cssData(i%2===1,'text-align:right;font-weight:bold;')}">${r.total_biaya > 0 ? fmt(r.total_biaya) : '—'}</td>
                </tr>`;
          }).join('')
        : `<tr><td colspan="${COLS}" style="${cssData()}text-align:center;color:${COLOR.slate500};font-style:italic;">
               Tidak ada data reservasi</td></tr>`;

    const totalBiaya = d.reservasis.reduce((s, r) => s + Number(r.total_biaya || 0), 0);

    const secReservasi = `
        <tr><td colspan="${COLS}" style="font-size:15px;font-weight:bold;color:${COLOR.brand};
            padding:16px 0 8px;border:none;background:none;">📋 Data Reservasi</td></tr>
        <tr>
            <th style="${cssHeader()}">Pelanggan</th>
            <th style="${cssHeader()}">Tanggal</th>
            <th colspan="2" style="${cssHeader()}">Layanan</th>
            <th style="${cssHeader()}">Status</th>
            <th style="${cssHeader()}">Biaya</th>
        </tr>
        ${resRows}
        <tr>
            <td colspan="5" style="${cssTotal('text-align:right;')}">TOTAL PENDAPATAN</td>
            <td style="${cssTotal('text-align:right;')}">${fmt(totalBiaya)}</td>
        </tr>
        <tr><td colspan="${COLS}" style="border:none;padding:8px;background:none;"></td></tr>
    `;

    // ── SECTION 4: PENDAPATAN HARIAN ─────────────────
    const phRows = d.pendapatanHarian.length
        ? d.pendapatanHarian.map((p, i) => `
            <tr>
                <td colspan="2" style="${cssData(i%2===1)}">${p.tanggal}</td>
                <td style="${cssData(i%2===1,'text-align:center;')}">${p.jumlah_transaksi} trx</td>
                <td colspan="3" style="${cssData(i%2===1,'text-align:right;font-weight:bold;')}">${fmt(p.total)}</td>
            </tr>`).join('')
        : `<tr><td colspan="${COLS}" style="${cssData()}text-align:center;color:${COLOR.slate500};font-style:italic;">
               Belum ada transaksi selesai</td></tr>`;

    const totalHarian = d.pendapatanHarian.reduce((s, p) => s + Number(p.total || 0), 0);

    const secPendapatan = `
        <tr><td colspan="${COLS}" style="font-size:15px;font-weight:bold;color:${COLOR.brand};
            padding:16px 0 8px;border:none;background:none;">💰 Pendapatan Harian</td></tr>
        <tr>
            <th colspan="2" style="${cssHeader()}">Tanggal</th>
            <th style="${cssHeader()}">Jumlah Transaksi</th>
            <th colspan="3" style="${cssHeader()}">Total Pendapatan</th>
        </tr>
        ${phRows}
        <tr>
            <td colspan="3" style="${cssTotal('text-align:right;')}">TOTAL</td>
            <td colspan="3" style="${cssTotal('text-align:right;')}">${fmt(totalHarian)}</td>
        </tr>
        <tr><td colspan="${COLS}" style="border:none;padding:8px;background:none;"></td></tr>
    `;

    // ── SECTION 5: REVIEW ────────────────────────────
    const rvRows = d.reviews.length
        ? d.reviews.map((rv, i) => `
            <tr>
                <td style="${cssData(i%2===1,'font-weight:bold;')}">${rv.nama}</td>
                <td style="${cssData(i%2===1,`text-align:center;font-weight:bold;color:${COLOR.amber};`)}">${rv.rating} ★</td>
                <td colspan="3" style="${cssData(i%2===1)}">${rv.komentar}</td>
                <td style="${cssData(i%2===1)}">${rv.tanggal}</td>
            </tr>`).join('')
        : `<tr><td colspan="${COLS}" style="${cssData()}text-align:center;color:${COLOR.slate500};font-style:italic;">
               Belum ada review</td></tr>`;

    const secReview = `
        <tr><td colspan="${COLS}" style="font-size:15px;font-weight:bold;color:${COLOR.brand};
            padding:16px 0 8px;border:none;background:none;">⭐ Review Pelanggan</td></tr>
        <tr>
            <th style="${cssHeader()}">Pelanggan</th>
            <th style="${cssHeader()}">Rating</th>
            <th colspan="3" style="${cssHeader()}">Komentar</th>
            <th style="${cssHeader()}">Tanggal</th>
        </tr>
        ${rvRows}
    `;

    // ── GABUNG & DOWNLOAD ─────────────────────────────
    const html = `
        <html xmlns:o="urn:schemas-microsoft-com:office:office"
              xmlns:x="urn:schemas-microsoft-com:office:excel"
              xmlns="http://www.w3.org/TR/REC-html40">
        <head>
            <meta charset="UTF-8">
            <style>
                body { font-family: Calibri, Arial, sans-serif; }
                table { border-collapse: collapse; }
                td, th { mso-number-format:"@"; }
            </style>
        </head>
        <body>
            <table>${kop}${secRingkasan}${secPerforma}${secReservasi}${secPendapatan}${secReview}</table>
        </body>
        </html>`;

    const blob = new Blob([html], { type: 'application/vnd.ms-excel;charset=utf-8' });
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href     = url;
    a.download = `Laporan-${laporanData.bengkel}-${getTodayString()}.xls`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}
</script>
@endsection