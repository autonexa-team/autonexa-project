<?php

namespace App\Http\Controllers;

use App\Models\Bengkel;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class BengkelController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['pelangganIndex']);
    }


    public function index()
    {
        // 🔒 proteksi role
        if (Auth::user()->role !== 'admin_pusat') {
            abort(403);
        }

        $bengkels = Bengkel::with('layanan')
            ->withCount(['reservasis'])
            ->withAvg('review as reviews_avg_rating', 'rating')
            ->paginate(12);

            //karna masih tahapan tampilan, jadi untuk ini nanti aja diurus sama sukma
        return view('admin-pusat.bengkel', [
            'bengkels'      => $bengkels,
            // 'kotaList'      => Bengkel::distinct()->pluck('kota'),
            // 'totalAktif'    => Bengkel::where('status','aktif')->count(),
            // 'totalNonaktif' => Bengkel::where('status','nonaktif')->count(),
            // 'totalKota'     => Bengkel::distinct('kota')->count(),
        ]);
    }


    public function pelangganIndex()
    {
        $bengkels = Bengkel::query()
            ->where('status', 'aktif') // ✅ hanya tampilkan aktif
            ->withAvg(['review' => function($q){
                $q->where('type','bengkel');
            }], 'rating')
            ->withCount(['review as reviews_count' => function($q){
                $q->where('type','bengkel');
            }])
            ->with('layanan')
            ->get();

        $totalBengkel = $bengkels->count();
        $avgRating    = $bengkels->avg('review') ?? 0;
        $totalReview  = $bengkels->sum('reviews_count');

        return view('pelanggan.bengkel', [
            'bengkels'     => $bengkels,
            'totalBengkel' => $totalBengkel,
            'avgRating'    => round($avgRating, 1),
            'totalReview'  => $totalReview,
        ]);
    }  

    //fungsi untuk menampilkan form tambah bengkel, nanti untuk proses simpannya di store()
    public function create()
    {
        // opsional: proteksi role (kalau belum pakai middleware role di route)
        if (Auth::user()->role !== 'admin_pusat') {
            abort(403);
        }

        // Ambil admin cabang yang belum memiliki bengkel
        $adminCabang = \App\Models\User::query()
            ->where('role', 'admin_cabang')
            ->where('is_active', true)
            // ->whereDoesntHave('bengkel')
            ->get();

        return view('admin-pusat.tambah-bengkel', [
            'adminCabang' => $adminCabang
        ]);
    }

    public function store(Request $request)
    {
        // 🔒 proteksi role
        if (Auth::user()->role !== 'admin_pusat') {
            abort(403);
        }

        // ✅ Validasi
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'admin_id'  => 'required|exists:users,id',
            'alamat'    => 'required|string',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'kota'      => 'nullable|string|max:100',
            'status'    => 'required|in:aktif,nonaktif',
            'foto'      => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        // 📸 Handle foto upload
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Pastikan folder assets/bengkels ada
            $assetsPath = public_path('assets/bengkels');
            if (!is_dir($assetsPath)) {
                mkdir($assetsPath, 0755, true);
            }
            
            // Pindahkan file ke folder assets/bengkels
            $file->move($assetsPath, $filename);
            $validated['foto'] = 'bengkels/' . $filename;
        }

        // 💾 Simpan ke database
        Bengkel::create($validated);

        return redirect()->route('admin-pusat.bengkel.index')
                        ->with('success', 'Bengkel berhasil ditambahkan');
    }

    public function show(int $id)
    {
        $bengkel = Bengkel::withCount(['reservasis', 'layanan', 'review'])
                        ->withAvg('review', 'rating')
                        ->with(['adminCabang', 'layanan'])
                        ->findOrFail($id);

        return view('admin-pusat.detail-bengkel', [
            'bengkel'               => $bengkel,
            'reservasiTerbaru'      => $bengkel->reservasis()
                                            ->with('user')
                                            ->latest('tanggal')
                                            ->take(5)
                                            ->get(),
            'reservasiHariIni'      => $bengkel->reservasis()->whereDate('tanggal', today())->count(),
            'reservasiSelesaiHariIni' => $bengkel->reservasis()->whereDate('tanggal', today())->where('status', 'done')->count(),
            'reservasiProsesHariIni'  => $bengkel->reservasis()->whereDate('tanggal', today())->where('status', 'in_progress')->count(),
            'reservasiBulanIni'     => $bengkel->reservasis()->whereMonth('tanggal', now()->month)->count(),
        ]);
    }

    public function destroy(int $id)
    {
        // 🔒 proteksi role
        if (Auth::user()->role !== 'admin_pusat') {
            abort(403);
        }

        $bengkel = Bengkel::findOrFail($id);

        // 📸 Hapus foto jika ada
        if ($bengkel->foto) {
            $fotoPath = public_path('assets/' . $bengkel->foto);
            if (file_exists($fotoPath)) {
                unlink($fotoPath);
            }
        }

        // 💾 Hapus bengkel dari database
        $bengkel->delete();

        return redirect()->route('admin-pusat.bengkel.index')
                        ->with('success', 'Bengkel berhasil dihapus');
    }

    public function edit(int $id)
    {
        // 🔒 proteksi role
        if (Auth::user()->role !== 'admin_pusat') {
            abort(403);
        }

        $bengkel = Bengkel::findOrFail($id);

        // Ambil admin cabang: yang sekarang + yang belum memiliki bengkel
        $adminCabang = \App\Models\User::query()
            ->where('role', 'admin_cabang')
            ->where('is_active', true)
            ->where(function($query) use ($bengkel) {
                // $query->whereDoesntHave('bengkel')
                $query->orWhere('id', $bengkel->admin_id);
            })
            ->get();

        return view('admin-pusat.edit-bengkel', [
            'bengkel' => $bengkel,
            'adminCabang' => $adminCabang
        ]);
    }

    public function update(Request $request, int $id)
    {
        // 🔒 proteksi role
        if (Auth::user()->role !== 'admin_pusat') {
            abort(403);
        }

        $bengkel = Bengkel::findOrFail($id);

        // ✅ Validasi
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'admin_id'  => 'required|exists:users,id',
            'alamat'    => 'required|string',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'kota'      => 'nullable|string|max:100',
            'status'    => 'required|in:aktif,nonaktif',
            'foto'      => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        // 📸 Handle hapus foto jika flag dikirim
        if ($request->input('hapus_foto') === '1' || $request->input('hapus_foto') == 1) {
            if ($bengkel->foto) {
                $oldFotoPath = public_path('assets/' . $bengkel->foto);
                if (file_exists($oldFotoPath)) {
                    unlink($oldFotoPath);
                }
                $validated['foto'] = null;
            }
        }

        // 📸 Handle foto upload
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($bengkel->foto) {
                $oldFotoPath = public_path('assets/' . $bengkel->foto);
                if (file_exists($oldFotoPath)) {
                    unlink($oldFotoPath);
                }
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Pastikan folder assets/bengkels ada
            $assetsPath = public_path('assets/bengkels');
            if (!is_dir($assetsPath)) {
                mkdir($assetsPath, 0755, true);
            }
            
            // Pindahkan file ke folder assets/bengkels
            $file->move($assetsPath, $filename);
            $validated['foto'] = 'bengkels/' . $filename;
        }

        // 💾 Update ke database
        $bengkel->update($validated);

        return redirect()->route('admin-pusat.bengkel.index')
                        ->with('success', 'Bengkel berhasil diperbarui');
    }
}
