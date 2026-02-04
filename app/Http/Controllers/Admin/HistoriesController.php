<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class HistoriesController extends Controller
{
    public function index(Request $request)
    {
        $allowedPerPage = [5, 15, 25];
        $perPage = (int) $request->input('per_page', 5);
        if (! in_array($perPage, $allowedPerPage)) {
            $perPage = 5;
        }

        // opsi sorting: tanggal & total
        $allowedSorts = ['date_desc', 'date_asc', 'total_desc', 'total_asc'];
        $sort = $request->input('sort', 'date_desc');
        if (! in_array($sort, $allowedSorts)) {
            $sort = 'date_desc';
        }

        $query = Order::with(['user', 'event']);

        switch ($sort) {
            case 'date_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'total_asc':
                $query->orderBy('total_harga', 'asc');
                break;
            case 'total_desc':
                $query->orderBy('total_harga', 'desc');
                break;
            default: // date_desc
                $query->orderBy('created_at', 'desc');
                break;
        }

        $histories = $query
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.history.index', [
            'histories'      => $histories,
            'perPage'        => $perPage,
            'allowedPerPage' => $allowedPerPage,
            'sort'           => $sort,
        ]);
    }

    public function show(string $history)
    {
        $order = Order::findOrFail($history);
        return view('admin.history.show', compact('order'));
    }
}
