{{-- resources/views/admin-pusat/layanan/index.blade.php --}}
@extends('layout.admin')
@section('title', 'Manajemen Layanan')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-layanan.css') }}">
@endpush

@section('content')

{{-- ===== PAGE HEADER ===== --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Layanan</h1>
        <p class="page-sub">Kelola layanan utama yang tersedia untuk seluruh bengkel</p>
    </div>
    <button class="btn-primary" id="btnTambahLayanan">
        <i class="bi bi-plus-lg"></i> Tambah Layanan
    </button>
</div>

{{-- ===== STAT CARDS ===== --}}
<div class="stat-grid stat-grid-3">
    <div class="stat-card">
        <div class="stat-icon-wrap si-orange"><i class="bi bi-wrench-adjustable"></i></div>
        <div class="stat-body">
            <div class="stat-label">Total Layanan</div>
            <div class="stat-value">{{ $totalLayanan ?? 0 }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap si-green"><i class="bi bi-check-circle"></i></div>
        <div class="stat-body">
            <div class="stat-label">Layanan Aktif</div>
            <div class="stat-value" style="color:#16a34a;">{{ $totalAktif ?? 0 }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap si-gray"><i class="bi bi-pause-circle"></i></div>
        <div class="stat-body">
            <div class="stat-label">Layanan Nonaktif</div>
            <div class="stat-value" style="color:#6b7280;">{{ $totalNonaktif ?? 0 }}</div>
        </div>
    </div>
</div>

{{-- ===== TOOLBAR ===== --}}
<div class="toolbar">
    <div class="search-wrap">
        <i class="bi bi-search search-icon"></i>
        <input
            type="text"
            class="search-input"
            id="searchInput"
            placeholder="Cari nama layanan atau deskripsi..."
            value="{{ request('search') }}"
        >
    </div>
    <select class="filter-select" id="filterStatus">
        <option value="">Semua Status</option>
        <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
    </select>
    <select class="filter-select" id="sortBy">
        <option value="">Urutkan...</option>
        <option value="harga-asc">Harga: Rendah ke Tinggi</option>
        <option value="harga-desc">Harga: Tinggi ke Rendah</option>
        <option value="durasi-asc">Durasi: Cepat ke Lama</option>
        <option value="durasi-desc">Durasi: Lama ke Cepat</option>
        <option value="nama-asc">Nama: A–Z</option>
    </select>
</div>

{{-- ===== TABEL LAYANAN ===== --}}
<div class="admin-table-card">
    <div class="table-card-header">
        <div class="table-card-title-wrap">
            <h3 class="table-card-title">Daftar Layanan</h3>
            <span class="count-pill" id="countPill">{{ $layanans->count() ?? 0 }} layanan</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="admin-table" id="layananTable">
            <thead>
                <tr>
                    <th class="th-sortable" data-sort="nama">
                        Layanan <span class="sort-icon">↕</span>
                    </th>
                    <th class="th-sortable" data-sort="harga">
                        Harga Estimasi <span class="sort-icon">↕</span>
                    </th>
                    <th class="th-sortable" data-sort="durasi">
                        Durasi <span class="sort-icon">↕</span>
                    </th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="layananTableBody">
                @forelse($layanans ?? [] as $l)
                <tr data-id="{{ $l->id }}"
                    data-nama="{{ strtolower($l->nama) }}"
                    data-desc="{{ strtolower($l->deskripsi ?? '') }}"
                    data-harga="{{ $l->harga_dasar }}"
                    data-durasi="{{ $l->durasi_menit }}"
                    data-status="{{ $l->status }}">
                    <td>
                        <div class="td-layanan-name">{{ $l->nama }}</div>
                        @if($l->deskripsi)
                            <div class="td-layanan-desc">{{ $l->deskripsi }}</div>
                        @endif
                    </td>
                    <td>
                        <div class="td-price">
                            Rp {{ number_format($l->harga_dasar ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="td-price-note">*estimasi</div>
                    </td>
                    <td>
                        <div class="td-durasi">
                            <i class="bi bi-clock"></i>
                            {{ $l->durasi_menit }} menit
                        </div>
                    </td>
                    <td>
                        <span class="status-badge {{ $l->status === 'aktif' ? 'badge-done' : 'badge-neutral' }}">
                            <span class="status-dot {{ $l->status === 'aktif' ? 'sd-aktif' : 'sd-nonaktif' }}"></span>
                            {{ $l->status === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <div class="aksi-wrap">
                            {{-- Edit --}}
                            <button
                                class="btn-icon-aksi"
                                title="Edit layanan"
                                onclick="editLayanan({{ $l->id }},
                                    '{{ addslashes($l->nama) }}',
                                    {{ $l->harga_dasar }},
                                    {{ $l->durasi_menit }},
                                    '{{ addslashes($l->deskripsi ?? '') }}',
                                    '{{ $l->status }}'
                                )">
                                <i class="bi bi-pencil"></i>
                            </button>

                            {{-- Toggle status --}}
                            <form action="{{ route('admin-pusat.layanan.toggle', $l->id) }}"
                                  method="POST" class="form-inline">
                                @csrf @method('PATCH')
                                <button
                                    type="submit"
                                    class="btn-toggle-status {{ $l->status === 'aktif' ? 'btn-to-nonaktif' : 'btn-to-aktif' }}"
                                    onclick="return confirm('{{ $l->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }} layanan ini?')">
                                    {{ $l->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            {{-- Hapus --}}
                            <form action="{{ route('admin-pusat.layanan.destroy', $l->id) }}"
                                  method="POST" class="form-inline">
                                @csrf @method('DELETE')
                                <button
                                    type="submit"
                                    class="btn-icon-aksi btn-icon-danger"
                                    title="Hapus layanan"
                                    onclick="return confirm('Hapus layanan {{ addslashes($l->nama) }}? Tindakan tidak dapat dibatalkan.')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="td-empty">
                        <i class="bi bi-wrench" style="font-size:28px;opacity:0.3;display:block;margin-bottom:8px;"></i>
                        Belum ada layanan. Klik "+ Tambah Layanan" untuk memulai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($layanan) && $layanan->hasPages())
    <div class="pagination-wrap">
        {{ $layanan->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- ===== MODAL TAMBAH / EDIT ===== --}}
<div class="modal-backdrop" id="modalBackdrop" style="display:none;">
    <div class="modal-box" id="modalBox">
        <div class="modal-header">
            <div class="modal-header-icon"><i class="bi bi-wrench-adjustable"></i></div>
            <div>
                <div class="modal-title" id="modalTitle">Tambah Layanan</div>
                <div class="modal-sub">Isi detail layanan yang akan ditambahkan</div>
            </div>
            <button class="modal-close" onclick="closeModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="modalForm" method="POST" novalidate>
            @csrf
            <span id="methodField"></span>
            <input type="hidden" id="modalLayananId" name="layanan_id" value="">

            {{-- Nama --}}
            <div class="fgroup">
                <label class="flabel" for="mNama">
                    Nama Layanan <span class="freq">*</span>
                </label>
                <input
                    type="text"
                    id="mNama"
                    name="nama"
                    class="finput"
                    placeholder="cth: Ganti Oli Mesin"
                    required
                >
            </div>

            {{-- Harga + Durasi --}}
            <div class="frow-2">
                <div class="fgroup">
                    <label class="flabel" for="mHarga">
                        Harga Dasar <span class="freq">*</span>
                    </label>
                    <div class="input-prefix-wrap">
                        <span class="input-prefix">Rp</span>
                        <input
                            type="number"
                            id="mHarga"
                            name="harga_dasar"
                            class="finput"
                            placeholder="85000"
                            min="0"
                            required
                        >
                    </div>
                    <p class="fhelper">*Harga dapat berubah tergantung kondisi kendaraan</p>
                </div>
                <div class="fgroup">
                    <label class="flabel" for="mDurasi">
                        Durasi <span class="freq">*</span>
                    </label>
                    <div class="input-suffix-wrap">
                        <input
                            type="number"
                            id="mDurasi"
                            name="durasi"
                            class="finput"
                            placeholder="30"
                            min="5"
                            required
                        >
                        <span class="input-suffix">menit</span>
                    </div>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="fgroup">
                <label class="flabel" for="mDesc">Deskripsi</label>
                <textarea
                    id="mDesc"
                    name="deskripsi"
                    class="finput"
                    rows="3"
                    placeholder="Jelaskan apa saja yang termasuk dalam layanan ini..."
                ></textarea>
            </div>

            {{-- Status --}}
            <div class="fgroup">
                <label class="flabel">Status</label>
                <div class="modal-toggle-wrap" id="mToggleWrap">
                    <div class="toggle-track on" id="mToggleTrack">
                        <div class="toggle-thumb"></div>
                    </div>
                    <div>
                        <div class="toggle-lbl" id="mToggleLbl">Aktif</div>
                        <div class="fhelper" style="margin-top:0;">Layanan tersedia untuk dipilih pelanggan</div>
                    </div>
                </div>
                <input type="hidden" name="status" id="mStatusInput" value="aktif">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-primary">
                    <i class="bi bi-floppy"></i>
                    <span id="modalSaveText">Simpan Layanan</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Pass data layanan ke JS untuk filter client-side --}}
<script>
window.layananMeta = @json($layanans ?? []);
</script>

@endsection

@push('scripts')
<script src="{{ asset('js/admin-layanan.js') }}"></script>
@endpush