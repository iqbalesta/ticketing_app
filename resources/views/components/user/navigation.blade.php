<div class="navbar bg-base-100 shadow-sm px-4 lg:px-8">
    <div class="navbar-start">
        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </div>
        </div>
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <img src="{{ asset('assets/images/logo.png') }}" alt="TixKet Logo" class="h-9 w-auto" />
        </a>
    </div>
    
    <!-- Search Bar Desktop Only -->
    <div class="flex-1 max-w-4xl mx-8" style="display: none;">
        <form action="{{ route('home') }}" method="GET" style="display: flex; width: 100%; border: 1px solid #d1d5db; border-radius: 8px; overflow: hidden;">
            <input type="text" name="q" value="{{ request('q') }}" style="flex: 1; padding: 10px 200px; border: none; outline: none; font-size: 14px;" placeholder="Temukan tiketmu disini" />
            <button type="submit" style="padding: 10px 20px; background-color: #2563eb; color: white; border: none; cursor: pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
    </div>
    <style>
        @media (min-width: 1024px) {
            .search-desktop { display: flex !important; }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.innerWidth >= 1024) {
                document.querySelector('.flex-1.max-w-4xl').style.display = 'flex';
            }
        });
    </script>
    
    <div class="navbar-end gap-1 lg:gap-4">
        <!-- Language/Region Dropdown -->
        <div class="dropdown dropdown-end hidden lg:block">
            <div tabindex="0" role="button" class="btn btn-ghost btn-sm gap-1">
                <span class="text-lg">🇮🇩</span>
                <span class="text-sm font-medium">ID</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        @guest
            <a href="{{ route('login') }}" class="btn btn-outline btn-sm gap-2 border-gray-300 hover:bg-gray-100 hover:border-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                <span>Login</span>
            </a>
            <a href="{{ route('register') }}" class="btn bg-blue-600 hover:bg-blue-700 text-white btn-sm">Register</a>
        @endguest

        @auth
            <!-- Transaksi -->
            <a href="{{ route('orders.index') }}" class="btn btn-ghost btn-sm gap-2 hidden lg:flex">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Transaksi</span>
            </a>
            
            <!-- Tiket -->
            <a href="{{ route('orders.index') }}" class="btn btn-ghost btn-sm gap-2 hidden lg:flex">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
                <span>Tiket</span>
            </a>
            
            <!-- Profil Dropdown -->
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost btn-sm gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="hidden lg:inline">Profil</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hidden lg:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
                <ul tabindex="0" class="mt-3 p-2 shadow menu menu-compact dropdown-content bg-base-100 rounded-box w-52 z-50">
                    <li class="menu-title px-4 py-2">
                        <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                    </li>
                    <li>
                        <a href="{{ route('orders.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Riwayat Pembelian
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        @endauth
    </div>
</div>
