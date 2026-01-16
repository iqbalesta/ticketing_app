<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <a href="{{ route('admin.order.index') }}" class="text-blue-500 hover:text-blue-700 mb-4 inline-block">&larr; Kembali</a>
        
        <div class="bg-white shadow-md rounded p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Order #{{ $order->id }}</h1>

            <div class="grid grid-cols-2 gap-4 mb-6 pb-6 border-b">
                <div>
                    <p class="text-gray-600"><strong>User:</strong> {{ $order->user->nama }}</p>
                    <p class="text-gray-600"><strong>Email:</strong> {{ $order->user->email }}</p>
                </div>
                <div>
                    <p class="text-gray-600"><strong>Event:</strong> {{ $order->event->judul }}</p>
                    <p class="text-gray-600"><strong>Tanggal Order:</strong> {{ $order->order_date->format('d M Y H:i') }}</p>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-4">Detail Tiket</h2>
            <div class="overflow-x-auto mb-6">
                <table class="w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-gray-700 font-bold">Tipe Tiket</th>
                            <th class="px-6 py-3 text-left text-gray-700 font-bold">Harga Satuan</th>
                            <th class="px-6 py-3 text-left text-gray-700 font-bold">Jumlah</th>
                            <th class="px-6 py-3 text-left text-gray-700 font-bold">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->detailOrders as $detail)
                            <tr class="border-t border-gray-300">
                                <td class="px-6 py-4">{{ ucfirst($detail->tiket->tipe) }}</td>
                                <td class="px-6 py-4">Rp{{ number_format($detail->tiket->harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">{{ $detail->jumlah }}</td>
                                <td class="px-6 py-4">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr class="border-t border-gray-300 bg-gray-50">
                            <td colspan="3" class="px-6 py-4 text-right font-bold">Total:</td>
                            <td class="px-6 py-4 font-bold">Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.order.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</body>
</html>
