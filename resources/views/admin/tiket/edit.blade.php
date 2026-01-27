<x-layouts.admin title="Edit Tiket">
    <div class="container mx-auto p-10">
        <div class="max-w-2xl mx-auto">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="card-title text-2xl">Edit Tiket</h2>
                        <a href="{{ route('admin.events.show', $tiket->event->id) }}" class="btn btn-ghost btn-sm">← Kembali</a>
                    </div>

                    <!-- Event Info -->
                    <div class="alert alert-info mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <h3 class="font-bold">{{ $tiket->event->judul }}</h3>
                            <div class="text-sm">{{ $tiket->event->lokasi ? $tiket->event->lokasi->nama : '-' }} • {{ $tiket->event->tanggal_waktu->format('d M Y H:i') }}</div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('admin.tickets.update', $tiket->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <!-- Tipe Tiket -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Tipe Tiket <span class="text-error">*</span></span>
                            </label>
                            <input 
                                type="text" 
                                name="tipe" 
                                class="input input-bordered w-full @error('tipe') input-error @enderror" 
                                placeholder="Contoh: Reguler, Premium, VIP, VVIP"
                                value="{{ old('tipe', $tiket->tipe) }}"
                                required>
                            @error('tipe')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- Harga -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Harga (Rp) <span class="text-error">*</span></span>
                            </label>
                            <input 
                                type="number" 
                                name="harga" 
                                class="input input-bordered w-full @error('harga') input-error @enderror" 
                                placeholder="Masukkan harga tiket"
                                value="{{ old('harga', $tiket->harga) }}"
                                min="0"
                                step="1000"
                                required>
                            <label class="label">
                                <span class="label-text-alt">Harga saat ini: Rp{{ number_format($tiket->harga, 0, ',', '.') }}</span>
                            </label>
                            @error('harga')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- Stok -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Stok <span class="text-error">*</span></span>
                            </label>
                            <input 
                                type="number" 
                                name="stok" 
                                class="input input-bordered w-full @error('stok') input-error @enderror" 
                                placeholder="Masukkan jumlah stok tiket"
                                value="{{ old('stok', $tiket->stok) }}"
                                min="0"
                                required>
                            <label class="label">
                                <span class="label-text-alt">Stok saat ini: {{ $tiket->stok }}</span>
                            </label>
                            @error('stok')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- Lokasi -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Lokasi</span>
                            </label>
                            <select 
                                name="lokasi_id" 
                                class="select select-bordered w-full @error('lokasi_id') select-error @enderror">
                                <option value="">-- Pilih Lokasi --</option>
                                @foreach ($lokasis as $lokasi)
                                    <option value="{{ $lokasi->id }}" {{ old('lokasi_id', $tiket->lokasi_id) == $lokasi->id ? 'selected' : '' }}>
                                        {{ $lokasi->nama }} ({{ $lokasi->kota }}, {{ $lokasi->provinsi }})
                                    </option>
                                @endforeach
                            </select>
                            <label class="label">
                                <span class="label-text-alt">Pilih lokasi penyelenggaraan tiket ini</span>
                            </label>
                            @error('lokasi_id')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="card-actions justify-between pt-6">
                            <a href="{{ route('admin.events.show', $tiket->event->id) }}" class="btn btn-ghost">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="alert alert-warning mt-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M7.08 6.47A9 9 0 1 0 20.92 17.08M7.08 6.47l5.08 5.08m0 0l5.08 5.08M7.08 6.47l5.08-5.08m0 0l5.08-5.08"></path></svg>
                <div>
                    <h3 class="font-bold">Info Penting</h3>
                    <div class="text-sm">
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>Perubahan harga akan berlaku untuk pembelian tiket baru</li>
                            <li>Mengurangi stok tidak akan membatalkan pesanan yang sudah ada</li>
                            <li>Pastikan stok mencukupi untuk pesanan yang belum dikonfirmasi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
