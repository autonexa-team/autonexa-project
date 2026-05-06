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
<div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4 animate-fade-slide-up">
    <div>
        <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Layanan Bengkel Cabang</h2>
        <p class="text-slate-500 mt-2 text-sm font-medium">Aktifkan atau nonaktifkan layanan yang tersedia khusus untuk cabang Anda sesuai dengan standar pusat.</p>
    </div>
</div>

<!-- Statistic Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Card 1 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 ease-out group relative overflow-hidden animate-fade-slide-up stagger-1">
        <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-br from-slate-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-500 text-sm font-semibold mb-1">Total Layanan Pusat</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="24">0</h3>
            </div>
            <div class="w-12 h-12 bg-slate-50 text-slate-600 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-slate-100">
                <i class="fas fa-tools"></i>
            </div>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 ease-out group relative overflow-hidden animate-fade-slide-up stagger-2">
        <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-br from-emerald-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-500 text-sm font-semibold mb-1">Layanan Aktif di Cabang</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="18">0</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-emerald-100">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>

    <!-- Card 3 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 ease-out group relative overflow-hidden animate-fade-slide-up stagger-3">
        <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-br from-slate-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-500 text-sm font-semibold mb-1">Layanan Nonaktif</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="6">0</h3>
            </div>
            <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-slate-100">
                <i class="fas fa-pause-circle"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Section -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden animate-fade-slide-up stagger-4">
    <!-- Filter Bar -->
    <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row gap-4 justify-between items-center">
        <div class="w-full md:w-1/3 flex items-center bg-white rounded-xl px-4 py-2 border border-slate-200 focus-within:border-brand/50 focus-within:ring-2 focus-within:ring-brand/20 transition-all shadow-sm">
            <i class="fas fa-search text-slate-400"></i>
            <input type="text" placeholder="Cari nama layanan..." class="bg-transparent border-none outline-none ml-3 w-full text-sm font-medium text-slate-700">
        </div>
        
        <div class="w-full md:w-auto flex gap-2">
            <button class="bg-slate-800 text-white px-5 py-2 rounded-xl text-sm font-bold shadow-sm hover:bg-slate-900 transition-colors">Semua</button>
            <button class="bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 px-5 py-2 rounded-xl text-sm font-bold transition-colors">Aktif</button>
            <button class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 px-5 py-2 rounded-xl text-sm font-bold transition-colors">Nonaktif</button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-slate-100 text-xs uppercase tracking-wider text-slate-400">
                    <th class="p-5 font-bold w-12 text-center">No</th>
                    <th class="p-5 font-bold">Informasi Layanan</th>
                    <th class="p-5 font-bold">Estimasi Harga</th>
                    <th class="p-5 font-bold">Durasi</th>
                    <th class="p-5 font-bold text-center">Status Cabang</th>
                    <th class="p-5 font-bold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <!-- Row 1: Aktif -->
                <tr class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors group">
                    <td class="p-5 text-slate-500 font-bold text-center">1</td>
                    <td class="p-5">
                        <h4 class="font-bold text-slate-800 group-hover:text-brand transition-colors">Ganti Oli Mesin</h4>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-1">Penggantian oli mesin menggunakan oli standar atau sintetis sesuai rekomendasi.</p>
                    </td>
                    <td class="p-5">
                        <p class="font-bold text-slate-800">Rp 150.000</p>
                    </td>
                    <td class="p-5">
                        <div class="flex items-center gap-1.5 text-slate-600 font-medium">
                            <i class="far fa-clock text-slate-400"></i> 30 Menit
                        </div>
                    </td>
                    <td class="p-5 text-center">
                        <span class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                        </span>
                    </td>
                    <td class="p-5 text-right">
                        <button onclick="toggleStatus(this, 'Aktif')" class="bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-700 border border-slate-200 px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-2 ml-auto w-32">
                            <i class="fas fa-power-off"></i> Nonaktifkan
                        </button>
                    </td>
                </tr>

                <!-- Row 2: Aktif -->
                <tr class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors group">
                    <td class="p-5 text-slate-500 font-bold text-center">2</td>
                    <td class="p-5">
                        <h4 class="font-bold text-slate-800 group-hover:text-brand transition-colors">Servis Berkala (Tune Up)</h4>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-1">Pengecekan dan pembersihan sistem pembakaran, filter udara, dan busi.</p>
                    </td>
                    <td class="p-5">
                        <p class="font-bold text-slate-800">Rp 350.000</p>
                    </td>
                    <td class="p-5">
                        <div class="flex items-center gap-1.5 text-slate-600 font-medium">
                            <i class="far fa-clock text-slate-400"></i> 90 Menit
                        </div>
                    </td>
                    <td class="p-5 text-center">
                        <span class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                        </span>
                    </td>
                    <td class="p-5 text-right">
                        <button onclick="toggleStatus(this, 'Aktif')" class="bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-700 border border-slate-200 px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-2 ml-auto w-32">
                            <i class="fas fa-power-off"></i> Nonaktifkan
                        </button>
                    </td>
                </tr>

                <!-- Row 3: Nonaktif -->
                <tr class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors group">
                    <td class="p-5 text-slate-500 font-bold text-center">3</td>
                    <td class="p-5">
                        <h4 class="font-bold text-slate-800 opacity-60">Spooring & Balancing</h4>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-1 opacity-60">Penyelarasan sudut roda dan keseimbangan roda untuk kenyamanan berkendara.</p>
                    </td>
                    <td class="p-5">
                        <p class="font-bold text-slate-800 opacity-60">Rp 250.000</p>
                    </td>
                    <td class="p-5">
                        <div class="flex items-center gap-1.5 text-slate-600 font-medium opacity-60">
                            <i class="far fa-clock text-slate-400"></i> 60 Menit
                        </div>
                    </td>
                    <td class="p-5 text-center">
                        <span class="bg-slate-50 border border-slate-200 text-slate-500 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif
                        </span>
                    </td>
                    <td class="p-5 text-right">
                        <button onclick="toggleStatus(this, 'Nonaktif')" class="bg-brand text-white hover:bg-brand-dark shadow-md shadow-brand/20 px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 ml-auto w-32">
                            <i class="fas fa-check"></i> Aktifkan
                        </button>
                    </td>
                </tr>

                 <!-- Row 4: Aktif -->
                <tr class="hover:bg-slate-50/80 transition-colors group">
                    <td class="p-5 text-slate-500 font-bold text-center">4</td>
                    <td class="p-5">
                        <h4 class="font-bold text-slate-800 group-hover:text-brand transition-colors">Ganti Kampas Rem</h4>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-1">Penggantian kampas rem depan atau belakang beserta pembersihan area pengereman.</p>
                    </td>
                    <td class="p-5">
                        <p class="font-bold text-slate-800">Rp 450.000</p>
                    </td>
                    <td class="p-5">
                        <div class="flex items-center gap-1.5 text-slate-600 font-medium">
                            <i class="far fa-clock text-slate-400"></i> 45 Menit
                        </div>
                    </td>
                    <td class="p-5 text-center">
                        <span class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                        </span>
                    </td>
                    <td class="p-5 text-right">
                        <button onclick="toggleStatus(this, 'Aktif')" class="bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-700 border border-slate-200 px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-2 ml-auto w-32">
                            <i class="fas fa-power-off"></i> Nonaktifkan
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Custom Script for Number Animation and Toggle Logic -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Number Counters Animation
    const counters = document.querySelectorAll(".counter-value");
    const duration = 1500; // 1.5 seconds

    counters.forEach(counter => {
        const target = parseFloat(counter.getAttribute("data-target"));
        
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            
            // easeOutExpo easing function
            const easeProgress = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            const current = Math.floor(easeProgress * target);
            
            counter.innerText = current;
            
            if (progress < 1) {
                window.requestAnimationFrame(step);
            } else {
                counter.innerText = target;
            }
        };
        
        window.requestAnimationFrame(step);
    });
});

