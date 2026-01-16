<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-2xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Event</h1>

        <div class="bg-white shadow-md rounded p-6">
            <form action="{{ route('admin.event.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="id_kategori" class="block text-gray-700 font-bold mb-2">Kategori</label>
                    <select 
                        id="id_kategori" 
                        name="id_kategori" 
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        required
                    >
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ $event->id_kategori == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                        @endforeach
                    </select>
                    @error('id_kategori')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="judul" class="block text-gray-700 font-bold mb-2">Judul Event</label>
                    <input 
                        type="text" 
                        id="judul" 
                        name="judul" 
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        value="{{ old('judul', $event->judul) }}"
                        required
                    >
                    @error('judul')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="deskripsi" class="block text-gray-700 font-bold mb-2">Deskripsi</label>
                    <textarea 
                        id="deskripsi" 
                        name="deskripsi" 
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        rows="4"
                        required
                    >{{ old('deskripsi', $event->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="lokasi" class="block text-gray-700 font-bold mb-2">Lokasi</label>
                    <input 
                        type="text" 
                        id="lokasi" 
                        name="lokasi" 
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        value="{{ old('lokasi', $event->lokasi) }}"
                        required
                    >
                    @error('lokasi')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="waktu" class="block text-gray-700 font-bold mb-2">Waktu</label>
                    <input 
                        type="datetime-local" 
                        id="waktu" 
                        name="waktu" 
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        value="{{ old('waktu', $event->waktu->format('Y-m-d\TH:i')) }}"
                        required
                    >
                    @error('waktu')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="gambar" class="block text-gray-700 font-bold mb-2">Gambar</label>
                    @if($event->gambar)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $event->gambar) }}" alt="{{ $event->judul }}" class="max-w-xs h-40 object-cover">
                        </div>
                    @endif
                    <input 
                        type="file" 
                        id="gambar" 
                        name="gambar" 
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        accept="image/*"
                    >
                    @error('gambar')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Update
                    </button>
                    <a href="{{ route('admin.event.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
