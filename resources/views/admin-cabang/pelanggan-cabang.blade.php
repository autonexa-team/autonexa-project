@extends('layout.admin-cabang')

@section('content')

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade { animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .tooltip-container .tooltip-text { visibility: hidden; opacity: 0; transition: opacity 0.3s ease, transform 0.3s ease; transform: translateY(5px); }
    .tooltip-container:hover .tooltip-text { visibility: visible; opacity: 1; transform: translateY(0); }
</style>

<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4 animate-fade">
    <div>
        <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Pelanggan Bengkel</h2>
        <p class="text-slate-500 mt-2 text-sm font-medium">Daftar pelanggan yang pernah melakukan reservasi di cabang Anda (Real-time)</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-fade" style="animation-delay: 100ms; opacity: 0;">
    <!-- Total -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 group hover:shadow-md hover:-translate-y-1 transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-500 text-sm font-bold mb-1">Total Pelanggan</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight" id="statTotal">0</h3>
            </div>
            <div class="w-12 h-12 bg-slate-50 text-slate-500 rounded-2xl flex items-center justify-center text-xl border border-slate-100 group-hover:scale-110 transition-all"><i class="fas fa-users"></i></div>
        </div>
    </div>

    <!-- Aktif -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 group hover:shadow-md hover:-translate-y-1 transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-500 text-sm font-bold mb-1">Pelanggan Aktif</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight" id="statAktif">0</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-xl border border-emerald-100 group-hover:scale-110 transition-all"><i class="fas fa-user-check"></i></div>
        </div>
        <div class="mt-3 text-xs text-slate-400 font-bold">Lebih dari 1x reservasi</div>
    </div>

    <!-- Baru -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 group hover:shadow-md hover:-translate-y-1 transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-500 text-sm font-bold mb-1">Pelanggan Baru</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight" id="statBaru">0</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-xl border border-blue-100 group-hover:scale-110 transition-all"><i class="fas fa-user-plus"></i></div>
        </div>
        <div class="mt-3 text-xs text-slate-400 font-bold">Bergabung bulan ini</div>
    </div>

    <!-- Tidak Aktif -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 group hover:shadow-md hover:-translate-y-1 transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-500 text-sm font-bold mb-1">Tidak Aktif</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight" id="statTidakAktif">0</h3>
            </div>
            <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center text-xl border border-slate-100 group-hover:scale-110 transition-all"><i class="fas fa-user-clock"></i></div>
        </div>
        <div class="mt-3 text-xs text-slate-400 font-bold">> 3 bulan tanpa reservasi</div>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden animate-fade" style="animation-delay: 200ms; opacity: 0;">
    
    <!-- Filter -->
    <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row gap-4 justify-between items-center">
        <div class="w-full md:w-1/3 flex items-center bg-white rounded-xl px-4 py-2 border border-slate-200 shadow-sm group focus-within:border-brand focus-within:shadow-md transition-all">
            <i class="fas fa-search text-slate-400 group-focus-within:text-brand transition-colors"></i>
            <input type="text" id="searchInput" placeholder="Cari nama atau nomor HP..." class="bg-transparent border-none outline-none ml-3 w-full text-sm font-medium">
            <button id="clearSearchBtn" class="ml-2 text-slate-400 hover:text-slate-600 transition-colors hidden" title="Hapus pencarian">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="w-full md:w-auto flex gap-2 overflow-x-auto">
            <button onclick="filterData('semua')" class="filter-btn bg-slate-800 text-white px-5 py-2 rounded-lg text-sm font-bold whitespace-nowrap shadow-sm hover:bg-slate-900 transition-colors active" data-filter="semua">Semua</button>
            <button onclick="filterData('aktif')" class="filter-btn bg-white border border-slate-200 text-slate-600 px-5 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-colors hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200" data-filter="aktif">Aktif</button>
            <button onclick="filterData('baru')" class="filter-btn bg-white border border-slate-200 text-slate-600 px-5 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-colors hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200" data-filter="baru">Baru</button>
            <button onclick="filterData('tidak-aktif')" class="filter-btn bg-white border border-slate-200 text-slate-600 px-5 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-colors hover:bg-slate-100" data-filter="tidak-aktif">Tidak Aktif</button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto" id="tableContainer">
        <div class="text-center p-8 text-slate-500">
            <i class="fas fa-spinner fa-spin text-2xl text-brand mb-3 block"></i>
            <p class="font-medium">Memuat data pelanggan...</p>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';
    
    let allPelanggan = [];
    let currentFilter = 'semua';
    let currentSearch = '';

    // Load data on page load
    document.addEventListener('DOMContentLoaded', loadPelanggan);

    // Search input - Real-time filtering
    const searchInput = document.getElementById('searchInput');
    const clearSearchBtn = document.getElementById('clearSearchBtn');

    searchInput.addEventListener('input', function(e) {
        currentSearch = e.target.value.toLowerCase().trim();
        
        // Show/hide clear button
        if (currentSearch.length > 0) {
            clearSearchBtn.classList.remove('hidden');
        } else {
            clearSearchBtn.classList.add('hidden');
        }
        
        renderTable();
    });

    // Clear search button
    clearSearchBtn.addEventListener('click', function(e) {
        e.preventDefault();
        searchInput.value = '';
        searchInput.focus();
        currentSearch = '';
        clearSearchBtn.classList.add('hidden');
        renderTable();
    });

    // Search on Enter key
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            searchInput.value = '';
            currentSearch = '';
            clearSearchBtn.classList.add('hidden');
            renderTable();
        }
    });

    async function loadPelanggan() {
        try {
            const res = await fetch('/admin-cabang/api/pelanggan-reservasi', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            
            if (!res.ok) throw new Error('Gagal memuat data');
            
            const data = await res.json();
            allPelanggan = data.pelanggan;
            
            // Update stats
            document.getElementById('statTotal').textContent = data.statistik.total;
            document.getElementById('statAktif').textContent = data.statistik.aktif;
            document.getElementById('statBaru').textContent = data.statistik.baru;
            document.getElementById('statTidakAktif').textContent = data.statistik.tidak_aktif;
            
            renderTable();
        } catch (err) {
            console.error('Error loading pelanggan:', err);
            document.getElementById('tableContainer').innerHTML = `
                <div class="text-center p-8 text-red-500">
                    <i class="fas fa-exclamation-circle text-2xl mb-3 block"></i>
                    <p class="font-medium">Gagal memuat data pelanggan</p>
                </div>
            `;
        }
    }

    window.filterData = function(filter) {
        currentFilter = filter;
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('bg-slate-800', 'text-white', 'active');
            btn.classList.add('bg-white', 'border', 'border-slate-200', 'text-slate-600');
        });
        event.target.classList.add('bg-slate-800', 'text-white', 'active');
        event.target.classList.remove('bg-white', 'border', 'border-slate-200', 'text-slate-600');
        renderTable();
    };

    function renderTable() {
        let filtered = allPelanggan;
        
        // Filter by status
        if (currentFilter !== 'semua') {
            filtered = filtered.filter(p => p.status === currentFilter);
        }
        
        // Filter by search - search in nama, phone, dan email
        if (currentSearch) {
            filtered = filtered.filter(p => {
                const nama = (p.nama || '').toLowerCase();
                const phone = (p.phone || '').toLowerCase();
                const email = (p.email || '').toLowerCase();
                
                // Return true jika search match di salah satu field
                return nama.includes(currentSearch) || 
                       phone.includes(currentSearch) || 
                       email.includes(currentSearch);
            });
        }

        if (filtered.length === 0) {
            document.getElementById('tableContainer').innerHTML = `
                <div class="text-center p-8 text-slate-500">
                    <i class="fas fa-user-slash text-3xl text-slate-300 mb-3 block"></i>
                    <p class="font-medium">${currentSearch ? 'Tidak ada pelanggan yang ditemukan' : 'Tidak ada data pelanggan'}</p>
                </div>
            `;
            return;
        }

        const statusColors = {
            'aktif': { bg: 'bg-emerald-50/20', border: 'border-emerald-50', badge: 'bg-emerald-100 border-emerald-200 text-emerald-700', dot: 'bg-emerald-500' },
            'baru': { bg: 'bg-blue-50/20', border: 'border-blue-50', badge: 'bg-blue-100 border-blue-200 text-blue-700', dot: 'bg-blue-500' },
            'tidak-aktif': { bg: 'bg-slate-50/20', border: 'border-slate-50', badge: 'bg-slate-100 border-slate-200 text-slate-500', dot: 'bg-slate-400' }
        };

        const statusLabel = {
            'aktif': 'Aktif',
            'baru': 'Baru',
            'tidak-aktif': 'Tidak Aktif'
        };

        const rows = filtered.map((p, idx) => {
            const colors = statusColors[p.status];
            const initials = p.nama.split(' ').map(n => n[0]).join('').toUpperCase();
            
            return `
                <tr class="border-b ${colors.bg} ${colors.border} hover:${colors.bg.replace('0.2', '0.6')} transition-colors group">
                    <td class="p-5 text-slate-500 font-bold text-center">${idx + 1}</td>
                    <td class="p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand to-orange-500 flex items-center justify-center text-white font-bold text-sm border border-brand/20 flex-shrink-0">
                                ${initials}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 group-hover:text-brand transition-colors">${p.nama}</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Terdaftar: ${p.terdaftar}</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-5">
                        <p class="font-bold text-slate-700">${p.phone}</p>
                        <p class="text-xs text-slate-500 mt-0.5">${p.email || '-'}</p>
                    </td>
                    <td class="p-5 text-center">
                        <span class="bg-white text-slate-700 border border-slate-200 font-black px-3 py-1 rounded-lg text-sm shadow-sm inline-block">${p.total_reservasi}</span>
                    </td>
                    <td class="p-5 text-slate-600 font-medium">${p.terakhir_reservasi}</td>
                    <td class="p-5 text-center">
                        <span class="${colors.badge} px-3 py-1 rounded-full text-[11px] font-bold inline-flex items-center gap-1.5 shadow-sm border">
                            <span class="w-1.5 h-1.5 rounded-full ${colors.dot}"></span> ${statusLabel[p.status]}
                        </span>
                </tr>
            `;
        }).join('');

        document.getElementById('tableContainer').innerHTML = `
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-100 text-xs uppercase tracking-wider text-slate-400">
                        <th class="p-5 font-bold w-12 text-center">No</th>
                        <th class="p-5 font-bold">Pelanggan</th>
                        <th class="p-5 font-bold">Kontak</th>
                        <th class="p-5 font-bold text-center">Total Reservasi</th>
                        <th class="p-5 font-bold">Terakhir Reservasi</th>
                        <th class="p-5 font-bold text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm">${rows}</tbody>
            </table>
        `;
    }
})();
</script>

@endsection
