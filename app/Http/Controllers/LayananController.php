<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LayananController extends Controller
{
    // LIST DATA
    public function index()
    {
        // sementara dummy dulu
        $layanans = collect([
            (object)[
                'id' => 1,
                'nama' => 'Ganti Oli',
                'harga_dasar' => 50000,
                'durasi_menit' => 30,
                'status' => 'aktif',
                'deskripsi' => 'Servis oli mesin',
            ],
            (object)[
                'id' => 2,
                'nama' => 'Tune Up',
                'harga_dasar' => 100000,
                'durasi_menit' => 60,
                'status' => 'nonaktif',
                'deskripsi' => 'Tune up mesin',
            ],
        ]);

        return view('admin-pusat.layanan', compact('layanans'));
    }

    // FORM TAMBAH
    public function create()
    {
        return view('admin-pusat.layanan');
    }

    // SIMPAN DATA
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'harga_dasar' => 'required|numeric',
            'durasi_menit' => 'required|integer',
            'deskripsi' => 'nullable',
        ]);

        // nanti diganti ke model
        // Layanan::create($request->all());

        return redirect()->route('admin-pusat.layanan')
            ->with('success', 'Layanan berhasil ditambahkan');
    }

    // FORM EDIT
    public function edit($id)
    {
        // dummy
        $layanan = (object)[
            'id' => $id,
            'nama' => 'Ganti Oli',
            'harga_dasar' => 50000,
            'durasi_menit' => 30,
            'deskripsi' => 'Servis oli mesin',
            'status' => 'aktif',
        ];

        return view('admin-pusat.layanan.edit', compact('layanans'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'harga_dasar' => 'required|numeric',
            'durasi_menit' => 'required|integer',
        ]);

        // nanti pakai model
        // Layanan::findOrFail($id)->update($request->all());

        return redirect()->route('admin-pusat.layanan.index')
            ->with('success', 'Layanan berhasil diupdate');
    }

    // DELETE
    public function destroy($id)
    {
        // Layanan::findOrFail($id)->delete();

        return back()->with('success', 'Layanan dihapus');
    }

    // TOGGLE STATUS (AKTIF / NONAKTIF)
    public function toggleStatus($id)
    {
        // contoh logic nanti
        // $layanan = Layanan::findOrFail($id);
        // $layanan->status = $layanan->status == 'aktif' ? 'nonaktif' : 'aktif';
        // $layanan->save();

        return back()->with('success', 'Status layanan diubah');
    }
}
