@extends('layouts.admin')

@section('title', 'Edit Customer')
@section('page-title', 'Edit Customer')

@section('content')
@if ($errors->any())
    <div class="mb-4 rounded bg-red-50 text-red-800 px-4 py-3 border border-red-200">
        <ul class="list-disc ml-5 text-sm">
            @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.customers.update', $customer) }}" class="bg-white shadow rounded p-5 space-y-4">
    @csrf
    @method('PUT')
    @include('admin.customers.partials.form-fields', ['customer' => $customer])
    <div class="pt-4">
        <button class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded">
            Update
        </button>
        <a href="{{ route('admin.customers.index') }}" class="ml-2 text-gray-600">Cancel</a>
    </div>
</form>
@endsection
