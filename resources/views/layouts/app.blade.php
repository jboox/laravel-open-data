<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sikka Open Data') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("global-search");
    const placeholders = [
        "Silahkan cari dataset atau artikel...",
        "Penduduk...",
        "Kesehatan...",
        "Pendidikan..."
    ];
    let index = 0;
    let charIndex = 0;
    let deleting = false;

    function typeEffect() {
        let current = placeholders[index];
        if (!deleting) {
            input.setAttribute("placeholder", current.substring(0, charIndex++));
            if (charIndex > current.length) {
                deleting = true;
                setTimeout(typeEffect, 1500); // pause sebentar
                return;
            }
        } else {
            input.setAttribute("placeholder", current.substring(0, charIndex--));
            if (charIndex < 0) {
                deleting = false;
                index = (index + 1) % placeholders.length;
                charIndex = 0;
            }
        }
        setTimeout(typeEffect, deleting ? 50 : 100);
    }

    typeEffect();
});
</script>


<body class="bg-gray-50 text-gray-900">

    <!-- Navbar -->
    <nav id="navbar" class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b transition-shadow">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">

            <!-- Logo -->
            <div>
                <a href="{{ url('/') }}" class="text-xl font-bold text-blue-600">
                    {{ config('app.name', 'Sikka Open Data') }}
                </a>
            </div>

            <!-- Menu utama (selalu tampil di desktop) -->
            <div class="flex items-center gap-8">
                <a href="{{ url('/datasets') }}"
                   class="{{ request()->is('datasets*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                   Datasets
                </a>
                <a href="{{ url('/articles') }}"
                   class="{{ request()->is('articles*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                   Articles
                </a>

                <!-- Dropdown kategori (klik toggle) -->
                <div class="relative">
                    <button id="kategori-toggle" class="flex items-center {{ request()->is('categories*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                        Kategori <span class="ml-1">▾</span>
                    </button>
                    <div id="kategori-menu" class="absolute left-0 mt-2 w-56 bg-white border rounded shadow-lg hidden z-50">
                        @php
                            try { $categories = \App\Models\Category::all(); }
                            catch (\Exception $e) { $categories = collect(); }
                        @endphp

                        @forelse($categories as $cat)
                            <a href="{{ route('categories.show', $cat->slug) }}"
                            class="block px-4 py-2 text-sm hover:bg-gray-100">
                                {{ $cat->name }}
                            </a>
                        @empty
                            <span class="block px-4 py-2 text-sm text-gray-400">Belum ada kategori</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Auth (desktop) -->
            <div class="hidden md:flex items-center">
                @if(Auth::check())
                    <span class="mr-4">Halo, {{ Auth::user()->name ?? 'User' }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:underline">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="{{ request()->is('login') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }} mr-4">
                       Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="{{ request()->is('register') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
                       Register
                    </a>
                @endif
            </div>

            <!-- Hamburger (mobile only) -->
            <div class="md:hidden">
                <button id="menu-toggle" class="text-gray-700 hover:text-blue-600 focus:outline-none">
                    ☰
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden bg-white border-t px-4 py-3 space-y-3 md:hidden">
            <a href="{{ url('/datasets') }}"
               class="block {{ request()->is('datasets*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
               Datasets
            </a>
            <a href="{{ url('/articles') }}"
               class="block {{ request()->is('articles*') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }}">
               Articles
            </a>

            <!-- Kategori (mobile, langsung tampil) -->
            <div>
                <p class="text-gray-700 font-semibold">Kategori:</p>
                <div class="mt-2 space-y-2">
                    @foreach($categories as $cat)
                        <a href="{{ route('categories.show', $cat->slug) }}"
                           class="block px-2 py-1 text-sm rounded {{ request()->is('categories/'.$cat->slug) ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-700 hover:bg-gray-100' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Auth (mobile) -->
            <div class="border-t pt-3">
                @if(Auth::check())
                    <p class="mb-2">Halo, {{ Auth::user()->name ?? 'User' }}</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-red-600 hover:underline">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="{{ request()->is('login') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }} mb-2 block">
                       Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="{{ request()->is('register') ? 'text-blue-600 font-semibold' : 'text-gray-700 hover:text-blue-600' }} block">
                       Register
                    </a>
                @endif
            </div>
        </div>
    </nav>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Toggle mobile menu
    const btn = document.getElementById('menu-toggle');
    const menu = document.getElementById('mobile-menu');
    const navbar = document.getElementById('navbar');

    btn?.addEventListener('click', function () {
        menu.classList.toggle('hidden');
    });

    // Shadow saat scroll
    window.addEventListener('scroll', function() {
        if (window.scrollY > 10) {
            navbar.classList.add('shadow-md');
        } else {
            navbar.classList.remove('shadow-md');
        }
    });

    // Toggle kategori menu (desktop)
    const kategoriBtn = document.getElementById('kategori-toggle');
    const kategoriMenu = document.getElementById('kategori-menu');

    kategoriBtn?.addEventListener('click', function(e) {
        e.stopPropagation();
        kategoriMenu.classList.toggle('hidden');
    });

    // Tutup dropdown kalau klik luar
    document.addEventListener('click', function(e) {
        if (!kategoriBtn.contains(e.target) && !kategoriMenu.contains(e.target)) {
            kategoriMenu.classList.add('hidden');
        }
    });
});
</script>


    <!-- Script toggle menu + shadow saat scroll -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const btn = document.getElementById('menu-toggle');
        const menu = document.getElementById('mobile-menu');
        const navbar = document.getElementById('navbar');

        btn?.addEventListener('click', function () {
            menu.classList.toggle('hidden');
        });

        window.addEventListener('scroll', function() {
            if (window.scrollY > 10) {
                navbar.classList.add('shadow-md');
            } else {
                navbar.classList.remove('shadow-md');
            }
        });
    });
    </script>

    <!-- Content -->
    <main class="py-8">
        @yield('content')
    </main>
</body>
</html>
