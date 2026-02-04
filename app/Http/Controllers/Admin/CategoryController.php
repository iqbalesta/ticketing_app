<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $allowedPerPage = [5, 15, 25];
        $perPage = (int) $request->input('per_page', 5);
        if (! in_array($perPage, $allowedPerPage)) {
            $perPage = 5;
        }

        // opsi sorting: nama & tanggal dibuat
        $allowedSorts = ['name_asc', 'name_desc', 'date_desc', 'date_asc'];
        $sort = $request->input('sort', 'name_asc');
        if (! in_array($sort, $allowedSorts)) {
            $sort = 'name_asc';
        }

        $query = Kategori::query();

        switch ($sort) {
            case 'name_desc':
                $query->orderBy('nama', 'desc');
                break;
            case 'date_desc':
                $query->orderBy('created_at', 'desc');
                break;
            case 'date_asc':
                $query->orderBy('created_at', 'asc');
                break;
            default: // name_asc
                $query->orderBy('nama', 'asc');
                break;
        }

        $categories = $query
            ->paginate($perPage)
            ->withQueryString();

        return view('pages.admin.category.index', [
            'categories'      => $categories,
            'perPage'         => $perPage,
            'allowedPerPage'  => $allowedPerPage,
            'sort'            => $sort,
        ]);
    }

    public function store(Request $request)
    {
        $payload = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        if (!isset($payload['nama'])) {
            return redirect()->route('admin.categories.index')->with('error', 'Nama kategori wajib diisi.');
        }

        Kategori::create([
            'nama' => $payload['nama'],
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $payload = $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        if (!isset($payload['nama'])) {
            return redirect()->route('admin.categories.index')->with('error', 'Nama kategori wajib diisi.');
        }

        $category = Kategori::findOrFail($id);
        $category->nama = $payload['nama'];
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        Kategori::destroy($id);
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
