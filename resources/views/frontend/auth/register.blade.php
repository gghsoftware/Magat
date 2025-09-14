@extends('layouts.frontend')

@section('title', 'Sign Up - Magat Funeral')

@section('content')
<div class="flex flex-col md:flex-row max-w-7xl mx-auto px-6 py-16 gap-12">

    <!-- Intro Section -->
    <div class="md:w-1/2 flex flex-col justify-center">
        <h1 class="text-5xl font-bold text-green-900 mb-6">
            Join <br>Magat Funeral Services!
        </h1>
        <p class="text-xl text-green-900">
            <strong>Create an account to manage your orders and memorial services.</strong>
        </p>
    </div>

    <!-- Registration Box -->
    <div class="md:w-1/2 bg-green-700 text-white rounded-3xl p-10 shadow-lg">
        <h2 class="text-2xl font-semibold mb-6 text-center">Sign Up</h2>

        <form action="{{ route('frontend.register.post') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Full Name -->
            <div>
                <label for="name" class="block mb-1 font-medium text-white">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                       class="w-full rounded-lg px-4 py-2 border border-green-900 focus:outline-none focus:ring-2 focus:ring-green-300 text-green-900 placeholder-green-200"
                       placeholder="Enter your full name" required>
                @error('name')
                    <span class="text-red-300 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block mb-1 font-medium text-white">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       class="w-full rounded-lg px-4 py-2 border border-green-900 focus:outline-none focus:ring-2 focus:ring-green-300 text-green-900 placeholder-green-200"
                       placeholder="Enter your email" required>
                @error('email')
                    <span class="text-red-300 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block mb-1 font-medium text-white">Password</label>
                <input type="password" id="password" name="password"
                       class="w-full rounded-lg px-4 py-2 border border-green-900 focus:outline-none focus:ring-2 focus:ring-green-300 text-green-900 placeholder-green-200"
                       placeholder="Enter a password" required>
                @error('password')
                    <span class="text-red-300 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block mb-1 font-medium text-white">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="w-full rounded-lg px-4 py-2 border border-green-900 focus:outline-none focus:ring-2 focus:ring-green-300 text-green-900 placeholder-green-200"
                       placeholder="Confirm your password" required>
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="w-full bg-white text-green-700 font-semibold rounded-lg py-2 hover:bg-gray-100 transition">
                Sign Up
            </button>
        </form>

        <!-- Login Link -->
        <div class="mt-6 text-center text-sm text-green-200">
            Already have an account? 
            <a href="{{ route('frontend.login') }}" class="underline hover:text-white font-medium">
                Log In
            </a>
        </div>
    </div>
</div>
@endsection
