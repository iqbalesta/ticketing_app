<x-layouts.admin :title="'Dashboard Admin'">
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Admin</h1>
            <p class="text-gray-600 mt-2">Selamat datang di panel administrasi Ticketing App</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Event Card -->
            <div class="card bg-base-100 shadow-lg">
                <div class="card-body">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="card-title text-lg">Total Event</h2>
                            <p class="text-4xl font-bold text-primary mt-4">{{ $totalEvents ?? 0 }}</p>
                        </div>
                        <div class="text-primary text-4xl opacity-20">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3a2 2 0 0 0 0-4V7a2 2 0 0 1 2-2" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Kategori Card -->
            <div class="card bg-base-100 shadow-lg">
                <div class="card-body">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="card-title text-lg">Total Kategori</h2>
                            <p class="text-4xl font-bold text-success mt-4">{{ $totalCategories ?? 0 }}</p>
                        </div>
                        <div class="text-success text-4xl opacity-20">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h6v6H4zm10 0h6v6h-6zM4 14h6v6H4zm10 3a3 3 0 1 0 6 0a3 3 0 1 0-6 0" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Transaksi Card -->
            <div class="card bg-base-100 shadow-lg">
                <div class="card-body">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="card-title text-lg">Total Transaksi</h2>
                            <p class="text-4xl font-bold text-warning mt-4">{{ $totalOrders ?? 0 }}</p>
                        </div>
                        <div class="text-warning text-4xl opacity-20">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3a2 2 0 0 0 0-4V7a2 2 0 0 1 2-2" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="card bg-base-100 shadow-lg">
            <div class="card-body">
                <h2 class="card-title mb-4">Pesanan Terbaru</h2>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>ID Order</th>
                                <th>User</th>
                                <th>Event</th>
                                <th>Tanggal Order</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(\App\Models\Order::with(['user', 'event'])->latest()->limit(10)->get() as $order)
                                <tr>
                                    <td><strong>#{{ $order->id }}</strong></td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->event->judul }}</td>
                                    <td>{{ $order->order_date->format('d M Y H:i') }}</td>
                                    <td><strong>Rp{{ number_format($order->total_harga, 0, ',', '.') }}</strong></td>
                                    <td>
                                        <a href="{{ route('admin.order.show', $order->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">Belum ada order</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
