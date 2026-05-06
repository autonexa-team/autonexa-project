@extends('layout.admin-cabang')

@section('content')

<!-- Custom Styles for Animations & Utilities -->
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade {
        animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    
    /* Custom CSS Tooltip */
    .tooltip-container .tooltip-text {
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.3s ease, transform 0.3s ease;
        transform: translateY(5px);
    }
    .tooltip-container:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
        transform: translateY(0);
    }
</style>

<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4 animate-fade">
    <div>
        <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Pelanggan Bengkel</h2>
        <p class="text-slate-500 mt-2 text-sm font-medium">Daftar pelanggan yang terdaftar dan pernah melakukan reservasi di cabang Anda</p>
    </div>
    
    <button class="bg-brand hover:bg-brand-dark text-white px-5 py-2.5 rounded-xl shadow-md shadow-brand/20 hover:shadow-lg hover:-translate-y-0.5 transition-all font-bold text-sm flex items-center gap-2">
        <i class="fas fa-user-plus"></i> Tambah Pelanggan
    </button>
</div>

<!-- Statistic Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-fade" style="animation-delay: 100ms; opacity: 0;">
    <!-- Card 1: Total -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 group hover:shadow-md hover:-translate-y-1 transition-all duration-300">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-500 text-sm font-bold mb-1 group-hover:text-slate-700 transition-colors">Total Pelanggan</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="428">0</h3>
            </div>
            <div class="w-12 h-12 bg-slate-50 text-slate-500 rounded-2xl flex items-center justify-center text-xl border border-slate-100 group-hover:bg-slate-100 group-hover:scale-110 transition-all duration-300">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <!-- Card 2: Aktif -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 group hover:shadow-md hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
        <div class="absolute inset-0 bg-emerald-500/5 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
        <div class="relative z-10 flex justify-between items-start">
            <div>
                <p class="text-slate-500 text-sm font-bold mb-1 group-hover:text-emerald-700 transition-colors">Pelanggan Aktif</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="215">0</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-xl border border-emerald-100 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
        <div class="mt-3 text-xs text-slate-400 font-bold relative z-10">Lebih dari 1x reservasi</div>
    </div>

    <!-- Card 3: Baru -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 group hover:shadow-md hover:-translate-y-1 transition-all duration-300">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-500 text-sm font-bold mb-1 group-hover:text-blue-600 transition-colors">Pelanggan Baru</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="32">0</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-xl border border-blue-100 group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300">
                <i class="fas fa-user-plus"></i>
            </div>
        </div>
        <div class="mt-3 text-xs text-slate-400 font-bold">Bergabung bulan ini</div>
    </div>

    <!-- Card 4: Tidak Aktif -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 group hover:shadow-md hover:-translate-y-1 transition-all duration-300">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-500 text-sm font-bold mb-1 group-hover:text-slate-700 transition-colors">Tidak Aktif</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="181">0</h3>
            </div>
            <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center text-xl border border-slate-100 group-hover:bg-slate-200 transition-all duration-300">
                <i class="fas fa-user-clock"></i>
            </div>
        </div>
        <div class="mt-3 text-xs text-slate-400 font-bold">> 3 bulan tanpa reservasi</div>
    </div>
</div>

