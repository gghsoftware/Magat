@extends('layouts.frontend')

@section('title', 'Your Cart')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <h1 class="text-2xl font-bold mb-6">Your Cart</h1>

    @if(session('status'))
        <div class="mb-4 rounded-lg bg-green-50 text-green-800 px-4 py-2 border border-green-200">
            {{ session('status') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 text-red-700 px-4 py-2 border border-red-200">
            {{ $errors->first() }}
        </div>
    @endif

    @if(empty($cart))
        <div class="bg-white rounded-xl border p-10 text-center text-gray-600">
            Your cart is empty.
        </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Items -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Row: Select all + Clear -->
            <div class="flex items-center justify-between bg-white rounded-xl border p-4">
                <label class="inline-flex items-center gap-2 text-sm">
                    <input id="selectAll" type="checkbox" class="rounded w-4 h-4">
                    <span class="font-medium">Select all</span>
                </label>

                <form method="POST" action="{{ route('frontend.cart.clear') }}">
                    @csrf @method('DELETE')
                    <button class="text-sm text-gray-500 hover:underline">Clear cart</button>
                </form>
            </div>

            @foreach($cart as $item)
            <div class="flex flex-wrap gap-4 bg-white rounded-xl border p-4 items-center" data-id="{{ $item['id'] }}" data-price="{{ (float)$item['price'] }}">
                <!-- Select -->
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" class="item-checkbox rounded w-4 h-4">
                </label>

                <!-- Image -->
                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                     class="w-20 h-20 object-cover rounded border">

                <!-- Info -->
                <div class="flex-1 min-w-[12rem]">
                    <div class="font-semibold">{{ $item['name'] }}</div>
                    <div class="text-gray-500 text-sm">₱{{ number_format($item['price'], 2) }}</div>
                </div>

                <!-- Qty form -->
                <form method="POST" action="{{ route('frontend.cart.update', $item['id']) }}" class="flex items-center gap-2 shrink-0">
                    @csrf @method('PATCH')
                    <input
                        type="number"
                        min="1"
                        name="qty"
                        value="{{ $item['qty'] }}"
                        class="w-20 rounded border-gray-300 px-2 py-1 item-qty"
                        aria-label="Quantity"
                    >
                    <button class="px-3 py-1 rounded border hover:bg-gray-50">Update</button>
                </form>

                <!-- Remove -->
                <form method="POST" action="{{ route('frontend.cart.remove', $item['id']) }}" class="shrink-0">
                    @csrf @method('DELETE')
                    <button class="px-3 py-1 rounded border hover:bg-gray-50">Remove</button>
                </form>
            </div>
            @endforeach
        </div>

        <!-- Right: Summary -->
        <aside class="bg-white rounded-xl border p-6 h-fit sticky top-24">
            <h2 class="font-semibold mb-4">Order Summary</h2>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-600">Items selected</dt>
                    <dd id="selectedCount" class="font-medium">0</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600">Selected subtotal</dt>
                    <dd id="selectedSubtotal" class="font-semibold">₱0.00</dd>
                </div>

                <hr class="my-3">

                <div class="flex justify-between text-xs">
                    <dt class="text-gray-500">Cart subtotal (all items)</dt>
                    <dd>₱{{ number_format($subtotal, 2) }}</dd>
                </div>
            </dl>

            {{-- Checkout with selected items only --}}
            <form id="selectionForm" method="GET" action="{{ route('frontend.checkout.show') }}" class="mt-5">
                <!-- JS will inject hidden inputs like: name="selected[]" value="ID" -->
                <button id="checkoutBtn"
                        type="submit"
                        class="w-full inline-flex justify-center rounded-lg bg-green-600 text-white px-4 py-2 font-semibold disabled:opacity-50 disabled:cursor-not-allowed hover:bg-green-700">
                    Proceed to Checkout
                </button>
                <p id="noSelectionHint" class="mt-2 text-xs text-gray-500 hidden">
                    Select at least one item to continue.
                </p>
            </form>

            <p class="text-xs text-gray-500 mt-3">
                Only the items you select will be included at checkout. You can still update quantities from the cart before checking out.
            </p>
        </aside>
    </div>
    @endif
</div>

@push('scripts')
<script>
(function(){
    const peso = (n) => `₱${Number(n).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;

    const selectAll   = document.getElementById('selectAll');
    const rows        = [...document.querySelectorAll('[data-id][data-price]')];
    const checkboxes  = rows.map(r => r.querySelector('.item-checkbox'));
    const qtyInputs   = rows.map(r => r.querySelector('.item-qty'));
    const subtotalEl  = document.getElementById('selectedSubtotal');
    const countEl     = document.getElementById('selectedCount');
    const form        = document.getElementById('selectionForm');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const hint        = document.getElementById('noSelectionHint');

    function getRowTotal(row){
        const price = parseFloat(row.dataset.price || '0');
        const qty   = parseInt(row.querySelector('.item-qty')?.value || '1', 10);
        return price * qty;
    }

    function syncSummary(){
        let selectedCount = 0;
        let selectedTotal = 0;

        // Clear previous hidden inputs
        [...form.querySelectorAll('input[name="selected[]"]')].forEach(el => el.remove());

        rows.forEach((row, idx) => {
            const cb = checkboxes[idx];
            if (cb?.checked) {
                selectedCount++;
                selectedTotal += getRowTotal(row);

                // Add hidden input for selected item ids
                const id = row.dataset.id;
                const hidden = document.createElement('input');
                hidden.type  = 'hidden';
                hidden.name  = 'selected[]';
                hidden.value = id;
                form.appendChild(hidden);
            }
        });

        countEl.textContent = selectedCount;
        subtotalEl.textContent = peso(selectedTotal);

        // Enable/disable checkout
        const any = selectedCount > 0;
        checkoutBtn.disabled = !any;
        hint.classList.toggle('hidden', any);

        // Maintain "select all" visual state
        if (checkboxes.length) {
            const allChecked = checkboxes.every(c => c.checked);
            const noneChecked = checkboxes.every(c => !c.checked);
            selectAll.indeterminate = !allChecked && !noneChecked;
            selectAll.checked = allChecked && !selectAll.indeterminate;
        }
    }

    // Events
    selectAll?.addEventListener('change', () => {
        checkboxes.forEach(c => c.checked = selectAll.checked);
        syncSummary();
    });

    checkboxes.forEach(c => c.addEventListener('change', syncSummary));

    // Recompute when qty fields change (use input for immediate feedback)
    qtyInputs.forEach(q => q?.addEventListener('input', () => {
        // Guard invalid values visually but don't block the form
        const v = parseInt(q.value || '1', 10);
        if (isNaN(v) || v < 1) q.value = 1;
        syncSummary();
    }));

    // Init (no items selected by default)
    syncSummary();
})();
</script>
@endpush
@endsection
