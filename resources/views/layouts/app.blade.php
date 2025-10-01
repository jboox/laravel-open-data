<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ navOpen: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sikka Open Data') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-800">

    <!-- Navbar -->
    <nav id="navbar" class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b transition-shadow">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">

            <!-- Logo -->
            <div>
                <a href="{{ url('/') }}" class="text-xl font-bold text-blue-600">
                    {{ config('app.name', 'Sikka Open Data') }}
                </a>
            </div>

            <!-- Menu utama (desktop) -->
            <div class="hidden md:flex items-center gap-8">
                <!-- Dropdown Data -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="font-semibold text-sm text-gray-700 hover:text-blue-600 flex items-center gap-1">
                        Data <span class="ml-1">▾</span>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute left-0 mt-2 w-40 bg-white border rounded shadow-lg z-50">
                        <a href="{{ route('datasets.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Datasets</a>
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Analitik</a>
                    </div>
                </div>

                <!-- Dropdown kategori -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="font-semibold text-sm text-gray-700 hover:text-blue-600 flex items-center gap-1">
                        Kategori <span class="ml-1">▾</span>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute left-0 mt-2 w-56 bg-white border rounded shadow-lg z-50">
                        @php
                            try { $categories = \App\Models\Category::all(); }
                            catch (\Exception $e) { $categories = collect(); }
                        @endphp
                        @forelse($categories as $cat)
                            <a href="{{ route('categories.show', $cat->slug) }}"
                               class="block px-4 py-2 text-sm hover:bg-gray-100">{{ $cat->name }}</a>
                        @empty
                            <span class="block px-4 py-2 text-sm text-gray-400">Belum ada kategori</span>
                        @endforelse
                    </div>
                </div>

                <a href="{{ route('articles.index') }}" class="font-semibold text-sm text-gray-700 hover:text-blue-600">Articles</a>
            </div>

            <!-- Auth (desktop) -->
            <div class="hidden md:flex items-center gap-4">
                @auth
                    <span class="text-sm text-gray-600">Hi, {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-blue-600">Login</a>
                    <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-blue-600">Register</a>
                @endauth
            </div>

            <!-- Hamburger (mobile only) -->
            <div class="md:hidden">
                <button @click="navOpen = !navOpen" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="navOpen" class="md:hidden bg-white border-t px-4 py-3 space-y-2">
            <a href="{{ route('datasets.index') }}" class="block text-sm text-gray-700 hover:bg-gray-100 px-2 py-1">Datasets</a>
            <a href="{{ route('dashboard') }}" class="block text-sm text-gray-700 hover:bg-gray-100 px-2 py-1">Analitik</a>
            <a href="{{ route('articles.index') }}" class="block text-sm text-gray-700 hover:bg-gray-100 px-2 py-1">Articles</a>
            <!--<div>
                <p class="text-gray-700 font-semibold">Kategori:</p>
                <div class="mt-2 space-y-2">
                    @foreach($categories as $cat)
                        <a href="{{ route('categories.show', $cat->slug) }}"
                           class="block px-2 py-1 text-sm rounded {{ request()->is('categories/'.$cat->slug) ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div> -->
            @auth
                <div class="border-t pt-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left text-sm text-red-600 hover:bg-red-50 px-2 py-1">
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="block text-sm text-gray-700 hover:bg-gray-100 px-2 py-1">Login</a>
                <a href="{{ route('register') }}" class="block text-sm text-gray-700 hover:bg-gray-100 px-2 py-1">Register</a>
            @endauth
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{-- Untuk Blade biasa --}}
        @yield('content')

        {{-- Untuk Livewire page component --}}
        {{ $slot ?? '' }}
    </main>
    @stack('scripts')
    @livewireScripts
</body>
</html>