<!-- Main Table Section -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden animate-fade" style="animation-delay: 200ms; opacity: 0;">
    
    <!-- Filter Bar -->
    <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row gap-4 justify-between items-center">
        <!-- Search -->
        <div class="w-full md:w-1/3 flex items-center bg-white rounded-xl px-4 py-2 border border-slate-200 focus-within:border-brand/50 focus-within:ring-2 focus-within:ring-brand/20 transition-all shadow-sm">
            <i class="fas fa-search text-slate-400"></i>
            <input type="text" placeholder="Cari nama atau nomor HP..." class="bg-transparent border-none outline-none ml-3 w-full text-sm font-medium text-slate-700">
        </div>
        
        <!-- Filter Status -->
        <div class="w-full md:w-auto flex gap-2 overflow-x-auto pb-2 md:pb-0 hide-scrollbar">
            <button class="bg-slate-800 text-white px-5 py-2 rounded-lg text-sm font-bold whitespace-nowrap shadow-sm hover:bg-slate-900 transition-colors">Semua</button>
            <button class="bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 px-5 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-colors">Aktif</button>
            <button class="bg-white border border-slate-200 text-slate-600 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 px-5 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-colors">Baru</button>
            <button class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 px-5 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-colors">Tidak Aktif</button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-slate-100 text-xs uppercase tracking-wider text-slate-400">
                    <th class="p-5 font-bold w-12 text-center">No</th>
                    <th class="p-5 font-bold">Pelanggan</th>
                    <th class="p-5 font-bold">Kontak</th>
                    <th class="p-5 font-bold text-center">Total Reservasi</th>
                    <th class="p-5 font-bold">Terakhir Reservasi</th>
                    <th class="p-5 font-bold text-center">Status</th>
                    <th class="p-5 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                
                <!-- Row 1: Aktif (Highlight background hijau lembut) -->
                <tr class="border-b border-emerald-50 bg-emerald-50/20 hover:bg-emerald-50/60 transition-colors group">
                    <td class="p-5 text-slate-500 font-bold text-center">1</td>
                    <td class="p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white overflow-hidden flex-shrink-0 border-2 border-emerald-100 shadow-sm">
                                <img src="https://ui-avatars.com/api/?name=Andi+Saputra&background=f1f5f9&color=10b981" alt="User">
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 group-hover:text-emerald-700 transition-colors">Andi Saputra</h4>
                                <!-- Tooltip Info -->
                                <div class="tooltip-container relative inline-block cursor-help">
                                    <p class="text-xs text-slate-500 mt-0.5 border-b border-dashed border-slate-300 inline-block">ID: CUST-001</p>
                                    <div class="tooltip-text absolute bottom-full left-0 mb-1 w-max">
                                        <div class="bg-slate-800 text-white text-[10px] px-2 py-1.5 rounded-lg shadow-lg font-bold">
                                            Terdaftar sejak: 12 Jan 2025
                                        </div>
                                        <div class="w-2 h-2 bg-slate-800 transform rotate-45 absolute -bottom-1 left-4"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="p-5">
                        <p class="font-bold text-slate-700">0812-3456-7890</p>
                        <p class="text-xs text-slate-500 mt-0.5">andi@email.com</p>
                    </td>
                    <td class="p-5 text-center">
                        <!-- Badge Jumlah Reservasi -->
                        <span class="bg-white text-slate-700 border border-slate-200 font-black px-3 py-1 rounded-lg text-sm shadow-sm group-hover:border-emerald-300 group-hover:text-emerald-700 transition-all inline-block">4</span>
                    </td>
                    <td class="p-5 text-slate-600 font-medium">
                        18 Mei 2026
                    </td>
                    <td class="p-5 text-center">
                        <span class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-3 py-1 rounded-full text-[11px] font-bold inline-flex items-center gap-1.5 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                        </span>
                    </td>
                    <td class="p-5 text-right">
                        <div class="flex justify-end gap-2">
                            <!-- Tooltip Button Lihat Detail -->
                            <div class="tooltip-container relative inline-block">
                                <button onclick="openModal()" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-brand hover:border-brand hover:bg-orange-50 transition-colors flex items-center justify-center shadow-sm">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <span class="tooltip-text absolute bottom-full right-0 mb-2 bg-slate-800 text-white text-[10px] font-bold px-2 py-1.5 rounded-lg whitespace-nowrap shadow-md">Lihat Detail</span>
                            </div>
                            
                            <!-- Tooltip Button Riwayat -->
                            <div class="tooltip-container relative inline-block">
                                <button class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-blue-500 hover:border-blue-500 hover:bg-blue-50 transition-colors flex items-center justify-center shadow-sm">
                                    <i class="fas fa-history"></i>
                                </button>
                                <span class="tooltip-text absolute bottom-full right-0 mb-2 bg-slate-800 text-white text-[10px] font-bold px-2 py-1.5 rounded-lg whitespace-nowrap shadow-md">Riwayat Reservasi</span>
                            </div>
                        </div>
                    </td>
                </tr>

                <!-- Row 2: Baru -->
                <tr class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors group">
                    <td class="p-5 text-slate-500 font-bold text-center">2</td>
                    <td class="p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-200 overflow-hidden flex-shrink-0 border border-slate-100">
                                <img src="https://ui-avatars.com/api/?name=Budi+Setiawan&background=f1f5f9&color=3b82f6" alt="User">
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors">Budi Setiawan</h4>
                                <p class="text-xs text-slate-500 mt-0.5">ID: CUST-428</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-5">
                        <p class="font-bold text-slate-700">0898-7654-3210</p>
                        <p class="text-xs text-slate-500 mt-0.5">budi@email.com</p>
                    </td>
                    <td class="p-5 text-center">
                        <span class="bg-slate-50 border border-slate-100 text-slate-600 font-bold px-3 py-1 rounded-lg text-sm inline-block shadow-sm">1</span>
                    </td>
                    <td class="p-5 text-slate-600 font-medium">
                        17 Mei 2026
                    </td>
                    <td class="p-5 text-center">
                        <span class="bg-blue-50 border border-blue-200 text-blue-700 px-3 py-1 rounded-full text-[11px] font-bold inline-flex items-center gap-1.5 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Baru
                        </span>
                    </td>
                    <td class="p-5 text-right">
                        <div class="flex justify-end gap-2">
                            <div class="tooltip-container relative inline-block">
                                <button onclick="openModal()" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-brand hover:border-brand hover:bg-orange-50 transition-colors flex items-center justify-center shadow-sm">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <span class="tooltip-text absolute bottom-full right-0 mb-2 bg-slate-800 text-white text-[10px] font-bold px-2 py-1.5 rounded-lg whitespace-nowrap shadow-md">Lihat Detail</span>
                            </div>
                            <div class="tooltip-container relative inline-block">
                                <button class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-blue-500 hover:border-blue-500 hover:bg-blue-50 transition-colors flex items-center justify-center shadow-sm">
                                    <i class="fas fa-history"></i>
                                </button>
                                <span class="tooltip-text absolute bottom-full right-0 mb-2 bg-slate-800 text-white text-[10px] font-bold px-2 py-1.5 rounded-lg whitespace-nowrap shadow-md">Riwayat Reservasi</span>
                            </div>
                        </div>
                    </td>
                </tr>

                <!-- Row 3: Tidak Aktif -->
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="p-5 text-slate-500 font-bold text-center">3</td>
                    <td class="p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-200 overflow-hidden flex-shrink-0 border border-slate-100 opacity-75 grayscale group-hover:grayscale-0 transition-all">
                                <img src="https://ui-avatars.com/api/?name=Dimas+Anggara&background=e2e8f0&color=64748b" alt="User">
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">Dimas Anggara</h4>
                                <p class="text-xs text-slate-500 mt-0.5">ID: CUST-042</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-5">
                        <p class="font-bold text-slate-700">0811-2233-4455</p>
                        <p class="text-xs text-slate-400 italic mt-0.5">Tidak ada email</p>
                    </td>
                    <td class="p-5 text-center">
                        <span class="bg-slate-50 border border-slate-100 text-slate-600 font-bold px-3 py-1 rounded-lg text-sm inline-block shadow-sm">1</span>
                    </td>
                    <td class="p-5 text-slate-400 font-medium italic">
                        12 Jan 2026
                    </td>
                    <td class="p-5 text-center">
                        <span class="bg-slate-100 border border-slate-200 text-slate-500 px-3 py-1 rounded-full text-[11px] font-bold inline-flex items-center gap-1.5 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Tidak Aktif
                        </span>
                    </td>
                    <td class="p-5 text-right">
                        <div class="flex justify-end gap-2">
                            <div class="tooltip-container relative inline-block">
                                <button onclick="openModal()" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-brand hover:border-brand hover:bg-orange-50 transition-colors flex items-center justify-center shadow-sm">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <span class="tooltip-text absolute bottom-full right-0 mb-2 bg-slate-800 text-white text-[10px] font-bold px-2 py-1.5 rounded-lg whitespace-nowrap shadow-md">Lihat Detail</span>
                            </div>
                            <div class="tooltip-container relative inline-block">
                                <button class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-blue-500 hover:border-blue-500 hover:bg-blue-50 transition-colors flex items-center justify-center shadow-sm">
                                    <i class="fas fa-history"></i>
                                </button>
                                <span class="tooltip-text absolute bottom-full right-0 mb-2 bg-slate-800 text-white text-[10px] font-bold px-2 py-1.5 rounded-lg whitespace-nowrap shadow-md">Riwayat Reservasi</span>
                            </div>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="p-5 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-white">
        <span class="text-sm text-slate-500 font-medium">Menampilkan 1-3 dari 428 pelanggan</span>
        <div class="flex items-center gap-2">
            <button class="px-4 py-2 rounded-xl border border-slate-200 text-slate-400 text-sm font-bold bg-slate-50 cursor-not-allowed">Prev</button>
            <button class="w-9 h-9 rounded-xl bg-brand text-white text-sm font-bold shadow-md shadow-brand/20">1</button>
            <button class="w-9 h-9 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-300 text-sm font-bold transition-all">2</button>
            <button class="w-9 h-9 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-300 text-sm font-bold transition-all">3</button>
            <span class="text-slate-400 px-1 font-bold">...</span>
            <button class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-300 text-sm font-bold transition-all shadow-sm">Next</button>
        </div>
    </div>
