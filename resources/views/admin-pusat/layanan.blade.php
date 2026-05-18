{{-- resources/views/admin-pusat/layanan.blade.php --}}
@extends('layout.admin')
@section('title', 'Manajemen Layanan')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-layanan.css') }}">
@endpush

@section('content')

{{-- Base URL untuk navigasi (jangan hapus) --}}
<input type="hidden" id="layananBaseUrl" value="{{ url('admin-pusat/layanan') }}">

{{-- ===== PAGE HEADER ===== --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Layanan</h1>
        <p class="page-sub">Kelola layanan yang ditawarkan di semua bengkel Autonexa</p>
    </div>
    <button type="button" class="btn-add" id="btnTambahLayanan" onclick="openModalTambah()">
        <i class="bi bi-plus-lg"></i> Tambah Layanan
    </button>
</div>

{{-- ===== STATS CARDS ===== --}}
<div class="stat-grid stat-grid-3">
    <div class="stat-card">
        <div class="stat-icon-wrap si-orange"><i class="bi bi-tools"></i></div>
        <div class="stat-body">
            <div class="stat-label">Total Layanan</div>
            <div class="stat-value">{{ count($layanans) }}</div>
            <div class="stat-trend trend-neutral">Layanan aktif & nonaktif</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap si-green"><i class="bi bi-check-circle"></i></div>
        <div class="stat-body">
            <div class="stat-label">Layanan Aktif</div>
            <div class="stat-value">{{ $layanans->where('status', 'aktif')->count() }}</div>
            <div class="stat-trend trend-success">Siap digunakan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap si-red"><i class="bi bi-x-circle"></i></div>
        <div class="stat-body">
            <div class="stat-label">Layanan Nonaktif</div>
            <div class="stat-value">{{ $layanans->where('status', 'nonaktif')->count() }}</div>
            <div class="stat-trend trend-warning">Sedang dinonaktifkan</div>
        </div>
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
<div class="alert-success" style="margin-bottom:20px;">
    <i class="bi bi-check-circle-fill"></i>
    <div>
        <strong>Berhasil!</strong>
        <p>{{ session('success') }}</p>
    </div>
    <button onclick="this.parentElement.remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;font-size:16px;">×</button>
</div>
@endif

{{-- ===== TABLE CARD ===== --}}
<div class="table-card">
    {{-- Search & Filter Bar --}}
    <div class="toolbar">
        <div class="search-wrap">
            <i class="bi bi-search search-icon"></i>
            <input
                type="text"
                id="searchInput"
                class="search-input"
                placeholder="Cari nama layanan..."
                autocomplete="off"
            >
        </div>
        <span class="count-label" id="countLabel">
            Menampilkan <strong>{{ count($layanans) }}</strong> layanan
        </span>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="admin-table" id="layananTable">
            <thead>
                <tr>
                    <th style="width:44px;">#</th>
                    <th>Nama Layanan</th>
                    <th>Deskripsi</th>
                    <th>Harga Dasar</th>
                    <th>Durasi</th>
                    <th>Status</th>
                    <th style="width:120px; text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="layananBody">
                @foreach($layanans as $i => $layanan)
                <tr class="layanan-row" data-name="{{ strtolower($layanan->nama) }}" data-desc="{{ strtolower($layanan->deskripsi ?? '') }}">
                    {{-- No --}}
                    <td class="td-mono">{{ $i + 1 }}</td>

                    {{-- Nama --}}
                    <td>
                        <div class="td-layanan-name">
                            <div class="layanan-name-icon">
                                <i class="bi bi-wrench"></i>
                            </div>
                            <div>
                                <div class="layanan-name-text">{{ $layanan->nama }}</div>
                                <div class="layanan-name-id">LAY-{{ str_pad($layanan->id, 4, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Deskripsi --}}
                    <td>
                        <span class="td-desc">{{ Str::limit($layanan->deskripsi ?? '-', 40) }}</span>
                    </td>

                    {{-- Harga --}}
                    <td>
                        <span class="td-harga">Rp {{ number_format($layanan->harga ?? 0, 0, ',', '.') }}</span>
                    </td>

                    {{-- Durasi --}}
                    <td>
                        <span class="td-durasi">{{ $layanan->durasi ?? 0 }} menit</span>
                    </td>

                    {{-- Status --}}
                    <td>
                        <span class="badge badge-{{ $layanan->status ?? 'pending' }}">
                            {{ ucfirst($layanan->status ?? 'pending') }}
                        </span>
                    </td>

                    {{-- Aksi --}}
                    <td>
                        <div class="td-aksi">
                            <a href="#"
                               class="btn-edit"
                               title="Edit"
                               data-id="{{ $layanan->id }}"
                               data-nama="{{ $layanan->nama }}"
                               data-harga="{{ $layanan->harga }}"
                               data-durasi="{{ $layanan->durasi }}"
                               data-deskripsi="{{ $layanan->deskripsi ?? '' }}"
                               onclick="editLayanan(event)">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn-toggle"
                                    type="button"
                                    title="Toggle Status"
                                    data-id="{{ $layanan->id }}"
                                    onclick="toggleStatusLayanan({{ $layanan->id }})">
                                <i class="bi bi-arrow-left-right"></i>
                            </button>
                            <button class="btn-hapus"
                                    type="button"
                                    title="Hapus"
                                    data-id="{{ $layanan->id }}"
                                    data-nama="{{ $layanan->nama }}"
                                    onclick="deleteLayanan({{ $layanan->id }})">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Empty state --}}
        <div class="empty-state" id="emptyState" style="display:none;">
            <div class="empty-icon"><i class="bi bi-search"></i></div>
            <div class="empty-title">Tidak ditemukan</div>
            <div class="empty-sub">Coba kata kunci lain</div>
        </div>
    </div>
</div>

{{-- ═══════════════════ MODAL FORM ═══════════════════ --}}
<div class="modal-overlay" id="modalForm" onclick="if(event.target === this) closeModal()">
    <div class="modal-dialog">
        <div class="modal-header">
            <div>
                <h2 class="modal-title" id="modalTitle">Tambah Layanan</h2>
                <p class="modal-sub">Isi data layanan baru</p>
            </div>
            <button class="modal-close" onclick="closeModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="formLayanan" method="POST" action="">
            @csrf
            <input type="hidden" id="methodField" name="_method" value="POST">

            <div class="modal-body">
                <div class="form-group">
                    <label for="nama" class="form-label">
                        Nama Layanan <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="nama" name="nama" class="form-control" 
                           placeholder="cth: Ganti Oli, Tune Up" required>
                </div>

                <div class="form-group">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3"
                              placeholder="Deskripsi singkat layanan..."></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="harga" class="form-label">
                            Harga Dasar (Rp) <span class="text-danger">*</span>
                        </label>
                        <input type="number" id="harga" name="harga" class="form-control" 
                               placeholder="50000" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="durasi" class="form-label">
                            Durasi (menit) <span class="text-danger">*</span>
                        </label>
                        <input type="number" id="durasi" name="durasi" class="form-control" 
                               placeholder="30" min="1" required>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-floppy"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.stat-grid-3 {
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
}

