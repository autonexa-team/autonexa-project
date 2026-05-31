<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Reservasi;
use App\Models\Bengkel;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    private function parsePeriode(Request $request): array
    {
        $periode = $request->input('periode');

        if ($periode === 'harian') {
            return [now()->format('Y-m-d'), now()->format('Y-m-d')];
        }

        if ($periode === 'mingguan') {
            return [
                now()->startOfWeek()->format('Y-m-d'),
                now()->endOfWeek()->format('Y-m-d'),
            ];
        }

        return [
            $request->input('dari',   now()->startOfMonth()->format('Y-m-d')),
            $request->input('sampai', now()->format('Y-m-d')),
        ];
    }

    private function getReportData(string $dari, string $sampai): array
    {
        $reservasis = Reservasi::with(['user', 'bengkel'])
            ->whereBetween('tanggal', [$dari, $sampai])
            ->orderBy('tanggal')
            ->get();

        $pendapatanHarian = Reservasi::with('bengkel')
            ->selectRaw('DATE(tanggal) as tanggal, bengkel_id,
                         COUNT(*) as jumlah_transaksi,
                         SUM(total_biaya) as total')
            ->whereBetween('tanggal', [$dari, $sampai])
            ->where('status', '=', 'done')
            ->groupBy('tanggal', 'bengkel_id')
            ->orderBy('tanggal')
            ->get()
            ->map(fn($r) => (object)[
                'tanggal'          => $r->tanggal,
                'bengkel'          => $r->bengkel->nama ?? '-',
                'jumlah_transaksi' => $r->jumlah_transaksi,
                'total'            => $r->total,
            ]);

        $performaBengkel = Bengkel::withCount([
                'reservasis as total_reservasi' => fn($q) =>
                    $q->whereBetween('tanggal', [$dari, $sampai])
            ])
            ->withSum([
                'reservasis as total_pendapatan' => fn($q) =>
                    $q->whereBetween('tanggal', [$dari, $sampai])
                      ->where('status', '=', 'done')
            ], 'total_biaya')
            ->withAvg([
                'reviews as rating' => fn($q) =>
                    $q->whereBetween('created_at', [$dari, $sampai])
            ], 'rating')
            ->get()
            ->map(fn($b) => (object)[
                'nama'             => $b->nama,
                'total_reservasi'  => $b->total_reservasi  ?? 0,
                'total_pendapatan' => $b->total_pendapatan ?? 0,
                'rating'           => round($b->rating     ?? 0, 1),
            ]);

        $reviews = Bengkel::withCount([
                'reviews as jumlah_review' => fn($q) =>
                    $q->whereBetween('created_at', [$dari, $sampai])
            ])
            ->withAvg([
                'reviews as rating_avg' => fn($q) =>
                    $q->whereBetween('created_at', [$dari, $sampai])
            ], 'rating')
            ->get()
            ->map(fn($b) => (object)[
                'bengkel'       => $b->nama,
                'jumlah_review' => $b->jumlah_review ?? 0,
                'rating_avg'    => round($b->rating_avg ?? 0, 1),
            ]);

        return [$reservasis, $pendapatanHarian, $performaBengkel, $reviews];
    }

    private function getReportDataCabang(int $bengkelId, string $dari, string $sampai): array
    {
        $reservasis = Reservasi::query()
            ->with('user')
            ->where('bengkel_id', '=', $bengkelId)
            ->whereBetween('tanggal', [$dari, $sampai])
            ->orderBy('tanggal')
            ->get();

        $pendapatanHarian = Reservasi::selectRaw(
                'DATE(tanggal) as tanggal,
                 COUNT(*) as jumlah_transaksi,
                 SUM(total_biaya) as total'
            )
            ->where('bengkel_id', '=', $bengkelId)
            ->whereBetween('tanggal', [$dari, $sampai])
            ->where('status', '=', 'done')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->map(fn($r) => (object)[
                'tanggal'          => $r->tanggal,
                'jumlah_transaksi' => $r->jumlah_transaksi,
                'total'            => $r->total ?? 0,
            ]);

        $reviews = Review::query()
            ->with('user')
            ->where('bengkel_id', '=', $bengkelId)
            ->whereBetween('created_at', [
                $dari   . ' 00:00:00',
                $sampai . ' 23:59:59',
            ])
            ->orderByDesc('created_at')
            ->get();

        return [$reservasis, $pendapatanHarian, $reviews];
    }

    // ── ADMIN PUSAT — Halaman laporan ──────────────────────────────
    public function index(Request $request)
    {
        [$dari, $sampai] = $this->parsePeriode($request);

        [$reservasis, $pendapatanHarian, $performaBengkel, $reviews] =
            $this->getReportData($dari, $sampai);

        $totalReservasi  = $reservasis->count();
        $selesai         = $reservasis->where('status', 'done')->count();
        $dibatalkan      = $reservasis->where('status', 'cancelled')->count();
        $totalPendapatan = $reservasis->where('status', 'done')->sum('total_biaya');
        $totalReview     = $reviews->sum('jumlah_review');
        $avgRating       = $reviews->avg('rating_avg') ?? 0;

        return view('admin-pusat.laporan', compact(
            'dari', 'sampai',
            'reservasis', 'pendapatanHarian', 'performaBengkel', 'reviews',
            'totalReservasi', 'selesai', 'dibatalkan',
            'totalPendapatan', 'totalReview', 'avgRating'
        ));
    }

    // ── ADMIN PUSAT — Export PDF ───────────────────────────────────
    public function exportPdf(Request $request)
    {
        [$dari, $sampai] = $this->parsePeriode($request);

        [$reservasis, $pendapatanHarian, $performaBengkel, $reviews] =
            $this->getReportData($dari, $sampai);

        $totalReservasi  = $reservasis->count();
        $selesai         = $reservasis->where('status', 'done')->count();
        $dibatalkan      = $reservasis->where('status', 'cancelled')->count();
        $totalPendapatan = $reservasis->where('status', 'done')->sum('total_biaya');
        $totalReview     = $reviews->sum('jumlah_review');
        $avgRating       = $reviews->avg('rating_avg') ?? 0;

        $pdf = Pdf::loadView('admin-pusat.laporan-pdf', [
            'dari'             => $dari,
            'sampai'           => $sampai,
            'userDownload'     => Auth::user()->name,
            'waktuDownload'    => now(),
            'reservasis'       => $reservasis,
            'pendapatanHarian' => $pendapatanHarian,
            'performaBengkel'  => $performaBengkel,
            'reviews'          => $reviews,
            'totalReservasi'   => $totalReservasi,
            'selesai'          => $selesai,
            'dibatalkan'       => $dibatalkan,
            'totalReview'      => $totalReview,
            'totalPendapatan'  => $totalPendapatan,
            'avgRating'        => $avgRating,
            'totalPelanggan'   => $reservasis->pluck('user_id')->unique()->count(),
        ])
        ->setPaper('a4', 'portrait')
        ->setOption('defaultFont', 'DejaVu Sans')
        ->setOption('isRemoteEnabled', true);

        return $pdf->stream('laporan-autonexa-' . now()->format('Ymd-Hi') . '.pdf');
    }

    // ── ADMIN CABANG — Halaman laporan ────────────────────────────
    public function indexCabang(Request $request)
    {
        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $bengkel = $user->bengkel;

        $dari   = $request->dari   ?? now()->startOfMonth()->toDateString();
        $sampai = $request->sampai ?? now()->endOfMonth()->toDateString();

        [$reservasis, $pendapatanHarian, $reviews] =
            $this->getReportDataCabang($bengkel->id, $dari, $sampai);

        $totalReservasi  = $reservasis->count();
        $totalPendapatan = $reservasis->where('status', 'done')->sum('total_biaya');
        $selesai         = $reservasis->where('status', 'done')->count();
        $dibatalkan      = $reservasis->where('status', 'cancelled')->count();
        $totalReview     = $reviews->count();
        $avgRating       = $reviews->avg('rating') ?? 0;

        return view('admin-cabang.laporan', compact(
            'reservasis', 'pendapatanHarian', 'reviews',
            'totalReservasi', 'totalPendapatan',
            'selesai', 'dibatalkan', 'totalReview', 'avgRating',
            'dari', 'sampai', 'bengkel'          // ← tambah $bengkel biar bisa dipakai di blade
        ));
    }

    // ── ADMIN CABANG — Export PDF ─────────────────────────────────
    public function exportPdfCabang(Request $request)
    {
        $bengkel = Bengkel::where('admin_id', '=', Auth::id())->firstOrFail();

        [$dari, $sampai] = $this->parsePeriode($request);

        [$reservasis, $pendapatanHarian, $reviews] =
            $this->getReportDataCabang($bengkel->id, $dari, $sampai);

        $totalReservasi  = $reservasis->count();
        $selesai         = $reservasis->where('status', 'done')->count();
        $dibatalkan      = $reservasis->where('status', 'cancelled')->count();
        $totalPendapatan = $reservasis->where('status', 'done')->sum('total_biaya');
        $totalReview     = $reviews->count();
        $avgRating       = $reviews->avg('rating') ?? 0;

        $pdf = Pdf::loadView('admin-cabang.laporan-pdf', [
            'dari'             => $dari,
            'sampai'           => $sampai,
            'userDownload'     => Auth::user()->name,
            'waktuDownload'    => now(),
            'namaBengkel'      => $bengkel->nama,
            'reservasis'       => $reservasis,
            'pendapatanHarian' => $pendapatanHarian,
            'reviews'          => $reviews,
            'totalReservasi'   => $totalReservasi,
            'selesai'          => $selesai,
            'dibatalkan'       => $dibatalkan,
            'totalPendapatan'  => $totalPendapatan,
            'totalReview'      => $totalReview,
            'avgRating'        => $avgRating,
        ])
        ->setPaper('a4', 'portrait')
        ->setOption('defaultFont', 'DejaVu Sans')
        ->setOption('isRemoteEnabled', true);

        return $pdf->stream(
            'laporan-' . \Illuminate\Support\Str::slug($bengkel->nama) .
            '-' . now()->format('Ymd-Hi') . '.pdf'
        );
    }
}