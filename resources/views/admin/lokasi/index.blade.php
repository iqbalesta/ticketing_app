<x-layouts.admin title="Daftar Lokasi">
    <div class="container p-10 mx-auto">
        @if (session('success'))
            <div class="z-50 toast toast-bottom toast-center">
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

        <div class="shadow-sm card bg-base-100">
            <div class="card-body">
                {{-- Header + Toolbar --}}
                <div class="flex flex-col gap-4 mb-4 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-2xl card-title">Daftar Lokasi</h2>

                    <div class="flex flex-wrap items-center gap-3">
                        {{-- Toolbar: per page + sorting --}}
                        <form method="GET" action="{{ route('admin.lokasi.index') }}" id="lokasiFilterForm"
                            class="flex flex-wrap items-center gap-3 px-3 py-2 border rounded-box bg-base-100 border-base-200">

                            {{-- Per page --}}
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-medium tracking-wide text-gray-500 uppercase">Tampil</span>
                                <select name="per_page" id="per_page"
                                    class="select select-bordered select-xs md:select-sm"
                                    onchange="document.getElementById('lokasiFilterForm').submit()">
                                    @foreach ($allowedPerPage as $size)
                                        <option value="{{ $size }}"
                                            {{ (int) $perPage === $size ? 'selected' : '' }}>
                                            {{ $size }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-xs text-gray-500">/ halaman</span>
                            </div>

                            <div class="hidden w-px h-5 bg-base-200 md:block"></div>

                            {{-- Sorting --}}
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-medium tracking-wide text-gray-500 uppercase">Urutkan</span>
                                <select name="sort" id="sort"
                                    class="select select-bordered select-xs md:select-sm"
                                    onchange="document.getElementById('lokasiFilterForm').submit()">
                                    <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>
                                        Nama A-Z
                                    </option>
                                    <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>
                                        Nama Z-A
                                    </option>
                                    <option value="city_asc" {{ $sort === 'city_asc' ? 'selected' : '' }}>
                                        Kota A-Z
                                    </option>
                                    <option value="city_desc" {{ $sort === 'city_desc' ? 'selected' : '' }}>
                                        Kota Z-A
                                    </option>
                                    <option value="capacity_desc" {{ $sort === 'capacity_desc' ? 'selected' : '' }}>
                                        Kapasitas tertinggi
                                    </option>
                                    <option value="capacity_asc" {{ $sort === 'capacity_asc' ? 'selected' : '' }}>
                                        Kapasitas terendah
                                    </option>
                                </select>
                            </div>
                        </form>

                        <a href="{{ route('admin.lokasi.create') }}" class="btn btn-primary btn-sm md:btn-md">
                            + Tambah Lokasi
                        </a>
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
                                        <th>{{ $lokasis->firstItem() + $index }}</th>
                                        <td>{{ $lokasi->nama }}</td>
                                        <td>{{ $lokasi->alamat }}</td>
                                        <td>{{ $lokasi->kota }}</td>
                                        <td>{{ $lokasi->provinsi }}</td>
                                        <td>{{ $lokasi->kapasitas ? number_format($lokasi->kapasitas, 0, ',', '.') : '-' }}
                                        </td>
                                        <td>
                                            <div class="flex gap-2">
                                                <a href="{{ route('admin.lokasi.edit', $lokasi->id) }}"
                                                    class="btn btn-warning btn-xs">Edit</a>
                                                <button class="btn btn-error btn-xs"
                                                    onclick="openDeleteModal({{ $lokasi->id }}, '{{ $lokasi->nama }}')">
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-8 text-center">
                                            <div class="text-gray-500">Belum ada lokasi</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($lokasis->hasPages())
                        <div
                            class="flex flex-col items-start justify-between gap-2 mt-4 text-sm text-gray-500 md:flex-row md:items-center">
                            <div>
                                Menampilkan
                                <span class="font-semibold">{{ $lokasis->firstItem() }}</span>
                                –
                                <span class="font-semibold">{{ $lokasis->lastItem() }}</span>
                                dari
                                <span class="font-semibold">{{ $lokasis->total() }}</span>
                                lokasi
                            </div>
                            <div class="flex justify-end w-full md:w-auto">
                                {{ $lokasis->links() }}
                            </div>
                        </div>
                    @endif
                @else
                    <div class="flex flex-col items-center justify-center py-12">
                        <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <p class="mb-6 text-lg text-gray-500">Belum ada lokasi</p>
                        <a href="{{ route('admin.lokasi.create') }}" class="btn btn-primary">Tambah Lokasi Pertama</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Delete Lokasi -->
    <dialog id="delete_lokasi_modal" class="modal">
        <div class="modal-box">
            <h3 class="mb-4 text-lg font-bold">Hapus Lokasi</h3>
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
            // Sesuaikan dengan prefix admin
            document.getElementById('deleteLokasiForm').action = `/admin/lokasi/${lokasiId}`;
            document.getElementById('delete_lokasi_modal').showModal();
        }
    </script>
</x-layouts.admin>
