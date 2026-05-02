<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();        

        // ======================
        // DATA GLOBAL DASHBOARD
        // ======================
        $totalBengkel = DB::table('bengkel')->count();
        $totalReservasi = DB::table('reservasi')->count();
        $totalPelanggan = DB::table('users')->where('role', 'pelanggan')->count();

        $totalPendapatan = DB::table('reservasi')
            ->where('status', 'selesai')
            ->sum('total_harga');

        $reservasiTerbaru = DB::table('reservasi')
            ->latest()
            ->take(5)
            ->get();

        $topBengkel = DB::table('bengkel')
            ->select('bengkel.*', DB::raw('COUNT(reservasi.id) as reservasi_count'))
            ->leftJoin('reservasi', 'bengkel.id', '=', 'reservasi.bengkel_id')
            ->groupBy('bengkel.id')
            ->orderByDesc('reservasi_count')
            ->take(5)
            ->get();

        $statusCount = [
            DB::table('reservasi')->where('status', 'pending')->count(),
            DB::table('reservasi')->where('status', 'diproses')->count(),
            DB::table('reservasi')->where('status', 'selesai')->count(),
            DB::table('reservasi')->where('status', 'dibatalkan')->count(),
        ];

        // ======================
        // CHART DATA
        // ======================
        $chartData = [
            'weekly' => [
                'labels' => ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                'data' => [120000, 180000, 150000, 200000, 170000, 220000, 190000],
            ],
            'monthly' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr'],
                'data' => [800000, 950000, 1100000, 1200000],
            ],
            'yearly' => [
                'labels' => ['2023', '2024', '2025'],
                'data' => [8000000, 9500000, 11000000],
            ],
        ];

        // ======================
        // VIEW DYNAMIC (ROLE)
        // ======================
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

            'admin_cabang' => view('admin-cabang.dashboard', compact(
                'totalBengkel',
                'totalReservasi',
                'reservasiTerbaru',
                'chartData'
            )),

            'mekanik' => view('mekanik.dashboard', compact(
                'reservasiTerbaru'
            )),

            default => abort(403),
        };
    }
}