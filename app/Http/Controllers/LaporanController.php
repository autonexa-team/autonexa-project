<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Reservasi;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LaporanController extends Controller
{
    //
    public function pdf(Request $request)
    {
        $reservasis = Reservasi::with(['user','bengkel'])
            ->whereBetween('tanggal', [
                $request->dari,
                $request->sampai
            ])
            ->get();

        $totalReservasi = $reservasis->count();
        $selesai = $reservasis->where('status','done')->count();
        $totalPendapatan = $reservasis->sum('total_biaya');

        $data = [
            'reservasis' => $reservasis,
            'totalReservasi' => $totalReservasi,
            'selesai' => $selesai,
            'totalPendapatan' => $totalPendapatan,
            'userDownload' => Auth::user()->name,
            'waktuDownload' => now()
        ];

        $pdf = Pdf::loadView('admin-pusat.laporan-pdf', $data)
            ->setPaper('A4','portrait');

        return $pdf->download('laporan-autonexa.pdf');
    }    
}
