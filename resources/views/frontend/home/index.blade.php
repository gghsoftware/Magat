@extends('layouts.frontend')

@section('title', 'Home - Magat Funeral')

@push('styles')
<style>
  /* --- Carousel --- */
  .carousel { --h: min(100vh, 720px); }
  .carousel-viewport { position: relative; height: var(--h); overflow: hidden; }

  /* Track must be flex, full height, and have a transform transition */
  .carousel-track {
    display: flex;
    height: 100%;
    will-change: transform;
    transition: transform 600ms ease;
  }

  /* Each slide should be exactly one viewport wide */
  .carousel-slide {
    flex: 0 0 100%;
    height: 100%;
    position: relative;
    content-visibility: auto;
  }

  /* Ensure images fill their slides */
  .carousel-slide img,
  .carousel-slide picture { width: 100%; height: 100%; object-fit: cover; display: block; }

  /* Don’t let overlays eat clicks on controls */
  .carousel-overlay,
  .carousel-cta { pointer-events: none; }
  .carousel-btn,
  .carousel-dots { pointer-events: auto; }

  .carousel-overlay { position: absolute; inset: 0; background: rgba(0,0,0,.4); z-index: 10; pointer-events: none; }
  .carousel-cta{ position: absolute; inset: 0; z-index: 20; pointer-events: auto; }

  /* Controls */
  .carousel-btn { 
    position: absolute; top: 50%; transform: translateY(-50%);
    z-index: 50; /* was 30 */
    padding: .6rem .9rem; border-radius: 9999px; background: rgba(0,0,0,.45); color: #fff;
    border: 0; cursor: pointer; backdrop-filter: blur(2px);
  }

  .carousel-viewport { touch-action: pan-y; }

  .carousel-btn:hover { background: rgba(0,0,0,.6); }
  .carousel-prev { left: 1rem; } .carousel-next { right: 1rem; }

  /* Dots */
  .carousel-dots {
    position: absolute; bottom: 1rem; left: 50%; transform: translateX(-50%);
    display: flex; gap: .5rem; z-index: 30;
  }
  .carousel-dot {
    width: .6rem; height: .6rem; border-radius: 9999px;
    background: rgba(255,255,255,.45); border: 0; cursor: pointer;
  }
  .carousel-dot[aria-current="true"] { background: #fff; }

  /* Respect reduced motion */
  @media (prefers-reduced-motion: reduce) {
    .carousel-track { transition-duration: 0ms; }
  }
</style>
@endpush



@section('content')

  @php
  // Slides
  $slides = [
    ['file' => 'images/test1.jpg', 'alt' => 'Chapel arrangement'],
    ['file' => 'images/test2.jpg', 'alt' => 'Casket and floral setup'],
    ['file' => 'images/test3.jpg', 'alt' => 'Memorial viewing'],
    ['file' => 'images/test4.jpg', 'alt' => 'Service hall'],
  ];

  // Safe: build HTML as a string (no Blade tags or raw HTML inside PHP)
  $pictureTag = function (string $file, string $alt, bool $eager = false) {
      $dir   = dirname($file);
      $name  = pathinfo($file, PATHINFO_FILENAME);
      $avif  = "$dir/$name.avif";
      $webp  = "$dir/$name.webp";
      $hasAvif = file_exists(public_path($avif));
      $hasWebp = file_exists(public_path($webp));
      $attr = $eager ? 'loading="eager" fetchpriority="high"' : 'loading="lazy" decoding="async"';

      $html  = '<picture>';
      if ($hasAvif) { $html .= '<source srcset="'.asset($avif).'" type="image/avif">'; }
      if ($hasWebp) { $html .= '<source srcset="'.asset($webp).'" type="image/webp">'; }
      $html .= '<img src="'.asset($file).'" alt="'.e($alt).'" class="min-w-full h-full object-cover opacity-70" '.$attr.' draggable="false">';
      $html .= '</picture>';

      return $html;
  };
@endphp


<!-- Hero -->
<div class="w-full carousel wallpaper-clouds">
  <div class="carousel-viewport" id="hero-carousel" aria-roledescription="carousel">
    <div class="carousel-track flex" data-track>
      @foreach($slides as $i => $s)
        <div class="carousel-slide shrink-0 basis-full" role="group" aria-roledescription="slide" aria-label="{{ $i+1 }} of {{ count($slides) }}">
          {!! $pictureTag($s['file'], $s['alt'] ?? 'Slide', $i===0) !!}
        </div>
      @endforeach
    </div>

    <div class="carousel-overlay"></div>

    <!-- Centered CTA -->
    <div class="carousel-cta flex flex-col items-center justify-center text-center text-white px-6">
      <h1 class="text-4xl md:text-6xl font-bold mb-4 drop-shadow">Welcome to Magat Funeral Services</h1>
      <p class="text-lg md:text-2xl max-w-2xl mb-8">Honoring life with dignity, compassion, and respect.</p>
      <div class="flex flex-col sm:flex-row gap-3">
        <a href="#packages" class="bg-white/95 hover:bg-white text-emerald-700 px-6 py-3 rounded-lg font-semibold shadow-lg transition">View Packages</a>
        <a href="{{ route('frontend.packages.index') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg transition">Browse Packages</a>
      </div>
    </div>

    <!-- Controls -->
    <button class="carousel-btn carousel-prev" type="button" aria-label="Previous slide" data-prev>❮</button>
    <button class="carousel-btn carousel-next" type="button" aria-label="Next slide" data-next>❯</button>
    <div class="carousel-dots" data-dots></div>
  </div>
</div>

  <!-- About -->
  <section class="py-16 bg-gray-50 dove-watermark">
    <div class="max-w-6xl mx-auto px-6 text-center">
      <h2 class="text-3xl font-bold mb-6">About Us</h2>
      <p class="text-gray-700 text-lg leading-relaxed max-w-3xl mx-auto">
        At <span class="font-semibold">Magat Funeral Services</span>, we are committed to helping families honor their loved ones
        with dignity, compassion, and respect. Our services provide comfort and support in times of need.
      </p>
    </div>
  </section>

  <!-- Packages (from DB) -->
  @php
    $packages = \App\Models\Package::where('is_active', true)->orderBy('price')->get();
  @endphp

  <section id="packages" class="py-16 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold">Service Packages</h2>
        <p class="text-gray-600 max-w-2xl mx-auto">Fixed inclusions, simple choices, clear pricing.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        @forelse($packages as $p)
          <article class="group relative rounded-2xl shadow-lg hover:shadow-2xl transition overflow-hidden bg-white">
            <!-- header -->
            <div class="p-6 border-b bg-white">
              <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold">{{ $p->name }}</h3>
                @if($p->price == 90000)
                  <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 font-semibold">Free Add-ons</span>
                @elseif($p->price <= 90000)
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
              <img src="{{ asset($p->thumbnail ?? 'images/placeholder.jpg') }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
            </div>

            <!-- inclusions -->
            <ul class="p-6 space-y-2 text-gray-700">
              @foreach(collect($p->inclusions ?? [])->take(6) as $inc)
                <li class="flex items-start gap-3">
                  <span aria-hidden="true" class="mt-1 inline-block h-2 w-2 rounded-full bg-emerald-600"></span>
                  <span>{{ $inc }}</span>
                </li>
              @endforeach
              @if(($p->inclusions && count($p->inclusions) > 6))
                <li class="text-gray-400">+ more</li>
              @endif
            </ul>

            <!-- footer -->
            <div class="p-6 pt-0">
              <div class="h-1 w-full rounded-full bg-gradient-to-r from-emerald-500 to-green-500 mb-5"></div>
              <a href="{{ route('frontend.packages.index') }}"
                class="w-full text-center bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg font-semibold block">
                View More
              </a>
            </div>


            <!-- corner dove -->
            <div class="pointer-events-none absolute -right-8 -bottom-8 opacity-10 select-none">
              <img src="{{ asset('images/wallpaper/dove-outline.svg') }}" alt="" class="w-48 h-48">
            </div>
          </article>
        @empty
          <div class="col-span-full bg-white rounded-2xl shadow p-10 text-center">
            <p class="text-gray-600">No packages available yet.</p>
          </div>
        @endforelse
      </div>

      <!-- Compare (4 columns only) -->
      @php
        // If you want specific names as headers, map by price or slug:
        $headers = ['OMS','OMV','Junior','Senior'];
      @endphp
      @if($packages->count())
      <div class="mt-10 rounded-2xl border bg-white p-5 overflow-x-auto">
        <div class="min-w-[640px]">
          <div class="grid grid-cols-5 text-sm font-semibold text-gray-600">
            <div class="py-3"></div>
            @foreach($headers as $h) <div class="py-3 text-center">{{ $h }}</div> @endforeach
          </div>
          @php
            $perks = [
              'Full garden'            => ['','', '✔️', '✔️'],
              'Landscape'              => ['','', '',  '✔️'],
              'Water dispenser'        => ['', '✔️', '✔️', '✔️'],
              'Balloons (≥25)'         => ['', '',   '✔️', '✔️'],
              'White roses (×24)'      => ['', '',   '',   ''],
              'Carriage / flower car'  => ['(hearse)', 'carriage', 'carriage + flower car', 'flower car (pickup)'],
            ];
          @endphp
          @foreach($perks as $label => $cols)
            <div class="grid grid-cols-5 border-t">
              <div class="py-3 pr-3 font-medium">{{ $label }}</div>
              @foreach($cols as $c)
                <div class="py-3 text-center">{{ $c }}</div>
              @endforeach
            </div>
          @endforeach
        </div>
      </div>
      @endif
    </div>
  </section>

  <!-- Featured Packages -->
  <section class="py-16">
    <div class="max-w-7xl mx-auto px-6">
      <h2 class="text-3xl font-bold text-center mb-12">Featured Packages</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($packages->take(3) as $f)
          <div class="group bg-white rounded-2xl shadow transition hover:shadow-2xl overflow-hidden">
            <div class="relative overflow-hidden">
              <img src="{{ asset($f->thumbnail ?? 'images/placeholder.jpg') }}"
                   alt="{{ $f->name }}" loading="lazy"
                   class="w-full h-72 object-cover transition duration-500 ease-out group-hover:scale-105 will-change-transform">
            </div>
            <div class="p-6">
              <h3 class="text-xl font-semibold mb-2">{{ $f->name }}</h3>
              <p class="text-gray-600 mb-4">Fixed package</p>
              <p class="font-bold text-emerald-700 mb-4">₱{{ number_format($f->price) }}</p>
              <a href="{{ route('frontend.packages.index') }}"
                class="inline-block bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg font-semibold transition">
                View More
              </a>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Services -->
  <section class="py-16 bg-gray-100">
    <div class="max-w-7xl mx-auto px-6 text-center">
      <h2 class="text-3xl font-bold mb-12">Our Services</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-xl shadow hover:shadow-md">
          <img src="{{ asset('images/service-burial.jpg') }}" class="w-full h-40 object-cover rounded-lg mb-4">
          <h3 class="text-xl font-semibold mb-2">Burial Services</h3>
          <p class="text-gray-600">Comprehensive arrangements ensuring dignity and respect.</p>
        </div>
        <div class="bg-white p-8 rounded-xl shadow hover:shadow-md">
          <img src="{{ asset('images/service-cremation.jpg') }}" class="w-full h-40 object-cover rounded-lg mb-4">
          <h3 class="text-xl font-semibold mb-2">Cremation Services</h3>
          <p class="text-gray-600">Personalized cremation options with caring support.</p>
        </div>
        <div class="bg-white p-8 rounded-xl shadow hover:shadow-md">
          <img src="{{ asset('images/service-memorial.jpg') }}" class="w-full h-40 object-cover rounded-lg mb-4">
          <h3 class="text-xl font-semibold mb-2">Memorial Packages</h3>
          <p class="text-gray-600">Helping families celebrate the life of their loved ones.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Gallery -->
  <section class="py-16">
    <h2 class="text-3xl font-bold mb-10 text-center">Our Gallery</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 max-w-7xl mx-auto px-6">
      @foreach ([
        ['img'=>'1st-package.jpg','caption'=>'Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam reiciendis rerum perspiciatis.'],
        ['img'=>'2nd-package.jpg','caption'=>'Minima adipisci qui autem dolores facere debitis ex tempore.'],
        ['img'=>'3rd-package.jpg','caption'=>'Perspiciatis incidunt sunt porro est illo ab totam.'],
        ['img'=>'4th-package.jpg','caption'=>'Magnam reiciendis rerum perspiciatis incidunt.'],
      ] as $g)
      <div class="group bg-white rounded-2xl shadow hover:shadow-2xl overflow-hidden transition">
        <div class="relative overflow-hidden">
          <img src="{{ asset('images/'.$g['img']) }}" alt="Gallery image" loading="lazy"
               class="w-full h-72 object-cover transition duration-500 ease-out group-hover:scale-105 will-change-transform">
          <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/0 to-black/0"></div>
        </div>
        <div class="p-4">
          <p class="text-sm text-gray-700">{{ $g['caption'] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </section>

  <!-- Contact CTA -->
  <section class="py-16 bg-emerald-600 text-white">
    <div class="max-w-6xl mx-auto text-center px-6">
      <h2 class="text-3xl font-bold mb-4">Need Assistance?</h2>
      <p class="mb-6">We’re here to support you 24/7. Contact us today to learn more about our services.</p>
      <a href="{{ route('frontend.contact.index') }}" class="bg-white text-emerald-700 px-6 py-3 rounded-lg font-semibold">Contact Us</a>
    </div>
  </section>

@endsection

@push('scripts')
<script>
(function () {
  const root = document.getElementById('hero-carousel');
  if (!root) return;

  const track   = root.querySelector('[data-track]');
  if (!track) return;
  const slides  = Array.from(track.children);
  const dots    = root.querySelector('[data-dots]');
  const btnPrev = root.querySelector('[data-prev]');
  const btnNext = root.querySelector('[data-next]');

  // Fallback: delegated clicks (works even if direct bindings fail)
  root.addEventListener('click', (e) => {
    const prevHit = e.target.closest('[data-prev]');
    const nextHit = e.target.closest('[data-next]');
    if (prevHit) { e.preventDefault(); prev(); }
    if (nextHit) { e.preventDefault(); next(); }
  });

  // build dots (only if container exists)
  if (dots) {
    dots.innerHTML = '';
    slides.forEach((_, idx) => {
      const b = document.createElement('button');
      b.className = 'carousel-dot';
      b.setAttribute('aria-label', `Go to slide ${idx+1}`);
      b.addEventListener('click', () => go(idx));
      dots.appendChild(b);
    });
  }

  let i = 0, timer = null, AUTO = 6000, dragging = false, startX = 0, dx = 0;
  const reduceMotion = matchMedia('(prefers-reduced-motion: reduce)').matches;

  function setX(px) {
    track.style.transform = `translateX(${px}px)`;
  }
  function position(instant = false) {
    track.style.transitionDuration = instant || reduceMotion ? '0ms' : '600ms';
    setX(-i * root.clientWidth);
  }

  function updateDots(){
  if (!dots) return;
  [...dots.children].forEach((d,k)=>d.setAttribute('aria-current', k===i ? 'true' : 'false'));
  }

  function lazyAround(idx){
    [idx-1, idx, idx+1].forEach(n=>{
      const s = slides[(n+slides.length)%slides.length];
      s?.querySelectorAll('img[loading="lazy"]').forEach(img => img.loading = 'eager');
    });
}


  function go(n, instant=false){
    i = (n + slides.length) % slides.length;
    position(instant);
    updateDots();
    lazyAround(i);
  }
  function next(){ go(i+1); restart(); }
  function prev(){ go(i-1); restart(); }

  function start(){ if (reduceMotion) return; stop(); timer = setInterval(next, AUTO); }
  function stop(){ if (timer) clearInterval(timer); timer = null; }
  function restart(){ stop(); start(); }

  // pause on hover / page hidden
  root.addEventListener('mouseenter', stop);
  root.addEventListener('mouseleave', start);
  document.addEventListener('visibilitychange', () => document.hidden ? stop() : start());

  // keyboard
  root.tabIndex = 0;
  root.addEventListener('keydown', e => {
    if (e.key === 'ArrowRight') next();
    if (e.key === 'ArrowLeft')  prev();
  });

  // drag / swipe
  root.addEventListener('pointerdown', e => {
    if (e.target.closest('a, button, input, select, textarea')) return;
    dragging = true; startX = e.clientX; dx = 0;
    track.style.transitionDuration = '0ms';
    root.setPointerCapture(e.pointerId);
    stop();
  });
  root.addEventListener('pointermove', e => {
    if (!dragging) return;
    dx = e.clientX - startX;
    setX((-i * root.clientWidth) + dx);
  });
  function endDrag(e){
    if (!dragging) return;
    dragging = false;
    const threshold = Math.min(120, root.clientWidth * 0.12);
    if (Math.abs(dx) > threshold) { dx < 0 ? next() : prev(); }
    else { go(i); start(); }
    try { if (e?.pointerId != null) root.releasePointerCapture(e.pointerId); } catch {}
  }
  root.addEventListener('pointerup', endDrag);
  root.addEventListener('pointercancel', endDrag);
  root.addEventListener('lostpointercapture', endDrag);

  // keep slide aligned on resize/orientation change
  window.addEventListener('resize', () => go(i, true));

  // init
  go(0, true); updateDots(); start();
})();

</script>
@endpush

