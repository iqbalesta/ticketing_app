<x-layouts.admin title="Edit Event">
    @if ($errors->any())
        <div class="z-50 toast toast-bottom toast-center">
            <ul class="alert alert-error">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

        <script>
            setTimeout(() => {
                document.querySelector('.toast')?.remove()
            }, 5000)
        </script>
    @endif

    <div class="container p-10 mx-auto">
        <div class="shadow-sm card bg-base-100">
            <div class="card-body">
                <h2 class="mb-6 text-2xl card-title">Edit Event</h2>

                <form id="eventForm" class="space-y-4" method="post"
                    action="{{ route('admin.events.update', $event->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Nama Event -->
                    <div class="form-control">
                        <label class="label">
                            <span class="font-semibold label-text">Judul Event</span>
                        </label>
                        <input type="text" name="judul" placeholder="Contoh: Konser Musik Rock"
                            class="w-full input input-bordered" value="{{ $event->judul }}" required />
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-control">
                        <label class="label">
                            <span class="font-semibold label-text">Deskripsi</span>
                        </label>
                        <textarea name="deskripsi" placeholder="Deskripsi lengkap tentang event..."
                            class="w-full h-24 textarea textarea-bordered" required>{{ $event->deskripsi }}</textarea>
                    </div>

                    <!-- Tanggal & Waktu -->
                    <div class="form-control">
                        <label class="label">
                            <span class="font-semibold label-text">Tanggal & Waktu</span>
                        </label>
                        <input type="datetime-local" name="tanggal_waktu" class="w-full input input-bordered"
                            value="{{ $event->tanggal_waktu->format('Y-m-d\TH:i') }}" required />
                    </div>

                    <!-- Lokasi -->
                    <div class="form-control">
                        <label class="label">
                            <span class="font-semibold label-text">Lokasi</span>
                        </label>
                        <select name="lokasi_id" class="w-full select select-bordered">
                            <option value="">-- Pilih Lokasi --</option>
                            @foreach ($lokasis as $lokasi)
                                <option value="{{ $lokasi->id }}"
                                    {{ $event->lokasi_id == $lokasi->id ? 'selected' : '' }}>
                                    {{ $lokasi->nama }} ({{ $lokasi->kota }}, {{ $lokasi->provinsi }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kategori -->
                    <div class="form-control">
                        <label class="label">
                            <span class="font-semibold label-text">Kategori</span>
                        </label>
                        <select name="kategori_id" class="w-full select select-bordered" required>
                            <option value="" disabled>Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $category->id == $event->kategori_id ? 'selected' : '' }}>
                                    {{ $category->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Upload Gambar -->
                    <div class="form-control">
                        <label class="label">
                            <span class="font-semibold label-text">Gambar Event</span>
                        </label>
                        <input type="file" name="gambar" accept="image/*"
                            class="w-full file-input file-input-bordered" />
                        <label class="label">
                            <span class="label-text-alt">Format: JPG, PNG, max 2MB (Kosongkan jika tidak ingin mengubah
                                gambar)</span>
                        </label>
                    </div>

                    <!-- Preview Gambar -->
                    <div id="imagePreview" class="overflow-hidden {{ $event->gambar ? '' : 'hidden' }}">
                        <label class="label">
                            <span class="font-semibold label-text">Preview Gambar</span>
                        </label>
                        <div class="max-w-sm avatar">
                            <div class="w-full rounded-lg">
                                @if ($event->gambar)
                                    <img id="previewImg" src="{{ asset('storage/' . $event->gambar) }}" alt="Preview">
                                @else
                                    <img id="previewImg" src="" alt="Preview">
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="justify-end mt-6 card-actions">
                        <a href="{{ route('admin.events.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('eventForm');
        const fileInput = form.querySelector('input[type="file"]');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');

        // Preview gambar saat dipilih
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-layouts.admin>
