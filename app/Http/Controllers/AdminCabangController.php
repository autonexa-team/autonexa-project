<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Bengkel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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

            // ✅ admin dengan bengkel aktif
            'totalAktif'   => User::where('role', 'admin_cabang')
                ->whereHas('bengkel', function ($q) {
                    $q->where('status', 'aktif');
                })
                ->count(),

            // ✅ admin dengan bengkel nonaktif
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

        return back()->with('success', "Data {$user->name} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        /* Lepas assignment bengkel sebelum hapus */
        Bengkel::where('admin_id', $user->id)->update(['admin_id' => null]);
        $user->delete();

        return back()->with('success', 'Admin cabang berhasil dihapus.');
    }
}