{{-- resources/views/admin-pusat/sparepart.blade.php --}}
@extends('layout.admin')

@section('title', 'Manajemen Sparepart')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-sparepart.css') }}">
@endpush

@section('content')

{{-- Base URL untuk admin-sparepart.js (jangan hapus) --}}
<input type="hidden" id="sparepartBaseUrl" value="{{ url('admin-pusat/sparepart') }}">

{{-- ───────────────────────────────── HEADER --}}
<div class="sp-header">
    <div class="sp-header-left">
        <h1 class="sp-title">Manajemen Sparepart</h1>
        <p class="sp-sub">Kelola data master sparepart untuk seluruh jaringan bengkel</p>
    </div>
    <div class="sp-header-actions">
        <button class="btn-tambah" id="btnTambah">
            <i class="bi bi-plus-lg"></i>
            Tambah Sparepart
        </button>
    </div>
</div>

{{-- ───────────────────────────────── STAT STRIP --}}
<div class="sp-stat-strip">
    <div class="sp-stat-item">
        <div class="sp-stat-icon si-orange">
            <i class="bi bi-wrench-adjustable"></i>
        </div>
        <div>
            <div class="sp-stat-num">{{ $totalSparepart }}</div>
            <div class="sp-stat-lbl">Total Sparepart</div>
        </div>
    </div>
    <div class="sp-stat-divider"></div>
    <div class="sp-stat-item">
        <div class="sp-stat-icon si-green">
            <i class="bi bi-currency-dollar"></i>
        </div>
        <div>
            <div class="sp-stat-num">Rp {{ number_format($avgHarga, 0, ',', '.') }}</div>
            <div class="sp-stat-lbl">Rata-rata Harga</div>
        </div>
    </div>
    <div class="sp-stat-divider"></div>
    <div class="sp-stat-item">
        <div class="sp-stat-icon si-blue">
            <i class="bi bi-shop"></i>
        </div>
        <div>
            <div class="sp-stat-num">{{ $spareparts->sum('bengkel') }}</div>
            <div class="sp-stat-lbl">Total Penggunaan</div>
        </div>
    </div>
    <div class="sp-stat-divider"></div>
    <div class="sp-stat-item">
        <div class="sp-stat-icon si-purple">
            <i class="bi bi-star-fill"></i>
        </div>
        <div>
            <div class="sp-stat-num">{{ $maxBengkel }}</div>
            <div class="sp-stat-lbl">Penggunaan Tertinggi</div>
        </div>
    </div>
</div>

