@extends('layouts.admin')

@section('title', 'Order #'.$order->id)
@section('page-title', 'Order #'.$order->id)

@section('content')
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Order info --}}
        <div class="bg-white rounded-xl shadow p-5 lg:col-span-2">
            <h3 class="text-lg font-semibold text-green-800 mb-4">Order Details</h3>

            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                <div>
                    <dt class="text-gray-500">Customer</dt>
                    <dd class="text-gray-900 font-medium">{{ $order->customer_name }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Email</dt>
                    <dd class="text-gray-900 break-all">{{ $order->customer_email }}</dd>
                </div>
                @if($order->customer_phone)
                <div>
                    <dt class="text-gray-500">Phone</dt>
                    <dd class="text-gray-900">{{ $order->customer_phone }}</dd>
                </div>
                @endif
                <div>
                    <dt class="text-gray-500">Payment Plan</dt>
                    <dd class="text-gray-900">{{ ucfirst($order->payment_plan) }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Subtotal</dt>
                    <dd class="text-gray-900">₱{{ number_format($order->subtotal, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Status</dt>
                    @php
                        $statusClass = [
                            'pending'   => 'bg-yellow-100 text-yellow-800',
                            'confirmed' => 'bg-blue-100 text-blue-800',
                            'paid'      => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ][$order->status] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <dd><span class="px-2 py-1 rounded text-xs {{ $statusClass }}">{{ ucfirst($order->status) }}</span></dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="text-gray-500">Notes</dt>
                    <dd class="text-gray-900 whitespace-pre-wrap">{{ $order->notes ?: '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Created</dt>
                    <dd class="text-gray-900">{{ optional($order->created_at)->format('Y-m-d H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Updated</dt>
                    <dd class="text-gray-900">{{ optional($order->updated_at)->format('Y-m-d H:i') }}</dd>
                </div>
            </dl>

            <h4 class="mt-6 mb-2 font-semibold text-gray-900">Items</h4>
            <div class="overflow-x-auto border rounded">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left">Name</th>
                            <th class="px-3 py-2 text-left">Price</th>
                            <th class="px-3 py-2 text-left">Qty</th>
                            <th class="px-3 py-2 text-left">Line Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($order->items as $it)
                            <tr>
                                <td class="px-3 py-2">{{ $it->name }}</td>
                                <td class="px-3 py-2">₱{{ number_format($it->price, 2) }}</td>
                                <td class="px-3 py-2">{{ $it->qty }}</td>
                                <td class="px-3 py-2">₱{{ number_format($it->price * $it->qty, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-3 py-4 text-gray-500">No items.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right: Actions & Payments --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="text-lg font-semibold text-green-800 mb-4">Actions</h3>
                <div class="flex flex-wrap gap-2">
                    @if($order->status !== 'cancelled' && $order->status !== 'paid')
                        <form action="{{ route('admin.orders.confirm', $order) }}" method="POST"
                              onsubmit="return confirm('Confirm this order?');">
                            @csrf
                            <button class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">Confirm</button>
                        </form>
                        <form action="{{ route('admin.orders.markPaid', $order) }}" method="POST"
                              onsubmit="return confirm('Mark this order as paid?');">
                            @csrf
                            <button class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded">Mark Paid</button>
                        </form>
                    @endif
                    @if($order->status !== 'cancelled')
                        <form action="{{ route('admin.orders.cancel', $order) }}" method="POST"
                              onsubmit="return confirm('Cancel this order?');">
                            @csrf
                            <button class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded">Cancel</button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="text-lg font-semibold text-green-800 mb-3">Payment Transactions</h3>
                <div class="overflow-x-auto border rounded">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left">ID</th>
                                <th class="px-3 py-2 text-left">Amount</th>
                                <th class="px-3 py-2 text-left">Method</th>
                                <th class="px-3 py-2 text-left">Status</th>
                                <th class="px-3 py-2 text-left">Paid At</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($order->transactions as $tr)
                                <tr>
                                    <td class="px-3 py-2">#{{ $tr->id }}</td>
                                    <td class="px-3 py-2">₱{{ number_format($tr->amount, 2) }}</td>
                                    <td class="px-3 py-2">{{ strtoupper($tr->payment_method) }}</td>
                                    <td class="px-3 py-2">
                                        @php
                                            $pClass = [
                                                'pending'   => 'bg-yellow-100 text-yellow-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'failed'    => 'bg-red-100 text-red-800',
                                            ][$tr->payment_status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2 py-1 rounded text-xs {{ $pClass }}">{{ ucfirst($tr->payment_status) }}</span>
                                    </td>
                                    <td class="px-3 py-2">{{ optional($tr->paid_at)->format('Y-m-d H:i') ?: '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-3 py-4 text-gray-500">No transactions.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($order->scheduledPayments->count())
                <div class="bg-white rounded-xl shadow p-5">
                    <h3 class="text-lg font-semibold text-green-800 mb-3">Scheduled Payments</h3>
                    <div class="overflow-x-auto border rounded">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left">Seq</th>
                                    <th class="px-3 py-2 text-left">Amount</th>
                                    <th class="px-3 py-2 text-left">Status</th>
                                    <th class="px-3 py-2 text-left">Due Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($order->scheduledPayments as $sp)
                                    <tr>
                                        <td class="px-3 py-2">{{ $sp->sequence }}</td>
                                        <td class="px-3 py-2">₱{{ number_format($sp->amount, 2) }}</td>
                                        <td class="px-3 py-2">
                                            @php
                                                $sClass = [
                                                    'scheduled' => 'bg-gray-100 text-gray-800',
                                                    'due'       => 'bg-yellow-100 text-yellow-800',
                                                    'paid'      => 'bg-green-100 text-green-800',
                                                    'overdue'   => 'bg-red-100 text-red-800',
                                                ][$sp->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2 py-1 rounded text-xs {{ $sClass }}">{{ ucfirst($sp->status) }}</span>
                                        </td>
                                        <td class="px-3 py-2">{{ optional($sp->due_date)->format('Y-m-d') ?: '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
