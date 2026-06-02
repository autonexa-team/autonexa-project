{{-- resources/views/admin-pusat/reservasi.blade.php --}}
@extends('layout.admin')
@section('title', 'Manajemen Reservasi')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush

@section('content')

{{-- ===== PAGE HEADER ===== --}}
<div class="page-header">
    <div>
        <h1 class="page-title">
            @if($bengkel)
                Reservasi - {{ $bengkel->nama }}
            @else
                Manajemen Reservasi
            @endif
        </h1>
        <p class="page-sub">
            @if($bengkel)
                Daftar reservasi untuk bengkel {{ $bengkel->nama }}
            @else
                Daftar semua reservasi dari seluruh bengkel
            @endif
        </p>
    </div>
    @if($bengkel)
        <a href="{{ route('admin-pusat.bengkel.show', $bengkel->id) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    @endif
</div>

{{-- ===== STATS ===== --}}
<div class="stats-bar">
    <span class="stat-pill sp-all">
        Total <strong>{{ $reservasi->total() }}</strong>
    </span>
</div>

{{-- ===== TABLE ===== --}}
<div class="table-responsive">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pelanggan</th>
                <th>Bengkel</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservasi as $res)
            <tr>
                <td>
                    <strong>#{{ $res->id }}</strong>
                </td>
                <td>
                    <div class="user-cell">
                        <span class="user-name">{{ $res->user->name ?? '-' }}</span>
                    </div>
                </td>
                <td>
                    <span class="bengkel-name">{{ $res->bengkel->nama ?? '-' }}</span>
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($res->tanggal)->format('d/m/Y') }}
                </td>
                <td>
                    {{ $res->waktu ?? '-' }}
                </td>
                <td>
                    <span class="badge badge-{{ $res->status }}">
                        {{ ucfirst(str_replace('_', ' ', $res->status ?? 'pending')) }}
                    </span>
                </td>
                <td>
                    <div class="action-cell">
                        <a href="#" class="btn-sm btn-view" title="Lihat Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted py-4">
                    Belum ada reservasi
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ===== PAGINATION ===== --}}
<div class="pagination-wrapper">
    {{ $reservasi->links() }}
</div>

<style>
.badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
}

.badge-pending {
    background-color: #fff3cd;
    color: #856404;
}

.badge-in_progress {
    background-color: #cce5ff;
    color: #004085;
}

.badge-done {
    background-color: #d4edda;
    color: #155724;
}

.badge-cancelled {
    background-color: #f8d7da;
    color: #721c24;
}

.user-cell {
    display: flex;
    align-items: center;
    gap: 8px;
}

.user-name {
    font-weight: 500;
}

.bengkel-name {
    font-weight: 500;
}

.action-cell {
    display: flex;
    gap: 8px;
}

.btn-sm {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    color: #666;
}

.btn-sm:hover {
    border-color: #999;
    background-color: #f5f5f5;
}

.btn-view {
    color: #0066cc;
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: #f5f5f5;
    color: #333;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-secondary:hover {
    background-color: #e0e0e0;
}
</style>

@endsection