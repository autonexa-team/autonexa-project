<?php

namespace App\Http\Controllers;

use App\Models\Bengkel;
use App\Models\Reservasi;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    public function index()
    {
        $bengkels = Bengkel::all();

        return view('pelanggan.reservasi', compact('bengkels'));
    }

    public function indexAdminPusat(Request $request)
    {
        $bengkelId = $request->query('bengkel');
        
        if ($bengkelId) {
            $reservasi = Reservasi::where('bengkel_id', $bengkelId)
                ->with('bengkel', 'user')
                ->orderBy('tanggal', 'desc')
                ->paginate(10);
            $bengkel = Bengkel::findOrFail($bengkelId);
        } else {
            $reservasi = Reservasi::with('bengkel', 'user')
                ->orderBy('tanggal', 'desc')
                ->paginate(10);
            $bengkel = null;
        }

        return view('admin-pusat.reservasi', compact('reservasi', 'bengkel'));
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