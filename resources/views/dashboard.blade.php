<x-layouts.admin :title="'Dashboard'">
    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="alert alert-info">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>Selamat datang di Ticketing App Admin Dashboard!</span>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Events -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-lg">Total Event</h2>
                    <p class="text-3xl font-bold text-primary">
                        {{ \App\Models\Event::count() }}
                    </p>
                </div>
            </div>

            <!-- Total Categories -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-lg">Total Kategori</h2>
                    <p class="text-3xl font-bold text-success">
                        {{ \App\Models\Kategori::count() }}
                    </p>
                </div>
            </div>

            <!-- Total Tickets -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-lg">Total Tiket</h2>
                    <p class="text-3xl font-bold text-warning">
                        {{ \App\Models\Tiket::count() }}
                    </p>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-lg">Total Order</h2>
                    <p class="text-3xl font-bold text-info">
                        {{ \App\Models\Order::count() }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title">Pesanan Terbaru</h2>
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID Order</th>
                                <th>User</th>
                                <th>Event</th>
                                <th>Tanggal</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Order::with(['user', 'event'])->latest()->limit(5)->get() as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->event->judul }}</td>
                                    <td>{{ $order->order_date->format('d M Y') }}</td>
                                    <td>Rp{{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada order</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

