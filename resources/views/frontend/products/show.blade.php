@extends('layouts.frontend')

@section('title', $product->name.' - Magat Funeral')

@section('content')
@php
    $img = $product->image_url ? asset('storage/'.$product->image_url) : asset('images/placeholder.jpg');
    $rating = $product->avg_rating ?? 5;
    $reviewsCount = $product->reviews_count ?? 0;
@endphp

<!-- Breadcrumb -->
<nav class="max-w-7xl mx-auto px-6 py-4 text-gray-600 text-sm" aria-label="Breadcrumb">
    <ol class="flex items-center gap-2">
        <li><a href="{{ route('frontend.home.index') }}" class="hover:underline">Home</a></li>
        <li aria-hidden="true">›</li>
        <li><a href="{{ route('frontend.products.index') }}" class="hover:underline">Products</a></li>
        <li aria-hidden="true">›</li>
        <li class="text-gray-800 font-semibold line-clamp-1">{{ $product->name }}</li>
    </ol>
</nav>

<div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-10 mt-4">
    <!-- Gallery -->
    <section>
        <div class="relative aspect-square bg-gray-100 rounded-xl overflow-hidden border border-gray-200">
            <img
                id="mainImg"
                src="{{ $img }}"
                alt="{{ $product->name }}"
                class="absolute inset-0 w-full h-full object-cover"
                onerror="this.onerror=null;this.src='{{ asset('images/placeholder.jpg') }}';"
            >
        </div>

        {{-- If you ever add more images, render thumbs here --}}
        @if(!empty($product->gallery) && is_array($product->gallery))
        <div class="mt-4 grid grid-cols-5 gap-3">
            @foreach($product->gallery as $g)
                @php
                    $thumb = $g ? asset('storage/'.$g) : asset('images/placeholder.jpg');
                @endphp
                <button
                    class="relative aspect-square bg-white border border-gray-200 rounded-lg overflow-hidden hover:border-gray-300"
                    onclick="document.getElementById('mainImg').src='{{ $thumb }}'">
                    <img src="{{ $thumb }}" alt="" class="w-full h-full object-cover">
                </button>
            @endforeach
        </div>
        @endif
    </section>

    <!-- Details -->
    <section class="flex flex-col">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $product->name }}</h1>

        <div class="mt-2 flex items-center gap-2 text-yellow-500">
            @for($i=1;$i<=5;$i++)
                <span>{{ $i <= $rating ? '★' : '☆' }}</span>
            @endfor
            <span class="text-sm text-gray-500">({{ $reviewsCount }} reviews)</span>
        </div>

        <p class="mt-4 text-3xl font-extrabold text-green-700">₱{{ number_format($product->price, 2) }}</p>

        <div class="mt-2">
            @if($product->status === 'available' && $product->stock > 0)
                <span class="inline-flex items-center gap-2 text-green-700 text-sm">
                    <span class="inline-block w-2 h-2 rounded-full bg-green-600"></span>
                    In stock ({{ $product->stock }})
                </span>
            @else
                <span class="inline-flex items-center gap-2 text-gray-600 text-sm">
                    <span class="inline-block w-2 h-2 rounded-full bg-gray-400"></span>
                    Unavailable
                </span>
            @endif
        </div>

        <div class="mt-6 prose max-w-none text-gray-700">
            {!! nl2br(e($product->description)) !!}
        </div>

        <div class="mt-6 flex flex-col sm:flex-row gap-3">
            @if($product->status === 'available' && $product->stock > 0)
                <form action="{{ route('frontend.cart.add', $product->id) }}" method="POST" class="flex items-center gap-3">
                    @csrf
                    <input type="number" name="qty" min="1" value="1" class="w-20 border rounded-lg px-3 py-2" />
                    <button class="inline-flex items-center justify-center rounded-lg bg-green-600 hover:bg-green-700 text-white px-6 py-3 font-semibold shadow-sm">
                        Add to Cart
                    </button>
                </form>
            @else
                <button class="inline-flex items-center justify-center rounded-lg bg-gray-200 text-gray-700 px-6 py-3 font-semibold cursor-not-allowed">
                    Out of Stock
                </button>
            @endif

            <a href="{{ route('frontend.products.index') }}"
               class="inline-flex items-center justify-center rounded-lg border border-gray-300 hover:bg-gray-50 px-6 py-3 font-semibold">
                Back to Products
            </a>
        </div>

        {{-- (Optional) Key points --}}
        <ul class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-gray-600">
            <li class="flex items-center gap-2">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-green-600"></span>
                Quality craftsmanship
            </li>
            <li class="flex items-center gap-2">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-green-600"></span>
                Gentle, minimal design
            </li>
        </ul>
    </section>
</div>

{{-- Related products (simple) --}}
@if($related->count())
<section class="max-w-7xl mx-auto px-6 mt-16">
    <h2 class="text-xl font-bold mb-4">You may also like</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($related as $r)
            @php
                $rimg = $r->image_url ? asset('storage/'.$r->image_url) : asset('images/placeholder.jpg');
            @endphp
            <a href="{{ route('frontend.products.show', $r->id) }}" class="group block rounded-xl border border-gray-200 hover:border-gray-300 overflow-hidden bg-white">
                <div class="relative aspect-square bg-gray-100">
                    <img src="{{ $rimg }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                         alt="{{ $r->name }}" loading="lazy"
                         onerror="this.onerror=null;this.src='{{ asset('images/placeholder.jpg') }}';">
                    <div class="absolute inset-0 ring-1 ring-black/5"></div>
                </div>
                <div class="p-4">
                    <div class="line-clamp-2 font-semibold text-gray-900">{{ $r->name }}</div>
                    <div class="mt-1 text-green-700 font-bold">₱{{ number_format($r->price, 2) }}</div>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif
@endsection
