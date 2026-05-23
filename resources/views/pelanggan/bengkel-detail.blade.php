{{-- resources/views/pelanggan/bengkel/show.blade.php --}}

@extends('layout.app-clean')
@section('title', ($bengkel->nama ?? 'Detail Bengkel') . ' — AutoNexa')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/bengkel-detail-pelanggan.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')

<div class="bd-page">

    {{-- ══════════════════════════════════════
         BREADCRUMB
    ══════════════════════════════════════ --}}
    <nav class="bd-breadcrumb">
        <a href="{{ route('landing') }}"><i class="fas fa-home"></i> Beranda</a>
        <span class="sep"><i class="fas fa-chevron-right"></i></span>
        <a href="{{ route('pelanggan.bengkel') }}">Bengkel</a>
        <span class="sep"><i class="fas fa-chevron-right"></i></span>
        <span class="cur">{{ $bengkel->nama ?? 'Detail Bengkel' }}</span>
    </nav>


    {{-- ══════════════════════════════════════
         HERO BAND
    ══════════════════════════════════════ --}}
    <div class="bd-hero anim-up">
        {{-- Cover foto bengkel --}}
        @if(!empty($bengkel->foto_cover))
            <img src="{{ asset('storage/' . $bengkel->foto_cover) }}"
                 alt="{{ $bengkel->nama }}" class="bd-hero__cover">
        @else
            <div class="bd-hero__cover-placeholder">🔧</div>
        @endif

        <div class="bd-hero__body">
            <div class="bd-hero__logo-wrap">
                {{-- Logo / Avatar bengkel --}}
                @if(!empty($bengkel->foto))
                    <img src="{{ asset('storage/' . $bengkel->foto) }}"
                         alt="{{ $bengkel->nama }}" class="bd-hero__logo">
                @else
                    <div class="bd-hero__logo-placeholder">
                        <i class="fas fa-store-alt"></i>
                    </div>
                @endif

                <div>
                    <h1 class="bd-hero__name">{{ $bengkel->nama ?? 'Nama Bengkel' }}</h1>

                    {{-- Meta row --}}
                    <div class="bd-hero__meta">
                        <span class="bd-hero__meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $bengkel->kota ?? 'Kota' }} · {{ $bengkel->alamat ?? '-' }}
                        </span>
                        <span class="bd-hero__meta-item">
                            <i class="fas fa-clock"></i>
                            {{ $bengkel->jam_buka ?? '08:00' }} – {{ $bengkel->jam_tutup ?? '17:00' }} WIB
                        </span>
                        @if(!empty($bengkel->telepon))
                        <span class="bd-hero__meta-item">
                            <i class="fas fa-phone"></i>
                            {{ $bengkel->telepon }}
                        </span>
                        @endif
                    </div>

                    {{-- Rating --}}
                    <div class="bd-rating-row" style="margin-top:.5rem;">
                        <div class="bd-stars">
                            @php $avg = round($bengkel->avg_rating ?? 0); @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star bd-star {{ $i <= $avg ? 'on' : '' }}"
                                   style="{{ $i <= $avg ? 'animation-delay:' . (($i-1)*60) . 'ms' : '' }}"></i>
                            @endfor
                        </div>
                        <span class="bd-rating-num">{{ number_format($bengkel->avg_rating ?? 0, 1) }}</span>
                        <span class="bd-rating-count">({{ number_format($bengkel->reviews_count ?? 0) }} ulasan)</span>
                    </div>
                </div>
            </div>

            {{-- Status + action --}}
            <div style="display:flex; flex-direction:column; align-items:flex-end; gap:.75rem;">
                @php
                    $isOpen = ($bengkel->status ?? 'aktif') === 'aktif';
                @endphp
                <span class="bd-status-pill {{ $isOpen ? 'bd-status-pill--open' : 'bd-status-pill--closed' }}">
                    <span class="bd-status-dot"></span>
                    {{ $isOpen ? 'Buka Sekarang' : 'Tutup' }}
                </span>
                <a href="https://wa.me/62{{ ltrim($bengkel->telepon ?? '', '0') }}"
                   target="_blank" class="btn-outline" style="font-size:.78rem;">
                    <i class="fab fa-whatsapp" style="color:#25d366;"></i> WhatsApp
                </a>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════
         STAT STRIP
    ══════════════════════════════════════ --}}
    <div class="bd-stat-strip">
        <div class="bd-stat-tile anim-up d1"
             style="--c-accent:#f97316; --c-blob:rgba(249,115,22,.07);
                    --c-icon-bg:rgba(249,115,22,.08); --c-icon-border:rgba(249,115,22,.18); --c-icon:#f97316;">
            <div class="bd-stat-tile__blob"></div>
            <div class="bd-stat-tile__icon"><i class="fas fa-calendar-check"></i></div>
            <div class="bd-stat-tile__num">{{ number_format($bengkel->reservasi_count ?? 0) }}</div>
            <div class="bd-stat-tile__label">Total Reservasi</div>
        </div>

        <div class="bd-stat-tile anim-up d2"
             style="--c-accent:#10b981; --c-blob:rgba(16,185,129,.07);
                    --c-icon-bg:rgba(16,185,129,.08); --c-icon-border:rgba(16,185,129,.18); --c-icon:#059669;">
            <div class="bd-stat-tile__blob"></div>
            <div class="bd-stat-tile__icon"><i class="fas fa-star"></i></div>
            <div class="bd-stat-tile__num">{{ number_format($bengkel->avg_rating ?? 0, 1) }}</div>
            <div class="bd-stat-tile__label">Rating Rata-rata</div>
        </div>

        <div class="bd-stat-tile anim-up d3"
             style="--c-accent:#3b82f6; --c-blob:rgba(59,130,246,.07);
                    --c-icon-bg:rgba(59,130,246,.08); --c-icon-border:rgba(59,130,246,.18); --c-icon:#2563eb;">
            <div class="bd-stat-tile__blob"></div>
            <div class="bd-stat-tile__icon"><i class="fas fa-tools"></i></div>
            <div class="bd-stat-tile__num">{{ count($bengkel->layanans ?? []) }}</div>
            <div class="bd-stat-tile__label">Layanan Tersedia</div>
        </div>

        <div class="bd-stat-tile anim-up d4"
             style="--c-accent:#8b5cf6; --c-blob:rgba(139,92,246,.07);
                    --c-icon-bg:rgba(139,92,246,.08); --c-icon-border:rgba(139,92,246,.18); --c-icon:#7c3aed;">
            <div class="bd-stat-tile__blob"></div>
            <div class="bd-stat-tile__icon"><i class="fas fa-users"></i></div>
            <div class="bd-stat-tile__num">{{ $bengkel->kapasitas ?? 8 }}</div>
            <div class="bd-stat-tile__label">Kapasitas / Hari</div>
        </div>
    </div>


    {{-- ══════════════════════════════════════
         MAIN LAYOUT
    ══════════════════════════════════════ --}}
    <div class="bd-layout">

        {{-- ────────────────────────────────
             LEFT — INFO + LAYANAN + ULASAN
        ──────────────────────────────────── --}}
        <div class="bd-main">

            {{-- 1. INFO BENGKEL ── --}}
            <div class="sec anim-up d1">
                <div class="sec__head">
                    <div class="sec__head-icon"><i class="fas fa-info-circle"></i></div>
                    <div>
                        <div class="sec__title">Informasi Bengkel</div>
                        <div class="sec__subtitle">Detail operasional & lokasi</div>
                    </div>
                </div>
                <div class="sec__body">
                    <div class="info-grid" style="margin-bottom:1.25rem;">
                        <div class="info-item">
                            <div class="info-item__label">Hari Operasional</div>
                            <div class="info-item__val">
                                <i class="fas fa-calendar-alt"></i>
                                {{ $bengkel->hari_operasional ?? 'Senin – Sabtu' }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-item__label">Jam Buka</div>
                            <div class="info-item__val">
                                <i class="fas fa-clock"></i>
                                {{ $bengkel->jam_buka ?? '08:00' }} – {{ $bengkel->jam_tutup ?? '17:00' }} WIB
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-item__label">Alamat Lengkap</div>
                            <div class="info-item__val">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $bengkel->alamat ?? '-' }}, {{ $bengkel->kota ?? '-' }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-item__label">Telepon</div>
                            <div class="info-item__val">
                                <i class="fas fa-phone"></i>
                                {{ $bengkel->telepon ?? '-' }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-item__label">Kapasitas Antrian Web</div>
                            <div class="info-item__val">
                                <i class="fas fa-layer-group"></i>
                                {{ $bengkel->kapasitas ?? 8 }} reservasi / hari
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-item__label">Kode Bengkel</div>
                            <div class="info-item__val" style="font-family:monospace; letter-spacing:.05em;">
                                #BGL-{{ str_pad($bengkel->id ?? 0, 4, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>
                    </div>

                    {{-- Peta --}}
                    <div id="bengkelMap" class="map-wrap"></div>
                </div>
            </div>


            {{-- 2. LAYANAN ── --}}
            <div class="sec anim-up d2" id="sectionLayanan">
                <div class="sec__head">
                    <div class="sec__head-icon" style="background:#ecfdf5; color:#059669; border-color:#a7f3d0;">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div>
                        <div class="sec__title">Layanan Tersedia</div>
                        <div class="sec__subtitle">Pilih layanan utama untuk reservasi</div>
                    </div>
                </div>
                <div class="sec__body">

                    <div class="alert-info">
                        <i class="fas fa-lightbulb"></i>
                        <span>Klik layanan di bawah untuk memilih layanan utama reservasimu. Estimasi biaya & durasi akan otomatis muncul.</span>
                    </div>

                    {{-- Layanan Grid --}}
                    <div class="layanan-grid" id="layananGrid">

                        @foreach($layanans as $l)
                        @php
                            $lId    = is_array($l) ? $l['id']    : $l->id;
                            $lNama  = is_array($l) ? $l['nama']  : $l->nama;
                            $lHarga = is_array($l) ? $l['harga'] : ($l->harga ?? 0);
                            $lDur   = is_array($l) ? $l['durasi']: ($l->durasi ?? 0);
                            $lDesc  = is_array($l) ? $l['desc']  : ($l->deskripsi ?? '');
                        @endphp
                        <div class="layanan-card"
                             data-id="{{ $lId }}"
                             data-nama="{{ $lNama }}"
                             data-harga="{{ $lHarga }}"
                             data-durasi="{{ $lDur }}"
                             data-desc="{{ $lDesc }}"
                             onclick="selectLayanan(this)">
                            <div class="layanan-card__check"><i class="fas fa-check"></i></div>
                            <div class="layanan-card__name">{{ $lNama }}</div>
                            <div class="layanan-card__price">Rp {{ number_format($lHarga, 0, ',', '.') }}</div>
                            <div class="layanan-card__durasi">
                                <i class="fas fa-clock"></i> {{ $lDur }} menit
                            </div>
                            <div class="layanan-card__desc">{{ $lDesc }}</div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Estimasi box setelah pilih layanan --}}
                    <div class="estimasi-box" id="estimasiBox">
                        <div class="estimasi-row">
                            <span class="estimasi-row__label">Layanan dipilih</span>
                            <span class="estimasi-row__val" id="estNama">—</span>
                        </div>
                        <div class="estimasi-row">
                            <span class="estimasi-row__label"><i class="fas fa-clock" style="margin-right:.3rem;"></i>Estimasi Durasi</span>
                            <span class="estimasi-row__val" id="estDurasi">—</span>
                        </div>
                        <div class="estimasi-row">
                            <span class="estimasi-row__label"><i class="fas fa-tag" style="margin-right:.3rem;"></i>Estimasi Harga</span>
                            <span class="estimasi-row__val brand" id="estHarga">—</span>
                        </div>
                        <p style="font-size:.72rem; color:var(--txt-3); margin-top:.5rem; line-height:1.5;">
                            * Harga dapat berubah tergantung kondisi aktual saat servis berlangsung.
                        </p>
                    </div>
                </div>
            </div>


            {{-- 3. ULASAN ── --}}
            <div class="sec anim-up d3">
                <div class="sec__head">
                    <div class="sec__head-icon" style="background:#fffbeb; color:#d97706; border-color:#fde68a;">
                        <i class="fas fa-star"></i>
                    </div>
                    <div>
                        <div class="sec__title">Ulasan Pelanggan</div>
                        <div class="sec__subtitle">{{ number_format($bengkel->reviews_count ?? 5) }} ulasan</div>
                    </div>
                </div>
                <div class="sec__body">

                    {{-- Rating overview --}}
                    <div class="rating-overview">
                        <div class="rating-big">
                            <div class="rating-big__num">{{ number_format($bengkel->avg_rating ?? 4.8, 1) }}</div>
                            <div class="rating-big__stars">
                                @for($i=1;$i<=5;$i++)
                                    <i class="fas fa-star rating-big__star"></i>
                                @endfor
                            </div>
                            <div class="rating-big__count">dari 5 bintang</div>
                        </div>

                        <div class="rating-bars">
                            @php
                                $bars = [
                                    5 => 70, 4 => 20, 3 => 6, 2 => 3, 1 => 1
                                ];
                            @endphp
                            @foreach($bars as $star => $pct)
                            <div class="rating-bar-row">
                                <span class="rating-bar-row__label">{{ $star }}</span>
                                <div class="rating-bar-track">
                                    <div class="rating-bar-fill" style="--w:{{ $pct }}%;"></div>
                                </div>
                                <span class="rating-bar-row__count">{{ $pct }}%</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Ulasan list --}}
                    <div class="ulasan-list">
                        @php
                            $dummyUlasan = [
                                ['nama'=>'Andi Saputra',  'init'=>'AS', 'bg'=>'#eef2ff', 'color'=>'#4f46e5', 'bintang'=>5, 'tgl'=>'10 Jul 2025', 'teks'=>'Servis cepat dan mekaniknya profesional. Harga sesuai estimasi, tidak ada biaya tersembunyi. Sudah jadi langganan tetap!'],
                                ['nama'=>'Budi Setiawan', 'init'=>'BS', 'bg'=>'#eff6ff', 'color'=>'#2563eb', 'bintang'=>5, 'tgl'=>'05 Jul 2025', 'teks'=>'Reservasi online sangat mudah. Langsung diterima tanpa antri. Kendaraan selesai tepat waktu sesuai estimasi.'],
                                ['nama'=>'Citra Kirana',  'init'=>'CK', 'bg'=>'#fdf2f8', 'color'=>'#db2777', 'bintang'=>4, 'tgl'=>'28 Jun 2025', 'teks'=>'Bengkel bersih dan nyaman. Mekanik ramah dan menjelaskan kondisi kendaraan dengan detail. Akan datang lagi.'],
                                ['nama'=>'Dewi Rahayu',   'init'=>'DR', 'bg'=>'#ecfdf5', 'color'=>'#059669', 'bintang'=>5, 'tgl'=>'20 Jun 2025', 'teks'=>'Sangat puas! Harga transparan dan hasil kerja memuaskan. Progress servis bisa dipantau real-time lewat aplikasi.'],
                            ];
                            $reviews = $bengkel->reviews ?? $dummyUlasan;
                        @endphp

                        @foreach($reviews as $rv)
                        @php
                            $rvNama   = is_array($rv) ? $rv['nama']   : ($rv->user->name ?? 'Pengguna');
                            $rvInit   = is_array($rv) ? $rv['init']   : strtoupper(substr($rv->user->name ?? 'U', 0, 2));
                            $rvBg     = is_array($rv) ? $rv['bg']     : '#eef2ff';
                            $rvColor  = is_array($rv) ? $rv['color']  : '#4f46e5';
                            $rvBint   = is_array($rv) ? $rv['bintang']: ($rv->rating ?? 5);
                            $rvTgl    = is_array($rv) ? $rv['tgl']    : \Carbon\Carbon::parse($rv->created_at)->format('d M Y');
                            $rvTeks   = is_array($rv) ? $rv['teks']   : ($rv->komentar ?? '');
                        @endphp
                        <div class="ulasan-item">
                            <div class="ulasan-header">
                                <div class="ulasan-avatar" style="background:{{ $rvBg }}; color:{{ $rvColor }};">
                                    {{ $rvInit }}
                                </div>
                                <div>
                                    <div class="ulasan-name">{{ $rvNama }}</div>
                                    <div class="ulasan-date">{{ $rvTgl }}</div>
                                </div>
                            </div>
                            <div class="ulasan-stars">
                                @for($i=1;$i<=5;$i++)
                                    <i class="fas fa-star ulasan-star {{ $i <= $rvBint ? 'on' : '' }}"></i>
                                @endfor
                            </div>
                            <p class="ulasan-text">{{ $rvTeks }}</p>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>

        </div>
        {{-- /bd-main --}}


        {{-- ────────────────────────────────
             RIGHT ASIDE — FORM RESERVASI
        ──────────────────────────────────── --}}
        <div class="bd-aside">

            {{-- RESERVASI CARD ── --}}
            <div class="reservasi-card anim-up d2" id="reservasiCard">

                <div class="reservasi-card__top">
                    <div class="reservasi-card__top-icon">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div class="reservasi-card__top-title">Buat Reservasi</div>
                    <div class="reservasi-card__top-sub">
                        Isi form berikut untuk booking servis di {{ $bengkel->nama ?? 'bengkel ini' }}
                    </div>
                </div>

                <div class="reservasi-card__body">

                    @auth
                    {{-- User sudah login, tampilkan form --}}

                    <div class="capacity-notice">
                        <i class="fas fa-info-circle"></i>
                        <span>Tersisa <strong id="slotsLeft">{{ $slotsHariIni ?? 6 }}</strong> slot untuk hari ini. Maks. 8 reservasi/hari.</span>
                    </div>

                    {{-- Layanan selected summary (sinkron dari kiri) --}}
                    <div class="layanan-summary" id="layananSummary">
                        <div class="layanan-summary__name" id="sumNama">—</div>
                        <div class="layanan-summary__row">
                            <span class="layanan-summary__label">Durasi estimasi</span>
                            <span class="layanan-summary__val" id="sumDurasi">—</span>
                        </div>
                        <div class="layanan-summary__row">
                            <span class="layanan-summary__label">Harga estimasi</span>
                            <span class="layanan-summary__val brand" id="sumHarga">—</span>
                        </div>
                    </div>

                    <form action="{{ route('pelanggan.booking.store') }}" method="POST" id="formReservasi">
                        @csrf
                        <input type="hidden" name="bengkel_id" value="{{ $bengkel->id ?? 1 }}">
                        <input type="hidden" name="layanan_id" id="hidLayananId">
                        <input type="hidden" name="waktu"      id="hidWaktu">

                        {{-- Tanggal --}}
                        <div class="rf-group">
                            <label class="rf-label">
                                <i class="fas fa-calendar-alt"></i>
                                Tanggal Servis <span class="req">*</span>
                            </label>
                            <input type="date" name="tanggal" id="rfTanggal"
                                   class="rf-input"
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('tanggal') }}"
                                   required>
                        </div>

                        {{-- Pilih Waktu (time slots) --}}
                        <div class="rf-group">
                            <label class="rf-label">
                                <i class="fas fa-clock"></i>
                                Pilih Waktu <span class="req">*</span>
                            </label>
                            <div class="slot-grid" id="slotGrid">
                                {{-- Dirender oleh JS berdasarkan tanggal --}}
                                @php
                                    $allSlots = [
                                        ['time'=>'08:00','count'=>0],
                                        ['time'=>'08:30','count'=>2],
                                        ['time'=>'09:00','count'=>8],
                                        ['time'=>'09:30','count'=>3],
                                        ['time'=>'10:00','count'=>1],
                                        ['time'=>'10:30','count'=>8],
                                        ['time'=>'11:00','count'=>4],
                                        ['time'=>'11:30','count'=>0],
                                        ['time'=>'13:00','count'=>2],
                                        ['time'=>'13:30','count'=>8],
                                        ['time'=>'14:00','count'=>1],
                                        ['time'=>'14:30','count'=>3],
                                        ['time'=>'15:00','count'=>0],
                                        ['time'=>'15:30','count'=>8],
                                        ['time'=>'16:00','count'=>2],
                                        ['time'=>'16:30','count'=>0],
                                    ];
                                    $kapasitas = $bengkel->kapasitas ?? 8;
                                @endphp

                                @foreach($allSlots as $slot)
                                @php
                                    $full   = $slot['count'] >= $kapasitas;
                                    $remain = $kapasitas - $slot['count'];
                                @endphp
                                <button type="button"
                                        class="slot-btn {{ $full ? 'full' : '' }}"
                                        data-time="{{ $slot['time'] }}"
                                        {{ $full ? 'disabled title=Slot penuh' : '' }}
                                        onclick="selectSlot(this)">
                                    {{ $slot['time'] }}
                                    @if($full)
                                        <span class="slot-count full">Penuh</span>
                                    @else
                                        <span class="slot-count">{{ $remain }}/{{ $kapasitas }}</span>
                                    @endif
                                </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Layanan (hidden select — diisi dari klik layanan kiri) --}}
                        <div class="rf-group">
                            <label class="rf-label">
                                <i class="fas fa-tools"></i>
                                Layanan Utama <span class="req">*</span>
                            </label>
                            <div class="rf-select-wrap">
                                <select name="layanan_id_select" id="rfLayanan" class="rf-select"
                                        onchange="selectLayananFromSelect(this)">
                                    <option value="">— Pilih Layanan —</option>
                                    @foreach($layanans as $l)
                                    @php
                                        $lId2   = is_array($l) ? $l['id']    : $l->id;
                                        $lNama2 = is_array($l) ? $l['nama']  : $l->nama;
                                        $lHrg2  = is_array($l) ? $l['harga'] : ($l->harga ?? 0);
                                        $lDur2  = is_array($l) ? $l['durasi']: ($l->durasi ?? 0);
                                        $lD2    = is_array($l) ? $l['desc']  : ($l->deskripsi ?? '');
                                    @endphp
                                    <option value="{{ $lId2 }}"
                                            data-nama="{{ $lNama2 }}"
                                            data-harga="{{ $lHrg2 }}"
                                            data-durasi="{{ $lDur2 }}"
                                            data-desc="{{ $lD2 }}">
                                        {{ $lNama2 }} — Rp {{ number_format($lHrg2,0,',','.') }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Keluhan --}}
                        <div class="rf-group">
                            <label class="rf-label">
                                <i class="fas fa-comment-alt"></i>
                                Keluhan Kendaraan <span class="req">*</span>
                            </label>
                            <textarea name="keluhan" class="rf-textarea"
                                      rows="3"
                                      placeholder="Ceritakan keluhan kendaraanmu secara detail..."
                                      required>{{ old('keluhan') }}</textarea>
                        </div>

                        {{-- Info Kendaraan (ringkas) --}}
                        <div class="rf-group">
                            <label class="rf-label">
                                <i class="fas fa-car"></i>
                                Kendaraan
                            </label>
                            <input type="text" name="kendaraan" class="rf-input"
                                   placeholder="cth: Honda Beat 2022 · B 1234 XYZ"
                                   value="{{ old('kendaraan') }}">
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="btn-reservasi" id="btnReservasi"
                                onclick="submitReservasi(this)">
                            <i class="fas fa-calendar-check"></i>
                            <span class="btn-text">Konfirmasi Reservasi</span>
                        </button>

                    </form>

                    @else
                    {{-- User belum login --}}
                    <div style="text-align:center; padding: 1.5rem 0;">
                        <div style="width:56px;height:56px;border-radius:50%;background:var(--brand-light);
                                    border:1.5px solid rgba(249,115,22,.2);
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:1.3rem;color:var(--brand);margin:0 auto 1rem;">
                            <i class="fas fa-lock"></i>
                        </div>
                        <p style="font-size:.88rem;color:var(--txt-2);margin-bottom:1.25rem;line-height:1.6;">
                            Login terlebih dahulu untuk membuat reservasi di bengkel ini.
                        </p>
                        <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}"
                           class="btn-reservasi" style="display:flex;margin-bottom:.65rem;text-decoration:none;">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Masuk & Reservasi</span>
                        </a>
                        <a href="{{ route('register') }}"
                           style="display:block;text-align:center;font-size:.8rem;color:var(--brand);
                                  font-weight:700;text-decoration:none;padding:.5rem;">
                            Belum punya akun? Daftar Gratis
                        </a>
                    </div>
                    @endauth

                </div>
            </div>

            {{-- QUICK INFO CARD ── --}}
            <div class="sec anim-up d3">
                <div class="sec__head">
                    <div class="sec__head-icon" style="background:#f5f3ff;color:#7c3aed;border-color:#ddd6fe;">
                        <i class="fas fa-location-dot"></i>
                    </div>
                    <div>
                        <div class="sec__title">Cara ke Sini</div>
                    </div>
                </div>
                <div class="sec__body" style="display:flex; flex-direction:column; gap:.7rem;">
                    <div style="display:flex;align-items:flex-start;gap:.65rem;font-size:.82rem;color:var(--txt-2);">
                        <i class="fas fa-map-marker-alt" style="color:var(--brand);margin-top:.2rem;flex-shrink:0;"></i>
                        <span>{{ $bengkel->alamat ?? 'Jl. Contoh No. 1' }}, {{ $bengkel->kota ?? 'Kota' }}</span>
                    </div>
                    <a href="https://www.google.com/maps?q={{ $bengkel->latitude ?? -6.9 }},{{ $bengkel->longitude ?? 107.6 }}"
                       target="_blank" class="btn-outline" style="justify-content:center;">
                        <i class="fas fa-map"></i> Buka di Google Maps
                    </a>
                </div>
            </div>

        </div>
        {{-- /bd-aside --}}

    </div>
    {{-- /bd-layout --}}

