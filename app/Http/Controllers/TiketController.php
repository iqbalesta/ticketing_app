<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use App\Models\Event;
use Illuminate\Http\Request;

class TiketController extends Controller
{
    public function index()
    {
        $tikets = Tiket::with('event')->get();
        return view('tiket.index', compact('tikets'));
    }

    public function create()
    {
        $events = Event::all();
        return view('tiket.create', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_event' => 'required|exists:event,id',
            'tipe' => 'required|in:premium,regular',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:1',
        ]);

        Tiket::create([
            'id_event' => $request->id_event,
            'tipe' => $request->tipe,
            'harga' => $request->harga,
            'stok' => $request->stok,
        ]);

        return redirect()->route('tiket.index')->with('success', 'Tiket berhasil ditambahkan');
    }

    public function edit(Tiket $tiket)
    {
        $events = Event::all();
        return view('tiket.edit', compact('tiket', 'events'));
    }

    public function update(Request $request, Tiket $tiket)
    {
        $request->validate([
            'id_event' => 'required|exists:event,id',
            'tipe' => 'required|in:premium,regular',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:1',
        ]);

        $tiket->update([
            'id_event' => $request->id_event,
            'tipe' => $request->tipe,
            'harga' => $request->harga,
            'stok' => $request->stok,
        ]);

        return redirect()->route('tiket.index')->with('success', 'Tiket berhasil diupdate');
    }

    public function destroy(Tiket $tiket)
    {
        $tiket->delete();
        return redirect()->route('tiket.index')->with('success', 'Tiket berhasil dihapus');
    }
}
