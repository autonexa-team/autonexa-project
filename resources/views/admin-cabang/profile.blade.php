@extends('layout.admin-cabang')

@section('content')

@php
    $user = auth()->user();
    $bengkel = $user->bengkel;

    $hariAktif = $bengkel && $bengkel->operasional
        ? $bengkel->operasional
            ->where('is_buka', true)
            ->pluck('hari')
            ->toArray()
        : [];

    $slotReservasi = collect();

    if ($bengkel) {
        $slotReservasi = $bengkel->slotReservasi
            ->mapWithKeys(function ($slot) {
                return [
                    substr($slot->jam_mulai, 0, 5) => $slot
                ];
            });
    }
@endphp

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
</style>

<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4 animate-fade-slide-up stagger-1">
    <div>
        <h2 class="text-3xl font-bold text-slate-800 tracking-tight flex items-center gap-3">
            Profil Cabang
            <span class="bg-brand/10 text-brand text-xs font-bold px-2.5 py-1 rounded-full border border-brand/20">{{ $bengkel?->nama ?? 'Belum Ada Bengkel' }}</span>
        </h2>
        <p class="text-slate-500 mt-2 text-sm font-medium">Kelola jam operasional, foto profil bengkel, dan pantau informasi yang diberikan oleh pusat.</p>
    </div>
    <div class="flex gap-2">
        <button type="submit" form="formProfilCabang" class="bg-brand hover:bg-brand-dark text-white px-6 py-2.5 rounded-xl shadow-md shadow-brand/20 transition-all font-bold text-sm flex items-center gap-2">
            <i class="fas fa-save"></i> Simpan Perubahan
        </button>
    </div>
</div>

<form id="formProfilCabang" action="{{ route('admin-cabang.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- KOLOM KIRI (Read-Only Info dari Pusat) -->
        <div class="lg:col-span-1 space-y-8 animate-fade-slide-up stagger-2">
            
            <!-- Card: Informasi Dasar (Read-Only) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-slate-50 rounded-bl-full opacity-50 z-0"></div>
                <div class="p-6 relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-lg shadow-sm border border-slate-200">
                            <i class="fas fa-store"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">Informasi Pusat</h3>
                            <p class="text-xs text-slate-500 font-medium">Data paten dari Admin Pusat</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Bengkel</label>
                            <input type="text" readonly value="{{ $bengkel?->nama ?? '-' }}" class="w-full bg-slate-50 border border-slate-200 text-slate-600 text-sm rounded-xl px-4 py-2.5 outline-none font-semibold cursor-not-allowed shadow-inner">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                            <textarea readonly class="w-full bg-slate-50 border border-slate-200 text-slate-600 text-sm rounded-xl px-4 py-2.5 outline-none font-semibold cursor-not-allowed resize-none shadow-inner" rows="3">{{ $bengkel?->alamat ?? '-' }}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Latitude</label>
                                <input type="text" readonly value="{{ $bengkel?->latitude ?? '-' }}" class="w-full bg-slate-50 border border-slate-200 text-slate-600 text-sm rounded-xl px-4 py-2.5 outline-none font-semibold cursor-not-allowed shadow-inner">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Longitude</label>
                                <input type="text" readonly value="{{ $bengkel?->longitude ?? '-' }}" class="w-full bg-slate-50 border border-slate-200 text-slate-600 text-sm rounded-xl px-4 py-2.5 outline-none font-semibold cursor-not-allowed shadow-inner">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Informasi Admin Cabang -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
                <div class="p-6 relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shadow-sm border border-blue-100">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">
                                Admin Cabang
                            </h3>

                            <p class="text-xs text-slate-500 font-medium">
                                Informasi pengelola bengkel
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-2xl overflow-hidden shadow-sm border border-slate-200">
                            <img
                                src="{{ $user->foto_profil
                                    ? asset('assets/profile/' . $user->foto_profil)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=ff6a00&color=fff' }}"
                                alt="Foto Profil"
                                class="w-full h-full object-cover"
                            >
                        </div>

                        <div>
                            <h4 class="font-bold text-slate-800 text-lg">
                                {{ $user->name }}
                            </h4>
                            <p class="text-sm text-slate-500">
                                {{ $user->email }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4">

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">
                                Nomor Telepon
                            </label>

                            <input
                                type="text"
                                readonly
                                value="{{ $user->phone ?? '-' }}"
                                class="w-full bg-slate-50 border border-slate-200 text-slate-600 text-sm rounded-xl px-4 py-2.5 outline-none font-semibold cursor-not-allowed shadow-inner"
                            >
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">
                                Role
                            </label>

                            <input
                                type="text"
                                readonly
                                value="Admin Cabang"
                                class="w-full bg-slate-50 border border-slate-200 text-slate-600 text-sm rounded-xl px-4 py-2.5 outline-none font-semibold cursor-not-allowed shadow-inner"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Layanan Tersedia (Read-Only) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
                <div class="p-6 relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shadow-sm border border-emerald-100">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">Layanan Aktif</h3>
                            <p class="text-xs text-slate-500 font-medium">Diatur pada Manajemen Layanan</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @if($bengkel?->layanan && count($bengkel->layanan) > 0)
                            @foreach($bengkel->layanan as $layanan)
                                <span class="bg-slate-50 text-slate-600 text-xs font-bold px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm">{{ $layanan->nama }}</span>
                            @endforeach
                        @else
                            <span class="text-slate-400 text-sm font-medium">Belum ada layanan</span>
                        @endif
                    </div>
                    
                    <a href="{{ route('admin-cabang.layanan') }}" class="block text-center text-brand text-sm font-bold mt-6 hover:underline transition-all">Kelola Layanan Cabang &rarr;</a>
                </div>
            </div>
            
        </div>

        <!-- KOLOM KANAN (Editable Settings) -->
        <div class="lg:col-span-2 space-y-8 animate-fade-slide-up stagger-3">
            
            <!-- Card: Pengaturan Operasional -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                        <i class="far fa-clock text-brand"></i> Jam Operasional Cabang
                    </h3>
                    <p class="text-sm text-slate-500 mt-1">Atur jam buka dan tutup bengkel agar pelanggan dapat memesan reservasi di waktu yang tepat.</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Jam Buka -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Jam Buka <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="far fa-sun text-amber-500"></i>
                                </div>
                                <input type="time" name="jam_buka" value="{{ $bengkel?->jam_buka ?? '08:00' }}" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand block pl-10 p-3 outline-none font-bold transition-all shadow-sm" required>
                            </div>
                        </div>
                        
                        <!-- Jam Tutup -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Jam Tutup <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="far fa-moon text-indigo-500"></i>
                                </div>
                                <input type="time" name="jam_tutup" value="{{ $bengkel?->jam_tutup ?? '17:00' }}" class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand block pl-10 p-3 outline-none font-bold transition-all shadow-sm" required>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-slate-100">
                        <label class="block text-sm font-bold text-slate-700 mb-4">Hari Operasional Aktif</label>
                        @php
                            $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                            $hariAktif = $bengkel?->hari_operasional ?? ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        @endphp
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach($hariList as $hari)
                                @php
                                    $isActive = in_array($hari, (array)$hariAktif);
                                    $isMinggu = $hari === 'Minggu';
                                @endphp
                                <label class="flex items-center justify-between p-3 border {{ $isActive && !$isMinggu ? 'border-brand bg-brand/5' : 'border-slate-200 bg-white' }} {{ $isMinggu ? 'opacity-60' : '' }} rounded-xl cursor-pointer {{ !$isMinggu ? 'hover:bg-brand/10' : 'hover:bg-slate-50' }} transition-colors shadow-sm group">
                                    <span class="text-sm font-bold {{ $isActive && !$isMinggu ? 'text-slate-800 group-hover:text-brand' : 'text-slate-500' }} transition-colors">{{ $hari }}{{ $isMinggu ? ' (Tutup)' : '' }}</span>
                                    <input type="checkbox" name="hari_operasional[]" value="{{ $hari }}" {{ $isActive && !$isMinggu ? 'checked' : '' }} {{ $isMinggu ? 'disabled' : '' }} class="w-4 h-4 text-brand bg-gray-100 border-gray-300 rounded focus:ring-brand accent-brand cursor-pointer">
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Pengaturan Slot Reservasi -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                        <i class="fas fa-users-cog text-brand"></i> Pengaturan Kuota Slot Reservasi
                    </h3>
                    <p class="text-sm text-slate-500 mt-1">Atur maksimal jumlah kendaraan yang dapat dilayani untuk setiap jamnya.</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Example Slots -->
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">08:00 - 09:00</label>
                            <div class="relative">
                                <input type="number" name="slot[08:00]" min="0" value="{{ $slotReservasi['08:00']->kuota ?? 5 }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand p-2.5 pl-4 outline-none font-bold transition-all shadow-sm">
                                <span class="absolute right-3 top-2.5 text-xs font-bold text-slate-400 pointer-events-none">Slot</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">09:00 - 10:00</label>
                            <div class="relative">
                                <input type="number" name="slot[09:00]" min="0" value="{{ $slotReservasi['09:00']->kuota ?? 5 }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand p-2.5 pl-4 outline-none font-bold transition-all shadow-sm">
                                <span class="absolute right-3 top-2.5 text-xs font-bold text-slate-400 pointer-events-none">Slot</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">10:00 - 11:00</label>
                            <div class="relative">
                                <input type="number" name="slot[10:00]" min="0" value="{{ $slotReservasi['10:00']->kuota ?? 5 }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand p-2.5 pl-4 outline-none font-bold transition-all shadow-sm">
                                <span class="absolute right-3 top-2.5 text-xs font-bold text-slate-400 pointer-events-none">Slot</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">11:00 - 12:00</label>
                            <div class="relative">
                                <input type="number" name="slot[11:00]" min="0" value="{{ $slotReservasi['11:00']->kuota ?? 5 }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand p-2.5 pl-4 outline-none font-bold transition-all shadow-sm">
                                <span class="absolute right-3 top-2.5 text-xs font-bold text-slate-400 pointer-events-none">Slot</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">13:00 - 14:00</label>
                            <div class="relative">
                                <input type="number" name="slot[13:00]" min="0" value="{{ $slotReservasi['13:00']->kuota ?? 5 }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand p-2.5 pl-4 outline-none font-bold transition-all shadow-sm">
                                <span class="absolute right-3 top-2.5 text-xs font-bold text-slate-400 pointer-events-none">Slot</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">14:00 - 15:00</label>
                            <div class="relative">
                                <input type="number" name="slot[14:00]" min="0" value="{{ $slotReservasi['14:00']->kuota ?? 5 }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand p-2.5 pl-4 outline-none font-bold transition-all shadow-sm">
                                <span class="absolute right-3 top-2.5 text-xs font-bold text-slate-400 pointer-events-none">Slot</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">15:00 - 16:00</label>
                            <div class="relative">
                                <input type="number" name="slot[15:00]" min="0" value="{{ $slotReservasi['15:00']->kuota ?? 5 }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand p-2.5 pl-4 outline-none font-bold transition-all shadow-sm">
                                <span class="absolute right-3 top-2.5 text-xs font-bold text-slate-400 pointer-events-none">Slot</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">16:00 - 17:00</label>
                            <div class="relative">
                                <input type="number" name="slot[16:00]" min="0" value="{{ $slotReservasi['16:00']->kuota ?? 3 }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand p-2.5 pl-4 outline-none font-bold transition-all shadow-sm">
                                <span class="absolute right-3 top-2.5 text-xs font-bold text-slate-400 pointer-events-none">Slot</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 p-4 bg-blue-50 rounded-xl border border-blue-100 flex gap-3 items-start shadow-sm">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5 text-lg"></i>
                        <p class="text-xs text-slate-600 font-medium leading-relaxed">
                            Jika Anda mengatur slot menjadi <strong class="text-slate-800">0</strong>, maka pada rentang jam tersebut pelanggan tidak akan bisa melakukan reservasi (diblokir). Berguna untuk waktu istirahat teknisi (misal jam 12:00-13:00) atau keperluan bengkel lainnya.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card: Foto Bengkel -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                        <i class="fas fa-camera text-brand"></i> Foto Profil Bengkel
                    </h3>
                    <p class="text-sm text-slate-500 mt-1">Unggah foto terbaik dari tampak depan cabang Anda untuk ditampilkan di aplikasi pelanggan.</p>
                </div>
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-6 items-start">
                        <!-- Current Photo / Preview -->
                        <div class="w-full md:w-1/3 aspect-video md:aspect-square bg-slate-100 rounded-xl overflow-hidden border-2 border-dashed border-slate-300 flex items-center justify-center relative group shadow-inner">
                            <img src="{{ $bengkel?->foto ? asset('assets/' . $bengkel->foto) : 'https://via.placeholder.com/600x400?text=Foto+Bengkel' }}" alt="Bengkel Cabang" class="w-full h-full object-cover z-0">
                            
                            <!-- Overlay Hover -->
                            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center z-10">
                                <button type="button" class="bg-white text-red-500 hover:bg-red-50 px-4 py-2 rounded-lg text-sm font-bold shadow-md transition-colors flex items-center">
                                    <i class="fas fa-trash-alt mr-2"></i> Hapus
                                </button>
                            </div>
                        </div>

                        <!-- Upload Control -->
                        <div class="flex-1 w-full">
                            <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100 hover:border-brand/50 transition-all group shadow-sm">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <div class="w-12 h-12 rounded-full bg-white shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform border border-slate-100">
                                        <i class="fas fa-cloud-upload-alt text-xl text-brand"></i>
                                    </div>
                                    <p class="mb-1 text-sm text-slate-500 font-bold"><span class="text-brand">Klik untuk upload</span> atau drag and drop</p>
                                    <p class="text-xs text-slate-400 font-medium">SVG, PNG, JPG atau GIF (Maks. 2MB)</p>
                                </div>
                                <input type="file" name="foto" class="hidden" accept="image/*"/>
                            </label>
                            
                            <div class="mt-4 p-4 bg-orange-50 rounded-xl border border-orange-100 flex gap-3 items-start shadow-sm">
                                <i class="fas fa-lightbulb text-amber-500 mt-0.5 text-lg"></i>
                                <p class="text-xs text-slate-600 font-medium leading-relaxed">
                                    Gunakan foto landscape beresolusi tinggi dengan pencahayaan yang terang. Hindari foto yang blur atau yang menampilkan terlalu banyak objek tidak relevan karena ini akan menjadi impresi pertama pelanggan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

@endsection
