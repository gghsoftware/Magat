@extends('layouts.admin')

@section('title', 'Orders Management')
@section('page-title', 'Orders Management')

@section('content')
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-green-800">Order List</h2>
        <a href="#"
           class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
            + Add Order
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full table-auto border-collapse">
            <thead class="bg-green-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium">Order ID</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Customer</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Package</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Amount</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Date</th>
                    <th class="px-6 py-3 text-center text-sm font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <!-- Example row -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-700">ORD-2024001</td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-semibold">Juan Dela Cruz</td>
                    <td class="px-6 py-4 text-sm text-gray-700">Premium Package</td>
                    <td class="px-6 py-4 text-sm text-gray-700">₱15,000</td>
                    <td class="px-6 py-4">
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Completed</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">2024-08-14</td>
                    <td class="px-6 py-4 text-center">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm">View</button>
                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">Cancel</button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-700">ORD-2024002</td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-semibold">Maria Santos</td>
                    <td class="px-6 py-4 text-sm text-gray-700">Basic Package</td>
                    <td class="px-6 py-4 text-sm text-gray-700">₱5,000</td>
                    <td class="px-6 py-4">
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Pending</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">2024-08-15</td>
                    <td class="px-6 py-4 text-center">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm">View</button>
                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">Cancel</button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-700">ORD-2024003</td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-semibold">Jose Rizal</td>
                    <td class="px-6 py-4 text-sm text-gray-700">Casket Only</td>
                    <td class="px-6 py-4 text-sm text-gray-700">₱12,000</td>
                    <td class="px-6 py-4">
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Cancelled</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">2024-08-16</td>
                    <td class="px-6 py-4 text-center">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm">View</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
