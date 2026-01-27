<x-layouts.admin title="Daftar Tiket">
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
                    <h2 class="card-title text-2xl">Daftar Tiket</h2>
                    <div class="flex gap-2">
                        @if (request('event_id'))
                            <a href="{{ route('admin.tickets.create', request('event_id')) }}" class="btn btn-primary btn-sm">
                                + Tambah Tiket
                            </a>
                        @endif
                        <a href="{{ route('admin.events.index') }}" class="btn btn-ghost btn-sm">Kembali ke Event</a>
                    </div>
                </div>

                <!-- Filter by Event -->
                <div class="form-control mb-6 max-w-xs">
                    <label class="label">
                        <span class="label-text font-semibold">Filter Event</span>
                    </label>
                    <select id="eventFilter" class="select select-bordered w-full" onchange="location = this.value;">
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
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($tikets as $tiket)
                            <div class="card bg-gradient-to-br from-primary to-primary-focus shadow-lg hover:shadow-xl transition-shadow">
                                <div class="card-body">
                                    <!-- Event Info -->
                                    <div class="mb-4 pb-4 border-b border-primary-content/20">
                                        <h3 class="text-xs text-primary-content/70 font-semibold mb-1">EVENT</h3>
                                        <p class="text-white font-bold text-lg line-clamp-2">{{ $tiket->event->judul }}</p>
                                    </div>

                                    <!-- Ticket Type -->
                                    <div class="mb-4">
                                        <h4 class="text-white/70 text-xs font-semibold mb-1">TIPE TIKET</h4>
                                        <p class="text-white text-2xl font-bold">{{ ucfirst($tiket->tipe) }}</p>
                                    </div>

                                    <!-- Price -->
                                    <div class="mb-4 bg-primary-content/10 rounded-lg p-3">
                                        <p class="text-primary-content/70 text-xs mb-1">HARGA</p>
                                        <p class="text-white text-xl font-bold">Rp{{ number_format($tiket->harga, 0, ',', '.') }}</p>
                                    </div>

                                    <!-- Stock Info -->
                                    <div class="mb-6 flex justify-between items-center">
                                        <div>
                                            <p class="text-primary-content/70 text-xs mb-1">STOK</p>
                                            <p class="text-white text-lg font-bold">{{ $tiket->stok }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-primary-content/70 text-xs mb-1">TERJUAL</p>
                                            <p class="text-white text-lg font-bold">{{ $tiket->detailOrders()->sum('jumlah') ?? 0 }}</p>
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="mb-6">
                                        @php
                                            $terjual = $tiket->detailOrders()->sum('jumlah') ?? 0;
                                            $percentage = $tiket->stok > 0 ? round(($terjual / $tiket->stok) * 100) : 0;
                                        @endphp
                                        <progress class="progress progress-success w-full" value="{{ $terjual }}" max="{{ $tiket->stok }}"></progress>
                                        <p class="text-primary-content/70 text-xs mt-2">
                                            {{ $percentage }}% Terjual
                                        </p>
                                    </div>

                                    <!-- Actions -->
                                    <div class="card-actions justify-between">
                                        <a href="{{ route('admin.tickets.edit', $tiket->id) }}" class="btn btn-sm btn-ghost text-white hover:bg-primary-content/20">
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
                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <p class="text-gray-500 text-lg mb-6">
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
            <h3 class="font-bold text-lg mb-4">Hapus Tiket</h3>
            <p class="py-4">Apakah Anda yakin ingin menghapus tiket <strong id="delete_ticket_name"></strong> dari event <strong id="delete_event_name"></strong>?</p>
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
