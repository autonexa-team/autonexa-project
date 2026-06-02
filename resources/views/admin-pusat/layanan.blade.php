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
        <p class="page-sub">Kelola layanan yang ditawarkan di semua bengkel Autonexa</p>
    </div>
    <button type="button" class="btn-primary" id="btnTambah">
        <i class="bi bi-plus-lg"></i> Tambah Layanan
    </button>
</div>

{{-- ===== STAT CARDS ===== --}}
<div class="stat-grid-3">
    <div class="stat-card">
        <div class="stat-icon-wrap si-orange">
            <i class="bi bi-wrench-adjustable"></i>
        </div>
        <div class="stat-body">
            <div class="stat-label">Total Layanan</div>
            <div class="stat-value">{{ $layanans->count() }}</div>
            <div class="stat-sub">Aktif &amp; nonaktif</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap si-green">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="stat-body">
            <div class="stat-label">Layanan Aktif</div>
            <div class="stat-value" style="color:#16a34a;">
                {{ $layanans->where('status', 'aktif')->count() }}
            </div>
            <div class="stat-sub">Siap digunakan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap si-gray">
            <i class="bi bi-pause-circle"></i>
        </div>
        <div class="stat-body">
            <div class="stat-label">Layanan Nonaktif</div>
            <div class="stat-value" style="color:#6b7280;">
                {{ $layanans->where('status', 'nonaktif')->count() }}
            </div>
            <div class="stat-sub">Sedang dinonaktifkan</div>
        </div>
    </div>
</div>

{{-- ===== FLASH MESSAGE ===== --}}
@if(session('success'))
<div class="flash-success">
    <i class="bi bi-check-circle-fill"></i>
    <span>{{ session('success') }}</span>
    <button onclick="this.parentElement.remove()" class="flash-close">
        <i class="bi bi-x-lg"></i>
    </button>
</div>
@endif

@if(session('error'))
<div class="flash-error">
    <i class="bi bi-exclamation-circle-fill"></i>
    <span>{{ session('error') }}</span>
    <button onclick="this.parentElement.remove()" class="flash-close">
        <i class="bi bi-x-lg"></i>
    </button>
</div>
@endif

{{-- ===== TOOLBAR ===== --}}
<div class="toolbar">
    <div class="search-wrap">
        <i class="bi bi-search search-icon"></i>
        <input
            type="text"
            id="searchInput"
            class="search-input"
            placeholder="Cari nama layanan atau deskripsi..."
            autocomplete="off"
        >
    </div>
    <select class="filter-select" id="filterStatus">
        <option value="">Semua Status</option>
        <option value="aktif">Aktif</option>
        <option value="nonaktif">Nonaktif</option>
    </select>
    <span class="count-pill" id="countPill">
        {{ $layanans->count() }} layanan
    </span>
</div>

