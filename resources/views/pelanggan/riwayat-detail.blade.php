@extends('layout.app-clean')

@section('title', 'Detail Reservasi #RV-' . str_pad($reservasi->id, 7, '0', STR_PAD_LEFT) . ' — AutoNexa')

@section('content')

{{-- ── Stylesheet ── --}}
<link rel="stylesheet" href="{{ asset('css/riwayat-detail-pelanggan.css') }}">

<div class="detail-page">

    {{-- ══════════════════════════════════════════
         PAGE HEADER
    ══════════════════════════════════════════ --}}
    <div class="detail-header anim-up">

        {{-- Breadcrumb --}}
        <div class="detail-header__breadcrumb">
            <a href="{{ route('pelanggan.dashboard') }}">
                <i class="fas fa-home"></i> Beranda
            </a>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <a href="{{ route('pelanggan.riwayat') }}">Riwayat Reservasi</a>
            <span class="sep"><i class="fas fa-chevron-right"></i></span>
            <span class="cur">Detail Reservasi</span>
        </div>

        <div class="detail-header__top">
            <div>
                <p class="detail-header__kicker">AutoNexa</p>
                <h1 class="detail-header__title">Detail <em>Reservasi</em></h1>
                <p class="detail-header__id">
                    <i class="fas fa-hashtag" style="font-size:.65rem; color:var(--brand);"></i>
                   RV-{{ str_pad($reservasi->id, 7, '0', STR_PAD_LEFT) }}&nbsp;·&nbsp;Dibuat {{ $reservasi->created_at ? $reservasi->created_at->translatedFormat('d F Y, H:i') . ' WIB' : '-' }}
                </p>
            </div>
            <div class="detail-header__actions">
                <button class="btn btn--outline btn--sm" onclick="window.print()">
                    <i class="fas fa-print"></i> Cetak
                </button>
                <button class="btn btn--outline btn--sm" id="shareBtn">
                    <i class="fas fa-share-nodes"></i> Bagikan
                </button>
                <a href="{{ route('pelanggan.riwayat') }}" class="btn btn--ghost btn--sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════
         STATUS HERO BANNER
    ══════════════════════════════════════════ --}}
    @php
        $statusColor = [
            'pending'      => ['bar'=>'#f59e0b','icon_bg'=>'#fffbeb','icon'=>'#d97706','icon_bd'=>'#fde68a','badge'=>'status-badge--waiting','label'=>'Menunggu Konfirmasi'],
            'dikonfirmasi' => ['bar'=>'#3b82f6','icon_bg'=>'#eff6ff','icon'=>'#2563eb','icon_bd'=>'#bfdbfe','badge'=>'status-badge--waiting','label'=>'Dikonfirmasi'],
            'diproses'     => ['bar'=>'#3b82f6','icon_bg'=>'#eff6ff','icon'=>'#2563eb','icon_bd'=>'#bfdbfe','badge'=>'status-badge--process','label'=>'Sedang Diproses'],
            'selesai'      => ['bar'=>'#10b981','icon_bg'=>'#ecfdf5','icon'=>'#059669','icon_bd'=>'#a7f3d0','badge'=>'status-badge--done','label'=>'Selesai'],
            'dibatalkan'   => ['bar'=>'#ef4444','icon_bg'=>'#fef2f2','icon'=>'#dc2626','icon_bd'=>'#fecaca','badge'=>'status-badge--cancel','label'=>'Dibatalkan'],
        ];
        $sc = $statusColor[$reservasi->status] ?? $statusColor['pending'];
    @endphp
    <div class="status-hero anim-up d1"
         style="--c-bar:{{ $sc['bar'] }}; --c-icon-bg:{{ $sc['icon_bg'] }}; --c-icon:{{ $sc['icon'] }}; --c-icon-border:{{ $sc['icon_bd'] }};">
        <div class="status-hero__bar"></div>
        <div class="status-hero__body">
            <div class="status-hero__icon-wrap">
                <i class="fas fa-store-alt"></i>
            </div>
            <div class="status-hero__text">
                <div class="status-hero__bengkel">{{ $reservasi->bengkel->nama ?? '-' }}</div>
                <div class="status-hero__meta">
                    <span><i class="fas fa-map-marker-alt"></i> {{ $reservasi->bengkel->alamat ?? '-' }}</span>
                    @if($reservasi->bengkel->telepon ?? null)
                    <span><i class="fas fa-phone"></i> {{ $reservasi->bengkel->telepon }}</span>
                    @endif
                    @if(($reservasi->bengkel->jam_buka ?? null) && ($reservasi->bengkel->jam_tutup ?? null))
                    <span><i class="fas fa-clock"></i> Buka {{ $reservasi->bengkel->jam_buka }} – {{ $reservasi->bengkel->jam_tutup }}</span>
                    @endif
                </div>
            </div>
            <div class="status-hero__right">
                <span class="status-badge {{ $sc['badge'] }}">
                    <span class="status-dot"></span>
                    {{ $sc['label'] }}
                </span>
                <span style="font-size:.72rem; color:var(--txt-3);">Update terakhir: {{ $reservasi->updated_at->timezone('Asia/Jakarta')->format('d M H:i') }} 
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════
         MAIN LAYOUT: LEFT + RIGHT ASIDE
    ══════════════════════════════════════════ --}}
    <div class="detail-layout">

        {{-- ────────────────────────────────────
             LEFT — MAIN CONTENT
        ──────────────────────────────────── --}}
        <div class="detail-main">

            {{-- ── 1. PROGRESS TRACKER ── --}}
            <div class="sec-card anim-up d1">
                <div class="sec-card__header">
                    <div class="sec-card__header-icon">
                        <i class="fas fa-route"></i>
                    </div>
                    <div>
                        <div class="sec-card__title">Progress Servis</div>
                        <div class="sec-card__subtitle">Tahap 4 dari 6 — Proses Servis berlangsung</div>
                    </div>
                </div>
                <div class="sec-card__body">

                    {{-- Alert aktif --}}
                    <div class="alert-banner alert-banner--info" style="margin-bottom:1.4rem;">
                        <i class="fas fa-circle-info"></i>
                        <div>
                            <strong>Kendaraan sedang dikerjakan.</strong>
                            Mekanik sedang melakukan servis berkala. Estimasi selesai dalam <strong>± 45 menit</strong>.
                            Kami akan mengirimkan notifikasi saat kendaraan siap diambil.
                        </div>
                    </div>

                    {{-- Steps --}}
                    <div class="progress-section__title">
                        <i class="fas fa-list-check" style="margin-right:.4rem; color:var(--brand);"></i>
                        Tahapan Servis
                    </div>

                    <div id="stepsTrack" class="steps-track">
                        {{-- Dirender oleh JS --}}
                    </div>

                </div>
            </div>


            {{-- ── 2. INFORMASI RESERVASI ── --}}
            <div class="sec-card anim-up d2">
                <div class="sec-card__header">
                    <div class="sec-card__header-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <div class="sec-card__title">Informasi Reservasi</div>
                        <div class="sec-card__subtitle">Detail jadwal dan layanan yang dipilih</div>
                    </div>
                </div>
                <div class="sec-card__body">
                    <div class="info-grid" style="margin-bottom:1.25rem;">
                        <div class="info-item">
                            <div class="info-item__label">Nomor Reservasi</div>
                            <div class="info-item__val" style="font-family:monospace; letter-spacing:.04em;">
                                #RV-{{ str_pad($reservasi->id, 7, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-item__label">Status</div>
                            <div class="info-item__val">
                                <span class="status-badge {{ $sc['badge'] }}">
                                    <span class="status-dot"></span> {{ $sc['label'] }}
                                </span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-item__label">Tanggal Reservasi</div>
                            <div class="info-item__val">
                                <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($reservasi->tanggal)->translatedFormat('l, d F Y') }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-item__label">Jam Reservasi</div>
                            <div class="info-item__val">
                                <i class="fas fa-clock"></i> {{ $reservasi->waktu }} WIB
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-item__label">Layanan Utama</div>
                            <div class="info-item__val">
                                <i class="fas fa-tools"></i> {{ $reservasi->layanan->nama ?? '-' }}
                            </div>
                        </div>
                        @if($reservasi->layanan->durasi ?? null)
                        <div class="info-item">
                            <div class="info-item__label">Estimasi Durasi</div>
                            <div class="info-item__val">
                                <i class="fas fa-hourglass-half"></i> ± {{ $reservasi->layanan->durasi }} menit
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="info-item" style="padding-top:1rem; border-top:1.5px solid var(--bg-subtle);">
                        <div class="info-item__label">Keluhan / Catatan Pelanggan</div>
                        <div class="info-item__val info-item__val--muted" style="margin-top:.35rem; line-height:1.6; display:block;">
                            "{{ $reservasi->keluhan ?? '-' }}"
                        </div>
                    </div>
                </div>
            </div>


            {{-- ── 3. INFORMASI KENDARAAN ── --}}
            <div class="sec-card anim-up d3">
                <div class="sec-card__header">
                    <div class="sec-card__header-icon" style="background:#eff6ff; color:#2563eb; border-color:#bfdbfe;">
                        <i class="fas fa-car"></i>
                    </div>
                    <div>
                        <div class="sec-card__title">Informasi Kendaraan</div>
                        <div class="sec-card__subtitle">Data kendaraan yang diservis</div>
                    </div>
                </div>
                <div class="sec-card__body">
                    <div class="info-grid info-grid--3">
                        <div class="info-item">
                            <div class="info-item__label">Merk & Model</div>
                            <div class="info-item__val">
                                <i class="fas fa-car-side"></i> {{ $reservasi->kendaraan ?? '-' }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-item__label">Plat Nomor</div>
                            <div class="info-item__val">
                                <span class="plate-chip">{{ $reservasi->plat ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- ── 5. TIMELINE AKTIVITAS ── --}}
            <div class="sec-card anim-up d4">
                <div class="sec-card__header">
                    <div class="sec-card__header-icon" style="background:#ecfdf5; color:#059669; border-color:#a7f3d0;">
                        <i class="fas fa-history"></i>
                    </div>
                    <div>
                        <div class="sec-card__title">Riwayat Aktivitas</div>
                        <div class="sec-card__subtitle">Kronologi perjalanan reservasi ini</div>
                    </div>
                </div>
                <div class="sec-card__body">
                    <div class="timeline" id="timelineTrack">
                        {{-- Dirender oleh JS --}}
                    </div>
                </div>
            </div>


            {{-- ── 6. RATING (hanya tampil jika selesai) ── --}}
            <div class="sec-card anim-up d4" id="ratingCard">
                <div class="sec-card__header">
                    <div class="sec-card__header-icon" style="background:#fffbeb; color:#d97706; border-color:#fde68a;">
                        <i class="fas fa-star"></i>
                    </div>
                    <div>
                        <div class="sec-card__title">Beri Ulasan</div>
                        <div class="sec-card__subtitle">Bantu kami meningkatkan layanan</div>
                    </div>
                </div>
                <div class="sec-card__body">

                    <div id="ratingForm">
                        <p style="font-size:.85rem; color:var(--txt-3); margin-bottom:1rem; line-height:1.6;">
                            Bagaimana pengalaman servis kendaraanmu di AutoNexa Cabang Bandung?
                        </p>
                        <div class="rating-stars" id="starGroup">
                            <button class="star-btn" data-val="1">★</button>
                            <button class="star-btn" data-val="2">★</button>
                            <button class="star-btn" data-val="3">★</button>
                            <button class="star-btn" data-val="4">★</button>
                            <button class="star-btn" data-val="5">★</button>
                        </div>
                        <textarea class="rating-textarea" id="ratingText"
                                  placeholder="Tuliskan ulasanmu di sini (opsional)..."></textarea>

                        {{-- INPUT FOTO --}}
                        <div class="foto-upload-wrap" style="margin: 1rem 0;">
                            <label style="font-size:.8rem; font-weight:700; color:var(--txt-3); display:block; margin-bottom:.5rem;">
                                <i class="fas fa-camera" style="color:var(--brand);"></i>
                                Lampirkan Foto <span style="font-weight:400;">(opsional, maks. 5 foto)</span>
                            </label>

                            <label for="fotoInput" class="foto-upload-label">
                                <i class="fas fa-plus"></i>
                                <span>Pilih Foto</span>
                                <input type="file" id="fotoInput" multiple accept="image/*" style="display:none;">
                            </label>

                            <div id="fotoPreviewGrid" style="
                                display:grid;
                                grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
                                gap:.6rem;
                                margin-top:.75rem;
                            "></div>
                            <p id="fotoError" style="color:#ef4444; font-size:.75rem; margin-top:.4rem; display:none;">
                                Maksimal 5 foto, masing-masing maks. 2MB.
                            </p>
                        </div>

                        <button class="btn btn--primary btn--full" id="submitRating">
                            <i class="fas fa-paper-plane"></i> Kirim Ulasan
                        </button>
                    </div>

                    <div class="rating-submitted" id="ratingDone">
                        <span style="font-size:1.5rem;">🎉</span>
                        <div>
                            <div style="font-weight:700; color:var(--c-done); font-size:.9rem;">Terima kasih atas ulasanmu!</div>
                            <div style="font-size:.78rem; color:var(--txt-3); margin-top:.15rem;">Ulasanmu membantu kami melayani lebih baik.</div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        {{-- /detail-main --}}


        {{-- ────────────────────────────────────
             RIGHT — ASIDE
        ──────────────────────────────────── --}}
        <div class="detail-aside">

            {{-- ── Ringkasan Cepat ── --}}
            <div class="sec-card anim-up d2">
                <div class="sec-card__header">
                    <div class="sec-card__header-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div>
                        <div class="sec-card__title">Ringkasan</div>
                    </div>
                </div>
                <div class="sec-card__body">
                    <div class="quick-stat">
                        <div class="quick-stat-row">
                            <span class="qs-label"><i class="fas fa-hashtag"></i> No. Reservasi</span>
                            <span class="qs-val" style="font-family:monospace; font-size:.75rem; letter-spacing:.04em;">RV-{{ str_pad($reservasi->id, 7, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="quick-stat-row">
                            <span class="qs-label"><i class="fas fa-store"></i> Bengkel</span>
                            <span class="qs-val" style="font-size:.77rem; text-align:right; max-width:130px;">{{ $reservasi->bengkel->nama ?? '-' }}</span>
                        </div>
                        <div class="quick-stat-row">
                            <span class="qs-label"><i class="fas fa-car"></i> Kendaraan</span>
                            <span class="qs-val">{{ $reservasi->kendaraan ?? '-' }}</span>
                        </div>
                        <div class="quick-stat-row">
                            <span class="qs-label"><i class="fas fa-calendar"></i> Tanggal</span>
                            <span class="qs-val">{{ \Carbon\Carbon::parse($reservasi->tanggal)->format('d M Y') }}</span>
                        </div>
                        <div class="quick-stat-row">
                            <span class="qs-label"><i class="fas fa-clock"></i> Jam</span>
                            <span class="qs-val">{{ $reservasi->waktu }} WIB</span>
                        </div>
                        @if($reservasi->layanan->durasi ?? null)
                        <div class="quick-stat-row">
                            <span class="qs-label"><i class="fas fa-hourglass"></i> Estimasi</span>
                            <span class="qs-val">± {{ $reservasi->layanan->durasi }} menit</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>


            {{-- ── Estimasi Biaya / Invoice ── --}}
            <div class="sec-card anim-up d3">
                <div class="sec-card__header">
                    <div class="sec-card__header-icon" style="background:#ecfdf5; color:#059669; border-color:#a7f3d0;">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div>
                        <div class="sec-card__title">Estimasi Biaya</div>
                        <div class="sec-card__subtitle">Belum final, dapat berubah</div>
                    </div>
                </div>
                <div class="sec-card__body">
                    @if($reservasi->layanan ?? null)
                    <div class="invoice-rows">
                        <div class="invoice-row">
                            <span class="invoice-row__label">{{ $reservasi->layanan->nama }}</span>
                            <span class="invoice-row__val">Rp {{ number_format($reservasi->layanan->harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="invoice-total">
                        <span class="invoice-total__label">Total Estimasi</span>
                        <span class="invoice-total__amount">
                            @if($reservasi->total_biaya)
                                Rp {{ number_format($reservasi->total_biaya, 0, ',', '.') }}
                            @else
                                Rp {{ number_format($reservasi->layanan->harga, 0, ',', '.') }}
                            @endif
                        </span>
                    </div>
                    @else
                    <p style="font-size:.85rem; color:var(--txt-3);">Data layanan tidak tersedia.</p>
                    @endif
                    <p style="font-size:.72rem; color:var(--txt-4); margin-top:.7rem; line-height:1.5; text-align:center;">
                        * Harga final ditentukan setelah servis selesai
                    </p>
                </div>
            </div>


            {{-- ── Aksi Cepat ── --}}
            <div class="sec-card anim-up d4">
                <div class="sec-card__header">
                    <div class="sec-card__header-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div>
                        <div class="sec-card__title">Aksi</div>
                    </div>
                </div>
                <div class="sec-card__body" style="display:flex; flex-direction:column; gap:.65rem;">
                    @if($reservasi->bengkel->telepon ?? null)
                    <a href="tel:{{ $reservasi->bengkel->telepon }}" class="btn btn--outline btn--full">
                        <i class="fas fa-phone"></i> Hubungi Bengkel
                    </a>
                    @endif
                    @if($reservasi->bengkel->whatsapp ?? null)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $reservasi->bengkel->whatsapp) }}" class="btn btn--outline btn--full" target="_blank">
                        <i class="fab fa-whatsapp" style="color:#25d366;"></i> Chat via WhatsApp
                    </a>
                    @endif
                    <a href="{{ route('pelanggan.reservasi') }}" class="btn btn--primary btn--full">
                        <i class="fas fa-plus"></i> Reservasi Baru
                    </a>
                    @if(!in_array($reservasi->status, ['selesai','dibatalkan']))
                    <button class="btn btn--danger btn--full" id="cancelBtn">
                        <i class="fas fa-times-circle"></i> Batalkan Reservasi
                    </button>
                    @endif
                </div>
            </div>


            {{-- ── Bengkel Info ── --}}
            <div class="sec-card anim-up d4">
                <div class="sec-card__header">
                    <div class="sec-card__header-icon" style="background:#fdf2f8; color:#db2777; border-color:#fbcfe8;">
                        <i class="fas fa-location-dot"></i>
                    </div>
                    <div>
                        <div class="sec-card__title">Info Bengkel</div>
                    </div>
                </div>
                <div class="sec-card__body">
                    <div class="quick-stat">
                        <div class="quick-stat-row">
                            <span class="qs-label"><i class="fas fa-map-marker-alt"></i> Alamat</span>
                            <span class="qs-val" style="font-size:.75rem; text-align:right; max-width:150px; line-height:1.4;">
                                {{ $reservasi->bengkel->alamat ?? '-' }}
                            </span>
                        </div>
                        @if($reservasi->bengkel->telepon ?? null)
                        <div class="quick-stat-row">
                            <span class="qs-label"><i class="fas fa-phone"></i> Telepon</span>
                            <span class="qs-val">{{ $reservasi->bengkel->telepon }}</span>
                        </div>
                        @endif
                        @if(($reservasi->bengkel->jam_buka ?? null) && ($reservasi->bengkel->jam_tutup ?? null))
                        <div class="quick-stat-row">
                            <span class="qs-label"><i class="fas fa-clock"></i> Jam Buka</span>
                            <span class="qs-val">{{ $reservasi->bengkel->jam_buka }} – {{ $reservasi->bengkel->jam_tutup }}</span>
                        </div>
                        @endif
                        @if($reservasi->bengkel->reviews_avg_rating ?? null)
                        <div class="quick-stat-row">
                            <span class="qs-label"><i class="fas fa-star"></i> Rating</span>
                            <span class="qs-val" style="color:#fbbf24;">{{ number_format($reservasi->bengkel->reviews_avg_rating, 1) }} / 5.0</span>
                        </div>
                        @endif
                    </div>
                    <a href="#" class="btn btn--outline btn--full" style="margin-top:.75rem; font-size:.78rem;">
                        <i class="fas fa-map"></i> Lihat di Peta
                    </a>
                </div>
            </div>

        </div>
        {{-- /detail-aside --}}

    </div>
    {{-- /detail-layout --}}

</div>
{{-- /detail-page --}}


{{-- FAB --}}
<a href="{{ route('pelanggan.reservasi') }}" class="fab">
    <i class="fas fa-plus"></i> Reservasi Baru
</a>


{{-- ════════════════════════════════════════════
     MODAL KONFIRMASI BATALKAN
════════════════════════════════════════════ --}}
<div id="cancelModal" style="
    display:none; position:fixed; inset:0; z-index:200;
    background:rgba(0,0,0,.45); backdrop-filter:blur(4px);
    align-items:center; justify-content:center; padding:1rem;">
    <div style="
        background:var(--bg-card); border-radius:var(--r-xl);
        border:1.5px solid var(--border); box-shadow:var(--sh-lg);
        padding:2rem 1.75rem; max-width:420px; width:100%;
        animation:fadeUp .3s var(--ease);">
        <div style="text-align:center; margin-bottom:1.4rem;">
            <div style="width:56px;height:56px;border-radius:50%;background:var(--bg-cancel);
                        display:flex;align-items:center;justify-content:center;margin:0 auto .9rem;
                        border:1.5px solid var(--bd-cancel);">
                <i class="fas fa-triangle-exclamation" style="color:var(--c-cancel); font-size:1.2rem;"></i>
            </div>
            <h3 style="font-family:'DM Serif Display',serif; font-size:1.3rem; color:var(--txt); margin-bottom:.45rem;">
                Batalkan Reservasi?
            </h3>
            <p style="font-size:.85rem; color:var(--txt-3); line-height:1.6;">
                Kamu yakin ingin membatalkan reservasi <strong>#RV-{{ str_pad($reservasi->id, 7, '0', STR_PAD_LEFT) }}</strong>?
                Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>
        <div style="display:flex; gap:.65rem;">
            <button class="btn btn--outline btn--full" id="cancelNo">
                Tidak, Kembali
            </button>
            <button class="btn btn--danger btn--full" id="cancelYes">
                <i class="fas fa-times-circle"></i> Ya, Batalkan
            </button>
        </div>
    </div>
</div>


{{-- ════════════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════════ --}}
<script>
(function () {
    'use strict';

    /* ══════════════════════════
       DATA DARI DATABASE
    ══════════════════════════ */
    const RESERVATION = @json($reservasiJs);

    const STEPS = [
        { icon: 'fa-hourglass-start', label: 'Menunggu Konfirmasi' },
        { icon: 'fa-check',           label: 'Reservasi Diterima'  },
        { icon: 'fa-car',             label: 'Kendaraan Diperiksa' },
        { icon: 'fa-wrench',          label: 'Proses Servis'       },
        { icon: 'fa-shield-alt',      label: 'Quality Check'       },
        { icon: 'fa-flag-checkered',  label: 'Selesai'             },
    ];

    /* ══════════════════════════
       RENDER STEPS TRACKER
    ══════════════════════════ */
    function renderSteps() {
        const container = document.getElementById('stepsTrack');
        if (!container) return;

        const active = RESERVATION.step;
        const pct    = active === 0 ? 0 : Math.round((active / (STEPS.length - 1)) * 100);

        // Connector fill
        const fill = document.createElement('div');
        fill.className = 'steps-track__fill';
        fill.style.setProperty('--prog-w', pct + '%');
        container.appendChild(fill);

        STEPS.forEach((s, i) => {
            let cls  = 'idle';
            if (i < active) cls = 'done';
            if (i === active) cls = 'active';
            const iconName = cls === 'done' ? 'fa-check' : s.icon;

            const step = document.createElement('div');
            step.className = `step ${cls}`;
            step.innerHTML = `
                <div class="step__node"><i class="fas ${iconName}"></i></div>
                <div class="step__label">${s.label}</div>`;
            container.appendChild(step);
        });
    }

    /* ══════════════════════════
       RENDER TIMELINE
    ══════════════════════════ */
    function renderTimeline() {
        const container = document.getElementById('timelineTrack');
        if (!container) return;

        container.innerHTML = RESERVATION.timeline.map(t => `
            <div class="tl-item ${t.state}">
                <div class="tl-item__dot"></div>
                <div class="tl-item__time">${t.time}</div>
                <div class="tl-item__title">${t.title}</div>
                ${t.desc ? `<div class="tl-item__desc">${t.desc}</div>` : ''}
            </div>`).join('');
    }

    /* ══════════════════════════
       RATING STARS
    ══════════════════════════ */
    let selectedRating = 0;
    const stars = document.querySelectorAll('.star-btn');

    stars.forEach(star => {
        star.addEventListener('mouseenter', function () {
            const val = +this.dataset.val;
            stars.forEach((s, i) => s.classList.toggle('active', i < val));
        });

        star.addEventListener('mouseleave', function () {
            stars.forEach((s, i) => s.classList.toggle('active', i < selectedRating));
        });

        star.addEventListener('click', function () {
            selectedRating = +this.dataset.val;
            stars.forEach((s, i) => s.classList.toggle('active', i < selectedRating));
        });
    });

    /* ══════════════════════════
   FOTO UPLOAD PREVIEW
══════════════════════════ */
let selectedFiles = [];

document.getElementById('fotoInput')?.addEventListener('change', function () {
    const MAX     = 5;
    const MAX_MB  = 2 * 1024 * 1024;
    const errEl   = document.getElementById('fotoError');
    const grid    = document.getElementById('fotoPreviewGrid');

    errEl.style.display = 'none';

    // Gabungkan file lama + baru, buang duplikat nama
    const newFiles = Array.from(this.files);
    const merged   = [...selectedFiles];

    for (const f of newFiles) {
        if (merged.length >= MAX) {
            errEl.textContent   = 'Maksimal 5 foto.';
            errEl.style.display = 'block';
            break;
        }
        if (f.size > MAX_MB) {
            errEl.textContent   = `File "${f.name}" melebihi 2MB.`;
            errEl.style.display = 'block';
            continue;
        }
        if (!merged.find(x => x.name === f.name)) merged.push(f);
    }

    selectedFiles = merged;
    renderPreviews();
    this.value = ''; // reset input agar file sama bisa dipilih ulang
});

function renderPreviews() {
    const grid = document.getElementById('fotoPreviewGrid');
    grid.innerHTML = '';

    selectedFiles.forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = ev => {
            const item = document.createElement('div');
            item.className = 'foto-preview-item';
            item.innerHTML = `
                <img src="${ev.target.result}" alt="preview">
                <button class="foto-remove" onclick="removePhoto(${idx})">
                    <i class="fas fa-times"></i>
                </button>`;
            grid.appendChild(item);
        };
        reader.readAsDataURL(file);
    });
}

window.removePhoto = function(idx) {
    selectedFiles.splice(idx, 1);
    renderPreviews();
};

/* ══════════════════════════
   SUBMIT RATING
══════════════════════════ */
document.getElementById('submitRating')?.addEventListener('click', async function () {
    if (!selectedRating) {
        this.textContent = 'Pilih bintang dulu!';
        this.style.background = 'var(--c-waiting)';
        setTimeout(() => {
            this.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Ulasan';
            this.style.background = '';
        }, 1800);
        return;
    }

    // Kirim ke server via FormData
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('rating',       selectedRating);
    formData.append('komentar',     document.getElementById('ratingText').value);
    formData.append('reservasi_id', '{{ $reservasi->id }}');
    formData.append('bengkel_id',   '{{ $reservasi->bengkel_id }}');

    selectedFiles.forEach(file => {
        formData.append('fotos[]', file);
    });

    this.innerHTML  = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
    this.disabled   = true;

    try {
        const res = await fetch('{{ route("pelanggan.review.store") }}', {
            method: 'POST',
            body:   formData,
        });

        const data = await res.json();

        if (res.ok) {
            document.getElementById('ratingForm').style.display = 'none';
            document.getElementById('ratingDone').classList.add('show');
        } else {
            alert(data.message ?? 'Gagal mengirim ulasan.');
            this.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Ulasan';
            this.disabled  = false;
        }
    } catch (e) {
        alert('Terjadi kesalahan. Coba lagi.');
        this.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Ulasan';
        this.disabled  = false;
    }
});

    /* ══════════════════════════
       CANCEL MODAL
    ══════════════════════════ */
    const modal     = document.getElementById('cancelModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const cancelNo  = document.getElementById('cancelNo');
    const cancelYes = document.getElementById('cancelYes');

    cancelBtn?.addEventListener('click', () => {
        modal.style.display = 'flex';
    });

    cancelNo?.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    modal?.addEventListener('click', e => {
        if (e.target === modal) modal.style.display = 'none';
    });

    cancelYes?.addEventListener('click', function () {
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Membatalkan...';
        this.disabled  = true;

        setTimeout(() => {
            modal.style.display = 'none';
            // Redirect ke riwayat setelah pembatalan
            window.location.href = '{{ route("pelanggan.riwayat") }}';
        }, 1600);
    });

    /* ══════════════════════════
       SHARE BUTTON
    ══════════════════════════ */
    document.getElementById('shareBtn')?.addEventListener('click', async function () {
        const shareData = {
            title : 'Detail Reservasi AutoNexa #RV-2025-001',
            text  : 'Cek detail reservasi servis motor saya di AutoNexa.',
            url   : window.location.href,
        };

        try {
            if (navigator.share) {
                await navigator.share(shareData);
            } else {
                await navigator.clipboard.writeText(window.location.href);
                this.innerHTML = '<i class="fas fa-check"></i> Link Disalin!';
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-share-nodes"></i> Bagikan';
                }, 2000);
            }
        } catch (e) { /* user cancelled */ }
    });

    /* ══════════════════════════
       INIT
    ══════════════════════════ */
    renderSteps();
    renderTimeline();

    // Show / hide rating card based on status
    // Tampilkan hanya jika selesai; sembunyikan jika sedang proses
    if (RESERVATION.status !== 'done') {
        const ratingCard = document.getElementById('ratingCard');
        if (ratingCard) ratingCard.style.display = 'none';
    }

})();
</script>

@endsection