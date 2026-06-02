<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    // LIST DATA
    public function index()
    {
        // Fetch dari database
        $layanans = Layanan::all();

        return view('admin-pusat.layanan', compact('layanans'));
    }

    // FORM TAMBAH
    public function create()
    {
        $layanans = Layanan::all();
        return view('admin-pusat.layanan', compact('layanans'));
    }

    // SIMPAN DATA
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'durasi' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Layanan::create($validated);

        return redirect()->route('admin-pusat.layanan')
            ->with('success', 'Layanan berhasil ditambahkan');
    }

    // FORM EDIT
    public function edit(int $id)
    {
        $layanan  = Layanan::findOrFail($id);
        $layanans = Layanan::all();
        return view('admin-pusat.layanan', compact('layanans', 'layanan'));
    }

    // UPDATE
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'durasi' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $layanan = Layanan::findOrFail($id);
        $layanan->update($validated);

        return redirect()->route('admin-pusat.layanan')
            ->with('success', 'Layanan berhasil diupdate');
    }

    // DELETE
    public function destroy(int $id)
    {
        $layanan = Layanan::findOrFail($id);
        $layanan->delete();

        return back()->with('success', 'Layanan berhasil dihapus');
    }

    // TOGGLE STATUS (AKTIF / NONAKTIF)
    public function toggleStatus(int $id)
    {
        $layanan = Layanan::findOrFail($id);
        $layanan->status = $layanan->status === 'aktif' ? 'nonaktif' : 'aktif';
        $layanan->save();

        return back()->with('success', 'Status layanan diubah menjadi ' . ucfirst($layanan->status));
    }
}