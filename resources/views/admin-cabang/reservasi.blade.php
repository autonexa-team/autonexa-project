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

<!-- sudah filter dan ambil data, nampilin banner riwayat reservasi aksi pelanggan -->
@if(isset($pelangganDipilih) && $pelangganDipilih)
<div class="mb-6 bg-blue-50 border border-blue-200 rounded-2xl p-5 animate-fade-slide-up">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h3 class="font-bold text-blue-800 text-lg">
                Riwayat Reservasi Pelanggan
            </h3>

            <p class="text-blue-600 text-sm mt-1">
                {{ $pelangganDipilih->name }}
                • {{ $reservasi->total() }} reservasi ditemukan
            </p>
        </div>

        <a href="{{ route('admin-cabang.reservasi') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-200 rounded-xl text-sm font-semibold text-blue-700 hover:bg-blue-100 transition">
            <i class="fas fa-arrow-left"></i>
            Lihat Semua Reservasi
        </a>
    </div>
</div>
@endif

<!-- Statistic Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card 1 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-lg hover:-translate-y-1 hover:border-slate-300 transition-all duration-300 ease-out group relative overflow-hidden animate-fade-slide-up stagger-1">
        <div class="absolute right-0 top-0 w-24 h-24 bg-gradient-to-br from-slate-50 to-transparent rounded-bl-full opacity-50 group-hover:scale-110 transition-transform"></div>
        <div class="flex justify-between items-start relative z-10">
            <div>
                <p class="text-slate-500 text-sm font-semibold mb-1">Total Reservasi</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $totalReservasi }}</h3>
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
                <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ $hariIni }}</h3>
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
                <h3 class="text-3xl font-black text-slate-800 tracking-tight" id="card-diproses">{{ $sedangDikerjakan }}</h3>
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
                <h3 class="text-3xl font-black text-slate-800 tracking-tight" id="card-selesai">{{ $selesai }}</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-xl shadow-inner border border-emerald-100">
                <i class="fas fa-check-double"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
