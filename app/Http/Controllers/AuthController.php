<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
 
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // contoh redirect berdasarkan role (opsional)
            $role = Auth::user()->role;
            if ($role === 'admin_pusat') return redirect()->route('admin-pusat.dashboard');
            if ($role === 'admin_cabang') return redirect()->route('admin-cabang.dashboard');

            return redirect('/'); // pelanggan
        }

        return back()->with('error', 'Email atau password salah')->withInput();
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'role' => 'pelanggan'
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil');
    }    
}
