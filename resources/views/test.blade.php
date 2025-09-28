<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tailwind Rounded Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="space-y-6">
        <!-- Default input -->
        <input type="text" placeholder="Default input" class="border p-2">

        <!-- Rounded (small) -->
        <input type="text" placeholder="Rounded" class="border p-2 rounded">

        <!-- Rounded large -->
        <input type="text" placeholder="Rounded-lg" class="border p-2 rounded-lg">

        <!-- Rounded full (oval) -->
        <input type="text" placeholder="Rounded-full" class="border p-2 rounded-full">

        <!-- Dummy search bar -->
        <div class="flex items-center space-x-2">
            <input type="text" placeholder="Cari dataset..." class="flex-grow py-3 px-4 rounded-full border border-gray-300 shadow">
            <button class="px-6 py-3 bg-blue-600 text-white rounded-lg">Cari</button>
        </div>
    </div>

</body>
</html>
