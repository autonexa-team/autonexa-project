<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user      = Auth::user();
        $bengkelId = $user->bengkel?->id;

        // ── ADMIN PUSAT ──────────────────────────────
        $totalBengkel = DB::table('bengkels')->count();

        $totalReservasi = DB::table('reservasis')
            ->where('bengkel_id', $bengkelId)
            ->count();

        $totalPelanggan = DB::table('users')
            ->where('role', 'pelanggan')
            ->count();

        $totalPendapatan = DB::table('reservasis')
            ->where('bengkel_id', $bengkelId)
            ->where('status', 'selesai')
            ->sum('total_biaya');

        $reservasiTerbaru = DB::table('reservasis')
            ->latest()
            ->take(5)
            ->get();

        $topBengkel = DB::table('bengkels')
            ->select('bengkels.*', DB::raw('COUNT(reservasis.id) as reservasi_count'))
            ->leftJoin('reservasis', 'bengkels.id', '=', 'reservasis.bengkel_id')
            ->groupBy('bengkels.id')
            ->orderByDesc('reservasi_count')
            ->take(5)
            ->get();

        $statusCount = [
            DB::table('reservasis')->where('status', 'pending')->count(),
            DB::table('reservasis')->where('status', 'diproses')->count(),
            DB::table('reservasis')->where('status', 'selesai')->count(),
            DB::table('reservasis')->where('status', 'dibatalkan')->count(),
        ];

        $chartData = [
            'weekly'  => [
                'labels' => ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                'data'   => [120000, 180000, 150000, 200000, 170000, 220000, 190000],
            ],
            'monthly' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr'],
                'data'   => [800000, 950000, 1100000, 1200000],
            ],
            'yearly'  => [
                'labels' => ['2023', '2024', '2025'],
                'data'   => [8000000, 9500000, 11000000],
            ],
        ];

        // ── ROUTING PER ROLE ─────────────────────────
        return match ($user->role) {

            'admin_pusat' => view('admin-pusat.dashboard', compact(
                'totalBengkel',
                'totalReservasi',
                'totalPendapatan',
                'totalPelanggan',
                'reservasiTerbaru',
                'topBengkel',
                'statusCount',
                'chartData'
            )),

            'admin_cabang' => view('admin-cabang.dashboard', [

                'totalReservasi' => DB::table('reservasis')
                    ->where('bengkel_id', $bengkelId)
                    ->count(),

                'serviceAktif' => DB::table('reservasis')
                    ->where('bengkel_id', $bengkelId)
                    ->where('status', 'diproses')
                    ->count(),

                'sparepartMenipis' => DB::table('bengkel_spareparts')
                    ->where('bengkel_id', $bengkelId)
                    ->where('stok', '<=', 5)
                    ->count(),

                'pendapatanHariIni' => DB::table('reservasis')
                    ->where('bengkel_id', $bengkelId)
                    ->where('status', 'selesai')
                    ->whereDate('tanggal', today())
                    ->sum('total_biaya'),

                // ✅ JOIN ke users agar nama_pelanggan tersedia di blade
                'reservasiHariIni' => DB::table('reservasis')
                    ->join('users', 'reservasis.user_id', '=', 'users.id')
                    ->select(
                        'reservasis.id',
                        'reservasis.user_id',
                        'reservasis.tanggal',
                        'reservasis.waktu',
                        'reservasis.keluhan',
                        'reservasis.status',
                        'reservasis.total_biaya',
                        'users.name as nama_pelanggan',
                    )
                    ->where('reservasis.bengkel_id', $bengkelId)
                    ->whereDate('reservasis.tanggal', today())
                    ->latest('reservasis.created_at')
                    ->get(),
            ]),

            default => abort(403),
        };
    }
}