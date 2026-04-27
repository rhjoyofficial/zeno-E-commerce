@extends('layouts.admin')
@section('title', 'Edit Variant - ' . $product->title)
@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Edit Variant</h1>
            <a href="{{ route('admin.products.variants.index', $product) }}"
                class="px-4 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-colors text-base">
                Back to Variants
            </a>
        </div>

        <div class="bg-white border border-gray-200 p-6">
            <form action="{{ route('admin.products.variants.update', [$product, $variant]) }}" method="POST"
                id="editVariantForm">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <!-- Color Selection -->
                    <div>
                        <label for="color_id" class="block text-sm font-medium text-gray-700 mb-1">Color <span
                                class="text-red-500">*</span></label>
                        <select id="color_id" name="color_id"
                            class="color-select mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-black focus:border-black"
                            required>
                            <option value="">Select Color</option>
                            @foreach ($colors as $color)
                            <option value="{{ $color->id }}" {{ old('color_id', $variant->color_id) == $color->id ?
                                'selected' : '' }}>
                                {{ $color->name }}
                            </option>
                            @endforeach
                        </select>
                        <div id="color-error" class="text-sm text-red-600 mt-1 hidden"></div>
                        @error('color_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Size Selection -->
                    <div>
                        <label for="size_id" class="block text-sm font-medium text-gray-700 mb-1">Size <span
                                class="text-red-500">*</span></label>
                        <select id="size_id" name="size_id"
                            class="size-select mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-black focus:border-black"
                            required>
                            <option value="">Select Size</option>
                            @foreach ($sizes as $size)
                            <option value="{{ $size->id }}" {{ old('size_id', $variant->size_id) == $size->id ?
                                'selected' : '' }}>
                                {{ $size->name }}
                            </option>
                            @endforeach
                        </select>
                        <div id="size-error" class="text-sm text-red-600 mt-1 hidden"></div>
                        @error('size_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (৳) <span
                                class="text-red-500">*</span></label>
                        <input type="number" step="0.01" min="0" id="price" name="price"
                            value="{{ old('price', $variant->price) }}"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-black focus:border-black"
                            required>
                        @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock Quantity -->
                    <div>
                        <label for="discount_price" class="block text-sm font-medium text-gray-700 mb-1">Discount
                            Price (৳)</label>
                        <input type="number" step="0.01" min="0" id="discount_price" name="discount_price"
                            value="{{ old('discount_price', $variant->discount_price) }}"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-black focus:border-black">
                        @error('discount_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock Quantity -->
                    <div>
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-1">Stock
                            Quantity <span class="text-red-500">*</span></label>
                        <input type="number" min="0" id="stock_quantity" name="stock_quantity"
                            value="{{ old('stock_quantity', $variant->stock_quantity) }}"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-black focus:border-black"
                            required>
                        @error('stock_quantity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock Alert -->
                    <div>
                        <label for="stock_alert" class="block text-sm font-medium text-gray-700 mb-1">Stock Alert
                            Level</label>
                        <input type="number" min="0" id="stock_alert" name="stock_alert"
                            value="{{ old('stock_alert', $variant->stock_alert) }}"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-black focus:border-black">
                        @error('stock_alert')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SKU -->
                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU <span
                                class="text-red-500">*</span></label>
                        <div class="mt-1 flex">
                            <input type="text" id="sku" name="sku" value="{{ old('sku', $variant->sku) }}"
                                class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                required />
                            <button type="button" id="generateSKU"
                                class="inline-flex items-center border border-l-0 border-gray-300 bg-gray-50 px-3 py-2 text-gray-500 hover:bg-gray-100 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
                                Generate
                            </button>
                        </div>
                        <div id="sku-error" class="text-sm text-red-600 mt-1 hidden"></div>
                        @error('sku')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span
                                class="text-red-500">*</span></label>
                        <select id="status" name="status"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-black focus:border-black"
                            required>
                            <option value="active" {{ old('status', $variant->status) == 'active' ? 'selected' : '' }}>
                                Active</option>
                            <option value="inactive" {{ old('status', $variant->status) == 'inactive' ? 'selected' : ''
                                }}>Inactive</option>
                        </select>
                        @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="px-6 py-2 bg-black text-white hover:bg-gray-800 transition-colors text-base">
                        Update Variant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
            const colorSelect = document.getElementById('color_id');
            const sizeSelect = document.getElementById('size_id');
            const skuInput = document.getElementById('sku');
            const generateSKUBtn = document.getElementById('generateSKU');
            const colorError = document.getElementById('color-error');
            const sizeError = document.getElementById('size-error');
            const skuError = document.getElementById('sku-error');
            let skuValidationTimeout;
            let combinationValidationTimeout;

            // Validate variant combination (color + size)
            function validateVariantCombination() {
                const colorId = colorSelect.value;
                const sizeId = sizeSelect.value;
                
                // Reset errors
                colorError.classList.add('hidden');
                sizeError.classList.add('hidden');
                colorError.textContent = '';
                sizeError.textContent = '';
                
                if (!colorId || !sizeId) return;
                
                // Check if combination already exists (excluding current variant)
                fetch('{{ route("admin.products.variants.checkCombinationEdit", [$product->id, $variant->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        color_id: colorId,
                        size_id: sizeId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        colorError.textContent = 'This color and size combination already exists for this product.';
                        colorError.classList.remove('hidden');
                        sizeError.textContent = 'This color and size combination already exists for this product.';
                        sizeError.classList.remove('hidden');
                        
                        // Disable form submission
                        colorSelect.setCustomValidity('Combination exists');
                        sizeSelect.setCustomValidity('Combination exists');
                    } else {
                        colorSelect.setCustomValidity('');
                        sizeSelect.setCustomValidity('');
                    }
                })
                .catch(error => {
                    console.error('Error validating variant combination:', error);
                });
            }

            // Validate SKU
            function validateSku(sku) {
                // Clear previous timeout to prevent multiple fetch calls
                clearTimeout(skuValidationTimeout);

                // Only start validation if the input is long enough
                if (sku.length >= 3) {
                    // Set a new timeout to wait for user to stop typing
                    skuValidationTimeout = setTimeout(() => {
                        fetch('{{ route("admin.products.variants.checkSkuEdit", [$product->id, $variant->id]) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ sku: sku })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.isAvailable) {
                                skuError.textContent = data.message;
                                skuError.classList.remove('hidden');
                                skuInput.setCustomValidity('SKU already exists');
                            } else {
                                skuError.classList.add('hidden');
                                skuInput.setCustomValidity('');
                            }
                        })
                        .catch(error => {
                            console.error('Error validating SKU:', error);
                        });
                    }, 500);
                } else {
                    // Reset validation state if input is too short
                    skuError.classList.add('hidden');
                    skuInput.setCustomValidity('');
                }
            }

            // Generate SKU
            function generateSku() {
                const randomSKU = 'PROD-VAR-' + Math.floor(10000 + Math.random() * 99999);
                skuInput.value = randomSKU;
                validateSku(randomSKU);
            }

            // Event listeners
            colorSelect.addEventListener('change', function() {
                clearTimeout(combinationValidationTimeout);
                combinationValidationTimeout = setTimeout(validateVariantCombination, 500);
            });

            sizeSelect.addEventListener('change', function() {
                clearTimeout(combinationValidationTimeout);
                combinationValidationTimeout = setTimeout(validateVariantCombination, 500);
            });

            skuInput.addEventListener('input', function() {
                validateSku(this.value.trim());
            });

            if (generateSKUBtn) {
                generateSKUBtn.addEventListener('click', generateSku);
            }

            // Clear validation when form is submitted
            const editVariantForm = document.getElementById('editVariantForm');
            if (editVariantForm) {
                editVariantForm.addEventListener('submit', function() {
                    colorSelect.setCustomValidity('');
                    sizeSelect.setCustomValidity('');
                    skuInput.setCustomValidity('');
                });
            }

            // Initial validation if values are already set
            if (colorSelect.value && sizeSelect.value) {
                validateVariantCombination();
            }
            if (skuInput.value) {
                validateSku(skuInput.value);
            }
        });
</script>
@endsection
