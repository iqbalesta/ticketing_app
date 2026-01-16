<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tiket</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">Daftar Tiket</h1>
            <a href="{{ route('admin.tiket.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Tambah Tiket
            </a>
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
                        <th class="px-6 py-3 text-left text-gray-700 font-bold">Event</th>
                        <th class="px-6 py-3 text-left text-gray-700 font-bold">Tipe</th>
                        <th class="px-6 py-3 text-left text-gray-700 font-bold">Harga</th>
                        <th class="px-6 py-3 text-left text-gray-700 font-bold">Stok</th>
                        <th class="px-6 py-3 text-left text-gray-700 font-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tikets as $tiket)
                        <tr class="border-t border-gray-300 hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $tiket->id }}</td>
                            <td class="px-6 py-4">{{ $tiket->event->judul }}</td>
                            <td class="px-6 py-4">{{ ucfirst($tiket->tipe) }}</td>
                            <td class="px-6 py-4">Rp{{ number_format($tiket->harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">{{ $tiket->stok }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.tiket.edit', $tiket->id) }}" class="text-blue-500 hover:text-blue-700 mr-3">Edit</a>
                                <form action="{{ route('admin.tiket.destroy', $tiket->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada tiket</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
