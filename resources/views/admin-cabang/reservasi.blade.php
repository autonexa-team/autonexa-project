@extends('layout.admin-cabang')

@section('content')
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
        <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Manajemen Reservasi</h2>
        <p class="text-slate-500 mt-2 text-sm font-medium">Kelola reservasi pelanggan di bengkel Anda</p>
    </div>
    <a href="{{ route('admin-cabang.reservasi-create') }}"
       class="bg-brand hover:brightness-105 text-white px-6 py-3 rounded-xl shadow-lg shadow-brand/25 flex items-center gap-2 font-semibold active:scale-95 transition-all duration-200 ease-out">

        <i class="fas fa-plus"></i>
        Reservasi Baru
    </a>
</div>

<!-- Statistic Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card 1 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 hover:border-slate-300 transition-all duration-300 ease-out group relative overflow-hidden animate-fade-slide-up stagger-1">
        <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-br from-slate-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-500 text-sm font-semibold mb-1">Total Reservasi</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="156">0</h3>
            </div>
            <div class="w-12 h-12 bg-slate-50 text-slate-600 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-slate-100">
                <i class="fas fa-list-ol"></i>
            </div>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 hover:border-orange-300 transition-all duration-300 ease-out group relative overflow-hidden animate-fade-slide-up stagger-2">
        <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-br from-orange-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-500 text-sm font-semibold mb-1">Reservasi Hari Ini</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="12">0</h3>
            </div>
            <div class="w-12 h-12 bg-orange-50 text-brand rounded-2xl flex items-center justify-center text-xl shadow-inner border border-orange-100">
                <i class="fas fa-calendar-day"></i>
            </div>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 hover:border-amber-300 transition-all duration-300 ease-out group relative overflow-hidden animate-fade-slide-up stagger-3">
        <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-br from-amber-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-500 text-sm font-semibold mb-1">Sedang Dikerjakan</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="5">0</h3>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-amber-100">
                <i class="fas fa-tools"></i>
            </div>
        </div>
    </div>

    <!-- Card 4 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 hover:border-emerald-300 transition-all duration-300 ease-out group relative overflow-hidden animate-fade-slide-up stagger-4">
        <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-br from-emerald-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-500 text-sm font-semibold mb-1">Selesai</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="8">0</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-emerald-100">
                <i class="fas fa-check-double"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 mb-6 flex flex-col md:flex-row gap-4 justify-between items-center animate-fade-slide-up stagger-4">
    <div class="flex items-center bg-slate-50 rounded-xl px-4 py-2 w-full md:w-80 border border-slate-200 focus-within:border-brand/50 focus-within:ring-2 focus-within:ring-brand/20 transition-all">
        <i class="fas fa-search text-slate-400"></i>
        <input type="text" placeholder="Cari nama pelanggan..." class="bg-transparent border-none outline-none ml-3 w-full text-sm font-medium text-slate-700">
    </div>
    
    <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
        <div class="relative">
            <select class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-brand focus:border-brand block p-2.5 outline-none font-medium appearance-none pr-8">
                <option selected>Semua Status</option>
                <option value="Menunggu">Menunggu</option>
                <option value="Dikonfirmasi">Dikonfirmasi</option>
                <option value="Dikerjakan">Dikerjakan</option>
                <option value="Selesai">Selesai</option>
                <option value="Dibatalkan">Dibatalkan</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                <i class="fas fa-chevron-down text-[10px]"></i>
            </div>
        </div>
        
        <input type="date" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-brand focus:border-brand block p-2.5 outline-none font-medium">
        
        <button class="bg-slate-800 hover:bg-slate-700 text-white px-5 py-2.5 rounded-xl transition-colors font-medium text-sm flex items-center gap-2">
            <i class="fas fa-filter"></i> Filter
        </button>
    </div>
</div>

