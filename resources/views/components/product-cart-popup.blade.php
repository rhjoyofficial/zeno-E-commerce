<div id="product-cart-popup" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-2 sm:p-4">
    <div class="bg-white w-full max-w-2xl max-h-[95vh] overflow-hidden relative shadow-lg">

        <button id="popup-close"
            class="absolute top-2 right-2 z-20 w-6 h-6 flex items-center justify-center text-gray-700 hover:text-gray-50 hover:bg-black transition-colors">
            <i class="fas fa-times text-base"></i>
        </button>

        <div class="flex flex-col lg:flex-row h-full">
            <div class="lg:w-1/2 flex flex-col items-center justify-center bg-gray-50 p-4">
                <div class="mb-4 w-full max:h-80">
                    <img id="popup-main-image" src="" alt="Product"
                        class="w-full h-full object-cover border mx-auto">
                </div>

                <div id="popup-thumbnails" class="flex gap-2 overflow-x-auto w-full justify-center p-1"></div>
            </div>

            <div class="lg:w-1/2 p-4 overflow-y-auto m-auto">
                <div class="mb-3">
                    <h2 id="popup-title" class="text-2xl font-bold text-black mb-1"></h2>
                    <p id="popup-description" class="text-gray-600 text-sm"></p>
                </div>

                <div class="mb-3">
                    <span id="popup-price" class="text-xl font-bold text-black"></span>
                </div>

                <div id="popup-variants">
                    <div id="popup-colors" class="mb-3 hidden">
                        <h3 class="text-sm font-semibold text-black mb-2">Color</h3>
                        <div id="color-options" class="flex flex-wrap gap-2"></div>
                    </div>

                    <div id="popup-sizes" class="mb-3 hidden">
                        <h3 class="text-sm font-semibold text-black mb-2">Size</h3>
                        <div id="size-options" class="grid grid-cols-4 gap-1"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <h3 class="text-sm font-semibold text-black mb-2">Quantity</h3>
                    <div class="flex items-center">
                        <div class="flex items-center border-2 border-gray-300 overflow-hidden">
                            <button id="qty-decrease"
                                class="px-2 py-1 text-gray-600 hover:text-gray-800 transition-colors">
                                <i class="fas fa-minus text-xs"></i>
                            </button>
                            <input type="text" id="popup-quantity" value="1"
                                class="w-12 text-center border-0 py-1 font-medium focus:outline-none text-sm"
                                min="1" max="99">
                            <button id="qty-increase"
                                class="px-2 py-1 text-gray-600 hover:text-gray-800 transition-colors">
                                <i class="fas fa-plus text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 mb-3">
                    <button id="popup-add-to-cart"
                        class="flex-1 px-4 py-2 bg-black text-white font-medium flex items-center justify-center gap-2 hover:bg-gray-800 transition-colors text-sm">
                        <i class="fas fa-shopping-cart"></i>
                        Add to Cart
                    </button>
                    <button
                        class="flex-1 px-4 py-2 border-2 border-black text-black font-medium flex items-center justify-center gap-2 hover:bg-gray-100 transition-colors text-sm">
                        <i class="fas fa-heart"></i>
                        Wishlist
                    </button>
                </div>

                <div id="popup-features" class="hidden mb-3 grid grid-cols-2 gap-2 text-sm text-gray-600 border-t pt-3">
                    <div class="flex items-center gap-1">
                        <i class="fas fa-truck text-black text-xs"></i>
                        Free Shipping
                    </div>
                    <div class="flex items-center gap-1">
                        <i class="fas fa-undo text-black text-xs"></i>
                        30-day Returns
                    </div>
                    <div class="flex items-center gap-1">
                        <i class="fas fa-shield-alt text-black text-xs"></i>
                        2-Year Warranty
                    </div>
                    <div class="flex items-center gap-1">
                        <i class="fas fa-headset text-black text-xs"></i>
                        24/7 Support
                    </div>
                </div>

                <div class="text-center border-t pt-3 mt-3">
                    <a href="#"
                        class="text-black font-medium inline-flex items-center gap-1 hover:text-gray-700 transition-colors text-sm">
                        View Full Product Details
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
