<?php

namespace App\Http\Controllers;

use App\Models\Bengkel;
use App\Models\Review;
use Illuminate\Http\Request;

class BengkelController extends Controller
{
    public function index()
    {
        $bengkels = Bengkel::withAvg(['review as rating_avg' => function($q){
                $q->where('type','bengkel');
            }], 'rating')
            ->withCount(['review as review_count' => function($q){
                $q->where('type','bengkel');
            }])
            ->with('layanan')
            ->get();

        $totalBengkel = $bengkels->count();
        $avgRating = $bengkels->avg('rating_avg'); // rata-rata semua bengkel
        $totalReview = $bengkels->sum('review_count');
        $ratingWebsite = Review::where('type','website')->avg('rating');

        return view('pelanggan.bengkel', [
            'bengkels'       => $bengkels,
            'totalBengkel'   => $totalBengkel,
            'avgRating'      => round($avgRating, 1),
            'totalReview'    => $totalReview,
            'ratingWebsite'  => round($ratingWebsite, 1),
        ]);
    }
}