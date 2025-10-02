@props(['dataset'])

<div class="relative inline-block text-left">
    <button type="button"
        class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 
               bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700"
        id="menu-button-{{ $dataset->id }}" aria-expanded="true" aria-haspopup="true">
        ⬇️ Download Agregasi
        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Dropdown menu -->
    <div class="origin-top-left absolute mt-2 w-44 rounded-md shadow-lg bg-white dark:bg-gray-800 
                ring-1 ring-black ring-opacity-5 hidden"
         id="download-menu-{{ $dataset->id }}" role="menu"
         aria-orientation="vertical" aria-labelledby="menu-button-{{ $dataset->id }}">
        <div class="py-1" role="none">
            <a href="{{ route('datasets.downloadAggregated', [$dataset->id, 'csv']) }}"
            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700">CSV</a>
            <a href="{{ route('datasets.downloadAggregated', [$dataset->id, 'json']) }}"
            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700">JSON</a>
            <a href="{{ route('datasets.downloadAggregated', [$dataset->id, 'xlsx']) }}"
            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700">Excel</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const button = document.getElementById('menu-button-{{ $dataset->id }}');
        const menu = document.getElementById('download-menu-{{ $dataset->id }}');

        button.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });

        document.addEventListener('click', (event) => {
            if (!button.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    });
</script>