.stat-card {
    display: flex;
    gap: 16px;
    padding: 16px;
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    transition: all 0.2s;
}

.stat-card:hover {
    border-color: #999;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.stat-icon-wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 8px;
    flex-shrink: 0;
    font-size: 24px;
}

.si-orange {
    background-color: #ffe8d6;
    color: #ff6a00;
}

.si-green {
    background-color: #d4edda;
    color: #28a745;
}

.si-red {
    background-color: #f8d7da;
    color: #dc3545;
}

.si-blue {
    background-color: #d1ecf1;
    color: #0c5460;
}

.stat-body {
    flex: 1;
}

.stat-label {
    font-size: 12px;
    color: #999;
    text-transform: uppercase;
    font-weight: 600;
    margin-bottom: 4px;
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #333;
}

.stat-trend {
    font-size: 12px;
    color: #666;
    margin-top: 4px;
}

.trend-neutral {
    color: #666;
}

.trend-success {
    color: #28a745;
}

.trend-warning {
    color: #ff9800;
}

.table-card {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
}

.toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    padding: 16px;
    border-bottom: 1px solid #e0e0e0;
}

.search-wrap {
    flex: 1;
    position: relative;
    max-width: 300px;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
}

.search-input {
    width: 100%;
    padding: 8px 12px 8px 36px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.search-input:focus {
    outline: none;
    border-color: #0066cc;
    box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
}

.count-label {
    font-size: 12px;
    color: #666;
    white-space: nowrap;
}

.td-layanan-name {
    display: flex;
    gap: 12px;
    align-items: center;
}

.layanan-name-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: #f5f5f5;
    border-radius: 6px;
    color: #ff6a00;
    flex-shrink: 0;
}

.layanan-name-text {
    font-weight: 600;
    color: #333;
}

.layanan-name-id {
    font-size: 12px;
    color: #999;
}

.td-desc {
    color: #666;
    font-size: 13px;
}

.td-harga {
    color: #28a745;
    font-weight: 600;
}

.td-durasi {
    color: #0066cc;
    font-weight: 500;
}

.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.badge-aktif {
    background-color: #d4edda;
    color: #155724;
}

.badge-nonaktif {
    background-color: #f8d7da;
    color: #721c24;
}

.badge-pending {
    background-color: #fff3cd;
    color: #856404;
}

