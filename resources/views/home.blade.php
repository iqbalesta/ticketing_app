<x-layouts.app>
    <!-- Hero Slideshow -->
    <div class="relative w-full h-[500px] overflow-hidden">
        @if ($events->count() > 0)
            <!-- Slides Container -->
            <div id="slideshow" class="relative w-full h-full">
                @foreach ($events as $index => $event)
                    <div
                        class="slide absolute inset-0 transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
                        <!-- Background Image -->
                        <div class="absolute inset-0 bg-center bg-cover"
                            style="background-image: url('{{ $event->gambar ? (filter_var($event->gambar, FILTER_VALIDATE_URL) ? $event->gambar : asset('storage/' . $event->gambar)) : asset('images/konser.jpeg') }}');">
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent">
                            </div>
                        </div>
                        <!-- Content -->
                        <div class="absolute bottom-0 left-0 right-0 p-8 text-white lg:p-16">
                            <div class="mx-auto max-w-7xl">
                                <span class="mb-3 badge badge-primary">{{ $event->kategori->nama ?? 'Event' }}</span>
                                <h2 class="mb-3 text-3xl font-bold lg:text-5xl">{{ $event->judul }}</h2>
                                <p class="mb-2 text-lg">
                                    📅
                                    {{ \Carbon\Carbon::parse($event->tanggal_waktu)->locale('id')->translatedFormat('d F Y, H:i') }}
                                    WIB
                                </p>
                                <p class="mb-4 text-lg">
                                    📍
                                    {{ $event->lokasi ? $event->lokasi->nama . ' (' . $event->lokasi->kota . ')' : '-' }}
                                </p>
                                <a href="{{ route('events.show', $event) }}" class="btn btn-primary">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Slide Indicators -->
            <div class="absolute z-10 flex gap-2 transform -translate-x-1/2 bottom-4 left-1/2">
                @foreach ($events as $index => $event)
                    <button onclick="goToSlide({{ $index }})"
                        class="slide-dot w-3 h-3 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-white w-8' : 'bg-white/50' }}">
                    </button>
                @endforeach
            </div>

            <!-- Navigation Arrows -->
            @if ($events->count() > 1)
                <button onclick="prevSlide()"
                    class="absolute text-2xl text-white transform -translate-y-1/2 left-4 top-1/2 btn btn-circle btn-ghost hover:bg-white/20">
                    ❮
                </button>
                <button onclick="nextSlide()"
                    class="absolute text-2xl text-white transform -translate-y-1/2 right-4 top-1/2 btn btn-circle btn-ghost hover:bg-white/20">
                    ❯
                </button>
            @endif
        @else
            <!-- Default Hero jika tidak ada event -->
            <div class="flex items-center justify-center w-full h-full bg-blue-900">
                <div class="text-center text-white">
                    <h1 class="text-5xl font-bold">Hi, Amankan Tiketmu yuk.</h1>
                    <p class="py-6">TixKet: Beli tiket, auto asik.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Slideshow Script -->
    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.slide-dot');
        const totalSlides = slides.length;
        let autoSlideInterval;

        function showSlide(index) {
            if (totalSlides === 0) return;

            currentSlide = (index + totalSlides) % totalSlides;

            slides.forEach((slide, i) => {
                slide.classList.toggle('opacity-100', i === currentSlide);
                slide.classList.toggle('opacity-0', i !== currentSlide);
            });

            dots.forEach((dot, i) => {
                dot.classList.toggle('bg-white', i === currentSlide);
                dot.classList.toggle('w-8', i === currentSlide);
                dot.classList.toggle('bg-white/50', i !== currentSlide);
                dot.classList.toggle('w-3', i !== currentSlide);
            });
        }

        function nextSlide() {
            showSlide(currentSlide + 1);
            resetAutoSlide();
        }

        function prevSlide() {
            showSlide(currentSlide - 1);
            resetAutoSlide();
        }

        function goToSlide(index) {
            showSlide(index);
            resetAutoSlide();
        }

        function startAutoSlide() {
            if (totalSlides > 1) {
                autoSlideInterval = setInterval(() => {
                    showSlide(currentSlide + 1);
                }, 5000);
            }
        }

        function resetAutoSlide() {
            clearInterval(autoSlideInterval);
            startAutoSlide();
        }

        // Start auto slideshow
        document.addEventListener('DOMContentLoaded', startAutoSlide);
    </script>

    <!-- Event List + Filter/Sorting + Pagination -->
    <section class="px-6 py-12 mx-auto max-w-7xl">
        {{-- Header + Toolbar --}}
        <div class="flex flex-col gap-4 mb-8 md:flex-row md:items-center md:justify-between">
            <h2 class="text-2xl italic font-black uppercase">Event</h2>

            <div
                class="flex flex-col items-start w-full gap-3 md:flex-row md:items-center md:gap-4 md:justify-end md:w-auto">
                {{-- Toolbar: per page + sorting (tanpa box besar) --}}
                <form method="GET" action="{{ route('home') }}" class="flex flex-wrap items-center gap-4 text-sm">

                    {{-- Per page --}}
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-semibold tracking-wide text-gray-500 uppercase">Tampil</span>
                        <select name="per_page"
                            class="px-2 border rounded-md select select-xs md:select-sm select-ghost border-base-300"
                            onchange="this.form.submit()">
                            @foreach ($allowedPerPage as $size)
                                <option value="{{ $size }}"
                                    {{ (int) request('per_page', $perPage) === $size ? 'selected' : '' }}>
                                    {{ $size }}
                                </option>
                            @endforeach
                        </select>
                        <span class="text-[11px] text-gray-500">/ halaman</span>
                    </div>

                    {{-- Sorting --}}
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-semibold tracking-wide text-gray-500 uppercase">Urutkan</span>
                        <select name="sort"
                            class="px-2 border rounded-md select select-xs md:select-sm select-ghost border-base-300"
                            onchange="this.form.submit()">
                            <option value="date_asc"
                                {{ request('sort', 'date_asc') === 'date_asc' ? 'selected' : '' }}>
                                Tanggal terdekat
                            </option>
                            <option value="date_desc" {{ request('sort') === 'date_desc' ? 'selected' : '' }}>
                                Tanggal terjauh
                            </option>
                            <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                                Harga termurah
                            </option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                                Harga termahal
                            </option>
                        </select>
                    </div>

                    {{-- bawa filter lain --}}
                    @if (request('kategori'))
                        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    @endif
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                </form>

                {{-- Kategori pills --}}
                <div class="flex flex-wrap gap-2">
                    {{-- Semua --}}
                    <a
                        href="{{ route(
                            'home',
                            array_filter([
                                'sort' => request('sort'),
                                'per_page' => request('per_page', $perPage),
                                'q' => request('q'),
                            ]),
                        ) }}">
                        <x-user.category-pill :label="'Semua'" :active="!request('kategori')" />
                    </a>

                    {{-- Per kategori --}}
                    @foreach ($categories as $kategori)
                        <a
                            href="{{ route(
                                'home',
                                array_filter([
                                    'kategori' => $kategori->id,
                                    'sort' => request('sort'),
                                    'per_page' => request('per_page', $perPage),
                                    'q' => request('q'),
                                ]),
                            ) }}">
                            <x-user.category-pill :label="$kategori->nama" :active="request('kategori') == $kategori->id" />
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        @if ($events->count())
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($events as $event)
                    <x-user.event-card :title="$event->judul" :date="$event->tanggal_waktu" :location="$event->lokasi ? $event->lokasi->nama . ' (' . $event->lokasi->kota . ')' : '-'" :price="$event->tikets_min_harga"
                        :image="$event->gambar" :href="route('events.show', $event)" />
                @endforeach
            </div>

            @if ($events->hasPages())
                <div class="flex justify-center mt-8">
                    {{ $events->links() }}
                </div>
            @endif
        @else
            <p class="text-center text-gray-500">Belum ada event yang sesuai filter.</p>
        @endif
    </section>
</x-layouts.app>
