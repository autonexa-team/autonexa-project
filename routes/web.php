<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\BengkelController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanController;
use App\Models\Review;
use App\Models\Bengkel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;




// ── PUBLIC ── //

// ── auth ── //
Route::get('/', function () {
    return view('landing.index');
})->name('landing');

Route::get('/about', function () {
    return view('landing.about');
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
    $user = \App\Models\User::where('email', $request->email)->first();
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

    $user = \App\Models\User::where('email', $request->email)->first();

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

Route::get('/bengkel', [BengkelController::class, 'index'])
    ->name('pelanggan.bengkel');    



// ── AUTH (kalau pakai Breeze) ─────────
// require __DIR__.'/auth.php';


// ── PELANGGAN ─────────────────────────
Route::middleware(['auth', 'role:pelanggan'])
    ->prefix('pelanggan')
    ->name('pelanggan.')
    ->group(function () {

    Route::post('/booking', [ReservasiController::class, 'store'])
    ->name('booking.store');  

    Route::get('/riwayat', [ReservasiController::class, 'riwayat'])
        ->name('riwayat');

    Route::get('/profile', [ReservasiController::class, 'profile'])
        ->name('profile');        

});


// ── ADMIN PUSAT ───────────────────────
Route::middleware(['auth', 'role:admin_pusat'])
    ->prefix('admin-pusat')
    ->name('admin-pusat.')
    ->group(function () {

    Route::get('/dashboard', function () {
        return view('admin-pusat.dashboard');
    })->name('dashboard');    

    Route::get('/laporan', function () {
        return view('admin-pusat.laporan');
    })->name('laporan');    
    
    Route::get('/bengkel', function () {
        return view('admin-pusat.bengkel');
    })->name('bengkel');

    Route::get('/sparepart', function () {
        return view('admin-pusat.sparepart');
    })->name('sparepart');    

    Route::get('/layanan', function () {
        return view('admin-pusat.layanan');
    })->name('layanan');        

    Route::get('/user', function () {
        return view('admin-pusat.user');
    })->name('user');          

    Route::get('/review', function () {
        return view('admin-pusat.review');
    })->name('review');              

    Route::get('/reservasi', function () {
        return view('admin-pusat.reservasi');
    })->name('reservasi');      

    Route::get('/laporan/pdf', [LaporanController::class, 'pdf'])
        ->name('laporan.pdf');


    // Route::get('/bengkel', function () {
    //     $bengkels = \App\Models\Bengkel::paginate(10);

    //     return view('admin-pusat.bengkel', compact('bengkels'));
    // });

    Route::get('/bengkel/export', function () {
        return 'Export bengkel (dummy dulu)';
    })->name('bengkel.export');        
    Route::resource('bengkel', BengkelController::class);
    

    
    
        
});


// ── ADMIN CABANG ──────────────────────
Route::middleware(['auth', 'role:admin_cabang'])
    ->prefix('admin-cabang')
    ->name('admin-cabang.')
    ->group(function () {

});


// ── MEKANIK ───────────────────────────
Route::middleware(['auth', 'role:mekanik'])
    ->prefix('mekanik')
    ->name('mekanik.')
    ->group(function () {


});