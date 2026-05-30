<?php

namespace App\Http\Controllers;

use App\Models\Bengkel;
use App\Models\Reservasi;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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

    /**
     * Get data pelanggan existing (untuk dropdown search pelanggan)
     */
    public function getPelangganExisting()
    {
        $pelanggan = User::where('role', 'pelanggan')
            ->select('id', 'name', 'phone', 'email')
            ->orderBy('name')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'nama' => $user->name,
                    'hp' => $user->phone ?? '-',
                    'email' => $user->email ?? '',
                    'plat' => '-' // TODO: Dapatkan dari reservation terakhir atau profile
                ];
            });

        return response()->json($pelanggan);
    }

    /**
     * Display reservasi untuk admin cabang dari database
     */
    public function getReservasiCabang()
    {
        $user = Auth::user();
        
        if (!$user || !$user->isAdminCabang()) {
            return redirect()->route('login');
        }

        $bengkel = $user->bengkel;
        if (!$bengkel) {
            abort(404, 'Bengkel tidak ditemukan');
        }

        // Get reservasi dari database
        $reservasi = Reservasi::query()
            ->where('bengkel_id', $bengkel->id)
            ->with(['user', 'layanan'])
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        // Count statistic
        $totalReservasi = Reservasi::query()->where('bengkel_id', $bengkel->id)->count();
        $hariIni = Reservasi::query()
            ->where('bengkel_id', $bengkel->id)
            ->whereDate('tanggal', Carbon::today())
            ->count();
        $sedangDikerjakan = Reservasi::query()
            ->where('bengkel_id', $bengkel->id)
            ->where('status', 'diproses')
            ->count();
        $selesai = Reservasi::query()
            ->where('bengkel_id', $bengkel->id)
            ->where('status', 'selesai')
            ->count();

        return view('admin-cabang.reservasi', compact(
            'reservasi',
            'totalReservasi',
            'hariIni',
            'sedangDikerjakan',
            'selesai'
        ));
    }

    /**
     * Get detail pelanggan berdasarkan ID
     */
    public function getPelangganDetail(int $userId)
    {
        $user = User::where('role', 'pelanggan')
            ->find($userId);

        if (!$user) {
            return response()->json(['error' => 'Pelanggan tidak ditemukan'], 404);
        }

        return response()->json([
            'id' => $user->id,
            'nama' => $user->name,
            'hp' => $user->phone ?? '',
            'email' => $user->email ?? '',
            'plat' => '-' // TODO: Dapatkan dari reservation terakhir
        ]);
    }

    /**
     * Get layanan yang aktif untuk admin cabang tertentu
     */
    public function getLayananAktif()
    {
        $user = Auth::user();
        
        if (!$user || !$user->isAdminCabang()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $bengkel = $user->bengkel;
        if (!$bengkel) {
            return response()->json(['error' => 'Bengkel tidak ditemukan'], 404);
        }

        // Get layanan yang aktif pada bengkel ini (via BengkelLayanan)
        $layanan = $bengkel->layanan()
            ->where('status', 'aktif')
            ->select('layanans.id', 'layanans.nama', 'layanans.harga', 'layanans.durasi', 'layanans.deskripsi')
            ->get()
            ->map(function ($l) {
                return [
                    'id' => $l->id,
                    'nama' => $l->nama,
                    'harga' => (int)$l->harga,
                    'durasi' => $l->durasi,
                    'deskripsi' => $l->deskripsi ?? ''
                ];
            });

        return response()->json($layanan);
    }

    /**
     * Store reservasi dari admin cabang (dengan kemungkinan pelanggan baru)
     */
    public function storeAdminCabang(Request $request)
    {
        try {
            $validated = $request->validate([
                'pelanggan_id' => 'nullable|exists:users,id',
                'p_nama' => 'required|string|max:100',
                'p_hp' => 'required|string|max:20',
                'p_email' => 'nullable|email',
                'p_plat' => 'required|string|max:20',
                'layanan_id' => 'required|exists:layanans,id',
                'tgl_reservasi' => 'required|date|after_or_equal:today',
                'jam_reservasi' => 'required|string',
                'status' => 'nullable|in:Menunggu,Dikonfirmasi,Proses,Selesai',
                'catatan' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi data gagal',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            $user = Auth::user();
            if (!$user || !$user->isAdminCabang()) {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
            }

            $bengkel = $user->bengkel;
            if (!$bengkel) {
                return response()->json(['success' => false, 'error' => 'Bengkel tidak ditemukan'], 404);
            }

            // Validasi tanggal tidak terlewat
            $tglReservasi = Carbon::parse($request->tgl_reservasi)->startOfDay();
            $today = Carbon::now()->startOfDay();
            
            if ($tglReservasi->lt($today)) {
                return response()->json(['success' => false, 'error' => 'Tanggal reservasi tidak boleh di masa lalu'], 422);
            }

            // Validasi jam tidak terlewat jika hari ini
            if ($tglReservasi->isSameDay($today)) {
                $now = Carbon::now();
                // Buat datetime lengkap: tanggal + jam untuk perbandingan akurat
                $reservasiDateTime = Carbon::createFromFormat(
                    'Y-m-d H:i',
                    $request->tgl_reservasi . ' ' . $request->jam_reservasi,
                    date_default_timezone_get()
                );
                
                if ($reservasiDateTime->isPast()) {
                    return response()->json(['success' => false, 'error' => 'Jam reservasi sudah terlewat'], 422);
                }
            }

            $userId = null;

            // Jika pelanggan existing
            if ($request->pelanggan_id) {
                $userId = $request->pelanggan_id;
            } else {
                // Buat pelanggan baru
                $email = $request->p_email && $request->p_email !== '' 
                    ? $request->p_email 
                    : 'pelanggan_' . time() . '@autonexa.local';
                
                $userId = User::create([
                    'name' => $request->p_nama,
                    'phone' => $request->p_hp,
                    'email' => $email,
                    'role' => 'pelanggan',
                    'password' => Hash::make('DefaultPassword123!') // Temporary password
                ])->id;
            }

            // Get layanan to get harga
            $layanan = Layanan::find($request->layanan_id);
            $totalBiaya = $layanan ? (int)$layanan->harga : 0;

            // Buat reservasi
            $statusMap = [
                'Menunggu' => 'pending',
                'Dikonfirmasi' => 'dikonfirmasi',
                'Proses' => 'diproses',
                'Selesai' => 'selesai',
            ];

            $reservasi = Reservasi::create([
                'user_id' => $userId,
                'bengkel_id' => $bengkel->id,
                'layanan_id' => $request->layanan_id,
                'tanggal' => $request->tgl_reservasi,
                'waktu' => $request->jam_reservasi,
                'kendaraan' => $request->p_plat,
                'plat' => $request->p_plat,
                'keluhan' => $request->catatan ?? '',
                'status' => $statusMap[$request->status ?? 'Menunggu'] ?? 'pending',
                'total_biaya' => $totalBiaya,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reservasi berhasil disimpan',
                'reservasi_id' => $reservasi->id
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating reservasi: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatusAdminCabang(Request $request, $id)
    {
        try {
            $user = Auth::user();
            if (!$user || !$user->isAdminCabang()) {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
            }

            $reservasi = Reservasi::findOrFail($id);

            // Verify ownership
            if ($reservasi->bengkel_id !== $user->bengkel->id) {
                return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
            }

            $validated = $request->validate([
                'status' => 'required|in:pending,dikonfirmasi,diproses,selesai,dibatalkan'
            ]);

            $reservasi->update([
                'status' => $validated['status']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui',
                'status' => $reservasi->status
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating reservasi status: ' . $e->getMessage(), [
                'exception' => $e,
                'reservasi_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show detail reservasi untuk admin cabang
     */
    public function showDetailAdminCabang($id)
    {
        try {
            $user = Auth::user();
            if (!$user || !$user->isAdminCabang()) {
                abort(401, 'Unauthorized');
            }

            $reservasi = Reservasi::with(['user', 'layanan', 'bengkel'])
                ->findOrFail($id);

            // Verify ownership - admin cabang hanya bisa lihat reservasi dari bengkelnya sendiri
            if ($reservasi->bengkel_id !== $user->bengkel->id) {
                abort(403, 'Forbidden');
            }

            return view('admin-cabang.reservasi-detail', compact('reservasi'));
        } catch (\Exception $e) {
            Log::error('Error showing reservasi detail: ' . $e->getMessage(), [
                'exception' => $e,
                'reservasi_id' => $id
            ]);
            abort(404, 'Reservasi tidak ditemukan');
        }
    }

    public function riwayat()
    {
        return view('pelanggan.riwayat');
    }

    public function profile()
    {
        return view('pelanggan.profile');
    }

    public function riwayatDetail(int $id)
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