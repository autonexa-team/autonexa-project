@extends('layout.app-clean')

@section('title', 'Riwayat Reservasi — AutoNexa')

@section('content')

{{-- ── Stylesheet ── --}}
<link rel="stylesheet" href="{{ asset('css/riwayat.css') }}">

<div class="rw-page">

    {{-- ════════════════════════════════════════
         PAGE HEADER
    ════════════════════════════════════════ --}}
    <div class="rw-header anim-up">
        {{-- Breadcrumb --}}
        <div class="rw-header__breadcrumb">
            <a href="{{ route('landing') }}"><i class="fas fa-home"></i> Beranda</a>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <span class="cur">Riwayat Reservasi</span>
        </div>

        <div class="rw-header__top">
            <div>
                <p class="rw-header__kicker">AutoNexa</p>
                <h1 class="rw-header__title">Riwayat <em>Reservasi</em></h1>
                <p class="rw-header__subtitle">Pantau semua servis kendaraan Anda dalam satu tempat.</p>
            </div>
            <div class="rw-header__actions">
                <a href="{{ route('pelanggan.reservasi') }}" class="btn btn--primary">
                    <i class="fas fa-plus"></i> Reservasi Baru
                </a>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         STAT STRIP
    ════════════════════════════════════════ --}}
    <div class="stat-strip">
        {{-- Total --}}
        <div class="stat-tile"
             style="--c-accent:#f97316; --c-blob:rgba(249,115,22,.07);
                    --c-icon-bg:rgba(249,115,22,.08); --c-icon-border:rgba(249,115,22,.18); --c-icon:#f97316;">
            <div class="stat-tile__blob"></div>
            <div class="stat-tile__icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="stat-tile__num counter" data-target="12">0</div>
            <div class="stat-tile__label">Total Reservasi</div>
        </div>

        {{-- Aktif --}}
        <div class="stat-tile"
             style="--c-accent:#3b82f6; --c-blob:rgba(59,130,246,.07);
                    --c-icon-bg:rgba(59,130,246,.08); --c-icon-border:rgba(59,130,246,.18); --c-icon:#2563eb;">
            <div class="stat-tile__blob"></div>
            <div class="stat-tile__icon"><i class="fas fa-wrench"></i></div>
            <div class="stat-tile__num counter" data-target="2">0</div>
            <div class="stat-tile__label">Sedang Aktif</div>
        </div>

        {{-- Selesai --}}
        <div class="stat-tile"
             style="--c-accent:#10b981; --c-blob:rgba(16,185,129,.07);
                    --c-icon-bg:rgba(16,185,129,.08); --c-icon-border:rgba(16,185,129,.18); --c-icon:#059669;">
            <div class="stat-tile__blob"></div>
            <div class="stat-tile__icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-tile__num counter" data-target="8">0</div>
            <div class="stat-tile__label">Selesai</div>
        </div>

        {{-- Dibatalkan --}}
        <div class="stat-tile"
             style="--c-accent:#ef4444; --c-blob:rgba(239,68,68,.07);
                    --c-icon-bg:rgba(239,68,68,.08); --c-icon-border:rgba(239,68,68,.18); --c-icon:#dc2626;">
            <div class="stat-tile__blob"></div>
            <div class="stat-tile__icon"><i class="fas fa-times-circle"></i></div>
            <div class="stat-tile__num counter" data-target="2">0</div>
            <div class="stat-tile__label">Dibatalkan</div>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         TOOLBAR — Search & Date Filter
    ════════════════════════════════════════ --}}
    <div class="toolbar">
        <div class="search-box">
            <i class="fas fa-search search-box__icon"></i>
            <input type="text" id="searchInput"
                   placeholder="Cari layanan, kendaraan, atau nomor reservasi..."
                   autocomplete="off">
        </div>
        <input type="date" id="dateFilter" class="date-input" title="Filter tanggal">
        <button class="btn btn--outline btn--sm" id="clearFilter">
            <i class="fas fa-times"></i> Reset
        </button>
    </div>

    {{-- ════════════════════════════════════════
         TABS
    ════════════════════════════════════════ --}}
    <div class="tabs" id="tabBar">
        <button class="tab-btn active" data-tab="all">
            Semua <span class="tab-count">12</span>
        </button>
        <button class="tab-btn" data-tab="process">
            <i class="fas fa-wrench" style="font-size:.7rem;"></i> Diproses <span class="tab-count">1</span>
        </button>
        <button class="tab-btn" data-tab="waiting">
            <i class="fas fa-clock" style="font-size:.7rem;"></i> Menunggu <span class="tab-count">1</span>
        </button>
        <button class="tab-btn" data-tab="done">
            <i class="fas fa-check" style="font-size:.7rem;"></i> Selesai <span class="tab-count">8</span>
        </button>
        <button class="tab-btn" data-tab="cancel">
            <i class="fas fa-ban" style="font-size:.7rem;"></i> Dibatalkan <span class="tab-count">2</span>
        </button>
    </div>

    {{-- ════════════════════════════════════════
         SKELETON LOADING (tampil sementara)
    ════════════════════════════════════════ --}}
    <div id="skeletonWrap" class="card-list">
        @for ($i = 0; $i < 3; $i++)
        <div class="skeleton-card">
            <div class="skeleton-card__top">
                <div class="skel" style="width:46px;height:46px;border-radius:14px;flex-shrink:0;"></div>
                <div style="flex:1; display:flex; flex-direction:column; gap:.5rem;">
                    <div class="skel" style="height:14px; width:55%;"></div>
                    <div class="skel" style="height:11px; width:35%;"></div>
                </div>
                <div class="skel" style="height:26px; width:90px; border-radius:40px;"></div>
            </div>
            <div class="skeleton-card__meta">
                @for ($j = 0; $j < 3; $j++)
                <div class="skeleton-cell">
                    <div class="skel" style="height:10px; width:50%; margin-bottom:.5rem;"></div>
                    <div class="skel" style="height:13px; width:70%;"></div>
                </div>
                @endfor
            </div>
            <div class="skeleton-card__foot">
                <div class="skel" style="height:13px; width:40%;"></div>
                <div class="skel" style="height:32px; width:110px; border-radius:12px;"></div>
            </div>
        </div>
        @endfor
    </div>

    {{-- ════════════════════════════════════════
         CARD LIST (dirender oleh JS)
    ════════════════════════════════════════ --}}
    <div id="cardList" class="card-list" style="display:none;"></div>

    {{-- ════════════════════════════════════════
         EMPTY STATE
    ════════════════════════════════════════ --}}
    <div id="emptyState" class="empty-state" style="display:none;">
        <div class="empty-state__illus">🔍</div>
        <h3 class="empty-state__title">Tidak Ada Reservasi</h3>
        <p class="empty-state__sub">Belum ada riwayat reservasi yang cocok dengan filter yang kamu pilih.</p>
        <a href="{{ route('pelanggan.reservasi') }}" class="btn btn--primary">
            <i class="fas fa-plus"></i> Buat Reservasi Sekarang
        </a>
    </div>

    {{-- ════════════════════════════════════════
         PAGINATION
    ════════════════════════════════════════ --}}
    <div id="pagination" class="pagination" style="display:none;"></div>

