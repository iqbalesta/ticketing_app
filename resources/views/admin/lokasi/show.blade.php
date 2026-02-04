<x-layouts.admin title="Detail Lokasi">
    <div class="container mx-auto p-10">
        <div class="max-w-2xl mx-auto">
            <div class="card bg-base-100 shadow-sm">
                <div class="card-body">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="card-title text-2xl">Detail Lokasi</h2>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.lokasi.edit', $lokasi->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            <a href="{{ route('admin.lokasi.index') }}" class="btn btn-ghost btn-sm">← Kembali</a>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Nama Lokasi -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Nama Lokasi</span>
                            </label>
                            <input type="text" class="input input-bordered w-full" value="{{ $lokasi->nama }}" disabled />
                        </div>

                        <!-- Alamat -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Alamat</span>
                            </label>
                            <textarea class="textarea textarea-bordered h-24 w-full" disabled>{{ $lokasi->alamat }}</textarea>
                        </div>

                        <!-- Kota dan Provinsi -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Kota</span>
                                </label>
                                <input type="text" class="input input-bordered w-full" value="{{ $lokasi->kota }}" disabled />
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Provinsi</span>
                                </label>
                                <input type="text" class="input input-bordered w-full" value="{{ $lokasi->provinsi }}" disabled />
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        @if ($lokasi->deskripsi)
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Deskripsi</span>
                            </label>
                            <textarea class="textarea textarea-bordered h-24 w-full" disabled>{{ $lokasi->deskripsi }}</textarea>
                        </div>
                        @endif

                        <!-- Kapasitas -->
                        @if ($lokasi->kapasitas)
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Kapasitas</span>
                            </label>
                            <input type="text" class="input input-bordered w-full" value="{{ number_format($lokasi->kapasitas, 0, ',', '.') }} orang" disabled />
                        </div>
                        @endif

                        <!-- Jumlah Tiket -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Jumlah Tiket</span>
                            </label>
                            <input type="text" class="input input-bordered w-full" value="{{ $lokasi->tikets->count() }} tiket" disabled />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tiket yang menggunakan lokasi ini -->
            @if ($lokasi->tikets->count() > 0)
            <div class="card bg-base-100 shadow-sm mt-6">
                <div class="card-body">
                    <h3 class="card-title text-xl mb-4">Tiket yang Menggunakan Lokasi Ini</h3>
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Event</th>
                                    <th>Tipe Tiket</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lokasi->tikets as $index => $tiket)
                                <tr>
                                    <th>{{ $index + 1 }}</th>
                                    <td>{{ $tiket->event->judul }}</td>
                                    <td>{{ ucfirst($tiket->tipe) }}</td>
                                    <td>Rp{{ number_format($tiket->harga, 0, ',', '.') }}</td>
                                    <td>{{ $tiket->stok }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
