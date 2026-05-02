@extends('layouts.app')
@section('title', 'Tentang Autonexa')

@section('content')

<section class="page-header">
    <div class="container">
        <h1>Tentang Autonexa</h1>
        <p>Kenali lebih dekat platform reservasi bengkel terpercaya kami</p>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        {{-- Sejarah --}}
        <div class="row align-items-center g-5 mb-5">
            <div class="col-lg-6">
                <span class="section-tag">Siapa Kami</span>
                <h2 class="section-title">Platform Bengkel Digital Terpercaya</h2>
                <p>Autonexa berdiri sejak <strong>2020</strong> dengan misi menyederhanakan proses reservasi service kendaraan di Indonesia. Kami menghubungkan pelanggan dengan bengkel-bengkel terpercaya secara digital.</p>
                <p>Dengan teknologi modern, kami memastikan setiap proses service berjalan transparan dan pelanggan selalu mendapatkan informasi terkini tentang kendaraannya.</p>
                <div class="row g-3 mt-2">
                    <div class="col-6">
                        <div class="about-stat-card">
                            <div class="stat-big">50+</div>
                            <div class="stat-sm">Bengkel Partner</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="about-stat-card">
                            <div class="stat-big">10K+</div>
                            <div class="stat-sm">Pelanggan Aktif</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="about-stat-card">
                            <div class="stat-big">200+</div>
                            <div class="stat-sm">Mekanik Terlatih</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="about-stat-card">
                            <div class="stat-big">15</div>
                            <div class="stat-sm">Kota Tersedia</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('images/about-team.jpg') }}" alt="Tim Autonexa" class="img-fluid rounded-3 shadow">
            </div>
        </div>

        {{-- Tim --}}
        <div class="section-header text-center mb-5">
            <span class="section-tag">Tim Kami</span>
            <h2 class="section-title">Orang-orang di Balik Autonexa</h2>
        </div>
        <div class="row g-4 mb-5">
            @foreach([
                ['nama' => 'Budi Santoso',    'jabatan' => 'CEO & Founder',           'foto' => 'team-1.jpg'],
                ['nama' => 'Siti Rahayu',     'jabatan' => 'CTO',                     'foto' => 'team-2.jpg'],
                ['nama' => 'Agus Pratama',    'jabatan' => 'Head of Operations',       'foto' => 'team-3.jpg'],
                ['nama' => 'Dewi Kusuma',     'jabatan' => 'Customer Success Manager', 'foto' => 'team-4.jpg'],
            ] as $anggota)
            <div class="col-6 col-lg-3">
                <div class="team-card">
                    <div class="team-avatar">
                        <img src="{{ asset('images/' . $anggota['foto']) }}" alt="{{ $anggota['nama'] }}">
                    </div>
                    <div class="team-info">
                        <h6>{{ $anggota['nama'] }}</h6>
                        <span>{{ $anggota['jabatan'] }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Bengkel Partner --}}
        <div class="section-header text-center mb-5">
            <span class="section-tag">Jaringan Kami</span>
            <h2 class="section-title">Bengkel Terotorisasi</h2>
        </div>
        <div class="row g-4">
            @foreach($bengkel as $bengkel)
            <div class="col-md-6 col-lg-4">
                <div class="partner-card">
                    <img src="{{ $bengkel->foto ? asset('storage/'.$bengkel->foto) : asset('images/bengkel-default.jpg') }}"
                         alt="{{ $bengkel->nama }}" class="partner-img">
                    <div class="partner-info">
                        <h6>{{ $bengkel->nama }}</h6>
                        <p><i class="bi bi-geo-alt me-1"></i>{{ $bengkel->alamat }}</p>
                        <div class="partner-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= ($bengkel->reviews_avg_rating ?? 0) ? '-fill text-warning' : ' text-muted' }}"></i>
                            @endfor
                            <span class="ms-1 small text-muted">({{ number_format($bengkel->reviews_avg_rating ?? 0, 1) }})</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection