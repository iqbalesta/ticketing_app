<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allowedPerPage = [5, 15, 25];
        $perPage = (int) $request->input('per_page', 5);
        if (! in_array($perPage, $allowedPerPage)) {
            $perPage = 5;
        }

        // opsi sorting: nama, kota, kapasitas
        $allowedSorts = ['name_asc', 'name_desc', 'city_asc', 'city_desc', 'capacity_desc', 'capacity_asc'];
        $sort = $request->input('sort', 'name_asc');
        if (! in_array($sort, $allowedSorts)) {
            $sort = 'name_asc';
        }

        $query = Lokasi::query();

        switch ($sort) {
            case 'name_desc':
                $query->orderBy('nama', 'desc');
                break;
            case 'city_asc':
                $query->orderBy('kota', 'asc');
                break;
            case 'city_desc':
                $query->orderBy('kota', 'desc');
                break;
            case 'capacity_desc':
                $query->orderBy('kapasitas', 'desc');
                break;
            case 'capacity_asc':
                $query->orderBy('kapasitas', 'asc');
                break;
            default: // name_asc
                $query->orderBy('nama', 'asc');
                break;
        }

        $lokasis = $query
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.lokasi.index', [
            'lokasis'        => $lokasis,
            'perPage'        => $perPage,
            'allowedPerPage' => $allowedPerPage,
            'sort'           => $sort,
        ]);
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
