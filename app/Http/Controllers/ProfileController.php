<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Reservasi;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'reservasi_selesai' => Reservasi::where('user_id', $user->id)->where('status', 'selesai')->count(),
            'reservasi_aktif' => Reservasi::where('user_id', $user->id)->where('status', 'diproses')->count(),
            'total_review' => Review::where('user_id', $user->id)->count(),
        ];

        return view('pelanggan.profile', compact(
            'user',
            'reservasis',
            'reviews',
            'stats'
        ));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

            if ($request->filled('name')) {
                $user->name = $request->name;
            }

        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::exists('public/' . $user->foto)) {
                Storage::delete('public/' . $user->foto);
            }

            $path = $request->file('foto')->store('foto-profil', 'public');
            $user->foto = $path;
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui');
    }

       public function edit()
    {
        return view('pelanggan.profile_edit', [
            'user' => Auth::user()
        ]);
    }
}