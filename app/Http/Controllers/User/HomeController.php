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

        $eventsQuery = Event::with(['kategori', 'lokasi'])
            ->withMin('tikets', 'harga'); // menghasilkan field tikets_min_harga

        // Filter by kategori
        if ($request->filled('kategori')) {
            $eventsQuery->where('kategori_id', $request->kategori);
        }

        // Search by keyword
        if ($request->filled('q')) {
            $keyword = $request->q;
            $eventsQuery->where(function ($query) use ($keyword) {
                $query->where('judul', 'like', "%{$keyword}%")
                    ->orWhere('deskripsi', 'like', "%{$keyword}%");
                // kalau mau search lokasi relasi, bisa ditambah whereHas('lokasi', ...)
            });
        }

        // Sorting
        $allowedSorts = ['date_asc', 'date_desc', 'price_asc', 'price_desc'];
        $sort = $request->input('sort', 'date_asc');
        if (! in_array($sort, $allowedSorts)) {
            $sort = 'date_asc';
        }

        switch ($sort) {
            case 'date_desc':
                $eventsQuery->orderBy('tanggal_waktu', 'desc');
                break;
            case 'price_asc':
                $eventsQuery->orderBy('tikets_min_harga', 'asc');
                break;
            case 'price_desc':
                $eventsQuery->orderBy('tikets_min_harga', 'desc');
                break;
            default: // date_asc
                $eventsQuery->orderBy('tanggal_waktu', 'asc');
                break;
        }

        // Pagination: pilihan 5 / 25 / 50 per halaman
        $allowedPerPage = [5, 25, 50];
        $perPage = (int) $request->input('per_page', 25);
        if (! in_array($perPage, $allowedPerPage)) {
            $perPage = 25;
        }

        $events = $eventsQuery
            ->paginate($perPage)
            ->withQueryString();

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $events,
                'meta' => [
                    'per_page'   => $perPage,
                    'sort'       => $sort,
                    'kategori'   => $request->input('kategori'),
                    'q'          => $request->input('q'),
                ],
            ]);
        }

        return view('home', [
            'events'         => $events,
            'categories'     => $categories,
            'sort'           => $sort,
            'perPage'        => $perPage,
            'allowedPerPage' => $allowedPerPage,
        ]);
    }
}
