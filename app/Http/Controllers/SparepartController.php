<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sparepart;

class SparepartController extends Controller
{
    // ──────────────────────── INDEX ──────────────────────────
    public function index()
    {
        // Proteksi role
        if (Auth::user()->role !== 'admin_pusat') {
            abort(403);
        }

        $spareparts = Sparepart::query()
            ->withCount(['bengkels'])
            ->get();

        $totalSparepart = $spareparts->count();
        $avgHarga       = $spareparts->avg('harga') ?? 0;
        $maxBengkel     = $spareparts->max('bengkels_count') ?? 0;

        return view('admin-pusat.sparepart', [
            'spareparts'    => $spareparts,
            'totalSparepart' => $totalSparepart,
            'avgHarga'      => $avgHarga,
            'maxBengkel'    => $maxBengkel,
        ]);
    }

    // ──────────────────────── STORE ──────────────────────────
    public function store(Request $request)
    {
        // Proteksi role
        if (Auth::user()->role !== 'admin_pusat') {
            abort(403);
        }

        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'harga'      => 'required|numeric|min:0',
            'deskripsi'  => 'nullable|string',
        ]);

        Sparepart::create($validated);

        return redirect()->route('admin-pusat.sparepart')
                        ->with('success', 'Sparepart berhasil ditambahkan');
    }

    // ──────────────────────── UPDATE ──────────────────────────
    public function update(Request $request, int $id)
    {
        // Proteksi role
        if (Auth::user()->role !== 'admin_pusat') {
            abort(403);
        }

        $sparepart = Sparepart::findOrFail($id);

        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'harga'      => 'required|numeric|min:0',
            'deskripsi'  => 'nullable|string',
        ]);

        $sparepart->update($validated);

        return redirect()->route('admin-pusat.sparepart')
                        ->with('success', 'Sparepart berhasil diperbarui');
    }

    // ──────────────────────── DESTROY ──────────────────────────
    public function destroy(int $id)
    {
        // Proteksi role
        if (Auth::user()->role !== 'admin_pusat') {
            abort(403);
        }

        $sparepart = Sparepart::findOrFail($id);
        $sparepart->delete();

        return redirect()->route('admin-pusat.sparepart')
                        ->with('success', 'Sparepart berhasil dihapus');
    }
}
