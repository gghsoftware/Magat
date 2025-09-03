@extends('layouts.frontend')

@section('title', 'Caskets - Magat Funeral')

@section('content')
@php
    $categories     = ['All', 'Wooden Caskets', 'Metal Caskets', 'Urns', 'Accessories'];
    $currentCategory= request('category', 'All');
    $q              = request('q');
    $sort           = request('sort', 'Newest');

    // Only filter when user actually provided values
    $minReq         = request()->filled('min_price') ? (int) request('min_price') : null;
    $maxReq         = request()->filled('max_price') ? (int) request('max_price') : null;

    // Display-only (don’t force filters)
    $minDisplay     = $minReq ?? 0;
    $maxDisplay     = $maxReq ?? 100000;

    $inStock        = request('in_stock');
    $outStock       = request('out_of_stock');
@endphp

<!-- Promo strip to Packages -->
<div class="bg-green-50 border-b border-green-100">
  <div class="max-w-7xl mx-auto px-6 py-3 text-sm flex items-center justify-between">
    <p class="text-green-900">
      Looking for complete arrangements? Explore our <span class="font-semibold">Service Packages</span> starting at
      <span class="font-bold">₱25,000</span>.
    </p>
    <a href="{{ route('frontend.home.index') }}#packages"
       class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg font-semibold">
       View Packages
       <span>❯</span>
    </a>
  </div>
</div>

<!-- Breadcrumb -->
<nav class="max-w-7xl mx-auto px-6 py-4 text-gray-600 text-sm" aria-label="Breadcrumb">
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('frontend.home.index') }}" class="hover:underline">Home</a>
        </li>
        <li aria-hidden="true">›</li>
        <li class="text-gray-800 font-semibold">Caskets</li>
    </ol>
</nav>

<!-- Page Header -->
<header class="text-center mt-4 mb-10">
    <h1 class="text-3xl md:text-4xl font-extrabold mb-3">Our Products</h1>
    <p class="text-gray-600 max-w-2xl mx-auto">Explore our selection of caskets and funeral products.</p>
</header>

