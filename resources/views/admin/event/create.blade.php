<x-layouts.admin title="Tambah Event Baru">
    @if ($errors->any())
        <div class="toast toast-bottom toast-center z-50">
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

    <div class="container mx-auto p-10">
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-6">Tambah Event Baru</h2>

                <form id="eventForm" class="space-y-4" method="post" action="{{ route('admin.events.store') }}" enctype="multipart/form-data">
                    @csrf
                    <!-- Nama Event -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Judul Event</span>
                        </label>
                        <input
                            type="text"
                            name="judul"
                            placeholder="Contoh: Konser Musik Rock"
                            class="input input-bordered w-full"
                            required />
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Deskripsi</span>
                        </label>
                        <textarea
                            name="deskripsi"
                            placeholder="Deskripsi lengkap tentang event..."
                            class="textarea textarea-bordered h-24 w-full"
                            required></textarea>
                    </div>

                    <!-- Tanggal & Waktu -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Tanggal & Waktu</span>
                        </label>
                        <input
                            type="datetime-local"
                            name="tanggal_waktu"
                            class="input input-bordered w-full"
                            required />
                    </div>

                    <!-- Lokasi -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Lokasi</span>
                        </label>
                        <input
                            type="text"
                            name="lokasi"
                            placeholder="Contoh: Stadion Utama"
                            class="input input-bordered w-full"
                            required />
                    </div>

                    <!-- Kategori -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Kategori</span>
                        </label>
                        <select name="kategori_id" class="select select-bordered w-full" required>
                            <option value="" disabled selected>Pilih Kategori</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Upload Gambar -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Gambar Event</span>
                        </label>
                        <input
                            type="file"
                            name="gambar"
                            accept="image/*"
                            class="file-input file-input-bordered w-full"
                            required />
                        <label class="label">
                            <span class="label-text-alt">Format: JPG, PNG, max 2MB</span>
                        </label>
                    </div>

                    <!-- Preview Gambar -->
                    <div id="imagePreview" class="hidden overflow-hidden">
                        <label class="label">
                            <span class="label-text font-semibold">Preview Gambar</span>
                        </label>
                        <div class="avatar max-w-sm">
                            <div class="w-full rounded-lg">
                                <img id="previewImg" src="" alt="Preview">
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="card-actions justify-end mt-6">
                        <a href="{{ route('admin.events.index') }}" class="btn btn-ghost">Batal</a>
                        <button type="reset" class="btn btn-ghost">Reset</button>
                        <button type="submit" class="btn btn-primary">Simpan Event</button>
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

        // Handle reset
        form.addEventListener('reset', function() {
            imagePreview.classList.add('hidden');
        });
    </script>
</x-layouts.admin>