@if(!request('user_id'))
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
@endif

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
                @if($reservasi->count() > 0)
                    @foreach($reservasi as $key => $res)
                        @php
                            $statusColors = [
                                'pending' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'dot' => 'bg-slate-500', 'label' => 'Menunggu'],
                                'dikonfirmasi' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'dot' => 'bg-blue-500', 'label' => 'Dikonfirmasi'],
                                'diproses' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'dot' => 'bg-amber-500', 'label' => 'Dikerjakan'],
                                'selesai' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'dot' => 'bg-emerald-500', 'label' => 'Selesai'],
                                'dibatalkan' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'dot' => 'bg-red-500', 'label' => 'Dibatalkan'],
                            ];
                            $status = $statusColors[$res->status] ?? $statusColors['pending'];
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors duration-300 ease-out group" data-status="{{ $res->status }}">
                            <td class="px-6 py-4 text-center font-semibold text-slate-600">{{ ($reservasi->currentPage() - 1) * $reservasi->perPage() + $key + 1 }}</td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800">{{ $res->user->name ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium">{{ $res->layanan->nama ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-800 flex items-center gap-2">
                                    <i class="far fa-calendar-alt text-slate-400"></i> {{ $res->tanggal->format('d M Y') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $res->total_biaya ? 'Rp ' . number_format($res->total_biaya, 0, ',', '.') : '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold {{ $status['bg'] }} {{ $status['text'] }} border {{ str_replace('bg-', 'border-', $status['bg']) }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $status['dot'] }} {{ $res->status === 'diproses' ? 'animate-pulse' : '' }}"></span> {{ $status['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin-cabang.reservasi-detail', $res->id) }}"
                                    class="px-3 py-1.5 rounded-lg text-xs font-bold text-brand bg-orange-50 hover:bg-brand hover:text-white transition-colors">
                                        Detail
                                    </a>

                                    <button onclick="openStatusModal({{ $res->id }}, '{{ $res->user->name }}', '{{ $status['label'] }}', {{ $key }})" class="px-3 py-1.5 rounded-lg text-xs font-bold text-slate-500 bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-colors">
                                        Ubah Status
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                                    <i class="fas fa-box-open text-4xl text-slate-300"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800">Belum ada reservasi</h3>
                                <p class="text-slate-500 text-sm mt-1 max-w-sm">Data reservasi pelanggan akan muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($reservasi->hasPages())
    <div class="px-6 py-4 border-t border-slate-100 flex flex-col md:flex-row gap-4 items-center justify-between bg-white">
        <span class="text-sm text-slate-500 font-medium">
            Menampilkan {{ $reservasi->firstItem() ?? 0 }}-{{ $reservasi->lastItem() ?? 0 }} dari {{ $reservasi->total() }} data
        </span>
        <div class="flex items-center gap-2">
            @if($reservasi->onFirstPage())
                <button class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 text-slate-400 cursor-not-allowed opacity-50">
                    <i class="fas fa-chevron-left text-xs"></i>
                </button>
            @else
                <a href="{{ $reservasi->previousPageUrl() }}" class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                    <i class="fas fa-chevron-left text-xs"></i>
                </a>
            @endif
            
            @foreach($reservasi->getUrlRange(1, $reservasi->lastPage()) as $page => $url)
                @if($page == $reservasi->currentPage())
                    <button class="w-8 h-8 rounded-lg flex items-center justify-center bg-brand text-white font-bold text-sm shadow-md shadow-brand/20">{{ $page }}</button>
                @else
                    <a href="{{ $url }}" class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors font-bold text-sm">{{ $page }}</a>
                @endif
            @endforeach
            
            @if($reservasi->hasMorePages())
                <a href="{{ $reservasi->nextPageUrl() }}" class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                    <i class="fas fa-chevron-right text-xs"></i>
                </a>
            @else
                <button class="w-8 h-8 rounded-lg flex items-center justify-center border border-slate-200 text-slate-400 cursor-not-allowed opacity-50">
                    <i class="fas fa-chevron-right text-xs"></i>
                </button>
            @endif
        </div>
    </div>
    @endif
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
            <!-- Hidden inputs to store reservation ID and row index -->
            <input type="hidden" id="reservasiId" value="">
            <input type="hidden" id="rowIndex" value="">

            <div class="mb-4">
                <p class="text-sm font-medium text-slate-500 mb-1">Pelanggan</p>
                <p id="modalCustomerName" class="font-bold text-slate-800 text-lg">Nama Pelanggan</p>
            </div>
            
            <div class="mb-6 relative">
                <label for="statusSelect" class="block text-sm font-medium text-slate-700 mb-2">Pilih Status Baru</label>
                <div class="relative">
                    <select id="statusSelect" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-brand focus:border-brand block w-full p-3 outline-none font-medium appearance-none">
                        <option value="Menunggu">Menunggu Konfirmasi</option>
                        <option value="Dikonfirmasi">Dikonfirmasi (Approved)</option>
                        <option value="Proses">Sedang Diproses (On-going)</option>
                        <option value="Selesai">Selesai (Completed)</option>
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
                <button onclick="saveStatus()" class="px-5 py-2.5 rounded-xl font-bold text-white bg-brand hover:bg-brand-dark transition-colors shadow-lg shadow-brand/20 flex items-center gap-2">
                    <span id="btnText">Simpan Perubahan</span>
                    <i id="btnIcon" class="fas fa-check-circle"></i>
                    <i id="loadingIcon" class="fas fa-circle-notch fa-spin hidden"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const statusMap = {
        'Menunggu': 'pending',
        'Dikonfirmasi': 'dikonfirmasi',
        'Proses': 'diproses',
        'Selesai': 'selesai',
        'Dibatalkan': 'dibatalkan'
    };

    const statusColors = {
        'pending':      { bg: 'bg-slate-100', text: 'text-slate-600', dot: 'bg-slate-500', label: 'Menunggu' },
        'dikonfirmasi': { bg: 'bg-blue-50',   text: 'text-blue-600',  dot: 'bg-blue-500',  label: 'Dikonfirmasi' },
        'diproses':     { bg: 'bg-amber-50',  text: 'text-amber-600', dot: 'bg-amber-500', label: 'Dikerjakan' },
        'selesai':      { bg: 'bg-emerald-50',text: 'text-emerald-600',dot: 'bg-emerald-500',label: 'Selesai' },
        'dibatalkan':   { bg: 'bg-red-50',    text: 'text-red-600',   dot: 'bg-red-500',   label: 'Dibatalkan' }
    };

    function openStatusModal(reservasiId, customerName, currentStatus, rowIndex) {
        document.getElementById('reservasiId').value = reservasiId;
        document.getElementById('rowIndex').value = rowIndex;
        document.getElementById('modalCustomerName').textContent = customerName;
        document.getElementById('statusSelect').value = currentStatus === 'Dikerjakan' ? 'Proses' : currentStatus;

        const modal = document.getElementById('statusModal');
        const content = document.getElementById('statusModalContent');
        modal.classList.remove('hidden');
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95');
    }

    function closeStatusModal() {
        const modal = document.getElementById('statusModal');
        const content = document.getElementById('statusModalContent');
        modal.classList.add('opacity-0');
        content.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function saveStatus() {
        const reservasiId   = document.getElementById('reservasiId').value;
        const rowIndex      = parseInt(document.getElementById('rowIndex').value);
        const statusDisplay = document.getElementById('statusSelect').value;
        const statusDb      = statusMap[statusDisplay];

        const btnSave    = document.querySelector('button[onclick="saveStatus()"]');
        const btnText    = document.getElementById('btnText');
        const btnIcon    = document.getElementById('btnIcon');
        const loadingIcon= document.getElementById('loadingIcon');

        btnSave.disabled = true;
        btnText.textContent = 'Memproses...';
        btnIcon.classList.add('hidden');
        loadingIcon.classList.remove('hidden');

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ||
                          Array.from(document.querySelectorAll('input')).find(i => i.name === '_token')?.value;

        fetch(`/admin-cabang/reservasi/${reservasiId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: statusDb })
        })
        .then(r => r.json())
        .then(result => {
            btnSave.disabled = false;
            btnText.textContent = 'Simpan Perubahan';
            btnIcon.classList.remove('hidden');
            loadingIcon.classList.add('hidden');

            if (result.success) {
                // ── 1. Baca status lama dari badge tabel ──
                const rows = document.querySelectorAll('table tbody tr');
                let statusLama = null;
                if (rows[rowIndex]) {
                    statusLama = rows[rowIndex].getAttribute('data-status');
                    // Update data-status ke status baru supaya ubah status berikutnya juga benar
                    rows[rowIndex].setAttribute('data-status', statusDb);
                }

                // ── 2. Update badge tabel ──
                if (rows[rowIndex]) {
                    const statusCell = rows[rowIndex].querySelector('td:nth-child(6)');
                    const newColor   = statusColors[statusDb];
                    const newBorder  = newColor.bg.replace('bg-', 'border-');
                    const pulse      = statusDb === 'diproses' ? 'animate-pulse' : '';
                    statusCell.innerHTML = `
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold ${newColor.bg} ${newColor.text} border ${newBorder}">
                            <span class="w-1.5 h-1.5 rounded-full ${newColor.dot} ${pulse}"></span> ${newColor.label}
                        </span>`;
                }

                // ── 3. Update stat cards ──
                const cardSelesai  = document.getElementById('card-selesai');
                const cardDiproses = document.getElementById('card-diproses');

                // Kurangi card lama
                if (statusLama === 'diproses' && cardDiproses)
                    cardDiproses.textContent = Math.max(0, parseInt(cardDiproses.textContent) - 1);
                if (statusLama === 'selesai' && cardSelesai)
                    cardSelesai.textContent = Math.max(0, parseInt(cardSelesai.textContent) - 1);

                // Tambah card baru
                if (statusDb === 'selesai' && cardSelesai)
                    cardSelesai.textContent = parseInt(cardSelesai.textContent) + 1;
                if (statusDb === 'diproses' && cardDiproses)
                    cardDiproses.textContent = parseInt(cardDiproses.textContent) + 1;

                showToast('Status berhasil diubah', 'Perubahan status reservasi telah disimpan.');
                setTimeout(() => closeStatusModal(), 1000);

            } else {
                showToast('Gagal mengubah status', result.message || 'Silakan coba lagi.', 'error');
            }
        })
        .catch(error => {
            btnSave.disabled = false;
            btnText.textContent = 'Simpan Perubahan';
            btnIcon.classList.remove('hidden');
            loadingIcon.classList.add('hidden');
            console.error('Error:', error);
            showToast('Error', 'Terjadi kesalahan saat mengubah status', 'error');
        });
    }

    function showToast(title, msg, type = 'success') {
        const borderColor = type === 'error' ? 'border-red-500' : 'border-emerald-500';
        const bgColor     = type === 'error' ? 'bg-red-500/20'  : 'bg-emerald-500/20';
        const textColor   = type === 'error' ? 'text-red-400'   : 'text-emerald-400';
        const icon        = type === 'error' ? 'fa-exclamation-circle' : 'fa-check';

        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 bg-slate-800 text-white p-4 rounded-xl shadow-2xl flex items-start gap-4 transform transition-all duration-500 ease-out translate-x-10 opacity-0 border-l-4 ${borderColor}`;
        toast.innerHTML = `
            <div class="w-8 h-8 rounded-full ${bgColor} ${textColor} flex items-center justify-center flex-shrink-0">
                <i class="fas ${icon}"></i>
            </div>
            <div>
                <h4 class="font-bold text-sm text-white">${title}</h4>
                <p class="text-xs text-slate-300 mt-1">${msg}</p>
            </div>`;
        document.body.appendChild(toast);
        requestAnimationFrame(() => toast.classList.remove('translate-x-10', 'opacity-0'));
        setTimeout(() => {
            toast.classList.add('translate-x-10', 'opacity-0');
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }

    // ── Search & Filter Reservasi ──
const searchReservasi = document.querySelector('input[placeholder="Cari nama pelanggan..."]');
const statusSelect    = document.querySelector('select');
const dateInput       = document.querySelector('input[type="date"]');
const tableRows       = document.querySelectorAll('table tbody tr');

function applyFilter() {
    const keyword = searchReservasi?.value.toLowerCase() ?? '';
    const status  = statusSelect?.value ?? '';
    const date    = dateInput?.value ?? '';

    tableRows.forEach(row => {
        // Skip empty state row
        if (row.querySelector('td[colspan]')) return;

        const nama    = row.querySelector('td:nth-child(2) p')?.textContent.toLowerCase() ?? '';
        const label   = row.querySelector('td:nth-child(6) span')?.textContent.trim() ?? '';
        const tanggal = row.querySelector('td:nth-child(4) span')?.textContent.trim() ?? '';

        const matchSearch = nama.includes(keyword);
        const matchStatus = !status || status === 'Semua Status' || label.includes(status);

        // Konversi tanggal filter (yyyy-mm-dd) ke format tampilan (dd MMM yyyy)
        let matchDate = true;
        if (date) {
            const d = new Date(date);
            const formatted = d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
            matchDate = tanggal.replace(/\s+/g, ' ').includes(formatted.replace(/\s+/g, ' '));
        }

        row.style.display = (matchSearch && matchStatus && matchDate) ? '' : 'none';
    });
}

searchReservasi?.addEventListener('input', applyFilter);
statusSelect?.addEventListener('change', applyFilter);
dateInput?.addEventListener('change', applyFilter);

document.querySelector('button.bg-slate-800')?.addEventListener('click', applyFilter);
</script>
@endsection
