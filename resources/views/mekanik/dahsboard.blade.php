{{-- resources/views/mekanik/dashboard.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Dashboard Mekanik')
@section('page-title', 'Dashboard Saya')

@section('content')
<div class="row g-4">

    {{-- Stat --}}
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-warning">
            <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $pending }}</div>
                <div class="stat-label">Antrian</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-primary">
            <div class="stat-icon"><i class="bi bi-tools"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $aktif }}</div>
                <div class="stat-label">Sedang Dikerjakan</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-success">
            <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $selesaiHariIni }}</div>
                <div class="stat-label">Selesai Hari Ini</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-info">
            <div class="stat-icon"><i class="bi bi-bar-chart"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $totalBulanIni }}</div>
                <div class="stat-label">Total Bulan Ini</div>
            </div>
        </div>
    </div>

    {{-- Tugas Aktif / Antrian --}}
    <div class="col-12">
        <div class="card-dash">
            <div class="card-dash-header">
                <h6><i class="bi bi-list-task me-2"></i>Daftar Tugas Service</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Pelanggan</th>
                                <th>Keluhan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tugas as $t)
                            <tr>
                                <td class="text-muted small">{{ str_pad($t->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $t->user->name }}</div>
                                    <small class="text-muted">{{ $t->user->phone }}</small>
                                </td>
                                <td>{{ Str::limit($t->keluhan, 40) }}</td>
                                <td>{{ $t->tanggal->format('d M Y') }}</td>
                                <td>@include('partials.status-badge', ['status' => $t->status])</td>
                                <td>
                                    @if($t->status === 'dikonfirmasi')
                                    <form method="POST" action="{{ route('mekanik.service.mulai', $t->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="bi bi-play-fill me-1"></i>Mulai Service
                                        </button>
                                    </form>
                                    @elseif($t->status === 'diproses')
                                    <a href="{{ route('mekanik.service.detail', $t->id) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil me-1"></i>Input Hasil
                                    </a>
                                    @else
                                    <a href="{{ route('mekanik.service.detail', $t->id) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-emoji-smile fs-2 d-block mb-2"></i>
                                    Tidak ada tugas saat ini
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection