{{-- resources/views/pelanggan/about.blade.php --}}
@extends('layout.app')
@section('title', 'Tentang AutoNexa')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endpush

@section('content')

{{-- ════════════════════════════════════════
     HERO
════════════════════════════════════════ --}}
<section class="ab-hero">
    <div class="ab-hero__inner">
        <div class="ab-hero__kicker">
            <span class="ab-hero__kicker-dot"></span>
            Mengenal Kami
        </div>
        <h1 class="ab-hero__title">
            Platform Bengkel<br>
            <em>Terpercaya</em> di Indonesia
        </h1>
        <p class="ab-hero__sub">
            Kenali lebih dekat visi, tim, dan jaringan bengkel partner
            yang membuat AutoNexa menjadi pilihan utama servis kendaraan.
        </p>
    </div>
</section>


{{-- ════════════════════════════════════════
     STAT STRIP
════════════════════════════════════════ --}}
<div class="ab-stats">
    <div class="ab-stat reveal d1">
        <span class="ab-stat__num">50+</span>
        <span class="ab-stat__label">Bengkel Partner</span>
    </div>
    <div class="ab-stat reveal d2">
        <span class="ab-stat__num">10K+</span>
        <span class="ab-stat__label">Pelanggan Aktif</span>
    </div>
    <div class="ab-stat reveal d3">
        <span class="ab-stat__num">200+</span>
        <span class="ab-stat__label">Mekanik Terlatih</span>
    </div>
    <div class="ab-stat reveal d4">
        <span class="ab-stat__num">15</span>
        <span class="ab-stat__label">Kota Tersedia</span>
    </div>
</div>


{{-- ════════════════════════════════════════
     SIAPA KAMI
════════════════════════════════════════ --}}
<section class="ab-section">
    <div class="ab-wrap">
        <div class="ab-two-col">

            {{-- Text --}}
            <div class="reveal">
                <span class="ab-section-label">Siapa Kami</span>
                <h2 class="ab-section-title">
                    Menyederhanakan Servis<br>Kendaraan di Indonesia
                </h2>
                <div class="ab-body">
                    <p>
                        <strong>AutoNexa</strong> berdiri sejak <strong>2020</strong> dengan misi
                        menyederhanakan proses reservasi servis kendaraan di Indonesia. Kami
                        menghubungkan pelanggan dengan bengkel-bengkel terpercaya secara digital,
                        tanpa perlu antri dan tanpa ketidakpastian.
                    </p>
                    <p>
                        Dengan teknologi modern, kami memastikan setiap proses servis berjalan
                        transparan — pelanggan selalu mendapat informasi terkini tentang kendaraannya,
                        dari awal hingga selesai.
                    </p>
                    <p>
                        Kami percaya bahwa servis kendaraan seharusnya semudah memesan makanan online.
                        Dan itulah yang sedang kami wujudkan, satu reservasi dalam satu waktu.
                    </p>
                </div>
            </div>

            {{-- Image --}}
            <div class="ab-img-wrap reveal d1">
                @if(file_exists(public_path('images/about-team.jpg')))
                    <img src="{{ asset('images/about-team.jpg') }}"
                         alt="Tim AutoNexa" class="ab-img">
                @else
                    <div class="ab-img-placeholder">🔧</div>
                @endif

                <div class="ab-award">
                    <div class="ab-award__icon">🏆</div>
                    <div>
                        <div class="ab-award__title">Platform Terpercaya</div>
                        <div class="ab-award__sub">Melayani sejak 2020</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ════════════════════════════════════════
     VISI & MISI
════════════════════════════════════════ --}}
<section class="ab-section ab-section--alt">
    <div class="ab-wrap">

        <div class="ab-section-hdr--center reveal">
            <span class="ab-section-label">Arah & Tujuan</span>
            <h2 class="ab-section-title">Visi & Misi</h2>
        </div>

        <div class="vm-grid">
            <div class="vm-card reveal d1">
                <div class="vm-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="vm-title">Visi</div>
                <p class="vm-body">
                    Menjadi platform reservasi bengkel nomor satu di Indonesia yang
                    menghubungkan jutaan pelanggan dengan bengkel terpercaya secara
                    digital, cepat, dan transparan.
                </p>
            </div>
            <div class="vm-card reveal d2">
                <div class="vm-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <div class="vm-title">Misi</div>
                <p class="vm-body">
                    Menyederhanakan pengalaman servis kendaraan melalui teknologi,
                    transparansi harga, dan layanan pelanggan yang prima — di setiap
                    langkah dari reservasi hingga kendaraan selesai diservis.
                </p>
            </div>
        </div>

    </div>
</section>


{{-- ════════════════════════════════════════
     FITUR INOVATIF (BinGo! style)
════════════════════════════════════════ --}}
<section class="ab-section">
    <div class="ab-wrap">

        <div class="ab-section-hdr--center reveal">
            <span class="ab-section-label">Keunggulan</span>
            <h2 class="ab-section-title">Fitur Inovatif</h2>
            <p class="ab-section-desc">
                Solusi digital untuk servis kendaraan yang lebih mudah, cepat, dan transparan
            </p>
        </div>

        <div class="ab-features-grid">

            <div class="ab-feature reveal d1">
                <div class="ab-feature__icon fi-orange">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="ab-feature__title">Reservasi Instan</div>
                <p class="ab-feature__desc">
                    Booking servis hanya dalam beberapa klik — kapan saja, di mana saja,
                    tanpa perlu antri atau menelepon bengkel.
                </p>
            </div>

            <div class="ab-feature reveal d2">
                <div class="ab-feature__icon fi-blue">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="ab-feature__title">Bengkel Terdekat</div>
                <p class="ab-feature__desc">
                    Temukan bengkel partner terdekat dari lokasimu secara otomatis
                    dengan peta interaktif yang mudah digunakan.
                </p>
            </div>

            <div class="ab-feature reveal d3">
                <div class="ab-feature__icon fi-green">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="ab-feature__title">Pantau Real-Time</div>
                <p class="ab-feature__desc">
                    Ikuti progress servis kendaraanmu secara langsung. Tidak perlu
                    menunggu di bengkel — pantau dari mana saja.
                </p>
            </div>

            <div class="ab-feature reveal d1">
                <div class="ab-feature__icon fi-amber">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="ab-feature__title">Notifikasi Otomatis</div>
                <p class="ab-feature__desc">
                    Terima update status servis langsung via email & SMS — dari
                    konfirmasi hingga kendaraan siap diambil.
                </p>
            </div>

            <div class="ab-feature reveal d2">
                <div class="ab-feature__icon fi-purple">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="ab-feature__title">Harga Transparan</div>
                <p class="ab-feature__desc">
                    Estimasi biaya ditampilkan sebelum servis dimulai. Tidak ada
                    biaya tersembunyi — semua jelas dari awal.
                </p>
            </div>

            <div class="ab-feature reveal d3">
                <div class="ab-feature__icon fi-rose">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="ab-feature__title">Mekanik Bersertifikat</div>
                <p class="ab-feature__desc">
                    Semua mekanik di jaringan AutoNexa telah tersertifikasi dan
                    berpengalaman menangani berbagai jenis kendaraan.
                </p>
            </div>

        </div>
    </div>
</section>


