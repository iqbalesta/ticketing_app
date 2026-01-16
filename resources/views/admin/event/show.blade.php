<x-layouts.admin title="Detail Event">
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
                    <h2 class="card-title text-2xl">Detail Event</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-ghost btn-sm">Kembali</a>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Nama Event -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Judul Event</span>
                        </label>
                        <input type="text" class="input input-bordered w-full" value="{{ $event->judul }}" disabled />
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Deskripsi</span>
                        </label>
                        <textarea class="textarea textarea-bordered h-24 w-full" disabled>{{ $event->deskripsi }}</textarea>
                    </div>

                    <!-- Tanggal & Waktu -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Tanggal & Waktu</span>
                        </label>
                        <input type="text" class="input input-bordered w-full"
                            value="{{ $event->tanggal_waktu->format('d M Y H:i') }}" disabled />
                    </div>

                    <!-- Lokasi -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Lokasi</span>
                        </label>
                        <input type="text" class="input input-bordered w-full" value="{{ $event->lokasi }}" disabled />
                    </div>

                    <!-- Kategori -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Kategori</span>
                        </label>
                        <input type="text" class="input input-bordered w-full" value="{{ $event->kategori->nama }}" disabled />
                    </div>

                    <!-- Preview Gambar -->
                    @if ($event->gambar)
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Gambar Event</span>
                        </label>
                        <div class="avatar max-w-sm">
                            <div class="w-full rounded-lg">
                                <img src="{{ asset('images/events/' . $event->gambar) }}" alt="{{ $event->judul }}">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tiket Section -->
        <div class="card bg-base-100 shadow-sm mt-6">
            <div class="card-body">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="card-title text-xl">Daftar Tiket</h3>
                    <button class="btn btn-primary btn-sm" onclick="add_ticket_modal.showModal()">
                        + Tambah Tiket
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tipe</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($event->tikets as $index => $ticket)
                            <tr>
                                <th>{{ $index + 1 }}</th>
                                <td>{{ ucfirst($ticket->tipe) }}</td>
                                <td>Rp{{ number_format($ticket->harga, 0, ',', '.') }}</td>
                                <td>{{ $ticket->stok }}</td>
                                <td>
                                    <div class="flex gap-2">
                                        <button class="btn btn-warning btn-xs" onclick="openEditModal({{ $ticket->id }}, '{{ $ticket->tipe }}', {{ $ticket->harga }}, {{ $ticket->stok }})">
                                            Edit
                                        </button>
                                        <button class="btn btn-error btn-xs" onclick="openDeleteModal({{ $ticket->id }}, '{{ $ticket->tipe }}')">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada tiket untuk event ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Tiket -->
    <dialog id="add_ticket_modal" class="modal">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-lg mb-4">Tambah Tiket</h3>
            <form action="{{ route('admin.tickets.store') }}" method="POST">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">
                <div class="form-control mb-3">
                    <label class="label">
                        <span class="label-text">Tipe Tiket</span>
                    </label>
                    <input type="text" name="tipe" class="input input-bordered w-full" placeholder="Masukkan tipe tiket (contoh: Reguler, VIP, VVIP)" required>
                </div>
                <div class="form-control mb-3">
                    <label class="label">
                        <span class="label-text">Harga</span>
                    </label>
                    <input type="number" name="harga" class="input input-bordered w-full" placeholder="Masukkan harga" required min="0">
                </div>
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Stok</span>
                    </label>
                    <input type="number" name="stok" class="input input-bordered w-full" placeholder="Masukkan stok" required min="0">
                </div>
                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Modal Edit Tiket -->
    <dialog id="edit_ticket_modal" class="modal">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-lg mb-4">Edit Tiket</h3>
            <form id="editTicketForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-control mb-3">
                    <label class="label">
                        <span class="label-text">Tipe Tiket</span>
                    </label>
                    <input type="text" name="tipe" id="edit_tipe" class="input input-bordered w-full" placeholder="Masukkan tipe tiket" required>
                </div>
                <div class="form-control mb-3">
                    <label class="label">
                        <span class="label-text">Harga</span>
                    </label>
                    <input type="number" name="harga" id="edit_harga" class="input input-bordered w-full" required min="0">
                </div>
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Stok</span>
                    </label>
                    <input type="number" name="stok" id="edit_stok" class="input input-bordered w-full" required min="0">
                </div>
                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Modal Hapus Tiket -->
    <dialog id="delete_ticket_modal" class="modal">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-lg mb-4">Hapus Tiket</h3>
            <p>Apakah Anda yakin ingin menghapus tiket <span id="delete_ticket_name" class="font-semibold"></span>?</p>
            <form id="deleteTicketForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="delete_ticket_modal.close()">Batal</button>
                    <button type="submit" class="btn btn-error">Hapus</button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        function openEditModal(id, tipe, harga, stok) {
            document.getElementById('editTicketForm').action = `/admin/tickets/${id}`;
            document.getElementById('edit_tipe').value = tipe;
            document.getElementById('edit_harga').value = harga;
            document.getElementById('edit_stok').value = stok;
            edit_ticket_modal.showModal();
        }

        function openDeleteModal(id, tipe) {
            document.getElementById('deleteTicketForm').action = `/admin/tickets/${id}`;
            document.getElementById('delete_ticket_name').textContent = tipe.toUpperCase();
            delete_ticket_modal.showModal();
        }
    </script>
</x-layouts.admin>
