@extends('layouts.admin')

@section('title', 'Manage Packages')
@section('page-title', 'Manage Packages')

@section('content')
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-green-800">Package List</h2>
        <a href="#"
           class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
            + Add Package
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full table-auto border-collapse">
            <thead class="bg-green-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium">ID</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Package Name</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Price</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Description</th>
                    <th class="px-6 py-3 text-center text-sm font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <!-- Example row -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-700">1</td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-semibold">Basic Package</td>
                    <td class="px-6 py-4 text-sm text-gray-700">₱5,000</td>
                    <td class="px-6 py-4 text-sm text-gray-600">Includes basic funeral services</td>
                    <td class="px-6 py-4 text-center">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm">Edit</button>
                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">Delete</button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-700">2</td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-semibold">Premium Package</td>
                    <td class="px-6 py-4 text-sm text-gray-700">₱15,000</td>
                    <td class="px-6 py-4 text-sm text-gray-600">Premium services with additional benefits</td>
                    <td class="px-6 py-4 text-center">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm">Edit</button>
                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
