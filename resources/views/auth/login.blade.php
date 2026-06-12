{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Autonexa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="auth-body">

<div class="auth-container">

    {{-- ===== PANEL KIRI (navy, dekoratif — selaras hero landing) ===== --}}
    <div class="auth-left d-none d-lg-flex">
        <div class="an-orb an-orb-1" style="top:-80px; right:-60px;"></div>
        <div class="an-orb an-orb-2" style="bottom:-40px; left:-40px;"></div>

        <div class="auth-left-content">
            <!-- <a href="{{ route('landing') }}" class="auth-brand-sm">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="logo-lg">
            </a> -->

            <span class="an-tag" style="color:var(--an-br-l);">Autonexa Platform</span>
            <h2>Reservasi service motor jadi lebih mudah</h2>
            <p>Reservasi online, pantau proses, dan dapatkan informasi service dengan mudah.</p>

            <div class="auth-features">
                <div class="auth-feature-item">
                    <i class="bi bi-check-circle-fill"></i> Booking mudah & cepat
                </div>
                <div class="auth-feature-item">
                    <i class="bi bi-check-circle-fill"></i> Tracking real-time
                </div>
                <div class="auth-feature-item">
                    <i class="bi bi-check-circle-fill"></i> Notifikasi otomatis
                </div>
            </div>

            {{-- Mini stat strip — gaya an-stats-3 --}}
            <div class="auth-mini-stats">
                <div class="auth-mini-stat">
                    <div class="auth-mini-stat__num">24+</div>
                    <div class="auth-mini-stat__label">Bengkel</div>
                </div>
                <div class="auth-mini-stat">
                    <div class="auth-mini-stat__num">4.8</div>
                    <div class="auth-mini-stat__label">Rating</div>
                </div>
                <div class="auth-mini-stat">
                    <div class="auth-mini-stat__num">1.2k</div>
                    <div class="auth-mini-stat__label">Pengguna</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== PANEL KANAN (form) ===== --}}
    <div class="auth-right">
        <div class="auth-card">

            <div class="auth-header">
                <h4>Selamat Datang</h4>
                <p>Masuk ke akun Anda untuk melanjutkan</p>
            </div>

            @if(session('error'))
                <div class="auth-alert auth-alert-danger">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login.process') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label class="form-label">Password</label>
                        <a href="{{ route('auth.lupa-password') }}" class="text-primary small">Lupa password?</a>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="passwordInput"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="••••••••" required>
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>

                <button type="submit" class="btn btn-auth-primary w-100 btn-auth">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                </button>
            </form>

            <div class="auth-divider"><span>atau masuk dengan</span></div>

            <a href="{{ url('/auth/google/redirect') }}" class="btn-auth-outline w-100">
                <i class="bi bi-google"></i>
                Login dengan Google
            </a>

            <p class="text-center auth-footer-text">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-primary fw-semibold">Daftar sekarang</a>
            </p>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const input = document.getElementById('passwordInput');
        const icon = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });
</script>
</body>
</html>