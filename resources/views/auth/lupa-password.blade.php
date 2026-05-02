{{-- resources/views/auth/lupa-password.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — Autonexa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="auth-body">

<div class="auth-container">

    {{-- Panel kiri --}}
    <div class="auth-left d-none d-lg-flex">
        <div class="auth-left-content">
            <a href="{{ route('landing') }}" class="auth-brand-sm d-flex align-items-center gap-2">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="logo-lg">
            </a>
            <h2>Reset Password</h2>
            <p>Masukkan email terdaftar dan kami akan kirimkan tautan untuk membuat password baru.</p>
            <div class="auth-features">
                <div class="auth-feature-item">
                    <i class="bi bi-1-circle-fill"></i> Masukkan email terdaftar
                </div>
                <div class="auth-feature-item">
                    <i class="bi bi-2-circle-fill"></i> Cek inbox atau folder spam
                </div>
                <div class="auth-feature-item">
                    <i class="bi bi-3-circle-fill"></i> Buat password baru
                </div>
            </div>
        </div>
    </div>

    {{-- Panel kanan --}}
    <div class="auth-right">
        <div class="auth-card">
            <div class="auth-header">
                <h4>Lupa Password?</h4>
                <p>Tenang, kami bantu reset password akun kamu.</p>
            </div>

            {{-- Notif sukses (setelah email terkirim) --}}
            @if(session('status'))
                <div class="auth-alert auth-alert-success">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- Notif error --}}
            @if(session('error'))
                <div class="auth-alert auth-alert-danger">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Info box --}}
            <div class="auth-info-box">
                <i class="bi bi-info-circle-fill"></i>
                <span>
                    Masukkan alamat email yang terdaftar. Link reset akan dikirim dan berlaku selama
                    <strong>60 menit</strong>.
                </span>
            </div>

            <form method="POST" action="{{ route('auth.lupa-password.kirim') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email Terdaftar</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="nama@email.com"
                            value="{{ old('email') }}"
                            required
                            autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-auth">
                    <i class="bi bi-send me-2"></i>Kirim Link Reset
                </button>
            </form>

            <div class="auth-back-link">
                <a href="{{ route('login') }}">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke halaman login
                </a>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>