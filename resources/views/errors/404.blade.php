<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>404 - Halaman Tidak Ditemukan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/404.css') }}">
</head>
<body>

    <div class="gradient-bg"></div>
    <div class="mesh one"></div>
    <div class="mesh two"></div>

    <main class="container">

        <div class="card">

            <div class="logo-area">

                <div class="orbit orbit-one"></div>
                <div class="orbit orbit-two"></div>

                <div class="logo-glow"></div>
                <img src="{{ asset('assets/logo.png') }}"
                        alt="Autonexa Logo"
                        class="logo">
                </div>

            <h1 class="error-code">
                4<span>0</span>4
            </h1>

            <h2 class="title">
                Halaman Tidak Ditemukan
            </h2>
            
           <p class="description">
                Halaman yang kamu cari mungkin telah dipindahkan,
                dihapus, atau URL yang dimasukkan tidak valid.
            </p>

            <div class="button-group">

                <a href="{{ url('/') }}" class="btn-primary">
                    ← Kembali ke Beranda
                </a>

            </div>

            <div class="footer-text">
                AUTONEXA • PAGE NOT FOUND
            </div>

        </div>

    </main>

</body>
</html>