// Simulate Toggle Status Feature (Client-side)
function toggleStatus(btn, currentStatus) {
    const row = btn.closest('tr');
    const badgeCell = row.querySelector('td:nth-child(5)');
    const title = row.querySelector('h4');
    const desc = row.querySelector('p');
    const price = row.querySelector('td:nth-child(3) p');
    const time = row.querySelector('td:nth-child(4) div');
    
    if (currentStatus === 'Aktif') {
        if(confirm('Nonaktifkan layanan ini di cabang Anda?')) {
            // Ubah button menjadi Aktifkan (style Orange Brand)
            btn.outerHTML = `<button onclick="toggleStatus(this, 'Nonaktif')" class="bg-brand text-white hover:bg-brand-dark shadow-md shadow-brand/20 px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 ml-auto w-32"><i class="fas fa-check"></i> Aktifkan</button>`;
            
            // Ubah badge menjadi Nonaktif (Abu-abu)
            badgeCell.innerHTML = `<span class="bg-slate-50 border border-slate-200 text-slate-500 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Nonaktif</span>`;
            
            // Tambah efek opacity redup pada teks baris untuk menandakan tidak aktif
            title.classList.add('opacity-60');
            desc.classList.add('opacity-60');
            price.classList.add('opacity-60');
            time.classList.add('opacity-60');
            title.classList.remove('group-hover:text-brand');
        }
    } else {
        if(confirm('Aktifkan layanan ini di cabang Anda?')) {
            // Ubah button menjadi Nonaktifkan (style Neutral)
            btn.outerHTML = `<button onclick="toggleStatus(this, 'Aktif')" class="bg-slate-100 text-slate-500 hover:bg-slate-200 hover:text-slate-700 border border-slate-200 px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center justify-center gap-2 ml-auto w-32"><i class="fas fa-power-off"></i> Nonaktifkan</button>`;
            
            // Ubah badge menjadi Aktif (Hijau)
            badgeCell.innerHTML = `<span class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5 shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif</span>`;
            
            // Hilangkan efek opacity redup
            title.classList.remove('opacity-60');
            desc.classList.remove('opacity-60');
            price.classList.remove('opacity-60');
            time.classList.remove('opacity-60');
            title.classList.add('group-hover:text-brand');
        }
    }
}
</script>
@endsection
