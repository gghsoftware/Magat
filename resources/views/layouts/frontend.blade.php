@php use Illuminate\Support\Str; @endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'My E-Commerce')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans">

    <!-- Navbar -->
    <nav class="bg-emerald-600 text-white shadow-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="flex items-center justify-between py-6">
                <!-- Logo + Brand -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('frontend.home.index') }}" class="flex items-center gap-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Magat Funeral Services Logo"
                             class="h-16 w-16 rounded-full border-2 border-white shadow-md">
                        <span class="font-extrabold text-3xl tracking-wide hover:text-emerald-100 transition">
                            Magat Funeral Services
                        </span>
                    </a>
                </div>

                <!-- Desktop Links -->
                <ul class="hidden md:flex items-center gap-6 xl:gap-10 text-lg xl:text-xl font-semibold">
                    <li>
                        <a href="{{ route('frontend.home.index') }}"
                           class="transition {{ request()->routeIs('frontend.home.*') ? 'text-white underline underline-offset-8 decoration-2' : 'hover:text-emerald-100' }}">
                           Gallery
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.packages.index') }}"
                           class="transition {{ request()->routeIs('frontend.packages.*') ? 'text-white underline underline-offset-8 decoration-2' : 'hover:text-emerald-100' }}">
                           Packages
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('frontend.contact.index') }}"
                           class="transition {{ request()->routeIs('frontend.contact.*') ? 'text-white underline underline-offset-8 decoration-2' : 'hover:text-emerald-100' }}">
                           About Us
                        </a>
                    </li>

                    {{-- Desktop user area --}}
                    @auth
                    <li class="relative">
                        <details class="group">
                        <summary class="list-none flex items-center gap-3 cursor-pointer rounded-lg px-3 py-2 hover:bg-white/10">
                            <span class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-white text-emerald-700 font-bold">
                            {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="font-semibold">
                            {{ Str::limit(auth()->user()->name, 18) }}
                            </span>
                            <svg class="w-4 h-4 opacity-80 group-open:rotate-180 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.086l3.71-3.855a.75.75 0 111.08 1.04l-4.24 4.4a.75.75 0 01-1.08 0l-4.24-4.4a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </summary>

                        <div class="absolute right-0 mt-2 w-64 bg-white text-emerald-900 rounded-xl shadow-xl ring-1 ring-black/5 overflow-hidden z-50">
                            <div class="px-4 py-3 border-b">
                            <p class="font-semibold truncate">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-gray-600 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <ul class="py-1 text-sm">
                            <li>
                                <a href="{{ route('frontend.account.dashboard') }}" class="block px-4 py-2 hover:bg-emerald-50">My Account</a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.account.orders') }}" class="block px-4 py-2 hover:bg-emerald-50">My Orders</a>
                            </li>
                            <li class="border-t">
                                <form method="POST" action="{{ route('frontend.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-emerald-50 text-red-600 font-medium">
                                    Log Out
                                </button>
                                </form>
                            </li>
                            </ul>
                        </div>
                        </details>
                    </li>
                    @else
                    <li>
                        <a href="{{ route('frontend.login') }}"
                        class="bg-white text-emerald-700 px-5 py-3 rounded-lg font-bold hover:bg-gray-100 shadow transition">
                        Log In
                        </a>
                    </li>
                    <li class="hidden xl:block">
                        <a href="{{ route('frontend.register') }}"
                        class="px-5 py-3 rounded-lg font-bold hover:bg-white/10 border border-white/40">
                        Sign Up
                        </a>
                    </li>
                    @endauth

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
                <a href="{{ route('frontend.packages.index') }}" class="block rounded-md px-4 py-3 text-lg font-semibold hover:bg-white/10">Packages</a>
                <a href="{{ route('frontend.contact.index') }}" class="block rounded-md px-4 py-3 text-lg font-semibold hover:bg-white/10">About Us</a>

                {{-- Mobile auth-aware actions --}}
                @guest
                <a href="{{ route('frontend.login') }}" class="block rounded-md px-4 py-3 text-lg font-bold bg-white text-emerald-700 hover:bg-gray-100">
                    Log In
                </a>
                <a href="{{ route('frontend.register') }}" class="block rounded-md px-4 py-3 text-lg font-semibold hover:bg-white/10">
                    Sign Up
                </a>
                @else
                <div class="flex items-center gap-3 rounded-md px-4 py-3">
                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-white text-emerald-700 font-bold">
                    {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                    </span>
                    <div class="min-w-0">
                    <div class="font-semibold truncate">{{ auth()->user()->name }}</div>
                    <div class="text-sm text-emerald-100 truncate">{{ auth()->user()->email }}</div>
                    </div>
                </div>

                <a href="{{ route('frontend.account.dashboard') }}" class="block rounded-md px-4 py-3 text-lg font-semibold hover:bg-white/10">
                    My Account
                </a>
                <a href="{{ route('frontend.account.orders') }}" class="block rounded-md px-4 py-3 text-lg font-semibold hover:bg-white/10">
                    My Orders
                </a>
                <form method="POST" action="{{ route('frontend.logout') }}" class="px-4 pt-1">
                    @csrf
                    <button type="submit" class="w-full text-left rounded-md px-4 py-3 text-lg font-semibold hover:bg-white/10 text-red-200">
                    Log Out
                    </button>
                </form>
                @endguest

            </div>
        </div>
    </nav>

    {{-- Toast container --}}
    <div id="toast-root" class="fixed bottom-6 right-6 z-[9999] space-y-3"></div>

    @php
    // Assemble messages from session and validation
    $toastPayload = [
        'success' => session('success'),
        'error'   => session('error'),
        'warning' => session('warning'),
        'errors'  => $errors->any() ? $errors->all() : [],
        // if user was sent to login explicitly for customization
        'reason'  => request('reason'),
    ];
    @endphp

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-emerald-700 text-gray-200 py-12 mt-20">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-base">For more details please contact us</p>
            <p class="text-2xl font-bold">0919-234-5578</p>
            <a href="{{ route('admin.login') }}"
               class="inline-block mt-4 text-emerald-200 font-semibold hover:text-white transition">
               Admin Portal
            </a>
            <div class="mt-8 text-sm text-gray-200/80">
                © {{ date('Y') }} Magat Funeral Services. All rights reserved.
            </div>
        </div>
    </footer>

<script>
(function(){
  const root = document.getElementById('toast-root');

  function toast(type, message, timeout = 4200){
    if(!message) return;
    const base = document.createElement('div');
    base.className = [
      'pointer-events-auto select-none',
      'w-[320px] rounded-xl shadow-lg ring-1 ring-black/5',
      'px-4 py-3 text-sm flex gap-3 items-start',
      'bg-white'
    ].join(' ');

    const colorByType = {
      success: 'text-emerald-800',
      error:   'text-red-800',
      warning: 'text-amber-800',
      info:    'text-emerald-800'
    };

    const badgeByType = {
      success: 'bg-emerald-600',
      error:   'bg-red-600',
      warning: 'bg-amber-600',
      info:    'bg-emerald-600'
    };

    base.innerHTML = `
      <span class="mt-0.5 h-2 w-2 rounded-full ${badgeByType[type]||badgeByType.info}"></span>
      <div class="min-w-0 ${colorByType[type]||colorByType.info}">
        ${message}
      </div>
      <button type="button" class="ml-auto text-gray-400 hover:text-gray-600">✕</button>
    `;

    const closeBtn = base.querySelector('button');
    closeBtn.addEventListener('click', () => root.removeChild(base));

    root.appendChild(base);
    setTimeout(() => { if (root.contains(base)) root.removeChild(base); }, timeout);
  }

  // Pull data from Blade
  const data = @json($toastPayload);

  // 1) explicit success/error/warning flashes
  if (data.success) toast('success', data.success);
  if (data.error)   toast('error',   data.error);
  if (data.warning) toast('warning', data.warning);

  // 2) validation errors -> show first one (or loop if you prefer)
  if (Array.isArray(data.errors) && data.errors.length){
    toast('error', data.errors[0]);
  }

  // 3) info toast when redirected to login for customization
  if (data.reason === 'customization') {
    toast('info', 'Please log in to customize a package.');
  }
})();
</script>

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
