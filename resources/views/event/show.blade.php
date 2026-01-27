<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Event</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <a href="{{ route('admin.event.index') }}" class="text-blue-500 hover:text-blue-700 mb-4 inline-block">&larr; Kembali</a>
        
        <div class="bg-white shadow-md rounded overflow-hidden">
            @if($event->gambar)
                <img src="{{ asset('storage/' . $event->gambar) }}" alt="{{ $event->judul }}" class="w-full h-96 object-cover">
            @else
                <div class="w-full h-96 bg-gray-300 flex items-center justify-center">
                    <span class="text-gray-500">No Image</span>
                </div>
            @endif

            <div class="p-6">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">{{ $event->judul }}</h1>
                <p class="text-gray-600 mb-4">{{ $event->deskripsi }}</p>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-gray-600"><strong>Kategori:</strong> {{ $event->kategori->nama }}</p>
                        <p class="text-gray-600"><strong>Lokasi:</strong> {{ $event->lokasi ? $event->lokasi->nama . ' (' . $event->lokasi->kota . ')' : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600"><strong>Waktu:</strong> {{ $event->waktu->format('d M Y H:i') }}</p>
                        <p class="text-gray-600"><strong>Penyelenggara:</strong> {{ $event->user->nama }}</p>
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mb-4">Tiket Tersedia</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    @forelse($event->tikets as $tiket)
                        <div class="border border-gray-300 rounded p-4">
                            <h3 class="text-lg font-bold text-gray-800">{{ ucfirst($tiket->tipe) }}</h3>
                            <p class="text-gray-600">Harga: Rp{{ number_format($tiket->harga, 0, ',', '.') }}</p>
                            <p class="text-gray-600">Stok: {{ $tiket->stok }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500">Belum ada tiket</p>
                    @endforelse
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('admin.event.edit', $event->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                    <form action="{{ route('admin.event.destroy', $event->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Yakin ingin menghapus?')">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
