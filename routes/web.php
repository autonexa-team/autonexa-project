<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\BengkelController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\AdminCabangController;
use App\Http\Controllers\ReviewController;
use App\Models\Review;
use App\Models\Bengkel;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;




// ── PUBLIC ── //

// ── auth ── //
Route::get('/', function () {
    return view('landing.index');
})->name('landing');

Route::get('/about', function () {
    $bengkels = Bengkel::all();
    return view('landing.about', compact('bengkels'));
})->name('about');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.process');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/lupa-password', function () {
    return view('auth.lupa-password');
})->name('auth.lupa-password');

//lupa password masih pake dummy
Route::post('/lupa-password', function (Request $request) {
    $request->validate([
        'email' => 'required|email'
    ]);

    // cek user ada atau tidak (opsional tapi bagus)
    $user = \App\Models\User::query()->where('email', $request->email)->first();
    if (!$user) {
        return back()->with('error', 'Email tidak ditemukan');
    }

    // token dummy (hanya biar URL terlihat realistis)
    $token = Str::random(40);

    // redirect ke halaman reset + bawa email
    return redirect()->route('auth.reset-password', [
        'token' => $token,
        'email' => $request->email
    ]);
})->name('auth.lupa-password.kirim');

Route::get('/reset-password/{token}', function ($token, Request $request) {
    return view('auth.reset-password', [
        'token' => $token,
        'email' => $request->query('email') // ambil dari query string
    ]);
})->name('auth.reset-password');

Route::post('/reset-password', function (\Illuminate\Http\Request $request) {

    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed'
    ]);

    $user = \App\Models\User::query()->where('email', $request->email)->first();

    if (!$user) {
        return back()->with('error', 'Email tidak ditemukan');
    }

    $user->password = bcrypt($request->password);
    $user->save();

    return redirect()->route('login')->with('status', 'Password berhasil diubah');

})->name('password.update');

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

// ── Landing ── //

Route::get('/reservasi', [ReservasiController::class, 'index'])
    ->name('reservasi.public');  

Route::get('/bengkel', [BengkelController::class, 'pelangganIndex'])
    ->name('pelanggan.bengkel');    

Route::get('/bengkel/{id}', [BengkelController::class, 'showPelanggan'])
    ->name('pelanggan.bengkel-detail');    

// ── AUTH (kalau pakai Breeze) ─────────
// require __DIR__.'/auth.php';


// ── PELANGGAN ─────────────────────────
Route::middleware(['auth', 'role:pelanggan'])
    ->prefix('pelanggan')
    ->name('pelanggan.')
    ->group(function () {

    Route::post('/booking', [ReservasiController::class, 'store'])
    ->name('booking.store');  

    Route::get('/reservasi', [ReservasiController::class, 'create'])
        ->name('reservasi');    

    Route::get('/riwayat', [ReservasiController::class, 'riwayat'])
        ->name('riwayat');

    Route::get('/riwayat/{id}', [ReservasiController::class, 'riwayatDetail'])
        ->name('riwayat-detail');        

    Route::get('/profile', [ReservasiController::class, 'profile'])
        ->name('profile');   
        
    Route::get('/dashboard', function () {
        return view('pelanggan.dashboard');
    })->name('dashboard');        

});


