@extends('layout.admin-cabang')

@section('content')
<style>
    @keyframes fadeSlideUp {
        0%   { opacity: 0; transform: translateY(10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-slide-up { animation: fadeSlideUp 0.4s cubic-bezier(0.16,1,0.3,1) forwards; opacity: 0; }
    .stagger-1 { animation-delay: 50ms; }
    .stagger-2 { animation-delay: 100ms; }
    .stagger-3 { animation-delay: 150ms; }
    .stagger-4 { animation-delay: 200ms; }
    .stagger-5 { animation-delay: 250ms; }
</style>

@php
    $statusConfig = [
        'pending'      => ['bg'=>'bg-slate-50',   'border'=>'border-slate-200',  'icon_bg'=>'bg-slate-100',   'icon_text'=>'text-slate-600',  'icon'=>'fa-clock',        'label'=>'Menunggu Konfirmasi', 'dot'=>'bg-slate-500',   'text'=>'text-slate-800',  'badge_bg'=>'bg-slate-100',   'badge_text'=>'text-slate-600'],
        'dikonfirmasi' => ['bg'=>'bg-blue-50',    'border'=>'border-blue-200',   'icon_bg'=>'bg-blue-100',    'icon_text'=>'text-blue-600',   'icon'=>'fa-check-circle', 'label'=>'Dikonfirmasi',        'dot'=>'bg-blue-500',    'text'=>'text-blue-800',   'badge_bg'=>'bg-blue-100',    'badge_text'=>'text-blue-600'],
        'diproses'     => ['bg'=>'bg-amber-50',   'border'=>'border-amber-200',  'icon_bg'=>'bg-amber-100',   'icon_text'=>'text-amber-600',  'icon'=>'fa-tools',        'label'=>'Sedang Dikerjakan',   'dot'=>'bg-amber-500',   'text'=>'text-amber-800',  'badge_bg'=>'bg-amber-100',   'badge_text'=>'text-amber-600'],
        'selesai'      => ['bg'=>'bg-emerald-50', 'border'=>'border-emerald-200','icon_bg'=>'bg-emerald-100', 'icon_text'=>'text-emerald-600','icon'=>'fa-flag-checkered','label'=>'Selesai',            'dot'=>'bg-emerald-500', 'text'=>'text-emerald-800','badge_bg'=>'bg-emerald-100', 'badge_text'=>'text-emerald-600'],
        'dibatalkan'   => ['bg'=>'bg-red-50',     'border'=>'border-red-200',    'icon_bg'=>'bg-red-100',     'icon_text'=>'text-red-600',    'icon'=>'fa-times-circle', 'label'=>'Dibatalkan',          'dot'=>'bg-red-500',     'text'=>'text-red-800',    'badge_bg'=>'bg-red-100',     'badge_text'=>'text-red-600'],
    ];
    $sc = $statusConfig[$reservasi->status] ?? $statusConfig['pending'];

    // canEdit dihitung dari status saja — tidak bergantung relasi spareparts
    $canEdit = !in_array($reservasi->status, ['selesai', 'dibatalkan']);

    // Load layanan jika belum di-eager load oleh controller
    if (!$reservasi->relationLoaded('layanan')) {
        $reservasi->load('layanan');
    }
    $hargaLayanan = $reservasi->layanan?->harga ?? 0;

    // Gunakan ReservasiSparepart query langsung agar tidak crash
    // jika relasi di model masih belongsToMany
    $sparepartRows  = \App\Models\ReservasiSparepart::query()
        ->where('reservasi_id', '=', $reservasi->id)
        ->get();
    $totalSparepart = $sparepartRows->sum(fn($s) => $s->qty * $s->harga);
    $totalTagihan   = $hargaLayanan + $totalSparepart;
@endphp

{{-- ── Header ───────────────────────────────────────────────── --}}
<div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4 animate-fade-slide-up">
    <div>
        <a href="{{ url('/admin-cabang/reservasi') }}"
           class="inline-flex items-center gap-2 text-slate-400 hover:text-brand font-bold text-sm mb-4 transition-colors bg-white px-3 py-1.5 rounded-lg border border-slate-100 shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
        <h2 class="text-3xl font-bold text-slate-800 tracking-tight flex items-center gap-3">
            Detail Reservasi
            <span class="text-slate-400 font-medium text-xl bg-slate-100 px-2 py-0.5 rounded-lg">
                #RSV-{{ str_pad($reservasi->id, 8, '0', STR_PAD_LEFT) }}
            </span>
        </h2>
        <p class="text-slate-500 mt-2 text-sm font-medium">Informasi lengkap reservasi pelanggan</p>
    </div>

    {{-- Tombol ubah status --}}
    @if($canEdit)
    <div class="flex flex-wrap items-center gap-3">
        @if($reservasi->status === 'pending')
        <button onclick="ubahStatus('dikonfirmasi')"
                class="bg-blue-500 hover:brightness-105 active:scale-95 text-white px-4 py-2.5 rounded-xl shadow-md shadow-blue-500/20 transition-all font-semibold text-sm flex items-center gap-2">
            <i class="fas fa-check-circle"></i> Konfirmasi
        </button>
        @endif
        @if($reservasi->status === 'dikonfirmasi')
        <button onclick="ubahStatus('diproses')"
                class="bg-amber-500 hover:brightness-105 active:scale-95 text-white px-4 py-2.5 rounded-xl shadow-md shadow-amber-500/20 transition-all font-semibold text-sm flex items-center gap-2">
            <i class="fas fa-tools"></i> Mulai Pengerjaan
        </button>
        @endif
        @if($reservasi->status === 'diproses')
        <button onclick="ubahStatus('selesai')"
                class="bg-emerald-500 hover:brightness-105 active:scale-95 text-white px-4 py-2.5 rounded-xl shadow-md shadow-emerald-500/20 transition-all font-semibold text-sm flex items-center gap-2">
            <i class="fas fa-flag-checkered"></i> Selesaikan
        </button>
        @endif
        @if(!in_array($reservasi->status, ['selesai','dibatalkan']))
        <button onclick="ubahStatus('dibatalkan')"
                class="bg-white hover:bg-red-50 active:scale-95 text-slate-600 hover:text-red-600 border border-slate-200 hover:border-red-200 px-4 py-2.5 rounded-xl shadow-sm transition-all font-semibold text-sm flex items-center gap-2">
            <i class="fas fa-times-circle"></i> Batalkan
        </button>
        @endif
    </div>
    @endif
</div>

{{-- ── Status Bar ───────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-6 overflow-hidden animate-fade-slide-up stagger-1">
    <div class="{{ $sc['bg'] }} {{ $sc['border'] }} border-b px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 {{ $sc['icon_bg'] }} {{ $sc['icon_text'] }} rounded-full flex items-center justify-center text-xl shadow-inner">
                <i class="fas {{ $sc['icon'] }} {{ $reservasi->status === 'diproses' ? 'animate-pulse' : '' }}"></i>
            </div>
            <div>
                <p class="{{ $sc['text'] }} font-bold text-lg">Status: {{ $sc['label'] }}</p>
                <p class="text-sm {{ $sc['text'] }} opacity-75">
                    Terakhir diperbarui: {{ $reservasi->updated_at->format('d M Y, H:i') }} WIB
                </p>
            </div>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold {{ $sc['badge_bg'] }} {{ $sc['badge_text'] }}">
            <span class="w-2 h-2 rounded-full {{ $sc['dot'] }}"></span>
            {{ $sc['label'] }}
        </span>
    </div>
</div>

{{-- ── Main Grid ────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">

    {{-- Left: Info Cards --}}
    <div class="xl:col-span-2 space-y-6">

        {{-- Informasi Pelanggan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden animate-fade-slide-up stagger-2">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center">
                    <i class="fas fa-user"></i>
                </div>
                <h3 class="font-bold text-slate-800">Informasi Pelanggan</h3>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1.5">Nama Lengkap</p>
                    <p class="text-slate-800 font-bold text-lg">{{ $reservasi->user?->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1.5">Nomor HP</p>
                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $reservasi->user?->phone ?? '')) }}"
                       target="_blank"
                       class="text-brand hover:text-brand-dark transition-colors flex items-center gap-2 font-bold text-lg">
                        <i class="fab fa-whatsapp text-emerald-500"></i>
                        {{ $reservasi->user?->phone ?? '-' }}
                    </a>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1.5">Plat Kendaraan</p>
                    <p class="text-slate-800 font-bold text-lg">{{ $reservasi->plat ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1.5">Jadwal Reservasi</p>
                    <p class="text-slate-800 font-bold flex items-center gap-2 text-base">
                        <i class="far fa-calendar-alt text-brand"></i>
                        {{ \Carbon\Carbon::parse($reservasi->tanggal)->format('d M Y') }}
                        <span class="bg-orange-50 text-brand border border-orange-100 px-2 py-0.5 rounded text-sm">
                            {{ $reservasi->waktu }} WIB
                        </span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Layanan & Keluhan --}}
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
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Bengkel</p>
                        <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                            <p class="text-slate-800 font-bold">
                                <i class="fas fa-store text-slate-400 mr-2"></i>
                                {{ $reservasi->bengkel?->nama ?? '-' }}
                            </p>
                            <p class="text-slate-500 text-xs font-medium mt-1 pl-6">
                                {{ $reservasi->bengkel?->alamat ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Layanan Utama</p>
                        <div class="flex items-start justify-between bg-orange-50 border border-orange-100 p-3 rounded-xl">
                            <div>
                                <p class="text-brand font-bold">{{ $reservasi->layanan?->nama ?? '-' }}</p>
                                <p class="text-brand-dark text-sm mt-0.5 font-bold">
                                    Rp {{ number_format($hargaLayanan, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="w-6 h-6 rounded-full bg-brand text-white flex items-center justify-center shadow-sm text-xs">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Keluhan Pelanggan</p>
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-slate-700 font-medium relative">
                        <i class="fas fa-quote-left absolute top-3 left-3 text-slate-200 text-2xl"></i>
                        <p class="relative z-10 pl-6 text-sm leading-relaxed">
                            "{{ $reservasi->keluhan ?? 'Tidak ada keluhan' }}"
                        </p>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2 flex items-center gap-2">
                        <span>Deskripsi Perbaikan</span>
                        <span class="bg-blue-100 text-blue-600 px-2 py-0.5 rounded text-[10px]">Admin</span>
                    </p>
                    <form action="{{ route('admin-cabang.reservasi.hasil-service', $reservasi->id) }}" method="POST">
                        @csrf
                        <textarea
                            name="hasil_service"
                            rows="3"
                            {{ !$canEdit ? 'readonly' : '' }}
                            class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand p-4 outline-none transition-all resize-none {{ !$canEdit ? 'cursor-not-allowed opacity-75' : '' }}"
                            placeholder="Masukkan detail perbaikan yang telah dilakukan..."
                        >{{ old('hasil_service', $reservasi->hasil_service) }}</textarea>
                        @if($canEdit)
                        <div class="mt-3 flex justify-end">
                            <button type="submit"
                                class="bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition-colors flex items-center gap-2 shadow-sm">
                                <i class="fas fa-save"></i> Simpan Catatan
                            </button>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        {{-- Sparepart --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden animate-fade-slide-up stagger-4">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3 class="font-bold text-slate-800">Sparepart yang Digunakan</h3>
                </div>
                <button onclick="bukaModalTambahPart()"
                        id="btnTambahPart"
                        @if(!$canEdit) disabled @endif
                        class="text-xs font-bold text-white px-4 py-2.5 rounded-xl shadow-md flex items-center gap-2 w-fit transition-all
                               {{ $canEdit
                                   ? 'bg-slate-800 hover:bg-slate-700 active:scale-95 cursor-pointer shadow-slate-800/20'
                                   : 'bg-slate-300 cursor-not-allowed opacity-60' }}"
                        title="{{ $canEdit ? 'Tambah sparepart' : 'Tidak dapat menambah sparepart pada reservasi yang sudah selesai/dibatalkan' }}">
                    <i class="fas fa-plus"></i> Tambah Part
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[500px]">
                    <thead class="bg-white border-b border-slate-100">
                        <tr class="text-slate-400 text-xs uppercase tracking-wider">
                            <th class="px-6 py-3 font-bold">Nama Sparepart</th>
                            <th class="px-6 py-3 font-bold text-center w-28">Qty</th>
                            <th class="px-6 py-3 font-bold text-right w-36">Harga Satuan</th>
                            <th class="px-6 py-3 font-bold text-right w-36">Subtotal</th>
                            @if($canEdit)<th class="px-6 py-3 font-bold text-center w-16">Aksi</th>@endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm" id="sparepartTbody">
                        <tr id="sparepartLoading">
                            <td colspan="{{ $canEdit ? 5 : 4 }}" class="px-6 py-6 text-center text-slate-400">
                                <i class="fas fa-spinner fa-spin mr-2 text-brand"></i>
                                <span class="text-sm font-medium">Memuat data sparepart...</span>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-slate-50 border-t border-slate-100">
                        <tr>
                            <td colspan="{{ $canEdit ? 3 : 2 }}"
                                class="px-6 py-4 text-right font-bold text-slate-500 uppercase text-xs tracking-wider">
                                Total Sparepart
                            </td>
                            <td class="px-6 py-4 text-right font-black text-slate-800 text-base" id="totalSparepart">
                                Rp {{ number_format($totalSparepart, 0, ',', '.') }}
                            </td>
                            @if($canEdit)<td></td>@endif
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>

    {{-- Right: Summary & Log --}}
    <div class="space-y-6">

        {{-- Ringkasan Pembayaran --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden sticky top-24 animate-fade-slide-up stagger-2">
            <div class="bg-slate-800 px-6 py-4 flex items-center justify-center gap-3">
                <i class="fas fa-receipt text-slate-300"></i>
                <h3 class="font-bold text-white tracking-wider uppercase text-sm">Ringkasan Pembayaran</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">
                            <i class="fas fa-wrench text-slate-300 mr-1 w-4"></i> Layanan Utama
                        </span>
                        <span class="font-bold text-slate-800" id="summaryLayanan">
                            Rp {{ number_format($hargaLayanan, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">
                            <i class="fas fa-cogs text-slate-300 mr-1 w-4"></i> Sparepart
                        </span>
                        <span class="font-bold text-slate-800" id="summarySparepart">
                            Rp {{ number_format($totalSparepart, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-sm text-emerald-600 bg-emerald-50 px-2 py-1.5 rounded-lg -mx-2">
                        <span class="font-bold"><i class="fas fa-tags mr-1"></i> Diskon</span>
                        <span class="font-bold">- Rp 0</span>
                    </div>
                </div>
                <div class="pt-5 border-t border-dashed border-slate-200">
                    <div class="flex flex-col items-center text-center bg-orange-50 rounded-xl p-4 border border-orange-100">
                        <span class="text-brand-dark text-xs font-bold uppercase tracking-widest mb-1">Total Tagihan</span>
                        <span class="font-black text-3xl text-brand tracking-tight" id="summaryGrandTotal">
                            Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                <button class="w-full mt-4 bg-slate-100 hover:bg-slate-200 text-slate-700 py-3 rounded-xl font-bold transition-colors flex items-center justify-center gap-2 border border-slate-200 text-sm">
                    <i class="fas fa-print text-slate-400"></i> Cetak Invoice
                </button>
            </div>
        </div>

        {{-- Riwayat Status --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden animate-fade-slide-up stagger-3">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-slate-200 text-slate-500 flex items-center justify-center">
                    <i class="fas fa-history"></i>
                </div>
                <h3 class="font-bold text-slate-800">Riwayat</h3>
            </div>
            <div class="p-6">
                <div class="relative border-l-2 border-slate-100 ml-3 space-y-6">
                    <div class="relative pl-6">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full {{ $sc['dot'] }} border-4 border-white shadow-sm"></div>
                        <p class="font-bold {{ $sc['badge_text'] }} text-sm">{{ $sc['label'] }}</p>
                        <p class="text-slate-400 text-xs mt-0.5 font-bold">
                            <i class="far fa-clock"></i> {{ $reservasi->updated_at->format('d M Y, H:i') }} WIB
                        </p>
                    </div>
                    <div class="relative pl-6">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-slate-300 border-4 border-white shadow-sm"></div>
                        <p class="font-bold text-slate-800 text-sm">Reservasi Dibuat</p>
                        <p class="text-slate-400 text-xs mt-0.5 font-bold">
                            <i class="far fa-clock"></i> {{ $reservasi->created_at->format('d M Y, H:i') }} WIB
                        </p>
                        <p class="text-slate-600 text-xs mt-2 bg-slate-50 p-2.5 rounded-lg border border-slate-100 font-medium">
                            Reservasi dibuat oleh pelanggan.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ── Modal Tambah Part ────────────────────────────────────── --}}
<div id="modalTambahPart"
     class="fixed inset-0 z-50 flex items-center justify-center hidden bg-slate-900/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden m-4">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-box text-brand"></i> Tambah Sparepart
            </h3>
            <button onclick="closeTambahPart()" class="text-slate-400 hover:text-red-500 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">

            {{-- Cari Sparepart --}}
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
                <div id="spDropdown"
                     class="absolute z-10 left-0 right-0 bg-white border border-slate-200 rounded-xl shadow-xl mt-1 hidden max-h-52 overflow-y-auto">
                    <div id="spDropdownList"></div>
                </div>
                <p id="spNamaErr" class="text-red-500 text-xs mt-1 hidden">Pilih sparepart dari daftar</p>
            </div>

            {{-- Info sparepart terpilih --}}
            <div id="spSelectedInfo"
                 class="hidden bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-cog text-xs"></i>
                    </div>
                    <div class="min-w-0">
                        <p id="spSelectedNama" class="font-bold text-slate-800 text-sm truncate"></p>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Harga: <span id="spSelectedHarga" class="font-bold text-brand"></span>
                            &nbsp;·&nbsp;
                            Stok: <span id="spSelectedStok" class="font-bold"></span>
                        </p>
                    </div>
                </div>
                <button onclick="resetPilihan()" class="text-slate-400 hover:text-red-500 transition-colors flex-shrink-0">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>

            {{-- Harga & Qty --}}
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
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Kuantitas (Qty) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="spQty" min="1" value="1"
                           class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand block w-full p-3 outline-none transition-all">
                </div>
            </div>

            {{-- Keterangan --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Keterangan <span class="text-slate-400 font-normal text-xs">(opsional)</span>
                </label>
                <input type="text" id="spKeterangan"
                       class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand block w-full p-3 outline-none transition-all"
                       placeholder="cth: Diganti karena aus">
            </div>

            {{-- Preview subtotal --}}
            <div class="bg-orange-50 border border-orange-100 rounded-xl px-4 py-2.5 flex items-center justify-between">
                <span class="text-slate-500 text-xs font-bold uppercase tracking-wider">Subtotal</span>
                <span class="font-black text-brand text-base" id="spSubtotalPreview">Rp 0</span>
            </div>

            {{-- Tombol aksi --}}
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
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const RESERVASI_ID  = {{ $reservasi->id }};
    const HARGA_LAYANAN = {{ $hargaLayanan }};
    const STATUS        = '{{ $reservasi->status }}';
    const CAN_EDIT      = {{ $canEdit ? 'true' : 'false' }};
    const CSRF          = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    /* ── Helpers ─────────────────────────────────────── */
    const rupiah = n => 'Rp ' + Number(n).toLocaleString('id-ID');

    function escHtml(str) {
        return String(str)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;')
            .replace(/'/g,'&#39;');
    }

    /* ── Recalculate totals ──────────────────────────── */
    function recalcTotals() {
        let totalSp = 0;
        document.querySelectorAll('#sparepartTbody tr[data-id]').forEach(row => {
            totalSp += parseInt(row.dataset.qty ?? 1) * parseInt(row.dataset.harga ?? 0);
        });
        document.getElementById('totalSparepart').textContent    = rupiah(totalSp);
        document.getElementById('summarySparepart').textContent  = rupiah(totalSp);
        document.getElementById('summaryGrandTotal').textContent = rupiah(HARGA_LAYANAN + totalSp);
    }

    /* ── Render satu baris ───────────────────────────── */
    function renderRow(sp) {
        const tr = document.createElement('tr');
        tr.dataset.id    = sp.id;
        tr.dataset.qty   = sp.qty;
        tr.dataset.harga = sp.harga;
        tr.className     = 'hover:bg-slate-50 transition-colors duration-300 ease-out';

        const delBtn = CAN_EDIT
            ? `<button onclick="hapusSparepart(${sp.id}, this)"
                class="w-8 h-8 rounded-lg text-slate-300 hover:text-red-500 hover:bg-red-50 transition-colors inline-flex items-center justify-center"
                title="Hapus"><i class="fas fa-trash-alt"></i></button>`
            : `<span class="text-slate-200 text-xs">—</span>`;

        const qtyCtrl = CAN_EDIT
            ? `<div class="inline-flex items-center bg-slate-50 border border-slate-200 rounded-lg">
                <button onclick="ubahQty(${sp.id},-1,this)" class="px-2 py-1 text-slate-400 hover:text-brand transition-colors">−</button>
                <span class="font-bold w-8 text-center text-slate-700 qty-val">${sp.qty}</span>
                <button onclick="ubahQty(${sp.id},1,this)" class="px-2 py-1 text-slate-400 hover:text-brand transition-colors">+</button>
               </div>`
            : `<span class="font-bold text-slate-700 qty-val">${sp.qty}</span>`;

        tr.innerHTML = `
            <td class="px-6 py-4 font-bold text-slate-700">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-brand flex-shrink-0"></div>
                    <div>
                        <span>${escHtml(sp.nama)}</span>
                        ${sp.keterangan ? `<p class="text-slate-400 font-normal text-xs mt-0.5">${escHtml(sp.keterangan)}</p>` : ''}
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 text-center">${qtyCtrl}</td>
            <td class="px-6 py-4 text-right text-slate-500 font-medium">${rupiah(sp.harga)}</td>
            <td class="px-6 py-4 text-right font-bold text-slate-800 subtotal-val">${rupiah(sp.harga * sp.qty)}</td>
            ${CAN_EDIT ? `<td class="px-6 py-4 text-center">${delBtn}</td>` : ''}`;

        return tr;
    }

    function showEmpty() {
        document.getElementById('sparepartTbody').innerHTML = `
            <tr id="sparepartEmpty">
                <td colspan="${CAN_EDIT ? 5 : 4}" class="px-6 py-8 text-center text-slate-400">
                    <i class="fas fa-box-open text-3xl mb-2 block text-slate-200"></i>
                    <span class="text-sm font-medium">Belum ada sparepart ditambahkan</span>
                </td>
            </tr>`;
    }

    /* ── Load sparepart dari server ──────────────────── */
    async function loadSparepart() {
        try {
            const res   = await fetch(`/admin-cabang/reservasi/${RESERVASI_ID}/sparepart`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data  = await res.json();
            const items = Array.isArray(data) ? data : (data.data ?? []);
            const tbody = document.getElementById('sparepartTbody');
            tbody.innerHTML = '';
            items.length === 0 ? showEmpty() : items.forEach(sp => tbody.appendChild(renderRow(sp)));
            recalcTotals();
        } catch (err) {
            console.error('Load sparepart error:', err);
            document.getElementById('sparepartTbody').innerHTML =
                `<tr><td colspan="${CAN_EDIT ? 5 : 4}" class="px-6 py-4 text-center text-red-400 text-sm">
                    <i class="fas fa-exclamation-circle mr-1"></i> Gagal memuat data sparepart
                </td></tr>`;
        }
    }

    /* ── Hapus sparepart ─────────────────────────────── */
    window.hapusSparepart = async function (sparepartId, btn) {
        if (!confirm('Hapus sparepart ini dari daftar?')) return;
        btn.disabled  = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i>';
        try {
            const res = await fetch(`/admin-cabang/reservasi/${RESERVASI_ID}/sparepart/${sparepartId}`, {
                method : 'DELETE',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || res.statusText);
            const row = document.querySelector(`tr[data-id="${sparepartId}"]`);
            if (row) {
                row.style.transition = 'opacity .25s, transform .25s';
                row.style.opacity    = '0';
                row.style.transform  = 'translateX(12px)';
                setTimeout(() => {
                    row.remove();
                    if (!document.querySelectorAll('#sparepartTbody tr[data-id]').length) showEmpty();
                    recalcTotals();
                }, 260);
            }
            showToast('Sparepart dihapus', 'Berhasil dihapus dari daftar.');
        } catch (err) {
            btn.disabled  = false;
            btn.innerHTML = '<i class="fas fa-trash-alt"></i>';
            showToast('Gagal', err.message || 'Tidak dapat menghapus sparepart.', 'error');
            console.error('Delete sparepart error:', err);
        }
    };

    /* ── Update qty ──────────────────────────────────── */
    window.ubahQty = async function (sparepartId, delta, btn) {
        const row   = document.querySelector(`tr[data-id="${sparepartId}"]`);
        if (!row) return;
        const qtyEl = row.querySelector('.qty-val');
        const subEl = row.querySelector('.subtotal-val');
        const harga = parseInt(row.dataset.harga ?? 0);
        let   qty   = Math.max(1, parseInt(row.dataset.qty ?? 1) + delta);

        row.dataset.qty    = qty;
        qtyEl.textContent  = qty;
        subEl.textContent  = rupiah(harga * qty);
        recalcTotals();

        try {
            const res = await fetch(`/admin-cabang/reservasi/${RESERVASI_ID}/sparepart/${sparepartId}`, {
                method : 'PATCH',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
                body   : JSON.stringify({ qty })
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || res.statusText);
        } catch (err) { 
            console.error('Update qty error:', err);
            showToast('Gagal', 'Tidak dapat mengupdate kuantitas.', 'error');
        }
    };

    /* ── Ubah status reservasi ───────────────────────── */
    window.ubahStatus = async function (newStatus) {
        if (!confirm(`Ubah status menjadi "${newStatus}"?`)) return;
        try {
            const res = await fetch(`/admin-cabang/reservasi/${RESERVASI_ID}/update-status`, {
                method : 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body   : JSON.stringify({ status: newStatus })
            });
            const data = await res.json();
            if (data.success) {
                showToast('Status diperbarui', 'Halaman akan dimuat ulang...');
                setTimeout(() => window.location.reload(), 1200);
            } else {
                showToast('Gagal', data.message ?? 'Coba lagi.', 'error');
            }
        } catch (err) {
            showToast('Error', 'Tidak dapat mengubah status.', 'error');
        }
    };

    /* ── Modal Tambah Part ───────────────────────────── */
    let selectedSparepart = null;
    let searchDebounce    = null;

    /* ── Buka modal tambah part ─────────────────────── */
    window.bukaModalTambahPart = function () {
        const modal = document.getElementById('modalTambahPart');
        if (!modal) return;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            const inp = document.getElementById('spSearch');
            if (inp) inp.focus();
        }, 100);
    };

    window.resetPilihan = function () {
        selectedSparepart = null;
        document.getElementById('spSearch').value               = '';
        document.getElementById('spHarga').value                = '0';
        document.getElementById('spSubtotalPreview').textContent = 'Rp 0';
        document.getElementById('spSelectedInfo').classList.add('hidden');
        document.getElementById('spSearch').focus();
    };

    window.closeTambahPart = function () {
        document.getElementById('modalTambahPart').classList.add('hidden');
        document.body.style.overflow = '';
        selectedSparepart = null;
        ['spSearch','spKeterangan'].forEach(id => document.getElementById(id).value = '');
        document.getElementById('spHarga').value                = '0';
        document.getElementById('spQty').value                  = '1';
        document.getElementById('spSubtotalPreview').textContent = 'Rp 0';
        document.getElementById('spSelectedInfo').classList.add('hidden');
        document.getElementById('spDropdown').classList.add('hidden');
        document.getElementById('spNamaErr').classList.add('hidden');
        document.getElementById('spHargaErr').classList.add('hidden');
    };

    function pilihSparepart(item) {
        selectedSparepart = item;
        document.getElementById('spSelectedNama').textContent  = item.nama;
        document.getElementById('spSelectedHarga').textContent = rupiah(item.harga);
        document.getElementById('spSelectedStok').innerHTML    =
            item.stok > 0
                ? `<span class="text-emerald-600">${item.stok} pcs</span>`
                : `<span class="text-red-500">Habis</span>`;
        document.getElementById('spHarga').value = item.harga;
        document.getElementById('spSearch').value = '';
        document.getElementById('spDropdown').classList.add('hidden');
        document.getElementById('spSelectedInfo').classList.remove('hidden');
        document.getElementById('spNamaErr').classList.add('hidden');
        document.getElementById('spHargaErr').classList.add('hidden');
        updateSubtotalPreview();
        document.getElementById('spQty').focus();
    }

    function updateSubtotalPreview() {
        const h = parseInt(document.getElementById('spHarga').value) || 0;
        const q = parseInt(document.getElementById('spQty').value)   || 1;
        document.getElementById('spSubtotalPreview').textContent = rupiah(h * q);
    }

    // Store items untuk event delegation
    let dropdownItems = [];

    function renderDropdown(items) {
        const list = document.getElementById('spDropdownList');
        const dd   = document.getElementById('spDropdown');
        if (!items?.length) {
            list.innerHTML = `<div class="px-4 py-5 text-center text-slate-400 text-sm">
                <i class="fas fa-search mb-1 block text-slate-200 text-lg"></i> Sparepart tidak ditemukan</div>`;
            dropdownItems = [];
        } else {
            dropdownItems = items;
            list.innerHTML = items.map((item, idx) => `
                <button type="button" data-item-idx="${idx}"
                    class="sp-dropdown-item w-full text-left px-4 py-3 hover:bg-orange-50 transition-colors border-b border-slate-50 last:border-0 flex items-center justify-between gap-3 group">
                    <div class="min-w-0">
                        <p class="font-bold text-slate-800 text-sm truncate group-hover:text-brand">${escHtml(item.nama)}</p>
                        <p class="text-xs text-slate-400 mt-0.5">${rupiah(item.harga)} &nbsp;·&nbsp;
                            Stok: <span class="${item.stok > 0 ? 'text-emerald-600 font-bold' : 'text-red-500 font-bold'}">
                                ${item.stok > 0 ? item.stok + ' pcs' : 'Habis'}</span>
                        </p>
                    </div>
                    <i class="fas fa-chevron-right text-slate-200 group-hover:text-brand text-xs flex-shrink-0"></i>
                </button>`).join('');
        }
        dd.classList.remove('hidden');
    }

    async function searchSparepart(keyword) {
        const dd   = document.getElementById('spDropdown');
        const list = document.getElementById('spDropdownList');
        if (keyword.length < 1) { dd.classList.add('hidden'); return; }

        list.innerHTML = `<div class="px-4 py-4 text-center text-slate-400 text-sm">
            <i class="fas fa-spinner fa-spin mr-1"></i> Mencari...</div>`;
        dd.classList.remove('hidden');

        try {
            const res  = await fetch(`/admin-cabang/sparepart/search?search=${encodeURIComponent(keyword)}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error();
            renderDropdown(await res.json());
        } catch {
            list.innerHTML = `<div class="px-4 py-4 text-center text-red-400 text-sm">
                <i class="fas fa-exclamation-circle mr-1"></i> Gagal memuat sparepart</div>`;
        }
    }

    window.simpanSparepart = async function () {
        if (!selectedSparepart) {
            document.getElementById('spNamaErr').classList.remove('hidden');
            document.getElementById('spSearch').focus();
            return;
        }
        document.getElementById('spNamaErr').classList.add('hidden');

        const harga      = parseInt(document.getElementById('spHarga').value) || 0;
        const qty        = parseInt(document.getElementById('spQty').value)   || 1;
        const keterangan = document.getElementById('spKeterangan').value.trim();

        if (harga <= 0) {
            document.getElementById('spHargaErr').classList.remove('hidden');
            return;
        }
        document.getElementById('spHargaErr').classList.add('hidden');

        const btn  = document.getElementById('btnSimpanPart');
        const icon = document.getElementById('btnSimpanIcon');
        const text = document.getElementById('btnSimpanText');
        btn.disabled = true; icon.className = 'fas fa-spinner fa-spin'; text.textContent = 'Menyimpan...';

        try {
            const res = await fetch(`/admin-cabang/reservasi/${RESERVASI_ID}/sparepart`, {
                method : 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ sparepart_id: selectedSparepart.id, qty, keterangan })
            });
            
            const data = await res.json();
            
            if (!res.ok) {
                const errorMsg = data.message || data.errors?.sparepart_id?.[0] || 'Tidak dapat menyimpan sparepart.';
                throw new Error(errorMsg);
            }

            document.getElementById('sparepartEmpty')?.remove();
            const tbody = document.getElementById('sparepartTbody');
            const row   = renderRow(data.data ?? data);
            row.style.cssText = 'opacity:0;transform:translateY(-6px)';
            tbody.appendChild(row);
            requestAnimationFrame(() => {
                row.style.transition = 'opacity .3s,transform .3s';
                row.style.opacity    = '1';
                row.style.transform  = 'translateY(0)';
            });
            recalcTotals();
            closeTambahPart();
            showToast('Sparepart ditambahkan', `${selectedSparepart.nama} berhasil ditambahkan.`);
        } catch (err) {
            showToast('Gagal', err.message || 'Tidak dapat menyimpan sparepart.', 'error');
            console.error('Sparepart save error:', err);
        } finally {
            btn.disabled = false; icon.className = 'fas fa-plus'; text.textContent = 'Tambahkan';
        }
    };

    /* ── Toast ───────────────────────────────────────── */
    function showToast(title, msg, type = 'success') {
        const border  = type === 'error' ? 'border-red-500'     : 'border-emerald-500';
        const bgIcon  = type === 'error' ? 'bg-red-500/20'      : 'bg-emerald-500/20';
        const txtIcon = type === 'error' ? 'text-red-400'       : 'text-emerald-400';
        const icon    = type === 'error' ? 'fa-exclamation-circle' : 'fa-check';
        const t = document.createElement('div');
        t.className = `fixed top-4 right-4 z-50 bg-slate-800 text-white p-4 rounded-xl shadow-2xl flex items-start gap-4 transform transition-all duration-500 ease-out translate-x-10 opacity-0 border-l-4 ${border}`;
        t.innerHTML = `
            <div class="w-8 h-8 rounded-full ${bgIcon} ${txtIcon} flex items-center justify-center flex-shrink-0">
                <i class="fas ${icon}"></i>
            </div>
            <div>
                <h4 class="font-bold text-sm text-white">${title}</h4>
                <p class="text-xs text-slate-300 mt-1">${msg}</p>
            </div>`;
        document.body.appendChild(t);
        requestAnimationFrame(() => t.classList.remove('translate-x-10','opacity-0'));
        setTimeout(() => {
            t.classList.add('translate-x-10','opacity-0');
            setTimeout(() => t.remove(), 500);
        }, 3000);
    }

    /* ── Events ──────────────────────────────────────── */
    document.getElementById('spSearch')?.addEventListener('input', function () {
        clearTimeout(searchDebounce);
        if (selectedSparepart) return;
        searchDebounce = setTimeout(() => searchSparepart(this.value.trim()), 300);
    });

    // Event delegation untuk dropdown item selection
    document.getElementById('spDropdownList')?.addEventListener('click', function (e) {
        const btn = e.target.closest('.sp-dropdown-item');
        if (!btn) return;
        e.preventDefault();
        e.stopPropagation();
        const idx = btn.dataset.itemIdx;
        if (dropdownItems[idx]) {
            pilihSparepart(dropdownItems[idx]);
        }
    });

    document.addEventListener('click', function (e) {
        const dd = document.getElementById('spDropdown');
        const s  = document.getElementById('spSearch');
        if (dd && !dd.contains(e.target) && e.target !== s) dd.classList.add('hidden');
    });

    document.getElementById('spQty')?.addEventListener('input', updateSubtotalPreview);

    /* ── Init ────────────────────────────────────────── */
    document.addEventListener('DOMContentLoaded', loadSparepart);

    // Tutup modal saat klik di luar / tekan ESC
    document.getElementById('modalTambahPart')?.addEventListener('click', function (e) {
        if (e.target === this) closeTambahPart();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeTambahPart();
    });

})();
</script>
@endsection