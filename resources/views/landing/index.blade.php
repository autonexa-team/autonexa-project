{{-- resources/views/home.blade.php --}}
@extends('layout.app')
@section('title', 'Autonexa — Reservasi Bengkel Motor Online')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endpush

@section('content')

{{-- ═══════════════════════════════════════
     HERO — light bg + orbs + floating cards
════════════════════════════════════════ --}}
<section class="an-hero">
    <div class="an-orb an-orb-1"></div>
    <div class="an-orb an-orb-2"></div>
    <div class="an-orb an-orb-3"></div>

    <div class="an-container an-hero__grid">

        {{-- ── KIRI: teks utama ── --}}
        <div class="an-hero__left">

            <a href="#" class="an-ann-bar" data-aos="fade-down" data-aos-delay="0">
                <span class="an-ann-badge">● Platform Bengkel #1 di Indonesia</span>
            </a>

            <h1 class="an-hero__title" data-aos="fade-up" data-aos-delay="100">
                Servis Motor<br>
                <span class="an-accent-text">Lebih Mudah,</span><br>
                <span class="an-thin">Tanpa Antri.</span>
            </h1>

            <p class="an-hero__desc" data-aos="fade-up" data-aos-delay="200">
                Reservasi online, pantau proses, dan dapatkan informasi service
                secara real-time — semua dalam satu platform yang mudah digunakan.
            </p>

            <div class="an-hero__btns" data-aos="fade-up" data-aos-delay="300">
                <a href="{{ route('register') }}" class="an-btn-primary">
                    <i class="bi bi-calendar-plus"></i> Service Sekarang
                </a>
                <a href="{{ route('pelanggan.bengkel') }}" class="an-btn-secondary">
                    <i class="bi bi-info-circle"></i> Pelajari Lebih
                </a>
            </div>

        </div>

        {{-- ── KANAN: floating UI cards ── --}}
        <div class="an-hero__visual" data-aos="fade-left" data-aos-delay="200">

            {{-- Card utama: Reservasi aktif --}}
            <div class="an-ui-card an-card-main">
                <div class="an-card-header-block">
                    <span class="an-ch-label">RESERVASI AKTIF</span>
                    <span class="an-ch-title">Honda Beat — B 3421 XY</span>
                    <span class="an-ch-sub">Selasa, 27 Mei · 10:00 WIB</span>
                </div>
                <div class="an-card-body-block">
                    <div class="an-cb-row">
                        <span class="an-cb-key">Mekanik</span>
                        <span class="an-cb-val">Rizky Pratama</span>
                    </div>
                    <div class="an-cb-row">
                        <span class="an-cb-key">Layanan</span>
                        <span class="an-cb-val">Ganti Oli + CVT</span>
                    </div>
                    <div class="an-cb-row">
                        <span class="an-cb-key">Status</span>
                        <span class="an-cb-val">
                            <span class="an-chip an-chip-brand">
                                <span class="an-chip-dot"></span> Sedang Diperbaiki
                            </span>
                        </span>
                    </div>
                    <div class="an-cb-row">
                        <span class="an-cb-key">Progress</span>
                        <span class="an-cb-val an-cb-val--pct">65%</span>
                    </div>
                    <div class="an-prog-track">
                        <div class="an-prog-fill" style="width:65%"></div>
                    </div>
                </div>
            </div>

            {{-- Row bawah: 2 mini card --}}
            <div class="an-cards-row">

                <div class="an-ui-card an-card-mini">
                    <div class="an-mc-label">BENGKEL TERDEKAT</div>
                    <div class="an-mc-title">Bengkel Maju Jaya</div>
                    <div class="an-mc-sub">0.8 km dari lokasimu</div>
                    <div class="an-chip an-chip-green">
                        <span class="an-chip-dot an-chip-dot--green"></span> Slot Tersedia
                    </div>
                </div>

                <div class="an-ui-card an-card-mini">
                    <div class="an-mc-label">NOTIFIKASI</div>
                    <div class="an-mc-title">Motor kamu <strong>selesai</strong> diperbaiki!</div>
                    <div class="an-mc-sub">Baru saja · via Email</div>
                </div>

            </div>

        </div>

    </div>
</section>


