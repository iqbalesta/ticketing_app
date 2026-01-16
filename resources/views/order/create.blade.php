<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-2xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Buat Order</h1>

        <div class="bg-white shadow-md rounded p-6">
            <form action="{{ route('order.store') }}" method="POST" id="orderForm">
                @csrf
                <div class="mb-4">
                    <label for="id_event" class="block text-gray-700 font-bold mb-2">Pilih Event</label>
                    <select 
                        id="id_event" 
                        name="id_event" 
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        required
                    >
                        <option value="">Pilih Event</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}">{{ $event->judul }}</option>
                        @endforeach
                    </select>
                    @error('id_event')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div id="tiketContainer" class="mb-4 hidden">
                    <label class="block text-gray-700 font-bold mb-2">Pilih Tiket</label>
                    <div id="tiketList"></div>
                    @error('tikets')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Buat Order
                    </button>
                    <a href="{{ route('order.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const eventSelect = document.getElementById('id_event');
        const tiketContainer = document.getElementById('tiketContainer');
        const tiketList = document.getElementById('tiketList');
        const events = @json($events);

        eventSelect.addEventListener('change', function() {
            const eventId = this.value;
            tiketList.innerHTML = '';

            if (!eventId) {
                tiketContainer.classList.add('hidden');
                return;
            }

            const event = events.find(e => e.id == eventId);
            if (event && event.tikets.length > 0) {
                tiketContainer.classList.remove('hidden');
                event.tikets.forEach((tiket, index) => {
                    const html = `
                        <div class="mb-3 p-3 border border-gray-300 rounded">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-bold">${tiket.tipe.toUpperCase()} - Rp${new Intl.NumberFormat('id-ID').format(tiket.harga)}</p>
                                    <p class="text-sm text-gray-600">Stok: ${tiket.stok}</p>
                                </div>
                                <input 
                                    type="number" 
                                    name="tikets[${index}][jumlah]" 
                                    min="0" 
                                    max="${tiket.stok}" 
                                    value="0"
                                    class="w-16 px-2 py-1 border border-gray-300 rounded"
                                >
                            </div>
                            <input type="hidden" name="tikets[${index}][id_tiket]" value="${tiket.id}">
                        </div>
                    `;
                    tiketList.innerHTML += html;
                });
            } else {
                tiketContainer.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