{{-- ───────────────────────────────── TABLE CARD --}}
<div class="sp-card">

    {{-- Search & Filter Bar --}}
    <div class="sp-toolbar">
        <form method="GET" action="{{ route('admin-pusat.sparepart') }}" class="sp-search-form">
            <div class="sp-search-wrap">
                <i class="bi bi-search sp-search-icon"></i>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="sp-search"
                    placeholder="Cari sparepart..."
                    autocomplete="off"
                    oninput="debouncedSubmit()"
                >

                @if(request('search'))
                    <a href="{{ route('admin-pusat.sparepart') }}" class="sp-search-clear">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
        <div class="sp-toolbar-right">
            <span class="sp-count-label" id="countLabel">
                Menampilkan <strong>{{ $totalSparepart }}</strong> sparepart
            </span>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="admin-table" id="sparepartTable">
            <thead>
                <tr>
                    <th style="width:44px;">#</th>
                    <th>Nama Sparepart</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th style="text-align:center;">Dipakai Bengkel</th>
                    <th style="width:120px; text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="sparepartBody">
                @foreach($spareparts as $i => $sp)
                <tr class="sp-row" data-name="{{ strtolower($sp->nama) }}" data-desc="{{ strtolower($sp->deskripsi ?? '') }}">

                    {{-- No --}}
                    <td class="td-mono">{{ $spareparts->firstItem() + $i }}</td>

                    {{-- Nama --}}
                    <td>
                        <div class="td-sp-name">
                            <div class="sp-name-icon">
                                <i class="bi bi-gear-fill"></i>
                            </div>
                            <div>
                                <div class="sp-name-text">{{ $sp->nama }}</div>
                                <div class="sp-name-id">SP-{{ str_pad($sp->id, 4, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Harga --}}
                    <td>
                        <span class="td-harga">Rp {{ number_format($sp->harga, 0, ',', '.') }}</span>
                    </td>

                    {{-- Deskripsi --}}
                    <td>
                        <span class="td-desc">{{ $sp->deskripsi ?? '-' }}</span>
                    </td>

                    {{-- Bengkel usage --}}
                    <td style="text-align:center;">
                        <div class="td-bengkel-usage">
                            <span class="usage-num">{{ $sp->bengkels_count ?? 0 }}</span>
                            <div class="usage-bar-track">
                                <div class="usage-bar-fill"
                                     style="width: {{ $maxBengkel > 0 ? round(($sp->bengkels_count ?? 0) / $maxBengkel) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- Aksi --}}
                    <td>
                        <div class="td-aksi">
                            <a href="#"
                               class="btn-edit"
                               title="Edit"
                               data-id="{{ $sp->id }}"
                               data-nama="{{ $sp->nama }}"
                               data-harga="{{ $sp->harga }}"
                               data-deskripsi="{{ $sp->deskripsi ?? '' }}">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn-hapus"
                                    type="button"
                                    title="Hapus"
                                    data-id="{{ $sp->id }}"
                                    data-nama="{{ $sp->nama }}">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Empty state --}}
        <div class="sp-empty" id="emptyState" style="display:none;">
            <div class="sp-empty-icon"><i class="bi bi-search"></i></div>
            <div class="sp-empty-title">Tidak ditemukan</div>
            <div class="sp-empty-sub">Coba kata kunci lain</div>
        </div>
    </div>
    
    {{-- Pagination --}}
    @if($spareparts instanceof \Illuminate\Pagination\LengthAwarePaginator && $spareparts->hasPages())
    <div class="sp-pagination-wrap">

        {{-- Info kiri --}}
        <div class="sp-pagination-info">
            Menampilkan
            <strong>{{ $spareparts->firstItem() }}</strong>–<strong>{{ $spareparts->lastItem() }}</strong>
            dari <strong>{{ $spareparts->total() }}</strong> sparepart
        </div>

        {{-- Tombol navigasi --}}
        <div class="sp-pagination-controls">
            {{-- Pertama --}}
            @if($spareparts->onFirstPage())
                <button class="sp-page-btn sp-page-disabled" disabled>
                    <i class="bi bi-chevron-double-left"></i>
                </button>
            @else
                <a href="{{ $spareparts->url(1) }}" class="sp-page-btn">
                    <i class="bi bi-chevron-double-left"></i>
                </a>
            @endif

            {{-- Sebelumnya --}}
            @if($spareparts->onFirstPage())
                <button class="sp-page-btn sp-page-disabled" disabled>
                    <i class="bi bi-chevron-left"></i>
                </button>
            @else
                <a href="{{ $spareparts->previousPageUrl() }}" class="sp-page-btn">
                    <i class="bi bi-chevron-left"></i>
                </a>
            @endif

            {{-- Nomor halaman --}}
            @php
                $start = max(1, $spareparts->currentPage() - 2);
                $end   = min($spareparts->lastPage(), $spareparts->currentPage() + 2);
            @endphp

            @if($start > 1)
                <a href="{{ $spareparts->url(1) }}" class="sp-page-btn">1</a>
                @if($start > 2)
                    <span class="sp-page-ellipsis">...</span>
                @endif
            @endif

            @for($page = $start; $page <= $end; $page++)
                @if($page == $spareparts->currentPage())
                    <button class="sp-page-btn sp-page-active" disabled>{{ $page }}</button>
                @else
                    <a href="{{ $spareparts->url($page) }}" class="sp-page-btn">{{ $page }}</a>
                @endif
            @endfor

            @if($end < $spareparts->lastPage())
                @if($end < $spareparts->lastPage() - 1)
                    <span class="sp-page-ellipsis">...</span>
                @endif
                <a href="{{ $spareparts->url($spareparts->lastPage()) }}" class="sp-page-btn">
                    {{ $spareparts->lastPage() }}
                </a>
            @endif

            {{-- Selanjutnya --}}
            @if($spareparts->hasMorePages())
                <a href="{{ $spareparts->nextPageUrl() }}" class="sp-page-btn">
                    <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <button class="sp-page-btn sp-page-disabled" disabled>
                    <i class="bi bi-chevron-right"></i>
                </button>
            @endif

            {{-- Terakhir --}}
            @if($spareparts->hasMorePages())
                <a href="{{ $spareparts->url($spareparts->lastPage()) }}" class="sp-page-btn">
                    <i class="bi bi-chevron-double-right"></i>
                </a>
            @else
                <button class="sp-page-btn sp-page-disabled" disabled>
                    <i class="bi bi-chevron-double-right"></i>
                </button>
            @endif

        </div>

        {{-- Info kanan --}}
        <div class="sp-pagination-pages">
            Hal <strong>{{ $spareparts->currentPage() }}</strong>
            / <strong>{{ $spareparts->lastPage() }}</strong>
        </div>

    </div>
    @endif

