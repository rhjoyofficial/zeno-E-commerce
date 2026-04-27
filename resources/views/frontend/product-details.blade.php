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

<div class="max-w-7xl mx-auto sm:px-6 md:px-10 lg:px-8 py-16" x-data="productPage()" x-init="initSplide()">
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
                <div class=" top-4 space-y-3">
                    <template x-for="(image, index) in images" :key="index">
                        <button @click="goToSlide(index)" :class="{
                                'border-2 border-black': activeImageIndex === index, 
                                'border border-gray-200 hover:border-gray-400': activeImageIndex !== index
                            }" class="overflow-hidden transition-all w-[81px] h-[100px] ">
                            <img :src="image.thumb" class="w-full h-full object-cover"
                                :alt="'Thumbnail ' + (index + 1)">
                        </button>
                    </template>
                </div>
            </div>

            <!-- Main Images with Splide -->
            <div class="flex-1">
                <div class="splide" id="main-slider">
                    <div class="splide__track">
                        <div class="splide__list">
                            <template x-for="(image, index) in images" :key="index">
                                <div class="splide__slide relative">
                                    <img :src="image.main" class="w-full h-full object-cover"
                                        :alt="'Main Image ' + (index + 1)">
                                    <button @click="openFullscreen(image.main)"
                                        class="absolute bottom-4 right-4 bg-black text-white px-4 py-2 text-sm hover:bg-gray-800 transition">
                                        View Full Image
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Info Column (same as before) -->
        <div class="lg:w-5/12">
            <div class="sticky top-4">
                <!-- Add to Cart Notification -->
                <div x-show="showCartNotification" x-transition
                    class="fixed top-24 right-4 bg-green-500 text-white px-6 py-3 shadow-lg z-50"
                    x-init="setTimeout(() => showCartNotification = false, 3000)">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span x-text="cartNotificationMessage"></span>
                    </div>
                </div>
                <!-- Title and Price -->
                <div class="space-y-2 mt-4">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Premium Comfort Sneakers</h1>
                    <div class="flex items-center gap-3">
                        <span class="text-2xl font-bold">$99.99</span>
                        <span class="text-lg text-gray-500 line-through">$129.99</span>
                        <span class="text-sm bg-red-100 text-red-700 px-2 py-1 ">Save 23%</span>
                    </div>
                </div>

                <!-- Color Selection -->
                <div class="mt-8">
                    <h3 class="text-base font-semibold text-black uppercase tracking-[2px]">
                        Color: <span x-text="selectedColor"></span>
                    </h3>
                    <div class="mt-4 flex gap-2">
                        <template x-for="(color, index) in colors" :key="index">
                            <button @click="changeColor(index)" :class="{
                                    'border-2 border-black': selectedColorIndex === index, 
                                    'border-2 border-gray-200 hover:border-gray-400': selectedColorIndex !== index
                                }" class="w-12 h-12 " :style="'background-color: ' + color.hex"
                                :title="color.name"></button>
                        </template>
                    </div>
                </div>

                <!-- Size Selection -->
                <div class="mt-8">
                    <div class="flex justify-between items-center">
                        <h3 class="text-base font-semibold text-black uppercase tracking-[2px]">Size</h3>
                        <button @click="openSizeGuide()"
                            class="text-base font-medium capitalize text-gray-800 hover:underline">
                            Size Guide
                        </button>
                    </div>
                    <div class="mt-4 grid grid-cols-5 gap-2">
                        <template x-for="(size, index) in sizes" :key="index">
                            <button @click="selectedSize = size" :class="{
                                    'border border-black bg-black text-white': selectedSize === size, 
                                    'border border-gray-200 hover:border-gray-400': selectedSize !== size
                                }" class="py-2 text-sm ">
                                <span x-text="size"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Quantity and Add to Cart -->
                <div class="mt-8">
                    <div class="flex items-center">
                        <span class="text-base font-semibold text-black uppercase tracking-[2px] mr-4">Quantity</span>
                        <div class="flex border w-32 font-semibold text-black">
                            <button @click="quantity > 1 ? quantity-- : null"
                                class="px-3 py-2 border-r text-gray-700 hover:bg-gray-50">
                                -
                            </button>
                            <input type="text" x-model="quantity" class="w-full text-center border-none focus:ring-0"
                                min="1" max="10">
                            <button @click="quantity < 10 ? quantity++ : null"
                                class="px-3 py-2 border-l text-gray-700 hover:bg-gray-50">
                                +
                            </button>
                        </div>
                    </div>
                    <button @click="addToCart()"
                        class="mt-10 w-full bg-black hover:bg-gray-800 text-white py-3 font-medium transition-colors">
                        Add to Bag
                    </button>
                    <h5 class="text-black text-xs font-normal text-center mt-3">
                        <span x-text="purchaseCount"></span> Customers Purchased, next is you
                    </h5>
                </div>

                <!-- Product Details Accordion -->
                <div class="mt-10 border-t" x-data="{ activeAccordion: null }">
                    <div class="space-y-4">
                        <!-- Description -->
                        <div>
                            <button @click="activeAccordion = activeAccordion === 'description' ? null : 'description'"
                                class="w-full flex justify-between items-center py-4 text-base font-medium uppercase">
                                <span>Description</span>
                                <svg :class="{'rotate-180': activeAccordion === 'description'}"
                                    class="w-5 h-5 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="activeAccordion === 'description'" x-collapse
                                class="pb-4 text-sm text-gray-700">
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
                            <button @click="activeAccordion = activeAccordion === 'shipping' ? null : 'shipping'"
                                class="w-full flex justify-between items-center py-4 text-base font-medium uppercase">
                                <span>SHIPPING Methods</span>
                                <svg :class="{'rotate-180': activeAccordion === 'shipping'}"
                                    class="w-5 h-5 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="activeAccordion === 'shipping'" x-collapse class="pb-4 text-sm text-gray-700">
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
                            <button @click="activeAccordion = activeAccordion === 'returns' ? null : 'returns'"
                                class="w-full flex justify-between items-center py-4 text-base font-medium uppercase">
                                <span>Returns & Exchanges</span>
                                <svg :class="{'rotate-180': activeAccordion === 'returns'}"
                                    class="w-5 h-5 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="activeAccordion === 'returns'" x-collapse
                                class="pb-4 text-sm text-gray-700 space-y-2">
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
    <div x-show="isFullscreen" class="fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4"
        @click.away="isFullscreen = false">
        <button @click="isFullscreen = false" class="absolute top-4 right-4 text-white text-3xl">
            &times;
        </button>
        <img :src="fullscreenImage" class="max-w-full max-h-full object-contain" alt="Fullscreen view">
    </div>
    <div x-show="isFullscreen" class="fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4"
        @click.away="isFullscreen = false">
        <button @click="isFullscreen = false" class="absolute top-4 right-4 text-white text-3xl">
            &times;
        </button>
        <img :src="images[activeImageIndex].main" class="max-w-full max-h-full object-contain" alt="Fullscreen view">
    </div>

    <!-- Size Guide Modal -->
    <div x-show="showSizeGuide" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        @click.away="showSizeGuide = false">
        <div class="bg-white max-w-2xl w-full max-h-[90vh] overflow-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Size Guide</h3>
                    <button @click="showSizeGuide = false" class="text-2xl">
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
        <!-- Product Grid -->
        @include('products.index', ['products' => $products])
    </section>



