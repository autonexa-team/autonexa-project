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
                <div class="bengkel-list" id="bengkelList">
                    @foreach($bengkels as $bengkel)
                    @php
                        $kapasitas   = $bengkel->kapasitas ?? 8;
                        $terpakai    = $reservasiHariIni[$bengkel->id] ?? 0;
                        $sisaSlot    = $kapasitas - $terpakai;
                        $penuh       = $sisaSlot <= 0;
                    @endphp
                    <div class="bengkel-card {{ $penuh ? 'bengkel-penuh' : '' }}"
                        data-id="{{ $bengkel->id }}"
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

                <input type="hidden" name="bengkel_id" id="bengkelId">
                @error('bengkel_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- ===== BLOK 2: INFORMASI KENDARAAN (BARU) ===== --}}
        <div class="reservasi-block" id="blok-kendaraan">
            <div class="block-header">
                <span class="block-number">2</span>
                <h5 class="block-title">Informasi Kendaraan</h5>
            </div>
            <div class="block-body">
                <div class="kendaraan-grid">
                    <div class="form-field">
                        <label class="field-label">
                            <i class="bi bi-tag"></i> Merek Kendaraan
                        </label>
                        <input type="text" name="merk" class="field-input"
                               placeholder="cth: Honda, Yamaha, Toyota"
                               value="{{ old('merk') }}" required>
                        @error('merk')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-field">
                        <label class="field-label">
                            <i class="bi bi-card-text"></i> Tipe / Model
                        </label>
                        <input type="text" name="tipe" class="field-input"
                               placeholder="cth: Beat, NMAX, Brio"
                               value="{{ old('tipe') }}" required>
                        @error('tipe')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-field">
                        <label class="field-label">
                            <i class="bi bi-upc-scan"></i> Nomor Plat
                        </label>
                        <input type="text" name="plat" class="field-input plat-input"
                               placeholder="cth: B 1234 XYZ"
                               value="{{ old('plat') }}" required>
                        @error('plat')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-field">
                        <label class="field-label">
                            <i class="bi bi-palette"></i> Warna Kendaraan
                        </label>
                        <input type="text" name="warna" class="field-input"
                               placeholder="cth: Merah, Hitam, Putih"
                               value="{{ old('warna') }}">
                    </div>
                </div>
                {{-- Foto kendaraan (opsional) --}}
                <div class="form-field" style="margin-bottom:0;">
                    <label class="field-label">
                        <i class="bi bi-camera"></i> Foto Kendaraan
                        <span class="label-optional">· opsional</span>
                    </label>
                    <div class="foto-upload-area" id="fotoUploadArea">
                        <i class="bi bi-cloud-arrow-up"></i>
                        <p>Klik atau drag foto ke sini</p>
                        <small>JPG, PNG · Maks 2MB</small>
                    </div>
                    <input type="file" name="foto_kendaraan" id="fotoKendaraan"
                           accept="image/*" style="display:none">
                    <div id="fotoPreview" style="display:none;">
                        <img id="fotoPreviewImg" src="" class="foto-preview-img" alt="Preview">
                        <button type="button" class="btn-hapus-foto" id="btnHapusFoto">
                            <i class="bi bi-x"></i> Hapus
                        </button>
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
                            <p class="field-helper">
                                <i class="bi bi-info-circle"></i>
                                Slot penuh ditandai abu-abu. Pilih tanggal dulu untuk cek ketersediaan.
                            </p>
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
                    <div class="form-field" style="margin-bottom:8px;">
                        <label class="field-label">
                            <i class="bi bi-wrench-adjustable-circle"></i>
                            Pilih Layanan Utama
                            <span class="label-optional">· pilih satu</span>
                        </label>
                        <div class="layanan-list" id="layananList">
                            @foreach($layanans as $layanan)
                            <div class="layanan-card {{ old('layanan_id') == $layanan->id ? 'selected' : '' }}"
                                data-id="{{ $layanan->id }}"
                                data-nama="{{ $layanan->nama }}"
                                data-durasi="{{ $layanan->durasi }}"
                                data-harga="{{ $layanan->harga }}">
                                <div class="layanan-radio">
                                    <div class="layanan-radio-dot"></div>
                                </div>
                                <div class="layanan-detail">
                                    <div class="layanan-nama">{{ $layanan->nama }}</div>
                                    @if($layanan->deskripsi)
                                        <div class="layanan-desc">{{ $layanan->deskripsi }}</div>
                                    @endif
                                    <div class="layanan-badges">
                                        <span class="badge-durasi">
                                            <i class="bi bi-clock"></i>
                                            ~{{ $layanan->durasi }} menit
                                        </span>
                                        <span class="badge-harga">
                                            <i class="bi bi-cash"></i>
                                            Mulai Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
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
    </div>

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
</div>

@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

{{-- Data slot per jam dari controller --}}
<script>
window.slotData     = @json($slotPerJam ?? []);
window.kapasitasBengkel = @json($bengkels->pluck('kapasitas', 'id') ?? []);
</script>

<script src="{{ asset('js/booking.js') }}"></script>
@endpush