</div>{{-- /rw-page --}}

{{-- ── FAB ── --}}
<a href="{{ route('pelanggan.reservasi') }}" class="fab">
    <i class="fas fa-plus"></i> Reservasi Baru
</a>

{{-- ── Scripts ── --}}
<script>
(function () {
    'use strict';

    /* ══════════════════════════════════════
       DUMMY DATA
    ══════════════════════════════════════ */
    const RESERVATIONS = [
        {
            id: 'RV-2025-001',
            status: 'process',
            bengkel: 'AutoNexa Cabang Bandung',
            alamat: 'Jl. Soekarno-Hatta No. 88',
            kendaraan: 'Honda Brio',
            plat: 'B 1234 XYZ',
            layanan: 'Servis Berkala 10.000 KM',
            tanggal: '2025-07-10',
            jam: '10:00 WIB',
            keluhan: 'Mesin terasa kasar, oli sudah lama tidak ganti',
            biaya: null,
            step: 3, // 0-5
            timeline: [
                { time: '10 Jul, 09:15', title: 'Reservasi Dibuat', desc: 'Kamu berhasil membuat reservasi online.', state: 'done' },
                { time: '10 Jul, 09:45', title: 'Reservasi Dikonfirmasi', desc: 'Admin bengkel telah mengkonfirmasi reservasi kamu.', state: 'done' },
                { time: '10 Jul, 10:10', title: 'Kendaraan Diterima', desc: 'Kendaraan sudah tiba dan sedang diperiksa mekanik.', state: 'done' },
                { time: 'Sedang berlangsung', title: 'Proses Servis', desc: 'Mekanik sedang mengerjakan kendaraan kamu.', state: 'active' },
                { time: '—', title: 'Quality Check', desc: 'Pengecekan akhir kualitas hasil servis.', state: 'idle' },
                { time: '—', title: 'Selesai', desc: 'Kendaraan siap diambil.', state: 'idle' },
            ]
        },
        {
            id: 'RV-2025-002',
            status: 'waiting',
            bengkel: 'AutoNexa Cabang Jakarta Selatan',
            alamat: 'Jl. TB Simatupang No. 12',
            kendaraan: 'Toyota Avanza',
            plat: 'D 5678 ABC',
            layanan: 'Tune Up Mesin',
            tanggal: '2025-07-14',
            jam: '13:00 WIB',
            keluhan: 'Konsumsi BBM boros, akselerasi kurang responsif',
            biaya: null,
            step: 0,
            timeline: [
                { time: '12 Jul, 14:30', title: 'Reservasi Dibuat', desc: 'Kamu berhasil membuat reservasi untuk 14 Juli.', state: 'done' },
                { time: '—', title: 'Menunggu Konfirmasi Admin', desc: 'Admin akan segera mengkonfirmasi reservasimu.', state: 'active' },
                { time: '—', title: 'Kendaraan Diterima', desc: '', state: 'idle' },
                { time: '—', title: 'Proses Servis', desc: '', state: 'idle' },
                { time: '—', title: 'Quality Check', desc: '', state: 'idle' },
                { time: '—', title: 'Selesai', desc: '', state: 'idle' },
            ]
        },
        {
            id: 'RV-2025-003',
            status: 'done',
            bengkel: 'AutoNexa Cabang Bandung',
            alamat: 'Jl. Soekarno-Hatta No. 88',
            kendaraan: 'Honda Brio',
            plat: 'B 1234 XYZ',
            layanan: 'Ganti Oli & Filter',
            tanggal: '2025-06-20',
            jam: '09:00 WIB',
            keluhan: 'Ganti oli rutin',
            biaya: 'Rp 185.000',
            step: 5,
            timeline: [
                { time: '20 Jun, 08:30', title: 'Reservasi Dibuat',       desc: 'Reservasi online berhasil dibuat.', state: 'done' },
                { time: '20 Jun, 08:50', title: 'Reservasi Dikonfirmasi', desc: 'Admin telah mengkonfirmasi.', state: 'done' },
                { time: '20 Jun, 09:05', title: 'Kendaraan Diterima',     desc: 'Kendaraan sudah di bengkel.', state: 'done' },
                { time: '20 Jun, 09:20', title: 'Proses Servis',          desc: 'Mekanik mengerjakan ganti oli.', state: 'done' },
                { time: '20 Jun, 09:45', title: 'Quality Check',          desc: 'Pengecekan kebocoran & tekanan oli.', state: 'done' },
                { time: '20 Jun, 10:00', title: 'Selesai',                desc: 'Kendaraan siap diambil. Terima kasih!', state: 'done' },
            ]
        },
        {
            id: 'RV-2025-004',
            status: 'done',
            bengkel: 'AutoNexa Cabang Surabaya',
            alamat: 'Jl. Ahmad Yani No. 45',
            kendaraan: 'Toyota Avanza',
            plat: 'D 5678 ABC',
            layanan: 'Pengecekan & Servis Rem',
            tanggal: '2025-05-15',
            jam: '11:00 WIB',
            keluhan: 'Rem terasa blong saat pengereman mendadak',
            biaya: 'Rp 420.000',
            step: 5,
            timeline: [
                { time: '15 Mei, 10:30', title: 'Reservasi Dibuat',       desc: '', state: 'done' },
                { time: '15 Mei, 10:45', title: 'Reservasi Dikonfirmasi', desc: '', state: 'done' },
                { time: '15 Mei, 11:05', title: 'Kendaraan Diterima',     desc: '', state: 'done' },
                { time: '15 Mei, 11:30', title: 'Proses Servis',          desc: 'Penggantian kampas rem depan & belakang.', state: 'done' },
                { time: '15 Mei, 13:00', title: 'Quality Check',          desc: '', state: 'done' },
                { time: '15 Mei, 13:30', title: 'Selesai',                desc: 'Kendaraan telah selesai diservis.', state: 'done' },
            ]
        },
        {
            id: 'RV-2025-005',
            status: 'done',
            bengkel: 'AutoNexa Cabang Bandung',
            alamat: 'Jl. Soekarno-Hatta No. 88',
            kendaraan: 'Honda Brio',
            plat: 'B 1234 XYZ',
            layanan: 'Servis AC Mobil',
            tanggal: '2025-04-08',
            jam: '10:30 WIB',
            keluhan: 'AC tidak dingin, ada suara berisik dari blower',
            biaya: 'Rp 650.000',
            step: 5,
            timeline: [
                { time: '08 Apr, 10:00', title: 'Reservasi Dibuat',       desc: '', state: 'done' },
                { time: '08 Apr, 10:15', title: 'Reservasi Dikonfirmasi', desc: '', state: 'done' },
                { time: '08 Apr, 10:35', title: 'Kendaraan Diterima',     desc: '', state: 'done' },
                { time: '08 Apr, 11:00', title: 'Proses Servis',          desc: 'Pembersihan evaporator & isi freon.', state: 'done' },
                { time: '08 Apr, 13:30', title: 'Quality Check',          desc: '', state: 'done' },
                { time: '08 Apr, 14:00', title: 'Selesai',                desc: '', state: 'done' },
            ]
        },
        {
            id: 'RV-2025-006',
            status: 'done',
            bengkel: 'AutoNexa Cabang Jakarta Selatan',
            alamat: 'Jl. TB Simatupang No. 12',
            kendaraan: 'Toyota Avanza',
            plat: 'D 5678 ABC',
            layanan: 'Balancing & Rotasi Ban',
            tanggal: '2025-03-22',
            jam: '14:00 WIB',
            keluhan: 'Setir goyang di kecepatan tinggi',
            biaya: 'Rp 150.000',
            step: 5,
            timeline: [
                { time: '22 Mar', title: 'Reservasi Dibuat',       desc: '', state: 'done' },
                { time: '22 Mar', title: 'Reservasi Dikonfirmasi', desc: '', state: 'done' },
                { time: '22 Mar', title: 'Kendaraan Diterima',     desc: '', state: 'done' },
                { time: '22 Mar', title: 'Proses Servis',          desc: '', state: 'done' },
                { time: '22 Mar', title: 'Quality Check',          desc: '', state: 'done' },
                { time: '22 Mar', title: 'Selesai',                desc: '', state: 'done' },
            ]
        },
        {
            id: 'RV-2025-007',
            status: 'done',
            bengkel: 'AutoNexa Cabang Bandung',
            alamat: 'Jl. Soekarno-Hatta No. 88',
            kendaraan: 'Honda Brio',
            plat: 'B 1234 XYZ',
            layanan: 'Servis Berkala 20.000 KM',
            tanggal: '2025-02-14',
            jam: '09:30 WIB',
            keluhan: 'Servis berkala rutin 20.000 KM',
            biaya: 'Rp 490.000',
            step: 5,
            timeline: [
                { time: '14 Feb', title: 'Selesai', desc: '', state: 'done' },
            ]
        },
        {
            id: 'RV-2025-008',
            status: 'done',
            bengkel: 'AutoNexa Cabang Surabaya',
            alamat: 'Jl. Ahmad Yani No. 45',
            kendaraan: 'Toyota Avanza',
            plat: 'D 5678 ABC',
            layanan: 'Ganti Oli & Filter',
            tanggal: '2025-01-05',
            jam: '11:30 WIB',
            keluhan: 'Ganti oli awal tahun',
            biaya: 'Rp 175.000',
            step: 5,
            timeline: []
        },
        {
            id: 'RV-2024-011',
            status: 'done',
            bengkel: 'AutoNexa Cabang Bandung',
            alamat: 'Jl. Soekarno-Hatta No. 88',
            kendaraan: 'Honda Brio',
            plat: 'B 1234 XYZ',
            layanan: 'Tune Up Mesin',
            tanggal: '2024-11-30',
            jam: '10:00 WIB',
            keluhan: 'Mesin sering brebet pagi hari',
            biaya: 'Rp 780.000',
            step: 5,
            timeline: []
        },
        {
            id: 'RV-2024-009',
            status: 'done',
            bengkel: 'AutoNexa Cabang Jakarta Selatan',
            alamat: 'Jl. TB Simatupang No. 12',
            kendaraan: 'Toyota Avanza',
            plat: 'D 5678 ABC',
            layanan: 'Servis AC Mobil',
            tanggal: '2024-09-18',
            jam: '13:30 WIB',
            keluhan: 'AC kurang dingin saat macet',
            biaya: 'Rp 520.000',
            step: 5,
            timeline: []
        },
        {
            id: 'RV-2024-007',
            status: 'cancel',
            bengkel: 'AutoNexa Cabang Bandung',
            alamat: 'Jl. Soekarno-Hatta No. 88',
            kendaraan: 'Honda Brio',
            plat: 'B 1234 XYZ',
            layanan: 'Servis Berkala 10.000 KM',
            tanggal: '2024-08-03',
            jam: '09:00 WIB',
            keluhan: 'Servis rutin',
            biaya: null,
            step: 0,
            timeline: [
                { time: '01 Agu, 10:00', title: 'Reservasi Dibuat', desc: '', state: 'done' },
                { time: '02 Agu, 14:00', title: 'Dibatalkan', desc: 'Reservasi dibatalkan oleh pelanggan.', state: 'active' },
            ]
        },
        {
            id: 'RV-2024-005',
            status: 'cancel',
            bengkel: 'AutoNexa Cabang Surabaya',
            alamat: 'Jl. Ahmad Yani No. 45',
            kendaraan: 'Toyota Avanza',
            plat: 'D 5678 ABC',
            layanan: 'Pengecekan Rem',
            tanggal: '2024-06-20',
            jam: '14:30 WIB',
            keluhan: 'Rem sedikit blong',
            biaya: null,
            step: 0,
            timeline: [
                { time: '18 Jun', title: 'Reservasi Dibuat', desc: '', state: 'done' },
                { time: '19 Jun', title: 'Dibatalkan', desc: 'Reservasi dibatalkan oleh admin (slot penuh).', state: 'active' },
            ]
        },
    ];

    /* step labels & icons */
    const STEPS = [
        { icon: 'fa-hourglass-start', label: 'Menunggu Konfirmasi' },
        { icon: 'fa-check',           label: 'Reservasi Diterima'  },
        { icon: 'fa-car',             label: 'Kendaraan Diperiksa' },
        { icon: 'fa-wrench',          label: 'Proses Servis'       },
        { icon: 'fa-shield-alt',      label: 'Quality Check'       },
        { icon: 'fa-flag-checkered',  label: 'Selesai'             },
    ];

    const STATUS_MAP = {
        waiting: { label: 'Menunggu',     cls: 'status-badge--waiting', stripe: '#f59e0b' },
        process: { label: 'Diproses',     cls: 'status-badge--process', stripe: '#3b82f6' },
        done:    { label: 'Selesai',      cls: 'status-badge--done',    stripe: '#10b981' },
        cancel:  { label: 'Dibatalkan',   cls: 'status-badge--cancel',  stripe: '#ef4444' },
    };

    /* ══════════════════════════════════════
       STATE
    ══════════════════════════════════════ */
    const PER_PAGE = 5;
    let currentTab  = 'all';
    let currentPage = 1;
    let searchQuery = '';
    let dateFilter  = '';

    /* ══════════════════════════════════════
       RENDER HELPERS
    ══════════════════════════════════════ */
    function formatDate(str) {
        const d = new Date(str + 'T00:00:00');
        return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    }

    function buildStepTracker(activeStep) {
        const pct = activeStep === 0 ? 0 : Math.round((activeStep / (STEPS.length - 1)) * 100);
        const stepsHtml = STEPS.map((s, i) => {
            let cls = 'idle';
            if (i < activeStep) cls = 'done';
            if (i === activeStep) cls = 'active';
            const icon = cls === 'done' ? 'fa-check' : s.icon;
            return `
                <div class="step ${cls}">
                    <div class="step__node"><i class="fas ${icon}"></i></div>
                    <div class="step__label">${s.label}</div>
                </div>`;
        }).join('');

        return `
            <div class="progress-section">
                <div class="progress-section__title"><i class="fas fa-route" style="margin-right:.4rem;color:var(--brand);"></i>Progress Servis</div>
                <div class="steps-track">
                    <div class="steps-track__fill" style="--prog-w:${pct}%;"></div>
                    ${stepsHtml}
                </div>
            </div>`;
    }

    function buildTimeline(items) {
        if (!items.length) return '';
        const id = 'tl-' + Math.random().toString(36).slice(2);
        const rows = items.map(t => `
            <div class="tl-item ${t.state}">
                <div class="tl-item__dot"></div>
                <div class="tl-item__time">${t.time}</div>
                <div class="tl-item__title">${t.title}</div>
                ${t.desc ? `<div class="tl-item__desc">${t.desc}</div>` : ''}
            </div>`).join('');

        return `
            <div class="timeline-section">
                <button class="timeline-toggle" onclick="toggleTimeline(this,'${id}')">
                    <i class="fas fa-history" style="margin-right:.3rem;color:var(--brand);"></i>
                    Aktivitas Reservasi
                    <i class="fas fa-chevron-down chevron"></i>
                </button>
                <div class="timeline-body" id="${id}">
                    <div class="timeline">${rows}</div>
                </div>
            </div>`;
    }

    function buildCard(r, delay) {
        const st   = STATUS_MAP[r.status];
        const isActive  = r.status === 'process' || r.status === 'waiting';
        const isCancelled = r.status === 'cancel';

        const progressHtml = isActive ? buildStepTracker(r.step) : '';
        const timelineHtml = buildTimeline(r.timeline);

        const biayaHtml = r.biaya
            ? `<div class="cost-chip"><i class="fas fa-receipt"></i>${r.biaya}</div>`
            : '';

        return `
        <div class="resv-card"
             data-status="${r.status}"
             data-search="${(r.layanan + r.kendaraan + r.plat + r.id + r.bengkel).toLowerCase()}"
             data-date="${r.tanggal}"
             style="--c-stripe:${st.stripe}; animation-delay:${delay}ms;">

            {{-- Head --}}
            <div class="resv-card__head">
                <div class="resv-card__head-left">
                    <div class="bengkel-icon">
                        <i class="fas fa-store-alt"></i>
                    </div>
                    <div>
                        <div class="bengkel-name">${r.bengkel}</div>
                        <div class="bengkel-name branch">
                            <i class="fas fa-map-marker-alt" style="margin-right:.25rem;color:var(--brand);"></i>${r.alamat}
                            &nbsp;·&nbsp;
                            <span style="font-family:monospace; letter-spacing:.04em;">#${r.id}</span>
                        </div>
                    </div>
                </div>
                <span class="status-badge ${st.cls}">
                    <span class="status-dot"></span>
                    ${st.label}
                </span>
            </div>

            {{-- Meta --}}
            <div class="resv-card__meta">
                <div class="meta-cell">
                    <div class="meta-cell__label">Kendaraan</div>
                    <div class="meta-cell__val">
                        <i class="fas fa-car"></i>
                        <span>${r.kendaraan}</span>
                        <span class="plate-chip">${r.plat}</span>
                    </div>
                </div>
                <div class="meta-cell">
                    <div class="meta-cell__label">Tanggal Reservasi</div>
                    <div class="meta-cell__val">
                        <i class="fas fa-calendar"></i> ${formatDate(r.tanggal)}
                    </div>
                </div>
                <div class="meta-cell">
                    <div class="meta-cell__label">Jam Reservasi</div>
                    <div class="meta-cell__val">
                        <i class="fas fa-clock"></i> ${r.jam}
                    </div>
                </div>
            </div>

            ${progressHtml}
            ${timelineHtml}

            {{-- Footer --}}
            <div class="resv-card__foot">
                <div>
                    <div class="foot-service">
                        <i class="fas fa-tools"></i> ${r.layanan}
                    </div>
                    ${r.keluhan ? `<div class="foot-complaint">"${r.keluhan}"</div>` : ''}
                </div>
                <div class="foot-right">
                    ${biayaHtml}
                    <a href="{{ url('pelanggan/riwayat') }}/${r.id}" class="btn btn--ghost btn--sm">
                        <i class="fas fa-eye"></i> Lihat Detail
                    </a>
                    ${isCancelled ? '' : `<a href="#" class="btn btn--outline btn--sm">
                        <i class="fas fa-redo"></i> Pesan Ulang
                    </a>`}
                </div>
            </div>
        </div>`;
    }

    /* ══════════════════════════════════════
       FILTER & RENDER
    ══════════════════════════════════════ */
    function getFiltered() {
        return RESERVATIONS.filter(r => {
            const tabOk    = currentTab === 'all' || r.status === currentTab;
            const searchOk = !searchQuery || r.id.toLowerCase().includes(searchQuery)
                || r.layanan.toLowerCase().includes(searchQuery)
                || r.kendaraan.toLowerCase().includes(searchQuery)
                || r.plat.toLowerCase().includes(searchQuery)
                || r.bengkel.toLowerCase().includes(searchQuery);
            const dateOk   = !dateFilter || r.tanggal === dateFilter;
            return tabOk && searchOk && dateOk;
        });
    }

    function render() {
        const filtered  = getFiltered();
        const total     = filtered.length;
        const totalPages = Math.ceil(total / PER_PAGE);
        const start     = (currentPage - 1) * PER_PAGE;
        const slice     = filtered.slice(start, start + PER_PAGE);

        const cardList   = document.getElementById('cardList');
        const emptyState = document.getElementById('emptyState');
        const pagination = document.getElementById('pagination');

        if (!slice.length) {
            cardList.style.display   = 'none';
            emptyState.style.display = 'block';
            pagination.style.display = 'none';
            return;
        }

        emptyState.style.display = 'none';
        cardList.style.display   = 'flex';

        cardList.innerHTML = slice
            .map((r, i) => buildCard(r, i * 60))
            .join('');

        // Progress bar animate
        requestAnimationFrame(() => {
            cardList.querySelectorAll('.steps-track__fill').forEach(el => {
                const w = el.style.getPropertyValue('--prog-w');
                el.style.transition = 'width .9s cubic-bezier(0.16,1,0.3,1)';
            });
        });

        // Pagination
        if (totalPages <= 1) {
            pagination.style.display = 'none';
        } else {
            pagination.style.display = 'flex';
            renderPagination(totalPages);
        }
    }

    function renderPagination(totalPages) {
        const pg = document.getElementById('pagination');
        let html = `<button class="page-btn" onclick="goPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>
                        <i class="fas fa-chevron-left" style="font-size:.65rem;"></i>
                    </button>`;

        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || Math.abs(i - currentPage) <= 1) {
                html += `<button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="goPage(${i})">${i}</button>`;
            } else if (Math.abs(i - currentPage) === 2) {
                html += `<span class="page-sep">…</span>`;
            }
        }

        html += `<button class="page-btn" onclick="goPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>
                     <i class="fas fa-chevron-right" style="font-size:.65rem;"></i>
                 </button>`;
        pg.innerHTML = html;
    }

    /* ══════════════════════════════════════
       GLOBAL HANDLERS (called from HTML)
    ══════════════════════════════════════ */
    window.goPage = function(p) {
        const filtered = getFiltered();
        const max = Math.ceil(filtered.length / PER_PAGE);
        if (p < 1 || p > max) return;
        currentPage = p;
        render();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    window.toggleTimeline = function(btn, id) {
        btn.classList.toggle('open');
        const body = document.getElementById(id);
        body && body.classList.toggle('open');
    };

    /* ══════════════════════════════════════
       TABS
    ══════════════════════════════════════ */
    document.getElementById('tabBar').addEventListener('click', function(e) {
        const btn = e.target.closest('.tab-btn');
        if (!btn) return;
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentTab  = btn.dataset.tab;
        currentPage = 1;
        render();
    });

    /* ══════════════════════════════════════
       SEARCH & DATE
    ══════════════════════════════════════ */
    let searchTimer;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            searchQuery = this.value.trim().toLowerCase();
            currentPage = 1;
            render();
        }, 280);
    });

    document.getElementById('dateFilter').addEventListener('change', function() {
        dateFilter  = this.value;
        currentPage = 1;
        render();
    });

    document.getElementById('clearFilter').addEventListener('click', function() {
        document.getElementById('searchInput').value = '';
        document.getElementById('dateFilter').value  = '';
        searchQuery = '';
        dateFilter  = '';
        currentPage = 1;
        render();
    });

    /* ══════════════════════════════════════
       COUNTER ANIMATION
    ══════════════════════════════════════ */
    function animateCounters() {
        document.querySelectorAll('.counter').forEach(el => {
            const target = parseInt(el.dataset.target);
            let start    = null;
            const dur    = 1200;
            const tick   = ts => {
                if (!start) start = ts;
                const p = Math.min((ts - start) / dur, 1);
                const ease = 1 - Math.pow(2, -10 * p);
                el.textContent = Math.round(ease * target);
                if (p < 1) requestAnimationFrame(tick);
                else el.textContent = target;
            };
            requestAnimationFrame(tick);
        });
    }

    /* ══════════════════════════════════════
       INIT — simulate loading delay
    ══════════════════════════════════════ */
    setTimeout(() => {
        document.getElementById('skeletonWrap').style.display = 'none';
        render();
        animateCounters();
    }, 900);

})();
</script>

@endsection