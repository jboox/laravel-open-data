<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Sikka Open Data') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <!-- Navbar -->
    <nav class="bg-white shadow-md relative z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">

            <!-- Logo -->
            <div>
                <a href="{{ url('/') }}" class="text-xl font-bold text-blue-600">
                    {{ config('app.name', 'Sikka Open Data') }}
                </a>
            </div>

            <!-- Hamburger (mobile) -->
            <div class="md:hidden">
                <button id="menu-toggle" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                    ☰
                </button>
            </div>

            <!-- Menu utama -->
            <div id="menu" class="hidden md:flex items-center gap-8">
                <a href="{{ url('/datasets') }}" 
                class="{{ request()->is('datasets*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                Datasets
                </a>

                <a href="{{ url('/articles') }}" 
                class="{{ request()->is('articles*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                Articles
                </a>

                <!-- Dropdown kategori -->
                <div class="relative group">
                    <button class="flex items-center {{ request()->is('categories*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                        Kategori <span class="ml-1">▾</span>
                    </button>
                    <div class="absolute left-0 mt-2 w-56 bg-white border rounded shadow-lg hidden group-hover:block z-50">
                        @foreach(\App\Models\Category::all() as $cat)
                            <a href="{{ route('categories.show', $cat->slug) }}"
                            class="block px-4 py-2 text-sm 
                                    {{ request()->is('categories/'.$cat->slug) ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

        <!-- Auth -->
        <div class="hidden md:flex items-center">
            @if(Auth::check())
                <span class="mr-4">Halo, {{ Auth::user()->name ?? 'User' }}</span>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-red-600 hover:underline">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="{{ request()->is('login') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }} mr-4">Login</a>
                <a href="{{ route('register') }}" class="{{ request()->is('register') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">Register</a>
            @endif
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t px-4 py-3 space-y-3">
            <a href="{{ url('/datasets') }}" 
            class="block {{ request()->is('datasets*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
            Datasets
            </a>
            <a href="{{ url('/articles') }}" 
            class="block {{ request()->is('articles*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
            Articles
            </a>

            <!-- Kategori (langsung tampil di mobile) -->
            <div>
                <p class="text-gray-700 font-semibold">Kategori:</p>
                <div class="mt-2 space-y-2">
                    @foreach(\App\Models\Category::all() as $cat)
                        <a href="{{ route('categories.show', $cat->slug) }}"
                        class="block px-2 py-1 text-sm rounded 
                                {{ request()->is('categories/'.$cat->slug) ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Auth -->
            <div class="border-t pt-3">
                @if(Auth::check())
                    <p class="mb-2">Halo, {{ Auth::user()->name ?? 'Guest' }}</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-600 hover:underline">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="{{ request()->is('login') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }} mb-2 block">Login</a>
                    <a href="{{ route('register') }}" class="{{ request()->is('register') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }} block">Register</a>
                @endif
            </div>
        </div>
    </nav>

<!-- Script toggle menu -->
<script>
    document.getElementById('menu-toggle').addEventListener('click', function () {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>


    <!-- Script toggle menu -->
    <script>
        document.getElementById('menu-toggle').addEventListener('click', function () {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>

    <!-- Page Content -->
    <main class="py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-inner mt-8">
        <div class="max-w-7xl mx-auto px-4 py-6 text-center text-gray-500">
            &copy; {{ date('Y') }} {{ config('app.name', 'Sikka Open Data') }}. All rights reserved.
        </div>
    </footer>
</body>
</html>
