<x-layouts.app>
    <!-- Hero Slideshow -->
    <div class="relative w-full h-[500px] overflow-hidden">
        @if($events->count() > 0)
            <!-- Slides Container -->
            <div id="slideshow" class="relative w-full h-full">
                @foreach($events as $index => $event)
                <div class="slide absolute inset-0 transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}">
                    <!-- Background Image -->
                    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $event->gambar ? (filter_var($event->gambar, FILTER_VALIDATE_URL) ? $event->gambar : asset('images/events/' . $event->gambar)) : asset('images/konser.jpeg') }}');">
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                    </div>
                    <!-- Content -->
                    <div class="absolute bottom-0 left-0 right-0 p-8 lg:p-16 text-white">
                        <div class="max-w-7xl mx-auto">
                            <span class="badge badge-primary mb-3">{{ $event->kategori->nama ?? 'Event' }}</span>
                            <h2 class="text-3xl lg:text-5xl font-bold mb-3">{{ $event->judul }}</h2>
                            <p class="text-lg mb-2">📅 {{ \Carbon\Carbon::parse($event->tanggal_waktu)->locale('id')->translatedFormat('d F Y, H:i') }} WIB</p>
                            <p class="text-lg mb-4">📍 {{ $event->lokasi ? $event->lokasi->nama . ' (' . $event->lokasi->kota . ')' : '-' }}</p>
                            <a href="{{ route('events.show', $event) }}" class="btn btn-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Slide Indicators -->
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2 z-10">
                @foreach($events as $index => $event)
                <button onclick="goToSlide({{ $index }})" class="slide-dot w-3 h-3 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-white w-8' : 'bg-white/50' }}"></button>
                @endforeach
            </div>
            
            <!-- Navigation Arrows -->
            @if($events->count() > 1)
            <button onclick="prevSlide()" class="absolute left-4 top-1/2 transform -translate-y-1/2 btn btn-circle btn-ghost text-white text-2xl hover:bg-white/20">❮</button>
            <button onclick="nextSlide()" class="absolute right-4 top-1/2 transform -translate-y-1/2 btn btn-circle btn-ghost text-white text-2xl hover:bg-white/20">❯</button>
            @endif
        @else
            <!-- Default Hero jika tidak ada event -->
            <div class="w-full h-full bg-blue-900 flex items-center justify-center">
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

    <section class="max-w-7xl mx-auto py-12 px-6">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-black uppercase italic">Event</h2>
            <div class="flex gap-2">
                <a href="{{ route('home') }}">
                    <x-user.category-pill :label="'Semua'" :active="!request('kategori')" />
                </a>
                @foreach($categories as $kategori)
                <a href="{{ route('home', ['kategori' => $kategori->id]) }}">
                    <x-user.category-pill :label="$kategori->nama" :active="request('kategori') == $kategori->id" />
                </a>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($events as $event)
            <x-user.event-card 
                :title="$event->judul" 
                :date="$event->tanggal_waktu" 
                :location="$event->lokasi ? $event->lokasi->nama . ' (' . $event->lokasi->kota . ')' : '-'"
                :price="$event->tikets_min_harga" 
                :image="$event->gambar" 
                :href="route('events.show', $event)" />
            @endforeach
        </div>
    </section>
</x-layouts.app>
