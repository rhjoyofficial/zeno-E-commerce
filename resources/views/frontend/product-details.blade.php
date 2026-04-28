@extends('layouts.app')
@section('title', $product->title)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
<style>
    .splide__slide img { width: 100%; height: 500px; object-fit: cover; }
    @media (min-width: 1024px) { .splide__slide img { height: 640px; } }
    .splide__arrow { background: rgba(0,0,0,0.7); }
    .splide__arrow svg { fill: white; }
</style>
@endpush

@section('content')
@php
    $finalPrice  = (float) $product->final_price;
    $basePrice   = (float) $product->price;
    $hasDiscount = $product->discount && $product->discount_price && $finalPrice < $basePrice;
    $savePercent = $hasDiscount ? round((($basePrice - $finalPrice) / $basePrice) * 100) : 0;
@endphp

<div class="max-w-7xl mx-auto sm:px-6 md:px-10 lg:px-8 py-16">

    {{-- Breadcrumbs --}}
    @php
    $breadcrumbs = [
        ['title' => 'Home', 'url' => route('home')],
        ['title' => $product->category?->category_name ?? 'Products', 'url' => route('products.list')],
        ['title' => $product->title],
    ];
    @endphp
    <x-breadcrumbs :breadcrumbs="$breadcrumbs" />

    <!-- Product Display -->
    <div class="flex flex-col lg:flex-row gap-8 mt-8">

        <!-- Image Gallery Column -->
        <div class="lg:w-7/12 flex flex-col lg:flex-row gap-6">

            <!-- Thumbnails (Vertical, desktop only) -->
            <div class="hidden lg:block w-20 flex-shrink-0">
                <div class="space-y-3">
                    @foreach ($images as $index => $image)
                        <button class="thumb-btn overflow-hidden transition-all w-[81px] h-[100px]
                            {{ $index === 0 ? 'border-2 border-black' : 'border border-gray-200 hover:border-gray-400' }}"
                            onclick="goToSlide({{ $index }})">
                            <img src="{{ $image['url'] }}" class="w-full h-full object-cover" alt="{{ $product->title }}">
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Main Image Splide Slider -->
            <div class="flex-1">
                <div class="splide" id="main-slider">
                    <div class="splide__track">
                        <div class="splide__list">
                            @foreach ($images as $index => $image)
                                <div class="splide__slide relative">
                                    <img src="{{ $image['url'] }}" class="w-full h-full object-cover"
                                        alt="{{ $product->title }} image {{ $index + 1 }}">
                                    <button onclick="openFullscreen('{{ $image['url'] }}')"
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

                <!-- Title and Price -->
                <div class="space-y-2 mt-4">
                    <p class="text-sm uppercase tracking-widest text-gray-500">
                        {{ $product->category?->category_name ?? '' }}
                    </p>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">{{ $product->title }}</h1>
                    @if ($product->short_description)
                        <p class="text-gray-600 text-sm">{{ $product->short_description }}</p>
                    @endif

                    <div class="flex items-center gap-3 pt-1">
                        @if ($hasDiscount)
                            <span class="text-2xl font-bold" id="display-price">
                                {{ config('app.currency_symbol', '৳') }}{{ number_format($finalPrice, 2) }}
                            </span>
                            <span class="text-lg text-gray-400 line-through">
                                {{ config('app.currency_symbol', '৳') }}{{ number_format($basePrice, 2) }}
                            </span>
                            <span class="text-sm bg-red-100 text-red-700 px-2 py-1">Save {{ $savePercent }}%</span>
                        @else
                            <span class="text-2xl font-bold" id="display-price">
                                {{ config('app.currency_symbol', '৳') }}{{ number_format($finalPrice, 2) }}
                            </span>
                        @endif
                    </div>

                    @if ($product->stock_quantity <= 0 && !$product->has_variants)
                        <span class="inline-block bg-red-100 text-red-700 text-xs px-3 py-1 font-semibold uppercase">Out of Stock</span>
                    @elseif ($product->stock_alert && $product->stock_quantity <= $product->stock_alert)
                        <span class="inline-block bg-yellow-100 text-yellow-700 text-xs px-3 py-1 font-semibold uppercase">Low Stock</span>
                    @endif
                </div>

                @if ($product->has_variants && count($variantColors) > 0)
                <!-- Color Selection -->
                <div class="mt-8">
                    <h3 class="text-base font-semibold text-black uppercase tracking-[2px]">
                        Color: <span id="selected-color-label">{{ $variantColors[0]['name'] ?? '' }}</span>
                    </h3>
                    <div class="mt-4 flex flex-wrap gap-2" id="color-options">
                        @foreach ($variantColors as $index => $color)
                            <button class="color-btn w-10 h-10 {{ $index === 0 ? 'border-2 border-black ring-2 ring-black ring-offset-1' : 'border-2 border-gray-200 hover:border-gray-400' }}"
                                style="background-color: {{ $color['hex'] }}"
                                title="{{ $color['name'] }}"
                                data-color-id="{{ $color['id'] }}"
                                data-color-name="{{ $color['name'] }}"
                                onclick="selectColor(this)">
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif

                @if ($product->has_variants && count($variantSizes) > 0)
                <!-- Size Selection -->
                <div class="mt-8">
                    <div class="flex justify-between items-center">
                        <h3 class="text-base font-semibold text-black uppercase tracking-[2px]">
                            Size: <span id="selected-size-label" class="font-normal normal-case text-gray-600">— select —</span>
                        </h3>
                        <button onclick="document.getElementById('size-guide-modal').classList.remove('hidden')"
                            class="text-base font-medium capitalize text-gray-800 hover:underline">
                            Size Guide
                        </button>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2" id="size-options">
                        @foreach ($variantSizes as $size)
                            <button class="size-btn px-4 py-2 text-sm border-2 border-gray-200 hover:border-gray-400 font-medium"
                                data-size-id="{{ $size['id'] }}"
                                data-size-name="{{ $size['name'] }}"
                                onclick="selectSize(this)">
                                {{ $size['name'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Stock info for variant (shown after selection) -->
                @if ($product->has_variants)
                <p id="variant-stock-info" class="mt-3 text-sm text-gray-500 hidden"></p>
                @endif

                <!-- Quantity and Add to Cart -->
                <div class="mt-8">
                    <div class="flex items-center gap-4">
                        <span class="text-base font-semibold text-black uppercase tracking-[2px]">Qty</span>
                        <div class="flex border w-32 font-semibold text-black">
                            <button type="button" onclick="adjustQty(-1)"
                                class="px-3 py-2 border-r text-gray-700 hover:bg-gray-50">−</button>
                            <input type="number" id="product-qty" value="1" min="1" max="99"
                                class="w-full text-center border-none focus:ring-0 text-sm">
                            <button type="button" onclick="adjustQty(1)"
                                class="px-3 py-2 border-l text-gray-700 hover:bg-gray-50">+</button>
                        </div>
                    </div>

                    <button type="button" id="add-to-cart-btn" onclick="addToCartDetail()"
                        class="mt-6 w-full bg-black hover:bg-gray-800 text-white py-4 font-semibold tracking-[2px] uppercase transition-colors">
                        Add to Bag
                    </button>

                    <a href="{{ route('products.list') }}"
                        class="mt-3 block w-full border border-black text-black py-4 text-center font-semibold tracking-[2px] uppercase hover:bg-gray-50 transition-colors">
                        Continue Shopping
                    </a>
                </div>

                <!-- Product Details Accordion -->
                <div class="mt-10 border-t">
                    <div class="space-y-0">

                        @if ($product->details?->description)
                        <div class="border-b">
                            <button onclick="toggleAccordion('accordion-description')"
                                class="w-full flex justify-between items-center py-4 text-base font-medium uppercase">
                                <span>Description</span>
                                <svg id="accordion-description-chevron" class="w-5 h-5 text-gray-400 transition-transform"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div id="accordion-description" class="hidden pb-4 text-sm text-gray-700 prose max-w-none">
                                {!! nl2br(e($product->details->description)) !!}
                            </div>
                        </div>
                        @endif

                        @if ($product->details?->specifications)
                        <div class="border-b">
                            <button onclick="toggleAccordion('accordion-specs')"
                                class="w-full flex justify-between items-center py-4 text-base font-medium uppercase">
                                <span>Specifications</span>
                                <svg id="accordion-specs-chevron" class="w-5 h-5 text-gray-400 transition-transform"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div id="accordion-specs" class="hidden pb-4 text-sm text-gray-700">
                                {!! nl2br(e($product->details->specifications)) !!}
                            </div>
                        </div>
                        @endif

                        <div class="border-b">
                            <button onclick="toggleAccordion('accordion-shipping')"
                                class="w-full flex justify-between items-center py-4 text-base font-medium uppercase">
                                <span>Shipping</span>
                                <svg id="accordion-shipping-chevron" class="w-5 h-5 text-gray-400 transition-transform"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div id="accordion-shipping" class="hidden pb-4 text-sm text-gray-700">
                                <p>Standard delivery across Bangladesh. Free shipping on orders over {{ config('app.currency_symbol', '৳') }}{{ number_format((float) config('shop.free_shipping_minimum', 5000), 0) }}.</p>
                            </div>
                        </div>

                        <div class="border-b">
                            <button onclick="toggleAccordion('accordion-returns')"
                                class="w-full flex justify-between items-center py-4 text-base font-medium uppercase">
                                <span>Returns &amp; Exchanges</span>
                                <svg id="accordion-returns-chevron" class="w-5 h-5 text-gray-400 transition-transform"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div id="accordion-returns" class="hidden pb-4 text-sm text-gray-700 space-y-2">
                                <p><strong>7-Day Return Policy:</strong> Unworn items with tags attached may be returned within 7 days.</p>
                                <p><strong>Exchange:</strong> Contact our support team to arrange a size or color exchange.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Fullscreen Modal -->
    <div id="fullscreen-modal"
        class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4">
        <button onclick="document.getElementById('fullscreen-modal').classList.add('hidden')"
            class="absolute top-4 right-4 text-white text-4xl leading-none">&times;</button>
        <img id="fullscreen-img" src="" class="max-w-full max-h-full object-contain" alt="Full view">
    </div>

    <!-- Size Guide Modal -->
    <div id="size-guide-modal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white max-w-2xl w-full max-h-[90vh] overflow-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Size Guide</h3>
                    <button onclick="document.getElementById('size-guide-modal').classList.add('hidden')"
                        class="text-2xl leading-none">&times;</button>
                </div>
                <img src="{{ asset('images/size-guide.jpg') }}" alt="Size Guide" class="w-full">
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if (count($products) > 0)
    <section class="my-20">
        <h2 class="text-3xl font-bold text-black mb-8 text-center">You May Also Like</h2>
        @include('products.index', ['products' => $products])
    </section>
    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
<script>
(function () {
    const productId      = {{ $product->id }};
    const hasVariants    = {{ $product->has_variants ? 'true' : 'false' }};
    const basePrice      = {{ (float) $product->price }};
    const currencySymbol = '{{ config('app.currency_symbol', '৳') }}';
    const allVariants    = @json($variantsForJs);

    let selectedColorId  = {{ count($variantColors) > 0 ? $variantColors[0]['id'] : 'null' }};
    let selectedSizeId   = null;
    let selectedVariantId = null;
    let splideInstance   = null;

    // ── Splide ──────────────────────────────────────────────────────────────
    splideInstance = new Splide('#main-slider', {
        type: 'slide', rewind: false, pagination: false, arrows: true,
        height: window.innerWidth >= 1024 ? '640px' : '500px',
    }).mount();

    splideInstance.on('move', function (newIndex) {
        updateThumbnails(newIndex);
    });

    window.goToSlide = function (index) {
        if (splideInstance) splideInstance.go(index);
    };

    function updateThumbnails(activeIndex) {
        document.querySelectorAll('.thumb-btn').forEach(function (btn, i) {
            btn.classList.toggle('border-2', true);
            btn.classList.toggle('border-black', i === activeIndex);
            btn.classList.toggle('border-gray-200', i !== activeIndex);
        });
    }

    // ── Modals ───────────────────────────────────────────────────────────────
    window.openFullscreen = function (src) {
        document.getElementById('fullscreen-img').src = src;
        document.getElementById('fullscreen-modal').classList.remove('hidden');
    };
    document.getElementById('fullscreen-modal').addEventListener('click', function (e) {
        if (e.target === this) this.classList.add('hidden');
    });
    document.getElementById('size-guide-modal').addEventListener('click', function (e) {
        if (e.target === this) this.classList.add('hidden');
    });

    // ── Color selection ──────────────────────────────────────────────────────
    window.selectColor = function (btn) {
        selectedColorId = parseInt(btn.dataset.colorId, 10);
        selectedSizeId  = null;
        selectedVariantId = null;

        document.querySelectorAll('.color-btn').forEach(function (b) {
            b.classList.remove('border-black', 'ring-2', 'ring-black', 'ring-offset-1');
            b.classList.add('border-gray-200');
        });
        btn.classList.remove('border-gray-200');
        btn.classList.add('border-black', 'ring-2', 'ring-black', 'ring-offset-1');

        const label = document.getElementById('selected-color-label');
        if (label) label.textContent = btn.dataset.colorName;

        updateAvailableSizes(selectedColorId);
        updateVariantInfo();
    };

    function updateAvailableSizes(colorId) {
        const availableSizeIds = new Set(
            allVariants.filter(v => v.color_id === colorId && v.stock_quantity > 0).map(v => v.size_id)
        );
        document.querySelectorAll('.size-btn').forEach(function (btn) {
            const sid = parseInt(btn.dataset.sizeId, 10);
            if (availableSizeIds.has(sid)) {
                btn.disabled = false;
                btn.classList.remove('opacity-40', 'cursor-not-allowed');
            } else {
                btn.disabled = true;
                btn.classList.add('opacity-40', 'cursor-not-allowed');
            }
        });
    }

    // ── Size selection ───────────────────────────────────────────────────────
    window.selectSize = function (btn) {
        if (btn.disabled) return;
        selectedSizeId = parseInt(btn.dataset.sizeId, 10);

        document.querySelectorAll('.size-btn').forEach(function (b) {
            b.classList.remove('border-black', 'bg-black', 'text-white');
            b.classList.add('border-gray-200');
        });
        btn.classList.remove('border-gray-200');
        btn.classList.add('border-black', 'bg-black', 'text-white');

        const label = document.getElementById('selected-size-label');
        if (label) label.textContent = btn.dataset.sizeName;

        findMatchingVariant();
        updateVariantInfo();
    };

    function findMatchingVariant() {
        const match = allVariants.find(function (v) {
            const colorOk = selectedColorId ? v.color_id === selectedColorId : true;
            const sizeOk  = selectedSizeId  ? v.size_id  === selectedSizeId  : true;
            return colorOk && sizeOk;
        });
        selectedVariantId = match ? match.id : null;

        // Update displayed price if variant has a different price
        if (match) {
            const displayEl = document.getElementById('display-price');
            if (displayEl) {
                const price = match.final_price > 0 ? match.final_price : match.price;
                displayEl.textContent = currencySymbol + price.toFixed(2);
            }
        }
    }

    function updateVariantInfo() {
        const infoEl = document.getElementById('variant-stock-info');
        if (!infoEl) return;
        if (!selectedVariantId) { infoEl.classList.add('hidden'); return; }

        const variant = allVariants.find(v => v.id === selectedVariantId);
        if (variant) {
            infoEl.classList.remove('hidden');
            if (variant.stock_quantity <= 0) {
                infoEl.textContent = 'Out of stock for this combination.';
                infoEl.className = 'mt-3 text-sm text-red-600';
            } else if (variant.stock_quantity < 5) {
                infoEl.textContent = 'Only ' + variant.stock_quantity + ' left in stock!';
                infoEl.className = 'mt-3 text-sm text-yellow-600';
            } else {
                infoEl.textContent = 'In stock (' + variant.stock_quantity + ' available)';
                infoEl.className = 'mt-3 text-sm text-green-600';
            }
        }
    }

    // ── Qty ──────────────────────────────────────────────────────────────────
    window.adjustQty = function (delta) {
        const input = document.getElementById('product-qty');
        let qty = parseInt(input.value, 10) || 1;
        qty = Math.min(99, Math.max(1, qty + delta));
        input.value = qty;
    };

    // ── Add to Cart ──────────────────────────────────────────────────────────
    window.addToCartDetail = function () {
        if (hasVariants) {
            if (!selectedColorId && {{ count($variantColors) > 0 ? 'true' : 'false' }}) {
                if (typeof notifications !== 'undefined') notifications.error('Please select a colour.');
                return;
            }
            if (!selectedSizeId && {{ count($variantSizes) > 0 ? 'true' : 'false' }}) {
                if (typeof notifications !== 'undefined') notifications.error('Please select a size.');
                return;
            }
            if (!selectedVariantId) {
                if (typeof notifications !== 'undefined') notifications.error('This combination is not available.');
                return;
            }
        }

        const qty = parseInt(document.getElementById('product-qty').value, 10) || 1;
        const btn = document.getElementById('add-to-cart-btn');
        btn.disabled = true;
        btn.textContent = 'Adding…';
        showLoader();

        fetch(window.appConfig.routes.cartAdd, {
            method:  'POST',
            headers: {
                'Content-Type':  'application/json',
                'X-CSRF-TOKEN':  window.appConfig.csrfToken,
                'Accept':        'application/json',
            },
            body: JSON.stringify({
                product_id: productId,
                qty:        qty,
                variant_id: selectedVariantId,
            }),
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                if (typeof notifications !== 'undefined') notifications.success('Added to bag!');
                document.querySelectorAll('.cart-counter').forEach(function (el) {
                    el.textContent = data.cart_count;
                });
            } else {
                if (typeof notifications !== 'undefined') notifications.error(data.message || 'Could not add to bag.');
            }
        })
        .catch(function () {
            if (typeof notifications !== 'undefined') notifications.error('An error occurred. Please try again.');
        })
        .finally(function () {
            btn.disabled = false;
            btn.textContent = 'Add to Bag';
            hideLoader();
        });
    };

    // ── Accordion ────────────────────────────────────────────────────────────
    window.toggleAccordion = function (id) {
        const panel   = document.getElementById(id);
        const chevron = document.getElementById(id + '-chevron');
        const hidden  = panel.classList.contains('hidden');
        panel.classList.toggle('hidden', !hidden);
        if (chevron) chevron.classList.toggle('rotate-180', hidden);
    };

    // Init: if variants, mark first colour as selected and update sizes
    if (hasVariants && selectedColorId) {
        updateAvailableSizes(selectedColorId);
    }
})();
</script>
@endpush