{{-- ════════════════════════════════════════
     TIM KAMI
════════════════════════════════════════ --}}
<section class="ab-section ab-section--alt">
    <div class="ab-wrap">

        <div class="ab-section-hdr--center reveal">
            <span class="ab-section-label">Tim Kami</span>
            <h2 class="ab-section-title">Orang-orang di Balik AutoNexa</h2>
            <p class="ab-section-desc">
                Tim kecil dengan semangat besar untuk membangun platform
                reservasi bengkel terbaik di Indonesia.
            </p>
        </div>

        <div class="ab-team-grid">
            @php
                $team = [
                    [
                        'nama' => "Dita Muta'aliy Soihda",
                        'nim' => '162023026',
                        'role' => 'Frontend Developer',
                        'foto' => 'team-1.jpg'
                    ],
                    [
                        'nama' => 'Sukma Oktavia',
                        'nim' => '162023016',
                        'role' => 'Backend Developer',
                        'foto' => 'team-2.jpg'
                    ],
                    [
                        'nama' => 'Auliya Az Zahrah Salsabilah',
                        'nim' => '162023026',
                        'role' => 'UI/UX Designer',
                        'foto' => 'team-3.jpg'
                    ],
                    [
                        'nama' => 'Dhina Nur Rizki Ramadani',
                        'nim' => '162023029',
                        'role' => 'Quality Assurance',
                        'foto' => 'team-4.jpg'
                    ],
                ];
            @endphp

            @foreach($team as $i => $anggota)
            @php
                $initials = collect(explode(' ', $anggota['nama']))
                    ->map(fn($w) => strtoupper($w[0]))->take(2)->join('');
                $fotoPath = public_path('images/' . $anggota['foto']);
            @endphp
            <div class="ab-team-card reveal d{{ ($i % 4) + 1 }}">
                <div class="ab-team-photo-wrap">
                    @if(file_exists($fotoPath))
                        <img src="{{ asset('images/' . $anggota['foto']) }}"
                             alt="{{ $anggota['nama'] }}" class="ab-team-photo">
                    @else
                        <div class="ab-team-initials">{{ $initials }}</div>
                    @endif
                    <div class="ab-team-nim">{{ $anggota['nim'] }}</div>
                </div>
                <div class="ab-team-body">
                    <div class="ab-team-name">{{ $anggota['nama'] }}</div>
                    <div class="ab-team-role">{{ $anggota['role'] }}</div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- ════════════════════════════════════════
     BENGKEL PARTNER
════════════════════════════════════════ --}}
<section class="ab-section">
    <div class="ab-wrap">

        <div class="ab-section-hdr--center reveal">
            <span class="ab-section-label">Jaringan Kami</span>
            <h2 class="ab-section-title">Bengkel Terotorisasi</h2>
            <p class="ab-section-desc">
                {{ isset($bengkels) ? $bengkels->count() : 0 }} bengkel terpercaya
                siap melayani kendaraan Anda di seluruh kota
            </p>
        </div>

        @if(isset($bengkels) && $bengkels->count() > 0)
        <div class="ab-partner-marquee">
            <div class="ab-partner-track">
                {{-- Render dua kali untuk loop seamless --}}
                @for($loopIdx = 0; $loopIdx < 2; $loopIdx++)
                    @foreach($bengkels as $bengkel)
                    <div class="ab-partner-card">
                        @if($bengkel->foto)
                            <img src="{{ asset('storage/' . $bengkel->foto) }}"
                                 alt="{{ $bengkel->nama }}" class="ab-partner-cover">
                        @else
                            <div class="ab-partner-placeholder">🔧</div>
                        @endif

                        <div class="ab-partner-body">
                            <div class="ab-partner-name">{{ $bengkel->nama }}</div>
                            <div class="ab-partner-addr">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $bengkel->alamat }}
                            </div>
                            <div class="ab-partner-stars">
                                @php $avg = round($bengkel->reviews_avg_rating ?? 0); @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star ab-star {{ $i <= $avg ? 'on' : '' }}"></i>
                                @endfor
                                <span class="ab-partner-score">
                                    ({{ number_format($bengkel->reviews_avg_rating ?? 0, 1) }})
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endfor
            </div>
        </div>
        @else
        <div class="ab-partner-empty">
            <i class="fas fa-store"></i>
            <p>Bengkel partner segera hadir</p>
        </div>
        @endif

    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    const els = document.querySelectorAll('.reveal');
    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
            } else {
                e.target.classList.remove('visible');
            }
        });
    }, {
        threshold: 0.15
    });
    els.forEach(el => io.observe(el));
})();
</script>
@endpush