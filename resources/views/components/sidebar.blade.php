<aside class="w-64 bg-green-900 text-white flex flex-col">
    <!-- Logo Section -->
    <div class="p-6 border-b border-green-800 text-center">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mx-auto w-24 h-24 mb-2 rounded-full">
        <h2 class="text-2xl font-bold">Admin Panel</h2>
    </div>

    <!-- Navigation -->
    <nav class="mt-4 flex-1 space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center px-6 py-3 hover:bg-green-700 transition {{ request()->routeIs('admin.dashboard') ? 'bg-green-800' : '' }}">
            <!-- Home Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M4 10v10h6V14h4v6h6V10" />
            </svg>
            Dashboard
        </a>

        <!-- Manage Package -->
        <a href="{{ route('admin.packages.index') }}"
           class="flex items-center px-6 py-3 hover:bg-green-700 transition {{ request()->routeIs('admin.packages.*') ? 'bg-green-800' : '' }}">
            <!-- Archive/Package Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-4V3H10v2H6a2 2 0 00-2 2v6m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6m16 0H4" />
            </svg>
            Manage Package
        </a>

        <!-- Inventory -->
        <a href="{{ route('admin.inventory.index') }}"
           class="flex items-center px-6 py-3 hover:bg-green-700 transition {{ request()->routeIs('admin.inventory.*') ? 'bg-green-800' : '' }}">
            <!-- Cube Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4a2 2 0 001-1.73z" />
            </svg>
            Inventory
        </a>

        <!-- Orders -->
        <a href="{{ route('admin.orders.index') }}"
           class="flex items-center px-6 py-3 hover:bg-green-700 transition {{ request()->routeIs('admin.orders.*') ? 'bg-green-800' : '' }}">
            <!-- Shopping Cart Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.6 8m0 0a2 2 0 104 0m-4 0a2 2 0 11-4 0" />
            </svg>
            Orders
        </a>

        <!-- View Customers -->
        <a href="{{ route('admin.customers.index') }}"
           class="flex items-center px-6 py-3 hover:bg-green-700 transition {{ request()->routeIs('admin.customers.*') ? 'bg-green-800' : '' }}">
            <!-- Users Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M12 12a4 4 0 100-8 4 4 0 000 8z" />
            </svg>
            View Customers
        </a>

        <!-- Reports -->
        <a href="{{ route('admin.reports.index') }}"
           class="flex items-center px-6 py-3 hover:bg-green-700 transition {{ request()->routeIs('admin.reports.*') ? 'bg-green-800' : '' }}">
            <!-- Chart Bar Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6m4 6v-4m4 4V9M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            Reports
        </a>
    </nav>

    <!-- Logout Button -->
    <div class="border-t border-green-800 p-4">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center justify-center w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                <!-- Logout Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 11-4 0v-1m0-10V5a2 2 0 114 0v1" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>
