<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'My E-Commerce')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">

    <!-- Navbar -->
    <nav class="bg-green-700 text-white shadow-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="flex items-center justify-between py-6">
                <!-- Logo + Brand -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('frontend.home.index') }}" class="flex items-center gap-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Magat Funeral Services Logo"
                             class="h-16 w-16 rounded-full border-2 border-white shadow-md">
                        <span class="font-extrabold text-3xl tracking-wide hover:text-gray-200 transition">
                            Magat Funeral Services
                        </span>
                    </a>
                </div>

                @php
                    // Badge shows total quantity in cart (fallback to item count if you prefer)
                    $cart = session('cart', []);
                    $cartCount = collect($cart)->sum('qty') ?: count($cart);
                @endphp

                <!-- Desktop Links -->
                <ul class="hidden md:flex items-center gap-6 xl:gap-10 text-lg xl:text-xl font-semibold">
                    <li>
                        <a href="{{ route('frontend.home.index') }}"
                           class="transition {{ request()->routeIs('frontend.home.*') ? 'text-white underline underline-offset-8 decoration-2' : 'hover:text-gray-200' }}">
                           Gallery
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.products.index') }}"
                           class="transition {{ request()->routeIs('frontend.products.*') ? 'text-white underline underline-offset-8 decoration-2' : 'hover:text-gray-200' }}">
                           Caskets
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.contact.index') }}"
                           class="transition {{ request()->routeIs('frontend.contact.*') ? 'text-white underline underline-offset-8 decoration-2' : 'hover:text-gray-200' }}">
                           About Us
                        </a>
                    </li>

                    <!-- 🛒 Cart (icon) -->
                    <li class="relative">
                        <a href="{{ route('frontend.cart.index') }}"
                           class="inline-flex items-center justify-center w-11 h-11 rounded-full hover:bg-white/10 transition"
                           aria-label="Open cart">
                            <!-- Heroicon: Shopping Cart -->
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.8"
                                 class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.438M7.5 14.25h8.693c.51 0 .955-.343 1.087-.835l1.62-6.08a1.125 1.125 0 0 0-1.087-1.415H5.106M7.5 14.25L5.106 3M7.5 14.25l-.878 3.293A1.125 1.125 0 0 0 7.706 18.75h8.588m0 0a1.125 1.125 0 1 0 0 2.25m-8.588-2.25a1.125 1.125 0 1 0 0 2.25" />
                            </svg>
                            <span class="sr-only">Cart</span>
                        </a>
                        @if($cartCount > 0)
                            <span
                              class="absolute -top-1 -right-1 min-w-[1.25rem] px-1.5 py-0.5 text-xs leading-none
                                     bg-red-600 text-white rounded-full text-center font-bold">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </li>

                    <li>
                        <a href="{{ route('frontend.login') }}"
                           class="bg-white text-green-700 px-5 py-3 rounded-lg font-bold hover:bg-gray-100 shadow transition">
                           Log In
                        </a>
                    </li>
                </ul>

                <!-- Mobile Hamburger -->
                <button id="menuBtn"
                        class="md:hidden inline-flex items-center justify-center rounded-lg p-3 hover:bg-white/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/70"
                        aria-expanded="false" aria-controls="mobileMenu" aria-label="Open menu">
                    <!-- Icon -->
                    <svg id="menuIcon" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="closeIcon" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div id="mobileMenu" class="md:hidden hidden border-t border-white/20">
            <div class="max-w-7xl mx-auto px-6 py-4 space-y-2">
                <a href="{{ route('frontend.home.index') }}" class="block rounded-md px-4 py-3 text-lg font-semibold hover:bg-white/10">Gallery</a>
                <a href="{{ route('frontend.products.index') }}" class="block rounded-md px-4 py-3 text-lg font-semibold hover:bg-white/10">Caskets</a>
                <a href="{{ route('frontend.contact.index') }}" class="block rounded-md px-4 py-3 text-lg font-semibold hover:bg-white/10">About Us</a>

                <!-- 🛒 Cart (mobile icon + text) -->
                <a href="{{ route('frontend.cart.index') }}"
                   class="flex items-center gap-3 rounded-md px-4 py-3 text-lg font-semibold hover:bg-white/10">
                    <span class="relative inline-flex">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.8"
                             class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.438M7.5 14.25h8.693c.51 0 .955-.343 1.087-.835l1.62-6.08a1.125 1.125 0 0 0-1.087-1.415H5.106M7.5 14.25L5.106 3M7.5 14.25l-.878 3.293A1.125 1.125 0 0 0 7.706 18.75h8.588m0 0a1.125 1.125 0 1 0 0 2.25m-8.588-2.25a1.125 1.125 0 1 0 0 2.25" />
                        </svg>
                        @if($cartCount > 0)
                            <span
                              class="absolute -top-1 -right-1 min-w-[1.15rem] px-1.5 py-0.5 text-[10px] leading-none
                                     bg-red-600 text-white rounded-full text-center font-bold">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </span>
                    <span>Cart</span>
                </a>

                <a href="{{ route('frontend.login') }}" class="block rounded-md px-4 py-3 text-lg font-bold bg-white text-green-700 hover:bg-gray-100">
                    Log In
                </a>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-green-800 text-gray-200 py-12 mt-20">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-base">For more details please contact us</p>
            <p class="text-2xl font-bold">0919-234-5578</p>
            <a href="{{ route('admin.login') }}"
               class="inline-block mt-4 text-green-200 font-semibold hover:text-white transition">
               Admin Portal
            </a>
            <div class="mt-8 text-sm text-gray-400">
                © {{ date('Y') }} Magat Funeral Services. All rights reserved.
            </div>
        </div>
    </footer>

    @stack('scripts')

    <!-- Mobile menu script -->
    <script>
        (function () {
            const btn = document.getElementById('menuBtn');
            const panel = document.getElementById('mobileMenu');
            const menuIcon = document.getElementById('menuIcon');
            const closeIcon = document.getElementById('closeIcon');

            function closeMenu() {
                panel.classList.add('hidden');
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
            }
            function openMenu() {
                panel.classList.remove('hidden');
                menuIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
                btn.setAttribute('aria-expanded', 'true');
            }

            btn?.addEventListener('click', () => {
                const isOpen = btn.getAttribute('aria-expanded') === 'true';
                isOpen ? closeMenu() : openMenu();
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeMenu();
            });

            document.addEventListener('click', (e) => {
                const within = btn.contains(e.target) || panel.contains(e.target);
                if (!within) closeMenu();
            });
        })();
    </script>
</body>
</html>
