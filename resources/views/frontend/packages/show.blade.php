@extends('layouts.frontend')
@section('title', $package->name . ' - Customize')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
  <nav class="text-sm text-gray-500 mb-6">
    <a href="{{ route('frontend.packages.index') }}" class="hover:underline">Packages</a> ›
    <span class="text-gray-800 font-semibold">{{ $package->name }}</span>
  </nav>

  <div class="grid lg:grid-cols-2 gap-8">
    <!-- Gallery / Thumb -->
    <div class="bg-white rounded-2xl shadow overflow-hidden">
      <div class="aspect-[16/9] bg-gray-100">
        <img src="{{ asset($package->thumbnail ?? 'images/placeholder.jpg') }}" alt="{{ $package->name }}" class="w-full h-full object-cover">
      </div>
      @if($package->gallery && count($package->gallery))
        <div class="p-4 grid grid-cols-4 gap-3">
          @foreach($package->gallery as $g)
            <img src="{{ asset($g) }}" class="w-full h-20 object-cover rounded-lg border" alt="Inclusion">
          @endforeach
        </div>
      @endif

      <div class="p-6">
        <h2 class="text-xl font-bold mb-2">Inclusions</h2>

        @php
          // Normalize inclusions into [label, qty] pairs (extracts counts like “Chairs × 40”)
          $inclusionRows = [];
          foreach(($package->inclusions ?? []) as $inc){
            if (preg_match('/^(.*?)(?:\s*[x×]\s*(\d+))?$/iu', $inc, $m)) {
              $label = trim($m[1]);
              $qty   = isset($m[2]) ? (int)$m[2] : 1;
              $inclusionRows[] = ['label'=>$label, 'qty'=>$qty];
            } else {
              $inclusionRows[] = ['label'=>trim($inc), 'qty'=>1];
            }
          }
        @endphp

        <div class="mb-3 flex items-center justify-between">
          <p class="text-sm text-gray-600">Select items to include and adjust quantities as needed.</p>
          <div class="text-sm">
            <label class="inline-flex items-center gap-2 cursor-pointer">
              <input type="checkbox" id="toggleAll" class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-600" checked>
              <span>Select all</span>
            </label>
          </div>
        </div>

        <ul id="inclusionList" class="space-y-3 text-gray-700">
          @foreach($inclusionRows as $i => $row)
            <li class="flex items-center justify-between gap-3 border rounded-lg p-3">
              <label class="flex items-center gap-3 flex-1">
                <input type="checkbox" name="items[{{ $i }}][checked]" value="1"
                       class="h-5 w-5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-600 inc-check"
                       checked>
                <input type="hidden" name="items[{{ $i }}][label]" value="{{ $row['label'] }}" class="inc-label">
                <span class="font-medium">{{ $row['label'] }}</span>
              </label>

              <div class="flex items-center gap-2">
                <button type="button" class="qty-btn px-2 py-1 border rounded" aria-label="Decrease">−</button>
                <input type="number" min="1" name="items[{{ $i }}][qty]"
                       value="{{ $row['qty'] }}" class="w-16 text-center rounded border-gray-300 focus:ring-emerald-600 inc-qty">
                <button type="button" class="qty-btn px-2 py-1 border rounded" aria-label="Increase">+</button>
              </div>
            </li>
          @endforeach
        </ul>
      </div>
    </div>

    <!-- Configurator -->
    <div class="bg-white rounded-2xl shadow p-6">
      <header class="mb-6">
        <h1 class="text-2xl md:text-3xl font-extrabold">{{ $package->name }}</h1>
        <p class="text-emerald-700 text-3xl font-extrabold mt-2">₱{{ number_format($package->price) }}</p>
        <div class="mt-2 flex flex-wrap gap-2 text-xs">
          @if($isNinety)
            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 text-emerald-700 px-2.5 py-1 border border-emerald-200">🎁 Free add-ons</span>
          @endif
          @if($canUpgrade)
            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 text-emerald-700 px-2.5 py-1 border border-emerald-200">🌿 Garden +₱20k (≤ ₱90k)</span>
          @endif
          <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 text-emerald-700 px-2.5 py-1 border border-emerald-200">👵 Senior −20%</span>
          <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 text-emerald-700 px-2.5 py-1 border border-emerald-200">₱ Full or 30% DP</span>
        </div>
      </header>

      <form method="POST" action="{{ route('frontend.packages.quote', $package->slug) }}" id="configForm" class="space-y-6">
        @csrf

        {{-- Selected inclusions & custom items are part of THIS form; they’re above. --}}

        @if($canUpgrade)
        <div class="border rounded-lg p-4">
          <label class="flex items-start gap-3">
            <input type="checkbox" name="garden_upgrade" value="1"
                   class="mt-1 h-5 w-5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-600">
            <div>
              <div class="font-semibold">Garden Upgrade</div>
              <div class="text-sm text-gray-600">Add landscaped/full garden. <span class="font-semibold text-emerald-700">+₱20,000</span></div>
            </div>
          </label>
        </div>
        @endif

        @if($isNinety)
        <div class="border rounded-lg p-4">
          <div class="font-semibold mb-2">Free Add-ons (₱90,000 package)</div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
            <label class="flex items-center gap-2">
              <input type="checkbox" name="addons[]" value="chairs" class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-600">
              <span>Extra Chairs</span>
            </label>
            <label class="flex items-center gap-2">
              <input type="checkbox" name="addons[]" value="tables" class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-600">
              <span>Extra Tables</span>
            </label>
            <label class="flex items-center gap-2">
              <input type="checkbox" name="addons[]" value="tent" class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-600">
              <span>Additional Tent</span>
            </label>
          </div>
          <p class="text-xs text-gray-500 mt-2">These add-ons are free only for the ₱90,000 package.</p>
        </div>
        @endif

        <!-- Custom items -->
        <div class="border rounded-lg p-4">
          <div class="font-semibold mb-3">Add More Items</div>
          <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[220px]">
              <label class="block text-sm text-gray-600 mb-1">Item name</label>
              <input type="text" id="customLabel" class="w-full rounded-lg border-gray-300 focus:ring-emerald-600" placeholder="e.g., White balloons">
            </div>
            <div>
              <label class="block text-sm text-gray-600 mb-1">Qty</label>
              <input type="number" id="customQty" min="1" value="1" class="w-24 rounded-lg border-gray-300 text-center focus:ring-emerald-600">
            </div>
            <button type="button" id="addCustom" class="h-10 px-4 rounded-lg bg-emerald-600 text-white font-semibold hover:bg-emerald-700">
              Add item
            </button>
          </div>

          <ul id="customList" class="mt-3 space-y-2 text-sm"></ul>
        </div>

        <!-- Live description & hidden summary -->
        <div>
          <label class="block text-sm font-semibold mb-2">Description / Summary (auto-generated)</label>
          <textarea id="summaryText" class="w-full rounded-lg border-gray-300 focus:ring-emerald-600 text-sm" rows="5" readonly></textarea>
          <input type="hidden" name="summary" id="summary">
        </div>

        <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-900">
          <div class="font-semibold mb-1">Notes</div>
          <ul class="list-disc list-inside space-y-1">
            <li>Senior citizens may avail 20% discount (present valid ID) — applied on next step.</li>
            <li>Choose Full payment or 30% downpayment on the next step.</li>
            <li>Any added items are subject to confirmation/quotation unless marked free.</li>
          </ul>
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-3 font-semibold">
          Continue to Payment
        </button>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
  const $ = sel => document.querySelector(sel);
  const $$ = sel => Array.from(document.querySelectorAll(sel));

  // Toggle all inclusions
  const toggleAll = $('#toggleAll');
  toggleAll?.addEventListener('change', () => {
    $$('.inc-check').forEach(ch => ch.checked = toggleAll.checked);
    buildSummary();
  });

  // Quantity +/- buttons
  $$('.qty-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const wrapper = btn.closest('li');
      const input = wrapper.querySelector('.inc-qty');
      const dec = btn.textContent.trim() === '−';
      const next = Math.max(1, (parseInt(input.value || '1', 10) + (dec ? -1 : 1)));
      input.value = next;
      buildSummary();
    });
  });

  // React to direct edits
  $$('.inc-check, .inc-qty').forEach(el => el.addEventListener('input', buildSummary));

  // Custom items add/remove
  const customList = $('#customList');
  $('#addCustom')?.addEventListener('click', () => {
    const label = $('#customLabel').value.trim();
    const qty   = Math.max(1, parseInt($('#customQty').value || '1', 10));
    if(!label) return;

    const idx = customList.querySelectorAll('li').length;
    const li = document.createElement('li');
    li.className = 'flex items-center justify-between gap-2 bg-gray-50 border rounded p-2';

    li.innerHTML = `
      <div class="flex items-center gap-2">
        <span class="inline-block h-2 w-2 rounded-full bg-emerald-600"></span>
        <span class="font-medium">${label}</span>
        <span class="text-gray-600">×</span>
        <input type="number" name="custom_items[${idx}][qty]" min="1" value="${qty}" class="w-16 text-center rounded border-gray-300 focus:ring-emerald-600 custom-qty">
        <input type="hidden" name="custom_items[${idx}][label]" value="${label}" class="custom-label">
      </div>
      <button type="button" class="text-red-600 hover:underline remove-custom">Remove</button>
    `;
    customList.appendChild(li);
    $('#customLabel').value = '';
    $('#customQty').value = '1';
    buildSummary();
  });

  customList?.addEventListener('click', (e) => {
    if(e.target.classList.contains('remove-custom')){
      e.target.closest('li')?.remove();
      // Reindex names to keep them compact
      Array.from(customList.querySelectorAll('li')).forEach((li, i) => {
        li.querySelectorAll('input').forEach(input => {
          input.name = input.name.replace(/custom_items\[\d+\]/, `custom_items[${i}]`);
        });
      });
      buildSummary();
    }
  });

  customList?.addEventListener('input', (e) => {
    if(e.target.classList.contains('custom-qty')) buildSummary();
  });

  // Build summary text
  function buildSummary(){
    const lines = [];
    // Inclusions
    const incs = $$('#inclusionList li').map(li => {
      const chk = li.querySelector('.inc-check');
      const label = li.querySelector('.inc-label').value;
      const qty = parseInt(li.querySelector('.inc-qty').value || '1', 10);
      return chk.checked ? {label, qty} : null;
    }).filter(Boolean);

    if(incs.length){
      lines.push('Inclusions:');
      incs.forEach(i => lines.push(` • ${i.label} × ${i.qty}`));
    }

    // Custom items
    const customs = Array.from(customList.querySelectorAll('li')).map(li => {
      const label = li.querySelector('.custom-label').value;
      const qty = parseInt(li.querySelector('.custom-qty').value || '1', 10);
      return {label, qty};
    });
    if(customs.length){
      lines.push('Additional items:');
      customs.forEach(i => lines.push(` • ${i.label} × ${i.qty}`));
    }

    // Garden & Add-ons (visual hint only; server reads real fields)
    const garden = document.querySelector('input[name="garden_upgrade"]')?.checked;
    if(garden) lines.push('Garden upgrade: YES (+₱20,000)');

    const addons = Array.from(document.querySelectorAll('input[name="addons[]"]:checked')).map(i => i.value);
    if(addons.length){
      lines.push('Free add-ons:');
      addons.forEach(a => lines.push(` • ${a}`));
    }

    const text = lines.join('\n');
    const summaryText = document.getElementById('summaryText');
    const summaryHidden = document.getElementById('summary');
    summaryText.value = text;
    summaryHidden.value = text;
  }

  // Initial
  buildSummary();
})();
</script>
@endpush
