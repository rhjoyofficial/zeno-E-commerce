@extends('layouts.app')
@section('title', 'Your Bag')

@section('content')
    <div class="max-w-7xl mx-auto px-2 py-8">
        <h1 class="ms-6 text-4xl font-bold uppercase mb-2 font-megumi ">Your Bag <span
                class="itemInBag text-base font-normal">
                ({{ $totalItems }} {{ $totalItems <= 1 ? 'item' : 'items' }}) </span>
        </h1>
        <div class="flex flex-col lg:flex-row gap-8" id="cart-container">
            @if ($totalItems > 0)
                <div class="lg:w-[60%] xl:w-[58%] bg-white">
                    <div class="ms-6">
                        <p class="text-gray-800 font-medium">You have items in your Bag - Check Out now and make them yours.
                        </p>
                    </div>

                    <div class="px-6 py-6">
                        <div class="space-y-2">
                            @foreach ($cartItems as $cartItem)
                                @if ($cartItem->product)
                                    @php
                                        $realPrice = $cartItem->variant
                                            ? (float) $cartItem->variant->price
                                            : (float) $cartItem->product->price;

                                        // For variants: any non-null discount_price is active (no boolean flag).
                                        // For products: respect the discount boolean flag.
                                        $discountPrice = $cartItem->variant
                                            ? ($cartItem->variant->discount_price ? (float) $cartItem->variant->discount_price : null)
                                            : ($cartItem->product->discount && $cartItem->product->discount_price ? (float) $cartItem->product->discount_price : null);

                                        $effectivePrice = $discountPrice ?? $realPrice;
                                        $youSave        = $discountPrice ? $realPrice - $discountPrice : 0;
                                        $savePercentage = $realPrice > 0 ? ($youSave / $realPrice) * 100 : 0;
                                    @endphp
                                    <hr data-hr-id="{{ $cartItem->id }}"
                                        class="block lg:w-auto lg:h-[2px] lg:bg-gray-200 lg:border-none lg:mx-0">
                                    <div class="cart-item-row relative flex gap-4 items-start py-6"
                                        data-item-id="{{ $cartItem->id }}" data-price="{{ $effectivePrice }}"
                                        data-discount="{{ $youSave }}">
                                        <div class="relative w-64 h-64 flex-shrink-0 bg-gray-100 overflow-hidden">
                                            <div class="absolute top-2 left-2 z-10">
                                                <input type="checkbox"
                                                    class="item-checkbox w-5 h-5 border-2 border-black appearance-none checked:bg-black checked:border-black relative cursor-pointer focus:ring-0 ring-offset-0 focus:ring-offset-0 outline-none focus:outline-none checked:hover:bg-black"
                                                    checked>
                                            </div>
                                            <img class="w-full h-full object-cover"
                                                src="{{ $cartItem->product->primaryImage ? asset('storage/' . $cartItem->product->primaryImage->image_path) : asset('images/products/default.jpg') }}"
                                                alt="{{ $cartItem->product->title }}">
                                        </div>
                                        <div class="flex-1 min-w-0 font-semibold">
                                            <p class="font-semibold text-2xl mb-2">{{ $cartItem->product->title }}</p>
                                            @if ($cartItem->variant)
                                                <p class="text-xs font-normal text-gray-600 mb-2 uppercase tracking-wider">
                                                    PRODUCT CODE: #<a href="#" title="View Product Details"
                                                        class="underline">{{ $cartItem->variant->sku }} </a>
                                                </p>
                                            @else
                                                <p class="text-xs font-normal text-gray-600 mb-2 uppercase tracking-wider">
                                                    PRODUCT CODE: #<a href="#" title="View Product Details"
                                                        class="underline">{{ $cartItem->product->sku }}</a>
                                                </p>
                                            @endif
                                            <div class="mb-4">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <span class="text-base text-gray-800 font-medium">COLOR:</span>
                                                    @if ($cartItem->variant)
                                                        <span
                                                            class="text-base">{{ $cartItem->variant->color->name }}</span>
                                                    @else
                                                        <span class="text-base">Random Color</span>
                                                    @endif
                                                </div>
                                                @if ($cartItem->variant)
                                                    <div class="flex gap-2">
                                                        <button class="w-6 h-6 border-2 border-black"
                                                            style="background-color: {{ $cartItem->variant->color->hex_code }}"></button>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="mb-4">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <span
                                                        class="text-base text-gray-800 font-medium uppercase">Price:</span>
                                                    @if ($discountPrice)
                                                        <p class="text-base text-gray-800">
                                                            <span
                                                                class="text-black font-medium text-lg">{{ config('app.currency_symbol') }}{{ number_format($discountPrice, 2) }}</span>
                                                            <span
                                                                class="text-[#E71046] line-through mr-2 text-sm">{{ config('app.currency_symbol') }}{{ number_format($realPrice, 2) }}</span>
                                                        </p>
                                                    @else
                                                        <p class="text-base text-gray-800">
                                                            <span
                                                                class="text-black font-medium text-lg">{{ config('app.currency_symbol') }}{{ number_format($realPrice, 2) }}</span>
                                                        </p>
                                                    @endif
                                                </div>

                                                @if ($discountPrice)
                                                    <p class="text-xs text-gray-600 mt-1">
                                                        YOU SAVE {{ config('app.currency_symbol') }}{{ number_format($youSave, 2) }}
                                                        ({{ round($savePercentage) }}%)
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="flex flex-wrap items-center gap-4 mb-4">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-base text-gray-800 font-medium">SIZE:</span>
                                                    @if ($cartItem->variant && $cartItem->variant->size)
                                                        <span class="px-1.5 py-1 text-sm bg-gray-200">
                                                            {{ $cartItem->variant->size->name }}
                                                        </span>
                                                    @else
                                                        <span class="px-1.5 py-1 text-sm bg-gray-200">
                                                            Free Size
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-base text-gray-800 font-medium">QTY:</span>
                                                    <select
                                                        class="px-1.5 py-1 bg-gray-200 border-none focus:outline-none focus:ring-0 text-sm w-12 cursor-pointer product-qty-selector"
                                                        data-cart-item-id="{{ $cartItem->id }}"
                                                        data-product-id="{{ $cartItem->product_id }}"
                                                        data-variant-id="{{ $cartItem->variant_id }}">
                                                        @for ($i = 1; $i <= 10; $i++)
                                                            <option value="{{ $i }}"
                                                                {{ $cartItem->qty == $i ? 'selected' : '' }}>
                                                                {{ $i }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="flex flex-wrap items-center justify-start gap-2">
                                                <button
                                                    class="remove-item text-sm underline uppercase font-medium tracking-wider hover:text-gray-600"
                                                    data-item-id="{{ $cartItem->id }}">
                                                    REMOVE ITEM
                                                </button>
                                                @if ($cartItem->variant && $cartItem->variant->stock_quantity < 10)
                                                    <span class="text-red-600 ">Low stock
                                                        {{ $cartItem->variant->stock_quantity }}*</span>
                                                @elseif($cartItem->product->stock_quantity < 10)
                                                    <span class="text-red-600 ">Low stock
                                                        {{ $cartItem->product->stock_quantity }}*</span>
                                                @endif

                                            </div>

                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="lg:w-[40%] xl:w-[42%] bg-white">
                    <h1 class="text-2xl font-bold mb-4 uppercase ms-6 font-megumi">Order Summary</h1>

                    <div class="px-6 pb-6">
                        <hr class="block lg:w-auto lg:h-[2px] lg:bg-gray-200 lg:border-none lg:mx-0">
                        <div class="space-y-3 my-4">
                            <div class="flex justify-between">
                                <span>Order value</span>
                                <span class="font-semibold" id="order-value-total">$0.00</span>
                            </div>
                            <div class="flex justify-between parentDiscount">
                                <span>Discount</span>
                                <span class="font-semibold text-green-600" id="total-discount">{{ config('app.currency_symbol') }}0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="uppercase">VAT {{ round(config('app.vat_rate', 0.05) * 100) }}%</span>
                                <span class="font-semibold" id="vat-amount">{{ config('app.currency_symbol') }}0.00</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span>Coupon discount</span>
                                <div id="cart-coupon-apply-wrapper">
                                    <button type="button"
                                        onclick="document.getElementById('cart-coupon-apply-wrapper').classList.add('hidden'); document.getElementById('cart-coupon-field').classList.remove('hidden')"
                                        class="text-sm font-semibold underline hover:no-underline">
                                        Apply coupon
                                    </button>
                                </div>
                                <div id="cart-coupon-field" class="hidden flex gap-2 items-center">
                                    <input type="text" id="cart-coupon-input" placeholder="Enter code"
                                        class="text-sm p-2 border border-black focus:outline-none focus:border-black w-32 uppercase"
                                        maxlength="7">
                                    <button type="button"
                                        onclick="alert('Applying coupon: ' + document.getElementById('cart-coupon-input').value)"
                                        class="text-sm font-semibold underline hover:no-underline">
                                        Apply
                                    </button>
                                    <button type="button"
                                        onclick="document.getElementById('cart-coupon-field').classList.add('hidden'); document.getElementById('cart-coupon-apply-wrapper').classList.remove('hidden'); document.getElementById('cart-coupon-input').value = ''"
                                        class="text-sm font-semibold px-2 hover:bg-gray-100">
                                        ✕
                                    </button>
                                </div>
                            </div>

                            <div class="flex justify-between">
                                <span>Shipping</span>
                                <span class="font-semibold">FREE</span>
                            </div>
                        </div>

                        <div class="flex justify-between font-semibold border-t-2 border-gray-200 pt-4 text-lg">
                            <span>Total Price</span>
                            <span id="total-price">$0.00</span>
                        </div>

                        <form method="POST" action="{{ route('checkout.index') }}" id="checkoutForm">
                            @csrf
                            <input type="hidden" name="selected_items" id="selectedItemsInput">
                            <button type="submit"
                                class="w-full bg-black text-white py-3 px-4 font-medium focus:outline-none focus:bg-gray-800 mt-6 mb-4 inline-block text-center">
                                Proceed to Checkout
                            </button>
                        </form>

                        <div class="text-center text-sm">
                            <a href="#" class="underline">Delivery & Return Policy</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex-1 text-center py-12">
                    <h2 class="text-3xl font-bold py-4">Your bag is empty</h2>
                    <a href="{{ route('home') }}"
                        class="bg-black text-lg text-white px-6 py-3 mt-4 inline-block">Continue
                        Shopping</a>
                </div>
            @endif
        </div>
    </div>
@endsection

