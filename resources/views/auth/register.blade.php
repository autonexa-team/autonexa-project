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
    <div class="auth-left d-none d-lg-flex">
        <div class="auth-left-content">
            <a href="{{ route('landing') }}" class="auth-brand-sm d-flex align-items-center gap-2">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="logo-lg">
            </a>
            <h2>Bergabung dengan Autonexa</h2>
            <p>Daftar gratis dan nikmati kemudahan reservasi bengkel online.</p>
        </div>
    </div>

    <div class="auth-right">
        <div class="auth-card">
            <div class="auth-header">
                <h4>Buat Akun Baru</h4>
                <p>Isi data diri Anda untuk mendaftar</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               placeholder="Nama lengkap Anda" value="{{ old('name') }}" required>
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
                <button type="submit" class="btn btn-primary w-100 btn-auth">
                    <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                </button>
            </form>

            <p class="text-center mt-3 mb-0">
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