@extends('layouts.admin')

@section('title', 'Reports Management')
@section('page-title', 'Reports Management')

@section('content')
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-green-800">Reports</h2>
        <div>
            <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                <option value="all">All Reports</option>
                <option value="sales">Sales Reports</option>
                <option value="orders">Order Reports</option>
                <option value="customers">Customer Reports</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full table-auto border-collapse">
            <thead class="bg-green-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium">Report ID</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Title</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Type</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Date</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Status</th>
                    <th class="px-6 py-3 text-center text-sm font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <!-- Example row -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-700">REP-001</td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-semibold">Monthly Sales - July 2024</td>
                    <td class="px-6 py-4 text-sm text-gray-700">Sales</td>
                    <td class="px-6 py-4 text-sm text-gray-700">2024-07-31</td>
                    <td class="px-6 py-4">
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Completed</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm">View</button>
                        <button class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded-md text-sm">Download</button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-700">REP-002</td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-semibold">Customer Activity - Aug 2024</td>
                    <td class="px-6 py-4 text-sm text-gray-700">Customer</td>
                    <td class="px-6 py-4 text-sm text-gray-700">2024-08-10</td>
                    <td class="px-6 py-4">
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Pending</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm">View</button>
                        <button class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded-md text-sm">Download</button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-700">REP-003</td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-semibold">Orders Summary - Aug 2024</td>
                    <td class="px-6 py-4 text-sm text-gray-700">Orders</td>
                    <td class="px-6 py-4 text-sm text-gray-700">2024-08-15</td>
                    <td class="px-6 py-4">
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Failed</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm">View</button>
                        <button class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded-md text-sm">Download</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