// ── ADMIN PUSAT ───────────────────────
Route::middleware(['auth', 'role:admin_pusat'])
    ->prefix('admin-pusat')
    ->name('admin-pusat.')
    ->group(function () {

    Route::get('/dashboard', function () {
        return view('admin-pusat.dashboard');
    })->name('dashboard');    

    Route::get('/sparepart', [SparepartController::class, 'index'])
        ->name('sparepart');    

    Route::get('/layanan', [LayananController::class, 'index'])
        ->name('layanan');    

    Route::get('/layanan/create', [LayananController::class, 'create'])
        ->name('layanan.create');

    Route::post('/layanan', [LayananController::class, 'store'])
        ->name('layanan.store');

    Route::get('/layanan/{id}/edit', [LayananController::class, 'edit'])
        ->name('layanan.edit');

    Route::put('/layanan/{id}', [LayananController::class, 'update'])
        ->name('layanan.update');

    Route::patch(
        'layanan/{id}/toggle',
        [LayananController::class, 'toggleStatus']
    )->name('layanan.toggle');    

    Route::delete('layanan/{id}', [LayananController::class, 'destroy'])
        ->name('layanan.destroy');    

    Route::get('/user', [AdminCabangController::class, 'index'])
        ->name('user');

    // masih dummy jadi dibuat seperti ini 
    Route::get('/review', function () {

        $totalReview = 128;
        $avgRating = 4.5;
        $reviewHariIni = 12;

        $reviews = collect([
            (object)[
                'user' => (object)['name' => 'Budi'],
                'bengkel' => (object)['nama' => 'Bengkel Jaya Motor'],
                'rating' => 5,
                'komentar' => 'Pelayanan sangat bagus dan cepat!',
                'created_at' => now()
            ],
            (object)[
                'user' => (object)['name' => 'Andi'],
                'bengkel' => (object)['nama' => 'Bengkel Makmur'],
                'rating' => 3,
                'komentar' => 'Lumayan, tapi agak lama.',
                'created_at' => now()->subDays(1)
            ],
            (object)[
                'user' => (object)['name' => 'Siti'],
                'bengkel' => (object)['nama' => 'Bengkel Sejahtera'],
                'rating' => 4,
                'komentar' => 'Cukup puas dengan hasilnya.',
                'created_at' => now()->subDays(2)
            ],
        ]);

        $bengkels = collect([
            (object)['id' => 1, 'nama' => 'Bengkel Jaya Motor'],
            (object)['id' => 2, 'nama' => 'Bengkel Makmur'],
            (object)['id' => 3, 'nama' => 'Bengkel Sejahtera'],
        ]);        

        return view('admin-pusat.review', compact(
            'totalReview',
            'avgRating',
            'reviewHariIni',
            'reviews',
            'bengkels'
        ));
    }) -> name('review');

    // kalo database riviewnya udah siap, pake ini 
    // $reviews = Review::with(['user','bengkel'])->paginate(10);
    // $totalReview = Review::count();

    // Route untuk reservasi
    Route::get('/reservasi', [ReservasiController::class, 'indexAdminPusat'])
        ->name('reservasi');      

    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan');

    Route::get('/laporan-pdf', [LaporanController::class, 'exportPdf'])
        ->name('laporan-pdf');

    Route::get('/bengkel/export', function () {
        return 'Export bengkel (dummy dulu)';
    })->name('bengkel.export');        

    Route::resource('bengkel', BengkelController::class);
    
    Route::post('/sparepart', [SparepartController::class, 'store'])
        ->name('sparepart.store');

    Route::put('/sparepart/{id}', [SparepartController::class, 'update'])
        ->name('sparepart.update');

    Route::delete('/sparepart/{id}', [SparepartController::class, 'destroy'])
        ->name('sparepart.destroy');    

    Route::patch('bengkel/{bengkel}/toggle-status', [BengkelController::class, 'toggleStatus'])
        ->name('bengkel.toggle-status');    

    Route::post('/user', [AdminCabangController::class, 'store']);            

    Route::delete('/user/{user}', [AdminCabangController::class, 'destroy'])
    ->name('user.destroy');

    Route::put('/user/{user}', [AdminCabangController::class, 'update'])
        ->name('user.update');    

    // web.php
    Route::get('/admin-pusat/review/{id}', [ReviewController::class, 'show'])
        ->name('admin-pusat.review.show');        
        
});


// ── ADMIN CABANG ──────────────────────
Route::middleware(['auth', 'role:admin_cabang'])
    ->prefix('admin-cabang')
    ->name('admin-cabang.')
    ->group(function () {

    Route::get('/dashboard', function () {
        return view('admin-cabang.dashboard');
    })->name('dashboard');

    Route::get('/sparepart', function () {
        return view('admin-cabang.sparepart');
    })->name('sparepart');

    Route::get('/reservasi', function () {
        return view('admin-cabang.reservasi');
    })->name('reservasi');

    // TAMBAH RESERVASI
    Route::get('/reservasi/create', function () {
        return view('admin-cabang.reservasi-create');
    })->name('reservasi-create');

    // DETAIL RESERVASI
    Route::get('/reservasi/{id}', function ($id) {
        return view('admin-cabang.reservasi-detail', compact('id'));
    })->name('reservasi-detail');


    // LAYANAN
    Route::get('/layanan', function () {
        return view('admin-cabang.layanan');
    })->name('layanan');

    // REVIEW LIST
    Route::get('/review', function () {
        return view('admin-cabang.review');
    })->name('review');

    // DETAIL REVIEW
    Route::get('/review/{id}', function ($id) {
        return view('admin-cabang.review-detail', compact('id'));
    })->name('review.detail');

    // PELANGGAN
    Route::get('/pelanggan', function () {
        return view('admin-cabang.pelanggan-cabang');
    })->name('pelanggan-cabang');

    // LAPORAN
    Route::get('/laporan', function () {
        return view('admin-cabang.laporan');
    })->name('laporan');    

    // notifikasi
    Route::get('/notifikasi', function () {
        return view('admin-cabang.notifikasi');
    })->name('notifikasi');

    // profile    
    Route::get('/profile', function () {
        $bengkel = auth()->user()->bengkel()->with('layanan')->first();
        return view('admin-cabang.profile', [
            'bengkel' => $bengkel
        ]);
    })->name('profile');


});