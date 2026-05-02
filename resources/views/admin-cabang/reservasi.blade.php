{{-- resources/views/admin-cabang/reservasi/index.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Kelola Reservasi')
@section('page-title', 'Kelola Reservasi')

@section('content')

{{-- Filter Tabs --}}
<div class="filter-tabs mb-4">
    @foreach(['semua' => 'Semua', 'pending' => 'Pending', 'dikonfirmasi' => 'Dikonfirmasi', 'diproses' => 'Diproses', 'selesai' => 'Selesai'] as $key => $label)
    <a href="{{ request()->fullUrlWithQuery(['status' => $key]) }}"
       class="filter-tab {{ request('status', 'semua') === $key ? 'active' : '' }}">
        {{ $label }}
        <span class="filter-count">{{ $counts[$key] ?? 0 }}</span>
    </a>
    @endforeach
</div>

<div class="card-dash">
    <div class="card-dash-header">
        <h6><i class="bi bi-calendar-check me-2"></i>Daftar Reservasi</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Pelanggan</th>
                        <th>Tanggal & Waktu</th>
                        <th>Keluhan</th>
                        <th>Mekanik</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservasis as $r)
                    <tr>
                        <td class="text-muted small">{{ str_pad($r->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="fw-semibold">{{ $r->user->name }}</div>
                            <small class="text-muted">{{ $r->user->phone }}</small>
                        </td>
                        <td>
                            <div>{{ $r->tanggal->format('d M Y') }}</div>
                            <small class="text-muted">{{ $r->waktu }} WIB</small>
                        </td>
                        <td>
                            <span class="text-truncate-2">{{ $r->keluhan }}</span>
                        </td>
                        <td>
                            @if($r->mekanik)
                                <span class="mekanik-assigned">
                                    <i class="bi bi-person-check text-success me-1"></i>
                                    {{ $r->mekanik->user->name }}
                                </span>
                            @else
                                <span class="text-muted small">Belum assign</span>
                            @endif
                        </td>
                        <td>@include('partials.status-badge', ['status' => $r->status])</td>
                        <td>
                            <div class="d-flex gap-1">
                                {{-- Konfirmasi (dari pending) --}}
                                @if($r->status === 'pending')
                                <form method="POST" action="{{ route('admin-cabang.reservasi.konfirmasi', $r->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success" title="Konfirmasi">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                                @endif

                                {{-- Assign Mekanik --}}
                                @if(in_array($r->status, ['dikonfirmasi', 'diproses']) && !$r->mekanik_id)
                                <button type="button" class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalAssign"
                                        data-reservasi-id="{{ $r->id }}">
                                    <i class="bi bi-person-plus"></i>
                                </button>
                                @endif

                                {{-- Lihat Detail --}}
                                <a href="{{ route('admin-cabang.reservasi.detail', $r->id) }}"
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>Tidak ada data reservasi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $reservasis->links() }}
    </div>
</div>

{{-- Modal Assign Mekanik --}}
<div class="modal fade" id="modalAssign" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Mekanik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" id="formAssign">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <label class="form-label">Pilih Mekanik yang Tersedia</label>
                    <select name="mekanik_id" class="form-select" required>
                        <option value="">-- Pilih Mekanik --</option>
                        @foreach($mekaniks as $m)
                        <option value="{{ $m->id }}" {{ !$m->tersedia ? 'disabled' : '' }}>
                            {{ $m->user->name }} — {{ $m->spesialisasi }}
                            {{ !$m->tersedia ? '(Sedang bertugas)' : '' }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('modalAssign').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    const id = btn.getAttribute('data-reservasi-id');
    document.getElementById('formAssign').action = `/admin-cabang/reservasi/${id}/assign`;
});
</script>
@endpush