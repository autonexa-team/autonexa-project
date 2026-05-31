<?php

namespace App\Http\Controllers;

use App\Models\Bengkel;
use App\Models\Reservasi;
use App\Models\Layanan;
use App\Models\ReservasiSparepart;
use App\Models\User;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReservasiController extends Controller
{
    public function index()
    {
        $bengkels = Bengkel::withAvg('reviews', 'rating')->get();
        $layanans = Layanan::all();

        // Hitung jumlah reservasi hari ini per bengkel (untuk tampil slot sisa)
        $today = Carbon::today()->toDateString();
        $reservasiHariIni = Reservasi::query()
            ->where('tanggal', '=', $today)
            ->whereNotIn('status', ['dibatalkan'])
            ->selectRaw('bengkel_id, COUNT(*) as total')
            ->groupBy('bengkel_id')
            ->pluck('total', 'bengkel_id')
            ->toArray();

        // Hitung slot per jam per bengkel untuk hari ini (dipakai booking.js)
        $slotPerJam = Reservasi::query()
            ->where('tanggal', '=', $today)
            ->whereNotIn('status', ['dibatalkan'])
            ->selectRaw('bengkel_id, waktu, COUNT(*) as total')
            ->groupBy('bengkel_id', 'waktu')
            ->get()
            ->groupBy('bengkel_id')
            ->map(fn($rows) => $rows->pluck('total', 'waktu'))
            ->toArray();

        return view('pelanggan.reservasi', compact('bengkels', 'layanans', 'reservasiHariIni', 'slotPerJam'));
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
        try {
            // Validate incoming data
            $validated = $request->validate([
                'bengkel_id' => 'required|exists:bengkels,id',
                'layanan_id' => 'required|exists:layanans,id',
                'tanggal'    => 'required|date|after_or_equal:today',
                'waktu'      => 'required|string',
                'plat'       => 'required|string|max:20',
                'kendaraan'  => 'required|string|max:100',
                'keluhan'    => 'required|string',
                'total_biaya'=> 'nullable|integer|min:0',
            ]);

            // Get current user
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
            }

            // Validate date is not in the past
            $tanggalReservasi = Carbon::parse($request->tanggal)->startOfDay();
            $today = Carbon::now()->startOfDay();
            
            if ($tanggalReservasi->lt($today)) {
                return back()->with('error', 'Tanggal reservasi tidak boleh di masa lalu');
            }

            // Validate time is not in the past if today
            if ($tanggalReservasi->isSameDay($today)) {
                $now = Carbon::now();
                $reservasiDateTime = Carbon::createFromFormat(
                    'Y-m-d H:i',
                    $request->tanggal . ' ' . $request->waktu,
                    date_default_timezone_get()
                );
                
                if ($reservasiDateTime->isPast()) {
                    return back()->with('error', 'Jam reservasi sudah terlewat');
                }
            }

            // Hitung total biaya dari harga layanan
            // Prioritas: pakai total_biaya dari form (dikirim JS), fallback ke harga layanan dari DB
            $layanan = Layanan::query()->where('id', '=', (int) $request->layanan_id)->first();
            $totalBiaya = $request->filled('total_biaya')
                ? (int) $request->total_biaya
                : ($layanan ? (int) $layanan->harga : 0);

            // Create new reservation
            $reservasi = Reservasi::create([
                'user_id'     => $user->id,
                'bengkel_id'  => $request->bengkel_id,
                'layanan_id'  => $request->layanan_id,
                'tanggal'     => $request->tanggal,
                'waktu'       => $request->waktu,
                'plat'        => $request->plat,
                'kendaraan'   => $request->kendaraan,
                'keluhan'     => $request->keluhan,
                'status'      => 'pending',
                'total_biaya' => $totalBiaya,
            ]);

            return redirect()
                ->route('pelanggan.riwayat')
                ->with('success', 'Reservasi berhasil dibuat! Nomor reservasi: #' . str_pad($reservasi->id, 6, '0', STR_PAD_LEFT));

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating reservation: ' . $e->getMessage());
            return back()
                ->with('error', 'Terjadi kesalahan saat membuat reservasi. Silakan coba lagi.')
                ->withInput();
        }
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
                    'plat' => '' 
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
            $layanan = Layanan::query()->where('id', '=', (int) $request->layanan_id)->first();
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

    public function updateStatusAdminCabang(Request $request, int $id)
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
    public function showDetailAdminCabang(int $id)
    {
        try {

            $user = Auth::user();

            if (!$user || !$user->isAdminCabang()) {
                abort(401, 'Unauthorized');
            }

            $reservasi = Reservasi::with([
                'user',
                'layanan',
                'bengkel',
                'spareparts'
            ])->findOrFail($id);

            if ($reservasi->bengkel_id !== $user->bengkel->id) {
                abort(403, 'Forbidden');
            }

            // sparepart aktif milik bengkel
            $sparepartsAktif = $reservasi->bengkel
                ->spareparts()
                ->wherePivot('stok', '>', 0)
                ->get();

            return view(
                'admin-cabang.reservasi-detail',
                compact(
                    'reservasi',
                    'sparepartsAktif'
                )
            );

        } catch (\Exception $e) {

            Log::error('Error showing reservasi detail: '.$e->getMessage(), [
                'exception' => $e,
                'reservasi_id' => $id
            ]);

            abort(404, 'Reservasi tidak ditemukan');
        }
    }

    public function riwayat()
    {
        $user = Auth::user();

        $reservasiList = Reservasi::query()
            ->where('user_id', $user->id)
            ->with(['bengkel', 'layanan'])
            ->orderBy('tanggal', 'desc')
            ->get();

        // Hitung statistik
        $totalReservasi  = $reservasiList->count();
        $aktif           = $reservasiList->whereIn('status', ['pending', 'dikonfirmasi', 'diproses'])->count();
        $selesai         = $reservasiList->where('status', 'selesai')->count();
        $dibatalkan      = $reservasiList->where('status', 'dibatalkan')->count();

        // Map ke format yang dipakai JS di blade
        $reservasiJs = $reservasiList->map(function ($r) {
            $statusMap = [
                'pending'      => 'waiting',
                'dikonfirmasi' => 'waiting',
                'diproses'     => 'process',
                'selesai'      => 'done',
                'dibatalkan'   => 'cancel',
            ];

            $stepMap = [
                'pending'      => 0,
                'dikonfirmasi' => 1,
                'diproses'     => 3,
                'selesai'      => 5,
                'dibatalkan'   => 0,
            ];

            return [
                'id'        => 'RV-' . str_pad($r->id, 7, '0', STR_PAD_LEFT),
                'db_id'     => $r->id,
                'status'    => $statusMap[$r->status] ?? 'waiting',
                'bengkel'   => $r->bengkel->nama ?? '-',
                'alamat'    => $r->bengkel->alamat ?? '-',
                'kendaraan' => $r->kendaraan ?? '-',
                'plat'      => $r->plat ?? '-',
                'layanan'   => $r->layanan->nama ?? '-',
                'tanggal'   => $r->tanggal,
                'jam'       => $r->waktu . ' WIB',
                'keluhan'   => $r->keluhan ?? '',
                'biaya'     => $r->total_biaya ? 'Rp ' . number_format($r->total_biaya, 0, ',', '.') : null,
                'step'      => $stepMap[$r->status] ?? 0,
                'timeline'  => [
                    ['time' => $r->created_at->format('d M Y · H:i') . ' WIB', 'title' => 'Reservasi Dibuat', 'desc' => 'Reservasi berhasil dibuat.', 'state' => 'done'],
                ],
            ];
        })->values()->toArray();

        return view('pelanggan.riwayat', compact(
            'reservasiJs',
            'totalReservasi',
            'aktif',
            'selesai',
            'dibatalkan'
        ));
    }

    public function profile()
    {
        return view('pelanggan.profile');
    }

    public function riwayatDetail(int $id)
    {
        $user = Auth::user();

        $reservasi = Reservasi::with(['bengkel', 'layanan'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        $statusMap = [
            'pending'      => 'waiting',
            'dikonfirmasi' => 'waiting',
            'diproses'     => 'process',
            'selesai'      => 'done',
            'dibatalkan'   => 'cancel',
        ];

        $stepMap = [
            'pending'      => 0,
            'dikonfirmasi' => 1,
            'diproses'     => 3,
            'selesai'      => 5,
            'dibatalkan'   => 0,
        ];

        $reservasiJs = [
            'id'        => 'RV-' . str_pad($reservasi->id, 7, '0', STR_PAD_LEFT),
            'db_id'     => $reservasi->id,
            'status'    => $statusMap[$reservasi->status] ?? 'waiting',
            'step'      => $stepMap[$reservasi->status] ?? 0,
            'bengkel'   => $reservasi->bengkel->nama ?? '-',
            'alamat'    => $reservasi->bengkel->alamat ?? '-',
            'telepon'   => $reservasi->bengkel->telepon ?? '-',
            'jam_buka'  => ($reservasi->bengkel->jam_buka ?? '08:00') . ' – ' . ($reservasi->bengkel->jam_tutup ?? '17:00'),
            'kendaraan' => $reservasi->kendaraan ?? '-',
            'plat'      => $reservasi->plat ?? '-',
            'layanan'   => $reservasi->layanan->nama ?? '-',
            'durasi'    => $reservasi->layanan->durasi ?? null,
            'tanggal'   => $reservasi->tanggal,
            'jam'       => $reservasi->waktu . ' WIB',
            'keluhan'   => $reservasi->keluhan ?? '',
            'biaya'     => $reservasi->total_biaya ? 'Rp ' . number_format($reservasi->total_biaya, 0, ',', '.') : null,
            'created_at'=> $reservasi->created_at->format('d F Y, H:i') . ' WIB',
            'timeline'  => [
                ['time' => $reservasi->created_at->format('d M Y · H:i') . ' WIB', 'title' => 'Reservasi Dibuat', 'desc' => 'Reservasi berhasil dibuat secara online.', 'state' => 'done'],
            ],
        ];

        return view('pelanggan.riwayat-detail', compact('reservasi', 'reservasiJs'));
    }    
    

    public function publicReservasi()
    {
        if (!Auth::check()) {
            return view('pelanggan.reservasi-gate');
        }

        return $this->index();
    }    

    // update catatan service    
    public function updateHasilService(Request $request, $id)
    {
        $request->validate([
            'hasil_service' => 'nullable|string'
        ]);

        $reservasi = Reservasi::findOrFail($id);

        $reservasi->update([
            'hasil_service' => $request->hasil_service
        ]);

        return back()->with(
            'success',
            'Catatan servis berhasil disimpan'
        );
    }    

    public function tambahSparepart(Request $request, $id)
    {
        $request->validate([
            'sparepart_id' => 'required|exists:sparepart,id',
            'qty' => 'required|integer|min:1'
        ]);

        $reservasi = Reservasi::findOrFail($id);

        $sparepart = Sparepart::findOrFail(
            $request->sparepart_id
        );

        $reservasi->spareparts()->attach(
            $sparepart->id,
            [
                'qty' => $request->qty,
                'harga' => $sparepart->harga
            ]
        );

        $reservasi->increment(
            'total_biaya',
            $sparepart->harga * $request->qty
        );

        return back()->with(
            'success',
            'Sparepart berhasil ditambahkan'
        );
    }    

    public function hapusSparepart($reservasiId, $sparepartId)
    {
        $reservasi = Reservasi::findOrFail($reservasiId);

        $reservasi->spareparts()->detach($sparepartId);

        return back()->with(
            'success',
            'Sparepart berhasil dihapus'
        );
    }    

    /* ================================================================
       ADMIN CABANG — Sparepart CRUD (real-time)
    ================================================================ */

    /**
     * GET /admin-cabang/reservasi/{id}/sparepart
     */
    public function sparepartIndex(int $id): \Illuminate\Http\JsonResponse
    {
        $user      = Auth::user();
        $reservasi = Reservasi::query()
            ->where('bengkel_id', '=', $user->bengkel->id)
            ->where('id', '=', $id)
            ->firstOrFail();

        return response()->json(
            $reservasi->spareparts()
                ->select(['id', 'nama', 'qty', 'harga', 'keterangan'])
                ->orderBy('created_at', 'asc')
                ->get()
        );
    }

    /**
     * POST /admin-cabang/reservasi/{id}/sparepart
     */
    public function sparepartStore(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $user      = Auth::user();
        $reservasi = Reservasi::query()
            ->where('bengkel_id', '=', $user->bengkel->id)
            ->where('id', '=', $id)
            ->firstOrFail();

        if (in_array($reservasi->status, ['selesai', 'dibatalkan'])) {
            return response()->json(['message' => 'Reservasi sudah selesai atau dibatalkan.'], 403);
        }

        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'harga'       => 'required|integer|min:1',
            'qty'         => 'required|integer|min:1',
            'keterangan'  => 'nullable|string|max:500',
        ]);

        $sp = $reservasi->spareparts()->create($validated);

        // Update total biaya reservasi
        $reservasi->total_biaya = ($reservasi->layanan->harga ?? 0)
            + $reservasi->spareparts()->sum(DB::raw('harga * qty'));
        $reservasi->save();

        return response()->json(['data' => $sp], 201);
    }

    /**
     * PATCH /admin-cabang/reservasi/{id}/sparepart/{spId}
     * Update qty saja.
     */
    public function sparepartUpdate(Request $request, int $id, int $spId): \Illuminate\Http\JsonResponse
    {
        $user      = Auth::user();
        $reservasi = Reservasi::query()
            ->where('bengkel_id', '=', $user->bengkel->id)
            ->where('id', '=', $id)
            ->firstOrFail();

        $sp = $reservasi->spareparts()->findOrFail($spId);

        $validated = $request->validate(['qty' => 'required|integer|min:1']);
        $sp->update($validated);

        // Update total biaya reservasi
        $reservasi->total_biaya = ($reservasi->layanan->harga ?? 0)
            + $reservasi->spareparts()->sum(DB::raw('harga * qty'));
        $reservasi->save();

        return response()->json(['data' => $sp]);
    }

    /**
     * DELETE /admin-cabang/reservasi/{id}/sparepart/{spId}
     */
    public function sparepartDestroy(int $id, int $spId): \Illuminate\Http\JsonResponse
    {
        $user      = Auth::user();
        $reservasi = Reservasi::query()
            ->where('bengkel_id', '=', $user->bengkel->id)
            ->where('id', '=', $id)
            ->firstOrFail();

        if (in_array($reservasi->status, ['selesai', 'dibatalkan'])) {
            return response()->json(['message' => 'Reservasi sudah selesai atau dibatalkan.'], 403);
        }

        $reservasi->spareparts()->findOrFail($spId)->delete();

        // Update total biaya reservasi
        $reservasi->total_biaya = ($reservasi->layanan->harga ?? 0)
            + $reservasi->spareparts()->sum(DB::raw('harga * qty'));
        $reservasi->save();

        return response()->json(['message' => 'Sparepart dihapus.', 'total_biaya' => $reservasi->total_biaya]);
    }
}