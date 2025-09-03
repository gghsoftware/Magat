@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'WELCOME ADMIN !')

@section('content')
    <p class="text-gray-700 mb-6">
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi ipsam laudantium error, optio libero dicta.
    </p>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-green-600 text-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <h3 class="font-medium">Total Users</h3>
            <p class="text-3xl font-bold mt-2">156</p>
        </div>
        <div class="bg-green-600 text-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <h3 class="font-medium">Orders</h3>
            <p class="text-3xl font-bold mt-2">89</p>
        </div>
        <div class="bg-green-600 text-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <h3 class="font-medium">Revenue</h3>
            <p class="text-3xl font-bold mt-2">₱2,450,000</p>
        </div>
        <div class="bg-green-600 text-white p-6 rounded-xl shadow-md hover:shadow-lg transition">
            <h3 class="font-medium">Messages</h3>
            <p class="text-3xl font-bold mt-2">23</p>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white p-6 rounded-xl shadow-md">
        <h3 class="text-lg font-semibold mb-4 text-green-700">Monthly Order Summary (January - December 2024)</h3>
        <div class="h-64 flex items-center justify-center text-gray-400 border-2 border-dashed border-green-200 rounded-lg">
            [ Chart Placeholder ]
        </div>
    </div>
@endsection
