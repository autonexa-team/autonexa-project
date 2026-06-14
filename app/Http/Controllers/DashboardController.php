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

        return match ($user->role) {

            'admin_pusat' => (function () {

                // STAT CARDS
                $totalReservasi  = DB::table('reservasis')->count();
                $totalBengkel    = DB::table('bengkels')->count();
                $totalPelanggan  = DB::table('users')->where('role', 'pelanggan')->count();
                $totalPendapatan = DB::table('reservasis')->where('status', 'selesai')->sum('total_biaya');
                $trendPendapatan = 0;
                $trendPelanggan  = 0;

                $bengkelBaru = DB::table('bengkels')
                    ->whereMonth('created_at', now()->month)
                    ->count();

                $bengkelBermasalah = DB::table('bengkels')
                    ->where('status', 'nonaktif')
                    ->count();

                $totalReview = DB::table('reviews')->count();
                $avgRating   = round(DB::table('reviews')->avg('rating') ?? 0, 1);

                // TABEL TRANSAKSI TERBARU
                $transaksiTerbaru = DB::table('reservasis')
                    ->join('bengkels', 'reservasis.bengkel_id', '=', 'bengkels.id')
                    ->join('users',    'reservasis.user_id',    '=', 'users.id')
                    ->select(
                        'reservasis.id',
                        'reservasis.total_biaya as total',
                        'reservasis.created_at',
                        'bengkels.nama as bengkel_nama',
                        'users.name as user_name',
                    )
                    ->latest('reservasis.created_at')
                    ->take(10)
                    ->get()
                    ->map(function ($r) {
                        $r->bengkel = (object)['nama' => $r->bengkel_nama];
                        $r->user    = (object)['name' => $r->user_name];
                        return $r;
                    });

                // RANKING BENGKEL
                $rankingBengkel = DB::table('bengkels')
                    ->leftJoin('reviews', 'bengkels.id', '=', 'reviews.bengkel_id')
                    ->select(
                        'bengkels.id',
                        'bengkels.nama',
                        'bengkels.kota',
                        DB::raw('AVG(reviews.rating) as avg_rating'),
                    )
                    ->groupBy('bengkels.id', 'bengkels.nama', 'bengkels.kota')
                    ->orderByDesc('avg_rating')
                    ->take(10)
                    ->get();

                // CHART: PENDAPATAN BULANAN 
                $rawBulanan = DB::table('reservasis')
                    ->where('status', 'selesai')
                    ->whereYear('created_at', now()->year)   // ← ganti updated_at → created_at
                    ->selectRaw('MONTH(created_at) as bulan, SUM(total_biaya)/1000 as total')
                    ->groupBy('bulan')
                    ->orderBy('bulan')
                    ->pluck('total', 'bulan')
                    ->toArray();
    
                $pendapatanBulanan = [];
                $targetBulanan     = [];
                for ($m = 1; $m <= 12; $m++) {
                    $pendapatanBulanan[] = round($rawBulanan[$m] ?? 0, 1);
                    $targetBulanan[]     = 1200;  // sesuaikan target per bulan
                }
                $pendapatanLabBulanan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

                // CHART: PENDAPATAN MINGGUAN 
                $rawMingguan = DB::table('reservasis')
                    ->where('status', 'selesai')
                    ->whereYear('created_at', now()->year)
                    ->selectRaw('WEEK(created_at) as periode, SUM(total_biaya)/1000 as total')
                    ->groupBy('periode')->orderBy('periode')
                    ->pluck('total', 'periode')->toArray();

                $pendapatanMingguan    = [];
                $targetMingguan        = []; 
                $pendapatanLabMingguan = [];
                foreach ($rawMingguan as $minggu => $total) {
                    $pendapatanLabMingguan[] = 'M' . $minggu;
                    $pendapatanMingguan[]    = round($total, 1);             
                    $targetMingguan[]        = 200;       
                }

                // Fallback jika kosong
                if (empty($pendapatanMingguan)) {
                    $pendapatanMingguan    = [0];
                    $pendapatanLabMingguan = ['M1'];     
                    $targetMingguan        = [200];               
                }

                // CHART: PENDAPATAN TAHUNAN 
                $rawTahunan = DB::table('reservasis')
                    ->where('status', 'selesai')
                    ->whereYear('created_at', now()->year)
                    ->selectRaw('YEAR(created_at) as periode, SUM(total_biaya)/1000 as total')
                    ->groupBy('periode')->orderBy('periode')
                    ->pluck('total', 'periode')->toArray();

                $pendapatanTahunan    = array_values(array_map(fn($v) => round($v, 1), $rawTahunan));
                $targetTahunan        = array_fill(0, count($pendapatanTahunan), 500); 
                $pendapatanLabTahunan = array_map('strval', array_keys($rawTahunan));
                if (empty($pendapatanTahunan)) {
                    $pendapatanTahunan    = [0];
                    $targetTahunan        = [500];
                    $pendapatanLabTahunan = [(string) now()->year];
                }

                // TREND PENDAPATAN — tambahkan kalkulasi
                $pendapatanBulanIni  = DB::table('reservasis')
                    ->where('status', 'selesai')
                    ->whereMonth('updated_at', now()->month)
                    ->sum('total_biaya');
                $pendapatanBulanLalu = DB::table('reservasis')
                    ->where('status', 'selesai')
                    ->whereMonth('updated_at', now()->subMonth()->month)
                    ->sum('total_biaya');
                $trendPendapatan = $pendapatanBulanLalu > 0
                    ? round((($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100)
                    : 0;    
                    
                // TREND PELANGGAN
                $pelangganBulanIni  = DB::table('users')->where('role','pelanggan')
                    ->whereMonth('created_at', now()->month)->count();
                $pelangganBulanLalu = DB::table('users')->where('role','pelanggan')
                    ->whereMonth('created_at', now()->subMonth()->month)->count();
                $trendPelanggan = $pelangganBulanLalu > 0
                    ? round((($pelangganBulanIni - $pelangganBulanLalu) / $pelangganBulanLalu) * 100)
                    : 0;
                    

                // CHART: PERFORMA BENGKEL 
                $perfomaBengkel = DB::table('bengkels')
                    ->leftJoin('reservasis', function ($join) {
                        $join->on('bengkels.id', '=', 'reservasis.bengkel_id')
                             ->whereMonth('reservasis.created_at', now()->month);
                    })
                    ->select('bengkels.nama', DB::raw('COUNT(reservasis.id) as total'))
                    ->groupBy('bengkels.id', 'bengkels.nama')
                    ->orderByDesc('total')
                    ->take(5)
                    ->get();

                $bengkelLabels = $perfomaBengkel->pluck('nama')->toArray();
                $bengkelData   = $perfomaBengkel->pluck('total')->map(fn($v) => (int)$v)->toArray();

                //  RETURN VIEW 
                return view('admin-pusat.dashboard', compact(
                    'totalBengkel',
                    'totalReservasi',
                    'totalPendapatan',
                    'totalPelanggan',
                    'trendPendapatan',
                    'trendPelanggan',
                    'bengkelBaru',
                    'bengkelBermasalah',
                    'totalReview',
                    'avgRating',
                    'transaksiTerbaru',
                    'rankingBengkel',
                    'bengkelLabels',
                    'bengkelData',
                    'pendapatanBulanan',
                    'targetBulanan',
                    'pendapatanLabBulanan',
                    'pendapatanMingguan',
                    'targetMingguan',
                    'pendapatanLabMingguan',
                    'pendapatanTahunan',
                    'targetTahunan',
                    'pendapatanLabTahunan',
                ));
            })(),

            'admin_cabang' => (function () use ($bengkelId) {
                return view('admin-cabang.dashboard', [

                    'totalReservasi' => DB::table('reservasis')
                        ->where('bengkel_id', $bengkelId)->count(),

                    'serviceAktif' => DB::table('reservasis')
                        ->where('bengkel_id', $bengkelId)
                        ->where('status', 'diproses')->count(),

                    'sparepartMenipis' => DB::table('bengkel_spareparts')
                        ->where('bengkel_id', $bengkelId)
                        ->where('stok', '<=', 5)->count(),

                    'pendapatanHariIni' => DB::table('reservasis')
                        ->where('bengkel_id', $bengkelId)
                        ->where('status', 'selesai')
                        ->whereDate('updated_at', today())
                        ->sum('total_biaya'),

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
                ]);
            })(),

            default => abort(403),
        };
    }
}