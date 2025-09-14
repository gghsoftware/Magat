@extends('layouts.admin')

@section('title', 'Customer #'.$customer->id)
@section('page-title', 'Customer #'.$customer->id)

@section('content')
    @if(session('success'))
        <div class="mb-4 rounded bg-green-50 text-green-800 px-4 py-3 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-sm text-gray-500">Customer ID</div>
                <div class="text-xl font-semibold">CUST-{{ str_pad($customer->id, 3, '0', STR_PAD_LEFT) }}</div>
            </div>
            <form action="{{ route('admin.customers.toggle', $customer) }}" method="POST">
                @csrf
                <button class="px-3 py-1.5 rounded text-sm
                    {{ $customer->status === 'active' ? 'bg-gray-600 hover:bg-gray-700' : 'bg-green-600 hover:bg-green-700' }} text-white">
                    {{ $customer->status === 'active' ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
        </div>

        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-3 text-sm mt-4">
            <div>
                <dt class="text-gray-500">Name</dt>
                <dd class="text-gray-900 font-medium">{{ $customer->name }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Email</dt>
                <dd class="text-gray-900 break-all">{{ $customer->email }}</dd>
            </div>
            @if($customer->phone)
            <div>
                <dt class="text-gray-500">Phone</dt>
                <dd class="text-gray-900">{{ $customer->phone }}</dd>
            </div>
            @endif
            @if($customer->address)
            <div class="md:col-span-2">
                <dt class="text-gray-500">Address</dt>
                <dd class="text-gray-900">{{ $customer->address }}</dd>
            </div>
            @endif
            <div>
                <dt class="text-gray-500">Status</dt>
                <dd>
                    <span class="px-2 py-1 rounded text-xs
                        {{ $customer->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($customer->status) }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-gray-500">Created</dt>
                <dd class="text-gray-900">{{ optional($customer->created_at)->format('Y-m-d H:i') }}</dd>
            </div>
        </dl>

        <div class="mt-5 flex items-center gap-2">
            <a href="{{ route('admin.customers.edit', $customer) }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">Edit</a>
            <a href="{{ route('admin.customers.index') }}" class="px-4 py-2 border rounded">Back to list</a>
            <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST"
                  onsubmit="return confirm('Delete this customer?');" class="ml-auto">
                @csrf @method('DELETE')
                <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Delete</button>
            </form>
        </div>
    </div>
@endsection
