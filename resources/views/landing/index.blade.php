@extends('layout.app')
@section('title', 'Autonexa — Reservasi Bengkel Online')

@section('content')

{{-- ═══════════════════════════════════════
     HERO
════════════════════════════════════════ --}}
<section class="hero-section">

    {{-- Background orbs --}}
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>
    <div class="hero-orb hero-orb-3"></div>

    <div class="container">
        <div class="hero-grid">

            {{-- ── LEFT ── --}}
            <div class="hero-left" data-aos="fade-right" data-aos-duration="700">

                {{-- Announcement bar --}}
                <a href="{{ route('about') }}" class="announcement-bar">
                    <span class="ann-badge">✦ Baru</span>
                    <span class="ann-text">Platform Bengkel #1 di Indonesia</span>
                    <span class="ann-arrow">→</span>
                </a>

                {{-- Headline --}}
                <h1 class="hero-title">
                    Servis Motor<br>
                    <span class="accent">Lebih Mudah,</span><br>
                    <span class="thin">Tanpa Antri.</span>
                </h1>

                <p class="hero-desc">
                    Reservasi online, pantau proses, dan dapatkan informasi service secara
                    real-time — semua dalam satu platform yang mudah digunakan.
                </p>

                {{-- CTA Buttons --}}
                <div class="hero-actions">
                    <a href="{{ route('register') }}" class="btn-primary-autonexa">
                        <i class="bi bi-calendar-plus"></i>
                        Service Sekarang
                    </a>
                </div>

                {{-- Stats --}}
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-num">50+</span>
                        <span class="stat-label">Bengkel Partner</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <span class="stat-num">10K+</span>
                        <span class="stat-label">Pelanggan Aktif</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <span class="stat-num">4.9★</span>
                        <span class="stat-label">Rating Rata-rata</span>
                    </div>
                </div>

            </div>

            {{-- ── RIGHT — Floating UI Cards ── --}}
            <div class="hero-visual d-none d-lg-block" data-aos="fade-left" data-aos-duration="700" data-aos-delay="150">

                {{-- Mini card 1: Bengkel terdekat --}}
                <div class="ui-card card-m1">
                    <div class="card-mini">
                        <div class="mc-label">Bengkel Terdekat</div>
                        <div class="mc-title">Bengkel Maju Jaya</div>
                        <div class="mc-sub">0.8 km dari lokasimu</div>
                        <div class="chip chip-green">
                            <div class="chip-dot dot-green"></div> Buka · Slot Tersedia
                        </div>
                    </div>
                </div>

                {{-- Main card: Reservasi aktif --}}
                <div class="ui-card card-main">
                    <div class="card-header-block">
                        <span class="ch-label">Reservasi Aktif</span>
                        <span class="ch-title">Honda Beat — B 3421 XY</span>
                        <span class="ch-sub">Selasa, 27 Mei · 10:00 WIB</span>
                    </div>
                    <div class="card-body-block">
                        <div class="cb-row">
                            <span class="cb-key">Mekanik</span>
                            <span class="cb-val">Rizky Pratama</span>
                        </div>
                        <div class="cb-row">
                            <span class="cb-key">Keluhan</span>
                            <span class="cb-val">Ganti Oli + CVT</span>
                        </div>
                        <div class="cb-row">
                            <span class="cb-key">Status</span>
                            <span class="cb-val">
                                <div class="chip chip-orange">
                                    <div class="chip-dot dot-orange"></div>
                                    Sedang Diperbaiki
                                </div>
                            </span>
                        </div>
                        <div class="cb-row">
                            <span class="cb-key">Estimasi Selesai</span>
                            <span class="cb-val orange">± 45 menit</span>
                        </div>
                        <div class="prog-wrap">
                            <div class="prog-head">
                                <span>Progress</span><span>65%</span>
                            </div>
                            <div class="prog-track">
                                <div class="prog-fill" style="width: 65%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mini card 2: Riwayat --}}
                <div class="ui-card card-m2">
                    <div class="card-mini">
                        <div class="mc-label">Riwayat Service</div>
                        <div class="mc-title">Tagihan Terakhir</div>
                        <div class="mc-price">Rp 185.000</div>
                        <div class="mc-sub">Oli + Filter Udara · 12 Apr</div>
                    </div>
                </div>

                {{-- Mini card 3: Notifikasi --}}
                <div class="ui-card card-m3">
                    <div class="card-mini">
                        <div class="mc-label">🔔 Notifikasi</div>
                        <div class="mc-title" style="font-size:12px;line-height:1.4;">
                            Motor kamu <strong>selesai</strong> diperbaiki!
                        </div>
                        <div class="mc-sub" style="margin-top:4px;">Baru saja · via Email</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════
     ABOUT
