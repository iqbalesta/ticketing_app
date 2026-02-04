<x-layouts.app>
    <section class="px-6 py-12 mx-auto max-w-7xl">
        <nav class="mb-6">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="{{ route('home') }}" class="link link-neutral">Beranda</a></li>
                    <li><a href="#" class="link link-neutral">Event</a></li>
                    <li>{{ $event->judul }}</li>
                </ul>
            </div>
        </nav>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Left / Main area -->
            <div class="lg:col-span-2">
                <div class="shadow card bg-base-100">
                    <figure>
                        <img src="{{ $event->gambar
                            ? asset('storage/' . $event->gambar)
                            : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp' }}"
                            alt="{{ $event->judul }}" class="object-cover w-full h-96" />
                    </figure>
                    <div class="card-body">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h1 class="text-3xl font-extrabold">{{ $event->judul }}</h1>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($event->tanggal_waktu)->locale('id')->translatedFormat('d F Y, H:i') }}
                                    • 📍
                                    {{ $event->lokasi ? $event->lokasi->nama . ' (' . $event->lokasi->kota . ')' : '-' }}
                                </p>
                                <div class="flex items-center gap-2 mt-3">
                                    <span
                                        class="badge badge-primary">{{ $event->kategori?->nama ?? 'Tanpa Kategori' }}</span>
                                    <span class="badge">{{ $event->user?->name ?? 'Penyelenggara' }}</span>
                                </div>
                            </div>
                        </div>

                        <p class="mt-4 leading-relaxed text-gray-700">{{ $event->deskripsi }}</p>

                        <div class="divider"></div>

                        <h3 class="text-xl font-bold">Pilih Tiket</h3>

                        <div class="mt-4 space-y-4">
                            @forelse($event->tikets as $tiket)
                                <div class="items-center p-4 shadow-sm card card-side">
                                    <div class="flex-1">
                                        <h4 class="font-bold">{{ $tiket->tipe }}</h4>
                                        <p class="text-sm text-gray-500">Stok: <span
                                                id="stock-{{ $tiket->id }}">{{ $tiket->stok }}</span></p>
                                        <p class="mt-2 text-sm">{{ $tiket->keterangan ?? '' }}</p>
                                    </div>

                                    <div class="text-right w-44">
                                        <div class="text-lg font-bold">
                                            {{ $tiket->harga ? 'Rp ' . number_format($tiket->harga, 0, ',', '.') : 'Gratis' }}
                                        </div>

                                        <div class="flex items-center justify-end gap-2 mt-3">
                                            <button type="button" class="btn btn-sm btn-outline" data-action="dec"
                                                data-id="{{ $tiket->id }}" aria-label="Kurangi satu">−</button>
                                            <input id="qty-{{ $tiket->id }}" type="number" min="0"
                                                max="{{ $tiket->stok }}" value="0"
                                                class="w-16 text-center input input-bordered"
                                                data-id="{{ $tiket->id }}" />
                                            <button type="button" class="btn btn-sm btn-outline" data-action="inc"
                                                data-id="{{ $tiket->id }}" aria-label="Tambah satu">+</button>
                                        </div>

                                        <div class="mt-2 text-sm text-gray-500">Subtotal: <span
                                                id="subtotal-{{ $tiket->id }}">Rp 0</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info">Tiket belum tersedia untuk acara ini.</div>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right / Summary -->
            <aside class="lg:col-span-1">
                <div class="sticky p-4 shadow card top-24 bg-base-100">
                    <h4 class="text-lg font-bold">Ringkasan Pembelian</h4>

                    <div class="mt-4">
                        <div class="flex justify-between text-sm text-gray-500"><span>Item</span><span
                                id="summaryItems">0</span>
                        </div>
                        <div class="flex justify-between mt-1 text-xl font-bold"><span>Total</span><span
                                id="summaryTotal">Rp
                                0</span></div>
                    </div>

                    <div class="divider"></div>

                    <div id="selectedList" class="space-y-2 text-sm text-gray-700">
                        <p class="text-gray-500">Belum ada tiket dipilih</p>
                    </div>

                    @auth
                        <button id="checkoutButton" class="btn btn-primary !bg-blue-900 text-white btn-block mt-6"
                            onclick="openCheckout()" disabled>Checkout</button>
                    @else
                        <a href="{{ route('login') }}" class="mt-6 text-white btn btn-primary btn-block">Login untuk
                            Checkout</a>
                    @endauth

                </div>
            </aside>
        </div>

        <!-- Checkout Modal -->
        <dialog id="checkout_modal" class="modal">
            <form method="dialog" class="modal-box">
                <h3 class="text-lg font-bold">Konfirmasi Pembelian</h3>
                <div class="mt-4 space-y-2 text-sm">
                    <div id="modalItems">
                        <p class="text-gray-500">Belum ada item.</p>
                    </div>

                    <div class="divider"></div>
                    <div class="flex items-center justify-between">
                        <span class="font-bold">Total</span>
                        <span class="text-lg font-bold" id="modalTotal">Rp 0</span>
                    </div>
                </div>

                {{-- pesan error checkout --}}
                <div id="checkoutError" class="hidden mt-3 text-sm text-red-600"></div>

                <div class="modal-action">
                    <button class="btn">Tutup</button>
                    <button type="button" class="btn btn-primary px-4 !bg-blue-900 text-white"
                        id="confirmCheckout">Konfirmasi</button>
                </div>
            </form>
        </dialog>

    </section>

    <script>
        (function() {
            // Helper to format Indonesian currency
            const formatRupiah = (value) => {
                return 'Rp ' + Number(value).toLocaleString('id-ID');
            }

            const tickets = {
                @foreach ($event->tikets as $tiket)
                    {{ $tiket->id }}: {
                        id: {{ $tiket->id }},
                        price: {{ $tiket->harga ?? 0 }},
                        stock: {{ $tiket->stok }},
                        tipe: "{{ e($tiket->tipe) }}"
                    },
                @endforeach
            };

            const summaryItemsEl = document.getElementById('summaryItems');
            const summaryTotalEl = document.getElementById('summaryTotal');
            const selectedListEl = document.getElementById('selectedList');
            const checkoutButton = document.getElementById('checkoutButton');

            function updateSummary() {
                let totalQty = 0;
                let totalPrice = 0;
                let selectedHtml = '';

                Object.values(tickets).forEach(t => {
                    const qtyInput = document.getElementById('qty-' + t.id);
                    if (!qtyInput) return;
                    const qty = Number(qtyInput.value || 0);
                    if (qty > 0) {
                        totalQty += qty;
                        totalPrice += qty * t.price;
                        selectedHtml +=
                            `<div class="flex justify-between"><span>${t.tipe} x ${qty}</span><span>${formatRupiah(qty * t.price)}</span></div>`;
                    }
                });

                summaryItemsEl.textContent = totalQty;
                summaryTotalEl.textContent = formatRupiah(totalPrice);
                selectedListEl.innerHTML = selectedHtml || '<p class="text-gray-500">Belum ada tiket dipilih</p>';
                if (checkoutButton) checkoutButton.disabled = totalQty === 0;
            }

            // Wire up plus/minus buttons and manual input
            document.querySelectorAll('[data-action="inc"]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const id = e.currentTarget.dataset.id;
                    const input = document.getElementById('qty-' + id)
                    const info = tickets[id];
                    if (!input || !info) return;
                    let val = Number(input.value || 0);
                    if (val < info.stock) val++;
                    input.value = val;
                    updateTicketSubtotal(id);
                    updateSummary();
                });
            });

            document.querySelectorAll('[data-action="dec"]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const id = e.currentTarget.dataset.id;
                    const input = document.getElementById('qty-' + id);
                    if (!input) return;
                    let val = Number(input.value || 0);
                    if (val > 0) val--;
                    input.value = val;
                    updateTicketSubtotal(id);
                    updateSummary();
                });
            });

            document.querySelectorAll('input[id^="qty-"]').forEach(input => {
                input.addEventListener('change', (e) => {
                    const el = e.currentTarget;
                    const id = el.dataset.id;
                    const info = tickets[id];
                    let val = Number(el.value || 0);
                    if (val < 0) val = 0;
                    if (val > info.stock) val = info.stock;
                    el.value = val;
                    updateTicketSubtotal(id);
                    updateSummary();
                });
            });

            function updateTicketSubtotal(id) {
                const t = tickets[id];
                const qty = Number(document.getElementById('qty-' + id).value || 0);
                const subtotalEl = document.getElementById('subtotal-' + id);
                if (subtotalEl) subtotalEl.textContent = formatRupiah(qty * t.price);
            }

            // Checkout modal
            window.openCheckout = function() {
                const modal = document.getElementById('checkout_modal');
                // populate modal items
                const modalItems = document.getElementById('modalItems');
                const modalTotal = document.getElementById('modalTotal');

                let itemsHtml = '';
                let total = 0;
                Object.values(tickets).forEach(t => {
                    const qty = Number(document.getElementById('qty-' + t.id).value || 0);
                    if (qty > 0) {
                        itemsHtml +=
                            `<div class="flex justify-between"><span>${t.tipe} x ${qty}</span><span>${formatRupiah(qty * t.price)}</span></div>`;
                        total += qty * t.price;
                    }
                });

                modalItems.innerHTML = itemsHtml || '<p class="text-gray-500">Belum ada item.</p>';
                modalTotal.textContent = formatRupiah(total);

                if (typeof modal.showModal === 'function') {
                    modal.showModal();
                } else {
                    // fallback for older browsers
                    modal.classList.add('modal-open');
                }
            }

            // Checkout confirmation - AJAX POST
            document.getElementById('confirmCheckout')?.addEventListener('click', async () => {
                const btn = document.getElementById('confirmCheckout');
                const errorBox = document.getElementById('checkoutError');

                // reset pesan error
                if (errorBox) {
                    errorBox.classList.add('hidden');
                    errorBox.textContent = '';
                }

                btn.setAttribute('disabled', 'disabled');
                btn.textContent = 'Memproses...';

                // gather items
                const items = [];
                Object.values(tickets).forEach(t => {
                    const qty = Number(document.getElementById('qty-' + t.id).value || 0);
                    if (qty > 0) items.push({
                        tiket_id: t.id,
                        jumlah: qty
                    });
                });

                if (items.length === 0) {
                    if (errorBox) {
                        errorBox.textContent = 'Tidak ada tiket dipilih.';
                        errorBox.classList.remove('hidden');
                    }
                    btn.removeAttribute('disabled');
                    btn.textContent = 'Konfirmasi';
                    return;
                }

                try {
                    const res = await fetch("{{ route('orders.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            event_id: {{ $event->id }},
                            items
                        })
                    });

                    let message = 'Gagal membuat pesanan';

                    if (!res.ok) {
                        if (res.status === 429) {
                            message = 'Anda terlalu sering memesan. Silakan coba lagi beberapa saat lagi.';
                        } else {
                            try {
                                const data = await res.json();
                                if (data && data.message) {
                                    message = data.message;
                                }
                            } catch (e) {}
                        }
                        throw new Error(message);
                    }

                    const data = await res.json();
                    window.location.href = data.redirect || '{{ route('orders.index') }}';
                } catch (err) {
                    console.log(err);
                    if (errorBox) {
                        errorBox.textContent = 'Terjadi kesalahan saat memproses pesanan: ' + err.message;
                        errorBox.classList.remove('hidden');
                    }
                    btn.removeAttribute('disabled');
                    btn.textContent = 'Konfirmasi';
                }
            });

            // init
            updateSummary();
        })();
    </script>
</x-layouts.app>
