<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-2xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Tambah Kategori</h1>

        <div class="bg-white shadow-md rounded p-6">
            <form action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="nama" class="block text-gray-700 font-bold mb-2">Nama Kategori</label>
                    <input 
                        type="text" 
                        id="nama" 
                        name="nama" 
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        value="{{ old('nama') }}"
                        required
                    >
                    @error('nama')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Simpan
                    </button>
                    <a href="{{ route('admin.kategori.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
