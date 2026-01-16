<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Kategori;
use App\Models\Order;
use App\Models\Tiket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $totalEvents = Event::count();
        $totalCategories = Kategori::count();
        $totalOrders = Order::count();
        $totalTickets = Tiket::count();

        return view('admin.dashboard', compact('totalEvents', 'totalCategories', 'totalOrders', 'totalTickets'));
    }
}
