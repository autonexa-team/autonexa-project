<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sparepart;

class SparepartController extends Controller
{
    // tambah data sekarang dummy dulu aja 
    public function store(Request $request)
    {
        // tidak simpan ke DB
        return redirect()->route('admin-pusat.sparepart')
            ->with('success', 'Sparepart berhasil ditambahkan (dummy)');
    }    

    // edit data dummy juga
    public function update(Request $request, $id)
    {
        return redirect()->route('admin-pusat.sparepart')
            ->with('success', 'Sparepart berhasil diperbarui (dummy)');
    }

    //ini katanya yang aslinya
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nama' => 'required',
    //         'harga' => 'required|numeric',
    //         'deskripsi' => 'nullable',
    //     ]);

    //     Sparepart::create([
    //         'nama' => $request->nama,
    //         'harga' => $request->harga,
    //         'deskripsi' => $request->deskripsi,
    //     ]);

    //     return redirect()->route('admin-pusat.sparepart')
    //                     ->with('success', 'Sparepart berhasil ditambahkan');
    // }    

}
