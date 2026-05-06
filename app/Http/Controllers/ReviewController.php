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

        return view('admin-pusat.review.index', [
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
}