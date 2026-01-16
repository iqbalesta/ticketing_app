<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Event;
use App\Models\DetailOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'event', 'detailOrders'])->get();
        return view('order.index', compact('orders'));
    }

    public function create()
    {
        $events = Event::with('tikets')->get();
        return view('order.create', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_event' => 'required|exists:event,id',
            'tikets' => 'required|array',
            'tikets.*.id_tiket' => 'required|exists:tiket,id',
            'tikets.*.jumlah' => 'required|integer|min:1',
        ]);

        $event = Event::find($request->id_event);
        $total_price = 0;

        $order = Order::create([
            'id_user' => auth()->id() ?? 1,
            'id_event' => $request->id_event,
            'order_date' => now(),
            'total_price' => 0,
        ]);

        foreach ($request->tikets as $tiketData) {
            $tiket = \App\Models\Tiket::find($tiketData['id_tiket']);
            $subtotal = $tiket->harga * $tiketData['jumlah'];
            $total_price += $subtotal;

            DetailOrder::create([
                'id_order' => $order->id,
                'id_tiket' => $tiketData['id_tiket'],
                'jumlah' => $tiketData['jumlah'],
                'subtotal' => $subtotal,
            ]);
        }

        $order->update(['total_price' => $total_price]);

        return redirect()->route('order.show', $order->id)->with('success', 'Order berhasil dibuat');
    }

    public function show(Order $order)
    {
        $order->load(['user', 'event', 'detailOrders.tiket']);
        return view('order.show', compact('order'));
    }
}
