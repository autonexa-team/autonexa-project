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
    <!-- Card 1: Total -->
    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 group hover:shadow-md hover:-translate-y-1 transition-all duration-300">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-500 text-sm font-bold mb-1 group-hover:text-slate-700 transition-colors">Total Pelanggan</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="{{ $totalPelanggan }}">0</h3>
            </div>
            <div class="w-12 h-12 bg-slate-50 text-slate-500 rounded-2xl flex items-center justify-center text-xl border border-slate-100 group-hover:bg-slate-100 group-hover:scale-110 transition-all duration-300">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <!-- Aktif -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 group hover:shadow-md hover:-translate-y-1 transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-500 text-sm font-bold mb-1 group-hover:text-emerald-700 transition-colors">Pelanggan Aktif</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="{{ $totalAktif }}">0</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-xl border border-emerald-100 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                <i class="fas fa-user-check"></i>
            </div>
        </div>
        <div class="mt-3 text-xs text-slate-400 font-bold">Lebih dari 1x reservasi</div>
    </div>

    <!-- Baru -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 group hover:shadow-md hover:-translate-y-1 transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-500 text-sm font-bold mb-1 group-hover:text-blue-600 transition-colors">Pelanggan Baru</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="{{ $totalBaru }}">0</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center text-xl border border-blue-100 group-hover:scale-110 group-hover:-rotate-6 transition-all duration-300">
                <i class="fas fa-user-plus"></i>
            </div>
        </div>
        <div class="mt-3 text-xs text-slate-400 font-bold">Bergabung bulan ini</div>
    </div>

    <!-- Tidak Aktif -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 group hover:shadow-md hover:-translate-y-1 transition-all">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-500 text-sm font-bold mb-1 group-hover:text-slate-700 transition-colors">Tidak Aktif</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight counter-value" data-target="{{ $totalTidakAktif }}">0</h3>
            </div>
            <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center text-xl border border-slate-100 group-hover:bg-slate-200 transition-all duration-300">
                <i class="fas fa-user-clock"></i>
            </div>
        </div>
        <div class="mt-3 text-xs text-slate-400 font-bold">> 3 bulan tanpa reservasi</div>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden animate-fade" style="animation-delay: 200ms; opacity: 0;">
    
    <!-- Filter Bar -->
    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
        <form method="GET" action="{{ route('admin-cabang.pelanggan-cabang') }}" 
            class="flex flex-col md:flex-row gap-4 justify-between items-center w-full">
            
            <!-- Search -->
            <div class="w-full md:w-1/3 flex items-center bg-white rounded-xl px-4 py-2 border border-slate-200 focus-within:border-brand/50 focus-within:ring-2 focus-within:ring-brand/20 transition-all shadow-sm">
                <i class="fas fa-search text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari nama atau nomor HP..." 
                    class="bg-transparent border-none outline-none ml-3 w-full text-sm font-medium text-slate-700">
            </div>

            <!-- Filter Status -->
            <div class="w-full md:w-auto flex gap-2 overflow-x-auto pb-2 md:pb-0">
                <a href="?status=semua" 
                class="{{ $status === 'semua' ? 'bg-slate-800 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-100' }} px-5 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-colors">
                    Semua
                </a>
                <a href="?status=aktif"
                class="{{ $status === 'aktif' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200' }} px-5 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-colors">
                    Aktif
                </a>
                <a href="?status=baru"
                class="{{ $status === 'baru' ? 'bg-blue-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200' }} px-5 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-colors">
                    Baru
                </a>
                <a href="?status=tidak_aktif"
                class="{{ $status === 'tidak_aktif' ? 'bg-slate-500 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-100' }} px-5 py-2 rounded-lg text-sm font-bold whitespace-nowrap transition-colors">
                    Tidak Aktif
                </a>
            </div>
        </form>
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
                    <th class="p-5 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
            @forelse($pelanggan as $index => $p)
                @php
                    $lastReservasi = $p->terakhir_reservasi;
                    $totalRes = $p->total_reservasi;
                    $isAktif = $totalRes > 1;
                    $isBaru = \Carbon\Carbon::parse($lastReservasi)->gte(now()->startOfMonth());
                    $isTidakAktif = \Carbon\Carbon::parse($lastReservasi)->lt(now()->subMonths(3));

                    if ($isAktif) {
                        $statusLabel = 'Aktif';
                        $statusClass = 'bg-emerald-100 border-emerald-200 text-emerald-700';
                        $dotClass = 'bg-emerald-500';
                        $rowClass = 'border-b border-emerald-50 bg-emerald-50/20 hover:bg-emerald-50/60';
                    } elseif ($isBaru) {
                        $statusLabel = 'Baru';
                        $statusClass = 'bg-blue-50 border-blue-200 text-blue-700';
                        $dotClass = 'bg-blue-500';
                        $rowClass = 'border-b border-slate-50 hover:bg-slate-50/80';
                    } else {
                        $statusLabel = 'Tidak Aktif';
                        $statusClass = 'bg-slate-100 border-slate-200 text-slate-500';
                        $dotClass = 'bg-slate-400';
                        $rowClass = 'border-b border-slate-50 hover:bg-slate-50/80';
                    }
                @endphp
                <tr class="{{ $rowClass }} transition-colors group">
                    <td class="p-5 text-slate-500 font-bold text-center">
                        {{ ($pelanggan->currentPage() - 1) * $pelanggan->perPage() + $index + 1 }}
                    </td>
                    <td class="p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 border border-slate-100 shadow-sm">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($p->name) }}&background=f1f5f9&color=10b981" alt="{{ $p->name }}">
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 normal-case" style="text-transform:capitalize;">
                                    {{ Str::title(strtolower($p->name)) }}
                                </h4>
                                <p class="text-xs text-slate-500 mt-0.5">ID: CUST-{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-5">
                        <p class="font-bold text-slate-700">{{ $p->phone ?? '-' }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $p->email }}</p>
                    </td>
                    <td class="p-5 text-center">
                        <span class="bg-white text-slate-700 border border-slate-200 font-black px-3 py-1 rounded-lg text-sm shadow-sm inline-block">
                            {{ $totalRes }}
                        </span>
                    </td>
                    <td class="p-5 text-slate-600 font-medium">
                        {{ $lastReservasi ? \Carbon\Carbon::parse($lastReservasi)->translatedFormat('d M Y') : '-' }}
                    </td>
                    <td class="p-5 text-center">
                        <span class="{{ $statusClass }} border px-3 py-1 rounded-full text-[11px] font-bold inline-flex items-center gap-1.5 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span> {{ $statusLabel }}
                        </span>
                    </td>
                    <td class="p-5 text-center">
                        <div class="flex justify-center items-center gap-2">
                            <div class="tooltip-container relative inline-block">
                                <button onclick="openModal({{ $p->id }})" 
                                    class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-brand hover:border-brand hover:bg-orange-50 transition-colors flex items-center justify-center shadow-sm">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <span class="tooltip-text absolute bottom-full right-0 mb-2 bg-slate-800 text-white text-[10px] font-bold px-2 py-1.5 rounded-lg whitespace-nowrap shadow-md">Lihat Detail</span>
                            </div>
                            <div class="tooltip-container relative inline-block">

                                <!-- tombol aksi riwayat reservasi -->
                                <a href="{{ route('admin-cabang.reservasi') }}?user_id={{ $p->id }}" 
                                    class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-blue-500 hover:border-blue-500 hover:bg-blue-50 transition-colors flex items-center justify-center shadow-sm">
                                    <i class="fas fa-history"></i>
                                </a>
                                <span class="tooltip-text absolute bottom-full right-0 mb-2 bg-slate-800 text-white text-[10px] font-bold px-2 py-1.5 rounded-lg whitespace-nowrap shadow-md">Riwayat Reservasi</span>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="p-10 text-center text-slate-400 font-medium">
                        Belum ada pelanggan yang terdaftar di cabang ini.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="p-5 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-white">
        <span class="text-sm text-slate-500 font-medium">
            Menampilkan {{ $pelanggan->firstItem() }}-{{ $pelanggan->lastItem() }} 
            dari {{ $pelanggan->total() }} pelanggan
        </span>
        <div>{{ $pelanggan->links() }}</div>
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
<!-- Modal Detail Pelanggan -->
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
        
        <!-- Modal Body — diisi dinamis oleh JS -->
        <div class="p-6" id="modalBody">
            <div class="flex justify-center items-center py-10">
                <i class="fas fa-spinner fa-spin text-brand text-2xl"></i>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/80 rounded-b-2xl flex justify-between items-center">
            <a id="modalRiwayatLink" href="#" class="text-brand font-bold text-sm hover:underline hover:text-brand-dark transition-colors">
                Lihat Semua Riwayat &rarr;
            </a>
            <div class="flex gap-3">
                <button onclick="closeModal()" class="px-5 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold rounded-xl text-sm transition-colors shadow-sm">Tutup</button>
                <a id="modalWaLink" href="#" target="_blank"
                   class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-sm transition-colors shadow-md flex items-center gap-2">
                    <i class="fab fa-whatsapp text-emerald-400"></i> Hubungi WA
                </a>
            </div>
        </div>
    </div>
