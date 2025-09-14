@extends('layouts.admin')

@section('title', 'Customers Management')
@section('page-title', 'Customers Management')

@section('content')
<<<<<<< HEAD
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
=======
@php
    use Illuminate\Support\Str;
    // Safe defaults for filters
    $q      = $q      ?? request('q', '');
    $status = $status ?? request('status');
    $sort   = $sort   ?? request('sort', 'latest');
@endphp

@if(session('success'))
    <div class="mb-4 rounded bg-green-50 text-green-800 px-4 py-3 border border-green-200">
        {{ session('success') }}
    </div>
@endif

<!-- Header -->
<div class="flex flex-col gap-3 mb-6 md:flex-row md:items-start md:justify-between">
    <h2 class="text-2xl font-semibold text-green-800">Customer List</h2>

    <div class="flex flex-col gap-2 md:flex-row md:items-start md:gap-3 w-full md:w-auto">
        <!-- Filters -->
        <form method="GET" class="flex flex-wrap gap-x-2 gap-y-2 items-start w-full md:w-auto">
            <input type="text" name="q" value="{{ $q }}" placeholder="Search name, email, phone…"
                   class="border rounded px-3 py-2 text-sm w-full sm:w-72 md:w-64 min-w-0">

            <select name="status" class="border rounded px-3 py-2 text-sm w-[9.5rem] sm:w-auto">
                <option value="">All Status</option>
                <option value="active"   @selected($status==='active')>Active</option>
                <option value="inactive" @selected($status==='inactive')>Inactive</option>
            </select>

            <select name="sort" class="border rounded px-3 py-2 text-sm w-[9.5rem] sm:w-auto">
                <option value="latest" @selected($sort==='latest')>Latest</option>
                <option value="oldest" @selected($sort==='oldest')>Oldest</option>
                <option value="name"   @selected($sort==='name')>Name (A–Z)</option>
            </select>

            <button class="px-3 py-2 border rounded text-sm whitespace-nowrap">
                Apply
            </button>
        </form>

        <!-- Add button -->
        <a href="{{ route('admin.customers.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition text-sm self-stretch md:self-auto shrink-0 whitespace-nowrap text-center">
            + Add Customer
        </a>
    </div>
</div>

<!-- Table -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-green-600 text-white sticky top-0 z-10">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold">Address</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold">Created</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($customers as $c)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $c->id }}</td>
                        <td class="px-6 py-3 text-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-green-100 text-green-800 flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(mb_substr($c->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $c->name }}</div>
                                    @if($c->role?->role_name)
                                        <div class="text-xs text-gray-500">{{ ucfirst($c->role->role_name) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $c->email }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">{{ $c->phone ?? '—' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-700">
                            @if($c->address)
                                <span title="{{ $c->address }}">{{ Str::limit($c->address, 32) }}</span>
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-3 text-sm">
                            <span class="px-2 py-1 rounded text-xs {{ $c->status==='active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($c->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-600">
                            {{ optional($c->created_at)->format('Y-m-d') ?: '—' }}
                        </td>
                        <td class="px-6 py-3 text-sm">
                            <div class="flex flex-col gap-2 sm:flex-row sm:justify-center">
                                <a href="{{ route('admin.customers.edit', $c) }}"
                                   class="inline-flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm w-full sm:w-auto">
                                    Edit
                                </a>
                                <form action="{{ route('admin.customers.destroy', $c) }}"
                                      method="POST" class="inline-block"
                                      onsubmit="return confirm('Delete this customer?');">
                                    @csrf @method('DELETE')
                                    <button
                                        class="inline-flex items-center justify-center bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm w-full sm:w-auto">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-6 text-gray-500 text-center" colspan="8">No customers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $customers->links() }}
</div>
>>>>>>> 54d403e (Initial commit of Magat Funeral project)
@endsection
