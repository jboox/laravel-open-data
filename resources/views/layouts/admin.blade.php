<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>[x-cloak]{ display:none !important; }</style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 flex" 
      x-data="{ sidebarOpen: window.innerWidth >= 1024, openMaster: true, openContent: true }"
      x-init="window.addEventListener('resize', () => { sidebarOpen = window.innerWidth >= 1024 })">

    <!-- Mobile header -->
    <header class="lg:hidden w-full bg-white dark:bg-gray-800 p-4 flex justify-between items-center shadow-md">
        <button @click="sidebarOpen = true" class="text-gray-700 dark:text-gray-200 text-2xl">
            ☰
        </button>
        <h1 class="text-lg font-bold text-blue-600 dark:text-blue-400">Admin Panel</h1>
    </header>

    <!-- Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 transform lg:translate-x-0 w-64 bg-white dark:bg-gray-800 h-screen p-4 shadow-lg z-50
               transition-transform ease-in-out duration-300"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        <!-- Close (mobile only) -->
        <div class="lg:hidden flex justify-end mb-2">
            <button @click="sidebarOpen = false" class="text-gray-700 dark:text-gray-200 text-xl">✖</button>
        </div>

        <!-- Sidebar content -->
        <h2 class="text-xl font-bold mb-6 text-blue-600 dark:text-blue-400 hidden lg:block">Admin Panel</h2>
        <nav class="space-y-2">
            <!-- Master Data -->
            <div>
                <button @click="openMaster = !openMaster"
                        class="w-full flex items-center px-3 py-2 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    ⚙️ <span class="ml-2">Master Data</span>
                    <span class="ml-auto" x-text="openMaster ? '▾' : '▸'"></span>
                </button>
                <div x-show="openMaster" x-transition.duration.200ms class="ml-6 mt-2 space-y-1" x-cloak>
                    <a href="{{ route('admin.users.index') }}"
                       class="block px-3 py-2 rounded transition {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        👤 Users
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                       class="block px-3 py-2 rounded transition {{ request()->routeIs('admin.categories.*') ? 'bg-blue-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        📂 Kategori
                    </a>
                    <a href="{{ route('admin.regions.index') }}"
                       class="block px-3 py-2 rounded transition {{ request()->routeIs('admin.regions.*') ? 'bg-blue-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        🌍 Wilayah
                    </a>
                </div>
            </div>

            <!-- Konten -->
            <div>
                <button @click="openContent = !openContent"
                        class="w-full flex items-center px-3 py-2 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                    🗂️ <span class="ml-2">Konten</span>
                    <span class="ml-auto" x-text="openContent ? '▾' : '▸'"></span>
                </button>
                <div x-show="openContent" x-transition.duration.200ms class="ml-6 mt-2 space-y-1" x-cloak>
                    <a href="{{ route('admin.datasets.index') }}"
                       class="block px-3 py-2 rounded transition {{ request()->routeIs('admin.datasets.*') ? 'bg-blue-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        📊 Dataset
                    </a>
                    <a href="{{ route('admin.articles.index') }}"
                       class="block px-3 py-2 rounded transition {{ request()->routeIs('admin.articles.*') ? 'bg-blue-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        📰 Artikel
                    </a>
                </div>
            </div>
        </nav>
    </aside>

    <!-- Overlay (mobile) -->
    <div class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden transition-opacity duration-300"
         x-show="sidebarOpen && window.innerWidth < 1024"
         x-transition.opacity
         @click="sidebarOpen = false"
         x-cloak></div>

    <!-- Content -->
    <main class="flex-1 p-6 dark:text-gray-200 lg:ml-64 transition-all duration-300">
        @yield('content')
    </main>
</body>
</html>
