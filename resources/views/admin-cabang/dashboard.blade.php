@extends('layout.admin-cabang')

@section('content')

{{-- ── Stylesheet ── --}}
<link rel="stylesheet" href="{{ asset('css/admincabang-dashboard.css') }}">

<div class="dash-page">

    {{-- ────────────────────────────────────────────
         HEADER
    ──────────────────────────────────────────── --}}
    <div class="dash-header animate-fade-slide-up">
        <div>
            <h2 class="dash-header__title">Halo, Admin Cabang 👋</h2>
            <p class="dash-header__subtitle">Berikut adalah ringkasan operasional bengkel Anda hari ini.</p>
        </div>
        <a href="{{ route('admin-cabang.reservasi-create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Reservasi Baru
        </a>
    </div>

    {{-- ────────────────────────────────────────────
         STAT CARDS
    ──────────────────────────────────────────── --}}
    <div class="stat-grid">

        {{-- Card 1 · Total Reservasi --}}
        <div class="stat-card animate-fade-slide-up stagger-1"
             style="--c-accent:#f97316; --c-bubble:rgba(249,115,22,.07);
                    --c-icon-bg:rgba(249,115,22,.08); --c-icon-border:rgba(249,115,22,.18); --c-icon-color:#f97316;">
            <div class="stat-card__bubble"></div>
            <div class="stat-card__head">
                <div>
                    <p class="stat-card__label">Total Reservasi</p>
                    <h3 class="stat-card__value counter-value" data-target="{{ $totalReservasi }}">0</h3>
                </div>
                <div class="stat-card__icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            <div class="stat-card__foot">
                <span class="badge badge--up">
                    <i class="fas fa-arrow-up" style="font-size:.6rem;"></i> 12%
                </span>
                <span style="font-size:.75rem; color:var(--text-faint); font-weight:500;">vs kemarin</span>
            </div>
        </div>

        {{-- Card 2 · Service Aktif --}}
        <div class="stat-card animate-fade-slide-up stagger-2"
             style="--c-accent:#3b82f6; --c-bubble:rgba(59,130,246,.07);
                    --c-icon-bg:rgba(59,130,246,.08); --c-icon-border:rgba(59,130,246,.18); --c-icon-color:#2563eb;">
            <div class="stat-card__bubble"></div>
            <div class="stat-card__head">
                <div>
                    <p class="stat-card__label">Service Aktif</p>
                    <h3 class="stat-card__value counter-value" data-target="{{ $serviceAktif }}">0</h3>
                </div>
                <div class="stat-card__icon">
                    <i class="fas fa-tools"></i>
                </div>
            </div>
            <div class="stat-card__foot">
                <span class="badge badge--info">Sedang Dikerjakan</span>
            </div>
        </div>

        {{-- Card 3 · Sparepart Menipis --}}
        <div class="stat-card animate-fade-slide-up stagger-3"
             style="--c-accent:#ef4444; --c-bubble:rgba(239,68,68,.07);
                    --c-icon-bg:rgba(239,68,68,.08); --c-icon-border:rgba(239,68,68,.18); --c-icon-color:#dc2626;">
            <div class="stat-card__bubble"></div>
            <div class="stat-card__head">
                <div>
                    <p class="stat-card__label">Sparepart Menipis</p>
                    <h3 class="stat-card__value counter-value" data-target="{{ $sparepartMenipis }}">0</h3>
                </div>
                <div class="stat-card__icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
            <div class="stat-card__foot">
                <span class="badge badge--danger">
                    <i class="fas fa-circle-exclamation" style="font-size:.65rem;"></i>
                    Perlu Restock Segera
                </span>
            </div>
        </div>

        {{-- Card 4 · Pendapatan --}}
        <div class="stat-card animate-fade-slide-up stagger-4"
             style="--c-accent:#10b981; --c-bubble:rgba(16,185,129,.07);
                    --c-icon-bg:rgba(16,185,129,.08); --c-icon-border:rgba(16,185,129,.18); --c-icon-color:#059669;">
            <div class="stat-card__bubble"></div>
            <div class="stat-card__head">
                <div>
                    <p class="stat-card__label">Pendapatan Hari Ini</p>
                    <h3 class="stat-card__value stat-card__value--md">
                        <span class="counter-value" 
                            data-target="{{ number_format($pendapatanHariIni / 1000000, 1, '.', '') }}" 
                            data-decimals="1"
                            data-prefix="Rp " 
                            data-suffix="M">Rp 0.0M</span>
                    </h3>
                </div>
                <div class="stat-card__icon">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
            <div class="stat-card__foot">
                <span class="badge badge--up">
                    <i class="fas fa-arrow-up" style="font-size:.6rem;"></i> 5%
                </span>
                <span style="font-size:.75rem; color:var(--text-faint); font-weight:500;">dari target harian</span>
            </div>
        </div>

    </div>{{-- /stat-grid --}}

    {{-- ────────────────────────────────────────────
         RESERVASI TABLE
    ──────────────────────────────────────────── --}}
    <div class="table-section animate-fade-slide-up stagger-5">

        {{-- Table Header --}}
        <div class="table-section__header">
            <div class="table-section__title-wrap">
                <div class="table-section__icon">
                    <i class="fas fa-list-ul"></i>
                </div>
                <h3 class="table-section__title">Jadwal Reservasi Hari Ini</h3>
            </div>
            <a href="{{ route('admin-cabang.reservasi') }}" class="table-section__link">
                Lihat Semua &rarr;
            </a>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Keluhan</th>
                        <th>Waktu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($reservasiHariIni as $rsv)
                <tr>
                    {{-- Kolom Pelanggan --}}
                    <td>
                        <div class="cell-user">
                            <div class="avatar">
                                {{ strtoupper(substr($rsv->nama_pelanggan ?? 'P', 0, 2)) }}
                            </div>
                            <div>
                                <p class="cell-name">{{ $rsv->nama_pelanggan ?? '-' }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Kolom Keluhan --}}
                    <td>
                        <span class="cell-service">
                            {{ \Illuminate\Support\Str::limit($rsv->keluhan, 40) }}
                        </span>
                    </td>

                    {{-- Kolom Waktu --}}
                    <td>
                        <span class="cell-time">
                            <i class="far fa-clock"></i>
                            {{ \Carbon\Carbon::parse($rsv->waktu)->format('H:i') }} WIB
                        </span>
                    </td>

                    {{-- Kolom Status --}}
                    <td>
                        @php
                            $statusClass = match($rsv->status) {
                                'pending'    => 'status--waiting',
                                'diproses'   => 'status--process',
                                'selesai'    => 'status--done',
                                'dibatalkan' => 'status--cancel',
                                default      => 'status--waiting',
                            };
                            $statusLabel = match($rsv->status) {
                                'pending'    => 'Menunggu',
                                'diproses'   => 'Proses Servis',
                                'selesai'    => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                                default      => $rsv->status,
                            };
                        @endphp
                        <span class="status {{ $statusClass }}">
                            <span class="status__dot"></span>
                            {{ $statusLabel }}
                        </span>
                    </td>

                    {{-- Kolom Aksi --}}
                    <td>
                        <div class="cell-actions">
                            <a href="{{ route('admin-cabang.reservasi-detail', $rsv->id) }}"
                               class="action-btn action-btn--view">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-slate-400 py-8">
                        Tidak ada reservasi hari ini.
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="{{ asset('js/admin-cabang-dashboard.js') }}"></script>

@endsection