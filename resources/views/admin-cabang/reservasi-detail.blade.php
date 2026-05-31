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
        <a href="{{ url('/admin-cabang/reservasi') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-brand font-bold text-sm mb-4 transition-colors bg-white px-3 py-1.5 rounded-lg border border-slate-100 shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
        <h2 class="text-3xl font-bold text-slate-800 tracking-tight flex items-center gap-3">
            Detail Reservasi <span class="text-slate-400 font-medium text-xl bg-slate-100 px-2 py-0.5 rounded-lg">#RSV-{{ str_pad($reservasi->id, 8, '0', STR_PAD_LEFT) }}</span>
        </h2>
        <p class="text-slate-500 mt-2 text-sm font-medium">Informasi lengkap reservasi pelanggan dan riwayat operasional</p>
    </div>
    
    <!-- Action Buttons for Status Flow -->
    <div class="flex flex-wrap items-center gap-3">
        <button class="bg-blue-500 hover:brightness-105 active:scale-95 text-white px-4 py-2.5 rounded-xl shadow-md shadow-blue-500/20 transition-all duration-200 ease-out font-semibold text-sm flex items-center gap-2">
            <i class="fas fa-check-circle"></i> Konfirmasi
        </button>
        <button class="bg-amber-500 hover:brightness-105 active:scale-95 text-white px-4 py-2.5 rounded-xl shadow-md shadow-amber-500/20 transition-all duration-200 ease-out font-semibold text-sm flex items-center gap-2 hidden">
            <i class="fas fa-tools"></i> Mulai Pengerjaan
        </button>
        <button class="bg-emerald-500 hover:brightness-105 active:scale-95 text-white px-4 py-2.5 rounded-xl shadow-md shadow-emerald-500/20 transition-all duration-200 ease-out font-semibold text-sm flex items-center gap-2">
            <i class="fas fa-flag-checkered"></i> Selesaikan
        </button>
        <button class="bg-white hover:bg-red-50 active:scale-95 text-slate-600 hover:text-red-600 border border-slate-200 hover:border-red-200 px-4 py-2.5 rounded-xl shadow-sm transition-all duration-200 ease-out font-semibold text-sm flex items-center gap-2">
            <i class="fas fa-times-circle"></i> Batalkan
        </button>
    </div>
</div>

<!-- Status Highlight Bar -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-6 overflow-hidden animate-fade-slide-up stagger-1">
    <!-- Ubah class bg dan text sesuai status: bg-slate-50 (Menunggu), bg-blue-50 (Dikonfirmasi), bg-amber-50 (Dikerjakan), bg-emerald-50 (Selesai), bg-red-50 (Dibatalkan) -->
    <div class="bg-amber-50 border-b border-amber-100 px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center text-xl shadow-inner border border-amber-200">
                <i class="fas fa-tools animate-pulse"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 mb-0.5">
                    <p class="text-amber-800 font-bold text-lg">Status: Sedang Dikerjakan</p>
                    <span class="bg-amber-200 text-amber-800 text-[10px] font-black px-2 py-0.5 rounded uppercase tracking-wider">Aktif</span>
                </div>
                <p class="text-amber-700 text-sm">Mekanik sedang mengerjakan kendaraan pelanggan saat ini.</p>
            </div>
        </div>
        <div class="text-left sm:text-right bg-white/50 px-4 py-2 rounded-xl border border-amber-200/50">
            <p class="text-amber-600/70 text-xs font-bold uppercase tracking-wider mb-1">Terakhir Diperbarui</p>
            <p class="text-amber-900 font-bold text-sm"><i class="far fa-clock mr-1"></i> Hari ini, 14:30 WIB</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
    
    <!-- Left Column: Customer & Service Info -->
    <div class="xl:col-span-2 space-y-6">
        
        <!-- Customer Info Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden animate-fade-slide-up stagger-2">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center">
                    <i class="fas fa-user"></i>
                </div>
                <h3 class="font-bold text-slate-800">Informasi Pelanggan</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1.5">Nama Lengkap</p>
                        <p class="text-slate-800 font-bold text-lg">{{ $reservasi->user->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1.5">Nomor HP</p>
                        <p class="text-slate-800 font-bold text-lg">
                            <a href="https://wa.me/{{ str_replace(['0', '-', ' '], ['62', '', ''], $reservasi->user->phone ?? '') }}" target="_blank" class="text-brand hover:text-brand-dark transition-colors flex items-center gap-2">
                                <i class="fab fa-whatsapp text-emerald-500"></i> {{ $reservasi->user->phone ?? '-' }}
                            </a>
                        </p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1.5">Plat Nomor Kendaraan</p>
                        <p class="text-slate-800 font-bold text-lg">{{ $reservasi->plat ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1.5">Jadwal Reservasi</p>
                        <p class="text-slate-800 font-bold flex items-center gap-2 text-base">
                            <i class="far fa-calendar-alt text-brand"></i> {{ \Carbon\Carbon::parse($reservasi->tanggal)->format('d M Y') }}
                            <span class="bg-orange-50 text-brand border border-orange-100 px-2 py-0.5 rounded text-sm">{{ $reservasi->waktu }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service & Bengkel Info Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden animate-fade-slide-up stagger-3">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center">
                    <i class="fas fa-wrench"></i>
                </div>
                <h3 class="font-bold text-slate-800">Layanan & Keluhan</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b border-slate-100">
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Bengkel Tujuan</p>
                        <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                            <p class="text-slate-800 font-bold"><i class="fas fa-store text-slate-400 mr-2"></i> {{ $reservasi->bengkel->nama ?? '-' }}</p>
                            <p class="text-slate-500 text-xs font-medium mt-1 pl-6">{{ $reservasi->bengkel->alamat ?? '-' }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Layanan Utama</p>
                        <div class="flex items-start justify-between bg-orange-50 border border-orange-100 p-3 rounded-xl">
                            <div>
                                <p class="text-brand font-bold">{{ $reservasi->layanan->nama ?? '-' }}</p>
                                <p class="text-brand-dark text-sm mt-0.5 font-bold">Rp {{ number_format($reservasi->layanan->harga ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="w-6 h-6 rounded-full bg-brand text-white flex items-center justify-center shadow-sm text-xs">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2 flex items-center gap-2">
                        Keluhan Pelanggan <span class="bg-slate-200 text-slate-500 px-2 py-0.5 rounded text-[10px]">Catatan</span>
                    </p>
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-slate-700 font-medium relative">
                        <i class="fas fa-quote-left absolute top-3 left-3 text-slate-200 text-2xl"></i>
                        <p class="relative z-10 pl-6 text-sm leading-relaxed">
                            "{{ $reservasi->keluhan ?? 'Tidak ada keluhan' }}"
                        </p>
                    </div>
                </div>
                
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2 flex items-center justify-between">
                        <span class="flex items-center gap-2">
                            Deskripsi Perbaikan
                            <span class="bg-blue-100 text-blue-600 px-2 py-0.5 rounded text-[10px]">
                                Admin
                            </span>
                        </span>
                    </p>

                    <form action="{{ route('admin-cabang.reservasi.hasil-service', $reservasi->id) }}" method="POST">
                        @csrf
                        <textarea
                            name="hasil_service"
                            rows="3"
                            class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand p-4 outline-none transition-all resize-none"
                            placeholder="Masukkan detail perbaikan yang telah dilakukan pada kendaraan..."
                        >{{ old('hasil_service', $reservasi->hasil_service) }}</textarea>

                        <div class="mt-3 flex justify-end">
                            <button
                                type="submit"
                                class="bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition-colors flex items-center gap-2 shadow-sm"
                            >
                                <i class="fas fa-save"></i>
                                Simpan Catatan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sparepart Info Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden animate-fade-slide-up stagger-4">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3 class="font-bold text-slate-800">Sparepart yang Digunakan</h3>
                </div>
                <button onclick="document.getElementById('modalTambahPart').classList.remove('hidden')" class="text-xs font-bold text-white bg-slate-800 hover:bg-slate-700 transition-colors px-3 py-2 rounded-lg shadow-sm flex items-center gap-2 w-fit">
                    <i class="fas fa-plus"></i> Tambah Part
                </button>
            </div>
            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[500px]">
                        <thead class="bg-white border-b border-slate-100">
                            <tr class="text-slate-400 text-xs uppercase tracking-wider">
                                <th class="px-6 py-3 font-bold">Nama Sparepart</th>
                                <th class="px-6 py-3 font-bold text-center w-24">Qty</th>
                                <th class="px-6 py-3 font-bold text-right w-32">Harga Satuan</th>
                                <th class="px-6 py-3 font-bold text-right w-32">Subtotal</th>
                                <th class="px-6 py-3 font-bold text-center w-16">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">

                        @forelse($reservasi->spareparts as $sparepart)

                        <tr class="hover:bg-slate-50 transition-colors duration-300 ease-out group">

                            <td class="px-6 py-4 font-bold text-slate-700">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-brand"></div>
                                    {{ $sparepart->nama }}
                                </div>
                            </td>

                            <td class="px-6 py-4 text-center">
                                {{ $sparepart->pivot->qty }}
                            </td>

                            <td class="px-6 py-4 text-right text-slate-500 font-medium">
                                Rp {{ number_format($sparepart->pivot->harga,0,',','.') }}
                            </td>

                            <td class="px-6 py-4 text-right font-bold text-slate-800">
                                Rp {{ number_format(
                                    $sparepart->pivot->harga * $sparepart->pivot->qty,
                                    0,
                                    ',',
                                    '.'
                                ) }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                -
                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-400">
                                Belum ada sparepart digunakan
                            </td>
                        </tr>

                        @endforelse
                        <tbody class="divide-y divide-slate-50 text-sm" id="sparepartTbody">
                            {{-- Loading state --}}
                            <tr id="sparepartLoading">
                                <td colspan="5" class="px-6 py-6 text-center text-slate-400">
                                    <i class="fas fa-spinner fa-spin mr-2 text-brand"></i>
                                    <span class="text-sm font-medium">Memuat data sparepart...</span>
                                </td>
                            </tr>
                        </tbody>

                        @php
                            $totalSparepart = $reservasi->spareparts->sum(function ($sparepart) {
                                return $sparepart->pivot->qty * $sparepart->pivot->harga;
                            });
                        @endphp                        

                        <tfoot class="bg-slate-50 border-t border-slate-100">
                            <tr>
                                <td colspan="3"
                                    class="px-6 py-4 text-right font-bold text-slate-500 uppercase text-xs tracking-wider">
                                    Total Sparepart
                                </td>

                                <td class="px-6 py-4 text-right font-black text-slate-800 text-base">
                                    Rp {{ number_format($totalSparepart, 0, ',', '.') }}
                                </td>
                                <td colspan="3" class="px-6 py-4 text-right font-bold text-slate-500 uppercase text-xs tracking-wider">Total Sparepart</td>
                                <td class="px-6 py-4 text-right font-black text-slate-800 text-base" id="totalSparepart">Rp 0</td>
                                <td></td>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Right Column: Cost Summary & Logs -->
    <div class="space-y-6">
        
    @php
        $hargaLayanan = $reservasi->layanan->harga ?? 0;

        $totalSparepart = $reservasi->spareparts->sum(function ($sparepart) {
            return $sparepart->pivot->qty * $sparepart->pivot->harga;
        });

        $diskon = 0;

        $totalTagihan = $hargaLayanan + $totalSparepart - $diskon;
    @endphp    
        <!-- Summary Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden sticky top-24 animate-fade-slide-up stagger-2">
            <div class="bg-slate-800 px-6 py-4 flex items-center justify-center gap-3">
                <i class="fas fa-receipt text-slate-300"></i>
                <h3 class="font-bold text-white tracking-wider uppercase text-sm">Ringkasan Pembayaran</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium"><i class="fas fa-wrench text-slate-300 mr-1 w-4"></i> Layanan Utama</span>
                        <span class="font-bold text-slate-800">
                            Rp {{ number_format($hargaLayanan, 0, ',', '.') }}
                        <span class="font-bold text-slate-800" id="summaryLayanan">
                            Rp {{ number_format($reservasi->layanan->harga ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium"><i class="fas fa-cogs text-slate-300 mr-1 w-4"></i> Sparepart</span>
                        <span class="font-black text-3xl text-brand tracking-tight">
                            Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                        </span>

                        <span class="font-bold text-slate-800" id="summarySparepart">Rp 0</span>

                    </div>
                    <div class="flex justify-between items-center text-sm text-emerald-600 bg-emerald-50 px-2 py-1.5 rounded-lg -mx-2">
                        <span class="font-bold"><i class="fas fa-tags mr-1"></i> Diskon</span>
                        <span class="font-bold">- Rp 0</span>
                    </div>
                </div>
                
                <div class="pt-5 border-t border-dashed border-slate-200">
                    <div class="flex flex-col items-center text-center bg-orange-50 rounded-xl p-4 border border-orange-100">
                        <span class="text-brand-dark text-xs font-bold uppercase tracking-widest mb-1">Total Tagihan</span>

                        <span class="font-black text-3xl text-brand tracking-tight">
                            Rp {{ number_format($totalTagihan, 0, ',', '.') }}

                        <span class="font-black text-3xl text-brand tracking-tight" id="summaryGrandTotal">
                            Rp {{ number_format(($reservasi->total_biaya ?? 0), 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                
                <button class="w-full mt-4 bg-slate-100 hover:bg-slate-200 text-slate-700 py-3 rounded-xl font-bold transition-colors flex items-center justify-center gap-2 border border-slate-200 text-sm">
                    <i class="fas fa-print text-slate-400"></i> Cetak Invoice
                </button>
            </div>
        </div>
        
        <!-- Status Log Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden animate-fade-slide-up stagger-3">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-slate-200 text-slate-500 flex items-center justify-center">
                    <i class="fas fa-history"></i>
                </div>
                <h3 class="font-bold text-slate-800">Riwayat Status</h3>
            </div>
            <div class="p-6">
                <div class="relative border-l-2 border-slate-100 ml-3 space-y-6">
                    <!-- Log Item 1 (Latest) -->
                    <div class="relative pl-6">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-amber-500 border-4 border-white shadow-sm ring-2 ring-amber-100"></div>
                        <div>
                            <p class="font-bold text-amber-600 text-sm">Dikerjakan</p>
                            <p class="text-slate-400 text-xs mt-0.5 font-bold"><i class="far fa-clock"></i> 18 Mei 2026, 14:30 WIB</p>
                            <p class="text-slate-600 text-xs mt-2 bg-slate-50 p-2.5 rounded-lg border border-slate-100 font-medium">Mekanik mulai mengerjakan kendaraan dan melakukan pengecekan sparepart.</p>
                        </div>
                    </div>
                    
                    <!-- Log Item 2 -->
                    <div class="relative pl-6">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-blue-500 border-4 border-white shadow-sm"></div>
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Dikonfirmasi</p>
                            <p class="text-slate-400 text-xs mt-0.5 font-bold"><i class="far fa-clock"></i> 18 Mei 2026, 09:15 WIB</p>
                            <p class="text-slate-600 text-xs mt-2 bg-slate-50 p-2.5 rounded-lg border border-slate-100 font-medium">Reservasi dikonfirmasi oleh Admin. Jadwal disetujui.</p>
                        </div>
                    </div>
                    
                    <!-- Log Item 3 -->
                    <div class="relative pl-6">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-slate-300 border-4 border-white shadow-sm"></div>
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Menunggu</p>
                            <p class="text-slate-400 text-xs mt-0.5 font-bold"><i class="far fa-clock"></i> 17 Mei 2026, 19:45 WIB</p>
                            <p class="text-slate-600 text-xs mt-2 bg-slate-50 p-2.5 rounded-lg border border-slate-100 font-medium">Pelanggan membuat reservasi baru melalui aplikasi web.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Tambah Part -->
<div id="modalTambahPart" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/50 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform scale-100 animate-fade-slide-up m-4">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-box text-brand"></i> Tambah Sparepart
            </h3>
            <button onclick="closeTambahPart()" class="text-slate-400 hover:text-red-500 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <div class="p-6">
            <form
                action="{{ route('admin-cabang.reservasi.tambah-sparepart', $reservasi->id) }}"
                method="POST"
            >
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Pilih Sparepart
                    </label>
                    <div class="relative">
                        <select
                            name="sparepart_id"
                            required
                            class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand block w-full p-3 outline-none font-medium appearance-none cursor-pointer"
                        >
                            <option value="">
                                -- Pilih Part --
                            </option>
                            @foreach($sparepartsAktif as $sp)
                                <option value="{{ $sp->id }}">
                                    {{ $sp->nama }}
                                    -
                                    Rp {{ number_format($sp->harga,0,',','.') }}
                                    (stok: {{ $sp->pivot->stok }})
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Kuantitas (Qty)
                    </label>

                    <input
                        type="number"
                        name="qty"
                        min="1"
                        value="1"
                        required
                        class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand block w-full p-3 outline-none transition-all"
                    >
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button
                        type="button"
                        onclick="document.getElementById('modalTambahPart').classList.add('hidden')"
                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition-colors"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="px-5 py-2.5 bg-brand hover:bg-brand-dark text-white text-sm font-bold rounded-xl transition-colors shadow-md shadow-brand/20"
                    >
                        Tambahkan
                    </button>
                </div>
            </form>
        <div class="p-6 space-y-4">

            {{-- ── Cari Sparepart ── --}}
            <div class="relative">
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Cari Sparepart <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" id="spSearch" autocomplete="off"
                        class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand block w-full pl-9 pr-4 py-3 outline-none transition-all"
                        placeholder="Ketik nama sparepart...">
                </div>
                {{-- Dropdown hasil pencarian --}}
                <div id="spDropdown"
                    class="absolute z-10 left-0 right-0 bg-white border border-slate-200 rounded-xl shadow-xl mt-1 hidden max-h-52 overflow-y-auto">
                    <div id="spDropdownList"></div>
                </div>
                <p id="spNamaErr" class="text-red-500 text-xs mt-1 hidden">Pilih sparepart dari daftar</p>
            </div>

            {{-- ── Info sparepart terpilih ── --}}
            <div id="spSelectedInfo" class="hidden bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-cog text-xs"></i>
                    </div>
                    <div class="min-w-0">
                        <p id="spSelectedNama" class="font-bold text-slate-800 text-sm truncate"></p>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Harga: <span id="spSelectedHarga" class="font-bold text-brand"></span>
                            &nbsp;·&nbsp; Stok: <span id="spSelectedStok" class="font-bold"></span>
                        </p>
                    </div>
                </div>
                <button onclick="resetPilihan()" class="text-slate-400 hover:text-red-500 transition-colors flex-shrink-0" title="Ganti pilihan">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>

            {{-- ── Harga (readonly, dari DB) + Qty ── --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Harga Satuan (Rp)</label>
                    <div class="relative">
                        <input type="number" id="spHarga" min="0" value="0" readonly
                            class="bg-slate-100 border border-slate-200 text-slate-600 text-sm rounded-xl block w-full p-3 outline-none cursor-not-allowed font-medium"
                            placeholder="Otomatis terisi">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                    <p id="spHargaErr" class="text-red-500 text-xs mt-1 hidden">Pilih sparepart terlebih dahulu</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kuantitas (Qty) <span class="text-red-500">*</span></label>
                    <input type="number" id="spQty" min="1" value="1"
                        class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand block w-full p-3 outline-none transition-all">
                </div>
            </div>

            {{-- ── Keterangan ── --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Keterangan <span class="text-slate-400 font-normal text-xs">(opsional)</span>
                </label>
                <input type="text" id="spKeterangan"
                    class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand block w-full p-3 outline-none transition-all"
                    placeholder="cth: Diganti karena aus">
            </div>

            {{-- ── Preview subtotal ── --}}
            <div class="bg-orange-50 border border-orange-100 rounded-xl px-4 py-2.5 flex items-center justify-between">
                <span class="text-slate-500 text-xs font-bold uppercase tracking-wider">Subtotal</span>
                <span class="font-black text-brand text-base" id="spSubtotalPreview">Rp 0</span>
            </div>

            {{-- ── Tombol aksi ── --}}
            <div class="flex justify-end gap-3 pt-1">
                <button onclick="closeTambahPart()"
                    class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition-colors">
                    Batal
                </button>
                <button id="btnSimpanPart" onclick="simpanSparepart()"
                    class="px-5 py-2.5 bg-brand hover:brightness-105 text-white text-sm font-bold rounded-xl transition-colors shadow-md shadow-brand/20 flex items-center gap-2">
                    <i class="fas fa-plus" id="btnSimpanIcon"></i>
                    <span id="btnSimpanText">Tambahkan</span>
                </button>
            </div>
>>>>>>> Stashed changes
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const RESERVASI_ID   = {{ $reservasi->id }};
    const HARGA_LAYANAN  = {{ $reservasi->layanan->harga ?? 0 }};
    const STATUS         = '{{ $reservasi->status }}';
    const CSRF           = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    /* ── Helpers ─────────────────────────────────────────── */
    function rupiah(n) {
        return 'Rp ' + Number(n).toLocaleString('id-ID');
    }

    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    /* ── Recalculate totals setelah setiap perubahan ─────── */
    function recalcTotals() {
        const rows  = document.querySelectorAll('#sparepartTbody tr[data-id]');
        let totalSp = 0;
        rows.forEach(row => {
            const qty   = parseInt(row.dataset.qty   ?? 1);
            const harga = parseInt(row.dataset.harga ?? 0);
            totalSp    += qty * harga;
        });

        document.getElementById('totalSparepart').textContent   = rupiah(totalSp);
        document.getElementById('summarySparepart').textContent = rupiah(totalSp);
        document.getElementById('summaryGrandTotal').textContent = rupiah(HARGA_LAYANAN + totalSp);
    }

    /* ── Render satu baris sparepart ─────────────────────── */
    function renderRow(sp) {
        const tr = document.createElement('tr');
        tr.dataset.id    = sp.id;
        tr.dataset.qty   = sp.qty;
        tr.dataset.harga = sp.harga;
        tr.className     = 'hover:bg-slate-50 transition-colors duration-300 ease-out group';

        const canDelete = !['selesai', 'dibatalkan'].includes(STATUS);
        const deleteBtn = canDelete
            ? `<button onclick="hapusSparepart(${sp.id}, this)"
                    class="w-8 h-8 rounded-lg text-slate-300 hover:text-red-500 hover:bg-red-50 transition-colors inline-flex items-center justify-center"
                    title="Hapus">
                    <i class="fas fa-trash-alt"></i>
        </button>`
            : `<span class="text-slate-200 text-xs">—</span>`;

        tr.innerHTML = `
            <td class="px-6 py-4 font-bold text-slate-700">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-brand flex-shrink-0"></div>
                    <div>
                        <span>${sp.nama}</span>
                        ${sp.keterangan ? `<p class="text-slate-400 font-normal text-xs mt-0.5">${sp.keterangan}</p>` : ''}
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 text-center">
                <div class="inline-flex items-center bg-slate-50 border border-slate-200 rounded-lg">
                    <button onclick="ubahQty(${sp.id}, -1, this)" class="px-2 py-1 text-slate-400 hover:text-brand transition-colors" ${!canDelete ? 'disabled' : ''}>−</button>
                    <span class="font-bold w-8 text-center text-slate-700 qty-val">${sp.qty}</span>
                    <button onclick="ubahQty(${sp.id},  1, this)" class="px-2 py-1 text-slate-400 hover:text-brand transition-colors" ${!canDelete ? 'disabled' : ''}>+</button>
                </div>
            </td>
            <td class="px-6 py-4 text-right text-slate-500 font-medium">${rupiah(sp.harga)}</td>
            <td class="px-6 py-4 text-right font-bold text-slate-800 subtotal-val">${rupiah(sp.harga * sp.qty)}</td>
            <td class="px-6 py-4 text-center">${deleteBtn}</td>`;

        return tr;
    }

    /* ── State empty ─────────────────────────────────────── */
    function showEmpty() {
        const tbody = document.getElementById('sparepartTbody');
        tbody.innerHTML = `
            <tr id="sparepartEmpty">
                <td colspan="5" class="px-6 py-8 text-center text-slate-400">
                    <i class="fas fa-box-open text-3xl mb-2 block text-slate-200"></i>
                    <span class="text-sm font-medium">Belum ada sparepart ditambahkan</span>
                </td>
            </tr>`;
    }

    /* ── Load sparepart dari server ──────────────────────── */
    async function loadSparepart() {
        try {
            const res  = await fetch(`/admin-cabang/reservasi/${RESERVASI_ID}/sparepart`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            const items = Array.isArray(data) ? data : (data.data ?? []);

            const tbody = document.getElementById('sparepartTbody');
            tbody.innerHTML = '';

            if (items.length === 0) {
                showEmpty();
            } else {
                items.forEach(sp => tbody.appendChild(renderRow(sp)));
            }
            recalcTotals();
        } catch (err) {
            console.error('Load sparepart error:', err);
            document.getElementById('sparepartTbody').innerHTML =
                `<tr><td colspan="5" class="px-6 py-4 text-center text-red-400 text-sm">
                    <i class="fas fa-exclamation-circle mr-1"></i> Gagal memuat data
                </td></tr>`;
        }
    }

    /* ── Hapus sparepart ─────────────────────────────────── */
    window.hapusSparepart = async function (sparepartId, btn) {
        if (!confirm('Hapus sparepart ini dari daftar?')) return;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i>';

        try {
            const res = await fetch(`/admin-cabang/reservasi/${RESERVASI_ID}/sparepart/${sparepartId}`, {
                method : 'DELETE',
                headers: {
                    'Accept'          : 'application/json',
                    'X-CSRF-TOKEN'    : CSRF,
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });
            if (!res.ok) throw new Error(res.statusText);

            const row = document.querySelector(`tr[data-id="${sparepartId}"]`);
            if (row) {
                row.style.transition = 'opacity .25s, transform .25s';
                row.style.opacity    = '0';
                row.style.transform  = 'translateX(12px)';
                setTimeout(() => {
                    row.remove();
                    const remaining = document.querySelectorAll('#sparepartTbody tr[data-id]');
                    if (remaining.length === 0) showEmpty();
                    recalcTotals();
                }, 260);
            }
            showToast('Sparepart dihapus', 'Sparepart berhasil dihapus dari daftar.');
        } catch (err) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-trash-alt"></i>';
            showToast('Gagal', 'Tidak dapat menghapus sparepart.', 'error');
        }
    };

    /* ── Update qty ──────────────────────────────────────── */
    window.ubahQty = async function (sparepartId, delta, btn) {
        const row     = document.querySelector(`tr[data-id="${sparepartId}"]`);
        if (!row) return;
        const qtyEl   = row.querySelector('.qty-val');
        const subEl   = row.querySelector('.subtotal-val');
        const harga   = parseInt(row.dataset.harga ?? 0);
        let   qty     = parseInt(row.dataset.qty   ?? 1) + delta;
        if (qty < 1) qty = 1;

        // Optimistic update UI
        row.dataset.qty      = qty;
        qtyEl.textContent    = qty;
        subEl.textContent    = rupiah(harga * qty);
        recalcTotals();

        // Sync ke server
        try {
            await fetch(`/admin-cabang/reservasi/${RESERVASI_ID}/sparepart/${sparepartId}`, {
                method : 'PATCH',
                headers: {
                    'Content-Type'    : 'application/json',
                    'Accept'          : 'application/json',
                    'X-CSRF-TOKEN'    : CSRF,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ qty })
            });
        } catch (err) {
            console.error('Update qty error:', err);
        }
    };

    /* ── Modal Tambah Part ───────────────────────────────── */

    // State sparepart yang sedang dipilih
    let selectedSparepart = null; // { id, nama, harga, stok }
    let searchDebounce    = null;

    // Reset pilihan sparepart
    window.resetPilihan = function () {
        selectedSparepart = null;
        document.getElementById('spSearch').value              = '';
        document.getElementById('spHarga').value               = '0';
        document.getElementById('spSubtotalPreview').textContent = 'Rp 0';
        document.getElementById('spSelectedInfo').classList.add('hidden');
        document.getElementById('spSearch').closest('.relative').querySelector('input').disabled = false;
        document.getElementById('spSearch').focus();
    };

    // Pilih sparepart dari dropdown
    function pilihSparepart(item) {
        selectedSparepart = item;

        // Isi field info
        document.getElementById('spSelectedNama').textContent  = item.nama;
        document.getElementById('spSelectedHarga').textContent = rupiah(item.harga);
        document.getElementById('spSelectedStok').textContent  =
            item.stok > 0
                ? item.stok + ' pcs'
                : '<span class="text-red-500">Habis</span>';
        document.getElementById('spSelectedStok').innerHTML    =
            item.stok > 0
                ? `<span class="text-emerald-600">${item.stok} pcs</span>`
                : `<span class="text-red-500">Habis</span>`;

        // Isi harga (readonly)
        document.getElementById('spHarga').value = item.harga;

        // Tampilkan info & sembunyikan search
        document.getElementById('spSearch').value = '';
        document.getElementById('spDropdown').classList.add('hidden');
        document.getElementById('spSelectedInfo').classList.remove('hidden');

        // Update preview subtotal
        updateSubtotalPreview();

        // Fokus ke qty
        document.getElementById('spQty').focus();
        document.getElementById('spNamaErr').classList.add('hidden');
        document.getElementById('spHargaErr').classList.add('hidden');
    }

    // Update preview subtotal
    function updateSubtotalPreview() {
        const h = parseInt(document.getElementById('spHarga').value) || 0;
        const q = parseInt(document.getElementById('spQty').value)   || 1;
        document.getElementById('spSubtotalPreview').textContent = rupiah(h * q);
    }

    // Render dropdown hasil pencarian
    function renderDropdown(items) {
        const list = document.getElementById('spDropdownList');
        const dd   = document.getElementById('spDropdown');

        if (!items || items.length === 0) {
            list.innerHTML = `
                <div class="px-4 py-5 text-center text-slate-400 text-sm">
                    <i class="fas fa-search mb-1 block text-slate-200 text-lg"></i>
                    Sparepart tidak ditemukan
                </div>`;
            dd.classList.remove('hidden');
            return;
        }

        list.innerHTML = items.map(item => `
            <button type="button"
                onclick='pilihSparepart(${JSON.stringify(item)})'
                class="w-full text-left px-4 py-3 hover:bg-orange-50 transition-colors border-b border-slate-50 last:border-0 flex items-center justify-between gap-3 group">
                <div class="min-w-0">
                    <p class="font-bold text-slate-800 text-sm truncate group-hover:text-brand transition-colors">${escHtml(item.nama)}</p>
                    <p class="text-xs text-slate-400 mt-0.5">
                        ${rupiah(item.harga)}
                        &nbsp;·&nbsp;
                        Stok:
                        <span class="${item.stok > 0 ? 'text-emerald-600 font-bold' : 'text-red-500 font-bold'}">
                            ${item.stok > 0 ? item.stok + ' pcs' : 'Habis'}
                        </span>
                    </p>
                </div>
                <i class="fas fa-chevron-right text-slate-200 group-hover:text-brand text-xs flex-shrink-0 transition-colors"></i>
            </button>`
        ).join('');

        dd.classList.remove('hidden');
    }

    // Search sparepart via API SparepartController@search
    async function searchSparepart(keyword) {
        const dd   = document.getElementById('spDropdown');
        const list = document.getElementById('spDropdownList');

        if (keyword.length < 1) {
            dd.classList.add('hidden');
            return;
        }

        // Loading state
        list.innerHTML = `
            <div class="px-4 py-4 text-center text-slate-400 text-sm">
                <i class="fas fa-spinner fa-spin mr-1"></i> Mencari...
            </div>`;
        dd.classList.remove('hidden');

        try {
            const res = await fetch(`/admin-cabang/sparepart/search?search=${encodeURIComponent(keyword)}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error('Gagal fetch');
            const data = await res.json();
            renderDropdown(data);
        } catch (err) {
            list.innerHTML = `
                <div class="px-4 py-4 text-center text-red-400 text-sm">
                    <i class="fas fa-exclamation-circle mr-1"></i> Gagal memuat sparepart
                </div>`;
        }
    }

    // Event: input search dengan debounce 300ms
    document.getElementById('spSearch')?.addEventListener('input', function () {
        clearTimeout(searchDebounce);
        if (selectedSparepart) return; // sudah dipilih, abaikan
        searchDebounce = setTimeout(() => searchSparepart(this.value.trim()), 300);
    });

    // Event: klik di luar dropdown untuk menutup
    document.addEventListener('click', function (e) {
        const dd     = document.getElementById('spDropdown');
        const search = document.getElementById('spSearch');
        if (dd && !dd.contains(e.target) && e.target !== search) {
            dd.classList.add('hidden');
        }
    });

    // Event: qty berubah → update preview subtotal
    document.getElementById('spQty')?.addEventListener('input', updateSubtotalPreview);

    window.closeTambahPart = function () {
        document.getElementById('modalTambahPart').classList.add('hidden');
        // Reset semua field
        selectedSparepart = null;
        document.getElementById('spSearch').value              = '';
        document.getElementById('spHarga').value               = '0';
        document.getElementById('spQty').value                 = '1';
        document.getElementById('spKeterangan').value          = '';
        document.getElementById('spSubtotalPreview').textContent = 'Rp 0';
        document.getElementById('spSelectedInfo').classList.add('hidden');
        document.getElementById('spDropdown').classList.add('hidden');
        document.getElementById('spNamaErr').classList.add('hidden');
        document.getElementById('spHargaErr').classList.add('hidden');
    };

    window.simpanSparepart = async function () {
        // Validasi: harus sudah pilih dari dropdown
        if (!selectedSparepart) {
            document.getElementById('spNamaErr').classList.remove('hidden');
            document.getElementById('spSearch').focus();
            return;
        }
        document.getElementById('spNamaErr').classList.add('hidden');

        const harga      = parseInt(document.getElementById('spHarga').value) || 0;
        const qty        = parseInt(document.getElementById('spQty').value)   || 1;
        const keterangan = document.getElementById('spKeterangan').value.trim();

        // Nama & harga dari DB (selectedSparepart), bukan input manual
        const nama = selectedSparepart.nama;

        if (harga <= 0) {
            document.getElementById('spHargaErr').classList.remove('hidden');
            return;
        }
        document.getElementById('spHargaErr').classList.add('hidden');

        // Loading state
        const btn  = document.getElementById('btnSimpanPart');
        const icon = document.getElementById('btnSimpanIcon');
        const text = document.getElementById('btnSimpanText');
        btn.disabled     = true;
        icon.className   = 'fas fa-spinner fa-spin';
        text.textContent = 'Menyimpan...';

        try {
            const res  = await fetch(`/admin-cabang/reservasi/${RESERVASI_ID}/sparepart`, {
                method : 'POST',
                headers: {
                    'Content-Type'    : 'application/json',
                    'Accept'          : 'application/json',
                    'X-CSRF-TOKEN'    : CSRF,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ nama, harga, qty, keterangan })
            });
            if (!res.ok) throw new Error(res.statusText);
            const sp = await res.json();

            // Hapus state empty jika ada
            document.getElementById('sparepartEmpty')?.remove();

            // Tambah baris baru ke tabel
            const tbody = document.getElementById('sparepartTbody');
            const newRow = renderRow(sp.data ?? sp);
            newRow.style.opacity   = '0';
            newRow.style.transform = 'translateY(-6px)';
            tbody.appendChild(newRow);
            requestAnimationFrame(() => {
                newRow.style.transition = 'opacity .3s, transform .3s';
                newRow.style.opacity    = '1';
                newRow.style.transform  = 'translateY(0)';
            });

            recalcTotals();
            closeTambahPart();
            showToast('Sparepart ditambahkan', `${nama} berhasil ditambahkan ke daftar.`);
        } catch (err) {
            showToast('Gagal', 'Tidak dapat menyimpan sparepart.', 'error');
        } finally {
            btn.disabled     = false;
            icon.className   = 'fas fa-plus';
            text.textContent = 'Tambahkan';
        }
    };

    /* ── Toast helper ────────────────────────────────────── */
    function showToast(title, msg, type = 'success') {
        const borderColor = type === 'error' ? 'border-red-500'     : 'border-emerald-500';
        const bgColor     = type === 'error' ? 'bg-red-500/20'      : 'bg-emerald-500/20';
        const textColor   = type === 'error' ? 'text-red-400'       : 'text-emerald-400';
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

    /* ── Init ────────────────────────────────────────────── */
    document.addEventListener('DOMContentLoaded', loadSparepart);

})();
</script>
@endsection