{{-- views/pelanggan/reservasi.blade.php --}}
@extends('layout.app')
@section('title', 'Booking Service')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/reservasi.css') }}">
@endpush

@section('content')

{{-- ===== HERO ===== --}}
<div class="reservasi-hero">
    <div class="hero-text">
        <div class="hero-eyebrow">Booking Service</div>
        <h1 class="hero-title">Reservasi <span>Sekarang!</span></h1>
        <p class="hero-subtitle">Pilih bengkel terpercaya dan tentukan jadwal servis kendaraanmu</p>
    </div>
    <a href="{{ route('pelanggan.riwayat') }}" class="btn-riwayat">
        <span class="btn-riwayat-dot"></span>
        Reservasi Saya
        @if(isset($totalReservasi) && $totalReservasi > 0)
            <span class="btn-riwayat-badge">{{ $totalReservasi }}</span>
        @endif
    </a>
</div>

<div class="reservasi-wrapper">
    <div class="reservasi-left">

        {{-- ===== BLOK 1: PILIH BENGKEL ===== --}}
        <div class="reservasi-block" id="blok-bengkel">
            <div class="block-header">
                <span class="block-number">1</span>
                <h5 class="block-title">Pilih Bengkel</h5>
            </div>
            <div class="block-body">

                <div class="search-row">
                    <div class="search-input-wrap">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" id="searchBengkel" class="search-input"
                               placeholder="Cari bengkel...">
                    </div>
                    <button type="button" class="btn-terdekat" id="btnTerdekat">
                        <i class="bi bi-geo-alt-fill"></i> Bengkel Terdekat
                    </button>
                </div>

                {{-- Preview bengkel terpilih --}}
                <div class="bengkel-preview" id="bengkelPreview" style="display:none;">
                    <img id="previewFoto" src="" alt="" class="preview-foto">
                    <div class="preview-info">
                        <div class="preview-nama" id="previewNama"></div>
                        <div class="preview-alamat" id="previewAlamat"></div>
                        <div class="preview-rating" id="previewRating"></div>
                        {{-- Slot info --}}
                        <div class="preview-slot" id="previewSlot"></div>
                    </div>
                    <button type="button" class="btn-ganti" id="btnGanti">
                        <i class="bi bi-arrow-repeat"></i> Ganti
                    </button>
                </div>

                {{-- Daftar Bengkel --}}
                <div class="bengkel-list" id="bengkelList" style="display:none;">
                    @foreach($bengkels as $bengkel)
                    @php
                        $kapasitas   = $bengkel->kapasitas ?? 8;
                        $terpakai    = $reservasiHariIni[$bengkel->id] ?? 0;
                        $sisaSlot    = $kapasitas - $terpakai;
                        $penuh       = $sisaSlot <= 0;
                    @endphp
                    <div class="bengkel-card {{ $penuh ? 'bengkel-penuh' : '' }}"
                        data-id="{{ $bengkel->id }}"
                        data-buka="{{ $bengkel->jam_buka }}"
                        data-tutup="{{ $bengkel->jam_tutup }}"                        
                        data-nama="{{ $bengkel->nama }}"
                        data-alamat="{{ $bengkel->alamat }}"
                        data-foto="{{ $bengkel->foto ? asset('storage/'.$bengkel->foto) : asset('img/bengkel-default.jpg') }}"
                        data-rating="{{ $bengkel->reviews_avg_rating ?? 0 }}"
                        data-kapasitas="{{ $kapasitas }}"
                        data-terpakai="{{ $terpakai }}"
                        data-sisa="{{ $sisaSlot }}"
                        data-lat="{{ $bengkel->latitude ?? '' }}"
                        data-lng="{{ $bengkel->longitude ?? '' }}"
                        data-penuh="{{ $penuh ? '1' : '0' }}">
                        <img src="{{ $bengkel->foto ? asset('storage/'.$bengkel->foto) : asset('img/bengkel-default.jpg') }}"
                            class="bengkel-thumb" alt="{{ $bengkel->nama }}">
                        <div class="bengkel-info">
                            <div class="bengkel-nama">{{ $bengkel->nama }}</div>
                            <div class="bengkel-alamat">{{ $bengkel->alamat }}</div>
                            <div class="bengkel-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= ($bengkel->reviews_avg_rating ?? 0) ? '-fill star-on' : ' star-off' }}"></i>
                                @endfor
                                <small class="rating-val">({{ number_format($bengkel->reviews_avg_rating ?? 0, 1) }})</small>
                            </div>
                            {{-- Slot indicator --}}
                            <div class="slot-info {{ $penuh ? 'slot-penuh' : ($sisaSlot <= 2 ? 'slot-sedikit' : 'slot-aman') }}">
                                <i class="bi bi-circle-fill" style="font-size:6px;"></i>
                                @if($penuh)
                                    Penuh hari ini
                                @elseif($sisaSlot <= 2)
                                    Sisa {{ $sisaSlot }} slot — segera booking!
                                @else
                                    Sisa {{ $sisaSlot }} slot tersedia
                                @endif
                            </div>
                        </div>
                        <i class="bi bi-check-circle-fill check-icon"></i>
                    </div>
                    @endforeach
                </div>

                {{-- Placeholder saat belum search --}}
                <div class="bengkel-empty" id="bengkelEmpty">
                    <i class="bi bi-search"></i>
                    <p>Ketik nama bengkel untuk mencari</p>
                </div>                

                <input type="hidden" name="bengkel_id" id="bengkelId">
                @error('bengkel_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>
        </div>

        @auth
        {{-- ===== BLOK 2: INFORMASI KENDARAAN (BARU) ===== --}}
        <div class="reservasi-block" id="blok-kendaraan">
            <div class="block-header">
                <span class="block-number">2</span>
                <h5 class="block-title">Informasi Kendaraan</h5>
            </div>
            <div class="block-body">
                <div class="kendaraan-grid">

                    {{-- Nomor Plat --}}
                    <div class="form-field">
                        <label class="field-label">
                            <i class="bi bi-upc-scan"></i>
                            Nomor Plat
                        </label>

                        <input type="text"
                            name="plat"
                            class="field-input plat-input"
                            placeholder="cth: B 1234 XYZ"
                            value="{{ old('plat') }}"
                            required>

                        @error('plat')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Merk + tipe motor --}}
                    <div class="form-field">
                        <label class="field-label">
                            <i class="bi bi-bicycle"></i>
                            Motor
                        </label>

                        <input type="text"
                            name="kendaraan"
                            class="field-input"
                            placeholder="cth: Honda Beat / Yamaha NMAX"
                            value="{{ old('kendaraan') }}"
                            required>

                        @error('kendaraan')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>
        </div>    

        {{-- ===== BLOK 3: DETAIL RESERVASI ===== --}}
        <div class="reservasi-block" id="blok-form">
            <div class="block-header">
                <span class="block-number">3</span>
                <h5 class="block-title">Detail Reservasi</h5>
            </div>
            <div class="block-body">
                <form action="{{ route('pelanggan.booking.store') }}" method="POST"
                      id="formBooking" enctype="multipart/form-data">
                    @csrf
                    {{-- Hidden fields dari blok lain --}}
                    <input type="hidden" name="bengkel_id"    id="bengkelIdForm">
                    <input type="hidden" name="layanan_id"    id="layananIdForm">
                    <input type="hidden" name="waktu"         id="waktuForm">
                    <input type="hidden" name="merk_form"     id="merkForm">
                    <input type="hidden" name="tipe_form"     id="tipeForm">
                    <input type="hidden" name="plat_form"     id="platForm">
                    <input type="hidden" name="warna_form"    id="warnaForm">
                    <input type="hidden" name="total_biaya"   id="totalBiayaForm" value="0">
                    {{-- Mirror field dari Blok 2 (di luar form) agar ikut tersubmit --}}
                    <input type="hidden" name="plat"          id="platHidden">
                    <input type="hidden" name="kendaraan"     id="kendaraanHidden">

                    <div class="form-row-2">
                        <div class="form-field">
                            <label class="field-label">
                                <i class="bi bi-calendar3"></i> Tanggal Service
                            </label>
                            <input type="date" name="tanggal" class="field-input"
                                   id="tanggalInput"
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('tanggal') }}" required>
                            @error('tanggal')<div class="field-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-field">
                            <label class="field-label">
                                <i class="bi bi-clock"></i> Pilih Waktu
                            </label>
                            {{-- Slot waktu sebagai tombol grid --}}
                            <div class="jam-grid" id="jamGrid">
                                @foreach(['08:00','09:00','10:00','11:00','13:00','14:00','15:00','16:00'] as $jam)
                                <button type="button"
                                        class="jam-btn"
                                        data-jam="{{ $jam }}"
                                        data-slot-used="0">
                                    {{ $jam }}
                                    <span class="jam-sub"></span>
                                </button>
                                @endforeach
                            </div>
                            
                            @error('waktu')<div class="field-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-field">
                        <label class="field-label">
                            <i class="bi bi-chat-left-text"></i> Keluhan Kendaraan
                        </label>
                        <textarea name="keluhan" class="field-input field-textarea"
                                  rows="3"
                                  placeholder="Ceritakan keluhan kendaraan Anda secara detail..."
                                  required>{{ old('keluhan') }}</textarea>
                        @error('keluhan')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    {{-- ===== PILIH LAYANAN UTAMA (1 saja) ===== --}}
                    {{-- ===== PILIH LAYANAN UTAMA ===== --}}
                    <div class="form-field" style="margin-bottom:8px;">
                        <label class="field-label">
                            <i class="bi bi-wrench-adjustable-circle"></i>
                            Pilih Layanan Utama
                            <span class="label-optional">· pilih satu</span>
                        </label>

                        {{-- Dropdown trigger --}}
                        <div class="layanan-dropdown-trigger" id="layananTrigger"
                            onclick="toggleLayananDropdown()"
                            style="display:flex; align-items:center; justify-content:space-between;
                                    border:1.5px solid #e2e8f0; border-radius:10px; padding:12px 16px;
                                    cursor:pointer; background:#fff; user-select:none;">
                            <span id="layananTriggerText" style="color:#94a3b8; font-size:.9rem;">
                                — Pilih bengkel terlebih dahulu —
                            </span>
                            <i class="bi bi-chevron-down" id="layananChevron" style="transition:.2s;"></i>
                        </div>

                        {{-- Dropdown list --}}
                        <div id="layananDropdownList" style="
                            display:none; border:1.5px solid #e2e8f0; border-top:none;
                            border-radius:0 0 10px 10px; background:#fff;
                            max-height:320px; overflow-y:auto; box-shadow:0 8px 24px rgba(0,0,0,.08);">
                            <div id="layananOptions">
                                <div style="padding:16px; text-align:center; color:#94a3b8; font-size:.85rem;">
                                    Pilih bengkel terlebih dahulu
                                </div>
                            </div>
                        </div>

                        {{-- Info layanan terpilih --}}
                        <div id="layananSelectedInfo" style="display:none; margin-top:.75rem;
                            background:#fff7ed; border:1.5px solid #fed7aa; border-radius:10px; padding:12px 16px;">
                            <div style="font-weight:700; color:#c2410c; font-size:.9rem;" id="layananSelectedNama"></div>
                            <div style="font-size:.78rem; color:#9a3412; margin-top:.25rem;" id="layananSelectedMeta"></div>
                        </div>

                        @error('layanan_id')<div class="field-error">{{ $message }}</div>@enderror
                    </div>

                    {{-- Estimasi muncul setelah layanan dipilih --}}
                    <div class="estimasi-box" id="estimasiBox" style="display:none;">
                        <div class="estimasi-title">
                            <i class="bi bi-calculator"></i> Estimasi Layanan Terpilih
                        </div>
                        <div class="estimasi-row">
                            <span>Layanan</span>
                            <span id="estNama">—</span>
                        </div>
                        <div class="estimasi-row">
                            <span>Estimasi durasi pengerjaan</span>
                            <span id="estDurasi">—</span>
                        </div>
                        <div class="estimasi-row">
                            <span>Biaya layanan dasar</span>
                            <span id="estHarga">—</span>
                        </div>
                        <hr class="estimasi-divider">
                        <div class="estimasi-row estimasi-total">
                            <span>Total estimasi</span>
                            <span id="estTotal">—</span>
                        </div>
                        <p class="estimasi-note">
                            *Harga dapat berubah jika memerlukan penggantian sparepart
                            tambahan saat service berlangsung
                        </p>
                    </div>

                    <button type="submit" class="btn-reservasi" id="btnReservasi">
                        <i class="bi bi-check2-circle"></i>
                        Reservasi Sekarang
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </div>

    @auth
    <div class="reservasi-right">
        {{-- ===== BLOK 4: RINGKASAN ===== --}}
        <div class="reservasi-block" id="blok-ringkasan">
            <div class="block-header">
                <span class="block-number">4</span>
                <h5 class="block-title">Ringkasan Reservasi</h5>
            </div>
            <div class="block-body">
                <div id="ringkasanEmpty" class="ringkasan-empty">
                    <i class="bi bi-clipboard2-pulse"></i>
                    <p>Isi form di atas untuk melihat ringkasan</p>
                </div>
                <div id="ringkasanDetail" style="display:none;">
                    <div class="ringkasan-row">
                        <span class="ringkasan-label"><i class="bi bi-shop"></i> Bengkel</span>
                        <span class="ringkasan-val" id="rBengkel">—</span>
                    </div>
                    <div class="ringkasan-row">
                        <span class="ringkasan-label"><i class="bi bi-car-front"></i> Kendaraan</span>
                        <span class="ringkasan-val" id="rKendaraan">—</span>
                    </div>
                    <div class="ringkasan-row">
                        <span class="ringkasan-label"><i class="bi bi-wrench"></i> Layanan</span>
                        <span class="ringkasan-val" id="rLayanan">—</span>
                    </div>
                    <div class="ringkasan-row">
                        <span class="ringkasan-label"><i class="bi bi-calendar3"></i> Tanggal</span>
                        <span class="ringkasan-val" id="rTanggal">—</span>
                    </div>
                    <div class="ringkasan-row">
                        <span class="ringkasan-label"><i class="bi bi-clock"></i> Waktu</span>
                        <span class="ringkasan-val" id="rWaktu">—</span>
                    </div>
                    <div class="ringkasan-row">
                        <span class="ringkasan-label"><i class="bi bi-info-circle"></i> Status</span>
                        <span class="ringkasan-val">
                            <span class="status-badge status-pending">Menunggu Konfirmasi</span>
                        </span>
                    </div>

                    {{-- Estimasi ringkas --}}
                    <div class="ringkasan-estimasi" id="ringkasanEstimasi" style="display:none;">
                        <div class="re-title">
                            <i class="bi bi-calculator"></i> Estimasi Service
                        </div>
                        <div class="re-row">
                            <span>Durasi</span>
                            <span id="reDurasi">—</span>
                        </div>
                        <div class="re-row">
                            <span>Estimasi biaya</span>
                            <span id="reHarga" class="re-harga">—</span>
                        </div>
                    </div>

                    <p class="ringkasan-note">
                        *Durasi dan biaya bersifat estimasi, dapat berubah sesuai kondisi kendaraan
                    </p>
                </div>

                {{-- Mini Map Leaflet --}}
                <div id="map" class="mini-map"></div>
            </div>
        </div>
    </div>
    @endauth
</div>

@guest
{{-- ===== LOGIN MODAL ===== --}}
<div id="loginModal" class="login-modal">
    <div class="login-modal-overlay"></div>
    <div class="login-modal-content">
        <i class="bi bi-lock-fill login-modal-icon"></i>
        <p class="login-modal-text">Silahkan lakukan login terlebih dahulu untuk melakukan reservasi</p>
        <a href="{{ route('login') }}" class="btn-login-modal">
            <i class="bi bi-box-arrow-in-right"></i>
            Login sekarang!
        </a>
    </div>
</div>
@endguest

@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
{{-- Data untuk booking.js — harus sebelum booking.js dimuat --}}
window.slotData          = @json($slotPerJam ?? []);
window.kapasitasBengkel  = @json($bengkels->pluck('kapasitas', 'id') ?? []);
window.layananPerBengkel = @json($layananPerBengkel ?? []);  {{-- ← pindah ke sini --}}
window.isAuthenticated   = {{ auth()->check() ? 'true' : 'false' }};
window.loginModalElement = document.getElementById('loginModal');
</script>

<script src="{{ asset('js/booking.js') }}"></script>
@endpush