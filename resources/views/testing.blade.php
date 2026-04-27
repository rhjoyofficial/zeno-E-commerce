@extends('layouts.app')
@section('title', 'Product Variant Popup')
@push('styles')
<style>
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

    .popup-overlay {
        backdrop-filter: blur(8px);
        animation: fadeIn 0.3s ease-out;
    }

    .popup-content {
        animation: slideUp 0.3s ease-out;
    }

    .popup-overlay.closing {
        animation: fadeOut 0.3s ease-out;
    }

    .popup-content.closing {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(50px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes slideDown {
        from {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        to {
            opacity: 0;
            transform: translateY(50px) scale(0.95);
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto py-12">
    <!-- Trigger Button -->
    <div class="text-center">
        <button id="popup-trigger" class="bg-black text-white px-8 py-4 font-semibold">
            <i class="fas fa-shopping-bag mr-2"></i>
            Show Product Popup
        </button>
    </div>
</div>

<!-- Popup Container -->
<div id="popup-container"
    class="fixed inset-0 bg-black/40 popup-overlay flex items-center justify-center z-50 p-4 hidden">
    <div class="bg-white w-full max-w-4xl max-h-[90vh] overflow-hidden popup-content relative">

        <!-- Close Button -->
        <button id="close-popup"
            class="absolute top-4 right-4 z-20 bg-white w-10 h-10 flex items-center justify-center text-gray-600 hover:text-gray-800">
            <i class="fas fa-times text-lg"></i>
        </button>

        <div class="flex flex-col lg:flex-row h-full">
            <!-- Product Image Section -->
            <div class="lg:w-1/2 flex flex-col items-center justify-center bg-gray-50 p-8">
                <!-- Main Product Image -->
                <div class="mb-4">
                    <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80"
                        alt="Premium Headphones" class="w-96 h-96 object-cover">
                </div>

                <!-- Thumbnail Gallery -->
                <div class="flex gap-2">
                    <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80"
                        class="w-24 h-24 object-cover border-2 border-black cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1484704849700-f032a568e944?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80"
                        class="w-24 h-24 object-cover border-2 border-gray-300 cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1583394838336-acd977736f90?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80"
                        class="w-24 h-24 object-cover border-2 border-gray-300 cursor-pointer">
                </div>
            </div>

            <!-- Product Details -->
            <div class="lg:w-1/2 p-8 overflow-y-auto">
                <!-- Product Header -->
                <div class="mb-4">
                    <h2 class="text-2xl font-bold text-black mb-2">Premium Wireless Headphones</h2>
                </div>

                <!-- Price Section -->
                <div class="mb-4">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl font-bold text-black">$199.99</span>
                        <span class="text-lg text-gray-500 line-through">$249.99</span>
                        <span class="bg-red-500 text-white px-2 py-1 text-xs font-medium">Save $50</span>
                    </div>
                </div>

                <!-- Color Selection -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-base font-semibold text-black">Color</h3>
                        <span class="text-sm text-gray-600" id="selected-color">Midnight Red</span>
                    </div>
                    <div class="flex gap-3">
                        <button class="w-10 h-10 border-2 border-black flex items-center justify-center"
                            style="background: #DC2626" title="Midnight Red" data-color="Midnight Red">
                            <i class="fas fa-check text-white text-xs"></i>
                        </button>
                        <button class="w-10 h-10 border-2 border-gray-300 flex items-center justify-center"
                            style="background: #1E40AF" title="Ocean Blue" data-color="Ocean Blue">
                        </button>
                        <button class="w-10 h-10 border-2 border-gray-300 flex items-center justify-center"
                            style="background: #000000" title="Cosmic Black" data-color="Cosmic Black">
                        </button>
                    </div>
                </div>

                <!-- Size Selection -->
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-base font-semibold text-black">Size</h3>
                        <button class="text-black text-sm font-medium">
                            Size Guide
                        </button>
                    </div>
                    <div class="grid grid-cols-4 gap-2">
                        <button class="px-3 py-2 border-2 border-black bg-gray-100 text-black text-sm font-medium"
                            data-size="S">S</button>
                        <button class="px-3 py-2 border-2 border-gray-300 text-gray-700 text-sm font-medium"
                            data-size="M">M</button>
                        <button class="px-3 py-2 border-2 border-gray-300 text-gray-700 text-sm font-medium"
                            data-size="L">L</button>
                        <button class="px-3 py-2 border-2 border-gray-300 text-gray-700 text-sm font-medium"
                            data-size="XL">XL</button>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">Selected: <span id="selected-size">Small</span></p>
                </div>

                <!-- Quantity Selector -->
                <div class="mb-4">
                    <h3 class="text-base font-semibold text-black mb-3">Quantity</h3>
                    <div class="flex items-center">
                        <div class="flex items-center border-2 border-gray-300">
                            <button id="qty-decrease" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" id="quantity" value="1"
                                class="w-12 text-center border-0 py-2 font-medium" min="1" max="99">
                            <button id="qty-increase" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="ml-4 text-sm text-gray-600">
                            <p>Only <span class="font-medium text-orange-600">7 left</span> in stock</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 mb-4">
                    <button id="add-to-cart"
                        class="flex-1 px-6 py-3 bg-black text-white font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-shopping-cart"></i>
                        Add to Cart
                    </button>
                    <button
                        class="flex-1 px-6 py-3 border-2 border-black text-black font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-heart"></i>
                        Wishlist
                    </button>
                </div>

                <!-- View Details Link -->
                <div class="text-center border-t pt-4">
                    <a href="#" class="text-black font-medium inline-flex items-center gap-1">
                        View Full Product Details
                        <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const popupTrigger = document.getElementById('popup-trigger');
        const popupContainer = document.getElementById('popup-container');
        const popupContent = popupContainer.querySelector('.popup-content');
        const closePopup = document.getElementById('close-popup');
        const colorOptions = document.querySelectorAll('.color-option');
        const sizeOptions = document.querySelectorAll('.size-option');
        const qtyIncrease = document.getElementById('qty-increase');
        const qtyDecrease = document.getElementById('qty-decrease');
        const quantityInput = document.getElementById('quantity');
        const addToCartBtn = document.getElementById('add-to-cart');
        
        // Show popup
        popupTrigger.addEventListener('click', function() {
            popupContainer.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
        
        // Close popup function
        function closePopupHandler() {
            popupContainer.classList.add('closing');
            popupContent.classList.add('closing');
            
            setTimeout(() => {
                popupContainer.classList.add('hidden');
                popupContainer.classList.remove('closing');
                popupContent.classList.remove('closing');
                document.body.style.overflow = 'auto';
            }, 300);
        }
        
        closePopup.addEventListener('click', closePopupHandler);
        
        // Close popup when clicking outside
        popupContainer.addEventListener('click', function(e) {
            if (e.target === popupContainer) {
                closePopupHandler();
            }
        });

        // ESC key to close
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !popupContainer.classList.contains('hidden')) {
                closePopupHandler();
            }
        });
        
        // Color selection
        document.querySelectorAll('[data-color]').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('[data-color]').forEach(opt => {
                    opt.classList.remove('border-black');
                    opt.classList.add('border-gray-300');
                    opt.querySelector('.fa-check')?.remove();
                });
                
                this.classList.remove('border-gray-300');
                this.classList.add('border-black');
                this.innerHTML = '<i class="fas fa-check text-white text-xs"></i>';
                
                document.getElementById('selected-color').textContent = this.dataset.color;
            });
        });
        
        // Size selection
        document.querySelectorAll('[data-size]').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('[data-size]').forEach(opt => {
                    opt.classList.remove('border-black', 'bg-gray-100', 'text-black');
                    opt.classList.add('border-gray-300', 'text-gray-700');
                });
                
                this.classList.remove('border-gray-300', 'text-gray-700');
                this.classList.add('border-black', 'bg-gray-100', 'text-black');
                
                const sizeMap = { S: 'Small', M: 'Medium', L: 'Large', XL: 'X-Large' };
                document.getElementById('selected-size').textContent = sizeMap[this.dataset.size];
            });
        });
        
        // Quantity controls
        qtyIncrease.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue < 99) {
                quantityInput.value = currentValue + 1;
            }
        });
        
        qtyDecrease.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
    });
</script>
@endpush