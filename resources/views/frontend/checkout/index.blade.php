@extends('layouts.frontend')

@section('title', 'Checkout')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-10">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Form -->
        <section class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border p-6">
                <h2 class="font-semibold mb-4">Customer Information</h2>

                <form id="checkoutForm" method="POST" action="{{ route('frontend.checkout.store') }}" class="space-y-6">
                    @csrf

                    {{-- If you came from the cart with selected[] ids, keep them --}}
                    @foreach((array) request('selected', []) as $sid)
                        <input type="hidden" name="selected[]" value="{{ $sid }}">
                    @endforeach

                    <!-- Contact -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="customer_name">
                                Full Name <span class="text-red-600">*</span>
                            </label>
                            <input
                                id="customer_name"
                                name="customer_name"
                                value="{{ old('customer_name') }}"
                                class="w-full rounded-lg border-gray-300 px-3 py-2 focus:ring-2 focus:ring-green-600"
                                placeholder="Juan Dela Cruz"
                                autocomplete="name"
                                required
                            >
                            @error('customer_name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="customer_email">
                                Email <span class="text-red-600">*</span>
                            </label>
                            <input
                                id="customer_email"
                                type="email"
                                name="customer_email"
                                value="{{ old('customer_email') }}"
                                class="w-full rounded-lg border-gray-300 px-3 py-2 focus:ring-2 focus:ring-green-600"
                                placeholder="you@example.com"
                                autocomplete="email"
                                required
                            >
                            @error('customer_email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="customer_phone">
                                Phone (optional)
                            </label>
                            <input
                                id="customer_phone"
                                name="customer_phone"
                                value="{{ old('customer_phone') }}"
                                class="w-full rounded-lg border-gray-300 px-3 py-2 focus:ring-2 focus:ring-green-600"
                                placeholder="09XX-XXX-XXXX"
                                inputmode="tel"
                                pattern="[0-9+\-\s()]*"
                                autocomplete="tel"
                            >
                            @error('customer_phone')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address (optional but useful) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="address_line1">
                                Address (optional)
                            </label>
                            <input
                                id="address_line1"
                                name="address_line1"
                                value="{{ old('address_line1') }}"
                                class="w-full rounded-lg border-gray-300 px-3 py-2 focus:ring-2 focus:ring-green-600"
                                placeholder="House/Street/Barangay"
                                autocomplete="address-line1"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="city">
                                City/Municipality (optional)
                            </label>
                            <input
                                id="city"
                                name="city"
                                value="{{ old('city') }}"
                                class="w-full rounded-lg border-gray-300 px-3 py-2 focus:ring-2 focus:ring-green-600"
                                placeholder="City/Municipality"
                                autocomplete="address-level2"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="province">
                                Province (optional)
                            </label>
                            <input
                                id="province"
                                name="province"
                                value="{{ old('province') }}"
                                class="w-full rounded-lg border-gray-300 px-3 py-2 focus:ring-2 focus:ring-green-600"
                                placeholder="Province"
                                autocomplete="address-level1"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="postal_code">
                                Postal Code (optional)
                            </label>
                            <input
                                id="postal_code"
                                name="postal_code"
                                value="{{ old('postal_code') }}"
                                class="w-full rounded-lg border-gray-300 px-3 py-2 focus:ring-2 focus:ring-green-600"
                                placeholder="e.g. 1000"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                autocomplete="postal-code"
                            >
                        </div>
                    </div>

                    <!-- Payment Plan -->
                    <div class="pt-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Option</label>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="flex items-start gap-3 rounded-lg border p-3 cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_plan" value="full" class="mt-1" {{ old('payment_plan', 'full') === 'full' ? 'checked' : '' }}>
                                <div>
                                    <div class="font-semibold">Full Payment</div>
                                    <div class="text-xs text-gray-500">Pay 100% upfront.</div>
                                    <div class="text-xs text-gray-700 mt-1">
                                        Due today: <span class="font-semibold" id="dueFull">₱0.00</span>
                                    </div>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 rounded-lg border p-3 cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_plan" value="two" class="mt-1" {{ old('payment_plan') === 'two' ? 'checked' : '' }}>
                                <div>
                                    <div class="font-semibold">2 Payments</div>
                                    <div class="text-xs text-gray-500">50% now, 50% next month.</div>
                                    <div class="text-xs text-gray-700 mt-1 space-y-0.5">
                                        <div>Due today: <span class="font-semibold" id="dueTwoNow">₱0.00</span></div>
                                        <div>Next: <span id="dueTwoNext" class="font-semibold">₱0.00</span> <span class="text-gray-500" id="dateTwoNext"></span></div>
                                    </div>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 rounded-lg border p-3 cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_plan" value="three" class="mt-1" {{ old('payment_plan') === 'three' ? 'checked' : '' }}>
                                <div>
                                    <div class="font-semibold">3 Payments</div>
                                    <div class="text-xs text-gray-500">Split equally over 3 months.</div>
                                    <div class="text-xs text-gray-700 mt-1 space-y-0.5">
                                        <div>Due today: <span class="font-semibold" id="dueThreeNow">₱0.00</span></div>
                                        <div>Next 1: <span id="dueThreeNext1" class="font-semibold">₱0.00</span> <span class="text-gray-500" id="dateThree1"></span></div>
                                        <div>Next 2: <span id="dueThreeNext2" class="font-semibold">₱0.00</span> <span class="text-gray-500" id="dateThree2"></span></div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        @error('payment_plan')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="notes">
                            Notes (optional)
                        </label>
                        <textarea
                            id="notes"
                            name="notes"
                            rows="3"
                            class="w-full rounded-lg border-gray-300 px-3 py-2 focus:ring-2 focus:ring-green-600"
                            placeholder="Special instructions or requests…"
                        >{{ old('notes') }}</textarea>
                    </div>

                    <!-- Terms -->
                    <label class="flex items-start gap-3 text-sm">
                        <input type="checkbox" name="agree" value="1" class="mt-1 rounded" {{ old('agree') ? 'checked' : '' }} required>
                        <span>By placing this order, I confirm the information is correct and I agree to the payment schedule and service terms.</span>
                    </label>
                    @error('agree')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Submit -->
                    <button id="placeOrderBtn" class="w-full md:w-auto rounded-lg bg-green-600 text-white px-6 py-2 font-semibold hover:bg-green-700">
                        Place Order
                    </button>
                </form>
            </div>

            <!-- Installment policy note -->
            <div class="bg-white rounded-xl border p-6 text-xs text-gray-600">
                <strong>Installment policy (Magat Funeral):</strong>
                Maaari kayong pumili ng buo, 2 hulog, o 3 hulog. Ang unang hulog ay due agad, at ang susunod ay kada buwan.
                Makikipag-ugnayan ang Magat sa inyo para sa schedule at resibo, para klaro ang kasunduan at protected ang parehong panig.
            </div>
        </section>

        <!-- Right: Summary -->
        <aside class="bg-white rounded-xl border p-6 space-y-4 h-fit lg:sticky lg:top-24">
            <h2 class="font-semibold">Order Summary</h2>

            {{-- If cart was filtered by selection, controller should pass only selected items.
                 Otherwise we show full cart. --}}
            <ul class="divide-y">
                @foreach($cart as $i)
                <li class="py-3 flex items-center gap-3">
                    <img src="{{ $i['image'] }}" class="w-12 h-12 rounded border object-cover" alt="">
                    <div class="flex-1">
                        <div class="text-sm font-medium">{{ $i['name'] }}</div>
                        <div class="text-xs text-gray-500">Qty: {{ $i['qty'] }}</div>
                    </div>
                    <div class="text-sm font-semibold">₱{{ number_format($i['price']*$i['qty'], 2) }}</div>
                </li>
                @endforeach
            </ul>

            <div class="flex justify-between text-sm">
                <span>Subtotal</span>
                <span id="summarySubtotal" data-subtotal="{{ (float) $subtotal }}">₱{{ number_format($subtotal, 2) }}</span>
            </div>

            <hr>

            <!-- Live payment breakdown (mirrors selection above) -->
            <div class="text-sm space-y-1">
                <div class="flex justify-between">
                    <span>Amount due today</span>
                    <span id="amountDueToday" class="font-semibold">₱0.00</span>
                </div>
                <div id="nextPayments" class="text-xs text-gray-600 space-y-0.5"></div>
            </div>
        </aside>
    </div>
</div>

@push('scripts')
<script>
(function(){
    const peso = n => `₱${Number(n).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2})}`;
    const subtotal = Number(document.getElementById('summarySubtotal')?.dataset.subtotal || 0);

    // Elements in the plan cards
    const dueFull      = document.getElementById('dueFull');
    const dueTwoNow    = document.getElementById('dueTwoNow');
    const dueTwoNext   = document.getElementById('dueTwoNext');
    const dateTwoNext  = document.getElementById('dateTwoNext');
    const dueThreeNow  = document.getElementById('dueThreeNow');
    const dueThreeNext1= document.getElementById('dueThreeNext1');
    const dueThreeNext2= document.getElementById('dueThreeNext2');
    const dateThree1   = document.getElementById('dateThree1');
    const dateThree2   = document.getElementById('dateThree2');

    // Summary box mirrors
    const amountDueToday = document.getElementById('amountDueToday');
    const nextPayments   = document.getElementById('nextPayments');

    // Date helpers
    function fmtDate(d){
        return d.toLocaleDateString(undefined, { month:'short', day:'numeric', year:'numeric' });
    }
    function addMonths(base, n){
        const d = new Date(base.getTime());
        d.setMonth(d.getMonth() + n);
        return d;
    }

    // Pre-calc amounts
    const half   = subtotal / 2;
    const third  = subtotal / 3;

    // Fill plan cards
    if (dueFull)      dueFull.textContent       = peso(subtotal);
    if (dueTwoNow)    dueTwoNow.textContent     = peso(half);
    if (dueTwoNext)   dueTwoNext.textContent    = peso(half);
    if (dueThreeNow)  dueThreeNow.textContent   = peso(third);
    if (dueThreeNext1)dueThreeNext1.textContent = peso(third);
    if (dueThreeNext2)dueThreeNext2.textContent = peso(third);

    const today = new Date();
    if (dateTwoNext)  dateTwoNext.textContent  = `• ${fmtDate(addMonths(today, 1))}`;
    if (dateThree1)   dateThree1.textContent   = `• ${fmtDate(addMonths(today, 1))}`;
    if (dateThree2)   dateThree2.textContent   = `• ${fmtDate(addMonths(today, 2))}`;

    function currentPlan(){
        const checked = document.querySelector('input[name="payment_plan"]:checked');
        return checked ? checked.value : 'full';
    }

    function renderSummary(){
        nextPayments.innerHTML = '';
        switch(currentPlan()){
            case 'full':
                amountDueToday.textContent = peso(subtotal);
                break;
            case 'two':
                amountDueToday.textContent = peso(half);
                nextPayments.innerHTML = `
                    <div>Next: <span class="font-medium">${peso(half)}</span> • ${fmtDate(addMonths(today, 1))}</div>
                `;
                break;
            case 'three':
                amountDueToday.textContent = peso(third);
                nextPayments.innerHTML = `
                    <div>Next 1: <span class="font-medium">${peso(third)}</span> • ${fmtDate(addMonths(today, 1))}</div>
                    <div>Next 2: <span class="font-medium">${peso(third)}</span> • ${fmtDate(addMonths(today, 2))}</div>
                `;
                break;
        }
    }

    // Bind changes
    document.querySelectorAll('input[name="payment_plan"]').forEach(r => {
        r.addEventListener('change', renderSummary);
    });

    // Initial
    renderSummary();

    // Prevent double submit UX
    const form = document.getElementById('checkoutForm');
    const btn  = document.getElementById('placeOrderBtn');
    form?.addEventListener('submit', () => {
        btn.disabled = true;
        btn.classList.add('opacity-70','cursor-not-allowed');
    });
})();
</script>
@endpush
@endsection
