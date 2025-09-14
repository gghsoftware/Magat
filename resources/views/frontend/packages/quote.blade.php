@extends('layouts.frontend')
@section('title', 'Payment - ' . $package->name)

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10"> {{-- wider container --}}
  <header class="mb-6">
    <h1 class="text-2xl md:text-3xl font-extrabold">Payment & Summary</h1>
    <p class="text-gray-600">Review your selections and choose your payment option.</p>
  </header>

  @if(session('success'))
    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-emerald-900">
      {{ session('success') }}
    </div>
  @endif

  @php
    $addons      = $addons ?? [];
    $items       = $items ?? [];
    $customItems = $customItems ?? [];
    $senior      = $senior ?? false;
    $payment     = $payment ?? 'dp30';
  @endphp

  {{-- Make the form bigger than summary on desktop --}}
  <div class="grid lg:grid-cols-5 gap-8">
    <!-- Summary (left, narrower) -->
    <div class="lg:col-span-2">
      <div class="bg-white rounded-2xl shadow p-6 lg:sticky lg:top-24">
        <h2 class="text-lg font-bold mb-4">Package Summary</h2>

        <dl class="text-sm space-y-3">
          <div class="flex justify-between">
            <dt>Package</dt>
            <dd class="font-semibold text-gray-800">{{ $package->name }}</dd>
          </div>
          <div class="flex justify-between">
            <dt>Base Price</dt>
            <dd class="font-semibold text-gray-800">₱{{ number_format($package->price) }}</dd>
          </div>

          @if(!empty($gardenUpgrade))
          <div class="flex justify-between">
            <dt>Garden Upgrade</dt>
            <dd class="font-semibold text-gray-800">+ ₱20,000</dd>
          </div>
          @endif

          @if(count($items))
            <div class="pt-2">
              <dt class="mb-1 font-semibold">Selected Inclusions</dt>
              <dd class="text-gray-700">
                <ul class="list-disc list-inside">
                  @foreach($items as $i)
                    <li>{{ $i['label'] }} × {{ $i['qty'] }}</li>
                  @endforeach
                </ul>
              </dd>
            </div>
          @endif

          @if(count($customItems))
            <div>
              <dt class="mb-1 font-semibold">Additional Items (for quotation)</dt>
              <dd class="text-gray-700">
                <ul class="list-disc list-inside">
                  @foreach($customItems as $i)
                    <li>{{ $i['label'] }} × {{ $i['qty'] }}</li>
                  @endforeach
                </ul>
              </dd>
            </div>
          @endif

          @if(!empty($isNinety) && count($addons))
            <div>
              <dt class="mb-1 font-semibold">Free Add-ons</dt>
              <dd class="text-gray-700">
                <ul class="list-disc list-inside">
                  @foreach($addons as $a)
                    <li>{{ ucfirst($a) }} — <span class="text-emerald-700 font-semibold">FREE</span></li>
                  @endforeach
                </ul>
              </dd>
            </div>
          @endif

          {{-- Visible Subtotal (matches #subtotal for JS) --}}
          <div class="flex justify-between pt-2 border-t">
            <dt>Subtotal</dt>
            <dd class="font-semibold text-gray-800">₱{{ number_format($subtotal) }}</dd>
          </div>
        </dl>
      </div>
    </div>

    <!-- Payment & Discounts (right, wider) -->
    <div class="lg:col-span-3">
      <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-lg font-bold mb-4">Payment Options</h2>

        <form method="POST"
            action="{{ route('frontend.packages.submit', $package->slug) }}"
            id="payForm"
            class="space-y-6"
            enctype="multipart/form-data"> {{-- ✅ needed for file upload --}}
        @csrf

          {{-- carry selections from previous step --}}
          <input type="hidden" name="garden_upgrade" value="{{ !empty($gardenUpgrade) ? 1 : 0 }}">
          @foreach($addons as $a)
            <input type="hidden" name="addons[]" value="{{ $a }}">
          @endforeach
          @foreach($items as $idx => $i)
            <input type="hidden" name="items[{{ $idx }}][label]" value="{{ $i['label'] }}">
            <input type="hidden" name="items[{{ $idx }}][qty]"   value="{{ $i['qty'] }}">
          @endforeach
          @foreach($customItems as $idx => $i)
            <input type="hidden" name="custom_items[{{ $idx }}][label]" value="{{ $i['label'] }}">
            <input type="hidden" name="custom_items[{{ $idx }}][qty]"   value="{{ $i['qty'] }}">
          @endforeach
          @if(!empty($summary))
            <input type="hidden" name="summary" value="{{ $summary }}">
          @endif

          <div class="space-y-3">
            <label class="flex items-center gap-3">
                <input type="checkbox" name="senior" id="senior" value="1"
                    class="h-5 w-5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-600"
                    @checked(old('senior', $senior))>
                <span>Senior citizen (−20% discount, present valid ID)</span>
            </label>

            {{-- Senior ID upload (revealed when checked) --}}
            <div id="seniorIdWrap"
                class="mt-2 rounded-lg border border-emerald-200 bg-emerald-50/60 p-4 @if(!old('senior', $senior)) hidden @endif">
                <label for="senior_id" class="block text-sm font-semibold mb-1">
                Upload Senior ID <span class="text-red-600">*</span>
                </label>
                <input type="file"
                    id="senior_id"
                    name="senior_id"
                    accept="image/*"
                    class="block w-full rounded-lg border border-emerald-200 bg-white file:mr-3 file:rounded-md file:border-0 file:bg-emerald-600 file:px-4 file:py-2 file:text-white hover:file:bg-emerald-700" />

                @error('senior_id')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                {{-- Tiny preview --}}
                <div id="seniorIdPreview" class="mt-3 hidden">
                <img src="" alt="Senior ID preview" class="h-28 w-auto rounded-md border" />
                </div>

                <p class="mt-2 text-xs text-emerald-900">
                Accepted: JPG/PNG/WEBP, up to 5MB.
                </p>
            </div>
            </div>


          <div>
            <div class="font-semibold mb-2">Choose payment</div>
            <label class="flex items-center gap-3 mb-2">
              <input type="radio" name="payment" value="full"
                     class="h-4 w-4 text-emerald-600 focus:ring-emerald-600"
                     @checked(old('payment', $payment) === 'full')>
              <span>Full Payment</span>
            </label>
            <label class="flex items-center gap-3">
              <input type="radio" name="payment" value="dp30"
                     class="h-4 w-4 text-emerald-600 focus:ring-emerald-600"
                     @checked(old('payment', $payment) === 'dp30')>
              <span>30% Downpayment</span>
            </label>
          </div>

          <!-- Live totals -->
          <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-sm">
            <div class="flex justify-between">
              <span>Subtotal</span>
              <span id="subtotal" data-amount="{{ $subtotal }}" class="font-semibold text-gray-800">
                ₱{{ number_format($subtotal) }}
              </span>
            </div>
            <div class="flex justify-between">
              <span>Discount</span>
              <span id="discount" data-amount="{{ $discount }}">− ₱{{ number_format($discount) }}</span>
            </div>
            <div class="flex justify-between font-semibold mt-1">
              <span>Total</span>
              <span id="total" data-amount="{{ $total }}">₱{{ number_format($total) }}</span>
            </div>
            <div class="flex justify-between mt-1">
              <span>Due now</span>
              <span id="dueNow" data-amount="{{ $dueNow }}" class="font-bold text-emerald-700">₱{{ number_format($dueNow) }}</span>
            </div>
            <div class="flex justify-between text-gray-600 mt-1">
              <span>Remaining balance</span>
              <span id="balance" data-amount="{{ $balance }}">₱{{ number_format($balance) }}</span>
            </div>
          </div>

          {{-- Validation errors (optional, nice UX) --}}
          @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-red-800 text-sm">
              <div class="font-semibold mb-2">Please fix the following:</div>
              <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <!-- Contact -->
          <div class="grid md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-1">Full Name</label>
              <input name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-gray-300 focus:ring-emerald-600" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Phone</label>
              <input name="phone" value="{{ old('phone') }}" required class="w-full rounded-lg border-gray-300 focus:ring-emerald-600" />
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-medium mb-1">Email (optional)</label>
              <input name="email" type="email" value="{{ old('email') }}" class="w-full rounded-lg border-gray-300 focus:ring-emerald-600" />
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-medium mb-1">Notes (optional)</label>
              <textarea name="notes" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-emerald-600">{{ old('notes') }}</textarea>
            </div>
          </div>

          <button type="submit"
                  class="w-full rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-3 font-semibold">
            Confirm & Submit
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  // --- existing totals code (unchanged) ---
  const fmt = n => new Intl.NumberFormat('en-PH').format(n);
  const el  = id => document.getElementById(id);

  const subtotalEl = el('subtotal');
  const subtotal   = +subtotalEl.dataset.amount;

  const senior     = document.getElementById('senior');
  const payRadios  = Array.from(document.querySelectorAll('input[name="payment"]'));

  function recompute(){
    const isSenior = !!(senior && senior.checked);
    const discount = isSenior ? Math.round(subtotal * 0.20) : 0;
    const total    = Math.max(0, subtotal - discount);
    const payment  = (payRadios.find(r=>r.checked)?.value) || 'dp30';
    const dueNow   = payment === 'dp30' ? Math.ceil(total * 0.30) : total;
    const balance  = total - dueNow;

    el('discount').textContent = `− ₱${fmt(discount)}`;
    el('total').textContent    = `₱${fmt(total)}`;
    el('dueNow').textContent   = `₱${fmt(dueNow)}`;
    el('balance').textContent  = `₱${fmt(balance)}`;
  }

  senior?.addEventListener('change', recompute);
  payRadios.forEach(r => r.addEventListener('change', recompute));
  recompute();

  // --- NEW: Senior ID toggle + required + preview ---
  const wrap   = document.getElementById('seniorIdWrap');
  const input  = document.getElementById('senior_id');
  const prev   = document.querySelector('#seniorIdPreview img');
  const prevBox= document.getElementById('seniorIdPreview');

  function toggleSeniorId(){
    const on = senior?.checked;
    if (!wrap || !input) return;
    wrap.classList.toggle('hidden', !on);
    input.required = !!on; // client-side requirement
    if (!on) {
      input.value = '';
      if (prevBox) { prevBox.classList.add('hidden'); }
    }
  }

  senior?.addEventListener('change', toggleSeniorId);
  toggleSeniorId(); // set on load based on old() or server value

  // preview
  input?.addEventListener('change', (e) => {
    const file = e.target.files?.[0];
    if (!file) { prevBox?.classList.add('hidden'); return; }
    const url = URL.createObjectURL(file);
    if (prev) prev.src = url;
    prevBox?.classList.remove('hidden');
  });
})();
</script>

@endpush