</div>


{{-- ═══════════════════ MODAL TAMBAH ═══════════════════ --}}
<div class="sp-modal-overlay" id="modalTambah">
    <div class="sp-modal">
        <div class="sp-modal-header">
            <div>
                <h2 class="sp-modal-title">Tambah Sparepart</h2>
                <p class="sp-modal-sub">Isi data master sparepart baru</p>
            </div>
            <button class="sp-modal-close" onclick="closeModal('modalTambah')">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="sp-modal-body">
            <form action="{{ route('admin-pusat.sparepart.store') }}" method="POST" id="formTambah">
                @csrf
                <div class="sp-form-group">
                    <label class="sp-label">Nama Sparepart <span class="required">*</span></label>
                    <input type="text" name="nama" class="sp-input" placeholder="cth: Oli Mesin 10W-40" required>
                </div>
                <div class="sp-form-group">
                    <label class="sp-label">Harga <span class="required">*</span></label>
                    <div class="sp-input-prefix-wrap">
                        <span class="sp-input-prefix">Rp</span>
                        <input type="number" name="harga" class="sp-input sp-input-prefix-pad"
                               placeholder="0" min="0" step="500" required>
                    </div>
                </div>
                <div class="sp-form-group">
                    <label class="sp-label">Deskripsi</label>
                    <textarea name="deskripsi" class="sp-textarea" rows="3"
                              placeholder="Deskripsi singkat tentang sparepart ini..."></textarea>
                </div>
                <div class="sp-modal-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeModal('modalTambah')">Batal</button>
                    <button type="submit" class="btn-modal-save">
                        <i class="bi bi-check-lg"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════ MODAL EDIT ═══════════════════ --}}
