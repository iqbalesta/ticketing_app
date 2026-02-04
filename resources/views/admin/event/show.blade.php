<x-layouts.admin title="Detail Event">
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
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl card-title">Detail Event</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-ghost btn-sm">Kembali</a>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Nama Event -->
                    <div class="form-control">
                        <label class="label">
                            <span class="font-semibold label-text">Judul Event</span>
                        </label>
                        <input type="text" class="w-full input input-bordered" value="{{ $event->judul }}"
                            disabled />
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-control">
                        <label class="label">
                            <span class="font-semibold label-text">Deskripsi</span>
                        </label>
                        <textarea class="w-full h-24 textarea textarea-bordered" disabled>{{ $event->deskripsi }}</textarea>
                    </div>

                    <!-- Tanggal & Waktu -->
                    <div class="form-control">
                        <label class="label">
                            <span class="font-semibold label-text">Tanggal & Waktu</span>
                        </label>
                        <input type="text" class="w-full input input-bordered"
                            value="{{ $event->tanggal_waktu->format('d M Y H:i') }}" disabled />
                    </div>

                    <!-- Lokasi -->
                    <div class="form-control">
                        <label class="label">
                            <span class="font-semibold label-text">Lokasi</span>
                        </label>
                        <input type="text" class="w-full input input-bordered"
                            value="{{ $event->lokasi ? $event->lokasi->nama . ' (' . $event->lokasi->kota . ', ' . $event->lokasi->provinsi . ')' : '-' }}"
                            disabled />
                    </div>

                    <!-- Kategori -->
                    <div class="form-control">
                        <label class="label">
                            <span class="font-semibold label-text">Kategori</span>
                        </label>
                        <input type="text" class="w-full input input-bordered" value="{{ $event->kategori->nama }}"
                            disabled />
                    </div>

                    <!-- Preview Gambar -->
                    @if ($event->gambar)
                        <div class="form-control">
                            <label class="label">
                                <span class="font-semibold label-text">Gambar Event</span>
                            </label>
                            <div class="max-w-sm avatar">
                                <div class="w-full rounded-lg">
                                    <img src="{{ asset('storage/' . $event->gambar) }}" alt="{{ $event->judul }}">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tiket Section -->
        <div class="mt-6 shadow-sm card bg-base-100">
            <div class="card-body">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl card-title">Daftar Tiket</h3>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.tickets.create', ['event_id' => $event->id]) }}"
                            class="btn btn-primary btn-sm">
                            + Tambah Tiket
                        </a>
                        @if ($event->tikets->count() > 0)
                            <a href="{{ route('admin.tickets.index') }}?event_id={{ $event->id }}"
                                class="btn btn-info btn-sm">
                                Lihat Semua Tiket →
                            </a>
                        @endif
                    </div>
                </div>

                @if ($event->tikets->count() > 0)
                    <!-- Ticket Cards Preview -->
                    <div class="grid grid-cols-1 gap-4 mb-4 md:grid-cols-2">
                        @foreach ($event->tikets->take(4) as $ticket)
                            <div class="shadow card bg-gradient-to-br from-info to-info-focus">
                                <div class="p-4 card-body">
                                    <h4 class="mb-2 text-lg font-bold text-white">{{ ucfirst($ticket->tipe) }}</h4>
                                    <div class="mb-3 space-y-2 text-sm text-white/80">
                                        <div class="flex justify-between">
                                            <span>Harga:</span>
                                            <span
                                                class="font-semibold text-white">Rp{{ number_format($ticket->harga, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Stok:</span>
                                            <span class="font-semibold text-white">{{ $ticket->stok }}</span>
                                        </div>
                                    </div>
                                    <div class="justify-between text-xs card-actions">
                                        <a href="{{ route('admin.tickets.edit', $ticket->id) }}"
                                            class="text-white btn btn-ghost btn-xs hover:bg-white/20">
                                            Edit
                                        </a>
                                        <button class="btn btn-error btn-xs"
                                            onclick="openDeleteModal({{ $ticket->id }}, '{{ $ticket->tipe }}')">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($event->tikets->count() > 4)
                        <div class="text-sm text-center text-gray-500">
                            Menampilkan 4 dari {{ $event->tikets->count() }} tiket
                        </div>
                    @endif
                @else
                    <div class="py-8 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 012-2h6a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V5z">
                            </path>
                        </svg>
                        <p>Belum ada tiket untuk event ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Hapus Tiket -->
    <dialog id="delete_ticket_modal" class="modal">
        <div class="modal-box">
            <form method="dialog">
                <button class="absolute btn btn-sm btn-circle btn-ghost right-2 top-2">✕</button>
            </form>
            <h3 class="mb-4 text-lg font-bold">Hapus Tiket</h3>
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
        function openDeleteModal(id, tipe) {
            document.getElementById('deleteTicketForm').action = `/admin/tickets/${id}`;
            document.getElementById('delete_ticket_name').textContent = tipe.toUpperCase();
            delete_ticket_modal.showModal();
        }
    </script>
</x-layouts.admin>
