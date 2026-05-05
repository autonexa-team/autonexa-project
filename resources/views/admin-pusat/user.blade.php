{{-- resources/views/admin-pusat/user.blade.php --}}
@extends('layout.admin')
@section('title', 'Manajemen User')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-user.css') }}">
@endpush

@section('content')

{{-- ===== PAGE HEADER ===== --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen User</h1>
        <p class="page-sub">Kelola admin cabang bengkel Autonexa</p>
    </div>
    <button class="btn-primary" id="btnTambah">
        <i class="bi bi-person-plus"></i> Tambah Admin Cabang
    </button>
</div>

{{-- ===== STAT CARDS ===== --}}
<div class="stat-grid stat-grid-4">
    <div class="stat-card">
        <div class="stat-icon-wrap si-orange"><i class="bi bi-people"></i></div>
        <div class="stat-body">
            <div class="stat-label">Total Admin Cabang</div>
            <div class="stat-value">{{ $totalAdmin }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap si-green"><i class="bi bi-person-check"></i></div>
        <div class="stat-body">
            <div class="stat-label">Admin Aktif (Bengkel Aktif)</div>
            <div class="stat-value" style="color:#16a34a;">{{ $totalAktif }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap si-amber"><i class="bi bi-person-exclamation"></i></div>
        <div class="stat-body">
            <div class="stat-label">Belum Punya Bengkel</div>
            <div class="stat-value" style="color:#d97706;">{{ $totalBelum }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon-wrap si-blue"><i class="bi bi-building-check"></i></div>
        <div class="stat-body">
            <div class="stat-label">Sudah Ditugaskan</div>
            <div class="stat-value" style="color:#2563eb;">{{ $totalSudah }}</div>
        </div>
    </div>
</div>

{{-- ===== TOOLBAR ===== --}}
<div class="toolbar">
    <div class="search-wrap">
        <i class="bi bi-search search-icon"></i>
        <input type="text" class="search-input" id="searchInput"
               placeholder="Cari nama atau email...">
    </div>
    <select class="filter-select" id="filterStatus">
        <option value="">Semua Status</option>
        <option value="aktif">Aktif</option>
        <option value="nonaktif">Nonaktif</option>
    </select>
    <select class="filter-select" id="filterAssign">
        <option value="">Semua Assignment</option>
        <option value="punya">Sudah punya bengkel</option>
        <option value="belum">Belum punya bengkel</option>
    </select>
</div>

{{-- ===== TABEL USER ===== --}}
<div class="admin-table-card">
    <div class="table-card-header">
        <div class="table-card-title-wrap">
            <h3 class="table-card-title">Daftar Admin Cabang</h3>
            <span class="count-pill" id="countPill">{{ $users->total() }} admin</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="admin-table" id="userTable">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Assignment Bengkel</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                @forelse($users as $user)
                <tr
                    data-id="{{ $user->id }}"
                    data-nama="{{ strtolower($user->name) }}"
                    data-email="{{ strtolower($user->email) }}"
                    data-status="{{ $user->is_active ? 'aktif' : 'nonaktif' }}"
                    data-assign="{{ $user->bengkel ? 'punya' : 'belum' }}"
                >
                    <td>
                        <div class="td-user">
                            <div class="td-avatar">
                                {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $user->name)[1] ?? '', 0, 1)) }}
                            </div>
                            <div>
                                <div class="td-user-name">{{ $user->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="td-email-cell">{{ $user->email }}</td>
                    <td>
                        <span class="status-badge {{ $user->is_active ? 'badge-done' : 'badge-neutral' }}">
                            <span class="status-dot {{ $user->is_active ? 'sd-aktif' : 'sd-nonaktif' }}"></span>
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        @if($user->bengkel)
                            <span class="assign-badge assign-yes">
                                <i class="bi bi-check2"></i> Ditugaskan
                            </span>
                            <div class="assign-bengkel-name">
                                <i class="bi bi-shop"></i>
                                {{ $user->bengkel->nama }}
                            </div>
                        @else
                            <span class="assign-badge assign-no">
                                <i class="bi bi-dash"></i> Belum ditugaskan
                            </span>
                        @endif
                    </td>
                    <td class="td-date">
                        {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}
                    </td>
                    <td>
                        <div class="aksi-wrap">
                            {{-- Edit --}}
                            <button
                                class="btn-icon-aksi"
                                title="Edit"
                                onclick="editUser(
                                    {{ $user->id }},
                                    '{{ addslashes($user->name) }}',
                                    '{{ $user->email }}',
                                    {{ $user->is_active ? 'true' : 'false' }},
                                    {{ $user->bengkel ? $user->bengkel->id : 'null' }},
                                    '{{ addslashes($user->bengkel->nama ?? '') }}'
                                )">
                                <i class="bi bi-pencil"></i>
                            </button>

                            {{-- Hapus --}}
                            <form action="{{ route('admin-pusat.user.destroy', $user->id) }}"
                                  method="POST" class="form-inline">
                                @csrf @method('DELETE')
                                <button
                                    type="submit"
                                    class="btn-icon-aksi btn-icon-danger"
                                    title="Hapus"
                                    onclick="return confirm('Hapus admin {{ addslashes($user->name) }}? Bengkel yang dikelolanya akan kehilangan admin.')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="td-empty">
                        <i class="bi bi-people" style="font-size:28px;opacity:0.3;display:block;margin-bottom:8px;"></i>
                        Belum ada admin cabang. Klik "+ Tambah Admin Cabang" untuk memulai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="pagination-wrap">
        {{ $users->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- ===== MODAL TAMBAH / EDIT ===== --}}
<div class="modal-backdrop" id="modalBackdrop" style="display:none;">
    <div class="modal-box">

        <div class="modal-header">
            <div class="modal-header-icon"><i class="bi bi-person-gear"></i></div>
            <div>
                <div class="modal-title" id="modalTitle">Tambah Admin Cabang</div>
                <div class="modal-sub" id="modalSub">Buat akun admin cabang baru</div>
            </div>
            <button class="modal-close" onclick="closeModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="modalForm" method="POST" novalidate>
            @csrf
            <span id="methodField"></span>
            <input type="hidden" id="modalUserId" value="">

            <div class="modal-body">

                {{-- Info validasi assignment --}}
                <div class="assign-info-box" id="assignInfoBox">
                    <i class="bi bi-info-circle-fill"></i>
                    Satu admin hanya bisa mengelola satu bengkel, dan satu bengkel hanya boleh memiliki satu admin.
                </div>

                {{-- Nama --}}
                <div class="fgroup">
                    <label class="flabel" for="mNama">
                        Nama Lengkap <span class="freq">*</span>
                    </label>
                    <input type="text" id="mNama" name="name" class="finput"
                           placeholder="cth: Budi Santoso" required>
                </div>

                {{-- Email + Password --}}
                <div class="frow-2">
                    <div class="fgroup">
                        <label class="flabel" for="mEmail">
                            Email <span class="freq">*</span>
                        </label>
                        <input type="email" id="mEmail" name="email" class="finput"
                               placeholder="budi@autonexa.id" required>
                    </div>
                    <div class="fgroup">
                        <label class="flabel" for="mPass" id="mPassLabel">
                            Password <span class="freq">*</span>
                        </label>
                        <div class="pass-input-wrap">
                            <input type="password" id="mPass" name="password" class="finput"
                                   placeholder="min. 8 karakter">
                            <button type="button" class="btn-pass-eye" id="btnEye"
                                    onclick="togglePassVisibility()">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                        <p class="fhelper" id="mPassHelper">Minimal 8 karakter</p>
                    </div>
                </div>

                {{-- Assign Bengkel --}}
                <div class="fgroup">
                    <label class="flabel" for="mBengkel">
                        Assign Bengkel
                    </label>
                    <select id="mBengkel" name="bengkel_id" class="finput">
                        <option value="">Tidak assign bengkel (opsional)</option>
                        @foreach($bengkelFree ?? [] as $b)
                            <option value="{{ $b->id }}">{{ $b->nama }} — {{ $b->kota }}</option>
                        @endforeach
                    </select>
                    <p class="fhelper">
                        <i class="bi bi-info-circle"></i>
                        Hanya bengkel yang belum memiliki admin yang ditampilkan
                    </p>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-primary">
                    <i class="bi bi-floppy"></i>
                    <span id="modalSaveText">Simpan Admin</span>
                </button>
            </div>
        </form>

    </div>
</div>

{{-- Pass bengkelFree ke JS untuk keperluan edit --}}
<script>
window.bengkelFreeData = @json($bengkelFree ?? []);
</script>

@endsection

@push('scripts')
<script src="{{ asset('js/admin-user.js') }}"></script>
@endpush