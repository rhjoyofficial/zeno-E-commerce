@extends('layouts.app')
@section('title', 'Premium Comfort Sneakers')
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
<style>
    .splide__slide img {
        width: 100%;
        height: 700px;
        object-fit: cover;
    }
    .splide__arrow {
        background: rgba(0, 0, 0, 0.7);
    }
    .splide__arrow svg {
        fill: white;
    }
</style>
@endpush
@section('content')
@php
$images = [
    ['main' => asset('images/1.jpg'), 'thumb' => asset('images/1.jpg'), 'color' => 'Black'],
    ['main' => asset('images/2.jpg'), 'thumb' => asset('images/2.jpg'), 'color' => 'White'],
    ['main' => asset('images/3.jpg'), 'thumb' => asset('images/3.jpg'), 'color' => 'Navy Blue'],
    ['main' => asset('images/4.jpg'), 'thumb' => asset('images/4.jpg'), 'color' => 'Gray'],
];
$productColors = [
    ['name' => 'Black', 'hex' => '#000000'],
    ['name' => 'White', 'hex' => '#FFFFFF'],
    ['name' => 'Navy Blue', 'hex' => '#000080'],
    ['name' => 'Gray', 'hex' => '#808080'],
];
$sizes = ['US 7', 'US 8', 'US 9', 'US 10', 'US 11'];
$purchaseCount = 255;
@endphp

