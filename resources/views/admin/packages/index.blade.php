@extends('layouts.admin')

@section('title', 'Manage Packages')
@section('page-title', 'Manage Packages')

@section('content')
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 text-green-800 px-4 py-3 border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
        <h2 class="text-2xl font-semibold text-green-800">Package List</h2>

        <div class="flex items-center gap-2">
            <form method="GET" class="hidden md:flex items-center">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search name or slug…"
                    class="border rounded px-3 py-2 text-sm w-56 md:w-64"
                >
                <button class="ml-2 px-3 py-2 border rounded text-sm">Search</button>
            </form>

            <a href="{{ route('admin.packages.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition whitespace-nowrap">
                + Add Package
            </a>
        </div>
    </div>

    {{-- Desktop table --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-green-600 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide">Price</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wide">Gallery</th>
                        <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($packages as $pkg)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $pkg->id }}</td>
                            <td class="px-4 py-3 text-sm">
                                <div class="font-semibold text-gray-900">{{ $pkg->name }}</div>
                                <div class="text-xs text-gray-500">{{ $pkg->slug }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm">₱{{ number_format($pkg->price, 0) }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 rounded text-xs {{ $pkg->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $pkg->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                    
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @if(is_array($pkg->gallery))
                                        @foreach($pkg->gallery as $g)
                                            <img src="{{ asset('storage/'.$g) }}"
                                                 class="w-12 h-12 object-cover rounded border"
                                                 alt="Gallery image">
                                        @endforeach
                                    @else
                                        <span class="text-gray-400">No images</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center text-sm">
                                <a href="{{ route('admin.packages.edit', $pkg) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm">Edit</a>
                                <form action="{{ route('admin.packages.destroy', $pkg) }}"
                                      method="POST" class="inline"
                                      onsubmit="return confirm('Delete this package?');">
                                    @csrf @method('DELETE')
                                    <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">No packages found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $packages->links() }}</div>
@endsection
