<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">Daftar Order</h1>
        </div>

        @if($message = Session::get('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ $message }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-gray-700 font-bold">ID</th>
                        <th class="px-6 py-3 text-left text-gray-700 font-bold">User</th>
                        <th class="px-6 py-3 text-left text-gray-700 font-bold">Event</th>
                        <th class="px-6 py-3 text-left text-gray-700 font-bold">Tanggal</th>
                        <th class="px-6 py-3 text-left text-gray-700 font-bold">Total Harga</th>
                        <th class="px-6 py-3 text-left text-gray-700 font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="border-t border-gray-300 hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $order->id }}</td>
                            <td class="px-6 py-4">{{ $order->user->nama }}</td>
                            <td class="px-6 py-4">{{ $order->event->judul }}</td>
                            <td class="px-6 py-4">{{ $order->order_date->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.order.show', $order->id) }}" class="text-blue-500 hover:text-blue-700">Lihat Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada order</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
