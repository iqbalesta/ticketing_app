<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|integer',
            'items' => 'required|array',
            'items.*.tiket_id' => 'required|integer',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        // simulasi create order
        return response()->json([
            'message' => 'Order berhasil dibuat',
            'user_id' => $request->user()->id,
            'data' => $request->all(),
        ], 201);
    }
}
