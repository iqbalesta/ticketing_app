<x-layouts.admin title="Daftar Lokasi">
    <div class="container mx-auto p-10">
        @if (session('success'))
            <div class="toast toast-bottom toast-center z-50">
                <div class="alert alert-success">
                    <span>{{ session('success') }}</span>
                </div>
            </div>

            <script>
                setTimeout(() => {
                    document.querySelector('.toast')?.remove()
                }, 3000)
            </script>
        @endif

        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="card-title text-2xl">Daftar Lokasi</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.lokasi.create') }}" class="btn btn-primary btn-sm">+ Tambah Lokasi</a>
                    </div>
                </div>

                @if ($lokasis->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lokasi</th>
                                    <th>Alamat</th>
                                    <th>Kota</th>
                                    <th>Provinsi</th>
                                    <th>Kapasitas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($lokasis as $index => $lokasi)
                                <tr>
                                    <th>{{ $index + 1 }}</th>
                                    <td>{{ $lokasi->nama }}</td>
                                    <td>{{ $lokasi->alamat }}</td>
                                    <td>{{ $lokasi->kota }}</td>
                                    <td>{{ $lokasi->provinsi }}</td>
                                    <td>{{ $lokasi->kapasitas ? number_format($lokasi->kapasitas, 0, ',', '.') : '-' }}</td>
                                    <td>
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.lokasi.edit', $lokasi->id) }}" class="btn btn-warning btn-xs">Edit</a>
                                            <button class="btn btn-error btn-xs" onclick="openDeleteModal({{ $lokasi->id }}, '{{ $lokasi->nama }}')">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-8">
                                        <div class="text-gray-500">Belum ada lokasi</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg mb-6">Belum ada lokasi</p>
                        <a href="{{ route('admin.lokasi.create') }}" class="btn btn-primary">Tambah Lokasi Pertama</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Delete Lokasi -->
    <dialog id="delete_lokasi_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Hapus Lokasi</h3>
            <p class="py-4">Apakah Anda yakin ingin menghapus lokasi <strong id="delete_lokasi_name"></strong>?</p>
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn btn-ghost">Batal</button>
                </form>
                <form id="deleteLokasiForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-error">Hapus</button>
                </form>
            </div>
        </div>
    </dialog>

    <script>
        function openDeleteModal(lokasiId, namaLokasi) {
            document.getElementById('delete_lokasi_name').textContent = namaLokasi;
            document.getElementById('deleteLokasiForm').action = `/lokasi/${lokasiId}`;
            document.getElementById('delete_lokasi_modal').showModal();
        }
    </script>
</x-layouts.admin>
