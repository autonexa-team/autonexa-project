{{-- resources/views/admin-pusat/tambah-bengkel.blade.php --}}
@extends('layout.admin')
@section('title', 'Tambah Bengkel')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-bengkel.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')

{{-- ===== PAGE HEADER ===== --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Tambah Bengkel</h1>
        <nav class="breadcrumb-nav">
            <a href="{{ route('admin-pusat.dashboard') }}" class="bc-link">Dashboard</a>
            <i class="bi bi-chevron-right bc-sep"></i>
            <a href="{{ route('admin-pusat.bengkel.index') }}" class="bc-link">Bengkel</a>
            <i class="bi bi-chevron-right bc-sep"></i>
            <span class="bc-current">Tambah Baru</span>
        </nav>
    </div>
    <div class="ph-actions">
        <a href="{{ route('admin-pusat.bengkel.index') }}" class="btn-secondary">
            <i class="bi bi-x-lg"></i> Batal
        </a>
        <button type="submit" form="formTambahBengkel" class="btn-primary">
            <i class="bi bi-floppy"></i> Simpan Bengkel
        </button>
    </div>
</div>

{{-- ===== FORM ===== --}}
<form id="formTambahBengkel"
      action="{{ route('admin-pusat.bengkel.store') }}"
      method="POST"
      enctype="multipart/form-data"
      novalidate>
    @csrf

    <div class="form-two-col">

        {{-- ==================== KOLOM KIRI ==================== --}}
        <div class="form-col">

            {{-- Card 1: Informasi Bengkel --}}
            <div class="fcard">
                <div class="fcard-header">
                    <div class="fcard-icon"><i class="bi bi-shop"></i></div>
                    <div>
                        <div class="fcard-title">Informasi Bengkel</div>
                        <div class="fcard-sub">Data umum bengkel</div>
                    </div>
                </div>

                {{-- Nama Bengkel --}}
                <div class="fgroup">
                    <label class="flabel" for="nama">
                        Nama Bengkel <span class="freq">*</span>
                    </label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        class="finput @error('nama') finput-error @enderror"
                        placeholder="cth: Maju Jaya Motor Cabang Selatan"
                        value="{{ old('nama') }}"
                        required
                    >
                    @error('nama')
                        <p class="ferror">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Admin Cabang --}}
                <div class="fgroup">
                    <label class="flabel" for="admin_id">
                        Admin Cabang <span class="freq">*</span>
                    </label>
                    <select
                        id="admin_id"
                        name="admin_id"
                        class="finput @error('admin_id') finput-error @enderror"
                        required
                    >
                        <option value="">Pilih admin cabang...</option>
                        @foreach($adminCabang ?? [] as $admin)
                            <option value="{{ $admin->id }}"
                                {{ old('admin_id') == $admin->id ? 'selected' : '' }}>
                                {{ $admin->name }} ({{ $admin->email }})
                            </option>
                        @endforeach
                    </select>
                    <p class="fhelper">
                        <i class="bi bi-info-circle"></i>
                        Hanya admin cabang yang belum memiliki bengkel
                    </p>
                    @error('admin_id')
                        <p class="ferror">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="fgroup">
                    <label class="flabel">Status Bengkel</label>
                    <div class="toggle-wrap" id="toggleWrap">
                        <div class="toggle-track on" id="toggleTrack">
                            <div class="toggle-thumb"></div>
                        </div>
                        <div class="toggle-info">
                            <div class="toggle-lbl" id="toggleLabel">Aktif</div>
                            <div class="toggle-sub">Bengkel akan muncul di platform</div>
                        </div>
                    </div>
                    <input type="hidden" name="status" id="statusInput" value="aktif">
                </div>
            </div>

            {{-- Card 2: Lokasi --}}
            <div class="fcard">
                <div class="fcard-header">
                    <div class="fcard-icon"><i class="bi bi-geo-alt"></i></div>
                    <div>
                        <div class="fcard-title">Lokasi Bengkel</div>
                        <div class="fcard-sub">Alamat dan koordinat GPS</div>
                    </div>
                </div>

                {{-- Alamat + Tombol Cari --}}
                <div class="fgroup">
                    <label class="flabel" for="alamat">
                        Alamat Lengkap <span class="freq">*</span>
                    </label>
                    <textarea
                        id="alamat"
                        name="alamat"
                        class="finput @error('alamat') finput-error @enderror"
                        rows="3"
                        placeholder="cth: Jl. Sudirman No. 45, Jakarta Pusat, DKI Jakarta"
                        required
                    >{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="ferror">{{ $message }}</p>
                    @enderror

                    <button type="button" class="btn-geocode" id="btnGeocode">
                        <i class="bi bi-search"></i>
                        <span id="geocodeBtnText">Cari Lokasi</span>
                    </button>

                    {{-- Geo states --}}
                    <div id="geoLoading" class="geo-state geo-loading" style="display:none;">
                        <span class="geo-spinner"></span>
                        Mencari koordinat alamat...
                    </div>
                    <div id="geoSuccess" class="geo-state geo-success" style="display:none;">
                        <i class="bi bi-check-circle-fill"></i>
                        <span id="geoSuccessText">Lokasi ditemukan</span>
                    </div>
                    <div id="geoError" class="geo-state geo-error" style="display:none;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        Alamat tidak ditemukan. Coba perjelas alamat Anda.
                    </div>
                </div>

                {{-- Lat & Lng --}}
                <div class="frow-2">
                    <div class="fgroup">
                        <label class="flabel" for="latitude">Latitude</label>
                        <input
                            type="text"
                            id="latitude"
                            name="latitude"
                            class="finput finput-readonly"
                            placeholder="otomatis"
                            value="{{ old('latitude') }}"
                            readonly
                        >
                        <p class="fhelper">Bisa diedit manual</p>
                    </div>
                    <div class="fgroup">
                        <label class="flabel" for="longitude">Longitude</label>
                        <input
                            type="text"
                            id="longitude"
                            name="longitude"
                            class="finput finput-readonly"
                            placeholder="otomatis"
                            value="{{ old('longitude') }}"
                            readonly
                        >
                    </div>
                </div>

                {{-- Kota --}}
                <div class="fgroup">
                    <label class="flabel" for="kota">Kota</label>
                    <input
                        type="text"
                        id="kota"
                        name="kota"
                        class="finput finput-readonly"
                        placeholder="terisi otomatis dari geocoding"
                        value="{{ old('kota') }}"
                        readonly
                    >
                </div>
            </div>

        </div>

        {{-- ==================== KOLOM KANAN ==================== --}}
        <div class="form-col">

            {{-- Card 3: Peta --}}
            <div class="fcard">
                <div class="fcard-header">
                    <div class="fcard-icon"><i class="bi bi-map"></i></div>
                    <div>
                        <div class="fcard-title">Peta Lokasi</div>
                        <div class="fcard-sub">Drag marker untuk atur posisi tepat</div>
                    </div>
                </div>

                {{-- Map container --}}
                <div class="map-outer" id="mapOuter">
                    {{-- Empty state --}}
                    <div class="map-empty-state" id="mapEmptyState">
                        <div class="map-empty-icon">
                            <i class="bi bi-map"></i>
                        </div>
                        <p class="map-empty-title">Belum ada lokasi</p>
                        <p class="map-empty-sub">Masukkan alamat lalu klik "Cari Lokasi"</p>
                    </div>
                    {{-- Leaflet map --}}
                    <div id="leafletMap" style="display:none; width:100%; height:100%; border-radius:8px;"></div>
                </div>

                <p class="fhelper" style="margin-top:8px;">
                    <i class="bi bi-info-circle"></i>
                    Posisi marker akan memperbarui koordinat secara otomatis
                </p>
            </div>

            {{-- Card 4: Foto --}}
            <div class="fcard">
                <div class="fcard-header">
                    <div class="fcard-icon"><i class="bi bi-camera"></i></div>
                    <div>
                        <div class="fcard-title">Foto Bengkel</div>
                        <div class="fcard-sub">Thumbnail yang tampil di listing</div>
                    </div>
                </div>

                {{-- Upload area --}}
                <div
                    id="uploadArea"
                    class="upload-area"
                    ondragover="event.preventDefault(); this.classList.add('upload-drag')"
                    ondragleave="this.classList.remove('upload-drag')"
                    ondrop="handleDrop(event)"
                    onclick="document.getElementById('fotoInput').click()"
                >
                    <div class="upload-area-icon">
                        <i class="bi bi-cloud-arrow-up"></i>
                    </div>
                    <p class="upload-area-title">Klik atau drag foto ke sini</p>
                    <p class="upload-area-sub">
                        Format: JPG, PNG, WEBP · Maksimal <strong>2 MB</strong>
                    </p>
                </div>

                {{-- Preview --}}
                <div id="photoPreview" class="photo-preview-wrap" style="display:none;">
                    <img id="previewImg" src="" alt="Preview Foto Bengkel" class="photo-preview-img">
                    <div class="photo-preview-overlay">
                        <button type="button" class="btn-remove-photo" id="btnRemovePhoto">
                            <i class="bi bi-trash"></i> Hapus Foto
                        </button>
                    </div>
                </div>
                <p class="fhelper photo-filename" id="photoFilename" style="display:none;margin-top:6px;"></p>

                <input
                    type="file"
                    id="fotoInput"
                    name="foto"
                    accept="image/jpeg,image/png,image/webp"
                    style="display:none"
                >
                @error('foto')
                    <p class="ferror">{{ $message }}</p>
                @enderror
            </div>

            {{-- Aksi bawah --}}
            <div class="form-actions">
                <a href="{{ route('admin-pusat.bengkel.index') }}" class="btn-secondary btn-lg">
                    <i class="bi bi-arrow-left"></i> Batal
                </a>
                <button type="submit" form="formTambahBengkel" class="btn-primary btn-lg">
                    <i class="bi bi-floppy"></i> Simpan Bengkel
                </button>
            </div>

        </div>
    </div>
</form>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/admin-bengkel.js') }}"></script>
@endpush