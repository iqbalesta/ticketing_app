<x-layouts.admin title="Tambah Lokasi">
    <div class="container mx-auto p-10">
        <div class="max-w-2xl mx-auto">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="card-title text-2xl">Tambah Lokasi Baru</h2>
                        <a href="{{ route('admin.lokasi.index') }}" class="btn btn-ghost btn-sm">← Kembali</a>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('admin.lokasi.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- Nama Lokasi -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Nama Lokasi <span class="text-error">*</span></span>
                            </label>
                            <input 
                                type="text" 
                                name="nama" 
                                class="input input-bordered w-full @error('nama') input-error @enderror" 
                                placeholder="Contoh: GBK, Istora, Carnaval"
                                value="{{ old('nama') }}"
                                required>
                            @error('nama')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Alamat <span class="text-error">*</span></span>
                            </label>
                            <textarea 
                                name="alamat" 
                                class="textarea textarea-bordered w-full h-24 @error('alamat') textarea-error @enderror"
                                placeholder="Masukkan alamat lengkap"
                                required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- Kota dan Provinsi -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Kota <span class="text-error">*</span></span>
                                </label>
                                <input 
                                    type="text" 
                                    name="kota" 
                                    class="input input-bordered w-full @error('kota') input-error @enderror" 
                                    placeholder="Contoh: Jakarta"
                                    value="{{ old('kota') }}"
                                    required>
                                @error('kota')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Provinsi <span class="text-error">*</span></span>
                                </label>
                                <input 
                                    type="text" 
                                    name="provinsi" 
                                    class="input input-bordered w-full @error('provinsi') input-error @enderror" 
                                    placeholder="Contoh: DKI Jakarta"
                                    value="{{ old('provinsi') }}"
                                    required>
                                @error('provinsi')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Deskripsi</span>
                            </label>
                            <textarea 
                                name="deskripsi" 
                                class="textarea textarea-bordered w-full h-24"
                                placeholder="Masukkan deskripsi lokasi (opsional)">{{ old('deskripsi') }}</textarea>
                        </div>

                        <!-- Kapasitas -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Kapasitas</span>
                            </label>
                            <input 
                                type="number" 
                                name="kapasitas" 
                                class="input input-bordered w-full @error('kapasitas') input-error @enderror" 
                                placeholder="Masukkan kapasitas (opsional)"
                                value="{{ old('kapasitas') }}"
                                min="0"
                                step="1">
                            <label class="label">
                                <span class="label-text-alt">Jumlah kapasitas penonton</span>
                            </label>
                            @error('kapasitas')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="card-actions justify-between pt-6">
                            <a href="{{ route('admin.lokasi.index') }}" class="btn btn-ghost">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Lokasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