════════════════════════════════════════ --}}
<section class="about-section">
    <div class="container">
        <div class="about-grid">

            {{-- Image --}}
            <div class="about-img-block" data-aos="fade-right">
                @if(file_exists(public_path('images/about-mechanic.jpg')))
                    <img src="{{ asset('images/about-mechanic.jpg') }}"
                         alt="Mekanik Profesional Autonexa"
                         class="about-img">
                @else
                    <div class="about-img-placeholder">🔧</div>
                @endif

                <div class="award-badge">
                    <div class="award-icon">🏆</div>
                    <div>
                        <div class="award-title">Terpercaya</div>
                        <div class="award-sub">Sejak 2020</div>
                    </div>
                </div>
            </div>

            {{-- Text --}}
            <div data-aos="fade-left" data-aos-delay="100">
                <span class="section-tag">Tentang Kami</span>
                <h2 class="section-title">
                    Platform Servis Motor<br>yang Transparan
                </h2>
                <p class="section-desc">
                    Autonexa adalah platform digital yang memudahkan pengguna dalam melakukan
                    reservasi service kendaraan online serta memantau proses perbaikan secara
                    transparan dan real-time.
                </p>
                <ul class="about-list">
                    <li>
                        <span class="check-icon">✓</span>
                        Mekanik berpengalaman &amp; tersertifikasi
                    </li>
                    <li>
                        <span class="check-icon">✓</span>
                        Proses transparan dari awal hingga selesai
                    </li>
                    <li>
                        <span class="check-icon">✓</span>
                        Jaringan bengkel tersebar di seluruh kota
                    </li>
                </ul>
                <a href="{{ route('about') }}" class="btn-link-arrow">
                    Semua Tentang Kami
                    <i class="bi bi-arrow-right"></i>
                </a>
            </div>

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════
     FEATURES / KEUNGGULAN
════════════════════════════════════════ --}}
<section class="features-section" id="features">
    <div class="container">

        <div class="section-header" data-aos="fade-up">
            <span class="section-tag">Keunggulan</span>
            <h2 class="section-title">Kenapa Harus Autonexa?</h2>
            <p class="section-sub-desc">
                Kami hadir untuk memberikan pengalaman servis kendaraan yang lebih mudah,
                cepat, dan transparan.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="50">
                <div class="feature-card">
                    <div class="feature-icon-wrap fi-orange">
                        <i class="bi bi-wrench-adjustable-circle"></i>
                    </div>
                    <h5>Mekanik Profesional</h5>
                    <p>Semua mekanik telah tersertifikasi dan berpengalaman menangani berbagai jenis kendaraan.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="150">
                <div class="feature-card">
                    <div class="feature-icon-wrap fi-green">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h5>Reservasi Mudah</h5>
                    <p>Booking service hanya dalam beberapa klik, kapan saja dan di mana saja tanpa antri.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="250">
                <div class="feature-card">
                    <div class="feature-icon-wrap fi-yellow">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <h5>Bengkel Terdekat</h5>
                    <p>Temukan bengkel terdekat dari lokasi Anda dengan peta interaktif yang mudah digunakan.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="350">
                <div class="feature-card">
                    <div class="feature-icon-wrap fi-red">
                        <i class="bi bi-bell"></i>
                    </div>
                    <h5>Notifikasi Real-Time</h5>
                    <p>Dapatkan update status service langsung di email Anda setiap ada perubahan status.</p>
                </div>
            </div>
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════════
     CTA
════════════════════════════════════════ --}}
<section class="cta-section">
    <div class="container">
        <div class="cta-card" data-aos="zoom-in" data-aos-duration="600">
            <div class="cta-text">
                <h3>Siap service kendaraan Anda?</h3>
                <p>Booking sekarang dan rasakan kemudahan reservasi bengkel online bersama Autonexa.</p>
            </div>
            <a href="{{ route('register') }}" class="btn-white">
                <i class="bi bi-person-plus"></i>
                Daftar Gratis
            </a>
        </div>
    </div>
</section>

@endsection