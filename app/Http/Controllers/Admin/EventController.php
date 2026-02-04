<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $allowedPerPage = [5, 15, 25];
        $perPage = (int) $request->input('per_page', 5);
        if (! in_array($perPage, $allowedPerPage)) {
            $perPage = 5;
        }

        // sorting
        $allowedSorts = ['tanggal_desc', 'tanggal_asc', 'judul_asc', 'judul_desc'];
        $sort = $request->input('sort', 'tanggal_desc');
        if (! in_array($sort, $allowedSorts)) {
            $sort = 'tanggal_desc';
        }

        $query = Event::with(['kategori', 'lokasi']);

        switch ($sort) {
            case 'tanggal_asc':
                $query->orderBy('tanggal_waktu', 'asc');
                break;
            case 'judul_asc':
                $query->orderBy('judul', 'asc');
                break;
            case 'judul_desc':
                $query->orderBy('judul', 'desc');
                break;
            default: // 'tanggal_desc'
                $query->orderBy('tanggal_waktu', 'desc');
                break;
        }

        $events = $query
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.event.index', [
            'events' => $events,
            'perPage' => $perPage,
            'allowedPerPage' => $allowedPerPage,
            'sort' => $sort,
        ]);
    }

    public function create()
    {
        $categories = Kategori::all();
        $lokasis = Lokasi::all();
        return view('admin.event.create', compact('categories', 'lokasis'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_waktu' => 'required|date',
            'lokasi_id' => 'nullable|exists:lokasis,id',
            'kategori_id' => 'required|exists:kategoris,id',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('events', 'public');
            $validatedData['gambar'] = $path; // contoh: events/123456.jpg
        }

        $validatedData['user_id'] = auth()->user()->id ?? null;

        Event::create($validatedData);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $event = Event::findOrFail($id);
        $categories = Kategori::all();
        $tickets = $event->tikets;

        return view('admin.event.show', compact('event', 'categories', 'tickets'));
    }

    public function edit(string $id)
    {
        $event = Event::findOrFail($id);
        $categories = Kategori::all();
        $lokasis = Lokasi::all();
        return view('admin.event.edit', compact('event', 'categories', 'lokasis'));
    }

    public function update(Request $request, string $id)
    {
        try {
            $event = Event::findOrFail($id);

            $validatedData = $request->validate([
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'tanggal_waktu' => 'required|date',
                'lokasi_id' => 'nullable|exists:lokasis,id',
                'kategori_id' => 'required|exists:kategoris,id',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Handle file upload
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('events', 'public');
            $validatedData['gambar'] = $path; // contoh: events/123456.jpg
        }

            $event->update($validatedData);

            return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui event: ' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus.');
    }
}
