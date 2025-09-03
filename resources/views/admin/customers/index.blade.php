@extends('layouts.admin')

@section('title', 'Customers Management')
@section('page-title', 'Customers Management')

@section('content')
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-green-800">Customer List</h2>
        <a href="#"
           class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
            + Add Customer
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full table-auto border-collapse">
            <thead class="bg-green-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium">Customer ID</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Phone</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Address</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Status</th>
                    <th class="px-6 py-3 text-center text-sm font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <!-- Example row -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-700">CUST-001</td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-semibold">Juan Dela Cruz</td>
                    <td class="px-6 py-4 text-sm text-gray-700">juan@example.com</td>
                    <td class="px-6 py-4 text-sm text-gray-700">0917-123-4567</td>
                    <td class="px-6 py-4 text-sm text-gray-700">Quezon City</td>
                    <td class="px-6 py-4">
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm">View</button>
                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md text-sm">Edit</button>
                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">Delete</button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-700">CUST-002</td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-semibold">Maria Santos</td>
                    <td class="px-6 py-4 text-sm text-gray-700">maria@example.com</td>
                    <td class="px-6 py-4 text-sm text-gray-700">0928-456-7890</td>
                    <td class="px-6 py-4 text-sm text-gray-700">Makati City</td>
                    <td class="px-6 py-4">
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Inactive</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm">View</button>
                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md text-sm">Edit</button>
                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