</div>

<!-- Modal Detail Pelanggan (Tersembunyi secara default) -->
<div id="modalDetail" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop" onclick="closeModal()"></div>
    
    <div class="bg-white rounded-2xl w-full max-w-2xl mx-4 relative z-10 shadow-2xl transform scale-95 opacity-0 transition-all duration-300 ease-out" id="modalContent">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/80 rounded-t-2xl">
            <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                <i class="fas fa-address-card text-brand"></i> Detail Profil Pelanggan
            </h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <!-- Profil Atas -->
            <div class="flex items-start gap-6 mb-8">
                <div class="w-20 h-20 rounded-full bg-white overflow-hidden ring-4 ring-emerald-50 flex-shrink-0 shadow-sm border border-emerald-100">
                    <img src="https://ui-avatars.com/api/?name=Andi+Saputra&background=f1f5f9&color=10b981" alt="User" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-black text-slate-800 text-2xl tracking-tight">Andi Saputra</h4>
                            <p class="text-sm text-slate-500 font-bold mt-1">CUST-001 <span class="mx-2 text-slate-300">•</span> Terdaftar Jan 2025</p>
                        </div>
                        <span class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Pelanggan Aktif
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <!-- Kontak Card -->
                <div class="bg-slate-50 rounded-xl p-5 border border-slate-100 hover:border-slate-200 transition-colors">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Informasi Kontak</p>
                    <p class="text-slate-800 font-bold text-sm mb-2 hover:text-emerald-600 transition-colors cursor-pointer w-max"><i class="fab fa-whatsapp text-emerald-500 mr-2 text-lg align-middle"></i> 0812-3456-7890</p>
                    <p class="text-slate-600 font-medium text-sm"><i class="fas fa-envelope text-slate-400 mr-2"></i> andi@email.com</p>
                </div>
                
                <!-- Statistik Card -->
                <div class="bg-orange-50/50 rounded-xl p-5 border border-orange-100 flex justify-between items-center">
                    <div>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Total Reservasi Bengkel</p>
                        <h4 class="text-4xl font-black text-brand tracking-tight mt-1">4 <span class="text-sm font-bold text-slate-500 tracking-normal">Kali Kunjungan</span></h4>
                    </div>
                    <div class="w-14 h-14 bg-white rounded-xl shadow-sm flex items-center justify-center text-brand border border-orange-100 transform -rotate-6">
                        <i class="fas fa-history text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Riwayat Singkat -->
            <div>
                <h5 class="font-bold text-slate-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-list-ul text-slate-400"></i> Riwayat Reservasi Terakhir
                </h5>
                <div class="border border-slate-100 rounded-xl overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500">
                                <th class="p-3 pl-4 font-bold">Tanggal</th>
                                <th class="p-3 font-bold">Layanan Utama</th>
                                <th class="p-3 font-bold text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                                <td class="p-3 pl-4 text-slate-800 font-bold">18 Mei 2026</td>
                                <td class="p-3 text-slate-600 font-medium">Servis Berkala</td>
                                <td class="p-3 text-center"><span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded text-xs font-bold">Selesai</span></td>
                            </tr>
                            <tr class="border-b border-slate-50 bg-slate-50/30 hover:bg-slate-50/80 transition-colors">
                                <td class="p-3 pl-4 text-slate-800 font-bold">10 Jan 2026</td>
                                <td class="p-3 text-slate-600 font-medium">Ganti Oli Reguler</td>
                                <td class="p-3 text-center"><span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded text-xs font-bold">Selesai</span></td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="p-3 pl-4 text-slate-800 font-bold">15 Ags 2025</td>
                                <td class="p-3 text-slate-600 font-medium">Pengecekan Kaki-kaki</td>
                                <td class="p-3 text-center"><span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded text-xs font-bold">Selesai</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/80 rounded-b-2xl flex justify-between items-center">
            <a href="#" class="text-brand font-bold text-sm hover:underline hover:text-brand-dark transition-colors">Lihat Semua Riwayat &rarr;</a>
            <div class="flex gap-3">
                <button onclick="closeModal()" class="px-5 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold rounded-xl text-sm transition-colors shadow-sm">Tutup</button>
                <button class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-sm transition-colors shadow-md flex items-center gap-2">
                    <i class="fab fa-whatsapp text-emerald-400"></i> Hubungi WA
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Logic Animasi Modal Native JS
    function openModal() {
        const modal = document.getElementById('modalDetail');
        const backdrop = document.getElementById('modalBackdrop');
        const content = document.getElementById('modalContent');
        
        // Buang display none
        modal.classList.remove('hidden');
        
        // Beri jeda sangat kecil agar CSS render display:block sebelum menjalankan transisi opacity/transform
        requestAnimationFrame(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        });
    }
    
    function closeModal() {
        const modal = document.getElementById('modalDetail');
        const backdrop = document.getElementById('modalBackdrop');
        const content = document.getElementById('modalContent');
        
        // Hapus class transisi aktif
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        // Tunggu durasi transisi (300ms) baru tambah class hidden
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>

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
        
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            
            // easeOutExpo easing function
            const easeProgress = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            const current = easeProgress * target;
            
            counter.innerText = prefix + current.toLocaleString('id-ID', { minimumFractionDigits: decimals, maximumFractionDigits: decimals }) + suffix;
            
            if (progress < 1) {
                window.requestAnimationFrame(step);
            } else {
                counter.innerText = prefix + target.toLocaleString('id-ID', { minimumFractionDigits: decimals, maximumFractionDigits: decimals }) + suffix;
            }
        };
        
        window.requestAnimationFrame(step);
    });
});
</script>
@endsection
