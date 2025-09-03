<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-[#d6f0d6] font-sans antialiased">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Bar -->
            <header class="bg-[#28a745] text-white px-6 py-4 flex justify-between items-center shadow-md">
                <h1 class="text-lg font-bold">@yield('page-title', 'Dashboard')</h1>
                <button class="relative bg-white text-gray-700 px-4 py-2 rounded-full shadow">
                    Messages
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-2">23</span>
                </button>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
