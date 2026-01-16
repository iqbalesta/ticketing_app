<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
    <div class="drawer lg:drawer-open w-full min-h-screen bg-gray-50">
        <input id="my-drawer-4" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content">
            <!-- Navbar -->
            <nav class="navbar w-full bg-base-300">
                <div class="flex-1">
                    <button class="btn btn-ghost text-xl">Ticketing App</button>
                </div>
                <div class="flex-none gap-2">
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                            <div class="w-10 rounded-full bg-primary text-white flex items-center justify-center">
                                <span>{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                            <li><a href="#">{{ auth()->user()->name }}</a></li>
                            <li><a href="#">Profile</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <label for="my-drawer-4" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor" class="my-1.5 inline-block size-4">
                        <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2-2z"></path>
                        <path d="M9 4v16"></path>
                    </svg>
                </label>
            </nav>
            <!-- Page content -->
            <div class="p-4 lg:p-8">
                {{ $slot }}
            </div>
        </div>

        @include('components.admin.sidebar')
    </div>

    <footer class="bg-base-300 text-center py-3 mt-8">
        <div class="container">
            <p>© {{ date('Y') }} Ticketing App. All rights reserved.</p>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
