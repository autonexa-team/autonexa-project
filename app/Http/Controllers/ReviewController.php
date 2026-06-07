<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Bengkel;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'bengkel', 'reservasi'])
                       ->latest();

        /* Filter bengkel */
        if ($request->filled('bengkel_id')) {
            $query->where('bengkel_id', $request->bengkel_id);
        }

        /* Filter rating */
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        /* Search nama pelanggan / komentar */
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->whereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%"))
                   ->orWhere('komentar', 'like', "%{$q}%");
            });
        }

        /* Sorting */
        match ($request->get('sort', 'terbaru')) {
            'tertinggi' => $query->reorder('rating', 'desc')->orderBy('created_at', 'desc'),
            'terendah'  => $query->reorder('rating', 'asc')->orderBy('created_at', 'desc'),
            default     => $query->reorder('created_at', 'desc'),
        };

        $reviews = $query->paginate(10)->withQueryString();

        return view('admin-pusat.review', [
            'reviews'       => $reviews,
            'bengkels'      => Bengkel::orderBy('nama')->get(),
            'totalReview'   => Review::count(),
            'avgRating'     => round(Review::avg('rating'), 1),
            'reviewHariIni' => Review::whereDate('created_at', today())->count(),
            'bengkelTerbaik'=> Bengkel::withAvg('reviews', 'rating')
                                      ->orderByDesc('reviews_avg_rating')
                                      ->first(),
        ]);        
    }

    // ReviewController
    public function show($id) {
        $review = Review::with(['user', 'bengkel', 'reservasi.mekanik'])->findOrFail($id);
        $riwayatReview = Review::with('bengkel')
            ->where('user_id', $review->user_id)
            ->where('id', '!=', $review->id)
            ->latest()->take(3)->get();
        return view('admin-pusat.review-detail', compact('review', 'riwayatReview'));
    }  
    
    public function indexCabang(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $bengkel = $user->bengkel;

        $query = Review::with(['user', 'reservasi'])
                    ->where('bengkel_id', $bengkel->id)
                    ->latest();

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->whereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%"))
                ->orWhere('komentar', 'like', "%{$q}%");
            });
        }

        match ($request->get('sort', 'terbaru')) {
            'tertinggi' => $query->reorder('rating', 'desc'),
            'terendah'  => $query->reorder('rating', 'asc'),
            default     => $query->reorder('created_at', 'desc'),
        };

        $reviews = $query->paginate(10)->withQueryString();

        return view('admin-cabang.review', [
            'reviews'       => $reviews,
            'totalReview'   => Review::where('bengkel_id', $bengkel->id)->count(),
            'avgRating'     => round(Review::where('bengkel_id', $bengkel->id)->avg('rating'), 1),
            'reviewHariIni' => Review::where('bengkel_id', $bengkel->id)
                                    ->whereDate('created_at', today())->count(),
        ]);
    }

    public function exportPusat(Request $request)
    {
        $reviews = Review::with(['user', 'bengkel', 'reservasi'])
            ->latest()
            ->get();

        $filename = 'laporan-review-semua-' . now()->format('Ymd') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($reviews) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['No', 'Nama Pelanggan', 'Bengkel', 'Rating', 'Komentar', 'ID Reservasi', 'Tanggal Review']);

            $reviews->each(function ($review, $index) use ($file) {
                fputcsv($file, [
                    $index + 1,
                    $review->user->name ?? '-',
                    $review->bengkel->nama ?? '-',
                    $review->rating,
                    $review->komentar ?? '-',
                    $review->reservasi_id ?? '-',
                    $review->created_at->format('d/m/Y H:i'),
                ]);
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportCabang(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $bengkel = $user->bengkel;

        $reviews = Review::with(['user', 'reservasi'])
            ->where('bengkel_id', $bengkel->id)
            ->latest()
            ->get();

        $filename = 'laporan-review-' . \Str::slug($bengkel->nama) . '-' . now()->format('Ymd') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($reviews) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, ['No', 'Nama Pelanggan', 'Rating', 'Komentar', 'ID Reservasi', 'Tanggal Review']);

            $reviews->each(function ($review, $index) use ($file) {
                fputcsv($file, [
                    $index + 1,
                    $review->user->name ?? '-',
                    $review->rating,
                    $review->komentar ?? '-',
                    $review->reservasi_id ?? '-',
                    $review->created_at->format('d/m/Y H:i'),
                ]);
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function showCabang($id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $bengkel = $user->bengkel;

        $review = Review::with(['user', 'bengkel', 'reservasi'])
                        ->where('bengkel_id', $bengkel->id)
                        ->findOrFail($id);

        $riwayatReview = Review::with('bengkel')
            ->where('user_id', $review->user_id)
            ->where('id', '!=', $review->id)
            ->latest()->take(3)->get();

        return view('admin-cabang.review-detail', compact('review', 'riwayatReview'));
    }

    public function exportPdf(Request $request)
    {
        $bengkel = auth()->user()->bengkel; // sesuaikan dengan relasi Anda

        // Ambil semua review (tanpa pagination) untuk export
        $query = Review::with(['user', 'reservasi', 'bengkel'])
            ->where('bengkel_id', $bengkel->id);

        // Filter opsional (jika ingin tetap pakai filter dari halaman)
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', '%'.$request->search.'%'))
                ->orWhere('komentar', 'like', '%'.$request->search.'%');
            });
        }

        $reviews = $query->latest()->get();

        // Statistik
        $totalReview  = $reviews->count();
        $avgRating    = $reviews->avg('rating') ?? 0;
        $positifCount = $reviews->where('rating', '>=', 4)->count();
        $negatifCount = $reviews->where('rating', '<=', 2)->count();

        // Distribusi rating: [ 5 => 12, 4 => 8, 3 => 3, 2 => 1, 1 => 0 ]
        $ratingDist = $reviews->groupBy('rating')
            ->map->count()
            ->toArray();

        // Periode laporan
        $first  = $reviews->last()?->created_at;   // oldest
        $last   = $reviews->first()?->created_at;  // newest
        $periode = $first && $last
            ? \Carbon\Carbon::parse($first)->translatedFormat('d M Y')
            . ' – '
            . \Carbon\Carbon::parse($last)->translatedFormat('d M Y')
            : \Carbon\Carbon::now()->translatedFormat('M Y');

        return view('admin-cabang.review-export', compact(
            'bengkel',
            'reviews',
            'totalReview',
            'avgRating',
            'positifCount',
            'negatifCount',
            'ratingDist',
            'periode'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating'      => 'required|integer|min:1|max:5',
            'komentar'    => 'nullable|string|max:1000',
            'fotos'       => 'nullable|array|max:5',
            'fotos.*'     => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'reservasi_id'=> 'required|exists:reservasis,id',
            'bengkel_id'  => 'required|exists:bengkels,id',
        ]);

        $review = Review::create([
            'user_id'      => auth()->id(),
            'bengkel_id'   => $request->bengkel_id,
            'reservasi_id' => $request->reservasi_id,
            'rating'       => $request->rating,
            'komentar'     => $request->komentar,
        ]);

        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $foto) {
                $path = $foto->store('reviews', 'public');
                $review->fotos()->create(['foto' => $path]);
            }
        }

        return response()->json(['message' => 'Berhasil!']);
    }
}