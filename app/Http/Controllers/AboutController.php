<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bengkel;
use Illuminate\Http\Request;
use App\Models\Layanan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AboutController extends Controller
{
    public function index()
    {
        $bengkels = Bengkel::where('status', 'aktif')
                        ->withAvg('reviews', 'rating')
                        ->orderByDesc('reviews_avg_rating')
                        ->get();

        return view('pelanggan.about', compact('bengkels'));
    }
}