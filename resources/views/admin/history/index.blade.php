<x-layouts.admin title="History Pembelian">
    <div class="container p-10 mx-auto">
        {{-- Header + Toolbar --}}
        <div class="flex flex-col gap-4 mb-4 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-3xl font-semibold">History Pembelian</h1>

            <div class="flex flex-wrap items-center gap-3">
                {{-- Toolbar: per page + sorting --}}
                <form method="GET" action="{{ route('admin.histories.index') }}" id="historyFilterForm"
                    class="flex flex-wrap items-center gap-3 px-3 py-2 border rounded-box bg-base-100 border-base-200">

                    {{-- Per page --}}
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-medium tracking-wide text-gray-500 uppercase">Tampil</span>
                        <select name="per_page" id="per_page" class="select select-bordered select-xs md:select-sm"
                            onchange="document.getElementById('historyFilterForm').submit()">
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
                            onchange="document.getElementById('historyFilterForm').submit()">
                            <option value="date_desc" {{ $sort === 'date_desc' ? 'selected' : '' }}>
                                Tanggal terbaru
                            </option>
                            <option value="date_asc" {{ $sort === 'date_asc' ? 'selected' : '' }}>
                                Tanggal terlama
                            </option>
                            <option value="total_desc" {{ $sort === 'total_desc' ? 'selected' : '' }}>
                                Total tertinggi
                            </option>
                            <option value="total_asc" {{ $sort === 'total_asc' ? 'selected' : '' }}>
                                Total terendah
                            </option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="p-5 overflow-x-auto bg-white shadow-xs rounded-box">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pembeli</th>
                        <th>Event</th>
                        <th>Tanggal Pembelian</th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($histories as $index => $history)
                        <tr>
                            <th>{{ $histories->firstItem() + $index }}</th>
                            <td>{{ $history->user->name }}</td>
                            <td>{{ $history->event?->judul ?? '-' }}</td>
                            <td>{{ $history->created_at->format('d M Y') }}</td>
                            <td>Rp{{ number_format($history->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('admin.histories.show', $history->id) }}"
                                    class="text-white btn btn-sm btn-info">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada history pembelian tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($histories->hasPages())
            <div
                class="flex flex-col items-start justify-between gap-2 mt-4 text-sm text-gray-500 md:flex-row md:items-center">
                <div>
                    Menampilkan
                    <span class="font-semibold">{{ $histories->firstItem() }}</span>
                    –
                    <span class="font-semibold">{{ $histories->lastItem() }}</span>
                    dari
                    <span class="font-semibold">{{ $histories->total() }}</span>
                    transaksi
                </div>
                <div class="flex justify-end w-full md:w-auto">
                    {{ $histories->links() }}
                </div>
            </div>
        @endif
    </div>
</x-layouts.admin>
