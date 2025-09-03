<nav class="bg-green-700 text-white py-5 shadow-lg">
    <div class="container mx-auto flex justify-between items-center px-6">
        <!-- Logo + Brand -->
        <div class="flex items-center space-x-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-12 rounded-full">
            <a href="{{ route('frontend.home') }}" class="font-bold text-2xl">Magat Funeral Services</a>
        </div>

        <!-- Links -->
        <ul class="flex space-x-8 text-lg font-medium">
            <li><a href="{{ route('frontend.home') }}" class="hover:text-gray-200">Gallery</a></li>
            <li><a href="{{ route('frontend.products.index') }}" class="hover:text-gray-200">Caskets</a></li>
            <li><a href="{{ route('frontend.contact') }}" class="hover:text-gray-200">About Us</a></li>
            <li><a href="{{ route('login') }}" class="hover:text-gray-200">Log In</a></li>
        </ul>
    </div>
</nav>