<div class="max-w-7xl mx-auto sm:px-6 md:px-10 lg:px-8 py-16">
    {{-- Breadcrumbs --}}
    @php
    $breadcrumbs = [
        ['title' => 'Home', 'url' => '/'],
        ['title' => 'T-shirt', 'url' => '/categories/footwear'],
        ['title' => 'Premium Comfort']
    ];
    @endphp
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    {{-- End Breadcrumbs --}}

    <!-- Product Display -->
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Image Gallery Column -->
        <div class="lg:w-7/12 flex flex-col lg:flex-row gap-6">
            <!-- Thumbnails (Vertical) -->
            <div class="hidden lg:block w-20 flex-shrink-0">
                <div class="top-4 space-y-3">
                    @foreach ($images as $index => $image)
                        <button class="thumb-btn overflow-hidden transition-all w-[81px] h-[100px]
                            {{ $index === 0 ? 'border-2 border-black' : 'border border-gray-200 hover:border-gray-400' }}"
                            data-thumb-index="{{ $index }}"
                            onclick="goToSlide({{ $index }})">
                            <img src="{{ $image['thumb'] }}" class="w-full h-full object-cover"
                                alt="Thumbnail {{ $index + 1 }}">
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Main Images with Splide -->
            <div class="flex-1">
                <div class="splide" id="main-slider">
                    <div class="splide__track">
                        <div class="splide__list">
                            @foreach ($images as $index => $image)
                                <div class="splide__slide relative">
                                    <img src="{{ $image['main'] }}" class="w-full h-full object-cover"
                                        alt="Main Image {{ $index + 1 }}">
                                    <button onclick="openFullscreen('{{ $image['main'] }}')"
                                        class="absolute bottom-4 right-4 bg-black text-white px-4 py-2 text-sm hover:bg-gray-800 transition">
                                        View Full Image
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Info Column -->
        <div class="lg:w-5/12">
            <div class="sticky top-4">
                <!-- Add to Cart Notification -->
                <div id="cart-notification"
                    class="hidden fixed top-24 right-4 bg-green-500 text-white px-6 py-3 shadow-lg z-50">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span id="cart-notification-msg"></span>
                    </div>
                </div>

                <!-- Title and Price -->
                <div class="space-y-2 mt-4">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Premium Comfort Sneakers</h1>
                    <div class="flex items-center gap-3">
                        <span class="text-2xl font-bold">$99.99</span>
                        <span class="text-lg text-gray-500 line-through">$129.99</span>
                        <span class="text-sm bg-red-100 text-red-700 px-2 py-1">Save 23%</span>
                    </div>
                </div>

                <!-- Color Selection -->
                <div class="mt-8">
                    <h3 class="text-base font-semibold text-black uppercase tracking-[2px]">
                        Color: <span id="selected-color-label">{{ $productColors[0]['name'] }}</span>
                    </h3>
                    <div class="mt-4 flex gap-2">
                        @foreach ($productColors as $index => $color)
                            <button class="color-btn w-12 h-12 {{ $index === 0 ? 'border-2 border-black' : 'border-2 border-gray-200 hover:border-gray-400' }}"
                                style="background-color: {{ $color['hex'] }}"
                                title="{{ $color['name'] }}"
                                data-color-index="{{ $index }}"
                                data-color-name="{{ $color['name'] }}"
                                onclick="changeColor({{ $index }})">
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Size Selection -->
                <div class="mt-8">
                    <div class="flex justify-between items-center">
                        <h3 class="text-base font-semibold text-black uppercase tracking-[2px]">Size</h3>
                        <button onclick="document.getElementById('size-guide-modal').classList.remove('hidden')"
                            class="text-base font-medium capitalize text-gray-800 hover:underline">
                            Size Guide
                        </button>
                    </div>
                    <div class="mt-4 grid grid-cols-5 gap-2">
                        @foreach ($sizes as $size)
                            <button class="size-btn py-2 text-sm {{ $size === 'US 8' ? 'border border-black bg-black text-white' : 'border border-gray-200 hover:border-gray-400' }}"
                                data-size="{{ $size }}"
                                onclick="selectSize(this)">
                                {{ $size }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Quantity and Add to Cart -->
                <div class="mt-8">
                    <div class="flex items-center">
                        <span class="text-base font-semibold text-black uppercase tracking-[2px] mr-4">Quantity</span>
                        <div class="flex border w-32 font-semibold text-black">
                            <button type="button" onclick="adjustQty(-1)"
                                class="px-3 py-2 border-r text-gray-700 hover:bg-gray-50">
                                -
                            </button>
                            <input type="text" id="product-qty" value="1"
                                class="w-full text-center border-none focus:ring-0" min="1" max="10">
                            <button type="button" onclick="adjustQty(1)"
                                class="px-3 py-2 border-l text-gray-700 hover:bg-gray-50">
                                +
                            </button>
                        </div>
                    </div>
                    <button type="button" onclick="addToCart()"
                        class="mt-10 w-full bg-black hover:bg-gray-800 text-white py-3 font-medium transition-colors">
                        Add to Bag
                    </button>
                    <h5 class="text-black text-xs font-normal text-center mt-3">
                        <span id="purchase-count">{{ $purchaseCount }}</span> Customers Purchased, next is you
                    </h5>
                </div>

                <!-- Product Details Accordion -->
                <div class="mt-10 border-t">
                    <div class="space-y-4">
                        <!-- Description -->
                        <div>
                            <button onclick="toggleAccordion('accordion-description')"
                                class="w-full flex justify-between items-center py-4 text-base font-medium uppercase">
                                <span>Description</span>
                                <svg id="accordion-description-chevron"
                                    class="w-5 h-5 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="accordion-description" class="hidden pb-4 text-sm text-gray-700">
                                <p>Premium comfort sneakers made from high-quality materials that combine comfort with
                                    durability. Perfect for both casual and active occasions.</p>
                                <ul class="mt-2 space-y-1 pl-5 list-disc">
                                    <li>Premium quality materials</li>
                                    <li>Reinforced stitching for longevity</li>
                                    <li>Cushioned insole for all-day comfort</li>
                                    <li>Durable rubber sole with superior traction</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Shipping -->
                        <div>
                            <button onclick="toggleAccordion('accordion-shipping')"
                                class="w-full flex justify-between items-center py-4 text-base font-medium uppercase">
                                <span>SHIPPING Methods</span>
                                <svg id="accordion-shipping-chevron"
                                    class="w-5 h-5 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="accordion-shipping" class="hidden pb-4 text-sm text-gray-700">
                                <p>We offer multiple shipping options to meet your needs:</p>
                                <ul class="mt-2 space-y-2 pl-5 list-disc">
                                    <li><strong>Standard Shipping:</strong> 3-5 business days ($4.99)</li>
                                    <li><strong>Express Shipping:</strong> 2-3 business days ($9.99)</li>
                                    <li><strong>Priority Shipping:</strong> 1-2 business days ($14.99)</li>
                                </ul>
                                <p class="mt-2 font-medium">Free shipping on all orders over $75</p>
                            </div>
                        </div>

                        <!-- Returns -->
                        <div>
                            <button onclick="toggleAccordion('accordion-returns')"
                                class="w-full flex justify-between items-center py-4 text-base font-medium uppercase">
                                <span>Returns &amp; Exchanges</span>
                                <svg id="accordion-returns-chevron"
                                    class="w-5 h-5 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="accordion-returns" class="hidden pb-4 text-sm text-gray-700 space-y-2">
                                <p><strong>30-Day Return Policy:</strong> Unworn, unwashed items with tags attached may
                                    be returned within 30 days of delivery.</p>
                                <p><strong>Exchanges:</strong> We offer free exchanges for size or color within 14 days
                                    of delivery.</p>
                                <p><strong>Return Process:</strong> Initiate returns through your account page or
                                    contact our customer service.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fullscreen Image Modal -->
    <div id="fullscreen-modal" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4">
        <button onclick="document.getElementById('fullscreen-modal').classList.add('hidden')"
            class="absolute top-4 right-4 text-white text-3xl">
            &times;
        </button>
        <img id="fullscreen-img" src="" class="max-w-full max-h-full object-contain" alt="Fullscreen view">
    </div>

    <!-- Size Guide Modal -->
    <div id="size-guide-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white max-w-2xl w-full max-h-[90vh] overflow-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Size Guide</h3>
                    <button onclick="document.getElementById('size-guide-modal').classList.add('hidden')"
                        class="text-2xl">
                        &times;
                    </button>
                </div>
                <img src="{{ asset('images/size-guide.jpg') }}" alt="Size Guide" class="w-full">
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <section class="my-20 text-center">
        <h2 class="text-3xl font-bold text-black py-8">You May Also Like</h2>
        @include('products.index', ['products' => $products])
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
<script>
(function () {
    const images = @json(array_map(fn($img) => ['main' => $img['main'], 'color' => $img['color']], $images));
    const colors = @json($productColors);

    let activeImageIndex = 0;
    let selectedColorIndex = 0;
    let selectedSize = 'US 8';
    let splideInstance = null;

    // Init Splide
    splideInstance = new Splide('#main-slider', {
        type: 'slide',
        rewind: false,
        pagination: false,
        arrows: true,
        direction: 'ttb',
        height: '700px',
        wheel: true,
        releaseWheel: true,
        wheelSleep: 200,
        wheelMinThreshold: 10,
        breakpoints: {
            1024: { direction: 'ttb' },
            640:  { direction: 'ltr' }
        }
    }).mount();

    splideInstance.on('move', function (newIndex) {
        setActiveThumbnail(newIndex);
        const imgColor = images[newIndex].color;
        const ci = colors.findIndex(c => c.name === imgColor);
        if (ci !== -1) setActiveColor(ci);
        activeImageIndex = newIndex;
    });

    function setActiveThumbnail(index) {
        document.querySelectorAll('.thumb-btn').forEach(function (btn, i) {
            btn.className = btn.className.replace(/border-2 border-black|border border-gray-200 hover:border-gray-400/g, '').trim();
            if (i === index) {
                btn.classList.add('border-2', 'border-black');
            } else {
                btn.classList.add('border', 'border-gray-200', 'hover:border-gray-400');
            }
        });
    }

    function setActiveColor(index) {
        selectedColorIndex = index;
        document.querySelectorAll('.color-btn').forEach(function (btn, i) {
            btn.className = btn.className.replace(/border-2 border-black|border-2 border-gray-200 hover:border-gray-400/g, '').trim();
            if (i === index) {
                btn.classList.add('border-2', 'border-black');
            } else {
                btn.classList.add('border-2', 'border-gray-200', 'hover:border-gray-400');
            }
        });
        document.getElementById('selected-color-label').textContent = colors[index].name;
    }

    // Modals backdrop close
    document.getElementById('fullscreen-modal').addEventListener('click', function (e) {
        if (e.target === this) this.classList.add('hidden');
    });
    document.getElementById('size-guide-modal').addEventListener('click', function (e) {
        if (e.target === this) this.classList.add('hidden');
    });

    // Global functions used by inline onclick handlers
    window.goToSlide = function (index) {
        if (splideInstance) splideInstance.go(index);
    };

    window.openFullscreen = function (src) {
        document.getElementById('fullscreen-img').src = src;
        document.getElementById('fullscreen-modal').classList.remove('hidden');
    };

    window.changeColor = function (index) {
        setActiveColor(index);
        const matchIdx = images.findIndex(img => img.color === colors[index].name);
        if (matchIdx !== -1) window.goToSlide(matchIdx);
    };

    window.selectSize = function (btn) {
        selectedSize = btn.getAttribute('data-size');
        document.querySelectorAll('.size-btn').forEach(function (b) {
            b.className = b.className.replace(/border border-black bg-black text-white|border border-gray-200 hover:border-gray-400/g, '').trim();
            if (b === btn) {
                b.classList.add('border', 'border-black', 'bg-black', 'text-white');
            } else {
                b.classList.add('border', 'border-gray-200', 'hover:border-gray-400');
            }
        });
    };

    window.adjustQty = function (delta) {
        const input = document.getElementById('product-qty');
        let qty = parseInt(input.value, 10) || 1;
        qty = Math.min(10, Math.max(1, qty + delta));
        input.value = qty;
    };

    window.addToCart = function () {
        const qty = parseInt(document.getElementById('product-qty').value, 10) || 1;
        const color = colors[selectedColorIndex].name;
        const msg = `Added ${qty} ${color} sneakers (Size: ${selectedSize}) to cart`;
        const notification = document.getElementById('cart-notification');
        document.getElementById('cart-notification-msg').textContent = msg;
        notification.classList.remove('hidden');

        const countEl = document.getElementById('purchase-count');
        countEl.textContent = parseInt(countEl.textContent, 10) + 1;

        setTimeout(function () {
            document.getElementById('product-qty').value = 1;
            notification.classList.add('hidden');
        }, 3000);
    };

    window.toggleAccordion = function (id) {
        const panel = document.getElementById(id);
        const chevron = document.getElementById(id + '-chevron');
        const isHidden = panel.classList.contains('hidden');
        panel.classList.toggle('hidden', !isHidden);
        if (chevron) chevron.classList.toggle('rotate-180', isHidden);
    };
})();
</script>
@endpush