<!-- Category Pills + Search + Sort / View -->
<div class="max-w-7xl mx-auto px-6 mb-6">
    <div class="flex flex-col lg:flex-row lg:items-center gap-4">
        <!-- Category Pills (scrollable on mobile) -->
        <div class="flex-1">
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
                @foreach($categories as $category)
                    <a href="{{ route('frontend.products.index', array_filter([
                            'category' => $category !== 'All' ? $category : null,
                            'q' => $q,
                            'sort' => $sort,
                            // array_filter() removes null, so these only pass if set
                            'min_price' => $minReq,
                            'max_price' => $maxReq,
                            'in_stock' => $inStock ? 'on' : null,
                            'out_of_stock' => $outStock ? 'on' : null,
                    ])) }}"
                        class="whitespace-nowrap px-4 py-2 rounded-full font-semibold
                                {{ $currentCategory === $category ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-800' }}">
                        {{ $category }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Search + Sort + View Toggle -->
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('frontend.products.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="category" value="{{ $currentCategory === 'All' ? '' : $currentCategory }}">

                {{-- Only include hidden min/max if actually set --}}
                @if(!is_null($minReq)) <input type="hidden" name="min_price" value="{{ $minReq }}"> @endif
                @if(!is_null($maxReq)) <input type="hidden" name="max_price" value="{{ $maxReq }}"> @endif

                @if($inStock) <input type="hidden" name="in_stock" value="on"> @endif
                @if($outStock) <input type="hidden" name="out_of_stock" value="on"> @endif

                <div class="relative">
                    <input type="text" name="q" value="{{ $q }}" placeholder="Search products..."
                           class="w-56 md:w-64 rounded-lg border border-gray-300 bg-white px-4 py-2 pr-9 focus:outline-none focus:ring-2 focus:ring-green-600">
                    <span class="pointer-events-none absolute right-3 top-2.5">🔎</span>
                </div>

                <select name="sort" class="rounded-lg border border-gray-300 px-3 py-2 focus:ring-green-600">
                    <option {{ $sort==='Newest' ? 'selected' : '' }}>Newest</option>
                    <option {{ $sort==='Price: Low to High' ? 'selected' : '' }}>Price: Low to High</option>
                    <option {{ $sort==='Price: High to Low' ? 'selected' : '' }}>Price: High to Low</option>
                </select>

                <button class="rounded-lg bg-green-600 text-white px-4 py-2 font-semibold">
                    Apply
                </button>
            </form>

            <!-- Grid/List toggle (purely visual classes) -->
            <div class="hidden md:flex items-center gap-1" aria-label="View options">
                <button id="gridBtn" class="px-3 py-2 rounded-lg bg-green-600 text-white">Grid</button>
                <button id="listBtn" class="px-3 py-2 rounded-lg bg-gray-200">List</button>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Layout -->
<div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-4 gap-8">

    <!-- Sidebar Filters -->
    <aside class="space-y-6 lg:sticky lg:top-28 self-start">
        <form method="GET" action="{{ route('frontend.products.index') }}" id="filtersForm" class="space-y-6">
            <input type="hidden" name="category" value="{{ $currentCategory === 'All' ? '' : $currentCategory }}">
            <input type="hidden" name="q" value="{{ $q }}">
            <input type="hidden" name="sort" value="{{ $sort }}">

            <!-- Price Range (Dual) -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Price Range</h3>

                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="text-sm text-gray-500">Min</label>
                        <input id="minInput" name="min_price" type="number" min="0" step="50"
                               value="{{ $minReq !== null ? $minReq : '' }}"
                               placeholder="0"
                               class="mt-1 w-full rounded-lg border-gray-300 px-3 py-2 focus:ring-green-600">
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Max</label>
                        <input id="maxInput" name="max_price" type="number" min="0" step="50"
                               value="{{ $maxReq !== null ? $maxReq : '' }}"
                               placeholder="100000"
                               class="mt-1 w-full rounded-lg border-gray-300 px-3 py-2 focus:ring-green-600">
                    </div>
                </div>

                <!-- Sliders (visual only; no name so they don't submit) -->
                <div class="flex items-center gap-3">
                    <input id="minRange" type="range" min="0" max="100000" value="{{ $minDisplay }}" class="w-full">
                    <input id="maxRange" type="range" min="0" max="100000" value="{{ $maxDisplay }}" class="w-full">
                </div>

                <div class="flex justify-between text-sm text-gray-500 mt-2">
                    <span id="minLabel">₱{{ number_format($minDisplay) }}</span>
                    <span id="maxLabel">₱{{ number_format($maxDisplay) }}</span>
                </div>
            </div>

            <!-- Availability -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Availability</h3>
                <label class="flex items-center gap-2 mb-2 cursor-pointer">
                    <input type="checkbox" name="in_stock" {{ $inStock ? 'checked' : '' }} class="rounded w-4 h-4">
                    <span class="text-gray-700">In Stock</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="out_of_stock" {{ $outStock ? 'checked' : '' }} class="rounded w-4 h-4">
                    <span class="text-gray-700">Out of Stock</span>
                </label>
            </div>

            <div class="flex gap-3">
                <button class="w-full rounded-lg bg-green-600 text-white px-4 py-2 font-semibold">Apply Filters</button>
                <a href="{{ route('frontend.products.index') }}" class="w-full text-center rounded-lg bg-gray-100 px-4 py-2 font-semibold">Clear</a>
            </div>
        </form>
    </aside>

    <!-- Products Grid -->
    <section class="lg:col-span-3">
        <!-- Results meta -->
        @php $hasPriceFilter = !is_null($minReq) || !is_null($maxReq); @endphp
        @if($q || $currentCategory !== 'All' || $inStock || $outStock || $hasPriceFilter)
            <div class="mb-4 text-sm text-gray-600">
                Showing results
                @if($q) for “<span class="font-semibold text-gray-800">{{ $q }}</span>” @endif
                @if($currentCategory !== 'All') in <span class="font-semibold text-gray-800">{{ $currentCategory }}</span> @endif
                @if($inStock || $outStock)
                    ({{ $inStock ? 'In Stock' : '' }}{{ $inStock && $outStock ? ' & ' : '' }}{{ $outStock ? 'Out of Stock' : '' }})
                @endif
                @if($hasPriceFilter)
                    | Price:
                    <span class="font-semibold text-gray-800">
                        ₱{{ number_format($minReq ?? 0) }} – ₱{{ number_format($maxReq ?? 100000) }}
                    </span>
                @endif
            </div>
        @endif

        <!-- Grid/List container -->
        <div id="productsWrap" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @forelse($products as $product)
                <article class="bg-white rounded-xl border border-gray-200 shadow-sm relative">
                    <!-- Badge (single) -->
                    @if($product->is_new)
                        <span class="absolute top-3 left-3 bg-green-600 text-white text-xs px-2 py-1 rounded-full z-10">New</span>
                    @elseif(!$product->in_stock)
                        <span class="absolute top-3 left-3 bg-red-600 text-white text-xs px-2 py-1 rounded-full z-10">Out of Stock</span>
                    @endif

                    <!-- Wishlist (UI only) -->
                    <button type="button" class="absolute top-3 right-3 z-10 rounded-full bg-white/90 p-2 shadow"
                            aria-label="Add to wishlist" onclick="toggleHeart(this)">
                        <span class="heart">♡</span>
                    </button>

                    {{-- Image (minimal, no hover) --}}
                    <a href="{{ route('frontend.products.show', $product->id) }}" class="block">
                        <div class="relative aspect-square bg-gray-100 overflow-hidden">
                            <img
                                src="{{ $product->image_src }}"
                                alt="{{ $product->name }}"
                                class="absolute inset-0 w-full h-full object-cover"
                                onerror="this.onerror=null;this.src='{{ asset('images/placeholder.jpg') }}';"
                            >
                            <div class="absolute inset-0 ring-1 ring-black/5"></div>
                        </div>
                    </a>

                    <!-- Details -->
                    <div class="p-4">
                        <h2 class="text-sm font-semibold mb-1 line-clamp-2">
                            <a href="{{ route('frontend.products.show', $product->id) }}" class="hover:underline">
                                {{ $product->name }}
                            </a>
                        </h2>

                        @php $rating = $product->rating ?? 5; @endphp
                        <div class="flex items-center gap-1 text-yellow-500 text-sm mb-2" aria-label="Rating: {{ $rating }} out of 5">
                            @for($i=1;$i<=5;$i++)
                                <span>{{ $i <= $rating ? '★' : '☆' }}</span>
                            @endfor
                            <span class="ml-1 text-gray-400">({{ $product->reviews_count ?? 0 }})</span>
                        </div>

                        <p class="text-xl font-bold text-green-700 mb-4">₱{{ number_format($product->price, 2) }}</p>

                        {{-- Actions: only Add to Cart (no hover styles) --}}
                        <div class="mt-3">
                            <form method="POST" action="{{ route('frontend.cart.add', $product->id) }}">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button
                                    type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-3 py-2 text-sm font-semibold">
                                    <span>Add to Cart</span>
                                    <span aria-hidden="true">＋</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <!-- Graceful empty state -->
                <div class="col-span-full bg-white rounded-2xl shadow p-10 text-center">
                    <p class="text-gray-600">No products found. Try adjusting your filters.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12 flex justify-center">
            {{ $products->links() }}
        </div>
    </section>
</div>

<!-- Newsletter / CTA -->
<section class="mt-16 bg-green-600 text-white py-12 rounded-xl text-center max-w-7xl mx-auto px-6">
    <h3 class="text-2xl font-bold mb-3">Subscribe for Updates</h3>
    <p class="mb-6">Get notified about new caskets and special offers.</p>
    <form action="#" class="flex flex-col sm:flex-row justify-center gap-3 max-w-md mx-auto">
        <input type="email" placeholder="Enter your email"
               class="px-4 py-2 rounded-lg text-gray-900 placeholder-white/80 w-full sm:flex-1">
        <button class="bg-white text-green-700 px-6 py-2 rounded-lg font-semibold">
            Subscribe
        </button>
    </form>
</section>

<!-- Quick View Modal -->
<div id="quickView" class="fixed inset-0 hidden items-center justify-center bg-black/50 z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full overflow-hidden">
        <div class="flex">
            <img id="qvImg" src="" alt="" class="w-1/2 h-72 object-cover hidden md:block">
            <div class="flex-1 p-6">
                <div class="flex items-start justify-between">
                    <h3 id="qvName" class="text-xl font-bold"></h3>
                    <button class="text-gray-500" onclick="closeQuickView()" aria-label="Close">✕</button>
                </div>
                <p id="qvPrice" class="text-lg font-semibold text-green-700 mt-2"></p>
                <p class="text-gray-600 mt-4">Dignified, high-quality selection. Contact us to learn more about options and availability.</p>
                <div class="mt-6 flex gap-3">
                    <a id="qvDetails" href="#" class="flex-1 text-center bg-green-600 text-white px-4 py-2 rounded-lg font-semibold">View Details</a>
                    <button class="px-4 py-2 rounded-lg border border-gray-300" onclick="closeQuickView()">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
/* ====== Wishlist (UI only) ====== */
function toggleHeart(btn){
    const span = btn.querySelector('.heart');
    const active = span.textContent === '♥';
    span.textContent = active ? '♡' : '♥';
}

/* ====== Dual Range -> Inputs sync (₱) ====== */
const minRange = document.getElementById('minRange');
const maxRange = document.getElementById('maxRange');
const minInput = document.getElementById('minInput');
const maxInput = document.getElementById('maxInput');
const minLabel = document.getElementById('minLabel');
const maxLabel = document.getElementById('maxLabel');

function clampRanges(){
    let minV = Math.min(parseInt(minRange.value), parseInt(maxRange.value) - 50);
    let maxV = Math.max(parseInt(maxRange.value), parseInt(minRange.value) + 50);
    minRange.value = minV; maxRange.value = maxV;
    minInput.value = minV; maxInput.value = maxV;
    minLabel.textContent = `₱${Number(minV).toLocaleString()}`;
    maxLabel.textContent = `₱${Number(maxV).toLocaleString()}`;
}
[minRange, maxRange].forEach(el => el?.addEventListener('input', clampRanges));
[minInput, maxInput].forEach(el => el?.addEventListener('input', () => {
    const minV = Math.max(0, parseInt(minInput.value||0));
    const maxV = Math.max(minV + 50, parseInt(maxInput.value||0));
    minRange.value = minV; maxRange.value = maxV;
    minLabel.textContent = `₱${Number(minV).toLocaleString()}`;
    maxLabel.textContent = `₱${Number(maxV).toLocaleString()}`;
}));

/* ====== Grid/List Toggle (keeps 2/3/4 density) ====== */
const gridBtn = document.getElementById('gridBtn');
const listBtn = document.getElementById('listBtn');
const productsWrap = document.getElementById('productsWrap');

gridBtn?.addEventListener('click', () => {
    productsWrap.className = 'grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5';
    gridBtn.classList.add('bg-green-600','text-white');
    listBtn.classList.remove('bg-green-600','text-white');
    listBtn.classList.add('bg-gray-200');
});
listBtn?.addEventListener('click', () => {
    productsWrap.className = 'grid grid-cols-1 gap-6';
    listBtn.classList.remove('bg-gray-200');
    listBtn.classList.add('bg-green-600','text-white');
    gridBtn.classList.remove('bg-green-600','text-white');
});

/* ====== Quick View ====== */
function openQuickView({id, name, price, image}){
    const modal = document.getElementById('quickView');
    document.getElementById('qvName').textContent = name;
    document.getElementById('qvPrice').textContent = `₱${price}`;
    document.getElementById('qvImg').src = image;
    document.getElementById('qvDetails').href = @js(route('frontend.products.show', ':id')).replace(':id', id);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeQuickView(){
    const modal = document.getElementById('quickView');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
window.openQuickView = openQuickView;
window.closeQuickView = closeQuickView;
</script>
@endpush

@endsection