</div>

<script>
const pelangganData = {
    @foreach($pelanggan as $p)
    {{ $p->id }}: {
        name: @json($p->name),
        custId: "CUST-{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}",
        phone: @json($p->phone ?? '-'),
        email: @json($p->email),
        totalReservasi: {{ $p->total_reservasi }},
        terakhirReservasi: @json($p->terakhir_reservasi ? \Carbon\Carbon::parse($p->terakhir_reservasi)->translatedFormat('d M Y') : '-'),
        terdaftar: @json(\Carbon\Carbon::parse($p->created_at)->translatedFormat('M Y')),
        riwayatUrl: "{{ route('admin-cabang.reservasi') }}?user_id={{ $p->id }}",
        waUrl: "https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->phone ?? '') }}",
    },
    @endforeach
};

function openModal(userId) {
    const p = pelangganData[userId];
    if (!p) return;

    let statusLabel = 'Tidak Aktif';
    let statusBg = '#f1f5f9'; let statusColor = '#475569';
    let statusBorder = '#cbd5e1'; let dotColor = '#94a3b8';

    if (p.totalReservasi > 1) {
        statusLabel = 'Aktif';
        statusBg = '#d1fae5'; statusColor = '#065f46';
        statusBorder = '#6ee7b7'; dotColor = '#10b981';
    } else if (p.totalReservasi === 1) {
        statusLabel = 'Baru';
        statusBg = '#dbeafe'; statusColor = '#1e40af';
        statusBorder = '#93c5fd'; dotColor = '#3b82f6';
    }

    // Capitalize nama
    const namaRapi = p.name.toLowerCase().replace(/\b\w/g, c => c.toUpperCase());

    document.getElementById('modalBody').innerHTML = `
        <div style="display:flex;align-items:center;gap:16px;padding-bottom:20px;border-bottom:1px solid #f1f5f9;margin-bottom:20px;">
            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(namaRapi)}&background=fff7ed&color=f97316&size=80&bold=true&font-size=0.38"
                 style="width:64px;height:64px;border-radius:50%;border:2px solid #fed7aa;flex-shrink:0;">
            <div style="flex:1;min-width:0;">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap;">
                    <div>
                        <h4 style="font-size:17px;font-weight:800;color:#1e293b;margin:0 0 3px;line-height:1.3;">${namaRapi}</h4>
                        <p style="font-size:12px;color:#94a3b8;margin:0;font-weight:500;">${p.custId} &nbsp;·&nbsp; Terdaftar ${p.terdaftar}</p>
                    </div>
                    <span style="background:${statusBg};color:${statusColor};border:1px solid ${statusBorder};padding:3px 10px;border-radius:999px;font-size:11px;font-weight:700;display:inline-flex;align-items:center;gap:5px;white-space:nowrap;">
                        <span style="width:6px;height:6px;border-radius:50%;background:${dotColor};display:inline-block;flex-shrink:0;"></span>
                        ${statusLabel}
                    </span>
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
            <!-- Kontak -->
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:16px;">
                <p style="font-size:10px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.07em;margin:0 0 12px;">Informasi Kontak</p>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                    <span style="width:32px;height:32px;background:#dcfce7;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fab fa-whatsapp" style="color:#16a34a;font-size:15px;"></i>
                    </span>
                    <div>
                        <p style="font-size:11px;color:#94a3b8;margin:0 0 1px;font-weight:600;">WhatsApp</p>
                        <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0;">${p.phone}</p>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="width:32px;height:32px;background:#eff6ff;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-envelope" style="color:#3b82f6;font-size:13px;"></i>
                    </span>
                    <div style="min-width:0;">
                        <p style="font-size:11px;color:#94a3b8;margin:0 0 1px;font-weight:600;">Email</p>
                        <p style="font-size:12px;color:#475569;margin:0;word-break:break-all;">${p.email}</p>
                    </div>
                </div>
            </div>

            <!-- Statistik -->
            <div style="background:linear-gradient(135deg,#fff7ed 0%,#ffedd5 100%);border:1px solid #fed7aa;border-radius:12px;padding:16px;display:flex;flex-direction:column;justify-content:center;gap:6px;">
                <p style="font-size:10px;font-weight:700;color:#9a3412;text-transform:uppercase;letter-spacing:0.07em;margin:0;">Total Kunjungan</p>
                <div style="display:flex;align-items:baseline;gap:6px;">
                    <span style="font-size:40px;font-weight:900;color:#f97316;line-height:1;">${p.totalReservasi}</span>
                    <span style="font-size:14px;font-weight:700;color:#c2410c;">Kali</span>
                </div>
                <div style="display:flex;align-items:center;gap:5px;background:rgba(255,255,255,0.6);border-radius:8px;padding:5px 8px;width:fit-content;">
                    <i class="fas fa-calendar-check" style="color:#ea580c;font-size:11px;"></i>
                    <span style="font-size:11px;color:#9a3412;font-weight:600;">Terakhir: ${p.terakhirReservasi}</span>
                </div>
            </div>
        </div>
    `;

    document.getElementById('modalRiwayatLink').href = p.riwayatUrl;
    document.getElementById('modalWaLink').href = p.waUrl;

    const modal = document.getElementById('modalDetail');
    modal.classList.remove('hidden');
    requestAnimationFrame(() => {
        document.getElementById('modalBackdrop').classList.replace('opacity-0', 'opacity-100');
        document.getElementById('modalContent').classList.remove('scale-95', 'opacity-0');
        document.getElementById('modalContent').classList.add('scale-100', 'opacity-100');
    });
}

function closeModal() {
    const backdrop = document.getElementById('modalBackdrop');
    const content = document.getElementById('modalContent');
    backdrop.classList.replace('opacity-100', 'opacity-0');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => document.getElementById('modalDetail').classList.add('hidden'), 300);
}

// Counter animation
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".counter-value").forEach(counter => {
        const target = parseFloat(counter.getAttribute("data-target"));
        const duration = 1500;
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const easeProgress = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            counter.innerText = (easeProgress * target).toLocaleString('id-ID', { maximumFractionDigits: 0 });
            if (progress < 1) window.requestAnimationFrame(step);
            else counter.innerText = target.toLocaleString('id-ID');
        };
        window.requestAnimationFrame(step);
    });
});
</script>
@endsection
