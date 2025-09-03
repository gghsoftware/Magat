
{{-- resources/views/admin/inventory/index.blade.php --}}
@extends('layouts.admin')

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

@section('title', 'Inventory Management')
@section('page-title', 'Inventory Management')

@section('content')
    {{-- Flash --}}
    @if(session('status'))
        <div class="mb-4 bg-green-50 text-green-800 border border-green-200 px-4 py-2 rounded">
            {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-50 text-red-700 border border-red-200 px-4 py-2 rounded">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-green-800">Inventory List</h2>
        <button
           onclick="document.getElementById('addItemModal').classList.remove('hidden')"
           class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
            + Add Item
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full table-auto border-collapse">
            <thead class="bg-green-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium">ID</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Item Name</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Category</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Stock</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Price</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Status</th>
                    <th class="px-6 py-3 text-center text-sm font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($products as $p)
                <tr class="hover:bg-gray-50">
                    @php
                        // Robust thumbnail URL that works in subfolders and with mixed image_url formats
                        $thumb = asset('images/placeholder.jpg');

                        if ($p->image_url) {
                            if (Str::startsWith($p->image_url, ['http://','https://'])) {
                                // Absolute (CDN/external)
                                $thumb = $p->image_url;
                            } else {
                                // Normalize any accidental leading slash or "storage/" prefix
                                $rel = ltrim($p->image_url, '/');                 // e.g. "storage/products/x.jpg" or "products/x.jpg"
                                if (Str::startsWith($rel, 'storage/')) {
                                    $rel = Str::after($rel, 'storage/');          // make it "products/x.jpg"
                                }

                                // Confirm the file exists on the "public" disk
                                if (Storage::disk('public')->exists($rel)) {
                                    // Use asset() so subfolder installs resolve to /<app>/storage/products/x.jpg
                                    $thumb = asset('storage/'.$rel);
                                }
                            }
                        }
                    @endphp

                    <td class="px-6 py-4 text-sm text-gray-700">{{ $p->id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900 font-semibold flex items-center gap-3">
                        <img src="{{ $p->image_src }}" alt="" class="w-10 h-10 rounded object-cover border border-gray-200">
                        {{ $p->name }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $p->category->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $p->stock }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">₱{{ number_format($p->price, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $p->status==='available' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                            {{ ucfirst($p->status) }}
                        </span>
                    </td>
                    @php
                        $payload = [
                            'id' => $p->id,
                            'name' => $p->name,
                            'category_id' => $p->category_id,
                            'status' => $p->status,
                            'description' => $p->description,
                            'price' => $p->price,
                            'stock' => $p->stock,
                            'image' => $p->image_src,   // 👈 use accessor
                        ];
                    @endphp
                    <td class="px-6 py-4 text-center space-x-2">
                        {{-- Edit --}}
                        <button
                            type="button"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm"
                            onclick='openEditModal(this)'
                            data-payload='@json($payload)'>
                            Edit
                        </button>

                        {{-- Delete --}}
                        <form action="{{ route('admin.inventory.destroy', $p->id) }}"
                            method="POST"
                            class="inline-block"
                            onsubmit="return confirm('Delete this product permanently?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500">No items yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>

    <!-- Add Item Modal -->
    <div id="addItemModal" class="hidden fixed inset-0 z-50 bg-black/50 p-4 md:p-8">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl mx-auto overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="text-lg font-semibold">Add Inventory Item</h3>
                <button class="text-gray-500 hover:text-gray-700"
                        onclick="document.getElementById('addItemModal').classList.add('hidden')">✕</button>
            </div>

            <form method="POST" action="{{ route('admin.inventory.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Name</label>
                    <input name="name" class="w-full border rounded px-3 py-2" required value="{{ old('name') }}">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Category</label>
                        <select name="category_id" class="w-full border rounded px-3 py-2" required>
                            <option value="">-- select --</option>
                            @foreach($categories as $id => $label)
                                <option value="{{ $id }}" @selected(old('category_id')==$id)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Status</label>
                        <select name="status" class="w-full border rounded px-3 py-2" required>
                            <option value="available"   @selected(old('status')==='available')>Available</option>
                            <option value="unavailable" @selected(old('status')==='unavailable')>Unavailable</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium">Description</label>
                    <textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Price (₱)</label>
                        <input type="number" step="0.01" min="0" name="price" class="w-full border rounded px-3 py-2" required value="{{ old('price') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Stock</label>
                        <input type="number" min="0" name="stock" class="w-full border rounded px-3 py-2" required value="{{ old('stock', 0) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Image</label>
                        <input type="file" name="image" accept="image/*" class="block w-full">
                        <p class="text-xs text-gray-500 mt-1">JPG/PNG/WEBP up to 4MB.</p>
                    </div>
                </div>

                <div class="pt-2 flex items-center gap-2">
                    <button class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded">
                        Save Item
                    </button>
                    <button type="button" onclick="document.getElementById('addItemModal').classList.add('hidden')"
                            class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-50">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Item Modal -->
<div id="editItemModal" class="hidden fixed inset-0 z-50 bg-black/50 p-4 md:p-8">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl mx-auto overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-lg font-semibold">Edit Inventory Item</h3>
            <button class="text-gray-500 hover:text-gray-700"
                    onclick="document.getElementById('editItemModal').classList.add('hidden')">✕</button>
        </div>

        <form id="editForm" method="POST" action="#" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium">Name</label>
                <input id="edit_name" name="name" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Category</label>
                    <select id="edit_category_id" name="category_id" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- select --</option>
                        @foreach($categories as $id => $label)
                            <option value="{{ $id }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Status</label>
                    <select id="edit_status" name="status" class="w-full border rounded px-3 py-2" required>
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium">Description</label>
                <textarea id="edit_description" name="description" rows="3" class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium">Price (₱)</label>
                    <input id="edit_price" type="number" step="0.01" min="0" name="price" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Stock</label>
                    <input id="edit_stock" type="number" min="0" name="stock" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Replace Image</label>
                    <input type="file" name="image" accept="image/*" class="block w-full">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image.</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <img id="edit_preview" src="" alt="" class="w-16 h-16 rounded object-cover border border-gray-200 hidden">
                <span id="edit_no_image" class="text-sm text-gray-500">No image</span>
            </div>

            <div class="pt-2 flex items-center gap-2">
                <button class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded">
                    Save Changes
                </button>
                <button type="button"
                        onclick="document.getElementById('editItemModal').classList.add('hidden')"
                        class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-50">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(btn){
    const data = JSON.parse(btn.dataset.payload);

    // Set form action to the update route
    const form = document.getElementById('editForm');
    form.action = "{{ route('admin.inventory.update', '__ID__') }}".replace('__ID__', data.id);

    // Fill inputs
    document.getElementById('edit_name').value          = data.name ?? '';
    document.getElementById('edit_category_id').value   = data.category_id ?? '';
    document.getElementById('edit_status').value        = data.status ?? 'available';
    document.getElementById('edit_description').value   = data.description ?? '';
    document.getElementById('edit_price').value         = data.price ?? 0;
    document.getElementById('edit_stock').value         = data.stock ?? 0;

    // Preview image
    const preview = document.getElementById('edit_preview');
    const noImg   = document.getElementById('edit_no_image');
    if (data.image) {
        preview.src = data.image;
        preview.classList.remove('hidden');
        noImg.classList.add('hidden');
    } else {
        preview.src = '';
        preview.classList.add('hidden');
        noImg.classList.remove('hidden');
    }

    // Show modal
    document.getElementById('editItemModal').classList.remove('hidden');
}
</script>

@endsection
