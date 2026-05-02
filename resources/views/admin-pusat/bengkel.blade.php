{{-- resources/views/admin-pusat/bengkel/index.blade.php --}}
@extends('layout.admin')
@section('title', 'Manajemen Bengkel')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-bengkel.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')

{{-- ===== PAGE HEADER ===== --}}
<div class="ab-page-header">
    <div class="ab-page-header__left">
        <div class="ab-page-header__eyebrow">
            <i class="bi bi-shop-window"></i> Admin Pusat
        </div>
        <h1 class="ab-page-header__title">Manajemen Bengkel</h1>
        <p class="ab-page-header__sub">Kelola seluruh cabang bengkel yang terdaftar dalam sistem</p>
    </div>
    <div class="ab-page-header__actions">
        <a href="{{ route('admin-pusat.bengkel.export') }}" class="ab-btn ab-btn--ghost">
            <i class="bi bi-download"></i> Export
        </a>
        <button class="ab-btn ab-btn--primary" id="btnTambahBengkel">
            <i class="bi bi-plus-lg"></i> Tambah Bengkel
        </button>
    </div>
</div>

{{-- ===== STAT STRIP ===== --}}
<div class="ab-stat-strip">
    <div class="ab-stat-item">
        <div class="ab-stat-icon ab-stat-icon--orange">
            <i class="bi bi-shop-window"></i>
        </div>
        <div class="ab-stat-body">
            <div class="ab-stat-val">{{ $totalBengkel ?? 0 }}</div>
            <div class="ab-stat-lbl">Total Bengkel</div>
        </div>
    </div>
    <div class="ab-stat-divider"></div>
    <div class="ab-stat-item">
        <div class="ab-stat-icon ab-stat-icon--green">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="ab-stat-body">
            <div class="ab-stat-val">{{ $bengkelAktif ?? 0 }}</div>
            <div class="ab-stat-lbl">Bengkel Aktif</div>
        </div>
    </div>
    <div class="ab-stat-divider"></div>
    <div class="ab-stat-item">
        <div class="ab-stat-icon ab-stat-icon--blue">
            <i class="bi bi-person-workspace"></i>
        </div>
        <div class="ab-stat-body">
            <div class="ab-stat-val">{{ $totalMekanik ?? 0 }}</div>
            <div class="ab-stat-lbl">Total Mekanik</div>
        </div>
    </div>
    <div class="ab-stat-divider"></div>
    <div class="ab-stat-item">
        <div class="ab-stat-icon ab-stat-icon--amber">
            <i class="bi bi-star-fill"></i>
        </div>
        <div class="ab-stat-body">
            <div class="ab-stat-val">{{ number_format($avgRating ?? 0, 1) }}</div>
            <div class="ab-stat-lbl">Rata-rata Rating</div>
        </div>
    </div>
    <div class="ab-stat-divider"></div>
    <div class="ab-stat-item">
        <div class="ab-stat-icon ab-stat-icon--red">
            <i class="bi bi-x-circle"></i>
        </div>
        <div class="ab-stat-body">
            <div class="ab-stat-val">{{ $bengkelNonaktif ?? 0 }}</div>
            <div class="ab-stat-lbl">Nonaktif</div>
        </div>
    </div>
</div>

