<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lokasis = Lokasi::all();
        return view('admin.lokasi.index', compact('lokasis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.lokasi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255|unique:lokasis',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kapasitas' => 'nullable|numeric|min:0',
        ]);

        Lokasi::create($validatedData);

        return redirect()->route('admin.lokasi.index')->with('success', 'Lokasi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lokasi $lokasi)
    {
        return view('admin.lokasi.show', compact('lokasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lokasi $lokasi)
    {
        return view('admin.lokasi.edit', compact('lokasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lokasi $lokasi)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255|unique:lokasis,nama,' . $lokasi->id,
            'alamat' => 'required|string',
            'kota' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kapasitas' => 'nullable|numeric|min:0',
        ]);

        $lokasi->update($validatedData);

        return redirect()->route('admin.lokasi.index')->with('success', 'Lokasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lokasi $lokasi)
    {
        $lokasi->delete();

        return redirect()->route('admin.lokasi.index')->with('success', 'Lokasi berhasil dihapus.');
    }
}
