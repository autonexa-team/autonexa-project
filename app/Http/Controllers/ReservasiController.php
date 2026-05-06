<?php

namespace App\Http\Controllers;

use App\Models\Bengkel;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    public function index()
    {
        $bengkels = Bengkel::all();

        return view('pelanggan.reservasi', compact('bengkels'));
    }

    public function store(Request $request)
    {
        // nanti isi logic simpan
    }

    public function riwayat()
    {
        return view('pelanggan.riwayat');
    }

    public function profile()
    {
        return view('pelanggan.profile');
    }
    
}