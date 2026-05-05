<?php

namespace App\Http\Controllers;

use App\Models\Bengkel;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class BengkelController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['pelangganIndex']);
    }

    //untuk admin pusat dengan tampa data dummy
    // public function index()
    // {
    //     // 🔒 proteksi role
    //     if (Auth::user()->role !== 'admin_pusat') {
    //         abort(403);
    //     }

    //     $bengkels = Bengkel::with('layanan')
    //         ->withCount(['reservasis'])
    //         ->withAvg('review as reviews_avg_rating', 'rating')
    //         ->paginate(12);

    //         //karna masih tahapan tampilan, jadi untuk ini nanti aja diurus sama sukma
    //     return view('admin-pusat.bengkel', [
    //         'bengkels'      => $bengkels,
    //         // 'kotaList'      => Bengkel::distinct()->pluck('kota'),
    //         // 'totalAktif'    => Bengkel::where('status','aktif')->count(),
    //         // 'totalNonaktif' => Bengkel::where('status','nonaktif')->count(),
    //         // 'totalKota'     => Bengkel::distinct('kota')->count(),
    //     ]);
    // }
    
    //untuk admin pusat dengan menggunakan data dummy
    public function index()
    {
        // 🔥 DATA DUMMY
        $data = collect([
            (object)[
                'id' => 1,
                'nama' => 'Bengkel Jaya Motor',
                'alamat' => 'Jl. Asia Afrika No.10',
                'kota' => 'Bandung',
                'status' => 'aktif',
                'foto' => 'bengkel.jpg',
                'warna' => '#ff6a00',
                'reservasis_count' => 25,                
                'avg_rating' => 4.7,
                'layanan' => collect([
                    ['nama' => 'Ganti Oli'],
                    ['nama' => 'Tune Up'],
                    ['nama' => 'Servis Mesin']
                ])
            ],
            (object)[
                'id' => 2,
                'nama' => 'Bengkel Maju Jaya',
                'alamat' => 'Jl. Dago No.5',
                'kota' => 'Bandung',
                'status' => 'nonaktif',
                'foto' => null,
                'warna' => '#3b82f6',
                'reservasis_count' => 10,                
                'avg_rating' => 4.2,
                'layanan' => collect([
                    ['nama' => 'Ban'],
                    ['nama' => 'Kelistrikan']
                ])
            ]
        ]);

        $bengkels = new LengthAwarePaginator(
            $data,
            $data->count(),
            10
        );
    

        return view('admin-pusat.bengkel', [
            'bengkels' => $bengkels
        ]);
    }    

    //untuk tampilan ke pelanggan 
    public function pelangganIndex()
    {
        $bengkels = Bengkel::where('status', 'aktif') // ✅ hanya tampilkan aktif
            ->withAvg(['review as reviews_avg_rating' => function($q){
                $q->where('type','bengkel');
            }], 'rating')
            ->withCount(['review as reviews_count' => function($q){
                $q->where('type','bengkel');
            }])
            ->with('layanan')
            ->get();

        $totalBengkel = $bengkels->count();
        $avgRating    = $bengkels->avg('reviews_avg_rating') ?? 0;
        $totalReview  = $bengkels->sum('reviews_count');

        return view('pelanggan.bengkel', [
            'bengkels'     => $bengkels,
            'totalBengkel' => $totalBengkel,
            'avgRating'    => round($avgRating, 1),
            'totalReview'  => $totalReview,
        ]);
    }  

    //fungsi untuk menampilkan form tambah bengkel, nanti untuk proses simpannya di store()
    public function create()
    {
        // opsional: proteksi role (kalau belum pakai middleware role di route)
        if (Auth::user()->role !== 'admin_pusat') {
            abort(403);
        }

        return view('admin-pusat.tambah-bengkel');
    }

    // ini code ntuk kalo data tidak dummy,
    // public function show($id)
    // {
    //     $bengkel = Bengkel::withCount(['reservasis', 'layanan', 'review'])
    //                     ->withAvg('review', 'rating')
    //                     ->with(['adminCabang', 'layanan', 'spareparts'])
    //                     ->findOrFail($id);

    //     return view('admin-pusat.bengkel.show', [
    //         'bengkel'               => $bengkel,
    //         'reservasiTerbaru'      => $bengkel->reservasis()
    //                                         ->with('user')
    //                                         ->latest('tanggal')
    //                                         ->take(5)
    //                                         ->get(),
    //         'reservasiHariIni'      => $bengkel->reservasis()->whereDate('tanggal', today())->count(),
    //         'reservasiSelesaiHariIni' => $bengkel->reservasis()->whereDate('tanggal', today())->where('status', 'done')->count(),
    //         'reservasiProsesHariIni'  => $bengkel->reservasis()->whereDate('tanggal', today())->where('status', 'in_progress')->count(),
    //         'reservasiBulanIni'     => $bengkel->reservasis()->whereMonth('tanggal', now()->month)->count(),
    //         'spareparts'            => $bengkel->spareparts()->orderByRaw('(stok / stok_max) ASC')->get(),
    //         'sparepartKritis'       => $bengkel->spareparts()->whereRaw('stok / stok_max <= 0.3')->count(),
    //     ]);
    // }

    // karna masih dummy, jadi pake ini dulu aja
    public function show($id)
    {
        $bengkel = (object)[
            'id' => 1,
            'nama' => 'Bengkel Jaya Motor',
            'alamat' => 'Jl. Asia Afrika No.10',
            'kota' => 'Bandung',
            'status' => 'aktif',
            'latitude' => -6.9175,
            'longitude' => 107.6191,
            'created_at' => now(),

            'admin' => (object)[
                'name' => 'Admin Cabang',
                'email' => 'admin@mail.com'
            ],

            'layanan' => collect([
                (object)[
                    'nama' => 'Ganti Oli',
                    'harga_dasar' => 50000,
                    'durasi' => 30,
                    'deskripsi' => 'Servis oli mesin'
                ],
                (object)[
                    'nama' => 'Tune Up',
                    'harga_dasar' => 100000,
                    'durasi' => 60,
                    'deskripsi' => 'Penyetelan mesin'
                ]
            ])
        ];

        // ✅ DUMMY SPAREPART
        $spareparts = collect([
            (object)[
                'nama' => 'Oli Mesin Yamalube 1L',
                'stok' => 48,
                'stok_max' => 60,
                'satuan' => 'pcs'
            ],
            (object)[
                'nama' => 'Filter Udara Honda Beat',
                'stok' => 12,
                'stok_max' => 30,
                'satuan' => 'pcs'
            ],
            (object)[
                'nama' => 'Busi NGK Standard',
                'stok' => 3,
                'stok_max' => 30,
                'satuan' => 'pcs'
            ],
            (object)[
                'nama' => 'Kampas Rem Depan',
                'stok' => 0,
                'stok_max' => 20,
                'satuan' => 'pcs'
            ]
        ]);

        // hitung stok kritis
        $sparepartKritis = $spareparts->where('stok', 0)->count();

        // ✅ DUMMY RESERVASI
        $reservasiTerbaru = collect([
            (object)[
                'id' => 1,
                'user' => (object)['name' => 'Andi Setiawan'],
                'keluhan' => 'Tune Up Motor',
                'tanggal' => now(),
                'status' => 'done'
            ],
            (object)[
                'id' => 2,
                'user' => (object)['name' => 'Siti Rahayu'],
                'keluhan' => 'Ganti Oli',
                'tanggal' => now(),
                'status' => 'in_progress'
            ],
            (object)[
                'id' => 3,
                'user' => (object)['name' => 'Budi'],
                'keluhan' => 'Servis Rem',
                'tanggal' => now(),
                'status' => 'pending'
            ]
        ]);

        return view('admin-pusat.detail-bengkel', compact(
            'bengkel',
            'spareparts',
            'sparepartKritis',
            'reservasiTerbaru'
        ));
    }

    // dummy edit bengkel
    public function edit($id)
    {
        $bengkel = (object)[
            'id' => $id,
            'nama' => 'Bengkel Jaya Motor',
            'alamat' => 'Jl. Asia Afrika No.10',
            'kota' => 'Bandung',
            'status' => 'aktif',
            'foto' => 'bengkel.jpg',
            'latitude' => -6.9175,
            'longitude' => 107.6191,
            'admin_id' => 1,

            'adminCabang' => (object)[
                'id' => 1,
                'name' => 'Admin Cabang',
                'email' => 'admin@mail.com'
            ]            
        ];

        return view('admin-pusat.edit-bengkel', compact('bengkel'));
    }

    public function toggleStatus($id)
    {
        $bengkel = Bengkel::findOrFail($id);

        $bengkel->status = $bengkel->status === 'aktif' ? 'nonaktif' : 'aktif';

        $bengkel->save();

        return back()->with('success', 'Status berhasil diubah');
    }    
}