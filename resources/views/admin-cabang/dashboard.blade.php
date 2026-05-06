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
                    <h3 class="stat-card__value counter-value" data-target="24">0</h3>
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
                    <h3 class="stat-card__value counter-value" data-target="8">0</h3>
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
                    <h3 class="stat-card__value counter-value" data-target="12">0</h3>
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
                        <span class="counter-value" data-target="4.5" data-decimals="1"
                              data-prefix="Rp " data-suffix="M">Rp 0.0M</span>
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
                        <th>Kendaraan</th>
                        <th>Layanan</th>
                        <th>Waktu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    {{-- Row 1 — Menunggu --}}
                    <tr>
                        <td>
                            <div class="cell-user">
                                <div class="avatar" style="background:#eef2ff; color:#4f46e5;">AS</div>
                                <div>
                                    <p class="cell-name">Andi Saputra</p>
                                    <p class="cell-sub">0812-3456-7890</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="cell-vehicle-name">Honda Brio</p>
                            <span class="cell-plate">B 1234 XYZ</span>
                        </td>
                        <td><span class="cell-service">Servis Berkala 10rb KM</span></td>
                        <td>
                            <span class="cell-time">
                                <i class="far fa-clock"></i> 10:00 WIB
                            </span>
                        </td>
                        <td>
                            <span class="status status--waiting">
                                <span class="status__dot"></span>
                                Menunggu
                            </span>
                        </td>
                        <td>
                            <div class="cell-actions">
                                <button class="action-btn action-btn--edit" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button class="action-btn action-btn--confirm" title="Terima">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Row 2 — Proses Servis --}}
                    <tr>
                        <td>
                            <div class="cell-user">
                                <div class="avatar" style="background:#eff6ff; color:#2563eb;">BS</div>
                                <div>
                                    <p class="cell-name">Budi Setiawan</p>
                                    <p class="cell-sub">0856-7890-1234</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="cell-vehicle-name">Toyota Avanza</p>
                            <span class="cell-plate">D 5678 ABC</span>
                        </td>
                        <td><span class="cell-service">Ganti Oli &amp; Filter</span></td>
                        <td>
                            <span class="cell-time">
                                <i class="far fa-clock"></i> 11:30 WIB
                            </span>
                        </td>
                        <td>
                            <span class="status status--process">
                                <span class="status__dot"></span>
                                Proses Servis
                            </span>
                        </td>
                        <td>
                            <div class="cell-actions">
                                <button class="action-btn action-btn--edit" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button class="action-btn action-btn--done" title="Selesai">
                                    <i class="fas fa-check-double"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Row 3 — Selesai --}}
                    <tr>
                        <td>
                            <div class="cell-user">
                                <div class="avatar" style="background:#fdf2f8; color:#db2777;">CK</div>
                                <div>
                                    <p class="cell-name">Citra Kirana</p>
                                    <p class="cell-sub">0899-1122-3344</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="cell-vehicle-name">Mitsubishi Xpander</p>
                            <span class="cell-plate">F 9988 DEF</span>
                        </td>
                        <td><span class="cell-service">Pengecekan Rem</span></td>
                        <td>
                            <span class="cell-time">
                                <i class="far fa-clock"></i> 14:00 WIB
                            </span>
                        </td>
                        <td>
                            <span class="status status--done">
                                <span class="status__dot"></span>
                                Selesai
                            </span>
                        </td>
                        <td>
                            <div class="cell-actions">
                                <button class="action-btn action-btn--view" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>{{-- /table-section --}}

</div>{{-- /dash-page --}}

{{-- ── JavaScript ── --}}
<script src="{{ asset('js/admin-cabang-dashboard.js') }}"></script>

@endsection