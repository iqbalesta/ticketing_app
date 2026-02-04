<x-layouts.admin title="Manajemen Event">
    @if (session('success'))
        <div class="toast toast-bottom toast-center">
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

    <div class="container p-10 mx-auto">
        {{-- Header + Toolbar --}}
        <div class="flex flex-col gap-4 mb-4 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-3xl font-semibold">Manajemen Event</h1>

            <div class="flex flex-wrap items-center gap-3">
                {{-- Toolbar: per page + sorting --}}
                <form method="GET" action="{{ route('admin.events.index') }}" id="perPageForm"
                    class="flex flex-wrap items-center gap-3 px-3 py-2 border rounded-box bg-base-100 border-base-200">

                    {{-- Per page --}}
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-medium tracking-wide text-gray-500 uppercase">Tampil</span>
                        <select name="per_page" id="per_page" class="select select-bordered select-xs md:select-sm"
                            onchange="document.getElementById('perPageForm').submit()">
                            @foreach ($allowedPerPage as $size)
                                <option value="{{ $size }}" {{ (int) $perPage === $size ? 'selected' : '' }}>
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
                        <select name="sort" id="sort" class="select select-bordered select-xs md:select-sm"
                            onchange="document.getElementById('perPageForm').submit()">
                            <option value="tanggal_desc" {{ $sort === 'tanggal_desc' ? 'selected' : '' }}>
                                Tanggal terbaru
                            </option>
                            <option value="tanggal_asc" {{ $sort === 'tanggal_asc' ? 'selected' : '' }}>
                                Tanggal terlama
                            </option>
                            <option value="judul_asc" {{ $sort === 'judul_asc' ? 'selected' : '' }}>
                                Judul A-Z
                            </option>
                            <option value="judul_desc" {{ $sort === 'judul_desc' ? 'selected' : '' }}>
                                Judul Z-A
                            </option>
                        </select>
                    </div>
                </form>

                <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-sm md:btn-md">
                    Tambah Event
                </a>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="p-5 mt-2 overflow-x-auto bg-white shadow-xs rounded-box">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="w-1/3">Judul</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $index => $event)
                        <tr>
                            <th>{{ $events->firstItem() + $index }}</th>
                            <td>{{ $event->judul }}</td>
                            <td>{{ $event->kategori->nama }}</td>
                            <td>{{ $event->tanggal_waktu->format('d M Y') }}</td>
                            <td>{{ $event->lokasi ? $event->lokasi->nama : '-' }}</td>
                            <td>
                                <a href="{{ route('admin.events.show', $event->id) }}"
                                    class="mr-2 btn btn-sm btn-info">Detail</a>
                                <a href="{{ route('admin.events.edit', $event->id) }}"
                                    class="mr-2 btn btn-sm btn-primary">Edit</a>
                                <button class="text-white bg-red-500 btn btn-sm" onclick="openDeleteModal(this)"
                                    data-id="{{ $event->id }}">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada event tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($events->hasPages())
            <div
                class="flex flex-col items-start justify-between gap-2 mt-4 text-sm text-gray-500 md:flex-row md:items-center">
                <div>
                    Menampilkan
                    <span class="font-semibold">{{ $events->firstItem() }}</span>
                    –
                    <span class="font-semibold">{{ $events->lastItem() }}</span>
                    dari
                    <span class="font-semibold">{{ $events->total() }}</span>
                    event
                </div>
                <div class="flex justify-end w-full md:w-auto">
                    {{ $events->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Modal -->
    <dialog id="delete_modal" class="modal">
        <form method="POST" class="modal-box">
            @csrf
            @method('DELETE')

            <input type="hidden" name="event_id" id="delete_event_id">

            <h3 class="mb-4 text-lg font-bold">Hapus Event</h3>
            <p>Apakah Anda yakin ingin menghapus event ini?</p>
            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Hapus</button>
                <button class="btn" onclick="delete_modal.close()" type="reset">Batal</button>
            </div>
        </form>
    </dialog>

    <script>
        function openDeleteModal(button) {
            const id = button.dataset.id;
            const form = document.querySelector('#delete_modal form');
            document.getElementById("delete_event_id").value = id;

            // Set action dengan parameter ID
            form.action = `/admin/events/${id}`

            delete_modal.showModal();
        }
    </script>
</x-layouts.admin>