{{-- ===== VIEW TOGGLE + SEARCH ===== --}}
<div class="ab-toolbar">
    <div class="ab-toolbar__left">

        {{-- Search --}}
        <div class="ab-search-wrap">
            <i class="bi bi-search ab-search-icon"></i>
            <input
                type="text"
                id="abSearch"
                class="ab-search-input"
                placeholder="Cari nama bengkel, kota, atau PIC..."
                autocomplete="off">
            <button class="ab-search-clear" id="abSearchClear" style="display:none;">
                <i class="bi bi-x"></i>
            </button>
        </div>

        {{-- Filter Status --}}
        <div class="ab-filter-chips">
            <button class="ab-chip ab-chip--active" data-status="semua">Semua</button>
            <button class="ab-chip" data-status="aktif">
                <span class="ab-chip-dot ab-chip-dot--green"></span> Aktif
            </button>
            <button class="ab-chip" data-status="nonaktif">
                <span class="ab-chip-dot ab-chip-dot--red"></span> Nonaktif
            </button>
        </div>

    </div>
    <div class="ab-toolbar__right">
        {{-- Sort --}}
        <select class="ab-select" id="abSort">
            <option value="default">Default</option>
            <option value="rating">Rating Tertinggi</option>
            <option value="reservasi">Reservasi Terbanyak</option>
            <option value="mekanik">Mekanik Terbanyak</option>
            <option value="nama_az">Nama A–Z</option>
        </select>

        {{-- View toggle --}}
        <div class="ab-view-toggle">
            <button class="ab-view-btn ab-view-btn--active" id="btnViewTable" title="Tampilan tabel">
                <i class="bi bi-table"></i>
            </button>
            <button class="ab-view-btn" id="btnViewGrid" title="Tampilan grid">
                <i class="bi bi-grid-3x3-gap"></i>
            </button>
        </div>
    </div>
</div>

{{-- Result info --}}
<div class="ab-result-info">
    <span id="abResultCount">Menampilkan {{ $bengkels->count() }} bengkel</span>
</div>

