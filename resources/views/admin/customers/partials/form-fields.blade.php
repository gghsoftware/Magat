@php
    $customer = $customer ?? new \App\Models\User;
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

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <label class="block">
        <span class="text-sm text-gray-700">Name</span>
        <input name="name" type="text" value="{{ old('name', $customer->name) }}"
               class="mt-1 w-full border rounded px-3 py-2" required>
    </label>

    <label class="block">
        <span class="text-sm text-gray-700">Email</span>
        <input name="email" type="email" value="{{ old('email', $customer->email) }}"
               class="mt-1 w-full border rounded px-3 py-2" required>
    </label>

    <label class="block">
        <span class="text-sm text-gray-700">Phone</span>
        <input name="phone" type="text" value="{{ old('phone', $customer->phone) }}"
               class="mt-1 w-full border rounded px-3 py-2">
    </label>

    <label class="block">
        <span class="text-sm text-gray-700">Address</span>
        <textarea name="address" rows="2"
                  class="mt-1 w-full border rounded px-3 py-2">{{ old('address', $customer->address) }}</textarea>
    </label>

    <label class="block md:col-span-2">
        <span class="text-sm text-gray-700">Password</span>
        <input name="password" type="password"
               class="mt-1 w-full border rounded px-3 py-2"
               placeholder="{{ $customer->exists ? 'Leave blank to keep current password' : '' }}">
    </label>

    <label class="block">
        <span class="text-sm text-gray-700">Status</span>
        <select name="status" class="mt-1 w-full border rounded px-3 py-2">
            <option value="active" {{ old('status', $customer->status) === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $customer->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </label>
</div>
