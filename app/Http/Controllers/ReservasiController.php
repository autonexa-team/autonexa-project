<?php

namespace App\Http\Controllers;

use App\Models\Bengkel;
use App\Models\Reservasi;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservasiController extends Controller
{
    public function index()
    {
        $bengkels = Bengkel::all();
        $layanans = Layanan::all();

        return view('pelanggan.reservasi', compact('bengkels', 'layanans'));
    }

    public function indexAdminPusat(Request $request)
    {
        $bengkelId = (int) $request->query('bengkel');

        if ($bengkelId) {

            $reservasi = Reservasi::query()
                ->where('bengkel_id', $bengkelId)
                ->with('bengkel', 'user')
                ->orderBy('tanggal', 'desc')
                ->paginate(10);

            $bengkel = Bengkel::findOrFail($bengkelId);

        } else {

            $reservasi = Reservasi::query()
                ->with('bengkel', 'user')
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

    public function riwayatDetail($id)
    {
        return view('pelanggan.riwayat-detail', compact('id'));
    }    
    

    public function publicReservasi()
    {
        if (!Auth::check()) {
            return view('pelanggan.reservasi-gate');
        }

        return $this->index();
    }    
}