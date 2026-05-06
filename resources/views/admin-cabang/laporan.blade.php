@extends('layout.admin-cabang')

@section('content')

<!-- Style untuk Print (Export PDF via Browser) -->
<style>
    @media print {
        /* Sembunyikan elemen UI dashboard dan Sidebar */
        aside, header, nav, .no-print, button, input, select {
            display: none !important;
        }
        
        /* Reset background dan spacing khusus untuk A4/Kertas */
        body {
            background-color: white !important;
            padding: 0 !important;
            margin: 0 !important;
            color: black !important;
            font-family: 'Inter', sans-serif !important;
        }
        
        main {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            margin-left: 0 !important;
        }

        /* Hilangkan shadow dan border radius berlebih di print */
        .shadow-sm, .rounded-2xl, .border {
            box-shadow: none !important;
            border-radius: 0 !important;
            border: 1px solid #e2e8f0 !important;
        }

        /* Tampilkan elemen khusus print */
        .print-only {
            display: block !important;
        }
        
        .print-flex {
            display: flex !important;
        }

        /* Watermark Resmi */
        .print-watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 150px;
            color: rgba(0, 0, 0, 0.04);
            font-weight: 900;
            z-index: -1;
            white-space: nowrap;
            letter-spacing: 10px;
        }

        /* Footer Laporan */
        .print-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 5px;
            display: flex !important;
            justify-content: space-between;
        }

        /* Styling Tabel Laporan untuk Print */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #f8fafc !important;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            font-weight: bold;
        }
        
        /* Force background colors to print (Tailwind bg colors) */
        * {
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
        }
        
        .print-break-page {
            page-break-before: always;
        }
    }

    /* Sembunyikan elemen print di layar biasa (Dashboard) */
    @media screen {
        .print-only, .print-flex, .print-watermark, .print-footer, .print-header {
            display: none !important;
        }
    }

    /* Animasi Layar */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
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

<!-- Footer PDF -->
<div class="print-footer">
    <div>Diunduh oleh: <strong>Admin Pusat (Budi Hartono)</strong></div>
    <div id="printDate">Tanggal Cetak: {{ date('d M Y, H:i') }} WIB</div>
</div>

<!-- Akhir Elemen Khusus Print -->

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
            <button class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-2.5 rounded-xl shadow-md shadow-emerald-500/20 hover:shadow-lg hover:-translate-y-0.5 transition-all font-bold text-sm flex items-center gap-2">
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
            <!-- Tanggal Mulai -->
            <div class="md:col-span-3">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Mulai</label>
                <input type="date" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand block w-full p-2.5 outline-none font-medium transition-all" value="2026-05-01">
            </div>
            
            <!-- Tanggal Selesai -->
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
    
    <!-- C. LAPORAN BENGKEL (Performa Penting) -->
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
    <div class="print-break-page"></div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- A. LAPORAN RESERVASI (Detail Terakhir) -->
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

        <!-- B. LAPORAN PENDAPATAN (Tren Visual) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                    <i class="fas fa-chart-bar text-brand"></i> Tren Pendapatan Mingguan
                </h3>
            </div>
            <div class="p-6 flex-1 flex flex-col">
                <!-- Simple HTML/CSS Bar Chart -->
                <div class="h-48 flex items-end justify-between gap-3 border-b border-slate-200 pb-2 mb-4 mt-2 flex-1 relative">
                    <!-- Garis bantu background -->
                    <div class="absolute w-full border-t border-dashed border-slate-200 top-1/4 z-0"></div>
                    <div class="absolute w-full border-t border-dashed border-slate-200 top-2/4 z-0"></div>
                    <div class="absolute w-full border-t border-dashed border-slate-200 top-3/4 z-0"></div>

                    <!-- Week 1 -->
                    <div class="w-full flex flex-col justify-end items-center group relative cursor-pointer z-10 h-full">
                        <div class="absolute -top-8 bg-slate-800 text-white text-xs font-bold py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity shadow-md pointer-events-none">Rp 110Jt</div>
                        <div class="w-full bg-brand/30 hover:bg-brand transition-colors rounded-t-md border-t border-x border-brand/50" style="height: 60%;"></div>
                    </div>
                    <!-- Week 2 -->
                    <div class="w-full flex flex-col justify-end items-center group relative cursor-pointer z-10 h-full">
                        <div class="absolute -top-8 bg-slate-800 text-white text-xs font-bold py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity shadow-md pointer-events-none">Rp 125Jt</div>
                        <div class="w-full bg-brand/50 hover:bg-brand transition-colors rounded-t-md border-t border-x border-brand/50" style="height: 75%;"></div>
                    </div>
                    <!-- Week 3 (Peak) -->
                    <div class="w-full flex flex-col justify-end items-center group relative cursor-pointer z-10 h-full">
                        <div class="absolute -top-8 bg-slate-800 text-white text-xs font-bold py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity shadow-md pointer-events-none">Rp 150Jt</div>
                        <div class="w-full bg-brand hover:bg-brand-dark transition-colors rounded-t-md border-t border-x border-brand shadow-[0_0_15px_rgba(255,106,0,0.3)]" style="height: 90%;"></div>
                    </div>
                    <!-- Week 4 -->
                    <div class="w-full flex flex-col justify-end items-center group relative cursor-pointer z-10 h-full">
                        <div class="absolute -top-8 bg-slate-800 text-white text-xs font-bold py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity shadow-md pointer-events-none">Rp 100Jt</div>
                        <div class="w-full bg-brand/20 hover:bg-brand transition-colors rounded-t-md border-t border-x border-brand/50" style="height: 55%;"></div>
                    </div>
                </div>
                <!-- Label sumbu X -->
                <div class="flex justify-between text-xs text-slate-500 font-bold px-2 mt-2">
                    <span class="text-center w-full">Minggu 1</span>
                    <span class="text-center w-full">Minggu 2</span>
                    <span class="text-center w-full text-brand">Minggu 3</span>
                    <span class="text-center w-full">Minggu 4</span>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- Custom Script for Number Animation -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const counters = document.querySelectorAll(".counter-value");
    const duration = 1500; // 1.5 seconds

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
            
            // easeOutExpo easing function
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
</script>
@endsection
