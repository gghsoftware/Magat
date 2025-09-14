@extends('layouts.admin')

@section('title', 'Orders Management')
@section('page-title', 'Orders Management')

@section('content')
<<<<<<< HEAD
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
=======
    @if(session('success'))
        <div class="mb-4 rounded bg-green-50 text-green-800 px-4 py-3 border border-green-200">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded bg-red-50 text-red-800 px-4 py-3 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <h2 class="text-2xl font-semibold text-green-800">Order List</h2>

        <form method="GET" class="flex flex-wrap items-center gap-2">
            <input type="text" name="q" value="{{ $q }}" placeholder="Search id/name/email/phone…"
                   class="border rounded px-3 py-2 text-sm w-56 md:w-72">
            <select name="status" class="border rounded px-3 py-2 text-sm">
                <option value="">All Status</option>
                @foreach(['pending','confirmed','paid','cancelled'] as $st)
                    <option value="{{ $st }}" @selected($status===$st)>{{ ucfirst($st) }}</option>
                @endforeach
            </select>
            <button class="px-3 py-2 border rounded text-sm">Filter</button>
        </form>
    </div>

    {{-- Desktop table --}}
    <div class="hidden md:block bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-green-600 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide w-24">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide">Package</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide w-36">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide w-32">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide w-40">Date</th>
                        <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wide w-56">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($orders as $o)
                        <tr class="hover:bg-gray-50 align-top">
                            <td class="px-6 py-4 text-sm text-gray-700">#{{ $o->id }}</td>
                            <td class="px-6 py-4 text-sm">
                                <div class="font-semibold text-gray-900 truncate max-w-[22rem]">{{ $o->customer_name }}</div>
                                <div class="text-xs text-gray-500 break-all">{{ $o->customer_email }}</div>
                                @if($o->customer_phone)
                                    <div class="text-xs text-gray-500">{{ $o->customer_phone }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="truncate max-w-[16rem]">{{ $o->primary_item_name ?? '—' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm whitespace-nowrap">₱{{ number_format($o->subtotal, 2) }}</td>
                            <td class="px-6 py-4 text-sm">
                                @php
                                    $statusClass = [
                                        'pending'   => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-blue-100 text-blue-800',
                                        'paid'      => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ][$o->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 rounded text-xs {{ $statusClass }}">{{ ucfirst($o->status) }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ optional($o->created_at)->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-wrap items-center justify-center gap-2">
                                    <a href="{{ route('admin.orders.show', $o) }}"
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded text-sm">
                                        View
                                    </a>
                                    @if($o->status !== 'cancelled')
                                        <form action="{{ route('admin.orders.cancel', $o) }}" method="POST"
                                              onsubmit="return confirm('Cancel this order?');">
                                            @csrf
                                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded text-sm">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-6 py-6 text-center text-gray-500" colspan="7">No orders found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile cards --}}
    <div class="md:hidden grid grid-cols-1 gap-3">
        @forelse($orders as $o)
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">#{{ $o->id }}</div>
                    @php
                        $statusClass = [
                            'pending'   => 'bg-yellow-100 text-yellow-800',
                            'confirmed' => 'bg-blue-100 text-blue-800',
                            'paid'      => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ][$o->status] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-2 py-0.5 rounded text-xs {{ $statusClass }}">{{ ucfirst($o->status) }}</span>
                </div>
                <div class="mt-2 font-semibold">{{ $o->customer_name }}</div>
                <div class="text-xs text-gray-500 break-all">{{ $o->customer_email }}</div>
                <div class="mt-1 text-sm text-gray-700">₱{{ number_format($o->subtotal, 2) }}</div>
                <div class="text-xs text-gray-500">Pkg: {{ $o->primary_item_name ?? '—' }}</div>
                <div class="text-xs text-gray-500 mt-1">{{ optional($o->created_at)->format('Y-m-d H:i') }}</div>

                <div class="mt-3 flex items-center gap-2">
                    <a href="{{ route('admin.orders.show', $o) }}"
                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded text-sm">View</a>
                    @if($o->status !== 'cancelled')
                        <form action="{{ route('admin.orders.cancel', $o) }}" method="POST"
                              onsubmit="return confirm('Cancel this order?');">
                            @csrf
                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded text-sm">Cancel</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-gray-500 text-sm">No orders found.</div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
>>>>>>> 54d403e (Initial commit of Magat Funeral project)
    </div>
@endsection