<!-- Table Section -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden animate-fade-slide-up stagger-5">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-100">
                    <th class="px-6 py-4 font-bold w-16 text-center">No</th>
                    <th class="px-6 py-4 font-bold">Pelanggan</th>
                    <th class="px-6 py-4 font-bold">Layanan</th>
                    <th class="px-6 py-4 font-bold">Tanggal</th>
                    <th class="px-6 py-4 font-bold">Total Biaya</th>
                    <th class="px-6 py-4 font-bold">Status</th>
                    <th class="px-6 py-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 text-sm">
                <!-- Data Row 1 (Menunggu) -->
                <tr class="hover:bg-slate-50 transition-colors duration-300 ease-out group">
                    <td class="px-6 py-4 text-center font-semibold text-slate-600">1</td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-800">Andi Saputra</p>
                    </td>
                    <td class="px-6 py-4 text-slate-600 font-medium">Servis Berkala 10rb KM</td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-slate-800 flex items-center gap-2">
                            <i class="far fa-calendar-alt text-slate-400"></i> 18 Mei 2026
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-slate-800">Rp 850.000</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span> Menunggu
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin-cabang.reservasi-detail', 1) }}"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold text-brand bg-orange-50 hover:bg-brand hover:text-white transition-colors">
                                Detail
                            </a>

                            <button onclick="openStatusModal('Andi Saputra', 'Menunggu')" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-500 bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-colors">
                                Ubah Status
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Data Row 2 (Dikonfirmasi) -->
                <tr class="hover:bg-slate-50 transition-colors duration-300 ease-out group">
                    <td class="px-6 py-4 text-center font-semibold text-slate-600">2</td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-800">Budi Setiawan</p>
                    </td>
                    <td class="px-6 py-4 text-slate-600 font-medium">Ganti Oli & Filter</td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-slate-800 flex items-center gap-2">
                            <i class="far fa-calendar-alt text-slate-400"></i> 18 Mei 2026
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-slate-800">Rp 450.000</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-600 border border-blue-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Dikonfirmasi
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button class="px-3 py-1.5 rounded-lg text-xs font-bold text-brand bg-orange-50 hover:bg-brand hover:text-white transition-colors">
                                Detail
                            </button>
                            <button onclick="openStatusModal('Budi Setiawan', 'Dikonfirmasi')" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-500 bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-colors">
                                Ubah Status
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Data Row 3 (Dikerjakan) -->
                <tr class="hover:bg-slate-50 transition-colors duration-300 ease-out group">
                    <td class="px-6 py-4 text-center font-semibold text-slate-600">3</td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-800">Citra Kirana</p>
                    </td>
                    <td class="px-6 py-4 text-slate-600 font-medium">Pengecekan Rem & AC</td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-slate-800 flex items-center gap-2">
                            <i class="far fa-calendar-alt text-slate-400"></i> 18 Mei 2026
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-slate-800">-</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-600 border border-amber-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Dikerjakan
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button class="px-3 py-1.5 rounded-lg text-xs font-bold text-brand bg-orange-50 hover:bg-brand hover:text-white transition-colors">
                                Detail
                            </button>
                            <button onclick="openStatusModal('Citra Kirana', 'Dikerjakan')" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-500 bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-colors">
                                Ubah Status
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Data Row 4 (Selesai) -->
                <tr class="hover:bg-slate-50 transition-colors duration-300 ease-out group">
                    <td class="px-6 py-4 text-center font-semibold text-slate-600">4</td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-800">Dimas Anggara</p>
                    </td>
                    <td class="px-6 py-4 text-slate-600 font-medium">Turun Mesin (Overhaul)</td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-slate-800 flex items-center gap-2">
                            <i class="far fa-calendar-alt text-slate-400"></i> 15 Mei 2026
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-slate-800">Rp 5.200.000</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button class="px-3 py-1.5 rounded-lg text-xs font-bold text-brand bg-orange-50 hover:bg-brand hover:text-white transition-colors">
                                Detail
                            </button>
                            <button onclick="openStatusModal('Dimas Anggara', 'Selesai')" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-500 bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-colors">
                                Ubah Status
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Data Row 5 (Dibatalkan) -->
                <tr class="hover:bg-slate-50 transition-colors duration-300 ease-out group">
                    <td class="px-6 py-4 text-center font-semibold text-slate-600">5</td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-slate-800">Eka Putri</p>
                    </td>
                    <td class="px-6 py-4 text-slate-600 font-medium">Spooring & Balancing</td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-slate-800 flex items-center gap-2">
                            <i class="far fa-calendar-alt text-slate-400"></i> 14 Mei 2026
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-slate-800">-</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-600 border border-red-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Dibatalkan
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button class="px-3 py-1.5 rounded-lg text-xs font-bold text-brand bg-orange-50 hover:bg-brand hover:text-white transition-colors">
                                Detail
                            </button>
                            <button onclick="openStatusModal('Eka Putri', 'Dibatalkan')" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-500 bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-colors">
                                Ubah Status
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Empty State (Hidden by default) -->
    <div id="empty-state" class="hidden flex-col items-center justify-center py-16 text-center">
        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
            <i class="fas fa-box-open text-4xl text-slate-300"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-800">Belum ada reservasi</h3>
        <p class="text-slate-500 text-sm mt-1 max-w-sm">Data reservasi pelanggan akan muncul di sini. Saat ini belum ada data yang sesuai dengan filter Anda.</p>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-slate-100 flex flex-col md:flex-row gap-4 items-center justify-between bg-white">
        <span class="text-sm text-slate-500 font-medium">Menampilkan 1-5 dari 156 data</span>
        <div class="flex items-center gap-2">
            <button class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 text-slate-400 hover:bg-slate-50 transition-colors disabled:opacity-50" disabled>
                <i class="fas fa-chevron-left text-xs"></i>
            </button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center bg-brand text-white font-bold text-sm shadow-md shadow-brand/20">1</button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors font-bold text-sm">2</button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors font-bold text-sm">3</button>
            <span class="text-slate-400">...</span>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                <i class="fas fa-chevron-right text-xs"></i>
            </button>
        </div>
    </div>