{{-- ═══════════════════════════════════════
     ABOUT + FEATURE CARDS
════════════════════════════════════════ --}}
<section class="an-section an-section--white">
    <div class="an-container an-two-col">

        <div>
            <span class="an-tag">Mengapa Ribuan Pelanggan</span>
            <h2 class="an-sec-title">Memilih Autonexa untuk Servis Kendaraan Mereka</h2>
            <p class="an-sec-body">
                Dari ganti oli rutin hingga perbaikan mesin besar, kami menghubungkan
                pelanggan dengan bengkel terpercaya menggunakan teknologi reservasi
                yang mudah dan transparan.
            </p>

            <div class="an-social-row">
                <a href="#" class="an-soc-btn" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="an-soc-btn" aria-label="Twitter/X"><i class="bi bi-twitter-x"></i></a>
                <a href="#" class="an-soc-btn" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            </div>

            <div class="an-stats-3">
                <div class="an-s3-item">
                    <div class="an-s3-n">10K+</div>
                    <div class="an-s3-l">Pelanggan Puas</div>
                </div>
                <div class="an-s3-item">
                    <div class="an-s3-n">5 Thn</div>
                    <div class="an-s3-l">Pengalaman Platform</div>
                </div>
                <div class="an-s3-item">
                    <div class="an-s3-n">15+</div>
                    <div class="an-s3-l">Kota Tersedia</div>
                </div>
            </div>
        </div>

        <div class="an-feat-stack" data-aos="fade-left" data-aos-delay="100">
            <div class="an-fc">
                <div class="an-fc-icon"><i class="bi bi-wrench-adjustable-circle"></i></div>
                <div>
                    <div class="an-fc-title">Mekanik Tersertifikasi</div>
                    <div class="an-fc-desc">Semua mekanik mitra kami sudah bersertifikat dan berpengalaman menangani motor berbagai merek.</div>
                </div>
            </div>
            <div class="an-fc">
                <div class="an-fc-icon"><i class="bi bi-calendar-check"></i></div>
                <div>
                    <div class="an-fc-title">Booking All-in-One</div>
                    <div class="an-fc-desc">Pilih bengkel, pilih layanan, pilih waktu — semua bisa dilakukan dari satu halaman tanpa ribet.</div>
                </div>
            </div>
            <div class="an-fc">
                <div class="an-fc-icon"><i class="bi bi-bell"></i></div>
                <div>
                    <div class="an-fc-title">Pantau Status Real-Time</div>
                    <div class="an-fc-desc">Lihat progres servis motor kamu secara langsung dan terima notifikasi saat selesai via email.</div>
                </div>
            </div>
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════════
     BENGKEL CARDS
