<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Reservasi;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Bengkel;


class LaporanController extends Controller
{
    /**
     * Halaman laporan utama.
     */
    public function index(Request $request)
    {
        $dari   = $request->input('dari',   now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->input('sampai', now()->format('Y-m-d'));
 
        // ── GANTI BAGIAN INI dengan query Eloquent nyata ──────────
        //
        // $reservasis = Reservasi::with(['user', 'bengkel'])
        //     ->whereBetween('tanggal', [$dari, $sampai])
        //     ->orderBy('tanggal')
        //     ->get();
        //
        // $pendapatanHarian = Reservasi::selectRaw(
        //         'DATE(tanggal) as tanggal,
        //          bengkel_id,
        //          COUNT(*) as jumlah_transaksi,
        //          SUM(total_biaya) as total')
        //     ->with('bengkel')
        //     ->whereBetween('tanggal', [$dari, $sampai])
        //     ->where('status', 'done')
        //     ->groupBy('tanggal', 'bengkel_id')
        //     ->orderBy('tanggal')
        //     ->get()
        //     ->map(fn($r) => (object)[
        //         'tanggal'          => $r->tanggal,
        //         'bengkel'          => $r->bengkel->nama,
        //         'jumlah_transaksi' => $r->jumlah_transaksi,
        //         'total'            => $r->total,
        //     ]);
        //
        // $performaBengkel = Bengkel::withCount(['reservasis as total_reservasi' => fn($q) =>
        //         $q->whereBetween('tanggal', [$dari, $sampai])])
        //     ->withSum(['reservasis as total_pendapatan' => fn($q) =>
        //         $q->whereBetween('tanggal', [$dari, $sampai])->where('status', 'done')], 'total_biaya')
        //     ->withAvg('reviews as rating', 'rating')
        //     ->get()
        //     ->map(fn($b) => (object)[
        //         'nama'             => $b->nama,
        //         'total_reservasi'  => $b->total_reservasi,
        //         'total_pendapatan' => $b->total_pendapatan ?? 0,
        //         'rating'           => round($b->rating ?? 0, 1),
        //     ]);
        //
        // $reviews = Bengkel::withCount('reviews as jumlah_review')
        //     ->withAvg('reviews as rating_avg', 'rating')
        //     ->get()
        //     ->map(fn($b) => (object)[
        //         'bengkel'     => $b->nama,
        //         'jumlah_review' => $b->jumlah_review,
        //         'rating_avg'  => round($b->rating_avg ?? 0, 1),
        //     ]);
        //
        // ─────────────────────────────────────────────────────────
 
        // Dummy data sementara (hapus saat pakai model nyata)
        [$reservasis, $pendapatanHarian, $performaBengkel, $reviews] = $this->dummyData();
 
        $totalReservasi  = $reservasis->count();
        $selesai         = $reservasis->where('status', 'done')->count();
        $totalPendapatan = $reservasis->sum('total_biaya');
        $avgRating       = $reviews->avg('rating_avg');
 
        return view('admin-pusat.laporan', compact(
            'dari', 'sampai',
            'reservasis', 'pendapatanHarian', 'performaBengkel', 'reviews',
            'totalReservasi', 'selesai', 'totalPendapatan', 'avgRating'
        ));
    }
 
    /**
     * Generate & stream PDF.
     */
    public function exportPdf(Request $request)
    {
        $dari   = $request->input('dari',   now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->input('sampai', now()->format('Y-m-d'));
 
        // Sama seperti index — ganti dengan query nyata
        [$reservasis, $pendapatanHarian, $performaBengkel, $reviews] = $this->dummyData();
 
        $totalReservasi  = $reservasis->count();
        $selesai         = $reservasis->where('status', 'done')->count();
        $totalPendapatan = $reservasis->sum('total_biaya');
        $avgRating       = $reviews->avg('rating_avg');
 
        $pdf = Pdf::loadView('admin-pusat.laporan-pdf', [
            'dari'              => $dari,
            'sampai'            => $sampai,
            'userDownload'      => Auth::user()->name,
            'waktuDownload'     => now(),
            'reservasis'        => $reservasis,
            'pendapatanHarian'  => $pendapatanHarian,
            'performaBengkel'   => $performaBengkel,
            'reviews'           => $reviews,
            'totalReservasi'    => $totalReservasi,
            'selesai'           => $selesai,
            'totalPendapatan'   => $totalPendapatan,
            'avgRating'         => $avgRating,
        ])
        ->setPaper('a4', 'portrait')
        ->setOption('defaultFont', 'DejaVu Sans')
        ->setOption('isRemoteEnabled', true);
 
        $filename = 'laporan-autonexa-' . now()->format('Ymd-Hi') . '.pdf';
 
        return $pdf->stream($filename);
    }
 
    /**
     * Dummy data — hapus method ini saat pakai model nyata.
     */
    private function dummyData(): array
    {
        $reservasis = collect([
            (object)['user' => (object)['name' => 'Budi Santoso'],   'bengkel' => (object)['nama' => 'Bengkel Maju Jaya'],   'layanan' => 'Ganti Oli',    'tanggal' => '2026-05-01', 'status' => 'done',        'total_biaya' => 120000],
            (object)['user' => (object)['name' => 'Siti Rahayu'],    'bengkel' => (object)['nama' => 'Bengkel Prima Motor'], 'layanan' => 'Tune Up',      'tanggal' => '2026-05-02', 'status' => 'done',        'total_biaya' => 250000],
            (object)['user' => (object)['name' => 'Andi Wijaya'],    'bengkel' => (object)['nama' => 'Bengkel Cepat Beres'], 'layanan' => 'Ganti Ban',    'tanggal' => '2026-05-03', 'status' => 'in_progress', 'total_biaya' => 180000],
            (object)['user' => (object)['name' => 'Dewi Lestari'],   'bengkel' => (object)['nama' => 'Bengkel Maju Jaya'],   'layanan' => 'Servis Rem',   'tanggal' => '2026-05-04', 'status' => 'confirmed',   'total_biaya' => 95000],
            (object)['user' => (object)['name' => 'Rudi Hermawan'],  'bengkel' => (object)['nama' => 'Bengkel Prima Motor'], 'layanan' => 'Ganti Rantai', 'tanggal' => '2026-05-05', 'status' => 'done',        'total_biaya' => 145000],
            (object)['user' => (object)['name' => 'Agus Pramono'],   'bengkel' => (object)['nama' => 'Bengkel Setia Kawan'], 'layanan' => 'Ganti Kampas', 'tanggal' => '2026-05-07', 'status' => 'done',        'total_biaya' => 110000],
            (object)['user' => (object)['name' => 'Hendra Gunawan'], 'bengkel' => (object)['nama' => 'Bengkel Prima Motor'], 'layanan' => 'Servis Karbu', 'tanggal' => '2026-05-09', 'status' => 'done',        'total_biaya' => 200000],
            (object)['user' => (object)['name' => 'Fitriani'],       'bengkel' => (object)['nama' => 'Bengkel Setia Kawan'], 'layanan' => 'Ganti Oli',    'tanggal' => '2026-05-10', 'status' => 'done',        'total_biaya' => 120000],
        ]);
 
        $pendapatanHarian = collect([
            (object)['tanggal' => '2026-05-01', 'bengkel' => 'Bengkel Maju Jaya',   'jumlah_transaksi' => 3, 'total' => 360000],
            (object)['tanggal' => '2026-05-02', 'bengkel' => 'Bengkel Prima Motor', 'jumlah_transaksi' => 5, 'total' => 875000],
            (object)['tanggal' => '2026-05-03', 'bengkel' => 'Bengkel Cepat Beres', 'jumlah_transaksi' => 2, 'total' => 330000],
            (object)['tanggal' => '2026-05-04', 'bengkel' => 'Bengkel Setia Kawan', 'jumlah_transaksi' => 4, 'total' => 520000],
            (object)['tanggal' => '2026-05-05', 'bengkel' => 'Bengkel Maju Jaya',   'jumlah_transaksi' => 6, 'total' => 940000],
        ]);
 
        $performaBengkel = collect([
            (object)['nama' => 'Bengkel Maju Jaya',   'total_reservasi' => 42, 'total_pendapatan' => 5250000, 'rating' => 4.8],
            (object)['nama' => 'Bengkel Prima Motor',  'total_reservasi' => 38, 'total_pendapatan' => 4720000, 'rating' => 4.6],
            (object)['nama' => 'Bengkel Cepat Beres',  'total_reservasi' => 31, 'total_pendapatan' => 3890000, 'rating' => 4.5],
            (object)['nama' => 'Bengkel Setia Kawan',  'total_reservasi' => 27, 'total_pendapatan' => 3100000, 'rating' => 4.3],
        ]);
 
        $reviews = collect([
            (object)['bengkel' => 'Bengkel Maju Jaya',   'jumlah_review' => 38, 'rating_avg' => 4.8],
            (object)['bengkel' => 'Bengkel Prima Motor',  'jumlah_review' => 31, 'rating_avg' => 4.6],
            (object)['bengkel' => 'Bengkel Cepat Beres',  'jumlah_review' => 25, 'rating_avg' => 4.5],
            (object)['bengkel' => 'Bengkel Setia Kawan',  'jumlah_review' => 19, 'rating_avg' => 4.3],
        ]);
 
        return [$reservasis, $pendapatanHarian, $performaBengkel, $reviews];
    }
}