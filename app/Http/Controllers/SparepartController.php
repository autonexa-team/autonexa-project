<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sparepart;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Bengkel;

class SparepartController extends Controller
{
    // ──────────────────────── INDEX ──────────────────────────
    public function index()
    {

    $user = Auth::user();
    $search = request('search');

    // ADMIN PUSAT
    if ($user->role === 'admin_pusat') {

        $spareparts = Sparepart::query()
            ->withCount('bengkels')        
            
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%')
                    ->orWhere('harga', 'like', '%' . $search . '%');
                });
            })

            ->paginate(25)
            ->withQueryString();

        // Hitung hanya data yang belum dihapus
        $totalSparepart = Sparepart::query()->count('id');

        $avgHarga = Sparepart::avg('harga') ?? 0;

        $maxBengkel = Sparepart::withCount('bengkels')
            ->get()
            ->max('bengkels_count') ?? 0;

        // Total penggunaan sparepart
        $totalPenggunaan = Sparepart::withCount('bengkels')
            ->get()
            ->sum('bengkels_count');

        return view('admin-pusat.sparepart', [
            'spareparts'      => $spareparts,
            'totalSparepart'  => $totalSparepart,
            'avgHarga'        => $avgHarga,
            'maxBengkel'      => $maxBengkel,
            'totalPenggunaan' => $totalPenggunaan,
        ]);
    }

    // ADMIN CABANG dita ubah ini
    if ($user->role === 'admin_cabang') {

        $bengkel = \App\Models\Bengkel::where('admin_id', $user->id)->first();

        if (!$bengkel) {
            abort(404, 'Bengkel tidak ditemukan');
        }

        $search = request('search');
        $filter = request('filter');

        $query = $bengkel->spareparts();

        // SEARCH
        if ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        }

        // FILTER STOK
        if ($filter === 'aman') {
            $query->wherePivot('stok', '>', 5);
        }

        if ($filter === 'hampir-habis') {
            $query->wherePivot('stok', '>=', 1)
                ->wherePivot('stok', '<=', 5);
        }

        if ($filter === 'habis') {
            $query->wherePivot('stok', 0);
        }

        $spareparts = $query->paginate(10)->withQueryString();

        $totalJenis = $bengkel->spareparts()->count('sparepart.id');

        $totalStok = $bengkel->spareparts->sum(fn($item) => $item->pivot->stok);

        $hampirHabis = $bengkel->spareparts->filter(fn($item) =>
            $item->pivot->stok > 0 && $item->pivot->stok <= 5
        )->count();

        $stokHabis = $bengkel->spareparts->filter(fn($item) =>
            $item->pivot->stok == 0
        )->count();

        return view('admin-cabang.sparepart', [
            'spareparts'  => $spareparts,
            'totalJenis'  => $totalJenis,
            'totalStok'   => $totalStok,
            'hampirHabis' => $hampirHabis,
            'stokHabis'   => $stokHabis,
            'filter'      => $filter
        ]);
    }
    /* if ($user->role === 'admin_cabang') {

        $bengkel = \App\Models\Bengkel::where('admin_id', $user->id)->first();

        if (!$bengkel) {
            abort(404, 'Bengkel tidak ditemukan');
        }

        $spareparts = $bengkel->spareparts()->paginate(10);

        $totalJenis = $spareparts->count();

        $totalStok = $spareparts->sum(function ($item) {
            return $item->pivot->stok;
        });

        $hampirHabis = $spareparts->filter(function ($item) {
            return $item->pivot->stok > 0 && $item->pivot->stok <= 5;
        })->count();

        $stokHabis = $spareparts->filter(function ($item) {
            return $item->pivot->stok == 0;
        })->count();

        return view('admin-cabang.sparepart', [
            'spareparts'   => $spareparts,
            'totalJenis'   => $totalJenis,
            'totalStok'    => $totalStok,
            'hampirHabis'  => $hampirHabis,
            'stokHabis'    => $stokHabis,
        ]);
    } */

    abort(403);
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

        // Simpan sparepart master
        $sparepart = Sparepart::create($validated);

        // Ambil semua bengkel
        $bengkels = Bengkel::all();

        // Hubungkan sparepart baru ke semua bengkel
        foreach ($bengkels as $bengkel) {
            $bengkel->spareparts()->attach(
                $sparepart->id,
                [
                    'stok' => 0
                ]
            );
        }

        return redirect()
            ->route('admin-pusat.sparepart')
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

    // dita nambah ini
    public function updateStok(Request $request, int $id)
    {
        if (Auth::user()->role !== 'admin_cabang') {
            abort(403);
        }

        $bengkel = \App\Models\Bengkel::where('admin_id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'stok' => 'required|integer|min:0',
        ]);

        // Update hanya pivot stok, bukan data sparepart global
        $bengkel->spareparts()->updateExistingPivot($id, [
            'stok' => $validated['stok'],
        ]);

        return redirect()->route('admin-cabang.sparepart')
                        ->with('success', 'Stok berhasil diperbarui');
    }

    /* DITA NAMBAH INI */
    public function search(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'admin_cabang') {
            abort(403);
        }

        // Gunakan relasi hasOne di model User (konsisten dengan controller lain)
        $bengkel = $user->bengkel ?? \App\Models\Bengkel::where('admin_id', $user->id)->first();

        if (!$bengkel) {
            return response()->json([]);
        }

        $search = $request->search;

        $spareparts = $bengkel->spareparts()
            ->when($search, function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%");
            })
            ->get()
            ->map(function ($item) {
                return [
                    'id'    => $item->id,
                    'nama'  => $item->nama,
                    'stok'  => $item->pivot->stok,
                    'harga' => $item->harga,
                ];
            });

        return response()->json($spareparts);
    }
}