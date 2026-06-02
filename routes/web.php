<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\BengkelController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\AdminCabangController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Models\Review;
use App\Models\Bengkel;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;




// ── PUBLIC ── //

// auth google
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

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

//lupa password sudah tidak dummy
Route::post('/lupa-password', [ForgotPasswordController::class, 'sendResetLink'])
    ->name('auth.lupa-password.kirim');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetPassword'])
    ->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
    ->name('password.update');

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

// ── Landing ── //

Route::get('/reservasi', [ReservasiController::class, 'publicReservasi'])
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

    Route::get('/reservasi', [ReservasiController::class, 'index'])
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

    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');   

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

    // REVIEW
    Route::get('/review', [ReviewController::class, 'index'])->name('review');
   Route::get('/review/export', [ReviewController::class, 'exportPusat'])->name('review.export');
    Route::get('/review/{id}', [ReviewController::class, 'show'])->name('review.show');

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

});

// ── API ENDPOINTS UNTUK RESERVASI ────
    Route::middleware(['auth', 'role:admin_cabang'])
        ->prefix('api')
        ->name('api.')
        ->group(function () {

    Route::get('/pelanggan-existing', [ReservasiController::class, 'getPelangganExisting'])
        ->name('pelanggan-existing');
    
    Route::get('/pelanggan/{userId}', [ReservasiController::class, 'getPelangganDetail'])
        ->name('pelanggan-detail');
    
    Route::get('/layanan-aktif', [ReservasiController::class, 'getLayananAktif'])
        ->name('layanan-aktif');
});

// ── ADMIN CABANG ──────────────────────
Route::middleware(['auth', 'role:admin_cabang'])
    ->prefix('admin-cabang')
    ->name('admin-cabang.')
    ->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/sparepart', [SparepartController::class, 'index'])
    ->name('sparepart');

    // dita nnambah ini
    Route::patch('/sparepart/{id}/stok', [SparepartController::class, 'updateStok'])
    ->name('sparepart.updateStok');

    Route::get('/reservasi', [ReservasiController::class, 'getReservasiCabang'])
        ->name('reservasi');

    // TAMBAH RESERVASI
    Route::get('/reservasi/create', function () {
        return view('admin-cabang.reservasi-create');
    })->name('reservasi-create');

    // STORE RESERVASI (dari form create)
    Route::post('/reservasi/store', [ReservasiController::class, 'storeAdminCabang'])
        ->name('reservasi.store');

    // DETAIL RESERVASI
    Route::get('/reservasi/{id}', [ReservasiController::class, 'showDetailAdminCabang'])
        ->name('reservasi-detail');

    // EDIT RESERVASI (untuk future use)
    Route::get('/reservasi/{id}/edit', [ReservasiController::class, 'showDetailAdminCabang'])
        ->name('reservasi.edit');

    // UPDATE STATUS RESERVASI
    Route::post('/reservasi/{id}/update-status', [ReservasiController::class, 'updateStatusAdminCabang'])
        ->name('reservasi.update-status');

    // UPDATE STATUS RESERVASI (Alias untuk detail view)
    Route::post('/reservasi/{id}/toggle-status', [ReservasiController::class, 'updateStatusAdminCabang'])
        ->name('reservasi.toggle-status');

    // SEARCH SPAREPART DITA NAMBAH INI
    Route::get('/sparepart/search', [SparepartController::class, 'search'])
        ->name('sparepart.search');

    // ── SPAREPART CRUD (Real-time via AJAX) ────
    Route::get('/reservasi/{id}/sparepart', [ReservasiController::class, 'sparepartIndex'])
        ->name('reservasi.sparepart.index');
    
    Route::post('/reservasi/{id}/sparepart', [ReservasiController::class, 'sparepartStore'])
        ->name('reservasi.sparepart.store');
    
    Route::patch('/reservasi/{id}/sparepart/{spId}', [ReservasiController::class, 'sparepartUpdate'])
        ->name('reservasi.sparepart.update');
    
    Route::delete('/reservasi/{id}/sparepart/{spId}', [ReservasiController::class, 'sparepartDestroy'])
        ->name('reservasi.sparepart.destroy');


    // LAYANAN
    Route::get('/layanan',
        [AdminCabangController::class, 'layanan']
    )->name('layanan');
    
    Route::post('/layanan/{id}/toggle',
        [AdminCabangController::class, 'toggleLayanan']
    )->name('layanan.toggle');
    
    // REVIEW LIST
    Route::get('/review', [ReviewController::class, 'indexCabang'])->name('review');
    Route::get('/review/export', [ReviewController::class, 'exportCabang'])->name('review.export');
    Route::get('/review/export-pdf', [ReviewController::class, 'exportPdf'])->name('review.export-pdf'); // ← pindah ke sini
    Route::get('/review/{id}', [ReviewController::class, 'showCabang'])->name('review.detail');
    
    // PELANGGAN
    Route::get('/pelanggan', [AdminCabangController::class, 'pelangganCabang'])
    ->name('pelanggan-cabang');

    // LAPORAN
    Route::get('/laporan',     [LaporanController::class, 'indexCabang'])
    ->name('laporan');

    // notifikasi
    Route::get('/notifikasi', function () {
        return view('admin-cabang.notifikasi');
    })->name('notifikasi');

    // profile    
    Route::get('/profile', function () {
        $bengkel = Auth::user()->bengkel()->with('layanan')->first();
        return view('admin-cabang.profile', [
            'bengkel' => $bengkel
        ]);
    })->name('profile');

    Route::put('/profile', [AdminCabangController::class, 'updateProfile']) ->name('profile.update');

    Route::post(
        '/reservasi/{id}/hasil-service',
        [ReservasiController::class, 'updateHasilService']
    )->name('reservasi.hasil-service');    

});