.td-aksi {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.btn-edit, .btn-toggle, .btn-hapus {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
    background: #f5f5f5;
    color: #666;
    font-size: 14px;
}

.btn-edit:hover {
    border-color: #0066cc;
    background: #e3f2fd;
    color: #0066cc;
}

.btn-toggle:hover {
    border-color: #ff9800;
    background: #fff3e0;
    color: #ff9800;
}

.btn-hapus:hover {
    border-color: #dc3545;
    background: #ffe3e3;
    color: #dc3545;
}

.empty-state {
    padding: 40px 20px;
    text-align: center;
    color: #999;
}

.empty-icon {
    font-size: 48px;
    color: #ddd;
    margin-bottom: 12px;
}

.empty-title {
    font-weight: 600;
    margin-bottom: 4px;
}

.empty-sub {
    font-size: 13px;
}

.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-overlay.active {
    display: flex;
}

.modal-dialog {
    background: white;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 20px;
    border-bottom: 1px solid #e0e0e0;
}

.modal-header > div {
    flex: 1;
}

.modal-title {
    margin: 0;
    font-size: 18px;
    color: #333;
}

.modal-sub {
    margin: 4px 0 0 0;
    font-size: 13px;
    color: #666;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #999;
    padding: 0;
    margin-left: 12px;
}

.modal-close:hover {
    color: #333;
}

.modal-body {
    padding: 20px;
}

.form-group {
    margin-bottom: 16px;
}

.form-label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    font-size: 14px;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    font-family: inherit;
}

.form-control:focus {
    outline: none;
    border-color: #0066cc;
    box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    padding: 16px 20px;
    border-top: 1px solid #e0e0e0;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-primary {
    background-color: #0066cc;
    color: white;
}

.btn-primary:hover {
    background-color: #0052a3;
}

.btn-secondary {
    background-color: #f5f5f5;
    color: #333;
    border: 1px solid #ddd;
}

.btn-secondary:hover {
    background-color: #e0e0e0;
}

.text-danger {
    color: #dc3545;
}

.alert-success {
    display: flex;
    gap: 12px;
    padding: 12px 16px;
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
    border-radius: 4px;
    color: #155724;
}

.alert-success i {
    flex-shrink: 0;
    margin-top: 2px;
}

.alert-success strong {
    display: block;
}

.alert-success p {
    margin: 4px 0 0 0;
    font-size: 14px;
}
</style>

<script>
function openModalTambah() {
    document.getElementById('modalTitle').textContent = 'Tambah Layanan';
    document.getElementById('modalTitle').nextElementSibling.textContent = 'Isi data layanan baru';
    document.getElementById('methodField').value = 'POST';
    document.getElementById('formLayanan').action = '{{ route("admin-pusat.layanan.store") }}';
    document.getElementById('formLayanan').reset();
    document.getElementById('modalForm').classList.add('active');
}

function editLayanan(e) {
    e.preventDefault();
    const btn = e.target.closest('.btn-edit');
    const id = btn.dataset.id;
    const nama = btn.dataset.nama;
    const harga = btn.dataset.harga;
    const durasi = btn.dataset.durasi;
    const deskripsi = btn.dataset.deskripsi;

    document.getElementById('modalTitle').textContent = 'Edit Layanan';
    document.getElementById('modalTitle').nextElementSibling.textContent = 'Perbarui data layanan';
    document.getElementById('methodField').value = 'PUT';
    document.getElementById('formLayanan').action = `{{ url('admin-pusat/layanan') }}/${id}`;
    document.getElementById('nama').value = nama;
    document.getElementById('harga').value = harga;
    document.getElementById('durasi').value = durasi;
    document.getElementById('deskripsi').value = deskripsi;
    document.getElementById('modalForm').classList.add('active');
}

function deleteLayanan(id) {
    if (!confirm('Hapus layanan ini?')) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ url('admin-pusat/layanan') }}/${id}`;
    form.innerHTML = `@csrf
@method('DELETE')`;
    document.body.appendChild(form);
    form.submit();
}

function toggleStatusLayanan(id) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ url('admin-pusat/layanan') }}/${id}/toggle`;
    form.innerHTML = `@csrf
@method('PATCH')`;
    document.body.appendChild(form);
    form.submit();
}

function closeModal() {
    document.getElementById('modalForm').classList.remove('active');
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    const rows = document.querySelectorAll('.layanan-row');
    let visible = 0;

    rows.forEach(row => {
        const name = row.dataset.name;
        const desc = row.dataset.desc;
        const match = name.includes(query) || desc.includes(query);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    const emptyState = document.getElementById('emptyState');
    emptyState.style.display = visible === 0 ? 'block' : 'none';
    document.getElementById('countLabel').innerHTML = 
        `Menampilkan <strong>${visible}</strong> layanan`;
});

document.getElementById('formLayanan').addEventListener('submit', function(e) {
    e.preventDefault();
    this.submit();
});
</script>

@endsection