</div>

@endsection

<!-- Add Splide JS -->
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

<script>
    function productPage() {
        return {
            // Product data
            images: [
                { 
                    main: "{{ asset('images/1.jpg') }}", 
                    thumb: "{{ asset('images/1.jpg') }}",
                    color: 'Black'
                },
                { 
                    main: "{{ asset('images/2.jpg') }}", 
                    thumb: "{{ asset('images/2.jpg') }}",
                    color: 'White'
                },
                { 
                    main: "{{ asset('images/3.jpg') }}", 
                    thumb: "{{ asset('images/3.jpg') }}",
                    color: 'Navy Blue'
                },
                { 
                    main: "{{ asset('images/4.jpg') }}", 
                    thumb: "{{ asset('images/4.jpg') }}",
                    color: 'Gray'
                }
            ],
            colors: [
                { name: 'Black', hex: '#000000' },
                { name: 'White', hex: '#FFFFFF' },
                { name: 'Navy Blue', hex: '#000080' },
                { name: 'Gray', hex: '#808080' }
            ],
            sizes: ['US 7', 'US 8', 'US 9', 'US 10', 'US 11'],
            
            // State
            activeImageIndex: 0,
            selectedColorIndex: 0,
            selectedSize: 'US 8',
            quantity: 1,
            purchaseCount: 255,
            isFullscreen: false,
            fullscreenImage: '',
            showSizeGuide: false,
            showCartNotification: false,
            cartNotificationMessage: '',
            splide: null,
            
            // Computed
            get selectedColor() {
                return this.colors[this.selectedColorIndex].name;
            },
            
            // Methods
            initSplide() {
                this.$nextTick(() => {
                    this.splide = new Splide('#main-slider', {
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
                            1024: {
                                direction: 'ttb',
                            },
                            640: {
                                direction: 'ltr',
                            }
                        }
                    }).mount();

                    // Update active index when slide changes
                    this.splide.on('move', (newIndex) => {
                        this.activeImageIndex = newIndex;
                        // Update selected color to match the image
                        const imageColor = this.images[newIndex].color;
                        const colorIndex = this.colors.findIndex(c => c.name === imageColor);
                        if (colorIndex !== -1) {
                            this.selectedColorIndex = colorIndex;
                        }
                    });
                });
            },
            
            goToSlide(index) {
                if (this.splide) {
                    this.splide.go(index);
                }
            },
            
            changeColor(index) {
                this.selectedColorIndex = index;
                // Find the first image that matches this color
                const matchingIndex = this.images.findIndex(img => img.color === this.colors[index].name);
                if (matchingIndex !== -1) {
                    this.goToSlide(matchingIndex);
                }
            },
            
            openFullscreen(imageSrc) {
                this.fullscreenImage = imageSrc;
                this.isFullscreen = true;
            },
            
           openSizeGuide() {
                this.showSizeGuide = true;
            },
            
            addToCart() {
                this.cartNotificationMessage = `Added ${this.quantity} ${this.selectedColor} sneakers (Size: ${this.selectedSize}) to cart`;
                this.showCartNotification = true;
                this.purchaseCount++;
                
                // Reset form
                setTimeout(() => {
                    this.quantity = 1;
                    this.showCartNotification = false;

                }, 3000);
            }
        }
    }
</script>
@endpush