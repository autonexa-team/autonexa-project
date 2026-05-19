<?php

namespace App\Http\Controllers;

use App\Models\Bengkel;
use App\Models\Reservasi;
use App\Models\Layanan;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    public function index()
    {
        $bengkels = Bengkel::all();
        $layanans = Layanan::all();

        return view('pelanggan.reservasi', compact('bengkels', 'layanans'));
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

    public function riwayatDetail($id)
    {
        return view('pelanggan.riwayat-detail', compact('id'));
    }    
    
}