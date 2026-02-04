<x-layouts.admin title="Daftar Tiket">
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
                    <h2 class="text-2xl card-title">Daftar Tiket</h2>
                    <div class="flex gap-2">
                        @if (request('event_id'))
                            @if (request('event_id'))
                                <a href="{{ route('admin.tickets.create', ['event_id' => request('event_id')]) }}"
                                    class="btn btn-primary btn-sm">
                                    + Tambah Tiket
                                </a>
                            @endif
                        @endif
                        <a href="{{ route('admin.events.index') }}" class="btn btn-ghost btn-sm">Kembali ke Event</a>
                    </div>
                </div>

                <!-- Filter by Event -->
                <div class="max-w-xs mb-6 form-control">
                    <label class="label">
                        <span class="font-semibold label-text">Filter Event</span>
                    </label>
                    <select id="eventFilter" class="w-full select select-bordered" onchange="location = this.value;">
                        <option value="{{ route('admin.tiket.index') }}">Semua Event</option>
                        @foreach ($events as $event)
                            <option value="{{ route('admin.tiket.index', ['event_id' => $event->id]) }}"
                                {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->judul }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tickets Grid -->
                @if ($tikets->count() > 0)
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($tikets as $tiket)
                            <div
                                class="transition-shadow shadow-lg card bg-gradient-to-br from-primary to-primary-focus hover:shadow-xl">
                                <div class="card-body">
                                    <!-- Event Info -->
                                    <div class="pb-4 mb-4 border-b border-primary-content/20">
                                        <h3 class="mb-1 text-xs font-semibold text-primary-content/70">EVENT</h3>
                                        <p class="text-lg font-bold text-white line-clamp-2">{{ $tiket->event->judul }}
                                        </p>
                                    </div>

                                    <!-- Ticket Type -->
                                    <div class="mb-4">
                                        <h4 class="mb-1 text-xs font-semibold text-white/70">TIPE TIKET</h4>
                                        <p class="text-2xl font-bold text-white">{{ ucfirst($tiket->tipe) }}</p>
                                    </div>

                                    <!-- Price -->
                                    <div class="p-3 mb-4 rounded-lg bg-primary-content/10">
                                        <p class="mb-1 text-xs text-primary-content/70">HARGA</p>
                                        <p class="text-xl font-bold text-white">
                                            Rp{{ number_format($tiket->harga, 0, ',', '.') }}</p>
                                    </div>

                                    <!-- Stock Info -->
                                    <div class="flex items-center justify-between mb-6">
                                        <div>
                                            <p class="mb-1 text-xs text-primary-content/70">STOK</p>
                                            <p class="text-lg font-bold text-white">{{ $tiket->stok }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="mb-1 text-xs text-primary-content/70">TERJUAL</p>
                                            <p class="text-lg font-bold text-white">
                                                {{ $tiket->detailOrders()->sum('jumlah') ?? 0 }}</p>
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="mb-6">
                                        @php
                                            $terjual = $tiket->detailOrders()->sum('jumlah') ?? 0;
                                            $percentage = $tiket->stok > 0 ? round(($terjual / $tiket->stok) * 100) : 0;
                                        @endphp
                                        <progress class="w-full progress progress-success" value="{{ $terjual }}"
                                            max="{{ $tiket->stok }}"></progress>
                                        <p class="mt-2 text-xs text-primary-content/70">
                                            {{ $percentage }}% Terjual
                                        </p>
                                    </div>

                                    <!-- Actions -->
                                    <div class="justify-between card-actions">
                                        <a href="{{ route('admin.tickets.edit', $tiket->id) }}"
                                            class="text-white btn btn-sm btn-ghost hover:bg-primary-content/20">
                                            Edit
                                        </a>
                                        <button class="btn btn-sm btn-error"
                                            onclick="openDeleteModal({{ $tiket->id }}, '{{ $tiket->tipe }}', '{{ $tiket->event->judul }}')">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12">
                        <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <p class="mb-6 text-lg text-gray-500">
                            @if (request('event_id'))
                                Event ini belum memiliki tiket
                            @else
                                Belum ada tiket
                            @endif
                        </p>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-primary">Kelola Event</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Delete Tiket -->
    <dialog id="delete_ticket_modal" class="modal">
        <div class="modal-box">
            <h3 class="mb-4 text-lg font-bold">Hapus Tiket</h3>
            <p class="py-4">Apakah Anda yakin ingin menghapus tiket <strong id="delete_ticket_name"></strong> dari
                event <strong id="delete_event_name"></strong>?</p>
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn btn-ghost">Batal</button>
                </form>
                <form id="deleteTicketForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-error">Hapus</button>
                </form>
            </div>
        </div>
    </dialog>

    <script>
        function openDeleteModal(ticketId, tipe, eventName) {
            document.getElementById('delete_ticket_name').textContent = tipe;
            document.getElementById('delete_event_name').textContent = eventName;
            document.getElementById('deleteTicketForm').action = `/admin/tickets/${ticketId}`;
            document.getElementById('delete_ticket_modal').showModal();
        }
    </script>
</x-layouts.admin>
