<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\Review;

use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $reservasis = Reservasi::with('bengkel')
            ->where('user_id', $user->id)
            ->latest()
            ->take(3)
            ->get();

        $reviews = Review::with('bengkel')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $stats = [
        'total_reservasi' => Reservasi::where('user_id', $user->id)->count(),

        'reservasi_selesai' => Reservasi::where('user_id', $user->id)
            ->where('status', 'selesai')
            ->count(),

        'reservasi_aktif' => Reservasi::where('user_id', $user->id)
            ->where('status', 'diproses')
            ->count(),

        'total_review' => Review::where('user_id', $user->id)->count(),
    ];
    return view('pelanggan.profile', compact(
        'user',
        'reservasis',
        'reviews',
        'stats'
    ));
    }
}