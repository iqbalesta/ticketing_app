<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Kategori;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['user', 'kategori'])->get();
        return view('event.index', compact('events'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('event.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'waktu' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $filename = null;
        if ($request->hasFile('gambar')) {
            $filename = $request->file('gambar')->store('events', 'public');
        }

        Event::create([
            'id_user' => auth()->id() ?? 1,
            'id_kategori' => $request->id_kategori,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'waktu' => $request->waktu,
            'gambar' => $filename,
        ]);

        return redirect()->route('event.index')->with('success', 'Event berhasil ditambahkan');
    }

    public function show(Event $event)
    {
        $event->load(['tikets', 'orders']);
        return view('event.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $kategoris = Kategori::all();
        return view('event.edit', compact('event', 'kategoris'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string|max:255',
            'waktu' => 'required|date',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $filename = $event->gambar;
        if ($request->hasFile('gambar')) {
            $filename = $request->file('gambar')->store('events', 'public');
        }

        $event->update([
            'id_kategori' => $request->id_kategori,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'waktu' => $request->waktu,
            'gambar' => $filename,
        ]);

        return redirect()->route('event.index')->with('success', 'Event berhasil diupdate');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('event.index')->with('success', 'Event berhasil dihapus');
    }
}