════════════════════════════════════════ --}}
<section class="an-section an-section--soft">
    <div class="an-container">

        <div class="an-bengkel-header">
            <div>
                <span class="an-tag">Jaringan Bengkel Kami</span>
                <h2 class="an-sec-title" style="margin-bottom:0;">Bengkel Terpercaya di Sekitarmu</h2>
            </div>
            <p class="an-bengkel-sub">
                Dari servis rutin hingga perbaikan besar, temukan bengkel
                yang tepat sesuai kebutuhan kendaraanmu.
            </p>
        </div>

        <div class="an-bengkel-grid">
            @php
            $bengkelList = [
                ['nama'=>'Bengkel Maju Jaya',  'kota'=>'Sudirman, Jakarta Pusat','rating'=>4.8,'ulasan'=>126,'harga'=>85000,'slot'=>5,'status'=>'ok'],
                ['nama'=>'Auto Prima Service', 'kota'=>'Dago, Bandung',          'rating'=>4.6,'ulasan'=>95, 'harga'=>90000,'slot'=>2,'status'=>'few'],
                ['nama'=>'Bintang Motor',      'kota'=>'Ahmad Yani, Surabaya',   'rating'=>4.5,'ulasan'=>78, 'harga'=>75000,'slot'=>6,'status'=>'ok'],
            ];
            @endphp

            @foreach($bengkelList as $b)
            <div class="an-bcard" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="an-bcard-img">
                    <div class="an-bcard-photo"><i class="bi bi-building"></i></div>
                    <div class="an-bcard-badge">mulai Rp {{ number_format($b['harga'],0,',','.') }}</div>
                </div>
                <div class="an-bcard-body">
                    <div class="an-bcard-name">{{ $b['nama'] }}</div>
                    <div class="an-bcard-sub"><i class="bi bi-geo-alt-fill"></i> {{ $b['kota'] }}</div>
                    <div class="an-bcard-meta">
                        <div class="an-rating">
                            <span class="an-star">★</span>
                            {{ $b['rating'] }}
                            <span class="an-ulasan">({{ $b['ulasan'] }})</span>
                        </div>
                        <span class="an-slot-pill {{ $b['status']==='ok' ? 'slot-ok' : 'slot-few' }}">
                            {{ $b['slot'] }} slot {{ $b['status']==='ok' ? 'tersedia' : 'tersisa' }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="an-bengkel-footer">
            <a href="{{ route('pelanggan.bengkel') }}" class="an-btn-more">
                <i class="bi bi-map"></i> Lihat semua bengkel
            </a>
            <div class="an-nav-arrows">
                <button class="an-arr-btn" aria-label="Sebelumnya"><i class="bi bi-chevron-left"></i></button>
                <button class="an-arr-btn" aria-label="Berikutnya"><i class="bi bi-chevron-right"></i></button>
            </div>
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════════
     LAYANAN UNGGULAN
════════════════════════════════════════ --}}
<section class="an-section an-section--white">
    <div class="an-container">

        <div class="an-layanan-header">
            <div>
                <span class="an-tag">Layanan Tersedia</span>
                <h2 class="an-sec-title" style="margin-bottom:0;">Servis Apa yang Kamu Butuhkan?</h2>
            </div>
        </div>

        <div class="an-layanan-grid">
            <div class="an-lcard an-lcard--hero" data-aos="fade-up">
                <div class="an-lcard-orb"></div>
                <div class="an-lcard-eyebrow">Layanan Populer</div>
                <div class="an-lcard-name">Ganti Oli &amp; Tune Up</div>
                <div class="an-lcard-desc">Rutin setiap 3.000–5.000 km untuk performa motor terbaik</div>
                <a href="{{ route('register') }}" class="an-lcard-btn">
                    Booking sekarang <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="an-lcard an-lcard--sm" data-aos="fade-up" data-aos-delay="100">
                <div class="an-lcard-icon an-li-brand"><i class="bi bi-disc"></i></div>
                <div class="an-lcard-name an-lcard-name--sm">Servis Rem</div>
                <div class="an-lcard-desc an-lcard-desc--sm">Pemeriksaan &amp; penggantian kampas rem untuk keselamatan berkendara</div>
                <div class="an-lcard-price">mulai Rp 120.000</div>
            </div>
            <div class="an-lcard an-lcard--sm an-lcard--green" data-aos="fade-up" data-aos-delay="200">
                <div class="an-lcard-icon an-li-green"><i class="bi bi-circle"></i></div>
                <div class="an-lcard-name an-lcard-name--sm">Ganti Ban</div>
                <div class="an-lcard-desc an-lcard-desc--sm">Penggantian ban luar/dalam termasuk balancing untuk kenyamanan berkendara</div>
                <div class="an-lcard-price">mulai Rp 200.000</div>
            </div>
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════════
     STEPS — Semudah 1-2-3
════════════════════════════════════════ --}}
<section class="an-section an-section--soft">
    <div class="an-container">
        <div class="an-steps-hdr">
            <span class="an-tag">Semudah 1-2-3</span>
            <h2 class="an-sec-title">Booking Service di Autonexa</h2>
        </div>
        <div class="an-steps-grid">
            <div class="an-step-item" data-aos="fade-up" data-aos-delay="0">
                <div class="an-step-num">1</div>
                <div class="an-step-title">Pilih Bengkel</div>
                <div class="an-step-desc">Cari bengkel terdekat dari lokasimu, lihat rating dan slot tersedia</div>
            </div>
            <div class="an-step-item" data-aos="fade-up" data-aos-delay="150">
                <div class="an-step-num">2</div>
                <div class="an-step-title">Isi Detail &amp; Pilih Layanan</div>
                <div class="an-step-desc">Masukkan info kendaraan, pilih layanan, dan tentukan tanggal serta waktu</div>
            </div>
            <div class="an-step-item" data-aos="fade-up" data-aos-delay="300">
                <div class="an-step-num">3</div>
                <div class="an-step-title">Datang &amp; Selesai</div>
                <div class="an-step-desc">Datang sesuai jadwal, pantau progres servis, dan terima notifikasi saat selesai</div>
            </div>
        </div>
    </div>
</section>


@endsection