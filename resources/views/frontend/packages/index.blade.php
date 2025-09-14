@extends('layouts.frontend')
@section('title', 'Packages - Magat Funeral')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
  <header class="text-center mb-8">
    <h1 class="text-3xl md:text-4xl font-extrabold mb-2">Service Packages</h1>
    <p class="text-gray-600">Fixed inclusions, simple choices, clear pricing.</p>
  </header>

  <!-- Policy banner (lighter emerald) -->
  <div class="mb-10 rounded-xl border border-emerald-200 bg-emerald-50 p-5">
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-3 text-sm text-emerald-900">
      <div class="flex items-start gap-2">
        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white shadow text-xs font-bold">₱</span>
        <div><span class="font-semibold">Payment:</span> Full or <span class="font-semibold">30% Downpayment</span></div>
      </div>
      <div class="flex items-start gap-2">
        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white shadow text-xs font-bold">👵</span>
        <div><span class="font-semibold">Senior</span> 20% discount (present valid ID)</div>
      </div>
      <div class="flex items-start gap-2">
        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white shadow text-xs font-bold">🌿</span>
        <div>Packages <span class="font-semibold">≤ ₱90,000</span>: Garden Upgrade <span class="font-semibold">+₱20,000</span></div>
      </div>
      <div class="flex items-start gap-2">
        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-white shadow text-xs font-bold">🎁</span>
        <div><span class="font-semibold">₱90,000 package</span>: Free add-ons (chairs/tables/tent)</div>
      </div>
    </div>
  </div>

  @php /* $packages provided by controller */ @endphp

  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
    @forelse($packages as $p)
      @php
        $isNinety  = (int)$p->price === 90000;
        $canUpgrade = (int)$p->price <= 90000;
      @endphp

      <article class="group relative rounded-2xl shadow-lg hover:shadow-2xl transition overflow-hidden bg-white border border-gray-100">
        <!-- header -->
        <div class="p-6 border-b bg-white">
          <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold">{{ $p->name }}</h3>
            @if($isNinety)
              <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 font-semibold">Free Add-ons</span>
            @elseif($canUpgrade)
              <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 font-semibold">Upgradeable</span>
            @endif
          </div>
          <p class="mt-3">
            <span class="text-3xl font-extrabold text-emerald-600">₱{{ number_format($p->price) }}</span>
            <span class="text-gray-500 text-sm"> / package</span>
          </p>
        </div>

        <!-- thumbnail -->
        <div class="aspect-[16/9] bg-gray-100">
          <img src="{{ asset($p->thumbnail ?? 'images/placeholder.jpg') }}"
               alt="{{ $p->name }}" class="w-full h-full object-cover">
        </div>

        <!-- inclusions preview -->
        <ul class="p-6 space-y-2 text-gray-700">
          @foreach(collect($p->inclusions ?? [])->take(5) as $inc)
            <li class="flex items-start gap-3">
              <span aria-hidden="true" class="mt-1 inline-block h-2 w-2 rounded-full bg-emerald-600"></span>
              <span>{{ $inc }}</span>
            </li>
          @endforeach
          @if(($p->inclusions && count($p->inclusions) > 5))
            <li class="text-gray-400">+ more</li>
          @endif
        </ul>

        <!-- rule chips -->
        <div class="px-6 -mt-2 mb-4 flex flex-wrap gap-2 text-xs">
          @if($isNinety)
            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 text-emerald-700 px-2.5 py-1 border border-emerald-200">🎁 Free add-ons</span>
          @endif
          @if($canUpgrade)
            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 text-emerald-700 px-2.5 py-1 border border-emerald-200">🌿 Garden +₱20k</span>
          @endif
          <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 text-emerald-700 px-2.5 py-1 border border-emerald-200">👵 Senior −20%</span>
          <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 text-emerald-700 px-2.5 py-1 border border-emerald-200">₱ Full or 30% DP</span>
        </div>

        <!-- footer -->
        <div class="p-6 pt-0">
          <div class="h-1 w-full rounded-full bg-gradient-to-r from-emerald-500 to-green-500 mb-5"></div>
          {{-- Inside packages index card footer --}}
            <div class="flex flex-col sm:flex-row gap-3">
            @auth
                <a href="{{ route('frontend.packages.show', $p->slug) }}"
                class="flex-1 text-center bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg font-semibold">
                Customize / Get Quote
                </a>
            @else
                <a href="{{ route('frontend.login', [
                        'reason' => 'customization',
                        'next'   => route('frontend.packages.show', $p->slug)
                    ]) }}"
                class="flex-1 text-center bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg font-semibold">
                Customize / Get Quote
                </a>
            @endauth

            <button type="button"
                class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-800 px-5 py-2.5 rounded-lg font-semibold"
                onclick="openPkgGallery('{{ $p->name }}', @js($p->gallery ?? []))">
                View Gallery
            </button>
            </div>
        </div>
      </article>
    @empty
      <div class="col-span-full bg-white rounded-2xl shadow p-10 text-center">
        <p class="text-gray-600">No packages available yet.</p>
      </div>
    @endforelse
  </div>
</div>

<!-- Gallery modal -->
<div id="pkgGallery" class="fixed inset-0 hidden items-center justify-center bg-black/50 z-50 p-4">
  <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full overflow-hidden">
    <div class="flex items-center justify-between p-4 border-b">
      <h3 id="pkgGalleryTitle" class="text-lg font-bold">Package Gallery</h3>
      <button class="text-gray-500" onclick="closePkgGallery()">✕</button>
    </div>
    <div id="pkgGalleryBody" class="p-4 grid grid-cols-2 md:grid-cols-3 gap-3"></div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function openPkgGallery(name, images){
    const modal = document.getElementById('pkgGallery');
    const body  = document.getElementById('pkgGalleryBody');
    const title = document.getElementById('pkgGalleryTitle');
    title.textContent = `${name} — Gallery`;
    body.innerHTML = '';
    (images || []).forEach(src => {
      const el = document.createElement('img');
      el.src = src?.startsWith?.('http') ? src : `/${src}`;
      el.alt = 'Inclusion';
      el.className = 'w-full h-40 object-cover rounded-lg';
      body.appendChild(el);
    });
    modal.classList.remove('hidden'); modal.classList.add('flex');
  }
  function closePkgGallery(){
    const modal = document.getElementById('pkgGallery');
    modal.classList.add('hidden'); modal.classList.remove('flex');
  }
</script>
@endpush
