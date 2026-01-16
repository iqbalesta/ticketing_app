<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tiket</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-2xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Tiket</h1>

        <div class="bg-white shadow-md rounded p-6">
            <form action="{{ route('admin.tiket.update', $tiket->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="id_event" class="block text-gray-700 font-bold mb-2">Event</label>
                    <select 
                        id="id_event" 
                        name="id_event" 
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        required
                    >
                        <option value="">Pilih Event</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ $tiket->id_event == $event->id ? 'selected' : '' }}>{{ $event->judul }}</option>
                        @endforeach
                    </select>
                    @error('id_event')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tipe" class="block text-gray-700 font-bold mb-2">Tipe Tiket</label>
                    <select 
                        id="tipe" 
                        name="tipe" 
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        required
                    >
                        <option value="premium" {{ $tiket->tipe == 'premium' ? 'selected' : '' }}>Premium</option>
                        <option value="regular" {{ $tiket->tipe == 'regular' ? 'selected' : '' }}>Regular</option>
                    </select>
                    @error('tipe')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="harga" class="block text-gray-700 font-bold mb-2">Harga</label>
                    <input 
                        type="number" 
                        id="harga" 
                        name="harga" 
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        value="{{ old('harga', $tiket->harga) }}"
                        step="0.01"
                        required
                    >
                    @error('harga')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="stok" class="block text-gray-700 font-bold mb-2">Stok</label>
                    <input 
                        type="number" 
                        id="stok" 
                        name="stok" 
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        value="{{ old('stok', $tiket->stok) }}"
                        required
                    >
                    @error('stok')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Update
                    </button>
                    <a href="{{ route('admin.tiket.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
