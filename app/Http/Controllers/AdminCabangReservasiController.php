<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\User;
use App\Models\Layanan;
use App\Models\Bengkel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminCabangReservasiController extends Controller
{
    /**
     * Ambil bengkel milik admin yang sedang login.
     * Mencoba dua cara:
     *  1. Relasi $user->bengkel (hasOne di model User) — dipakai ReservasiController
     *  2. Fallback: Bengkel::where('admin_id', $user->id) — jika relasi belum ada di model User
     */
    private function getBengkel(): Bengkel
    {
        $user = Auth::user();

        // Cara 1: via relasi hasOne di model User
        if (method_exists($user, 'bengkel')) {
            $bengkel = $user->bengkel;
            if ($bengkel instanceof Bengkel) {
                return $bengkel;
            }
        }

        // Cara 2: fallback query langsung (jika kolom admin_id ada di tabel bengkels)
        $bengkel = Bengkel::query()->where('admin_id', $user->id)->first();
        if ($bengkel instanceof Bengkel) {
            return $bengkel;
        }

        abort(404, 'Bengkel untuk akun ini tidak ditemukan. Pastikan akun admin cabang sudah terhubung ke bengkel.');
    }

    // ─────────────────────────────────────────────────────────────
    // INDEX — GET /admin-cabang/reservasi
    // ─────────────────────────────────────────────────────────────
    public function index()
    {
        $bengkel = $this->getBengkel();

        $reservasi = Reservasi::query()
            ->where('bengkel_id', $bengkel->id)
            ->with('user', 'bengkel', 'layanan')
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->paginate(10);

        $totalReservasi   = Reservasi::query()->where('bengkel_id', $bengkel->id)->count();
        $hariIni          = Reservasi::query()->where('bengkel_id', $bengkel->id)
                                ->whereDate('tanggal', Carbon::today())
                                ->count();
        $sedangDikerjakan = Reservasi::query()->where('bengkel_id', $bengkel->id)
                                ->where('status', 'diproses')
                                ->count();
        $selesai          = Reservasi::query()->where('bengkel_id', $bengkel->id)
                                ->where('status', 'selesai')
                                ->count();

        return view('admin-cabang.reservasi', compact(
            'reservasi',
            'bengkel',
            'totalReservasi',
            'hariIni',
            'sedangDikerjakan',
            'selesai'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // CREATE — GET /admin-cabang/reservasi/create
    // ─────────────────────────────────────────────────────────────
    public function create()
    {
        $bengkel   = $this->getBengkel();
        $pelanggan = User::where('role', 'pelanggan')->orderBy('name', 'asc')->get();
        $layanans  = $bengkel->layanan()->get();

        return view('admin-cabang.reservasi-create', compact('bengkel', 'pelanggan', 'layanans'));
    }

    // ─────────────────────────────────────────────────────────────
    // STORE — POST /admin-cabang/reservasi
    // ─────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $bengkel = $this->getBengkel();

        $validated = $request->validate([
            'user_id'         => 'nullable|integer',
            'nama_pelanggan'  => 'required|string|max:255',
            'hp_pelanggan'    => 'required|string|max:20',
            'email_pelanggan' => 'nullable|email|max:255',
            'plat_kendaraan'  => 'required|string|max:20',
            'layanan_id'      => 'required|integer',
            'tanggal'         => 'required|date|after_or_equal:today',
            'waktu'           => 'required|date_format:H:i',
            'status'          => 'nullable|in:pending,dikonfirmasi,diproses,selesai,dibatalkan',
            'keluhan'         => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $userId = $validated['user_id'] ?? null;

            if (!$userId) {
                $newUser = User::create([
                    'name'     => $validated['nama_pelanggan'],
                    'email'    => $validated['email_pelanggan'] ?? 'pelanggan_' . time() . '@autonexa.local',
                    'phone'    => $validated['hp_pelanggan'],
                    'password' => bcrypt('default123'),
                    'role'     => 'pelanggan',
                ]);
                $userId = $newUser->id;
            } else {
                $existingUser = User::query()->find((int) $userId);
                if ($existingUser) {
                    $existingUser->update(['phone' => $validated['hp_pelanggan']]);
                }
            }

            $layanan = Layanan::findOrFail($validated['layanan_id']);

            $reservasi = Reservasi::create([
                'user_id'     => $userId,
                'bengkel_id'  => $bengkel->id,
                'layanan_id'  => $layanan->id,
                'kendaraan'   => $validated['plat_kendaraan'],
                'plat'        => $validated['plat_kendaraan'],
                'tanggal'     => $validated['tanggal'],
                'waktu'       => $validated['waktu'],
                'keluhan'     => $validated['keluhan'] ?? '',
                'status'      => $validated['status'] ?? 'pending',
                'total_biaya' => $layanan->harga ?? 0,
            ]);

            DB::commit();

            return redirect()
                ->route('admin-cabang.reservasi')
                ->with('success', 'Reservasi berhasil dibuat dengan nomor: ' . $reservasi->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan reservasi: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────
    // SHOW — GET /admin-cabang/reservasi/{id}
    // ─────────────────────────────────────────────────────────────
    public function show(int $id)
    {
        $bengkel = $this->getBengkel();

        $reservasi = Reservasi::query()
            ->with([
                'user',
                'layanan',
                'bengkel',
                'spareparts',
            ])
            ->where('bengkel_id', $bengkel->id)
            ->findOrFail($id);

        $sparepartsAktif = $bengkel->spareparts()
            ->wherePivot('stok', '>', 0)
            ->get();

        return view('admin-cabang.reservasi-detail', compact(
            'reservasi',
            'bengkel',
            'sparepartsAktif'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // EDIT — GET /admin-cabang/reservasi/{id}/edit
    // ─────────────────────────────────────────────────────────────
    public function edit(int $id)
    {
        $bengkel   = $this->getBengkel();
        $reservasi = Reservasi::query()
            ->where('bengkel_id', $bengkel->id)
            ->with('user', 'layanan')
            ->findOrFail($id);

        $pelanggan = User::where('role', 'pelanggan')->orderBy('name', 'asc')->get();
        $layanans  = $bengkel->layanan()->get();

        return view('admin-cabang.reservasi-edit', compact('reservasi', 'bengkel', 'pelanggan', 'layanans'));
    }

    // ─────────────────────────────────────────────────────────────
    // UPDATE — PATCH /admin-cabang/reservasi/{id}
    // ─────────────────────────────────────────────────────────────
    public function update(Request $request, int $id)
    {
        $bengkel   = $this->getBengkel();
        $reservasi = Reservasi::query()->where('bengkel_id', $bengkel->id)->findOrFail($id);

        $validated = $request->validate([
            'status'        => 'required|in:pending,dikonfirmasi,diproses,selesai,dibatalkan',
            'keluhan'       => 'nullable|string',
            'hasil_service' => 'nullable|string',
            'total_biaya'   => 'nullable|numeric|min:0',
            'tanggal'       => 'nullable|date',
            'waktu'         => 'nullable|date_format:H:i',
        ]);

        try {
            $reservasi->update($validated);

            return redirect()
                ->route('admin-cabang.reservasi-detail', $reservasi->id)
                ->with('success', 'Reservasi berhasil diperbarui');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui reservasi: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────
    // DESTROY — DELETE /admin-cabang/reservasi/{id}
    // ─────────────────────────────────────────────────────────────
    public function destroy(int $id)
    {
        $bengkel   = $this->getBengkel();
        $reservasi = Reservasi::query()->where('bengkel_id', $bengkel->id)->findOrFail($id);

        try {
            $reservasi->deleteOrFail();

            return redirect()
                ->route('admin-cabang.reservasi')
                ->with('success', 'Reservasi berhasil dihapus');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus reservasi: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────
    // API — GET /admin-cabang/reservasi/api/pelanggan
    // ─────────────────────────────────────────────────────────────
    public function getPelanggan()
    {
        $pelanggan = User::where('role', 'pelanggan')
            ->select('id', 'name', 'email', 'phone')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($pelanggan);
    }

    // ─────────────────────────────────────────────────────────────
    // API — GET /admin-cabang/reservasi/api/slot
    // ─────────────────────────────────────────────────────────────
    public function getTimeSlots(Request $request)
    {
        $tanggal = $request->query('tanggal');

        if (!$tanggal) {
            return response()->json(['error' => 'Tanggal harus disediakan'], 400);
        }

        $bengkel      = $this->getBengkel();
        $workingHours = ['08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'];
        $maxSlot      = $bengkel->kuota_slot['max_per_slot'] ?? 5;

        $slots = [];
        foreach ($workingHours as $jam) {
            $count = Reservasi::query()
                ->where('bengkel_id', $bengkel->id)
                ->where('tanggal', $tanggal)
                ->where('waktu', $jam)
                ->count();
            $slots[] = [
                'jam'      => $jam,
                'tersedia' => max(0, $maxSlot - $count),
                'penuh'    => $count >= $maxSlot,
            ];
        }

        return response()->json($slots);
    }
}