</div>

<!-- Modal Update Status -->
<div id="statusModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform scale-95 transition-transform duration-300" id="statusModalContent">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 text-lg">Ubah Status Reservasi</h3>
            <button onclick="closeStatusModal()" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-lg flex items-center justify-center hover:bg-red-50">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6">
            <div class="mb-4">
                <p class="text-sm font-medium text-slate-500 mb-1">Pelanggan</p>
                <p id="modalCustomerName" class="font-bold text-slate-800 text-lg">Nama Pelanggan</p>
            </div>
            
            <div class="mb-6 relative">
                <label for="statusSelect" class="block text-sm font-medium text-slate-700 mb-2">Pilih Status Baru</label>
                <div class="relative">
                    <select id="statusSelect" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-brand focus:border-brand block w-full p-3 outline-none font-medium appearance-none">
                        <option value="Menunggu">Menunggu</option>
                        <option value="Dikonfirmasi">Dikonfirmasi</option>
                        <option value="Dikerjakan">Dikerjakan</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
                    <!-- Custom dropdown arrow -->
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3 justify-end">
                <button onclick="closeStatusModal()" class="px-5 py-2.5 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                    Batal
                </button>
                <button onclick="saveStatus()" class="px-5 py-2.5 rounded-xl font-bold text-white bg-brand hover:bg-brand-dark transition-colors shadow-lg shadow-brand/20">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple Modal Logic
    function openStatusModal(customerName, currentStatus) {
        const modal = document.getElementById('statusModal');
        const content = document.getElementById('statusModalContent');
        
        document.getElementById('modalCustomerName').textContent = customerName;
        document.getElementById('statusSelect').value = currentStatus;
        
        modal.classList.remove('hidden');
        // Trigger reflow
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95');
    }

    function closeStatusModal() {
        const modal = document.getElementById('statusModal');
        const content = document.getElementById('statusModalContent');
        
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
    
    function saveStatus() {
        alert('Status berhasil diubah (Dummy)');
        closeStatusModal();
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
            
            counter.innerText = prefix + current.toFixed(decimals) + suffix;
            
            if (progress < 1) {
                window.requestAnimationFrame(step);
            } else {
                counter.innerText = prefix + target.toFixed(decimals) + suffix;
            }
        };
        
        window.requestAnimationFrame(step);
    });
});
</script>
@endsection
