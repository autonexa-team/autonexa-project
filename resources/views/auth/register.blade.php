{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Autonexa</title>
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
            <h2>Bergabung dengan Autonexa</h2>
            <p>Daftar gratis dan nikmati kemudahan reservasi bengkel online — dari booking sampai tracking real-time.</p>

            <div class="auth-features">
                <div class="auth-feature-item">
                    <i class="bi bi-check-circle-fill"></i> Gratis, tanpa biaya pendaftaran
                </div>
                <div class="auth-feature-item">
                    <i class="bi bi-check-circle-fill"></i> Akses ke 24+ bengkel rekanan
                </div>
                <div class="auth-feature-item">
                    <i class="bi bi-check-circle-fill"></i> Riwayat & notifikasi service
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
                <h4>Buat Akun Baru</h4>
                <p>Isi data diri Anda untuk mendaftar</p>
            </div>

            @if($errors->any() && !$errors->has('name') && !$errors->has('email') && !$errors->has('phone') && !$errors->has('password'))
                <div class="auth-alert auth-alert-danger">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>Terjadi kesalahan. Silakan periksa kembali data Anda.</span>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               placeholder="Nama lengkap Anda" value="{{ old('name') }}" required autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               placeholder="nama@email.com" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">No. Telepon</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               placeholder="08xxxxxxxxxx" value="{{ old('phone') }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    {{-- PASSWORD --}}
                    <div class="col-6">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="passwordInput"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Min. 8 karakter" required>
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- KONFIRMASI --}}
                    <div class="col-6">
                        <label class="form-label">Konfirmasi Password</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="confirmPasswordInput"
                                class="form-control"
                                placeholder="Ulangi password" required>
                            <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                                <i class="bi bi-eye" id="eyeConfirmIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="auth-info-box">
                    <i class="bi bi-shield-check"></i>
                    <span>Dengan mendaftar, Anda menyetujui <strong>Syarat & Ketentuan</strong> serta <strong>Kebijakan Privasi</strong> Autonexa.</span>
                </div>

                <button type="submit" class="btn btn-auth-primary w-100 btn-auth">
                    <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                </button>
            </form>

            <p class="text-center auth-footer-text">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-primary fw-semibold">Masuk di sini</a>
            </p>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // PASSWORD
    const passwordInput = document.getElementById('passwordInput');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        eyeIcon.classList.toggle('bi-eye');
        eyeIcon.classList.toggle('bi-eye-slash');
    });

    // KONFIRMASI
    const confirmInput = document.getElementById('confirmPasswordInput');
    const toggleConfirm = document.getElementById('toggleConfirmPassword');
    const eyeConfirmIcon = document.getElementById('eyeConfirmIcon');

    toggleConfirm.addEventListener('click', function () {
        const type = confirmInput.type === 'password' ? 'text' : 'password';
        confirmInput.type = type;
        eyeConfirmIcon.classList.toggle('bi-eye');
        eyeConfirmIcon.classList.toggle('bi-eye-slash');
    });

});
</script>
</body>
</html>