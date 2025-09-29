@extends('layouts.master-layout')
@section('title', 'Checkout')
@push('styles')
    <style>
        .scrollable-items {
            scrollbar-width: thin;
            scrollbar-color: #000 #f1f1f1;
        }

        .scrollable-items::-webkit-scrollbar {
            width: 4px;
        }

        .scrollable-items::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .scrollable-items::-webkit-scrollbar-thumb {
            background-color: #000;
            border-radius: 2px;
        }
    </style>
@endpush
@section('content')

    <div class="max-w-7xl mx-auto sm:px-6 md:px-10 lg:px-8 py-16">
        {{-- Breadcrumbs --}}
        {{-- @php
    $breadcrumbs = [
    ['title' => 'Home', 'url' => '/'],
    ['title' => 'T-shirt', 'url' => '/categories/footwear'],
    ['title' => 'Premium Comfort']
    ];
    @endphp

    <x-breadcrumbs :breadcrumbs="$breadcrumbs" /> --}}

        {{-- End Breadcrumbs --}}
        @php
            echo '<pre>';
            print_r($cartItems);
            echo '</pre>';
        @endphp
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Column - Checkout Form -->
            <div class="lg:w-[58%] bg-white">
                <h1 class="text-2xl font-semibold mb-8 uppercase  font-megumi ">CheckOut</h1>
                <hr class="w-1/2">
                <div class="pb-6 pt-8">
                    <!-- Contact and Delivery Information -->
                    <h2 class="text-lg font-semibold mb-10">Delivery</h2>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="relative">
                            <label for="full-name"
                                class="absolute -top-3 left-4 bg-white px-1 text-black text-base font-medium">
                                Full Name
                            </label>
                            <input type="text" id="full-name" placeholder="Full Name"
                                class="w-full border border-[#8C8C8C] px-6 py-5 text-black text-base font-normal placeholder-[#8C8C8C] outline-none capitalize">
                        </div>
                        <div class="relative">
                            <label for="phone"
                                class="absolute -top-3 left-4 bg-white px-1 text-black text-base font-medium">
                                Phone*
                            </label>
                            <input type="tel" id="phone" placeholder="Enter your phone number"
                                class="w-full border border-[#8C8C8C] px-6 py-5 text-black text-base font-normal placeholder-[#8C8C8C] outline-none"
                                required>
                        </div>
                    </div>

                    <div class="relative w-full mb-6">
                        <label for="address" class="absolute -top-3 left-4 bg-white px-1 text-black text-base font-medium">
                            Address
                        </label>
                        <input type="text" id="address" placeholder="Address"
                            class="w-full border border-[#8C8C8C] px-6 py-5 text-black text-base font-normal placeholder-[#8C8C8C] outline-none pr-10">
                    </div>

                    <div class="relative w-full mb-6">
                        <label for="apartment"
                            class="absolute -top-3 left-4 bg-white px-1 text-black text-base font-medium">
                            Apartment (optional)
                        </label>
                        <input type="text" id="apartment" placeholder="Apartment, suite, etc. (optional)"
                            class="w-full border border-[#8C8C8C] px-6 py-5 text-black text-base font-normal placeholder-[#8C8C8C] outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="relative">
                            <label for="city"
                                class="absolute -top-3 left-4 bg-white px-1 text-black text-base font-medium">
                                City
                            </label>
                            <input type="text" id="city" placeholder="City"
                                class="w-full border border-[#8C8C8C] px-6 py-5 text-black text-base font-normal placeholder-[#8C8C8C] outline-none">
                        </div>
                        <div class="relative">
                            <label for="postcode"
                                class="absolute -top-3 left-4 bg-white px-1 text-black text-base font-medium">
                                Postcode (optional)
                            </label>
                            <input type="text" id="postcode" placeholder="Postcode (optional)"
                                class="w-full border border-[#8C8C8C] px-6 py-5 text-black text-base font-normal placeholder-[#8C8C8C] outline-none">
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <h2 class="text-lg font-semibold mb-4">Payment</h2>
                    <div class="space-y-4 mb-8" x-data="{ selectedPayment: '' }">
                        <div class="flex items-center justify-between p-5 border border-[#8C8C8C]">
                            <label for="cod" class="flex-1 text-base">Cash on Delivery (COD)</label>
                            <input type="radio" id="cod" name="payment" value="cod" x-model="selectedPayment"
                                class="w-5 h-5 border border-[#8C8C8C] appearance-none rounded-none checked:bg-black checked:border-transparent focus:offset-0 focus:outline-none focus:ring-0">
                        </div>

                        <div class="flex items-center justify-between p-5 border border-[#8C8C8C]">
                            <label for="bkash" class="flex-1 text-base">Bkash</label>
                            <input type="radio" id="bkash" name="payment" value="bkash" x-model="selectedPayment"
                                class="w-5 h-5 border border-[#8C8C8C] appearance-none rounded-none checked:bg-black checked:border-transparent focus:offset-0 focus:outline-none focus:ring-0">
                        </div>

                        <div class="flex items-center justify-between p-5 border border-[#8C8C8C]">
                            <label for="mobile-banking" class="flex-1 text-base">Mobile Banking</label>
                            <input type="radio" id="mobile-banking" name="payment" value="mobile-banking"
                                x-model="selectedPayment"
                                class="w-5 h-5 border border-[#8C8C8C] appearance-none rounded-none checked:bg-black checked:border-transparent focus:offset-0 focus:outline-none focus:ring-0">
                        </div>

                        <div class="flex items-center justify-between p-5 border border-[#8C8C8C]">
                            <label for="card" class="flex-1 text-base">Credit Card/Debit Card</label>
                            <input type="radio" id="card" name="payment" value="card" x-model="selectedPayment"
                                class="w-5 h-5 border border-[#8C8C8C] appearance-none rounded-none checked:bg-black checked:border-transparent focus:offset-0 focus:outline-none focus:ring-0">
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="mb-8 mt-10">
                        <div class="flex items-start">
                            <input type="checkbox" id="same-address"
                                class="mt-1 mr-3 w-5 h-5 border border-[#8C8C8C] appearance-none checked:bg-black checked:border-transparent focus:offset-0 focus:outline-none focus:ring-0"
                                checked>
                            <label for="same-address" class="text-lg">Billing and delivery address are the same</label>
                        </div>
                    </div>

                    <div class="">
                        <p class="text-base text-black ">
                            By continuing, you agree to <a href="#" class="underline hover:no-underline">ZENO's
                                General Terms and Conditions</a>.
                        </p>
                    </div>

                    <!-- Order Button -->
                    <button
                        class="w-full bg-black text-white py-5 px-4 text-xl uppercase tracking-[2px] font-medium focus:outline-none focus:bg-gray-800 my-3">
                        Complete Purchase
                    </button>

                    <div class="flex items-center justify-start">
                        <svg width="18" height="18" viewBox="0 0 16 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.5 5.21224V4.50024C11.5 3.57199 11.1313 2.68175 10.4749 2.02537C9.8185 1.36899 8.92826 1.00024 8 1.00024C7.07174 1.00024 6.1815 1.36899 5.52513 2.02537C4.86875 2.68175 4.5 3.57199 4.5 4.50024V5.21224C4.05468 5.4066 3.67565 5.72651 3.40925 6.13285C3.14285 6.53919 3.00064 7.01436 3 7.50024V10.5002C3.00079 11.163 3.26444 11.7985 3.73311 12.2671C4.20178 12.7358 4.8372 12.9995 5.5 13.0002H10.5C11.1628 12.9995 11.7982 12.7358 12.2669 12.2671C12.7356 11.7985 12.9992 11.163 13 10.5002V7.50024C12.9994 7.01436 12.8571 6.53919 12.5908 6.13285C12.3244 5.72651 11.9453 5.4066 11.5 5.21224ZM5.5 4.50024C5.5 3.8372 5.76339 3.20132 6.23223 2.73248C6.70107 2.26364 7.33696 2.00024 8 2.00024C8.66304 2.00024 9.29893 2.26364 9.76777 2.73248C10.2366 3.20132 10.5 3.8372 10.5 4.50024V5.00024H5.5V4.50024ZM12 10.5002C12 10.8981 11.842 11.2796 11.5607 11.5609C11.2794 11.8422 10.8978 12.0002 10.5 12.0002H5.5C5.10218 12.0002 4.72064 11.8422 4.43934 11.5609C4.15804 11.2796 4 10.8981 4 10.5002V7.50024C4 7.10242 4.15804 6.72089 4.43934 6.43958C4.72064 6.15828 5.10218 6.00024 5.5 6.00024H10.5C10.8978 6.00024 11.2794 6.15828 11.5607 6.43958C11.842 6.72089 12 7.10242 12 7.50024V10.5002Z"
                                fill="black" />
                            <path
                                d="M8 8.00024C7.86739 8.00024 7.74021 8.05292 7.64645 8.14669C7.55268 8.24046 7.5 8.36764 7.5 8.50024V9.50024C7.5 9.63285 7.55268 9.76003 7.64645 9.8538C7.74021 9.94757 7.86739 10.0002 8 10.0002C8.13261 10.0002 8.25979 9.94757 8.35355 9.8538C8.44732 9.76003 8.5 9.63285 8.5 9.50024V8.50024C8.5 8.36764 8.44732 8.24046 8.35355 8.14669C8.25979 8.05292 8.13261 8.00024 8 8.00024Z"
                                fill="black" />
                        </svg>
                        <label for="security" class="text-sm">All data will be kept secure and encrypted</label>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="lg:w-[42%] bg-white">
                <h1 class="text-2xl font-semibold mb-8 uppercase  font-megumi ">Order Summary</h1>
                <hr class="w-3/2">
                <div class=" py-6">
                    <!-- Order Totals -->
                    <div class="space-y-3 my-4">
                        <div class="flex justify-between">
                            <span>Order value</span>
                            <span class="font-semibold">$69.97</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Discount</span>
                            <span class="font-semibold text-green-600">- $9.97</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="uppercase">VAT 5%</span>
                            <span class="font-semibold">$3.00</span>
                        </div>


                        <!-- Coupon Section -->
                        <div class="flex justify-between items-center" x-data="{
                            showCouponField: false,
                            couponCode: '',
                            applyCoupon() {
                                alert(`Applying coupon: ${this.couponCode}`);
                            }
                        }">
                            <span>Coupon discount</span>
                            <template x-if="!showCouponField">
                                <button @click="showCouponField = true"
                                    class="text-sm font-semibold underline hover:no-underline">
                                    Apply coupon
                                </button>
                            </template>
                            <template x-if="showCouponField">
                                <div class="flex gap-2 items-center">
                                    <input type="text" placeholder="Enter code" x-model="couponCode"
                                        class="text-sm p-2 border border-black focus:outline-none focus:border-black w-32 uppercase"
                                        maxlength="7">
                                    <button @click="applyCoupon()"
                                        class="text-sm font-semibold underline hover:no-underline">
                                        Apply
                                    </button>
                                    <button @click="showCouponField = false; couponCode = ''"
                                        class="text-sm font-semibold px-2 hover:bg-gray-100">
                                        ✕
                                    </button>
                                </div>
                            </template>
                        </div>


                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span class="font-semibold">FREE</span>
                        </div>
                    </div>


                    <!-- Total Price -->
                    <div class="flex justify-between font-semibold border-t-2 border-gray-200 pt-4 text-lg">
                        <span>Total Price</span>
                        <span>$63.00</span>
                    </div>


                    <!-- Items List with Scrollable Container -->
                    <div class="mt-6">
                        <h3 class="text-2xl font-semibold mb-4 uppercase tracking-wide font-megumi ">ITEMS YOU ORDERED</h3>


                        <div class="scrollable-items max-h-[calc(100vh-20px)] overflow-y-auto">
                            <!-- Product Items Container -->
                            <div class="space-y-2">
                                <!-- Product Item 1 -->
                                <div class="flex gap-4 items-start py-4 border-t-2 border-gray-200">
                                    <div class="w-28 h-28 flex-shrink-0 bg-gray-100 overflow-hidden">
                                        <img class="w-full h-full object-cover" src="{{ asset('images/8.jpg') }}"
                                            alt="Denim High Premium">
                                    </div>
                                    <div class="flex-1 min-w-0 font-semibold">
                                        <p class="font-semibold text-lg mb-1">Denim High Premium</p>
                                        <p class="text-xs text-gray-800 mb-1">COLOR: Black Nue</p>
                                        <p class="text-xs text-gray-800 mb-1">SIZE: XXL</p>
                                        <p class="text-xs text-gray-800 mb-1">QTY: 96</p>
                                        <p class="text-xs text-gray-800">PRICE: $42.50</p>
                                    </div>
                                </div>
                                <!-- Product Item 2 -->
                                <div class="flex gap-4 items-start py-4 border-t-2 border-gray-200">
                                    <div class="w-28 h-28 flex-shrink-0 bg-gray-100 overflow-hidden">
                                        <img class="w-full h-full object-cover" src="{{ asset('images/8.jpg') }}"
                                            alt="Denim High Premium">
                                    </div>
                                    <div class="flex-1 min-w-0 font-semibold">
                                        <p class="font-semibold text-lg mb-1">Denim High Premium</p>
                                        <p class="text-xs text-gray-800 mb-1">COLOR: Black Nue</p>
                                        <p class="text-xs text-gray-800 mb-1">SIZE: XXL</p>
                                        <p class="text-xs text-gray-800 mb-1">QTY: 96</p>
                                        <p class="text-xs text-gray-800">PRICE: $42.50</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('checkout', () => ({
                paymentMethod: '',
                showCouponField: false,


                selectPayment(method) {
                    this.paymentMethod = method;
                    // Uncheck all other payment methods
                    document.querySelectorAll('input[name="payment"]').forEach(input => {
                        if (input.id !== method) {
                            input.checked = false;
                        }
                    });
                },


                toggleCouponField() {
                    this.showCouponField = !this.showCouponField;
                }
            }));
        });
    </script>
@endpush
