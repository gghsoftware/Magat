@extends('layouts.admin')

@section('title', 'Edit Package')
@section('page-title', 'Edit Package')

@section('content')

    <form action="{{ route('admin.packages.update', $package) }}" method="POST" enctype="multipart/form-data"
          class="bg-white p-6 rounded-xl shadow max-w-3xl">
        @csrf @method('PUT')
        @include('admin.packages.partials.form-fields', ['package' => $package, 'mode' => 'edit'])
        <div class="mt-4">
            <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">Update Package</button>
            <a href="{{ route('admin.packages.index') }}" class="ml-2 px-4 py-2 border rounded">Back</a>
        </div>
    </form>
@endsection
