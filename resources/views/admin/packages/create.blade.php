@extends('layouts.admin')

@section('title', 'Add Package')
@section('page-title', 'Add Package')
{{-- create.blade.php --}}

@section('content')

    <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white p-6 rounded-xl shadow max-w-3xl">
        @csrf
        @include('admin.packages.partials.form-fields', ['package' => $package, 'mode' => 'create'])
        <div class="mt-4">
            <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">Save Package</button>
            <a href="{{ route('admin.packages.index') }}" class="ml-2 px-4 py-2 border rounded">Cancel</a>
        </div>
    </form>
@endsection
