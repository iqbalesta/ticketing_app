<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Event</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">Daftar Event</h1>
            <a href="{{ route('admin.event.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Tambah Event
            </a>
        </div>

        @if($message = Session::get('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ $message }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($events as $event)
                <div class="bg-white shadow-md rounded overflow-hidden hover:shadow-lg transition">
                    @if($event->gambar)
                        <img src="{{ asset('storage/' . $event->gambar) }}" alt="{{ $event->judul }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-300 flex items-center justify-center">
                            <span class="text-gray-500">No Image</span>
                        </div>
                    @endif
                    
                    <div class="p-4">
                        <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $event->judul }}</h2>
                        <p class="text-sm text-gray-600 mb-2">{{ substr($event->deskripsi, 0, 100) }}...</p>
                        <p class="text-sm text-gray-500 mb-1"><strong>Lokasi:</strong> {{ $event->lokasi }}</p>
                        <p class="text-sm text-gray-500 mb-1"><strong>Kategori:</strong> {{ $event->kategori->nama }}</p>
                        <p class="text-sm text-gray-500 mb-3"><strong>Waktu:</strong> {{ $event->waktu }}</p>
                        
                        <div class="flex gap-2">
                            <a href="{{ route('admin.event.show', $event->id) }}" class="text-blue-500 hover:text-blue-700 text-sm">Lihat Detail</a>
                            <a href="{{ route('admin.event.edit', $event->id) }}" class="text-blue-500 hover:text-blue-700 text-sm">Edit</a>
                            <form action="{{ route('admin.event.destroy', $event->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center text-gray-500">Belum ada event</div>
            @endforelse
        </div>
    </div>
</body>
</html>
