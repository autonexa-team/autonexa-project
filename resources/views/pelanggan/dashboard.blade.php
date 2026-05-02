{{-- resources/views/pelanggan/dashboard.blade.php --}}
@extends('layouts.dashboard')
@section('title', 'Dashboard Pelanggan')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4">

    {{-- Summary Cards --}}
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-primary">
            <div class="stat-icon"><i class="bi bi-calendar-check"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $totalReservasi }}</div>
                <div class="stat-label">Total Reservasi</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-warning">
            <div class="stat-icon"><i class="bi bi-clock-history"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $sedangDiproses }}</div>
                <div class="stat-label">Sedang Diproses</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-success">
            <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $selesai }}</div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-info">
            <div class="stat-icon"><i class="bi bi-star"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $totalReview }}</div>
                <div class="stat-label">Ulasan Diberikan</div>
            </div>
        </div>
    </div>

    {{-- Quick Action --}}
    <div class="col-12">
        <div class="card-dash">
            <div class="card-dash-header">
                <h6><i class="bi bi-lightning me-2"></i>Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <a href="{{ route('pelanggan.booking.create') }}" class="quick-action-btn">
                            <i class="bi bi-calendar-plus"></i>
                            <span>Booking Baru</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('pelanggan.riwayat') }}" class="quick-action-btn">
                            <i class="bi bi-clock-history"></i>
                            <span>Riwayat</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('pelanggan.bengkel') }}" class="quick-action-btn">
                            <i class="bi bi-geo-alt"></i>
                            <span>Bengkel Terdekat</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('profile.edit') }}" class="quick-action-btn">
                            <i class="bi bi-person-gear"></i>
                            <span>Profil Saya</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reservasi Aktif --}}
    <div class="col-lg-8">
        <div class="card-dash">
            <div class="card-dash-header">
                <h6><i class="bi bi-calendar-event me-2"></i>Reservasi Terbaru</h6>
                <a href="{{ route('pelanggan.riwayat') }}" class="btn-link-sm">Lihat semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Bengkel</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservasis as $r)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $r->bengkel->nama }}</div>
                                    <small class="text-muted">{{ $r->keluhan }}</small>
                                </td>
                                <td>
                                    <div>{{ $r->tanggal->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $r->waktu }}</small>
                                </td>
                                <td>
                                    @include('partials.status-badge', ['status' => $r->status])
                                </td>
                                <td>
                                    <a href="{{ route('pelanggan.tracking', $r->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="bi bi-calendar-x fs-2 d-block mb-2"></i>
                                    Belum ada reservasi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Terakhir --}}
    <div class="col-lg-4">
        <div class="card-dash h-100">
            <div class="card-dash-header">
                <h6><i class="bi bi-activity me-2"></i>Tracking Aktif</h6>
            </div>
            <div class="card-body">
                @if($aktif)
                <div class="tracking-mini">
                    <div class="tracking-bengkel">{{ $aktif->bengkel->nama }}</div>
                    <div class="tracking-date text-muted small">{{ $aktif->tanggal->format('d M Y') }} · {{ $aktif->waktu }}</div>
                    <div class="tracking-steps mt-3">
                        @foreach(['pending' => 'Menunggu', 'dikonfirmasi' => 'Dikonfirmasi', 'diproses' => 'Diproses', 'selesai' => 'Selesai'] as $key => $label)
                        <div class="tracking-step {{ $aktif->status === $key ? 'active' : (array_search($aktif->status, array_keys(['pending','dikonfirmasi','diproses','selesai'])) > array_search($key, ['pending','dikonfirmasi','diproses','selesai']) ? 'done' : '') }}">
                            <div class="step-dot"></div>
                            <div class="step-label">{{ $label }}</div>
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('pelanggan.tracking', $aktif->id) }}" class="btn btn-primary btn-sm w-100 mt-3">
                        Lihat Detail
                    </a>
                </div>
                @else
                <div class="text-center text-muted py-4">
                    <i class="bi bi-check-all fs-2 d-block mb-2 text-success"></i>
                    Tidak ada service aktif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection