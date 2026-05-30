@extends('layout.admin-cabang')

@section('content')

<!-- CSS Custom Animations -->
<style>
    @keyframes fadeSlideUp {
        0% { opacity: 0; transform: translateY(15px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-slide-up {
        animation: fadeSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }
    .stagger-1 { animation-delay: 50ms; }
    .stagger-2 { animation-delay: 100ms; }
    .stagger-3 { animation-delay: 150ms; }
    .stagger-4 { animation-delay: 200ms; }
</style>

<!-- Main Container -->
<div class="animate-fade-slide-up stagger-1">
    
    <!-- Header & Breadcrumbs -->
    <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <nav class="flex text-sm text-slate-500 font-medium mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ url('/admin-cabang/dashboard') }}" class="hover:text-brand transition-colors">Dashboard</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-[10px] mx-2"></i>
                            <a href="{{ route('admin-cabang.reservasi') }}" class="hover:text-brand transition-colors">Reservasi</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-[10px] mx-2"></i>
                            <span class="text-slate-800 font-bold">Edit Reservasi #{{ $reservasi->id }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Edit Reservasi</h2>
            <p class="text-slate-500 mt-1 text-sm">Update status dan informasi reservasi</p>
        </div>
        <div>
            <a href="{{ route('admin-cabang.reservasi') }}" class="bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4">
            <h3 class="text-red-800 font-bold mb-2">Terjadi Kesalahan:</h3>
            <ul class="text-red-700 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Area -->
    <form action="{{ route('admin-cabang.reservasi.update', $reservasi->id) }}" method="PATCH" class="grid grid-cols-1 lg:grid-cols-3 gap-6 relative items-start">
        @csrf
        @method('PATCH')
        
        <!-- Left Column: Form Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card 1: Info Pelanggan -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden animate-fade-slide-up stagger-2">
                <div class="border-b border-slate-100 px-6 py-4 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Informasi Pelanggan</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                            <input type="text" value="{{ $reservasi->user->name }}" disabled class="bg-slate-100 border border-slate-200 text-slate-700 text-sm rounded-xl block w-full p-3 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nomor HP / WhatsApp</label>
                            <input type="tel" value="{{ $reservasi->user->phone ?? '-' }}" disabled class="bg-slate-100 border border-slate-200 text-slate-700 text-sm rounded-xl block w-full p-3 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                            <input type="email" value="{{ $reservasi->user->email }}" disabled class="bg-slate-100 border border-slate-200 text-slate-700 text-sm rounded-xl block w-full p-3 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Plat Nomor Kendaraan</label>
                            <input type="text" value="{{ $reservasi->plat }}" disabled class="bg-slate-100 border border-slate-200 text-slate-700 text-sm rounded-xl block w-full p-3 outline-none uppercase font-bold">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Info Reservasi -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden animate-fade-slide-up stagger-3">
                <div class="border-b border-slate-100 px-6 py-4 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Informasi Reservasi</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Reservasi</label>
                            <input type="date" name="tanggal" value="{{ $reservasi->tanggal->format('Y-m-d') }}" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white block w-full p-3 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Jam Reservasi</label>
                            <input type="time" name="waktu" value="{{ $reservasi->waktu }}" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white block w-full p-3 outline-none transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Keluhan Pelanggan</label>
                        <textarea name="keluhan" rows="4" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white block w-full p-3 outline-none transition-all resize-none" placeholder="Keluhan atau catatan...">{{ $reservasi->keluhan }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Status & Actions -->
        <div class="lg:col-span-1 space-y-6 lg:sticky lg:top-6 animate-fade-slide-up stagger-4">
            
            <!-- Status Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-4 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-orange-50 text-brand flex items-center justify-center">
                        <i class="fas fa-cog"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Update Status</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Status Reservasi</label>
                        <div class="relative">
                            <select name="status" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white block w-full p-3 outline-none font-medium appearance-none transition-all">
                                <option value="pending" @selected($reservasi->status === 'pending')>Menunggu Konfirmasi</option>
                                <option value="dikonfirmasi" @selected($reservasi->status === 'dikonfirmasi')>Dikonfirmasi</option>
                                <option value="diproses" @selected($reservasi->status === 'diproses')>Sedang Diproses</option>
                                <option value="selesai" @selected($reservasi->status === 'selesai')>Selesai</option>
                                <option value="dibatalkan" @selected($reservasi->status === 'dibatalkan')>Dibatalkan</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Hasil Service</label>
                        <textarea name="hasil_service" rows="4" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white block w-full p-3 outline-none transition-all resize-none" placeholder="Hasil pekerjaan service...">{{ $reservasi->hasil_service }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Total Biaya (Rp)</label>
                        <input type="number" name="total_biaya" value="{{ $reservasi->total_biaya }}" step="0.01" class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-2 focus:ring-brand/20 focus:border-brand focus:bg-white block w-full p-3 outline-none transition-all" placeholder="0">
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="bg-slate-800 rounded-2xl shadow-lg border border-slate-700 overflow-hidden">
                <div class="p-6">
                    <h3 class="font-bold text-white uppercase tracking-wider text-xs flex items-center gap-2 mb-4">
                        <i class="fas fa-info-circle text-slate-400"></i> Info Reservasi
                    </h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center border-b border-slate-700 pb-2">
                            <span class="text-slate-400">ID Reservasi</span>
                            <span class="text-white font-bold">#{{ $reservasi->id }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-slate-700 pb-2">
                            <span class="text-slate-400">Dibuat Pada</span>
                            <span class="text-white font-bold">{{ $reservasi->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">Diubah Pada</span>
                            <span class="text-white font-bold">{{ $reservasi->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-3">
                <button type="submit" class="w-full bg-brand hover:bg-brand-dark text-white py-3.5 rounded-xl font-bold transition-all duration-300 shadow-lg shadow-brand/20 flex items-center justify-center gap-2 active:scale-95">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin-cabang.reservasi-detail', $reservasi->id) }}" class="w-full bg-slate-700 hover:bg-slate-600 text-white py-2.5 rounded-xl font-bold text-sm transition-colors flex items-center justify-center gap-2 text-center">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>

        </div>
    </form>
</div>

@endsection