{{-- ===========================
     VIEW 1: TABLE
============================== --}}
<div id="abViewTable">
    <div class="ab-table-card">
        <div class="ab-table-responsive">
            <table class="ab-table" id="bengkelTable">
                <thead>
                    <tr>
                        <th class="th-check">
                            <input type="checkbox" id="checkAll" class="ab-check">
                        </th>
                        <th class="th-sortable" data-col="nama">
                            Bengkel <i class="bi bi-chevron-expand"></i>
                        </th>
                        <th>Lokasi</th>
                        <th>PIC / Kontak</th>
                        <th class="th-sortable th-center" data-col="mekanik">
                            Mekanik <i class="bi bi-chevron-expand"></i>
                        </th>
                        <th class="th-sortable th-center" data-col="reservasi">
                            Reservasi <i class="bi bi-chevron-expand"></i>
                        </th>
                        <th class="th-sortable th-center" data-col="rating">
                            Rating <i class="bi bi-chevron-expand"></i>
                        </th>
                        <th>Layanan</th>
                        <th class="th-center">Status</th>
                        <th class="th-aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody id="bengkelTbody">
                    @forelse($bengkels as $bengkel)
                    <tr class="ab-tr"
                        data-id="{{ $bengkel->id }}"
                        data-nama="{{ strtolower($bengkel->nama) }}"
                        data-kota="{{ strtolower($bengkel->kota ?? '') }}"
                        data-pic="{{ strtolower($bengkel->pic ?? '') }}"
                        data-status="{{ $bengkel->status ?? 'aktif' }}"
                        data-rating="{{ $bengkel->reviews_avg_rating ?? 0 }}"
                        data-reservasi="{{ $bengkel->reservasi_count ?? 0 }}"
                        data-mekanik="{{ $bengkel->mekaniks_count ?? 0 }}">

                        <td><input type="checkbox" class="ab-check check-row" value="{{ $bengkel->id }}"></td>

                        {{-- Bengkel info --}}
                        <td>
                            <div class="ab-td-bengkel">
                                <div class="ab-td-thumb">
                                    @if($bengkel->foto)
                                        <img src="{{ asset('storage/'.$bengkel->foto) }}" alt="{{ $bengkel->nama }}">
                                    @else
                                        <span class="ab-td-thumb-placeholder">
                                            {{ strtoupper(substr($bengkel->nama, 0, 2)) }}
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <div class="ab-td-name">{{ $bengkel->nama }}</div>
                                    <div class="ab-td-sub">ID #{{ str_pad($bengkel->id, 4, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Lokasi --}}
                        <td>
                            <div class="ab-td-name">{{ $bengkel->kota ?? '-' }}</div>
                            <div class="ab-td-sub ab-td-alamat">{{ Str::limit($bengkel->alamat, 38) }}</div>
                        </td>

                        {{-- PIC --}}
                        <td>
                            <div class="ab-td-name">{{ $bengkel->pic ?? '-' }}</div>
                            <div class="ab-td-sub">{{ $bengkel->telepon ?? '-' }}</div>
                        </td>

                        {{-- Mekanik --}}
                        <td class="td-center">
                            <span class="ab-count-badge">{{ $bengkel->mekaniks_count ?? 0 }}</span>
                        </td>

                        {{-- Reservasi --}}
                        <td class="td-center">
                            <div class="ab-td-name td-center">{{ number_format($bengkel->reservasi_count ?? 0) }}</div>
                            <div class="ab-td-sub td-center">bulan ini</div>
                        </td>

                        {{-- Rating --}}
                        <td class="td-center">
                            <div class="ab-rating-cell">
                                <i class="bi bi-star-fill ab-star"></i>
                                <span class="ab-rating-num">{{ number_format($bengkel->reviews_avg_rating ?? 0, 1) }}</span>
                            </div>
                            <div class="ab-td-sub td-center">{{ $bengkel->reviews_count ?? 0 }} ulasan</div>
                        </td>

                        {{-- Layanan tags --}}
                        <td>
                            <div class="ab-tag-wrap">
                                @if($bengkel->layanan && $bengkel->layanan->count() > 0)
                                    @foreach($bengkel->layanan->take(2) as $l)
                                        <span class="ab-tag">{{ $l->nama }}</span>
                                    @endforeach
                                    @if($bengkel->layanan->count() > 2)
                                        <span class="ab-tag ab-tag--more">+{{ $bengkel->layanan->count() - 2 }}</span>
                                    @endif
                                @else
                                    <span class="ab-td-sub">—</span>
                                @endif
                            </div>
                        </td>

                        {{-- Status --}}
                        <td class="td-center">
                            <label class="ab-toggle" title="Toggle status">
                                <input type="checkbox"
                                       class="ab-toggle-input toggle-status"
                                       data-id="{{ $bengkel->id }}"
                                       {{ ($bengkel->status ?? 'aktif') === 'aktif' ? 'checked' : '' }}>
                                <span class="ab-toggle-slider"></span>
                            </label>
                        </td>

                        {{-- Aksi --}}
                        <td>
                            <div class="ab-aksi-group">
                                <button class="ab-aksi-btn ab-aksi-btn--view"
                                        data-id="{{ $bengkel->id }}" title="Lihat detail">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="ab-aksi-btn ab-aksi-btn--edit btn-edit-bengkel"
                                        data-id="{{ $bengkel->id }}"
                                        data-nama="{{ $bengkel->nama }}"
                                        data-alamat="{{ $bengkel->alamat }}"
                                        data-kota="{{ $bengkel->kota }}"
                                        data-telepon="{{ $bengkel->telepon }}"
                                        data-pic="{{ $bengkel->pic }}"
                                        data-status="{{ $bengkel->status ?? 'aktif' }}"
                                        data-lat="{{ $bengkel->latitude }}"
                                        data-lng="{{ $bengkel->longitude }}"
                                        title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="ab-aksi-btn ab-aksi-btn--delete btn-delete-bengkel"
                                        data-id="{{ $bengkel->id }}"
                                        data-nama="{{ $bengkel->nama }}"
                                        data-action="{{ route('admin-pusat.bengkel.destroy', $bengkel->id) }}"
                                        title="Hapus">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10">
                            <div class="ab-empty-state">
                                <i class="bi bi-shop-window"></i>
                                <h4>Belum ada bengkel terdaftar</h4>
                                <p>Klik "Tambah Bengkel" untuk memulai</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="ab-table-footer">
            <div class="ab-table-info">
                Total <strong>{{ $bengkels->total() ?? 0 }}</strong> bengkel terdaftar
            </div>
            <div class="ab-pagination">
                {{ $bengkels->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

{{-- ===========================
     VIEW 2: GRID CARDS
============================== --}}
<div id="abViewGrid" style="display:none;">
    <div class="ab-grid" id="bengkelGrid">
        @foreach($bengkels as $bengkel)
        <div class="ab-grid-card"
             data-id="{{ $bengkel->id }}"
             data-nama="{{ strtolower($bengkel->nama) }}"
             data-kota="{{ strtolower($bengkel->kota ?? '') }}"
             data-status="{{ $bengkel->status ?? 'aktif' }}"
             data-rating="{{ $bengkel->reviews_avg_rating ?? 0 }}"
             data-reservasi="{{ $bengkel->reservasi_count ?? 0 }}"
             data-mekanik="{{ $bengkel->mekaniks_count ?? 0 }}">

            {{-- Card image --}}
            <div class="ab-grid-img-wrap">
                <img src="{{ $bengkel->foto ? asset('storage/'.$bengkel->foto) : asset('img/bengkel-default.jpg') }}"
                     class="ab-grid-img" alt="{{ $bengkel->nama }}" loading="lazy">
                <span class="ab-grid-status-badge {{ ($bengkel->status ?? 'aktif') === 'aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                    ● {{ ($bengkel->status ?? 'aktif') === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>

            {{-- Card body --}}
            <div class="ab-grid-body">
                <h3 class="ab-grid-nama">{{ $bengkel->nama }}</h3>
                <p class="ab-grid-alamat">
                    <i class="bi bi-geo-alt"></i> {{ Str::limit($bengkel->alamat, 50) }}
                </p>

                {{-- Stats row --}}
                <div class="ab-grid-stats">
                    <div class="ab-grid-stat">
                        <span class="ab-grid-stat-val">
                            <i class="bi bi-star-fill" style="color:#f59e0b;font-size:11px;"></i>
                            {{ number_format($bengkel->reviews_avg_rating ?? 0, 1) }}
                        </span>
                        <span class="ab-grid-stat-lbl">Rating</span>
                    </div>
                    <div class="ab-grid-stat-sep"></div>
                    <div class="ab-grid-stat">
                        <span class="ab-grid-stat-val">{{ $bengkel->mekaniks_count ?? 0 }}</span>
                        <span class="ab-grid-stat-lbl">Mekanik</span>
                    </div>
                    <div class="ab-grid-stat-sep"></div>
                    <div class="ab-grid-stat">
                        <span class="ab-grid-stat-val">{{ number_format($bengkel->reservasi_count ?? 0) }}</span>
                        <span class="ab-grid-stat-lbl">Reservasi</span>
                    </div>
                </div>

                {{-- Tags --}}
                @if($bengkel->layanan && $bengkel->layanan->count() > 0)
                <div class="ab-tag-wrap" style="margin-top:10px;">
                    @foreach($bengkel->layanan->take(3) as $l)
                        <span class="ab-tag">{{ $l->nama }}</span>
                    @endforeach
                    @if($bengkel->layanan->count() > 3)
                        <span class="ab-tag ab-tag--more">+{{ $bengkel->layanan->count() - 3 }}</span>
                    @endif
                </div>
                @endif
            </div>

            {{-- Card footer --}}
            <div class="ab-grid-footer">
                <a href="{{ route('admin-pusat.bengkel.show', $bengkel->id) }}"
                   class="ab-btn ab-btn--sm ab-btn--ghost">
                    <i class="bi bi-eye"></i> Detail
                </a>
                <button class="ab-btn ab-btn--sm ab-btn--primary btn-edit-bengkel"
                        data-id="{{ $bengkel->id }}"
                        data-nama="{{ $bengkel->nama }}"
                        data-alamat="{{ $bengkel->alamat }}"
                        data-kota="{{ $bengkel->kota }}"
                        data-telepon="{{ $bengkel->telepon }}"
                        data-pic="{{ $bengkel->pic }}"
                        data-status="{{ $bengkel->status ?? 'aktif' }}">
                    <i class="bi bi-pencil"></i> Edit
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>


{{-- =============================================
     MODAL: TAMBAH / EDIT BENGKEL
=============================================== --}}
<div class="ab-modal-overlay" id="modalBengkel">
    <div class="ab-modal">
        <div class="ab-modal-header">
            <div>
                <h4 class="ab-modal-title" id="modalBengkelTitle">Tambah Bengkel</h4>
                <p class="ab-modal-sub" id="modalBengkelSub">Isi data bengkel baru</p>
            </div>
            <button class="ab-modal-close" id="modalBengkelClose">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="formBengkel" method="POST" enctype="multipart/form-data">
            @csrf
            <span id="methodField"></span>

            <div class="ab-modal-body">

                {{-- Upload Foto --}}
                <div class="ab-form-section">
                    <div class="ab-photo-upload" id="photoUploadArea">
                        <input type="file" name="foto" id="fotoInput" accept="image/*" hidden>
                        <div class="ab-photo-preview" id="photoPreview">
                            <i class="bi bi-camera"></i>
                            <span>Upload Foto Bengkel</span>
                            <small>JPG, PNG, WebP — maks 2MB</small>
                        </div>
                        <img id="photoPreviewImg" src="" alt="" style="display:none;" class="ab-photo-preview-img">
                    </div>
                </div>

                {{-- Row 1 --}}
                <div class="ab-form-row">
                    <div class="ab-form-field ab-form-field--full">
                        <label class="ab-form-label">Nama Bengkel <span class="req">*</span></label>
                        <input type="text" name="nama" id="fNama" class="ab-form-input"
                               placeholder="Contoh: Bengkel Maju Jaya" required>
                    </div>
                </div>

                {{-- Row 2 --}}
                <div class="ab-form-row">
                    <div class="ab-form-field">
                        <label class="ab-form-label">Kota <span class="req">*</span></label>
                        <input type="text" name="kota" id="fKota" class="ab-form-input"
                               placeholder="Contoh: Jakarta Selatan" required>
                    </div>
                    <div class="ab-form-field">
                        <label class="ab-form-label">No. Telepon</label>
                        <input type="text" name="telepon" id="fTelepon" class="ab-form-input"
                               placeholder="08xx-xxxx-xxxx">
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="ab-form-row">
                    <div class="ab-form-field ab-form-field--full">
                        <label class="ab-form-label">Alamat Lengkap <span class="req">*</span></label>
                        <textarea name="alamat" id="fAlamat" class="ab-form-textarea"
                                  rows="2" placeholder="Jl. Contoh No. 1, Kelurahan, Kecamatan..." required></textarea>
                    </div>
                </div>

                {{-- Row 3 --}}
                <div class="ab-form-row">
                    <div class="ab-form-field">
                        <label class="ab-form-label">PIC (Person in Charge)</label>
                        <input type="text" name="pic" id="fPic" class="ab-form-input"
                               placeholder="Nama penanggung jawab">
                    </div>
                    <div class="ab-form-field">
                        <label class="ab-form-label">Status</label>
                        <select name="status" id="fStatus" class="ab-form-select">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>

                {{-- Koordinat + Mini Map --}}
                <div class="ab-form-row">
                    <div class="ab-form-field">
                        <label class="ab-form-label">Latitude</label>
                        <input type="text" name="latitude" id="fLat" class="ab-form-input"
                               placeholder="-6.9175">
                    </div>
                    <div class="ab-form-field">
                        <label class="ab-form-label">Longitude</label>
                        <input type="text" name="longitude" id="fLng" class="ab-form-input"
                               placeholder="107.6191">
                    </div>
                </div>

                <div class="ab-form-hint">
                    <i class="bi bi-info-circle"></i>
                    Klik pada peta untuk menentukan koordinat bengkel secara otomatis
                </div>

                {{-- Mini map untuk pin lokasi --}}
                <div id="modalMap" class="ab-modal-map"></div>

            </div>

            <div class="ab-modal-footer">
                <button type="button" class="ab-btn ab-btn--ghost" id="btnModalCancel">Batal</button>
                <button type="submit" class="ab-btn ab-btn--primary" id="btnModalSave">
                    <i class="bi bi-check-lg"></i>
                    <span id="btnModalSaveText">Simpan Bengkel</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- =============================================
     MODAL: KONFIRMASI HAPUS
=============================================== --}}
<div class="ab-modal-overlay" id="modalHapus">
    <div class="ab-modal ab-modal--sm">
        <div class="ab-modal-danger-icon">
            <i class="bi bi-trash3"></i>
        </div>
        <h4 class="ab-modal-title" style="text-align:center;margin-bottom:6px;">Hapus Bengkel?</h4>
        <p class="ab-modal-sub" style="text-align:center;margin-bottom:20px;">
            Bengkel <strong id="hapusNamaBengkel"></strong> akan dihapus permanen beserta seluruh data terkait.
        </p>
        <div class="ab-modal-footer" style="justify-content:center;">
            <button class="ab-btn ab-btn--ghost" id="btnHapusCancel">Batal</button>
            <form id="formHapus" method="POST" style="margin:0;">
                @csrf @method('DELETE')
                <button type="submit" class="ab-btn ab-btn--danger">
                    <i class="bi bi-trash3"></i> Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

