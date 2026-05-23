{{-- resources/views/pelanggan/reservasi-gate.blade.php --}}
@extends('layout.app')
@section('title', 'Booking Service — Masuk Dulu')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/reservasi-gate.css') }}">
@endpush

@section('content')

<div class="rl-wrapper">

    {{-- ===== HERO CARD ===== --}}
    <div class="rl-hero-card">

        {{-- Brand --}}
        <div class="rl-brand">
            <div class="rl-brand-icon">
                <i class="bi bi-gear-wide-connected"></i>
            </div>
            <span class="rl-brand-name">Autonexa</span>
        </div>

        {{-- Teks utama --}}
        <p class="rl-eyebrow">Booking Service</p>
        <h1 class="rl-title">
            Masuk dulu untuk<br>
            <span class="rl-title-accent">reservasi bengkel</span>
        </h1>
        <p class="rl-sub">
            Buat akun gratis atau masuk untuk mulai booking servis kendaraan,
            pantau status pengerjaan, dan dapatkan riwayat servis lengkap.
        </p>

        {{-- CTA buttons --}}
        <div class="rl-btn-row">
            <a href="{{ route('login') }}" class="rl-btn-primary">
                <i class="bi bi-box-arrow-in-right"></i>
                Masuk Sekarang
            </a>
            <a href="{{ route('register') }}" class="rl-btn-secondary">
                <i class="bi bi-person-plus"></i>
                Daftar Gratis
            </a>
        </div>

        <p class="rl-guest-txt">
            Hanya ingin lihat bengkel?
            <a href="{{ route('pelanggan.bengkel') }}" class="rl-guest-link">
                Lihat daftar bengkel
            </a>
        </p>

        <hr class="rl-divider">

        {{-- Benefit cards --}}
        <div class="rl-benefits">

            <div class="rl-benefit-item">
                <div class="rl-benefit-icon bi-orange">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="rl-benefit-label">Booking Mudah</div>
                <div class="rl-benefit-sub">Reservasi online kapan saja tanpa antre</div>
            </div>

            <div class="rl-benefit-item">
                <div class="rl-benefit-icon bi-green">
                    <i class="bi bi-activity"></i>
                </div>
                <div class="rl-benefit-label">Pantau Status</div>
                <div class="rl-benefit-sub">Lihat progres servis secara real-time</div>
            </div>

            <div class="rl-benefit-item">
                <div class="rl-benefit-icon bi-blue">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="rl-benefit-label">Riwayat Servis</div>
                <div class="rl-benefit-sub">Semua riwayat tersimpan otomatis</div>
            </div>

            <div class="rl-benefit-item">
                <div class="rl-benefit-icon bi-amber">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="rl-benefit-label">Estimasi Biaya</div>
                <div class="rl-benefit-sub">Tahu perkiraan harga sebelum servis</div>
            </div>

        </div>
    </div>

    {{-- ===== STEPS CARD ===== --}}
    <div class="rl-steps-card rl-steps-flow">

        <div class="rl-steps-title">
            <i class="bi bi-list-ol"></i>
            Cara reservasi di Autonexa
        </div>

        <div class="rl-steps-row">

            {{-- STEP 1 --}}
            <div class="rl-step">

                <div class="rl-step-top">
                    <div class="rl-step-line"></div>
                    <div class="rl-step-num">1</div>
                </div>

                <div class="rl-step-info">
                    <div class="rl-step-label">Masuk</div>
                    <div class="rl-step-sub">
                        Login atau daftar akun
                    </div>
                </div>

            </div>

            {{-- STEP 2 --}}
            <div class="rl-step">

                <div class="rl-step-top">
                    <div class="rl-step-line"></div>
                    <div class="rl-step-num">2</div>
                </div>

                <div class="rl-step-info">
                    <div class="rl-step-label">Pilih bengkel</div>
                    <div class="rl-step-sub">
                        Cari yang terdekat
                    </div>
                </div>

            </div>

            {{-- STEP 3 --}}
            <div class="rl-step">

                <div class="rl-step-top">
                    <div class="rl-step-line"></div>
                    <div class="rl-step-num">3</div>
                </div>

                <div class="rl-step-info">
                    <div class="rl-step-label">Isi detail</div>
                    <div class="rl-step-sub">
                        Tanggal, layanan & kendaraan
                    </div>
                </div>

            </div>

            {{-- STEP 4 --}}
            <div class="rl-step">

                <div class="rl-step-top">
                    <div class="rl-step-num rl-step-num-last">4</div>
                </div>

                <div class="rl-step-info">
                    <div class="rl-step-label">Selesai!</div>
                    <div class="rl-step-sub">
                        Datang sesuai jadwal
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection