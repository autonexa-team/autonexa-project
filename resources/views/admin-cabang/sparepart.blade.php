@extends('layout.admin-cabang')

@section('content')

<!-- Alert / Notifikasi -->
<!-- Munculkan alert jika ada barang hampir habis -->
@if(($hampirHabis + $stokHabis) > 0)
<div class="mb-6 bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-xl shadow-sm flex items-start gap-3">
    <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5"></i>
    <div>
        <h4 class="text-amber-800 font-bold text-sm">Perhatian: Stok Hampir Habis</h4>
        <p class="text-amber-700 text-sm mt-1">
            Terdapat 
            <strong>{{ $hampirHabis + $stokHabis }}</strong> 
            jenis sparepart yang stoknya kurang dari 5 atau sudah habis.
            Segera lakukan restock untuk menghindari gangguan layanan.
        </p>
    </div>
</div>
@endif

{{-- dita nmbh ini --}}
@if(session('success'))
<div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl">
    {{ session('success') }}
</div>
@endif

<!-- Custom Animations -->
<style>
    @keyframes fadeSlideUp {
        0% { opacity: 0; transform: translateY(10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-slide-up {
        animation: fadeSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    .stagger-1 { animation-delay: 50ms; }
    .stagger-2 { animation-delay: 100ms; }
    .stagger-3 { animation-delay: 150ms; }
    .stagger-4 { animation-delay: 200ms; }
    .stagger-5 { animation-delay: 250ms; }
</style>

<!-- Header Section -->
<div class="flex justify-between items-end mb-8 animate-fade-slide-up">
    <div>
        <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Manajemen Sparepart</h2>
        <p class="text-slate-500 mt-2 text-sm font-medium">Kelola stok dan ketersediaan sparepart di bengkel Anda</p>
    </div>
</div>

<!-- Statistic Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card 1 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 ease-out group relative overflow-hidden animate-fade-slide-up stagger-1">
        <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-br from-slate-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-500 text-sm font-semibold mb-1">Jenis Sparepart</p> <!-- Dita nambah ini data-target=" $totalJenis -->
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="{{ $totalJenis }}"> 
                    {{  $totalJenis }}
                </h3> 
            </div>
            <div class="w-12 h-12 bg-slate-50 text-slate-600 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-slate-100">
                <i class="fas fa-cogs"></i>
            </div>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 ease-out group relative overflow-hidden animate-fade-slide-up stagger-2">
        <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-br from-blue-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-500 text-sm font-semibold mb-1">Total Stok</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="{{ $totalStok }}"> 
                    {{  $totalStok }}
                </h3>  
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-blue-100">
                <i class="fas fa-boxes"></i>
            </div>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 ease-out group relative overflow-hidden animate-fade-slide-up stagger-3">
        <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-br from-amber-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-500 text-sm font-semibold mb-1">Hampir Habis (&lt; 5)</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="{{ $hampirHabis }}"> 
                    {{  $hampirHabis }}
                </h3> 
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-amber-100">
                <i class="fas fa-exclamation-circle"></i>
            </div>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 ease-out group relative overflow-hidden animate-fade-slide-up stagger-4">
        <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-br from-red-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-500 text-sm font-semibold mb-1">Stok Habis (= 0)</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="{{ $stokHabis }}"> 
                    {{  $stokHabis }}
                </h3>
            </div>
            <div class="w-12 h-12 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-red-100">
                <i class="fas fa-times-circle"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 mb-6 flex items-center justify-between">
    <form method="GET" action="{{ route('admin-cabang.sparepart') }}"
        class="flex items-center gap-3 w-full">
        
        <div class="flex items-center bg-slate-50 rounded-xl px-4 py-2 w-full md:w-96 border border-slate-200 focus-within:ring-2 focus-within:ring-slate-300">
            <i class="fas fa-search text-slate-400"></i>
            <input type="text"
                name="search"
                id="searchInput"
                class="w-full bg-transparent outline-none border-none focus:ring-0 focus:outline-none pl-3"
                placeholder="Cari nama sparepart..."
                value="{{ request('search') }}">
        </div>

        <div class="flex items-center gap-3 ml-auto">

            <span id="countLabel" class="text-sm text-slate-500 font-medium"></span>

            <select name="filter" class="bg-slate-50 border border-slate-200 text-sm rounded-xl p-2.5 pr-8">
                <option value="">Semua Status Stok</option>
                <option value="aman">Aman (> 5)</option>
                <option value="hampir-habis">Hampir Habis (1-5)</option>
                <option value="habis">Habis (0)</option>
            </select>

            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                <i class="fas fa-chevron-down text-[10px]"></i>
            </div>
        </div>

        <button class="bg-slate-800 hover:bg-slate-700 text-white px-5 py-2.5 rounded-xl transition-colors font-medium text-sm flex items-center gap-2">
            <i class="fas fa-filter"></i> Filter
        </button>
    </form>
</div>

<!-- Table Section -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden animate-fade-slide-up stagger-5">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                    <th class="px-6 py-4 font-bold w-16 text-center">No</th>
                    <th class="px-6 py-4 font-bold">Nama Sparepart</th>
                    <th class="px-6 py-4 font-bold">Stok</th>
                    <th class="px-6 py-4 font-bold">Harga</th>
                    <th class="px-6 py-4 font-bold">Status Stok</th>
                    <th class="px-6 py-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 text-sm">

                @forelse($spareparts as $index => $sparepart)

                @php
                    $stok = $sparepart->pivot->stok;
                @endphp

                <tr class="hover:bg-slate-50 transition-colors duration-300 ease-out group
                    @if($stok == 0)
                        bg-red-50/30
                    @elseif($stok <= 5)
                        bg-amber-50/30
                    @endif
                ">
                    <!-- Nomor -->
                    <td class="px-6 py-4 text-center font-semibold text-slate-600">
                        {{ $spareparts->firstItem() + $index }}
                    </td>

                    <!-- Nama Sparepart -->
                    <td class="px-6 py-4">
                        <p class="
                            font-bold
                            @if($stok == 0)
                                text-slate-500 line-through
                            @else
                                text-slate-800
                            @endif
                        ">
                            {{ $sparepart->nama }}
                        </p>
                    </td>

                    <!-- Stok -->
                    <td class="
                        px-6 py-4 font-bold

                        @if($stok == 0)
                            text-red-500
                        @elseif($stok <= 5)
                            text-amber-600
                        @else
                            text-slate-800
                        @endif
                    ">
                        {{ $stok }}
                    </td>

                    <!-- Harga -->
                    <td class="px-6 py-4 font-medium text-slate-600">Rp {{ number_format($sparepart->harga, 0, ',', '.') }}</td>

                    <!-- Status -->
                    <td class="px-6 py-4">
                        @if($stok == 0)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                                Habis
                            </span>
                        @elseif($stok <= 5)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Hampir Habis
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Aman
                            </span>
                        @endif
                    </td>

                    <!-- Aksi -->
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button
                                onclick="openFormModal(
                                    {{ $sparepart->id }},
                                    {{ Js::from($sparepart->nama) }},
                                    {{ $stok }},
                                    {{ $sparepart->harga }}
                                )"
                                class="w-8 h-8 rounded-lg text-slate-400 hover:text-brand hover:bg-orange-50 transition-colors flex items-center justify-center"
                                title="Edit"
                            >
                                <i class="fas fa-pen text-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                        Tidak ada data sparepart
                    </td>
                </tr>
                @endforelse
            </tbody>
            {{-- <tbody class="divide-y divide-slate-50 text-sm">
                <!-- Data Row 1 (Aman) -->
                <tr class="hover:bg-slate-50 transition-colors duration-300 ease-out group">
                    <td class="px-6 py-4 text-center font-semibold text-slate-600">1</td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-800">Oli Mesin Pertamina Fastron 10W-40 (4L)</p>
                    </td>
                    <td class="px-6 py-4 font-bold text-slate-800">24</td>
                    <td class="px-6 py-4 font-medium text-slate-600">Rp 350.000</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aman
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="openFormModal('edit', 'Oli Mesin Pertamina Fastron 10W-40 (4L)', 24, 350000)" class="w-8 h-8 rounded-lg text-slate-400 hover:text-brand hover:bg-orange-50 transition-colors flex items-center justify-center" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Data Row 2 (Aman) -->
                <tr class="hover:bg-slate-50 transition-colors duration-300 ease-out group">
                    <td class="px-6 py-4 text-center font-semibold text-slate-600">2</td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-800">Filter Udara Honda Brio/Mobilio</p>
                    </td>
                    <td class="px-6 py-4 font-bold text-slate-800">15</td>
                    <td class="px-6 py-4 font-medium text-slate-600">Rp 125.000</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aman
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="openFormModal('edit', 'Filter Udara Honda Brio/Mobilio', 15, 125000)" class="w-8 h-8 rounded-lg text-slate-400 hover:text-brand hover:bg-orange-50 transition-colors flex items-center justify-center" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </button>
                            <button onclick="confirmDelete()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors flex items-center justify-center" title="Hapus">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Data Row 3 (Hampir Habis) -->
                <tr class="hover:bg-slate-50 transition-colors duration-300 ease-out group bg-amber-50/30">
                    <td class="px-6 py-4 text-center font-semibold text-slate-600">3</td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-800">Kampas Rem Depan Toyota Avanza</p>
                    </td>
                    <td class="px-6 py-4 font-bold text-amber-600">3</td>
                    <td class="px-6 py-4 font-medium text-slate-600">Rp 275.000</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Hampir Habis
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="openFormModal('edit', 'Kampas Rem Depan Toyota Avanza', 3, 275000)" class="w-8 h-8 rounded-lg text-slate-400 hover:text-brand hover:bg-orange-50 transition-colors flex items-center justify-center" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </button>
                            <button onclick="confirmDelete()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors flex items-center justify-center" title="Hapus">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Data Row 4 (Hampir Habis) -->
                <tr class="hover:bg-slate-50 transition-colors duration-300 ease-out group bg-amber-50/30">
                    <td class="px-6 py-4 text-center font-semibold text-slate-600">4</td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-800">Busi NGK Iridium BKR6EIX</p>
                    </td>
                    <td class="px-6 py-4 font-bold text-amber-600">4</td>
                    <td class="px-6 py-4 font-medium text-slate-600">Rp 95.000</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Hampir Habis
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="openFormModal('edit', 'Busi NGK Iridium BKR6EIX', 4, 95000)" class="w-8 h-8 rounded-lg text-slate-400 hover:text-brand hover:bg-orange-50 transition-colors flex items-center justify-center" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </button>
                            <button onclick="confirmDelete()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors flex items-center justify-center" title="Hapus">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Data Row 5 (Habis) -->
                <tr class="hover:bg-slate-50 transition-colors duration-300 ease-out group bg-red-50/30">
                    <td class="px-6 py-4 text-center font-semibold text-slate-600">5</td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-800 text-slate-500 line-through">Aki Mobil GS Astra Premium NS40ZL</p>
                    </td>
                    <td class="px-6 py-4 font-bold text-red-500">0</td>
                    <td class="px-6 py-4 font-medium text-slate-600">Rp 750.000</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Habis
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="openFormModal('edit', 'Aki Mobil GS Astra Premium NS40ZL', 0, 750000)" class="w-8 h-8 rounded-lg text-slate-400 hover:text-brand hover:bg-orange-50 transition-colors flex items-center justify-center" title="Edit">
                                <i class="fas fa-pen text-xs"></i>
                            </button>
                            <button onclick="confirmDelete()" class="w-8 h-8 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors flex items-center justify-center" title="Hapus">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody> --}}
        </table>
    </div>
    
    <!-- Empty State (Hidden by default) 
    <div id="empty-state" class="hidden flex-col items-center justify-center py-16 text-center">
        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
            <i class="fas fa-box-open text-4xl text-slate-300"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-800">Belum ada sparepart tersedia</h3>
        <p class="text-slate-500 text-sm mt-1 max-w-sm">Data stok sparepart bengkel Anda akan muncul di sini. Silakan tambah sparepart baru untuk memulai manajemen stok.</p>

    </div> -->

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-slate-100 flex flex-col md:flex-row gap-4 items-center justify-between bg-white">
        <span class="text-sm text-slate-500 font-medium"> 
            Menampilkan
            {{ $spareparts->firstItem() ?? 0 }}
            -
            {{ $spareparts->lastItem() ?? 0 }}
            dari
            {{ $spareparts->total() }}
            data
        </span>
        {{ $spareparts->links() }}
    </div>
</div>

<!-- Modal Form Tambah/Edit Sparepart -->
<div id="formModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 overflow-hidden transform scale-95 transition-transform duration-300" id="formModalContent">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 id="modalTitle" class="font-bold text-slate-800 text-lg">Update Stok Sparepart</h3>
            <button onclick="closeFormModal()" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-lg flex items-center justify-center hover:bg-red-50">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6"> {{-- dita nambah ini --}}
            <form id="sparepartForm" method="POST">
                @csrf
                @method('PATCH')

                <input type="hidden" id="sparepartId">

                <div class="mb-4">
                    <label for="namaSparepart" class="block text-sm font-medium text-slate-700 mb-2">Nama Sparepart</label>
                    <input type="text" id="namaSparepart" class="bg-slate-100 border border-slate-200 text-slate-500 text-sm rounded-xl block w-full p-3 outline-none font-medium cursor-not-allowed" readonly>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="stokSparepart" class="block text-sm font-medium text-slate-700 mb-2">Update Stok</label>
                        <div class="relative">
                            <input type="number" id="stokSparepart" placeholder="0" min="0" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-brand focus:border-brand focus:bg-white block w-full p-3 pl-10 outline-none font-bold transition-colors" required>
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                                <i class="fas fa-boxes text-xs"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="hargaSparepart" class="block text-sm font-medium text-slate-700 mb-2">Harga Jual (Rp)</label>
                        <div class="relative">
                            <input type="number" id="hargaSparepart" class="bg-slate-100 border border-slate-200 text-slate-500 text-sm rounded-xl block w-full p-3 pl-10 outline-none font-bold cursor-not-allowed" readonly>
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400 font-bold text-sm">
                                Rp
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-3 justify-end pt-2 border-t border-slate-100">
                    <button type="button" onclick="closeFormModal()" class="px-5 py-2.5 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button type="button" onclick="saveSparepart()" class="px-5 py-2.5 rounded-xl font-bold text-white bg-brand hover:bg-brand-dark transition-colors shadow-lg shadow-brand/20">
                        Update Stok
                    </button>
                </div>
            </form> 
        </div>
    </div>
</div>

<script>
    // Simple Modal Logic
    function openFormModal(id, nama = '', stok = '', harga = '') {
        const modal = document.getElementById('formModal');
        const content = document.getElementById('formModalContent');
        const title = document.getElementById('modalTitle');
        
        // Hanya melayani mode edit stok
        title.textContent = 'Update Stok Sparepart';
        /* dita nambah ii */
        document.getElementById('sparepartId').value = id;

        document.getElementById('namaSparepart').value = nama;
        document.getElementById('stokSparepart').value = stok;
        document.getElementById('hargaSparepart').value = harga;
        
        modal.classList.remove('hidden');
        // Trigger reflow
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95');
    }

    function closeFormModal() {
        const modal = document.getElementById('formModal');
        const content = document.getElementById('formModalContent');
        
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
    
    /* function saveSparepart() {
        alert('Stok sparepart berhasil diperbarui (Dummy)');
        closeFormModal();
    } */

    /* dita ganti ini buat stok*/
    function saveSparepart() {

        const sparepartId = document.getElementById('sparepartId').value;
        const stok = document.getElementById('stokSparepart').value;
        const form = document.getElementById('sparepartForm');

        form.action = `/admin-cabang/sparepart/${sparepartId}/stok`;

        let stokInput = document.getElementById('stokHidden');

        if (!stokInput) {
            stokInput = document.createElement('input');
            stokInput.type = 'hidden';
            stokInput.name = 'stok';
            stokInput.id = 'stokHidden';
            form.appendChild(stokInput);
        }

        stokInput.value = stok;

        form.submit();
    }
    
    function confirmDelete() {
        if(confirm('Apakah Anda yakin ingin menghapus data sparepart ini?')) {
            alert('Data sparepart berhasil dihapus (Dummy)');
        }
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

<script>
const searchInput = document.getElementById('searchInput');
const countLabel = document.getElementById('countLabel');

searchInput.addEventListener('input', function () {
    const q = this.value.trim().toLowerCase();
    const rows = document.querySelectorAll('tbody tr');

    let visible = 0;

    rows.forEach(row => {
        const nama = row.querySelector('td:nth-child(2)')?.innerText.toLowerCase() || '';
        const match = nama.includes(q);

        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    countLabel.innerHTML = `Menampilkan <b>${visible}</b> sparepart`;
});
</script>
@endsection
