<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\User;
use App\Models\Layanan;
use App\Models\Bengkel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminCabangReservasiController extends Controller
{
    /**
     * GET /admin-cabang/reservasi
     * List semua reservasi untuk bengkel admin cabang
     */
    public function index()
    {
        $admin = Auth::user();
        $bengkel = Bengkel::where('admin_id', $admin->id)->firstOrFail();
        
        $reservasi = Reservasi::where('bengkel_id', $bengkel->id)
            ->with('user', 'bengkel')
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->paginate(10);

        return view('admin-cabang.reservasi', compact('reservasi', 'bengkel'));
    }

    /**
     * GET /admin-cabang/reservasi/create
     * Form untuk membuat reservasi baru
     */
    public function create()
    {
        $admin = Auth::user();
        $bengkel = Bengkel::where('admin_id', $admin->id)->firstOrFail();
        
        // Get pelanggan existing
        $pelanggan = User::where('role', 'pelanggan')->orderBy('name', 'asc')->get();
        
        // Get layanan for this bengkel - use layanan() not layanans()
        $layanans = $bengkel->layanan()->get();
        
        return view('admin-cabang.reservasi-create', compact('bengkel', 'pelanggan', 'layanans'));
    }

    /**
     * POST /admin-cabang/reservasi
     * Store new reservation
     */
    public function store(Request $request)
    {
        $admin = Auth::user();
        $bengkel = Bengkel::where('admin_id', $admin->id)->firstOrFail();

        $validated = $request->validate([
            'user_id' => 'nullable|integer',
            'nama_pelanggan' => 'required|string|max:255',
            'hp_pelanggan' => 'required|string|max:20',
            'email_pelanggan' => 'nullable|email|max:255',
            'plat_kendaraan' => 'required|string|max:20',
            'layanan_id' => 'required|integer',
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu' => 'required|date_format:H:i',
            'status' => 'in:pending,dikonfirmasi,diproses,selesai,dibatalkan',
            'keluhan' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Jika tidak ada user_id (customer baru), buat user baru
            $userId = $validated['user_id'];
            if (!$userId) {
                $user = User::create([
                    'name' => $validated['nama_pelanggan'],
                    'email' => $validated['email_pelanggan'] ?? 'pelanggan_' . time() . '@autonexa.local',
                    'phone' => $validated['hp_pelanggan'],
                    'password' => bcrypt('default123'), // default password
                    'role' => 'pelanggan',
                ]);
                $userId = $user->id;
            } else {
                // Update data pelanggan existing jika ada perubahan
                $user = User::find($userId);
                $user->update([
                    'phone' => $validated['hp_pelanggan'],
                ]);
            }

            // Get layanan details
            $layanan = Layanan::findOrFail($validated['layanan_id']);

            // Hitung estimasi durasi (dalam menit, default 60)
            $durasi = (int) filter_var($layanan->durasi, FILTER_SANITIZE_NUMBER_INT) ?: 60;

            // Create reservasi
            $reservasi = Reservasi::create([
                'user_id' => $userId,
                'bengkel_id' => $bengkel->id,
                'kendaraan' => $validated['plat_kendaraan'], // bisa ditambah field tipe kendaraan jika ada
                'plat' => $validated['plat_kendaraan'],
                'tanggal' => $validated['tanggal'],
                'waktu' => $validated['waktu'],
                'keluhan' => $validated['keluhan'] ?? '',
                'status' => $validated['status'] ?? 'pending',
                'total_biaya' => $layanan->harga ?? 0,
            ]);

            // Attach layanan ke reservasi (jika ada relasi many-to-many)
            // $reservasi->layanans()->attach($layanan->id);

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

    /**
     * GET /admin-cabang/reservasi/{id}
     * Show detail reservasi
     */
    public function show(int $id)
    {
        $admin = Auth::user();
        $bengkel = Bengkel::where('admin_id', $admin->id)->firstOrFail();
        
        $reservasi = Reservasi::where('bengkel_id', $bengkel->id)
            ->with('user', 'bengkel')
            ->findOrFail($id);

        return view('admin-cabang.reservasi-detail', compact('reservasi', 'bengkel'));
    }

    /**
     * GET /admin-cabang/reservasi/{id}/edit
     * Show edit form
     */
    public function edit(int $id)
    {
        $admin = Auth::user();
        $bengkel = Bengkel::where('admin_id', $admin->id)->firstOrFail();
        
        $reservasi = Reservasi::where('bengkel_id', $bengkel->id)
            ->with('user')
            ->findOrFail($id);

        $pelanggan = User::where('role', 'pelanggan')->orderBy('name', 'asc')->get();
        $layanans = $bengkel->layanan()->get();

        return view('admin-cabang.reservasi-edit', compact('reservasi', 'bengkel', 'pelanggan', 'layanans'));
    }

    /**
     * PATCH /admin-cabang/reservasi/{id}
     * Update reservasi
     */
    public function update(Request $request, int $id)
    {
        $admin = Auth::user();
        $bengkel = Bengkel::where('admin_id', $admin->id)->firstOrFail();
        
        $reservasi = Reservasi::where('bengkel_id', $bengkel->id)->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,dikonfirmasi,diproses,selesai,dibatalkan',
            'keluhan' => 'nullable|string',
            'hasil_service' => 'nullable|string',
            'total_biaya' => 'nullable|numeric|min:0',
            'tanggal' => 'nullable|date',
            'waktu' => 'nullable|date_format:H:i',
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

    /**
     * DELETE /admin-cabang/reservasi/{id}
     * Delete reservasi
     */
    public function destroy(int $id)
    {
        $admin = Auth::user();
        $bengkel = Bengkel::where('admin_id', $admin->id)->firstOrFail();
        
        $reservasi = Reservasi::where('bengkel_id', $bengkel->id)->findOrFail($id);

        try {
            $reservasi->delete();

            return redirect()
                ->route('admin-cabang.reservasi')
                ->with('success', 'Reservasi berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus reservasi: ' . $e->getMessage());
        }
    }

    /**
     * GET /admin-cabang/reservasi/api/pelanggan
     * API endpoint untuk get semua pelanggan (untuk autocomplete/select)
     */
    public function getPelanggan()
    {
        $pelanggan = User::where('role', 'pelanggan')
            ->select('id', 'name', 'email', 'phone')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($pelanggan);
    }

    /**
     * GET /admin-cabang/reservasi/api/slot
     * API endpoint untuk check available time slots
     */
    public function getTimeSlots(Request $request)
    {
        $tanggal = $request->query('tanggal');
        $admin = Auth::user();
        $bengkel = Bengkel::where('admin_id', $admin->id)->firstOrFail();

        if (!$tanggal) {
            return response()->json(['error' => 'Tanggal harus disediakan'], 400);
        }

        // Working hours default
        $workingHours = ['08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'];

        // Get existing reservasi count per jam
        $slots = [];
        foreach ($workingHours as $jam) {
            $count = Reservasi::where('bengkel_id', $bengkel->id)
                ->where('tanggal', $tanggal)
                ->where('waktu', $jam)
                ->count();

            $maxSlot = $bengkel->kuota_slot['max_per_slot'] ?? 5;
            $isFull = $count >= $maxSlot;

            $slots[] = [
                'jam' => $jam,
                'tersedia' => $maxSlot - $count,
                'penuh' => $isFull,
            ];
        }

        return response()->json($slots);
    }
}