{{-- ===== TABLE CARD ===== --}}
<div class="table-card">
    <div class="table-card-header">
        <div class="table-card-title-wrap">
            <h3 class="table-card-title">Daftar Layanan</h3>
        </div>
    </div>

    <div class="table-responsive">
        <table class="admin-table" id="layananTable">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th style="width:220px;">Layanan</th>
                    <th>Deskripsi</th>
                    <th style="width:140px;">Harga Dasar</th>
                    <th style="width:110px;">Durasi</th>
                    <th style="width:100px;">Status</th>
                    <th style="width:220px; text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="layananBody">
                @forelse($layanans as $i => $layanan)
                <tr
                    class="layanan-row"
                    data-name="{{ strtolower($layanan->nama) }}"
                    data-desc="{{ strtolower($layanan->deskripsi ?? '') }}"
                    data-status="{{ $layanan->status }}"
                >
                    <td class="td-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</td>

                    {{-- Nama --}}
                    <td>
                        <div class="td-layanan-wrap">
                            <div class="layanan-icon-box">
                                <i class="bi bi-wrench-adjustable"></i>
                            </div>
                            <div>
                                <div class="layanan-name-text">{{ $layanan->nama }}</div>
                                <div class="layanan-name-id">
                                    LAY-{{ str_pad($layanan->id, 4, '0', STR_PAD_LEFT) }}
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- Deskripsi --}}
                    <td>
                        <span class="td-desc" title="{{ $layanan->deskripsi }}">
                            {{ Str::limit($layanan->deskripsi ?? '—', 55) }}
                        </span>
                    </td>

                    {{-- Harga --}}
                    <td>
                        <div class="td-harga">
                            Rp {{ number_format($layanan->harga ?? 0, 0, ',', '.') }}
                        </div>
                        <div class="td-harga-note">*estimasi</div>
                    </td>

                    {{-- Durasi --}}
                    <td>
                        <span class="td-durasi">
                            <i class="bi bi-clock"></i>
                            {{ $layanan->durasi ?? 0 }} menit
                        </span>
                    </td>

                    {{-- Status --}}
                    <td>
                        <span class="status-badge {{ $layanan->status === 'aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                            <span class="status-dot {{ $layanan->status === 'aktif' ? 'sd-aktif' : 'sd-nonaktif' }}"></span>
                            {{ $layanan->status === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>

                    {{-- Aksi --}}
                    <td>
                        <div class="aksi-wrap">
                            {{-- Edit --}}
                            <button
                                type="button"
                                class="btn-icon-aksi"
                                title="Edit layanan"
                                onclick="openModalEdit(
                                    {{ $layanan->id }},
                                    '{{ addslashes($layanan->nama) }}',
                                    '{{ addslashes($layanan->deskripsi ?? '') }}',
                                    {{ $layanan->harga ?? 0 }},
                                    {{ $layanan->durasi ?? 0 }},
                                    '{{ $layanan->status }}'
                                )">
                                <i class="bi bi-pencil"></i>
                            </button>

                            {{-- Toggle Status --}}
                            <form
                                action="{{ route('admin-pusat.layanan.toggle', $layanan->id) }}"
                                method="POST"
                                class="form-inline">
                                @csrf @method('PATCH')
                                <button
                                    type="submit"
                                    class="btn-toggle-status {{ $layanan->status === 'aktif' ? 'btn-to-nonaktif' : 'btn-to-aktif' }}"
                                    onclick="return confirm('{{ $layanan->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }} layanan ini?')">
                                    {{ $layanan->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            {{-- Hapus --}}
                            <form
                                action="{{ route('admin-pusat.layanan.destroy', $layanan->id) }}"
                                method="POST"
                                class="form-inline">
                                @csrf @method('DELETE')
                                <button
                                    type="submit"
                                    class="btn-icon-aksi btn-icon-danger"
                                    title="Hapus layanan"
                                    onclick="return confirm('Hapus layanan \'{{ addslashes($layanan->nama) }}\'? Tindakan tidak dapat dibatalkan.')">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="td-empty">
                        <i class="bi bi-wrench" style="font-size:28px;opacity:.3;display:block;margin-bottom:8px;"></i>
                        Belum ada layanan. Klik "+ Tambah Layanan" untuk memulai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Empty filter state --}}
        <div id="emptyFilter" style="display:none;" class="td-empty" style="padding:36px;">
            <i class="bi bi-search" style="font-size:28px;opacity:.3;display:block;margin-bottom:8px;"></i>
            Tidak ada layanan yang sesuai pencarian.
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════
     MODAL TAMBAH / EDIT
═══════════════════════════════════ --}}
<div class="modal-backdrop" id="modalBackdrop" style="display:none;">
    <div class="modal-box">

        <div class="modal-header">
            <div class="modal-header-icon">
                <i class="bi bi-wrench-adjustable"></i>
            </div>
            <div>
                <div class="modal-title" id="modalTitle">Tambah Layanan</div>
                <div class="modal-sub" id="modalSub">Isi detail layanan yang akan ditambahkan</div>
            </div>
            <button class="modal-close" onclick="closeModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="modalForm" method="POST" novalidate>
            @csrf
            <span id="methodField"></span>

            <div class="modal-body">

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

                {{-- Harga + Durasi --}}
                <div class="frow-2">
                    <div class="fgroup" style="margin-bottom:0;">
                        <label class="flabel" for="mHarga">
                            Harga Dasar <span class="freq">*</span>
                        </label>
                        <div class="input-prefix-wrap">
                            <span class="input-prefix">Rp</span>
                            <input
                                type="number"
                                id="mHarga"
                                name="harga"
                                class="finput"
                                placeholder="85000"
                                min="0"
                                required
                            >
                        </div>
                        <p class="fhelper">*Dapat berubah tergantung kondisi kendaraan</p>
                    </div>
                    <div class="fgroup" style="margin-bottom:0;">
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
                                min="1"
                                required
                            >
                            <span class="input-suffix">menit</span>
                        </div>
                    </div>
                </div>

                {{-- Status Toggle --}}
                <div class="fgroup" style="margin-top:14px; margin-bottom:0;">
                    <label class="flabel">Status</label>
                    <div class="modal-toggle-wrap" id="mToggleWrap">
                        <div class="toggle-track on" id="mToggleTrack">
                            <div class="toggle-thumb"></div>
                        </div>
                        <div>
                            <div class="toggle-lbl" id="mToggleLbl">Aktif</div>
                            <div class="fhelper" style="margin:0;">
                                Layanan tersedia untuk dipilih pelanggan
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="status" id="mStatusInput" value="aktif">
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal()">
                    Batal
                </button>
                <button type="submit" class="btn-primary">
                    <i class="bi bi-floppy"></i>
                    <span id="modalSaveText">Simpan Layanan</span>
                </button>
            </div>
        </form>

    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/admin-layanan.js') }}"></script>
@endpush