<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bengkel;
use Illuminate\Http\Request;
use App\Models\Layanan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AdminCabangController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'admin_cabang')
                    ->with('bengkel')
                    ->latest()
                    ->paginate(15);

        return view('admin-pusat.user', [
            'users'        => $users,

            // total semua admin cabang
            'totalAdmin'   => User::where('role', 'admin_cabang')->count(),

            //admin dengan bengkel aktif
            'totalAktif'   => User::where('role', 'admin_cabang')
                ->whereHas('bengkel', function ($q) {
                    $q->where('status', 'aktif');
                })
                ->count(),

            //admin dengan bengkel nonaktif
            'totalNonaktif' => User::where('role', 'admin_cabang')
                ->whereHas('bengkel', function ($q) {
                    $q->where('status', 'nonaktif');
                })
                ->count(),

            // belum punya bengkel
            'totalBelum'   => User::where('role', 'admin_cabang')
                ->whereDoesntHave('bengkel')
                ->count(),

            // sudah punya bengkel
            'totalSudah'   => User::where('role', 'admin_cabang')
                ->whereHas('bengkel')
                ->count(),

            'bengkelTanpaAdmin' => Bengkel::whereNull('admin_id')
                ->orWhere('admin_id', 0)
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'password'   => ['required', Password::min(8)],
            'bengkel_id' => 'nullable|exists:bengkels,id|unique:bengkels,admin_id',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'admin_cabang',            
        ]);

        /* Assign bengkel jika dipilih */
        if ($request->bengkel_id) {
            Bengkel::where('id', $request->bengkel_id)
                   ->update(['admin_id' => $user->id]);
        }

        return back()->with('success', "Admin cabang {$user->name} berhasil ditambahkan.");
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'password'   => ['nullable', Password::min(8)],
            'bengkel_id' => [
                'nullable',
                'exists:bengkels,id',
                /* Bengkel bisa saja sudah dipegang user ini, boleh tetap */
                function ($attribute, $value, $fail) use ($user) {
                    $taken = Bengkel::where('id', $value)
                                    ->whereNotNull('admin_id')
                                    ->where('admin_id', '!=', $user->id)
                                    ->exists();
                    if ($taken) {
                        $fail('Bengkel ini sudah memiliki admin cabang lain.');
                    }
                },
            ],
        ]);

        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,            
            ...(
                $request->filled('password')
                    ? ['password' => Hash::make($request->password)]
                    : []
            ),
        ]);

        /* Lepas bengkel lama jika ganti atau dikosongkan */
        Bengkel::where('admin_id', $user->id)->update(['admin_id' => null]);

        if ($request->bengkel_id) {
            Bengkel::where('id', $request->bengkel_id)
                   ->update(['admin_id' => $user->id]);
        }

        dd('masuk update', $user->id);

        return back()->with('success', "Data {$user->name} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        /* Lepas assignment bengkel sebelum hapus */
        Bengkel::where('admin_id', $user->id)->update(['admin_id' => null]);
        $user->delete();

        return back()->with('success', 'Admin cabang berhasil dihapus.');
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $bengkel = $user->bengkel;

        $request->validate([
            'jam_buka' => 'required',
            'jam_tutup' => 'required',
        ]);

        // proses jam operasional

        $bengkel->update([
            'jam_buka' => $request->jam_buka,
            'jam_tutup' => $request->jam_tutup,
        ]);

        // proses hari operasional
        if ($request->has('hari_operasional')) {

            $bengkel->operasional()->delete();

            foreach ($request->hari_operasional as $hari) {

                $bengkel->operasional()->create([
                    'hari' => $hari,
                    'is_buka' => true,
                ]);
            }
        }

        // Proses update slot reservasi

        if ($request->has('slot')) {
            // menghapus slot lama
            $bengkel->slotReservasi()->delete();

            foreach ($request->slot as $jam => $kuota) {
                $jamMulai = $jam;
                $jamSelesai = date(
                    'H:i',
                    strtotime($jamMulai . ' +1 hour')
                );

                $bengkel->slotReservasi()->create([
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                    'kuota' => $kuota,
                ]);
            }
        }

        return back()->with(
            'success',
            'Profil cabang berhasil diperbarui.'
        );
    }


    // ================== layanan =============================
    public function layanan()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $bengkel = $user->bengkel()->with('layanan')->first();

        $layanans = Layanan::all();

        $totalAktif    = $bengkel->layanan->count();
        $totalNonaktif = $layanans->count() - $totalAktif;

        return view('admin-cabang.layanan', compact(
            'layanans',
            'bengkel',
            // manbah ini
            'totalAktif',
            'totalNonaktif'
        ));
    }  

    public function toggleLayanan(int $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $bengkel = $user->bengkel;

        // cek apakah layanan aktif
        if ($bengkel->layanan()->where('layanan_id', $id)->exists()) {
            // nonaktifkan
            $bengkel->layanan()->detach($id);
        } else {
            // aktifkan
            $bengkel->layanan()->attach($id);
        }
        return back()->with('success', 'Status layanan berhasil diubah');
    }    

    public function pelangganCabang(Request $request)
    {
        $user = Auth::user();
        $bengkel = $user->bengkel;

        $query = User::where('role', 'pelanggan')
            ->whereHas('reservasi', function ($q) use ($bengkel) {
                $q->where('bengkel_id', $bengkel->id);
            })
            ->withCount(['reservasi as total_reservasi' => function ($q) use ($bengkel) {
                $q->where('bengkel_id', $bengkel->id);
            }])
            ->withMax(['reservasi as terakhir_reservasi' => function ($q) use ($bengkel) {
                $q->where('bengkel_id', $bengkel->id);
            }], 'tanggal');  // ← pakai 'tanggal'

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $status = $request->get('status', 'semua');

        if ($status === 'aktif') {
            $query->having('total_reservasi', '>', 1);
        } elseif ($status === 'baru') {
            $query->whereHas('reservasi', function ($q) use ($bengkel) {
                $q->where('bengkel_id', $bengkel->id)
                ->where('tanggal', '>=', now()->startOfMonth()->toDateString()); // ← 'tanggal'
            });
        } elseif ($status === 'tidak_aktif') {
            $query->where(function ($q) use ($bengkel) {
                $q->whereDoesntHave('reservasi', function ($r) use ($bengkel) {
                    $r->where('bengkel_id', $bengkel->id)
                    ->where('tanggal', '>=', now()->subMonths(3)->toDateString()); // ← 'tanggal'
                });
            });
        }

        $pelanggan = $query->latest('created_at')->paginate(15)->withQueryString();

        // Statistik
        $totalPelanggan = User::where('role', 'pelanggan')
            ->whereHas('reservasi', fn($q) => $q->where('bengkel_id', $bengkel->id))
            ->count();

        $totalAktif = User::where('role', 'pelanggan')
            ->whereHas('reservasi', fn($q) => $q->where('bengkel_id', $bengkel->id))
            ->withCount(['reservasi as total_reservasi' => fn($q) => $q->where('bengkel_id', $bengkel->id)])
            ->having('total_reservasi', '>', 1)
            ->count();

        $totalBaru = User::where('role', 'pelanggan')
            ->whereHas('reservasi', function ($q) use ($bengkel) {
                $q->where('bengkel_id', $bengkel->id)
                ->where('tanggal', '>=', now()->startOfMonth()->toDateString()); // ← 'tanggal'
            })->count();

        $totalTidakAktif = User::where('role', 'pelanggan')
            ->whereHas('reservasi', fn($q) => $q->where('bengkel_id', $bengkel->id))
            ->whereDoesntHave('reservasi', function ($q) use ($bengkel) {
                $q->where('bengkel_id', $bengkel->id)
                ->where('tanggal', '>=', now()->subMonths(3)->toDateString()); // ← 'tanggal'
            })->count();

        return view('admin-cabang.pelanggan-cabang', compact(
            'pelanggan',
            'totalPelanggan',
            'totalAktif',
            'totalBaru',
            'totalTidakAktif',
            'status'
        ));
    }
    
}