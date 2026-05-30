@extends('layout.admin-cabang')

@section('content')

<style>
    @media print {
        /* ── HIDE UI CHROME ── */
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

        /* ── SHOW PRINT-ONLY ELEMENTS ── */
        .print-only  { display: block !important; }
        .print-flex  { display: flex  !important; }
        .print-header{ display: block !important; }

        /* ── PRINT HEADER ── */
        .print-header {
            margin-bottom: 16px !important;
            padding-bottom: 10px !important;
            border-bottom: 2px solid #f97316 !important;
        }

        /* ── WATERMARK ── */
        .print-watermark {
            position: fixed !important;
            top: 50% !important; left: 50% !important;
            transform: translate(-50%, -50%) rotate(-35deg) !important;
            font-size: 100px !important; font-weight: 900 !important;
            color: rgba(0,0,0,0.04) !important;
            z-index: 9999 !important; pointer-events: none !important;
            white-space: nowrap !important;
        }

        /* ── PRINT FOOTER ── */
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

        /* ══════════════════════════════════════════
        SUMMARY CARDS → UBAH JADI TABEL HORIZONTAL
        ══════════════════════════════════════════ */
        .grid.lg\:grid-cols-4,
        .grid.md\:grid-cols-2.lg\:grid-cols-4 {
            display: table !important;
            width: 100% !important;
            border-collapse: collapse !important;
            margin-bottom: 16px !important;
            border: 1px solid #cbd5e1 !important;
        }

        /* Setiap card jadi sel tabel */
        .grid.lg\:grid-cols-4 > div,
        .grid.md\:grid-cols-2.lg\:grid-cols-4 > div {
            display: table-cell !important;
            width: 25% !important;
            padding: 10px 14px !important;
            border: 1px solid #cbd5e1 !important;
            vertical-align: top !important;
            background: white !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }

        /* Sembunyikan icon, badge trend, elemen dekoratif di card */
        .grid.lg\:grid-cols-4 .w-12,
        .grid.md\:grid-cols-2.lg\:grid-cols-4 .w-12,
        .grid.lg\:grid-cols-4 [class*="bg-blue-50"],
        .grid.lg\:grid-cols-4 [class*="bg-emerald-50"],
        .grid.lg\:grid-cols-4 [class*="bg-orange-50"],
        .grid.lg\:grid-cols-4 [class*="bg-amber-50"],
        .grid.md\:grid-cols-2.lg\:grid-cols-4 .w-12,
        .grid.lg\:grid-cols-4 .mt-4,
        .grid.md\:grid-cols-2.lg\:grid-cols-4 .mt-4 {
            display: none !important;
        }

        /* Label teks di card */
        .grid.lg\:grid-cols-4 p.text-slate-500,
        .grid.md\:grid-cols-2.lg\:grid-cols-4 p.text-slate-500 {
            font-size: 8.5px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            color: #64748b !important;
            margin-bottom: 4px !important;
        }

        /* Angka besar di card */
        .grid.lg\:grid-cols-4 h3,
        .grid.md\:grid-cols-2.lg\:grid-cols-4 h3 {
            font-size: 16px !important;
            font-weight: 800 !important;
            color: #1e293b !important;
            margin: 0 !important;
        }

        /* ── 2-COLUMN LAYOUT ── */
        .grid.lg\:grid-cols-2 {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 10px !important;
            break-inside: avoid !important;
        }

        /* ── SECTION CARDS ── */
        .bg-white {
            border: 1px solid #e2e8f0 !important;
            border-radius: 4px !important;
            box-shadow: none !important;
            break-inside: avoid !important;
        }

        /* ── SECTION HEADER ── */
        .bg-slate-50\/50, .bg-slate-50 {
            background-color: #f8fafc !important;
        }

        /* ── TABLES ── */
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

        /* ── STATUS BADGE → TEXT SAJA ── */
        span[class*="bg-emerald-100"],
        span[class*="bg-amber-100"],
        span[class*="bg-red-50"],
        span[class*="bg-amber-50"] {
            background: none !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            font-weight: 700 !important;
            font-size: 10px !important;
        }

        /* Warna status tetap terbaca */
        span.text-emerald-700 { color: #15803d !important; }
        span.text-amber-700   { color: #b45309 !important; }
        span.text-amber-600   { color: #d97706 !important; }
        span.text-red-600     { color: #dc2626 !important; }

        /* ── TFOOT ROW ── */
        tfoot tr td {
            background-color: #f8fafc !important;
            font-weight: 700 !important;
            font-size: 10px !important;
            border-top: 2px solid #94a3b8 !important;
        }

        /* ── BAR CHART ── */
        div[style*="height: 160px"] {
            height: 120px !important;
        }

        /* ── SPACING ── */
        .space-y-8 > * + * { margin-top: 12px !important; }
        .mb-8 { margin-bottom: 14px !important; }
        .mb-4 { margin-bottom: 8px !important; }
        .gap-6 { gap: 10px !important; }

        /* ── REMOVE LINK ── */
        .p-4.border-t a { display: none !important; }

        /* ── ANIMATE RESET (biar tidak invisible saat print) ── */
        [style*="opacity: 0"] {
            opacity: 1 !important;
            animation: none !important;
        }
        .animate-fade {
            animation: none !important;
            opacity: 1 !important;
        }
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
    .animate-fade {
        animation: fadeIn 0.5s ease-out forwards;
    }
</style>

<!-- Elemen Khusus Print (Disembunyikan di layar biasa) -->
<div class="print-header mb-8 pb-4 border-b-2 border-brand">
    <div class="print-flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-brand mb-1">AUTONEXA</h1>
            <p class="text-sm font-bold text-gray-800">Laporan Analisis Data Operasional Bengkel</p>
            <p class="text-xs text-gray-500">Periode: <span id="printPeriode">01 Mei 2026 - 31 Mei 2026</span></p>
        </div>
        <div class="text-right">
            <h2 class="text-lg font-bold text-gray-800">DOKUMEN RAHASIA</h2>
            <p class="text-xs text-gray-500">Hanya untuk internal manajemen pusat</p>
        </div>
    </div>
</div>

<!-- Watermark Background PDF -->
<div class="print-watermark">AUTONEXA</div>

<!-- Footer PDF dita merubah supaya sesuai dgn role-->
<div class="print-footer">
    <div>Diunduh oleh: <strong>{{ auth()->user()->name }}</strong>
        <span style="font-weight:normal;color:#94a3b8;">
            ({{ auth()->user()->role === 'admin_pusat' ? 'Admin Pusat' : 'Admin Cabang' }})
        </span>
    </div>
    <div id="printDate">Tanggal Cetak: <span id="printDateValue"></span> WIB</div>
</div>

<!-- Container Utama Dashboard -->
<div class="animate-fade no-print">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight flex items-center gap-3">
                Laporan <span class="bg-brand/10 text-brand text-sm px-3 py-1 rounded-full font-bold border border-brand/20">Pusat</span>
            </h2>
            <p class="text-slate-500 mt-2 text-sm font-medium">Analisis data operasional dan performa seluruh cabang bengkel</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="bg-brand hover:bg-brand-dark text-white px-5 py-2.5 rounded-xl shadow-md shadow-brand/20 hover:shadow-lg hover:-translate-y-0.5 transition-all font-bold text-sm flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> Export PDF
            </button>
            <button
                onclick="exportToExcel(
                    '{{ auth()->user()->name }}',
                    '{{ auth()->user()->role === 'admin_pusat' ? 'Admin Pusat' : 'Admin Cabang' }}',
                    document.getElementById('printPeriode')?.textContent ?? '01 Mei 2026 - 31 Mei 2026'
                )"
                class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl shadow-md shadow-emerald-500/20 hover:shadow-lg hover:-translate-y-0.5 transition-all font-bold text-sm flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>
    </div>

    <!-- Filter Section (Wajib) -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 mb-8">
        <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="fas fa-filter text-slate-400"></i> Filter Periode Laporan
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-3">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Mulai</label>
                <input type="date" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand block w-full p-2.5 outline-none font-medium transition-all" value="2026-05-01">
            </div>
            
            <div class="md:col-span-3">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Selesai</label>
                <input type="date" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand block w-full p-2.5 outline-none font-medium transition-all" value="2026-05-31">
            </div>
            
            <!-- Quick Filter -->
            <div class="md:col-span-4 flex items-center gap-2">
                <button class="flex-1 bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200 text-xs font-bold py-3 rounded-xl transition-colors">Hari Ini</button>
                <button class="flex-1 bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200 text-xs font-bold py-3 rounded-xl transition-colors">Minggu Ini</button>
                <button class="flex-1 bg-brand/10 text-brand border border-brand/20 shadow-sm text-xs font-bold py-3 rounded-xl transition-colors">Bulan Ini</button>
            </div>
            
            <!-- Actions -->
            <div class="md:col-span-2 flex gap-2">
                <button class="flex-1 bg-slate-800 hover:bg-slate-900 text-white font-bold py-2.5 rounded-xl text-sm transition-all shadow-sm">
                    Terapkan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Ringkasan Laporan -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-fade" style="animation-delay: 100ms; opacity: 0;">
    <!-- Card 1 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-500 text-sm font-bold mb-1">Total Reservasi</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="1248">0</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-500 border border-blue-100 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs font-bold">
            <span class="text-emerald-500 flex items-center bg-emerald-50 px-2 py-0.5 rounded"><i class="fas fa-arrow-up mr-1"></i> 14.5%</span>
            <span class="text-slate-400 ml-2">vs bulan lalu</span>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-500 text-sm font-bold mb-1">Reservasi Selesai</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="1102">0</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-500 border border-emerald-100 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-check-double"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs font-bold">
            <span class="text-emerald-700 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded">Success Rate: 88%</span>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-500 text-sm font-bold mb-1">Total Pendapatan</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight mt-1 counter-value" data-target="485.2" data-decimals="1" data-prefix="Rp " data-suffix="Jt">Rp 0Jt</h3>
            </div>
            <div class="w-12 h-12 bg-orange-50 text-brand border border-orange-100 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs font-bold">
            <span class="text-emerald-500 flex items-center bg-emerald-50 px-2 py-0.5 rounded"><i class="fas fa-arrow-up mr-1"></i> 8.2%</span>
            <span class="text-slate-400 ml-2">vs bulan lalu</span>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-500 text-sm font-bold mb-1">Rating Rata-rata</p>
                <div class="flex items-center gap-2">
                    <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="4.6" data-decimals="1" data-locale="en-US">0.0</h3>
                    <i class="fas fa-star text-amber-400 text-xl pb-1"></i>
                </div>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-500 border border-amber-100 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                <i class="fas fa-star"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-xs font-bold">
            <span class="text-slate-500 font-medium">Dari total 842 Ulasan</span>
        </div>
    </div>
</div>

<!-- Laporan Utama (Tables) -->
<div class="space-y-8 animate-fade" style="animation-delay: 200ms; opacity: 0;">
    
    <!-- LAPORAN BENGKEL (Performa Penting) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                <i class="fas fa-chart-pie text-brand"></i> Performa Cabang Bengkel
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500">
                        <th class="p-4 font-bold">Nama Bengkel</th>
                        <th class="p-4 font-bold text-center">Total Reservasi</th>
                        <th class="p-4 font-bold text-center">Reservasi Selesai</th>
                        <th class="p-4 font-bold text-right">Total Pendapatan</th>
                        <th class="p-4 font-bold text-center">Rating Rata-rata</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors">
                        <td class="p-4 font-bold text-slate-800">AutoNexa Cabang Sudirman</td>
                        <td class="p-4 text-center text-slate-600 font-medium">542</td>
                        <td class="p-4 text-center"><span class="text-emerald-700 font-bold bg-emerald-50 border border-emerald-100 px-2.5 py-1 rounded text-xs shadow-sm">480</span></td>
                        <td class="p-4 text-right font-black text-slate-800">Rp 215.500.000</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center gap-1 bg-amber-50 border border-amber-100 text-amber-600 font-bold px-2.5 py-1 rounded text-xs shadow-sm">
                                4.8 <i class="fas fa-star text-[10px]"></i>
                            </span>
                        </td>
                    </tr>
                    <tr class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors">
                        <td class="p-4 font-bold text-slate-800">AutoNexa Cabang Kebon Jeruk</td>
                        <td class="p-4 text-center text-slate-600 font-medium">425</td>
                        <td class="p-4 text-center"><span class="text-emerald-700 font-bold bg-emerald-50 border border-emerald-100 px-2.5 py-1 rounded text-xs shadow-sm">380</span></td>
                        <td class="p-4 text-right font-black text-slate-800">Rp 165.250.000</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center gap-1 bg-amber-50 border border-amber-100 text-amber-600 font-bold px-2.5 py-1 rounded text-xs shadow-sm">
                                4.5 <i class="fas fa-star text-[10px]"></i>
                            </span>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="p-4 font-bold text-slate-800">AutoNexa Cabang Kelapa Gading</td>
                        <td class="p-4 text-center text-slate-600 font-medium">281</td>
                        <td class="p-4 text-center"><span class="text-emerald-700 font-bold bg-emerald-50 border border-emerald-100 px-2.5 py-1 rounded text-xs shadow-sm">242</span></td>
                        <td class="p-4 text-right font-black text-slate-800">Rp 104.450.000</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center gap-1 bg-red-50 border border-red-100 text-red-600 font-bold px-2.5 py-1 rounded text-xs shadow-sm">
                                4.2 <i class="fas fa-star text-[10px]"></i>
                            </span>
                        </td>
                    </tr>
                </tbody>
                <tfoot class="bg-slate-50 border-t-2 border-slate-200 font-black text-slate-800">
                    <tr>
                        <td class="p-4 text-right uppercase text-xs tracking-wider">Total Keseluruhan</td>
                        <td class="p-4 text-center">1,248</td>
                        <td class="p-4 text-center text-emerald-600">1,102</td>
                        <td class="p-4 text-right text-brand text-base">Rp 485.200.000</td>
                        <td class="p-4 text-center text-amber-500">4.6</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Print Page Break Marker -->
    <div style="margin-top: 16px;"></div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- LAPORAN RESERVASI -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                    <i class="fas fa-list-alt text-brand"></i> Sampel Reservasi Terbaru
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500">
                            <th class="p-4 font-bold">Pelanggan & Cabang</th>
                            <th class="p-4 font-bold">Layanan</th>
                            <th class="p-4 font-bold">Status</th>
                            <th class="p-4 font-bold text-right">Biaya</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <tr class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors">
                            <td class="p-4">
                                <p class="font-bold text-slate-800">Andi Saputra</p>
                                <p class="text-xs text-slate-500">Cab. Sudirman</p>
                            </td>
                            <td class="p-4 text-slate-600 font-medium">Servis Berkala</td>
                            <td class="p-4">
                                <span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded text-xs font-bold border border-emerald-200 shadow-sm">Selesai</span>
                            </td>
                            <td class="p-4 text-right font-bold text-slate-800">Rp 1.250.000</td>
                        </tr>
                        <tr class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors">
                            <td class="p-4">
                                <p class="font-bold text-slate-800">Budi Setiawan</p>
                                <p class="text-xs text-slate-500">Cab. Kebon Jeruk</p>
                            </td>
                            <td class="p-4 text-slate-600 font-medium">Ganti Oli</td>
                            <td class="p-4">
                                <span class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-bold border border-amber-200 shadow-sm">Dikerjakan</span>
                            </td>
                            <td class="p-4 text-right font-bold text-slate-400">-</td>
                        </tr>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-4">
                                <p class="font-bold text-slate-800">Citra Kirana</p>
                                <p class="text-xs text-slate-500">Cab. Kelapa Gading</p>
                            </td>
                            <td class="p-4 text-slate-600 font-medium">Tune Up Plus</td>
                            <td class="p-4">
                                <span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded text-xs font-bold border border-emerald-200 shadow-sm">Selesai</span>
                            </td>
                            <td class="p-4 text-right font-bold text-slate-800">Rp 850.000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100 text-center no-print">
                <a href="#" class="text-brand font-bold text-sm hover:underline hover:text-brand-dark transition-colors">Lihat Semua Data Reservasi (1,248) &rarr;</a>
            </div>
        </div>

        <!-- LAPORAN PENDAPATAN -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                    <i class="fas fa-chart-bar text-brand"></i> Tren Pendapatan Mingguan
                </h3>
            </div>
            <div class="p-6 flex-1 flex flex-col">
                <div style="height: 160px; display: flex; align-items: flex-end; justify-content: space-between; gap: 12px; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px; position: relative;">
                    <!-- Grid lines -->
                    <div style="position:absolute;width:100%;border-top:1px dashed #e2e8f0;top:25%;"></div>
                    <div style="position:absolute;width:100%;border-top:1px dashed #e2e8f0;top:50%;"></div>
                    <div style="position:absolute;width:100%;border-top:1px dashed #e2e8f0;top:75%;"></div>

                    <!-- Minggu 1 -->
                    <div style="flex:1;display:flex;flex-direction:column;justify-content:flex-end;align-items:center;height:100%;position:relative;">
                        <div style="width:100%;height:60%;background:#fed7aa;border-top:2px solid #f97316;border-radius:4px 4px 0 0;"></div>
                    </div>
                    <!-- Minggu 2 -->
                    <div style="flex:1;display:flex;flex-direction:column;justify-content:flex-end;align-items:center;height:100%;position:relative;">
                        <div style="width:100%;height:75%;background:#fdba74;border-top:2px solid #f97316;border-radius:4px 4px 0 0;"></div>
                    </div>
                    <!-- Minggu 3 -->
                    <div style="flex:1;display:flex;flex-direction:column;justify-content:flex-end;align-items:center;height:100%;position:relative;">
                        <div style="width:100%;height:90%;background:#f97316;border-top:2px solid #ea580c;border-radius:4px 4px 0 0;"></div>
                    </div>
                    <!-- Minggu 4 -->
                    <div style="flex:1;display:flex;flex-direction:column;justify-content:flex-end;align-items:center;height:100%;position:relative;">
                        <div style="width:100%;height:55%;background:#ffedd5;border-top:2px solid #f97316;border-radius:4px 4px 0 0;"></div>
                    </div>
                </div>
                <!-- Label -->
                <div style="display:flex;justify-content:space-between;margin-top:8px;">
                    <span style="flex:1;text-align:center;font-size:11px;font-weight:700;color:#64748b;">Minggu 1<br><span style="font-weight:400;font-size:10px;">Rp 110Jt</span></span>
                    <span style="flex:1;text-align:center;font-size:11px;font-weight:700;color:#64748b;">Minggu 2<br><span style="font-weight:400;font-size:10px;">Rp 125Jt</span></span>
                    <span style="flex:1;text-align:center;font-size:11px;font-weight:700;color:#f97316;">Minggu 3<br><span style="font-weight:400;font-size:10px;">Rp 150Jt</span></span>
                    <span style="flex:1;text-align:center;font-size:11px;font-weight:700;color:#64748b;">Minggu 4<br><span style="font-weight:400;font-size:10px;">Rp 100Jt</span></span>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- Custom Script for Number Animation -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const counters = document.querySelectorAll(".counter-value");
    const duration = 1500; 

    counters.forEach(counter => {
        const target = parseFloat(counter.getAttribute("data-target"));
        const decimals = parseInt(counter.getAttribute("data-decimals")) || 0;
        const prefix = counter.getAttribute("data-prefix") || "";
        const suffix = counter.getAttribute("data-suffix") || "";
        const locale = counter.getAttribute("data-locale") || "id-ID";
        
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const easeProgress = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            const current = easeProgress * target;
            
            counter.innerText = prefix + current.toLocaleString(locale, { minimumFractionDigits: decimals, maximumFractionDigits: decimals }) + suffix;
            
            if (progress < 1) {
                window.requestAnimationFrame(step);
            } else {
                counter.innerText = prefix + target.toLocaleString(locale, { minimumFractionDigits: decimals, maximumFractionDigits: decimals }) + suffix;
            }
        };
        
        window.requestAnimationFrame(step);
    });
});

// Update jam footer print agar selalu real-time saat print ditekan
function updatePrintDate() {
    const now = new Date();
    const day   = now.getDate().toString().padStart(2, '0');
    const month = now.toLocaleString('id-ID', { month: 'long' });
    const year  = now.getFullYear();
    const hours = now.getHours().toString().padStart(2, '0');
    const mins  = now.getMinutes().toString().padStart(2, '0');
    
    const formatted = `${day} ${month} ${year}, ${hours}:${mins}`;
    
    const el = document.getElementById('printDateValue');
    if (el) el.textContent = formatted;
}

// Jalankan saat halaman load (untuk preview)
updatePrintDate();

// Jalankan ulang tepat sebelum print agar jamnya akurat
window.addEventListener('beforeprint', updatePrintDate);
</script>
@endsection
