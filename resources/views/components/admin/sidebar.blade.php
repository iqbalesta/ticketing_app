<div class="drawer-side">
    <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>
    <div class="flex min-h-full flex-col items-start bg-base-200 w-64">
        <div class="w-full flex items-center justify-center p-4 border-b border-base-300">
            <a href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('assets/images/logo.png') }}" alt="TixKet Logo" class="h-12" />
            </a>
        </div>
        
        <!-- Sidebar content here -->
        <ul class="menu w-full grow gap-1 p-4">
            <!-- Dashboard Item -->
            <li class="{{ request()->routeIs('admin.dashboard') ? 'bg-gray-300 rounded-lg' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="tooltip tooltip-right" data-tip="Dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M6 19h3v-5q0-.425.288-.712T10 13h4q.425 0 .713.288T15 14v5h3v-9l-6-4.5L6 10zm-2 0v-9q0-.475.213-.9t.587-.7l6-4.5q.525-.4 1.2-.4t1.2.4l6 4.5q.375.275.588.7T20 10v9q0 .825-.588 1.413T18 21h-4q-.425 0-.712-.288T13 20v-5h-2v5q0 .425-.288.713T10 21H6q-.825 0-1.412-.587T4 19m8-6.75" />
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Kategori item -->
            <li class="{{ request()->routeIs('admin.categories.*') ? 'bg-gray-300 rounded-lg' : '' }}">
                <a href="{{ route('admin.categories.index') }}" class="tooltip tooltip-right" data-tip="Kategori">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h6v6H4zm10 0h6v6h-6zM4 14h6v6H4zm10 3a3 3 0 1 0 6 0a3 3 0 1 0-6 0" />
                    </svg>
                    <span>Manajemen Kategori</span>
                </a>
            </li>
            
            <!-- Event item -->
            <li class="{{ request()->routeIs('admin.events.*') ? 'bg-gray-300 rounded-lg' : '' }}">
                <a href="{{ route('admin.events.index') }}" class="tooltip tooltip-right" data-tip="Event">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3a2 2 0 0 0 0-4V7a2 2 0 0 1 2-2" />
                    </svg>
                    <span>Manajemen Event</span>
                </a>
            </li>

            <!-- Tiket item -->
            <li class="{{ request()->routeIs('admin.tickets.*') || request()->routeIs('admin.tiket.*') ? 'bg-gray-300 rounded-lg' : '' }}">
                <a href="{{ route('admin.tickets.index') }}" class="tooltip tooltip-right" data-tip="Tiket">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2m6 0V3m0 2h0m6 5h0m-6 5h0" />
                    </svg>
                    <span>Manajemen Tiket</span>
                </a>
            </li>

            <!-- Lokasi item -->
            <li class="{{ request()->routeIs('admin.lokasi.*') ? 'bg-gray-300 rounded-lg' : '' }}">
                <a href="{{ route('admin.lokasi.index') }}" class="tooltip tooltip-right" data-tip="Lokasi">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Manajemen Lokasi</span>
                </a>
            </li>
            
            <!-- History item -->
            <li class="{{ request()->routeIs('admin.histories.*') ? 'bg-gray-300 rounded-lg' : '' }}">
                <a href="{{ route('admin.histories.index') }}" class="tooltip tooltip-right" data-tip="History">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span>History Pembelian</span>
                </a>
            </li>
        </ul>

        <!-- logout -->
        <div class="w-full p-4 border-t border-base-300">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="btn btn-outline btn-error w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M10 17v-2h4v-2h-4v-2l-5 3l5 3m9-12H5q-.825 0-1.413.588T3 7v10q0 .825.587 1.413T5 19h14q.825 0 1.413-.587T21 17v-3h-2v3H5V7h14v3h2V7q0-.825-.587-1.413T19 5z" />
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>
