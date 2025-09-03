@extends('layouts.frontend')

@section('title', 'Log In - Magat Funeral')

@section('content')
<div class="flex flex-col md:flex-row max-w-7xl mx-auto px-6 py-16 gap-12">

    <!-- Intro Section -->
    <div class="md:w-1/2 flex flex-col justify-center">
        <h1 class="text-5xl font-bold text-green-900 mb-6">
            Welcome to <br>Magat Funeral Services!
        </h1>
        <p class="text-xl text-green-900">
            <strong>Choose the best memories for your loved ones.</strong>
        </p>
    </div>

    <!-- Login Box -->
    <div class="md:w-1/2 bg-green-700 text-white rounded-3xl p-10 shadow-lg">
        <h2 class="text-2xl font-semibold mb-6">Log In</h2>

        <form action="{{ route('frontend.login.post') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block mb-1 font-medium">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       class="w-full rounded-lg px-4 py-2 border border-green-900 focus:outline-none focus:ring-2 focus:ring-green-300 text-green-900 placeholder-green-200 placeholder-opacity-90"
                       placeholder="Enter your email" required>
                @error('email')
                    <span class="text-red-300 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block mb-1 font-medium">Password</label>
                <input type="password" id="password" name="password"
                       class="w-full rounded-lg px-4 py-2 border border-green-900 focus:outline-none focus:ring-2 focus:ring-green-300 text-green-900 placeholder-green-200 placeholder-opacity-90"
                       placeholder="Enter your password" required>
                @error('password')
                    <span class="text-red-300 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Forgot Password -->
            <div class="text-right">
                <a href="{{ route('frontend.password.request') }}" class="text-green-200 hover:text-white text-sm">
                    Forgot Password?
                </a>
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="w-full bg-white text-green-700 font-semibold rounded-lg py-2 hover:bg-gray-100 transition">
                Log In
            </button>
        </form>

        <!-- Sign Up -->
        <div class="mt-6 text-center text-sm text-green-200">
            Don't have an account? <br>
            <a href="{{ route('frontend.register') }}" class="underline hover:text-white font-medium">
                Sign Up
            </a>
        </div>
    </div>
</div>
@endsection
