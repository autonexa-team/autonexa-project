<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — Autonexa</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="auth-body">

<div class="auth-container">

    {{-- PANEL KIRI --}}
    <div class="auth-left d-none d-lg-flex">
        <div class="auth-left-content">
            <div class="auth-brand-logo">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <h2>Password Baru</h2>
            <p>Buat password baru yang aman untuk akun kamu.</p>

            <div class="auth-features">
                <div class="auth-feature-item">
                    <i class="bi bi-check-circle-fill"></i> Minimal 6 karakter
                </div>
                <div class="auth-feature-item">
                    <i class="bi bi-check-circle-fill"></i> Gunakan kombinasi huruf & angka
                </div>
                <div class="auth-feature-item">
                    <i class="bi bi-check-circle-fill"></i> Jangan bagikan ke orang lain
                </div>
            </div>
        </div>
    </div>

    {{-- PANEL KANAN --}}
    <div class="auth-right">
        <div class="auth-card">

            <div class="auth-header">
                <h4>Reset Password</h4>
                <p>Masukkan password baru kamu</p>
            </div>

            {{-- ERROR --}}
            @if(session('error'))
                <div class="auth-alert auth-alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                {{-- TOKEN --}}
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- EMAIL --}}
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email"
                        class="form-control"
                        required>
                </div>

                {{-- PASSWORD --}}
                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <div class="input-group">
                        <input type="password" name="password" id="passwordInput"
                            class="form-control @error('password') is-invalid @enderror"
                            required>

                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>

                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- KONFIRMASI --}}
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <div class="input-group">
                        <input type="password" name="password_confirmation" id="confirmPasswordInput"
                            class="form-control"
                            required>

                        <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                            <i class="bi bi-eye" id="eyeConfirmIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-auth">
                    <i class="bi bi-check2-circle me-2"></i>
                    Simpan Password
                </button>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // PASSWORD UTAMA
    const passwordInput = document.getElementById('passwordInput');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;

        eyeIcon.classList.toggle('bi-eye');
        eyeIcon.classList.toggle('bi-eye-slash');
    });

    // KONFIRMASI PASSWORD
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