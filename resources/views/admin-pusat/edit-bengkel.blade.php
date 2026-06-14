{{-- resources/views/admin-pusat/bengkel/edit.blade.php --}}
@extends('layout.admin')
@section('title', 'Edit Bengkel — ' . $bengkel->nama)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-bengkel.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')

{{-- ===== PAGE HEADER ===== --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Edit Bengkel</h1>
        <nav class="breadcrumb-nav">
            <a href="{{ route('admin-pusat.dashboard') }}" class="bc-link">Dashboard</a>
            <i class="bi bi-chevron-right bc-sep"></i>
            <a href="{{ route('admin-pusat.bengkel.index') }}" class="bc-link">Bengkel</a>
            <i class="bi bi-chevron-right bc-sep"></i>
            <a href="{{ route('admin-pusat.bengkel.show', $bengkel->id) }}" class="bc-link">{{ $bengkel->nama }}</a>
            <i class="bi bi-chevron-right bc-sep"></i>
            <span class="bc-current">Edit</span>
        </nav>
    </div>
</div>

{{-- Flash error --}}
@if($errors->any())
<div class="alert-error">
    <i class="bi bi-exclamation-circle-fill"></i>
    <div>
        <strong>Terdapat {{ $errors->count() }} kesalahan:</strong>
        <ul style="margin:4px 0 0 16px;">
            @foreach($errors->all() as $error)
                <li style="font-size:12px;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <button onclick="this.parentElement.remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;font-size:16px;">×</button>
</div>
@endif

{{-- ===== FORM ===== --}}
<form id="formEditBengkel"
      action="{{ route('admin-pusat.bengkel.update', $bengkel->id) }}"
      method="POST"
      enctype="multipart/form-data"
      novalidate>
    @csrf
    @method('PUT')

    {{-- Pass data bengkel ke JS --}}
    <script>
        window.editBengkelData = {
            lat  : {{ $bengkel->latitude ?? 'null' }},
            lng  : {{ $bengkel->longitude ?? 'null' }},
            nama : "{{ addslashes($bengkel->nama) }}",
            hasFoto: {{ isset($bengkel->foto) && $bengkel->foto ? 'true' : 'false' }}
        };
    </script>

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
                        value="{{ old('nama', $bengkel->nama) }}"
                        required
                    >
                    @error('nama')
                        <p class="ferror"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
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

                        {{-- Admin yang sekarang ditugaskan (selalu muncul meski sudah punya bengkel) --}}
                        @if($bengkel->adminCabang)
                            <option value="{{ $bengkel->adminCabang->id }}"
                                {{ old('admin_id', $bengkel->admin_id) == $bengkel->adminCabang->id ? 'selected' : '' }}>
                                {{ $bengkel->adminCabang->name }} ({{ $bengkel->adminCabang->email }}) — Saat ini
                            </option>
                        @endif

                        {{-- Admin cabang lain yang belum punya bengkel --}}
                        @foreach($adminCabang ?? [] as $admin)
                            @if($admin->id !== $bengkel->admin_id)
                                <option value="{{ $admin->id }}"
                                    {{ old('admin_id') == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->name }} ({{ $admin->email }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <p class="fhelper">
                        <i class="bi bi-info-circle"></i>
                        Admin saat ini selalu tersedia. Ganti jika ingin alihkan ke admin lain.
                    </p>
                    @error('admin_id')
                        <p class="ferror"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- Nomor Telepon --}}
                <div class="fgroup">
                    <label class="flabel" for="telepon">
                        Nomor Telepon <span class="freq">*</span>
                    </label>

                    <input
                        type="text"
                        id="telepon"
                        name="telepon"
                        class="finput @error('telepon') finput-error @enderror"
                        placeholder="cth: 08123456789"
                        value="{{ old('telepon', $bengkel->telepon) }}"
                        required
                    >

                    @error('telepon')
                        <p class="ferror">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>                

                <!-- {{-- Kapasitas Antrian --}}
                <div class="fgroup">
                    <label class="flabel" for="kapasitas">
                        Kapasitas Antrian <span class="freq">*</span>
                    </label>
                    <div class="input-with-suffix">
                        <input
                            type="number"
                            id="kapasitas"
                            name="kapasitas"
                            class="finput @error('kapasitas') finput-error @enderror"
                            placeholder="cth: 6"
                            value="{{ old('kapasitas', $bengkel->kapasitas ?? 6) }}"
                            min="1"
                            max="50"
                            required
                        >
                        <span class="input-suffix">slot / hari</span>
                    </div>
                    <p class="fhelper">
                        <i class="bi bi-info-circle"></i>
                        Jumlah maksimal reservasi yang bisa diterima per hari
                    </p>
                    @error('kapasitas')
                        <p class="ferror"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div> -->

                {{-- Status --}}
                <div class="fgroup">
                    <label class="flabel">Status Bengkel</label>
                    <div class="toggle-wrap" id="toggleWrap">
                        <div class="toggle-track {{ old('status', $bengkel->status) === 'aktif' ? 'on' : '' }}" id="toggleTrack">
                            <div class="toggle-thumb"></div>
                        </div>
                        <div class="toggle-info">
                            <div class="toggle-lbl {{ old('status', $bengkel->status) !== 'aktif' ? 'off' : '' }}" id="toggleLabel">
                                {{ old('status', $bengkel->status) === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                            </div>
                            <div class="toggle-sub">Bengkel akan muncul di platform</div>
                        </div>
                    </div>
                    <input type="hidden" name="status" id="statusInput"
                           value="{{ old('status', $bengkel->status) }}">
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

                {{-- Alamat --}}
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
                    >{{ old('alamat', $bengkel->alamat) }}</textarea>
                    @error('alamat')
                        <p class="ferror"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
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
                            value="{{ old('latitude', $bengkel->latitude) }}"
                            readonly
                        >
                        <p class="fhelper">Klik untuk edit manual</p>
                    </div>
                    <div class="fgroup">
                        <label class="flabel" for="longitude">Longitude</label>
                        <input
                            type="text"
                            id="longitude"
                            name="longitude"
                            class="finput finput-readonly"
                            placeholder="otomatis"
                            value="{{ old('longitude', $bengkel->longitude) }}"
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
                        value="{{ old('kota', $bengkel->kota) }}"
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

                <div class="map-outer" id="mapOuter">
                    {{-- Empty state: hanya tampil kalau belum ada koordinat --}}
                    <div class="map-empty-state" id="mapEmptyState"
                         style="{{ ($bengkel->latitude && $bengkel->longitude) ? 'display:none;' : '' }}">
                        <div class="map-empty-icon"><i class="bi bi-map"></i></div>
                        <p class="map-empty-title">Belum ada lokasi</p>
                        <p class="map-empty-sub">Masukkan alamat lalu klik "Cari Lokasi"</p>
                    </div>
                    {{-- Leaflet: langsung tampil kalau sudah ada koordinat --}}
                    <div id="leafletMap"
                         style="{{ ($bengkel->latitude && $bengkel->longitude) ? '' : 'display:none;' }} width:100%;height:100%;border-radius:8px;">
                    </div>
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
                        <div class="fcard-sub">Kosongkan jika tidak ingin mengubah foto</div>
                    </div>
                </div>

                {{-- Foto existing --}}
                @if($bengkel->foto)
                <div class="foto-existing" id="fotoExisting">
                    <div class="foto-existing-wrap">
                        <img src="{{ asset('storage/' . $bengkel->foto) }}"
                             alt="Foto {{ $bengkel->nama }}"
                             class="foto-existing-img">
                    </div>
                    <div class="foto-existing-meta">
                        <span class="foto-existing-label">
                            <i class="bi bi-image"></i> Foto saat ini
                        </span>

                        <div class="foto-actions">
                            <button type="button" class="foto-btn foto-btn-edit" 
                                    onclick="document.getElementById('fotoInput').click()">
                                <i class="bi bi-pencil"></i> Ganti
                            </button>

                            <button type="button" class="foto-btn foto-btn-danger" id="btnHapusFoto">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                    {{-- Flag untuk hapus foto --}}
                    <input type="hidden" name="hapus_foto" id="hapusFotoInput" value="0">
                </div>
                @endif

                {{-- Upload area (tersembunyi kalau sudah ada foto) --}}
                <div id="uploadArea"
                     class="upload-area"
                     style="{{ $bengkel->foto ? 'display:none;' : '' }}"
                     ondragover="event.preventDefault(); this.classList.add('upload-drag')"
                     ondragleave="this.classList.remove('upload-drag')"
                     ondrop="handleDrop(event)"
                     onclick="document.getElementById('fotoInput').click()">
                    <div class="upload-area-icon">
                        <i class="bi bi-cloud-arrow-up"></i>
                    </div>
                    <p class="upload-area-title">Klik atau drag foto ke sini</p>
                    <p class="upload-area-sub">
                        Format: JPG, PNG, WEBP · Maksimal <strong>2 MB</strong>
                    </p>
                </div>

                {{-- Preview foto baru --}}
                <div id="photoPreview" class="photo-preview-wrap" style="display:none;">
                    <img id="previewImg" src="" alt="Preview Foto Baru" class="photo-preview-img">
                    <div class="photo-preview-overlay">
                        <button type="button" class="btn-remove-photo" id="btnRemovePhoto">
                            <i class="bi bi-trash"></i> Hapus Foto
                        </button>
                    </div>
                    <div class="foto-new-badge">
                        <i class="bi bi-arrow-repeat"></i> Foto baru
                    </div>
                </div>
                <p class="fhelper" id="photoFilename" style="display:none;margin-top:6px;"></p>

                <input type="file" id="fotoInput" name="foto"
                       accept="image/jpeg,image/png,image/webp" style="display:none">

                @error('foto')
                    <p class="ferror"><i class="bi bi-exclamation-circle"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Aksi bawah --}}
            <div class="form-actions">
                <a href="{{ route('admin-pusat.bengkel.show', $bengkel->id) }}"
                   class="btn-secondary btn-lg">
                    <i class="bi bi-arrow-left"></i> Batal
                </a>
                <button type="submit" form="formEditBengkel" class="btn-primary btn-lg">
                    <i class="bi bi-floppy"></i> Simpan Perubahan
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