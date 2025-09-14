{{-- resources/views/admin/packages/partials/form-fields.blade.php --}}
@php
    /** @var \App\Models\Package|null $package */
    $package = $package ?? new \App\Models\Package(['is_active' => true]);
@endphp

@if ($errors->any())
    <div class="mb-4 rounded bg-red-50 text-red-800 px-4 py-3 border border-red-200">
        <ul class="list-disc ml-4 text-sm">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 gap-4">
    <label class="block">
        <span class="text-sm text-gray-700">Name</span>
        <input name="name" type="text"
               value="{{ old('name', $package->name) }}"
               class="mt-1 w-full border rounded px-3 py-2" required>
    </label>

    <label class="block">
        <span class="text-sm text-gray-700">Slug (optional)</span>
        <input name="slug" type="text"
               value="{{ old('slug', $package->slug) }}"
               class="mt-1 w-full border rounded px-3 py-2" placeholder="auto if left blank">
    </label>

    <label class="block">
        <span class="text-sm text-gray-700">Price (₱)</span>
        <input name="price" type="number" step="1" min="0"
               value="{{ old('price', $package->price) }}"
               class="mt-1 w-full border rounded px-3 py-2" required>
    </label>

    <label class="block">
        <span class="text-sm text-gray-700">Thumbnail (image)</span>
        <input name="thumbnail" type="file" accept="image/*" class="mt-1">
        @if(($package->thumbnail_url ?? false))
            <div class="mt-2">
                <img src="{{ $package->thumbnail_url }}" class="w-24 h-24 object-cover rounded">
            </div>
        @endif
    </label>

    {{-- Inclusions: JSON array or one-per-line --}}
    <label class="block">
        <span class="text-sm text-gray-700">Inclusions</span>
        <textarea name="inclusions" rows="4" class="mt-1 w-full border rounded px-3 py-2"
                  placeholder="One per line or JSON array">@if(is_array(old('inclusions', $package->inclusions))){{ implode("\n", old('inclusions', $package->inclusions)) }}@else{{ old('inclusions') }}@endif</textarea>
        <span class="text-xs text-gray-500">Tip: paste JSON like ["casket","hearse"] or type one per line.</span>
    </label>

    {{-- Gallery: multi-image upload + existing previews/removal --}}
    <label class="block">
        <span class="text-sm text-gray-700">Gallery Images</span>
        <input name="gallery_images[]" type="file" multiple accept="image/*" class="mt-1">
        <span class="block text-xs text-gray-500 mt-1">
            You can select multiple images. Max 2MB each (jpg, jpeg, png, webp).
        </span>

        @if(is_array($package->gallery) && count($package->gallery))
            <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                @foreach($package->gallery as $path)
                    <label class="block border rounded p-2 flex flex-col items-center gap-2">
                        <img src="{{ str_starts_with($path, 'http') ? $path : asset('storage/'.$path) }}"
                             class="w-24 h-24 object-cover rounded" alt="">
                        <div class="text-xs break-all text-center">{{ $path }}</div>
                        <div class="text-xs">
                            <input type="checkbox" name="remove_gallery[]" value="{{ $path }}" class="mr-1">
                            Remove
                        </div>
                    </label>
                @endforeach
            </div>
            <span class="block text-xs text-gray-500 mt-1">
                Checked images will be deleted on save.
            </span>
        @endif
    </label>

    <label class="inline-flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input name="is_active" type="checkbox" value="1" class="rounded"
               {{ old('is_active', (bool) ($package->is_active ?? true)) ? 'checked' : '' }}>
        <span class="text-sm text-gray-700">Active</span>
    </label>
</div>
