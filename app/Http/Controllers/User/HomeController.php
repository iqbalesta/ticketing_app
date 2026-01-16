<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Kategori;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Kategori::all();

        $eventsQuery = Event::withMin('tikets', 'harga')
            ->orderBy('tanggal_waktu', 'asc');

        // Filter by kategori
        if ($request->has('kategori') && $request->kategori) {
            $eventsQuery->where('kategori_id', $request->kategori);
        }

        // Search by keyword
        if ($request->has('q') && $request->q) {
            $keyword = $request->q;
            $eventsQuery->where(function ($query) use ($keyword) {
                $query->where('judul', 'like', "%{$keyword}%")
                    ->orWhere('deskripsi', 'like', "%{$keyword}%")
                    ->orWhere('lokasi', 'like', "%{$keyword}%");
            });
        }

        $events = $eventsQuery->get();

        return view('home', compact('events', 'categories'));
    }
}
