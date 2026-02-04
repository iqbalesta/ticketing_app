<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Lokasi;
use App\Models\Tiket;
use Illuminate\Http\Request;

class TiketController extends Controller
{
    public function index()
    {
        $eventId = request('event_id');
        
        if ($eventId) {
            $tikets = Tiket::where('event_id', $eventId)->get();
        } else {
            $tikets = Tiket::all();
        }
        
        $events = Event::all();
        
        return view('admin.tiket.index', compact('tikets', 'events'));
    }

    public function create(string $eventId)
    {
        $event = Event::findOrFail($eventId);
        $lokasis = Lokasi::all();
        return view('admin.tiket.create', compact('event', 'lokasis'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'event_id' => 'required|exists:events,id',
            'tipe' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'lokasi_id' => 'nullable|exists:lokasis,id',
        ]);

        // Create the ticket
        Tiket::create($validatedData);

        return redirect()->route('admin.events.show', $validatedData['event_id'])->with('success', 'Ticket berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $tiket = Tiket::findOrFail($id);
        $lokasis = Lokasi::all();
        return view('admin.tiket.edit', compact('tiket', 'lokasis'));
    }

    public function update(Request $request, string $id)
    {
        $ticket = Tiket::findOrFail($id);

        $validatedData = $request->validate([
            'tipe' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'lokasi_id' => 'nullable|exists:lokasis,id',
        ]);

        $ticket->update($validatedData);

        return redirect()->route('admin.events.show', $ticket->event_id)->with('success', 'Ticket berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $ticket = Tiket::findOrFail($id);
        $eventId = $ticket->event_id;
        $ticket->delete();

        return redirect()->route('admin.events.show', $eventId)->with('success', 'Ticket berhasil dihapus.');
    }
}
