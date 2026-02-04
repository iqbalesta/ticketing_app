<x-layouts.admin title="Manajemen Kategori">
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
            <h1 class="text-3xl font-semibold">Manajemen Kategori</h1>

            <div class="flex flex-wrap items-center gap-3">
                {{-- Toolbar: per page + sorting --}}
                <form method="GET" action="{{ route('admin.categories.index') }}" id="categoryFilterForm"
                    class="flex flex-wrap items-center gap-3 px-3 py-2 border rounded-box bg-base-100 border-base-200">

                    {{-- Per page --}}
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-medium tracking-wide text-gray-500 uppercase">Tampil</span>
                        <select name="per_page" id="per_page" class="select select-bordered select-xs md:select-sm"
                            onchange="document.getElementById('categoryFilterForm').submit()">
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
                            onchange="document.getElementById('categoryFilterForm').submit()">
                            <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>
                                Nama A-Z
                            </option>
                            <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>
                                Nama Z-A
                            </option>
                            <option value="date_desc" {{ $sort === 'date_desc' ? 'selected' : '' }}>
                                Terbaru dibuat
                            </option>
                            <option value="date_asc" {{ $sort === 'date_asc' ? 'selected' : '' }}>
                                Terlama dibuat
                            </option>
                        </select>
                    </div>
                </form>

                <button class="btn btn-primary btn-sm md:btn-md" onclick="add_modal.showModal()">
                    Tambah Kategori
                </button>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="p-5 mt-2 overflow-x-auto bg-white shadow-xs rounded-box">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="w-3/4">Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $index => $category)
                        <tr>
                            <th>{{ $categories->firstItem() + $index }}</th>
                            <td>{{ $category->nama }}</td>
                            <td>
                                <button class="mr-2 btn btn-sm btn-primary" onclick="openEditModal(this)"
                                    data-id="{{ $category->id }}" data-nama="{{ $category->nama }}">
                                    Edit
                                </button>
                                <button class="text-white bg-red-500 btn btn-sm" onclick="openDeleteModal(this)"
                                    data-id="{{ $category->id }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada kategori tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($categories->hasPages())
            <div
                class="flex flex-col items-start justify-between gap-2 mt-4 text-sm text-gray-500 md:flex-row md:items-center">
                <div>
                    Menampilkan
                    <span class="font-semibold">{{ $categories->firstItem() }}</span>
                    –
                    <span class="font-semibold">{{ $categories->lastItem() }}</span>
                    dari
                    <span class="font-semibold">{{ $categories->total() }}</span>
                    kategori
                </div>
                <div class="flex justify-end w-full md:w-auto">
                    {{ $categories->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Add Category Modal -->
    <dialog id="add_modal" class="modal">
        <form method="POST" action="{{ route('admin.categories.store') }}" class="modal-box">
            @csrf
            <h3 class="mb-4 text-lg font-bold">Tambah Kategori</h3>
            <div class="w-full mb-4 form-control">
                <label class="mb-2 label">
                    <span class="label-text">Nama Kategori</span>
                </label>
                <input type="text" placeholder="Masukkan nama kategori" class="w-full input input-bordered"
                    name="nama" required />
            </div>
            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <button class="btn" onclick="add_modal.close()" type="reset">Batal</button>
            </div>
        </form>
    </dialog>

    <!-- Edit Category Modal With Retrieve ID -->
    <dialog id="edit_modal" class="modal">
        <form method="POST" class="modal-box">
            @csrf
            @method('PUT')

            <input type="hidden" name="category_id" id="edit_category_id">

            <h3 class="mb-4 text-lg font-bold">Edit Kategori</h3>
            <div class="w-full mb-4 form-control">
                <label class="mb-2 label">
                    <span class="label-text">Nama Kategori</span>
                </label>
                <input type="text" placeholder="Masukkan nama kategori" class="w-full input input-bordered"
                    id="edit_category_name" name="nama" />
            </div>
            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Simpan</button>
                <button class="btn" onclick="edit_modal.close()" type="reset">Batal</button>
            </div>
        </form>
    </dialog>

    <!-- Delete Modal -->
    <dialog id="delete_modal" class="modal">
        <form method="POST" class="modal-box">
            @csrf
            @method('DELETE')

            <input type="hidden" name="category_id" id="delete_category_id">

            <h3 class="mb-4 text-lg font-bold">Hapus Kategori</h3>
            <p>Apakah Anda yakin ingin menghapus kategori ini?</p>
            <div class="modal-action">
                <button class="btn btn-primary" type="submit">Hapus</button>
                <button class="btn" onclick="delete_modal.close()" type="reset">Batal</button>
            </div>
        </form>
    </dialog>

    <script>
        function openEditModal(button) {
            const name = button.dataset.nama;
            const id = button.dataset.id;
            const form = document.querySelector('#edit_modal form');

            document.getElementById("edit_category_name").value = name;
            document.getElementById("edit_category_id").value = id;

            // Set action dengan parameter ID
            form.action = `/admin/categories/${id}`

            edit_modal.showModal();
        }

        function openDeleteModal(button) {
            const id = button.dataset.id;
            const form = document.querySelector('#delete_modal form');
            document.getElementById("delete_category_id").value = id;

            // Set action dengan parameter ID
            form.action = `/admin/categories/${id}`

            delete_modal.showModal();
        }
    </script>
</x-layouts.admin>
