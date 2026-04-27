@php
$accessoryCategories = [
    ['title' => 'Casual Wear',    'subtitle' => 'Everyday Essentials', 'image' => asset('images/kids.jpg')],
    ['title' => 'Formal Attire',  'subtitle' => 'Office & Events',     'image' => asset('images/mens.jpg')],
    ['title' => 'Formal Attire',  'subtitle' => 'Office & Events',     'image' => asset('images/women.jpg')],
    ['title' => 'Formal Attire',  'subtitle' => 'Office & Events',     'image' => asset('images/watch.jpg')],
];
@endphp

<!-- Men's Fashion Section -->
<div class="relative max-w-7xl mx-auto py-12">
    <!-- Hero Banner -->
    <div class="relative h-80 bg-cover bg-center flex items-center justify-center text-center"
        style="background-image: url('{{ asset('images/womens-banner.jpg') }}');">
        <div class="bg-black bg-opacity-40 w-full h-full absolute top-0 left-0"></div>
        <div class="relative z-10 text-white">
            <h2 class="text-3xl font-bold uppercase">accessories</h2>
            <p class="mt-1 text-sm">Complete your look with the perfect add-ons.</p>
        </div>
    </div>

    <!-- Category Slider -->
    <div class="mt-6 px-4 overflow-hidden relative">
        <div id="accessories-slider"
            class="flex space-x-4 overflow-x-hidden scroll-smooth no-scrollbar"
            style="scroll-behavior: smooth;">
            @foreach ($accessoryCategories as $category)
                <div class="relative min-w-[calc(33.333%-1rem)] bg-white h-80 cursor-pointer group overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300">
                    <!-- Image Container -->
                    <div class="absolute inset-0 overflow-hidden">
                        <img src="{{ $category['image'] }}" alt="{{ $category['title'] }}"
                            class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/30 to-transparent"></div>
                    </div>
                    <!-- Content -->
                    <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                        <h3 class="text-2xl font-bold mb-1">{{ $category['title'] }}</h3>
                        <p class="text-sm opacity-90">{{ $category['subtitle'] }}</p>
                        <button
                            class="mt-3 px-4 py-2 bg-white/20 backdrop-blur-sm text-xs font-medium hover:bg-white/30 transition-colors">
                            Shop Collection →
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex items-center justify-between mt-4 gap-4">
            <!-- Progress Bar -->
            <div class="flex-1 h-1.5 bg-gray-100">
                <div id="accessories-progress" class="h-full bg-indigo-600 transition-all duration-300" style="width: 0%"></div>
            </div>

            <!-- Navigation -->
            <div class="flex items-center gap-2">
                <button id="acc-prev"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.5 14L4.5 9L9.5 4" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M20.5 20V13C20.5 11.9391 20.0786 10.9217 19.3284 10.1716C18.5783 9.42143 17.5609 9 16.5 9H4.5"
                            stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <button id="acc-next"
                    class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.5 14L20.5 9L15.5 4" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M4.5 20V13C4.5 11.9391 4.92143 10.9217 5.67157 10.1716C6.42172 9.42143 7.43913 9 8.5 9H20.5"
                            stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Accessories Products Section -->
    <section class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-left mb-8">
            <p class="text-gray-800 font-semibold uppercase">Timeless</p>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Pieces For Every Moment</h2>
        </div>
        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

            <div class="group relative bg-white hover:shadow-md transition-all duration-300" data-categories="men women">
                <div class="aspect-square bg-gray-100 relative overflow-hidden">
                    <span class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 text-xs font-medium z-10">New</span>
                    <button class="absolute top-2 right-2 p-2 text-gray-600 hover:text-red-500 z-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                    <img src="{{ asset('images/pro1.jpg') }}" alt="Product"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-medium text-gray-900">Men's Casual Shirt</h3>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="text-lg font-bold text-gray-900">$49.99</span>
                        <span class="text-sm text-gray-500 line-through">$69.99</span>
                        <span class="text-sm text-green-600">Save 29%</span>
                    </div>
                </div>
                <div class="absolute inset-x-0 bottom-0 p-4 bg-white/90 backdrop-blur opacity-0 group-hover:opacity-100 transition-opacity duration-300 h-1/5">
                    <div class="flex items-end gap-2">
                        <button class="flex-1 bg-gray-700 text-white py-2 hover:bg-gray-900 transition-colors text-sm font-medium">Add to Cart</button>
                        <button class="flex-1 bg-gray-700 text-white py-2 hover:bg-gray-900 transition-colors text-sm font-medium">Buy Now</button>
                    </div>
                </div>
            </div>

            <div class="group relative bg-white hover:shadow-md transition-all duration-300" data-categories="women">
                <div class="aspect-square bg-gray-100 relative overflow-hidden">
                    <span class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 text-xs font-medium z-10">New</span>
                    <button class="absolute top-2 right-2 p-2 text-gray-600 hover:text-red-500 z-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                    <img src="{{ asset('images/pro2.jpg') }}" alt="Product"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-medium text-gray-900">Women's Summer Dress</h3>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="text-lg font-bold text-gray-900">$79.99</span>
                        <span class="text-sm text-gray-500 line-through">$49.99</span>
                        <span class="text-sm text-green-600">Save 20%</span>
                    </div>
                </div>
                <div class="absolute inset-x-0 bottom-0 p-4 bg-white/90 backdrop-blur opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <button class="w-full bg-gray-700 text-white py-2 hover:bg-gray-900 transition-colors text-sm font-medium">Add to Wishlist</button>
                </div>
            </div>

            <div class="group relative bg-white hover:shadow-md transition-all duration-300" data-categories="kids">
                <div class="aspect-square bg-gray-100 relative overflow-hidden">
                    <span class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 text-xs font-medium z-10">New</span>
                    <button class="absolute top-2 right-2 p-2 text-gray-600 hover:text-red-500 z-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                    <img src="{{ asset('images/pro3.jpg') }}" alt="Product"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-medium text-gray-900">Kids' Colorful Backpack</h3>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="text-lg font-bold text-gray-900">$34.99</span>
                        <span class="text-sm text-gray-500 line-through">$49.99</span>
                    </div>
                </div>
                <div class="absolute inset-x-0 bottom-0 p-4 bg-white/90 backdrop-blur opacity-0 group-hover:opacity-100 transition-opacity duration-300 h-1/5">
                    <div class="flex items-end gap-2">
                        <button class="flex-1 bg-gray-700 text-white py-2 hover:bg-gray-900 transition-colors text-sm font-medium">Add to Cart</button>
                        <button class="flex-1 bg-gray-700 text-white py-2 hover:bg-gray-900 transition-colors text-sm font-medium">Buy Now</button>
                    </div>
                </div>
            </div>

            <div class="group relative bg-white hover:shadow-md transition-all duration-300" data-categories="men">
                <div class="aspect-square bg-gray-100 relative overflow-hidden">
                    <span class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 text-xs font-medium z-10">New</span>
                    <button class="absolute top-2 right-2 p-2 text-gray-600 hover:text-red-500 z-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                    <img src="{{ asset('images/pro4.jpg') }}" alt="Product"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-medium text-gray-900">Music Box</h3>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="text-lg font-bold text-gray-900">$79.99</span>
                    </div>
                </div>
                <div class="absolute inset-x-0 bottom-0 p-4 bg-white/90 backdrop-blur opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <button class="w-full bg-gray-700 text-white py-2 hover:bg-gray-900 transition-colors text-sm font-medium">Add to Wishlist</button>
                </div>
            </div>

            @foreach ([['pro2.jpg', "Women's Summer Dress", '$79.99', '$49.99', 'Save 20%'], ['pro2.jpg', "Women's Summer Dress", '$79.99', '$49.99', 'Save 20%'], ['pro2.jpg', "Women's Summer Dress", '$79.99', '$49.99', 'Save 20%'], ['pro2.jpg', "Women's Summer Dress", '$79.99', '$49.99', 'Save 20%']] as $p)
            <div class="group relative bg-white hover:shadow-md transition-all duration-300" data-categories="women">
                <div class="aspect-square bg-gray-100 relative overflow-hidden">
                    <span class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 text-xs font-medium z-10">New</span>
                    <button class="absolute top-2 right-2 p-2 text-gray-600 hover:text-red-500 z-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                    <img src="{{ asset('images/' . $p[0]) }}" alt="Product"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ $p[1] }}</h3>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="text-lg font-bold text-gray-900">{{ $p[2] }}</span>
                        <span class="text-sm text-gray-500 line-through">{{ $p[3] }}</span>
                        <span class="text-sm text-green-600">{{ $p[4] }}</span>
                    </div>
                </div>
                <div class="absolute inset-x-0 bottom-0 p-4 bg-white/90 backdrop-blur opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <button class="w-full bg-gray-700 text-white py-2 hover:bg-gray-900 transition-colors text-sm font-medium">Add to Wishlist</button>
                </div>
            </div>
            @endforeach

        </div>

        <!-- Show More Button -->
        <div class="mt-8 text-center">
            <button class="bg-black text-white px-8 py-3 hover:font-bold transition-colors font-medium">
                Show More
            </button>
        </div>
    </section>
</div>

<script>
(function () {
    const slider   = document.getElementById('accessories-slider');
    const progress = document.getElementById('accessories-progress');
    const prevBtn  = document.getElementById('acc-prev');
    const nextBtn  = document.getElementById('acc-next');

    function updateProgress() {
        if (!slider) return;
        const max = slider.scrollWidth - slider.clientWidth;
        const pct = max > 0 ? (slider.scrollLeft / max) * 100 : 0;
        progress.style.width = pct + '%';
        prevBtn.disabled = pct <= 0;
        nextBtn.disabled = pct >= 100;
    }

    slider.addEventListener('scroll', updateProgress);

    prevBtn.addEventListener('click', function () {
        slider.scrollLeft -= slider.clientWidth / 2;
    });

    nextBtn.addEventListener('click', function () {
        slider.scrollLeft += slider.clientWidth / 2;
    });

    updateProgress();
})();
</script>
