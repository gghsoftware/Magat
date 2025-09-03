@extends('layouts.frontend')

@section('title', 'Home - Magat Funeral')

@push('styles')
<style>
  /* Soft clouds + doves wallpaper */
  .wallpaper-clouds {
    background-image:
      linear-gradient(rgba(10,24,20,0.35), rgba(10,24,20,0.35)),
      url("{{ asset('images/wallpaper/clouds-doves.jpg') }}");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
  }
  /* Subtle dove watermark for light sections */
  .dove-watermark {
    position: relative;
    isolation: isolate;
  }
  .dove-watermark::after {
    content: "";
    position: absolute; inset: 0;
    background-image: url("{{ asset('images/wallpaper/dove-outline.svg') }}");
    background-repeat: no-repeat;
    background-position: right -60px bottom -40px;
    background-size: 260px auto;
    opacity: .05;
    pointer-events: none;
    z-index: -1;
  }
</style>
@endpush

@section('content')

    <!-- Full-width Landing Slider (kept) -->
    <div class="w-full">
        <div class="relative w-full h-screen mb-12 overflow-hidden wallpaper-clouds">
            <!-- Slider Wrapper -->
            <div class="flex transition-transform duration-700 h-full" id="slider">
                <img src="{{ asset('images/1st-package.jpg') }}" class="min-w-full h-full object-cover flex-shrink-0 opacity-70">
                <img src="{{ asset('images/2nd-package.jpg') }}" class="min-w-full h-full object-cover flex-shrink-0 opacity-70">
                <img src="{{ asset('images/3rd-package.jpg') }}" class="min-w-full h-full object-cover flex-shrink-0 opacity-70">
            </div>

            <!-- Dark Overlay (softened) -->
            <div class="absolute inset-0 bg-black/40 z-10"></div>

            <!-- Centered Text -->
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center text-white px-6 z-20">
                <h1 class="text-4xl md:text-6xl font-bold mb-4 drop-shadow">
                    Welcome to Magat Funeral Services
                </h1>
                <p class="text-lg md:text-2xl max-w-2xl mb-8">
                    Honoring life with dignity, compassion, and respect.
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="#packages"
                       class="bg-white/95 hover:bg-white text-green-700 px-6 py-3 rounded-lg font-semibold shadow-lg transition">
                       View Packages
                    </a>
                    <a href="{{ route('frontend.products.index') }}"
                       class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg transition">
                       View Our Caskets
                    </a>
                </div>
            </div>

            <!-- Controls -->
            <button onclick="prevSlide()"
                class="absolute left-6 top-1/2 -translate-y-1/2 bg-black bg-opacity-40 text-white px-4 py-2 rounded-full z-20">
                ❮
            </button>
            <button onclick="nextSlide()"
                class="absolute right-6 top-1/2 -translate-y-1/2 bg-black bg-opacity-40 text-white px-4 py-2 rounded-full z-20">
                ❯
            </button>

            <!-- Indicators -->
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex space-x-3 z-20" id="indicators"></div>
        </div>
    </div>

    <!-- About Us Section (kept, with subtle watermark) -->
    <section class="py-16 bg-gray-50 dove-watermark">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-6">About Us</h2>
            <p class="text-gray-700 text-lg leading-relaxed max-w-3xl mx-auto">
                At <span class="font-semibold">Magat Funeral Services</span>, we are committed to helping families honor their loved ones 
                with dignity, compassion, and respect. Our services provide comfort and support in times of need.
            </p>
        </div>
    </section>

    <!-- NEW: Packages section -->
    @php
      $packages = [
        [
          'name' => 'OMS (Wood)', 'price' => 25000,
          'badge' => 'Essential',
          'items' => ['Ordinary flowers','Tent ×1','Chairs ×20','Tables ×3','Hearse (car) ×1'],
          'accent' => 'from-green-600 to-emerald-600',
          'bg' => 'bg-white'
        ],
        [
          'name' => 'OMV (Metal)', 'price' => 45000,
          'badge' => 'Value',
          'items' => ['Ordinary flowers','Tent ×1','Chairs ×30','Tables ×4','Water dispenser','White balloons ×20','Hearse ×1'],
          'accent' => 'from-green-700 to-emerald-700',
          'bg' => 'bg-white'
        ],
        [
          'name' => 'Junior Metal', 'price' => 70000,
          'badge' => 'Popular',
          'items' => ['Full garden','Tent ×1','Chairs ×40','Tables ×6','Balloons ×25','Water dispenser','Cards ×6','Hearse + carriage attached'],
          'accent' => 'from-emerald-700 to-teal-700',
          'bg' => 'bg-white'
        ],
        [
          'name' => 'Senior Metal (Flexi)', 'price' => 90000,
          'badge' => 'Flexible',
          'items' => ['Full garden + landscape','Tent ×2','Chairs ×50','Tables ×8','Balloons ×30','Water dispenser','Cards ×6','Hearse + carriage + flower car (pickup)'],
          'accent' => 'from-teal-700 to-cyan-700',
          'bg' => 'bg-white'
        ],
        [
          'name' => 'Special (Semi-imported)', 'price' => 180000,
          'badge' => 'Special',
          'items' => ['Special garden','Tent ×2','Chairs ×100','Tables ×10','Balloons ×50','White rose ×24','Water dispenser','Cards 1 box','Live band','2× flower car, horse-pulled carriage'],
          'accent' => 'from-sky-700 to-indigo-700',
          'bg' => 'bg-white'
        ],
        [
          'name' => 'Imported', 'price' => 350000,
          'badge' => 'Premium',
          'items' => ['Special garden','Tent ×2','Chairs ×100','Tables ×10','Balloons ×50','White rose ×24','Water dispenser','Cards 1 box','Imported flower car + Cadillac'],
          'accent' => 'from-indigo-700 to-violet-700',
          'bg' => 'bg-white'
        ],
      ];
    @endphp

    <section id="packages" class="py-16 bg-gradient-to-b from-white to-gray-50">
      <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-bold">Service Packages</h2>
          <p class="text-gray-600 max-w-2xl mx-auto">
            Thoughtfully curated inclusions to meet different preferences and budgets.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
          @foreach($packages as $p)
          <article class="group relative rounded-2xl shadow-lg hover:shadow-2xl transition overflow-hidden {{ $p['bg'] }}">
            <!-- header -->
            <div class="p-6 border-b bg-white">
              <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold">{{ $p['name'] }}</h3>
                <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700 font-semibold">
                  {{ $p['badge'] }}
                </span>
              </div>
              <p class="mt-3">
                <span class="text-3xl font-extrabold text-green-700">₱{{ number_format($p['price']) }}</span>
                <span class="text-gray-500 text-sm"> / package</span>
              </p>
            </div>

            <!-- list -->
            <ul class="p-6 space-y-2 text-gray-700">
              @foreach($p['items'] as $item)
                <li class="flex items-start gap-3">
                  <span aria-hidden="true" class="mt-1 inline-block h-2 w-2 rounded-full bg-green-600"></span>
                  <span>{{ $item }}</span>
                </li>
              @endforeach
            </ul>

            <!-- footer -->
            <div class="p-6 pt-0">
              <div class="h-1 w-full rounded-full bg-gradient-to-r {{ $p['accent'] }} mb-5"></div>
              <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('frontend.contact.index') }}"
                   class="flex-1 text-center bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg font-semibold">
                   Inquire Now
                </a>
                <a href="{{ route('frontend.products.index') }}"
                   class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-800 px-5 py-2.5 rounded-lg font-semibold">
                   View Caskets
                </a>
              </div>
            </div>

            <!-- corner dove accent -->
            <div class="pointer-events-none absolute -right-8 -bottom-8 opacity-10 select-none">
              <img src="{{ asset('images/wallpaper/dove-outline.svg') }}" alt="" class="w-48 h-48">
            </div>
          </article>
          @endforeach
        </div>

        <!-- optional compare strip -->
        <div class="mt-10 rounded-2xl border bg-white p-5 overflow-x-auto">
          <div class="min-w-[720px]">
            <div class="grid grid-cols-7 text-sm font-semibold text-gray-600">
              <div class="py-3"></div>
              <div class="py-3 text-center">OMS</div>
              <div class="py-3 text-center">OMV</div>
              <div class="py-3 text-center">Junior</div>
              <div class="py-3 text-center">Senior</div>
              <div class="py-3 text-center">Special</div>
              <div class="py-3 text-center">Imported</div>
            </div>
            @php
              // quick matrix for a few notable perks
              $perks = [
                'Full garden' => ['','','✔️','✔️','✔️','✔️'],
                'Landscape'   => ['','','','✔️','✔️','✔️'],
                'Water dispenser' => ['','✔️','✔️','✔️','✔️','✔️'],
                'Balloons (≥25)' => ['','','✔️','✔️','✔️','✔️'],
                'White roses (×24)' => ['','','','','✔️','✔️'],
                'Carriage / flower car' => ['','(hearse)','carriage','carriage + flower car','2× flower car + horse carriage','Imported flower car + Cadillac'],
              ];
            @endphp
            @foreach($perks as $label => $cols)
              <div class="grid grid-cols-7 border-t">
                <div class="py-3 pr-3 font-medium">{{ $label }}</div>
                @foreach($cols as $c)
                  <div class="py-3 text-center">{{ $c }}</div>
                @endforeach
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Caskets -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-12">Featured Caskets</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ([
                    ['img'=>'1st-package.jpg','title'=>'Premium Mahogany Casket','desc'=>'Classic design with velvet interior and polished finish.','price'=>'$2,499'],
                    ['img'=>'2nd-package.jpg','title'=>'Elegant Oak Casket','desc'=>'Timeless style with plush satin interior.','price'=>'$1,899'],
                    ['img'=>'3rd-package.jpg','title'=>'Modern White Casket','desc'=>'A contemporary option symbolizing purity and peace.','price'=>'$1,499'],
                ] as $c)
                <div class="group bg-white rounded-2xl shadow transition hover:shadow-2xl overflow-hidden">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset('images/'.$c['img']) }}"
                            alt="{{ $c['title'] }}" loading="lazy"
                            class="w-full h-72 object-cover transition duration-500 ease-out group-hover:scale-105 will-change-transform">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">{{ $c['title'] }}</h3>
                        <p class="text-gray-600 mb-4">{{ $c['desc'] }}</p>
                        <p class="font-bold text-green-700 mb-4">{{ $c['price'] }}</p>
                        <a href="{{ route('frontend.products.index') }}"
                        class="inline-block bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg font-semibold transition">
                            View Details
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>


    <!-- Services Section -->
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

    <!-- Testimonials -->
    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-10">What Families Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-gray-50 p-6 rounded-xl shadow">
                    <p class="text-gray-700 italic">“Magat Funeral Services helped us through the most difficult time with kindness and care.”</p>
                    <h4 class="mt-4 font-semibold">– Maria Santos</h4>
                </div>
                <div class="bg-gray-50 p-6 rounded-xl shadow">
                    <p class="text-gray-700 italic">“The casket and arrangements were beautiful and dignified. Highly recommended.”</p>
                    <h4 class="mt-4 font-semibold">– Juan Dela Cruz</h4>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
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
                    <img src="{{ asset('images/'.$g['img']) }}"
                        alt="Gallery image"
                        loading="lazy"
                        class="w-full h-72 object-cover transition duration-500 ease-out group-hover:scale-105 will-change-transform">
                    <!-- subtle top gradient for text if you add overlays later -->
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
    <section class="py-16 bg-green-600 text-white">
        <div class="max-w-6xl mx-auto text-center px-6">
            <h2 class="text-3xl font-bold mb-4">Need Assistance?</h2>
            <p class="mb-6">We’re here to support you 24/7. Contact us today to learn more about our services.</p>
            <a href="{{ route('frontend.contact.index') }}" class="bg-white text-green-700 px-6 py-3 rounded-lg font-semibold">Contact Us</a>
        </div>
    </section>

@endsection

@push('scripts')
<script>
    (function () {
        const slider = document.getElementById('slider');
        const images = slider?.querySelectorAll('img') ?? [];
        const indicators = document.getElementById('indicators');
        let index = 0;
        const duration = 700;
        const autoplayMs = 5000;
        let timer = null;

        function update() {
            slider.style.transform = `translateX(-${index * 100}%)`;
            [...indicators.children].forEach((dot, i) => {
                dot.classList.toggle('opacity-100', i === index);
                dot.classList.toggle('opacity-40', i !== index);
            });
        }

        function makeDots() {
            if (!indicators || !images.length) return;
            indicators.innerHTML = '';
            images.forEach((_, i) => {
                const dot = document.createElement('button');
                dot.className = 'h-3 w-3 rounded-full bg-white transition opacity-40';
                dot.setAttribute('aria-label', `Slide ${i+1}`);
                dot.addEventListener('click', () => { index = i; update(); restart(); });
                indicators.appendChild(dot);
            });
            update();
        }

        window.prevSlide = function () {
            index = (index - 1 + images.length) % images.length;
            update(); restart();
        }
        window.nextSlide = function () {
            index = (index + 1) % images.length;
            update(); restart();
        }

        function autoplay() {
            timer = setInterval(() => { window.nextSlide(); }, autoplayMs);
        }
        function stop() { clearInterval(timer); timer = null; }
        function restart() { stop(); autoplay(); }

        // Pause on hover
        const sliderWrap = slider?.parentElement;
        sliderWrap?.addEventListener('mouseenter', stop);
        sliderWrap?.addEventListener('mouseleave', autoplay);

        // Init
        slider && (slider.style.transition = `transform ${duration}ms ease`);
        makeDots(); autoplay();
    })();
</script>
@endpush