</div>
{{-- /bd-page --}}


{{-- FAB mobile --}}
<a href="#reservasiCard" class="fab-reservasi" onclick="scrollToReservasi(event)">
    <i class="fas fa-calendar-plus"></i> Reservasi Sekarang
</a>


@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    'use strict';

    /* ── Leaflet Map ─────────────────────────────────── */
    const lat = {{ $bengkel->latitude  ?? -6.9 }};
    const lng = {{ $bengkel->longitude ?? 107.6 }};
    const nm  = "{{ addslashes($bengkel->nama ?? 'Bengkel') }}";

    const map = L.map('bengkelMap', { zoomControl: true, scrollWheelZoom: false })
                 .setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    const icon = L.divIcon({
        className: '',
        html: `<div style="
            width:36px;height:36px;border-radius:50%;
            background:#f97316;border:3px solid #fff;
            display:flex;align-items:center;justify-content:center;
            color:#fff;font-size:14px;
            box-shadow:0 4px 12px rgba(249,115,22,.4);">
            <i class='fas fa-store-alt'></i>
        </div>`,
        iconSize: [36, 36],
        iconAnchor: [18, 18],
    });

    L.marker([lat, lng], { icon })
     .addTo(map)
     .bindPopup(`<strong style="font-size:13px;">${nm}</strong><br>
                 <small style="color:#666;">{{ $bengkel->alamat ?? '' }}</small>`)
     .openPopup();

    /* ── Select Layanan (dari card kiri) ────────────── */
    window.selectLayanan = function (el) {
        // Deselect semua
        document.querySelectorAll('.layanan-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');

        const nama   = el.dataset.nama;
        const harga  = parseInt(el.dataset.harga);
        const durasi = parseInt(el.dataset.durasi);

        // Update estimasi box
        document.getElementById('estNama').textContent   = nama;
        document.getElementById('estDurasi').textContent = durasi + ' menit';
        document.getElementById('estHarga').textContent  = 'Rp ' + harga.toLocaleString('id-ID');
        document.getElementById('estimasiBox').classList.add('show');

        // Sync ke form aside
        document.getElementById('hidLayananId').value    = el.dataset.id;
        const sumEl = document.getElementById('layananSummary');
        if (sumEl) {
            document.getElementById('sumNama').textContent   = nama;
            document.getElementById('sumDurasi').textContent = durasi + ' menit';
            document.getElementById('sumHarga').textContent  = 'Rp ' + harga.toLocaleString('id-ID');
            sumEl.classList.add('show');
        }

        // Sync select dropdown
        const sel = document.getElementById('rfLayanan');
        if (sel) sel.value = el.dataset.id;

        // Scroll ke form reservasi di mobile
        if (window.innerWidth <= 960) {
            setTimeout(() => {
                document.getElementById('reservasiCard')
                    ?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 200);
        }
    };

    /* ── Select Layanan dari dropdown (aside form) ── */
    window.selectLayananFromSelect = function (sel) {
        const opt = sel.options[sel.selectedIndex];
        if (!opt.value) return;

        const nama   = opt.dataset.nama;
        const harga  = parseInt(opt.dataset.harga);
        const durasi = parseInt(opt.dataset.durasi);

        document.getElementById('hidLayananId').value = opt.value;

        // Sync card kiri
        const card = document.querySelector(`.layanan-card[data-id="${opt.value}"]`);
        if (card) {
            document.querySelectorAll('.layanan-card').forEach(c => c.classList.remove('selected'));
            card.classList.add('selected');
            document.getElementById('estNama').textContent   = nama;
            document.getElementById('estDurasi').textContent = durasi + ' menit';
            document.getElementById('estHarga').textContent  = 'Rp ' + harga.toLocaleString('id-ID');
            document.getElementById('estimasiBox').classList.add('show');
        }

        // Sync summary
        const sumEl = document.getElementById('layananSummary');
        if (sumEl) {
            document.getElementById('sumNama').textContent   = nama;
            document.getElementById('sumDurasi').textContent = durasi + ' menit';
            document.getElementById('sumHarga').textContent  = 'Rp ' + harga.toLocaleString('id-ID');
            sumEl.classList.add('show');
        }
    };

    /* ── Select Time Slot ───────────────────────────── */
    window.selectSlot = function (btn) {
        if (btn.classList.contains('full')) return;
        document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        document.getElementById('hidWaktu').value = btn.dataset.time;
    };

    /* ── Submit with loading state ─────────────────── */
    window.submitReservasi = function (btn) {
        const layananId = document.getElementById('hidLayananId').value;
        const waktu     = document.getElementById('hidWaktu').value;
        const tanggal   = document.getElementById('rfTanggal').value;

        if (!layananId) {
            alert('Pilih layanan terlebih dahulu!');
            document.getElementById('sectionLayanan')?.scrollIntoView({ behavior:'smooth' });
            return false;
        }
        if (!tanggal) {
            alert('Pilih tanggal servis!');
            return false;
        }
        if (!waktu) {
            alert('Pilih waktu servis!');
            return false;
        }

        // Loading state
        btn.classList.add('loading');
        btn.querySelector('.btn-text').textContent = 'Memproses...';
    };

    /* ── Scroll to form (mobile FAB) ────────────────── */
    window.scrollToReservasi = function (e) {
        e.preventDefault();
        document.getElementById('reservasiCard')?.scrollIntoView({ behavior:'smooth', block:'start' });
    };

})();
</script>
@endpush