{{-- =============================================
     MODAL: DETAIL BENGKEL
=============================================== --}}
<div class="ab-modal-overlay" id="modalDetail">
    <div class="ab-modal ab-modal--wide">
        <div class="ab-modal-header">
            <h4 class="ab-modal-title" id="detailNama">Detail Bengkel</h4>
            <button class="ab-modal-close" id="modalDetailClose"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="ab-modal-body" id="modalDetailBody">
            <div class="ab-detail-loading">
                <div class="ab-spinner"></div>
                <span>Memuat data bengkel...</span>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── SEARCH ──
    const searchInput = document.getElementById('abSearch');
    const searchClear = document.getElementById('abSearchClear');
    let searchTimer;

    searchInput.addEventListener('input', function () {
        searchClear.style.display = this.value ? 'flex' : 'none';
        clearTimeout(searchTimer);
        searchTimer = setTimeout(filterRows, 300);
    });

    searchClear.addEventListener('click', function () {
        searchInput.value = '';
        this.style.display = 'none';
        filterRows();
    });

    // ── FILTER CHIPS ──
    document.querySelectorAll('.ab-chip').forEach(chip => {
        chip.addEventListener('click', function () {
            document.querySelectorAll('.ab-chip').forEach(c => c.classList.remove('ab-chip--active'));
            this.classList.add('ab-chip--active');
            filterRows();
        });
    });

    // ── FILTER LOGIC ──
    function filterRows() {
        const q       = searchInput.value.toLowerCase().trim();
        const status  = document.querySelector('.ab-chip.ab-chip--active')?.dataset.status ?? 'semua';
        const rows    = document.querySelectorAll('#bengkelTbody .ab-tr');
        const cards   = document.querySelectorAll('#bengkelGrid .ab-grid-card');
        let count = 0;

        [...rows, ...cards].forEach(el => {
            const nama   = el.dataset.nama   || '';
            const kota   = el.dataset.kota   || '';
            const pic    = el.dataset.pic    || '';
            const elStatus = el.dataset.status || 'aktif';

            const matchQ = !q || nama.includes(q) || kota.includes(q) || pic.includes(q);
            const matchS = status === 'semua' || elStatus === status;

            el.style.display = matchQ && matchS ? '' : 'none';
            if (matchQ && matchS) count++;
        });

        document.getElementById('abResultCount').textContent = count + ' bengkel ditemukan';
    }

    // ── SORT ──
    document.getElementById('abSort').addEventListener('change', function () {
        const by   = this.value;
        const tbody = document.getElementById('bengkelTbody');
        const rows  = Array.from(tbody.querySelectorAll('.ab-tr'));

        rows.sort((a, b) => {
            if (by === 'rating')   return parseFloat(b.dataset.rating)   - parseFloat(a.dataset.rating);
            if (by === 'reservasi')return parseInt(b.dataset.reservasi)  - parseInt(a.dataset.reservasi);
            if (by === 'mekanik')  return parseInt(b.dataset.mekanik)    - parseInt(a.dataset.mekanik);
            if (by === 'nama_az')  return a.dataset.nama.localeCompare(b.dataset.nama);
            return 0;
        });
        rows.forEach(r => tbody.appendChild(r));
    });

    // ── VIEW TOGGLE ──
    const viewTable = document.getElementById('abViewTable');
    const viewGrid  = document.getElementById('abViewGrid');

    document.getElementById('btnViewTable').addEventListener('click', function () {
        viewTable.style.display = '';
        viewGrid.style.display  = 'none';
        this.classList.add('ab-view-btn--active');
        document.getElementById('btnViewGrid').classList.remove('ab-view-btn--active');
    });

    document.getElementById('btnViewGrid').addEventListener('click', function () {
        viewTable.style.display = 'none';
        viewGrid.style.display  = '';
        this.classList.add('ab-view-btn--active');
        document.getElementById('btnViewTable').classList.remove('ab-view-btn--active');
    });

    // ── CHECK ALL ──
    document.getElementById('checkAll').addEventListener('change', function () {
        document.querySelectorAll('.check-row').forEach(cb => cb.checked = this.checked);
    });

    // ── TOGGLE STATUS ──
    document.querySelectorAll('.toggle-status').forEach(toggle => {
        toggle.addEventListener('change', function () {
            const id     = this.dataset.id;
            const status = this.checked ? 'aktif' : 'nonaktif';
            fetch(`/admin-pusat/bengkel/${id}/toggle-status`, {
                method: 'PATCH',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
                body: JSON.stringify({ status })
            }).catch(() => { this.checked = !this.checked; });
        });
    });

    // ── MODAL SETUP ──
    let modalMap = null;
    let mapMarker = null;

    function initModalMap() {
        if (modalMap) return;
        modalMap = L.map('modalMap').setView([-6.9175, 107.6191], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(modalMap);

        modalMap.on('click', function (e) {
            const { lat, lng } = e.latlng;
            document.getElementById('fLat').value = lat.toFixed(6);
            document.getElementById('fLng').value = lng.toFixed(6);
            if (mapMarker) modalMap.removeLayer(mapMarker);
            mapMarker = L.marker([lat, lng]).addTo(modalMap);
        });
    }

    function openModal(id) {
        document.getElementById(id).classList.add('open');
        if (id === 'modalBengkel') {
            setTimeout(() => { initModalMap(); modalMap.invalidateSize(); }, 300);
        }
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
    }

    // ── TAMBAH BENGKEL ──
    document.getElementById('btnTambahBengkel').addEventListener('click', function () {
        document.getElementById('formBengkel').reset();
        document.getElementById('formBengkel').action = '{{ route("admin-pusat.bengkel.store") }}';
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('modalBengkelTitle').textContent = 'Tambah Bengkel';
        document.getElementById('modalBengkelSub').textContent   = 'Isi data bengkel baru';
        document.getElementById('btnModalSaveText').textContent  = 'Simpan Bengkel';
        document.getElementById('photoPreview').style.display    = 'flex';
        document.getElementById('photoPreviewImg').style.display = 'none';
        openModal('modalBengkel');
    });

    // ── EDIT BENGKEL ──
    document.querySelectorAll('.btn-edit-bengkel').forEach(btn => {
        btn.addEventListener('click', function () {
            const d = this.dataset;
            const form = document.getElementById('formBengkel');
            form.action = `/admin-pusat/bengkel/${d.id}`;
            document.getElementById('methodField').innerHTML = '@method("PUT")';
            document.getElementById('modalBengkelTitle').textContent = 'Edit Bengkel';
            document.getElementById('modalBengkelSub').textContent   = 'Perbarui data bengkel';
            document.getElementById('btnModalSaveText').textContent  = 'Perbarui';

            document.getElementById('fNama').value    = d.nama    || '';
            document.getElementById('fKota').value    = d.kota    || '';
            document.getElementById('fAlamat').value  = d.alamat  || '';
            document.getElementById('fTelepon').value = d.telepon || '';
            document.getElementById('fPic').value     = d.pic     || '';
            document.getElementById('fLat').value     = d.lat     || '';
            document.getElementById('fLng').value     = d.lng     || '';
            document.getElementById('fStatus').value  = d.status  || 'aktif';
            openModal('modalBengkel');

            if (d.lat && d.lng) {
                setTimeout(() => {
                    initModalMap();
                    modalMap.invalidateSize();
                    modalMap.setView([parseFloat(d.lat), parseFloat(d.lng)], 15);
                    if (mapMarker) modalMap.removeLayer(mapMarker);
                    mapMarker = L.marker([parseFloat(d.lat), parseFloat(d.lng)]).addTo(modalMap);
                }, 350);
            }
        });
    });

    // ── HAPUS ──
    document.querySelectorAll('.btn-delete-bengkel').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('hapusNamaBengkel').textContent = this.dataset.nama;
            document.getElementById('formHapus').action = this.dataset.action;
            openModal('modalHapus');
        });
    });

    // ── DETAIL VIEW ──
    document.querySelectorAll('.ab-aksi-btn--view').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            openModal('modalDetail');
            document.getElementById('modalDetailBody').innerHTML = `
                <div class="ab-detail-loading"><div class="ab-spinner"></div><span>Memuat...</span></div>`;

            fetch(`/admin-pusat/bengkel/${id}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('detailNama').textContent = data.nama;
                document.getElementById('modalDetailBody').innerHTML = renderDetail(data);
            })
            .catch(() => {
                document.getElementById('modalDetailBody').innerHTML =
                    '<p style="text-align:center;color:#ef4444;padding:24px;">Gagal memuat data.</p>';
            });
        });
    });

    function renderDetail(d) {
        return `
        <div class="ab-detail-grid">
            <div class="ab-detail-section">
                <div class="ab-detail-label">Alamat</div>
                <div class="ab-detail-val">${d.alamat || '—'}</div>
            </div>
            <div class="ab-detail-section">
                <div class="ab-detail-label">Kota</div>
                <div class="ab-detail-val">${d.kota || '—'}</div>
            </div>
            <div class="ab-detail-section">
                <div class="ab-detail-label">Telepon</div>
                <div class="ab-detail-val">${d.telepon || '—'}</div>
            </div>
            <div class="ab-detail-section">
                <div class="ab-detail-label">PIC</div>
                <div class="ab-detail-val">${d.pic || '—'}</div>
            </div>
            <div class="ab-detail-section">
                <div class="ab-detail-label">Total Mekanik</div>
                <div class="ab-detail-val">${d.mekaniks_count ?? 0} orang</div>
            </div>
            <div class="ab-detail-section">
                <div class="ab-detail-label">Rating</div>
                <div class="ab-detail-val">★ ${parseFloat(d.reviews_avg_rating || 0).toFixed(1)} (${d.reviews_count ?? 0} ulasan)</div>
            </div>
        </div>`;
    }

    // ── CLOSE MODALS ──
    ['modalBengkelClose','btnModalCancel','btnHapusCancel','modalDetailClose'].forEach(id => {
        document.getElementById(id)?.addEventListener('click', () => {
            document.querySelectorAll('.ab-modal-overlay').forEach(m => m.classList.remove('open'));
        });
    });

    document.querySelectorAll('.ab-modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function (e) {
            if (e.target === this) this.classList.remove('open');
        });
    });

    // ── FOTO PREVIEW ──
    document.getElementById('photoUploadArea').addEventListener('click', () => {
        document.getElementById('fotoInput').click();
    });

    document.getElementById('fotoInput').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('photoPreview').style.display    = 'none';
            const img = document.getElementById('photoPreviewImg');
            img.src = e.target.result;
            img.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });

});
</script>
@endpush