<div class="sp-modal-overlay" id="modalEdit">
    <div class="sp-modal">
        <div class="sp-modal-header">
            <div>
                <h2 class="sp-modal-title">Edit Sparepart</h2>
                <p class="sp-modal-sub">Perbarui data master sparepart</p>
            </div>
            <button class="sp-modal-close" onclick="closeModal('modalEdit')">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="sp-modal-body">
            <form action="" method="POST" id="formEdit">
                @csrf
                @method('PUT')
                <div class="sp-form-group">
                    <label class="sp-label">Nama Sparepart <span class="required">*</span></label>
                    <input type="text" name="nama" id="editNama" class="sp-input" required>
                </div>
                <div class="sp-form-group">
                    <label class="sp-label">Harga <span class="required">*</span></label>
                    <div class="sp-input-prefix-wrap">
                        <span class="sp-input-prefix">Rp</span>
                        <input type="number" name="harga" id="editHarga" class="sp-input sp-input-prefix-pad"
                               min="0" step="500" required>
                    </div>
                </div>
                <div class="sp-form-group">
                    <label class="sp-label">Deskripsi</label>
                    <textarea name="deskripsi" id="editDeskripsi" class="sp-textarea" rows="3"></textarea>
                </div>
                <div class="sp-modal-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeModal('modalEdit')">Batal</button>
                    <button type="submit" class="btn-modal-save">
                        <i class="bi bi-check-lg"></i> Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════ MODAL HAPUS ═══════════════════ --}}
<div class="sp-modal-overlay" id="modalHapus">
    <div class="sp-modal sp-modal-sm">
        <div class="sp-modal-header">
            <div>
                <h2 class="sp-modal-title">Hapus Sparepart?</h2>
                <p class="sp-modal-sub" id="hapusLabel">Tindakan ini tidak dapat dibatalkan</p>
            </div>
            <button class="sp-modal-close" onclick="closeModal('modalHapus')">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="sp-modal-body sp-hapus-body">
            <div class="sp-hapus-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <p class="sp-hapus-msg">
                Anda akan menghapus sparepart <strong id="hapusNama">-</strong>.
                Data yang terhapus tidak dapat dikembalikan.
            </p>
        </div>
        <div class="sp-modal-footer">
            <button type="button" class="btn-modal-cancel" onclick="closeModal('modalHapus')">Batal</button>
            <form action="" method="POST" id="formHapus" style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-modal-hapus">
                    <i class="bi bi-trash3"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>

@endsection



@push('scripts')
<script>
// ── LIVE SEARCH ───────────────────────────────────────

let searchTimer;

function debouncedSubmit() {
    clearTimeout(searchTimer);

    searchTimer = setTimeout(() => {
        document.querySelector('.sp-search-form').submit();
    }, 400);
}
    
// ── MODAL HELPERS ────────────────────────────────────────
function openModal(id) {
    const el = document.getElementById(id);
    el.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    const el = document.getElementById(id);
    el.classList.remove('show');
    document.body.style.overflow = '';
}

document.querySelectorAll('.sp-modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function (e) {
        if (e.target === this) closeModal(this.id);
    });
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.sp-modal-overlay.show')
            .forEach(m => closeModal(m.id));
    }
});

document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function (e) {
        e.preventDefault();

        openEditModal(
            this.dataset.id,
            this.dataset.nama,
            this.dataset.harga,
            this.dataset.deskripsi
        );
    });
});

// ── MODAL HAPUS ──────────────────────────────────────────
document.querySelectorAll('.btn-hapus').forEach(btn => {
    btn.addEventListener('click', function () {
        confirmDelete(this.dataset.id, this.dataset.nama);
    });
});

// ── MODAL TAMBAH ─────────────────────────────────────────
document.getElementById('btnTambah').addEventListener('click', () => openModal('modalTambah'));

// ── MODAL EDIT ───────────────────────────────────────────
function openEditModal(id, nama, harga, deskripsi) {
    document.getElementById('editNama').value      = nama;
    document.getElementById('editHarga').value     = harga;
    document.getElementById('editDeskripsi').value = deskripsi;

    const baseUrl = document.getElementById('sparepartBaseUrl').value;

    document.getElementById('formEdit').action = baseUrl + '/' + id;

    openModal('modalEdit');
}

// ── MODAL HAPUS ──────────────────────────────────────────
function confirmDelete(id, nama) {
    document.getElementById('hapusNama').textContent = nama;
    const baseUrl = document.getElementById('sparepartBaseUrl').value;
    document.getElementById('formHapus').action = baseUrl + '/' + id;
    openModal('modalHapus');
}
</script>
@endpush