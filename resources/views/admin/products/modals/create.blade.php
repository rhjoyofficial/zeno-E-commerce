@extends('layouts.admin')
@section('title', 'Create New Product')
@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white shadow-md p-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Add New Product</h2>
            <a href="{{ route('admin.products.index') }}"
                class="flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Back to Products
            </a>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
        <div class="px-4 py-3 mb-4 bg-red-50 text-red-800 border border-red-200">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z"
                        clip-rule="evenodd" />
                </svg>
                <h3 class="font-medium">Please fix the following errors:</h3>
            </div>
            <ul class="mt-2 pl-5 list-disc">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="productForm" method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data"
            class="space-y-2 divide-y divide-gray-200">
            @csrf

            <!-- Basic Information Section -->
            <div class="pt-3">
                <div class="mb-6">
                    <h3 class="text-xl font-semibold leading-6 text-gray-900">Basic Information</h3>
                    <p class="mt-1 text-sm text-gray-500">Essential details about your product.</p>
                </div>

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-12">
                    <!-- Category -->
                    <div class="sm:col-span-2">
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Category <span
                                class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <select id="category_id" name="category_id" required
                                class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id')==$category->id ? 'selected' :
                                    '' }}>
                                    {{ $category->category_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Brand -->
                    <div class="sm:col-span-2">
                        <label for="brand_id" class="block text-sm font-medium text-gray-700">Brand</label>
                        <div class="mt-1">
                            <select id="brand_id" name="brand_id"
                                class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Select Brand</option>
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id')==$brand->id ? 'selected' : '' }}>
                                    {{ $brand->brand_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="sm:col-span-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Product Title <span
                                class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <input type="text" id="title" value="{{ old('title') }}" name="title"
                                placeholder="Enter product title" required
                                class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <p id="title-error" class="mt-1 text-sm text-red-600 hidden"></p>
                    </div>

                    <!-- SKU -->
                    <div class="sm:col-span-4">
                        <label for="sku" class="block text-sm font-medium text-gray-700">SKU <span
                                class="text-red-500">*</span></label>
                        <div class="mt-1 flex ">
                            <input type="text" id="sku" name="sku" value="{{ old('sku') }}" required
                                class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
                            <button type="button" id="generateSKU"
                                class="inline-flex items-center border border-l-0 border-gray-300 bg-gray-50 px-3 py-2 text-gray-500 hover:bg-gray-100 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
                                Generate
                            </button>
                        </div>
                        <p id="sku-error" class="mt-1 text-sm text-red-600 hidden"></p>
                    </div>

                    <!-- Price -->
                    <div class="sm:col-span-2">
                        <label for="price" class="block text-sm font-medium text-gray-700">Base Price <span
                                class="text-red-500">*</span></label>
                        <div class="mt-1 relative ">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="text" step="0.01" min="0" id="price" name="price" value="{{ old('price') }}"
                                required
                                class="block w-full pl-7 pr-12 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">USD</span>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Quantity -->
                    <div class="sm:col-span-2">
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Stock
                            Quantity</label>
                        <div class="mt-1 relative ">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">PCS</span>
                            </div>
                            <input type="text" min="0" id="stock_quantity" name="stock_quantity"
                                value="{{ old('stock_quantity') }}"
                                class="block w-full pl-10 pr-4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <!-- Stock Alert -->
                    <div class="sm:col-span-2">
                        <label for="stock_alert" class="block text-sm font-medium text-gray-700">Low Stock
                            Alert</label>
                        <div class="mt-1 relative ">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">PCS</span>
                            </div>
                            <input type="text" min="0" id="stock_alert" name="stock_alert"
                                value="{{ old('stock_alert') }}" placeholder="Ex: 50"
                                class="block w-full pl-10 pr-4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <!-- Product Tags -->
                    <div class="sm:col-span-4">
                        <label class="block text-sm font-medium text-gray-700">Product Tags</label>
                        <div class="mt-1 relative">
                            <!-- Tags container with input -->
                            <div id="tag-container"
                                class="flex flex-wrap items-center gap-2 p-2 min-h-8 border border-gray-300 focus-within:ring-1 focus-within:ring-indigo-500 focus-within:border-indigo-500">
                                <!-- Selected tags will appear here -->
                                @foreach (old('tags', []) as $selectedTagId)
                                @php $selectedTag = $tags->firstWhere('id', $selectedTagId); @endphp
                                @if ($selectedTag)
                                <span
                                    class="tag-chip inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $selectedTag->name }}
                                    <input type="hidden" name="tags[]" value="{{ $selectedTag->id }}">
                                    <button type="button"
                                        class="ml-1.5 inline-flex text-indigo-600 hover:text-indigo-900 focus:outline-none">×</button>
                                </span>
                                @endif
                                @endforeach
                                <input id="tag-input" type="text"
                                    class="flex-1 min-w-[100px] border-0 focus:ring-0 p-0 text-sm"
                                    placeholder="Type to add tags" autocomplete="off">
                            </div>

                            <!-- Tags suggestions dropdown -->
                            <div id="tag-suggestions"
                                class="absolute z-10 mt-1 w-full bg-white shadow-lg border border-gray-300 py-1 overflow-auto max-h-60 hidden">
                                @foreach ($tags as $tag)
                                <div class="tag-option px-3 py-2 text-sm text-gray-700 hover:bg-indigo-100 cursor-pointer"
                                    data-value="{{ $tag->id }}">{{ $tag->name }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="sm:col-span-2">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <div class="mt-1">
                            <select id="status" name="status"
                                class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="active" {{ old('status', 'active' )=='active' ? 'selected' : '' }}>
                                    Active</option>
                                <option value="inactive" {{ old('status')=='inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- Variants status --}}
                    <div class="sm:col-span-12">
                        <div class="flex items-center">
                            <label for="has_variants" class="mr-2 block text-sm font-medium text-gray-700">This
                                product has variants</label>

                            <input type="hidden" name="has_variants" value="0">

                            <input type="checkbox" id="has_variants" name="has_variants" value="1"
                                class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500" {{
                                old('has_variants')=='1' ? 'checked' : '' }}>
                        </div>
                    </div>

                    <!-- Short Description -->
                    <div class="sm:col-span-6">
                        <label for="short_des" class="block text-sm font-medium text-gray-700">Short
                            Description</label>
                        <div class="mt-1">
                            <textarea id="short_des" name="short_description" rows="9" maxlength="500"
                                placeholder="Enter a brief description (max 500 characters)"
                                class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('short_des') }}</textarea>
                        </div>
                    </div>

                    <!-- Image Upload Box -->
                    <div class="sm:col-span-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Feature Image <span
                                class="text-red-500">*</span></label>

                        <!-- Clickable Upload Area -->
                        <div id="image-upload-container" class="relative group cursor-pointer">
                            <!-- Upload Box (shown when no image) -->
                            <div id="upload-box"
                                class="flex flex-col items-center justify-center h-48 border-2 border-dashed border-gray-300 bg-gray-50 hover:bg-gray-100 transition-colors {{ old('primary_image') ? 'hidden' : '' }}">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48" aria-hidden="true">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="mt-2 text-sm text-center">
                                    <p class="font-medium text-indigo-600 group-hover:text-indigo-500">Click to upload
                                    </p>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 1MB</p>
                                </div>
                                <input id="primary_image" name="primary_image" type="file"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                            </div>

                            <!-- Image Preview (shown when image exists) -->
                            <div id="image-preview-container"
                                class="relative h-48 overflow-hidden bg-gray-100 {{ old('primary_image') ? '' : 'hidden' }}">
                                <img id="image-preview"
                                    src="{{ old('primary_image') ? URL::temporaryUrl(Storage::path(old('primary_image')), now()->addMinutes(5)) : '' }}"
                                    alt="Preview" class="w-full h-full object-contain">

                                <!-- Remove Button -->
                                <div
                                    class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-3">
                                    <button type="button" id="remove-image-btn"
                                        class="w-full py-1.5 px-3 bg-white/90 text-sm font-medium text-gray-800 hover:bg-white transition-colors flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Remove Image
                                    </button>
                                </div>
                            </div>

                            <!-- Error Message -->
                            <p id="image-error" class="mt-1 text-sm text-red-600 hidden"></p>
                        </div>
                    </div>

                    <!-- Multiple Images Upload Section -->
                    <div class="sm:col-span-12">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Additional Images</label>
                        <p class="text-xs text-gray-500 mb-3">Select up to 5 images at once (max 500KB each)</p>

                        <!-- Image previews container -->
                        <div id="image-previews-container"
                            class="mb-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 hidden">
                            <!-- Image previews will be added here dynamically -->
                        </div>

                        <!-- Dropzone for multiple image selection -->
                        <div id="multiple-images-dropzone"
                            class="relative border-2 border-dashed border-gray-300  p-6 text-center hover:border-indigo-500 transition-colors cursor-pointer">
                            <input type="file" id="additional_images" name="additional_images[]" multiple
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48" aria-hidden="true">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="mt-4 flex flex-col text-sm text-gray-600">
                                <p class="font-medium text-indigo-600">Click to upload or drag and drop</p>
                                <p class="text-xs">PNG, JPG, GIF up to 500KB each</p>
                            </div>
                        </div>

                        <p id="multiple-images-error" class="mt-2 text-sm text-red-600 hidden"></p>
                    </div>

                </div>

                <!-- Form Actions -->
                <div class="pt-8" x-data="{ showConfirmation: false, formChanged: false }">
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelBtn"
                            @click="if(formChanged) { showConfirmation = true } else { window.location.href = '{{ route('admin.products.index') }}' }"
                            class="border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Cancel
                        </button>
                        <button type="button" id="submitBtn" @click="showConfirmation = true"
                            class="inline-flex justify-center border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Save Product
                        </button>
                    </div>

                    <!-- Confirmation Modal -->
                    <div x-show="showConfirmation" x-cloak x-transition
                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
                        <div class="bg-white p-6 max-w-md w-full">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Confirm Submission</h3>
                            <p class="text-sm text-gray-500 mb-6">Are you sure you want to submit this product? These
                                changes will be visible to customers immediately.</p>
                            <div class="flex justify-end space-x-3">
                                <button type="button" @click="showConfirmation = false"
                                    class="px-4 py-2 border border-gray-300  text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 border border-transparent  text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Confirm
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
        </form>
    </div>
</div>

{{-- Generate SKU --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const generateSKUBtn = document.getElementById('generateSKU');
    const skuInput = document.getElementById('sku');
    const skuError = document.getElementById('sku-error');
    let skuValidationTimeout;

    // This function handles the actual validation
    const checkSkuAvailability = (sku) => {
        console.log('Validating SKU:', sku);
        fetch('{{ route("admin.products.checkSku") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ sku: sku })
        })
        .then(response => response.json())
        .then(data => {  
            console.log('SKU validation response:', data);          
            if (!data.isAvailable) {
                skuError.textContent = 'This SKU already exists. Please use a different SKU.';
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
    };

    skuInput.addEventListener('input', function() {
        const sku = this.value.trim();
        // Clear previous timeout to prevent multiple fetch calls
        clearTimeout(skuValidationTimeout);

        // Only start validation if the input is long enough
        if (sku.length >= 3) {
            // Set a new timeout to wait for user to stop typing
            skuValidationTimeout = setTimeout(() => {
                checkSkuAvailability(sku);
            }, 500);
        } else {
            // Reset validation state if input is too short
            skuError.classList.add('hidden');
            skuInput.setCustomValidity('');
        }
    });

    // Handle the "Generate SKU" button separately
    if (generateSKUBtn) {
        generateSKUBtn.addEventListener('click', function() {
            const randomSKU = 'PROD-' + Math.floor(10000 + Math.random() * 99999);
            skuInput.value = randomSKU;
            checkSkuAvailability(randomSKU);
        });
    }

    // Clear validation when form is submitted
    const productForm = document.getElementById('productForm');
    if (productForm) {
        productForm.addEventListener('submit', function() {
            skuInput.setCustomValidity('');
        });
    }

    // Cancel button functionality
    const cancelBtn = document.getElementById('cancelBtn');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            window.location.reload();
        });
    }
});
</script>

{{-- Product Feature Image --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('primary_image');
            const imagePreview = document.getElementById('image-preview');
            const imagePreviewContainer = document.getElementById('image-preview-container');
            const uploadBox = document.getElementById('upload-box');
            const removeImageBtn = document.getElementById('remove-image-btn');
            const imageError = document.getElementById('image-error');
            const MAX_FILE_SIZE = 1 * 1024 * 1024; // 1MB in bytes

            // Handle image selection
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];

                if (!file) return;

                // Validate file size
                if (file.size > MAX_FILE_SIZE) {
                    imageError.textContent = 'Image size must be less than 1MB';
                    imageError.classList.remove('hidden');
                    imageInput.value = ''; // Clear the input
                    return;
                } else {
                    imageError.classList.add('hidden');
                }

                // Validate file type
                if (!file.type.match('image.*')) {
                    imageError.textContent = 'Please select an image file (JPEG, PNG, GIF)';
                    imageError.classList.remove('hidden');
                    imageInput.value = ''; // Clear the input
                    return;
                }

                // Create preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.classList.remove('hidden');
                    uploadBox.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            });

            // Handle image removal
            removeImageBtn.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent triggering the container click
                resetImageUpload();
            });

            // Clicking anywhere on preview container opens file dialog
            imagePreviewContainer.addEventListener('click', function() {
                imageInput.click();
            });

            // Reset image upload state
            function resetImageUpload() {
                imagePreview.src = '';
                imageInput.value = '';
                imagePreviewContainer.classList.add('hidden');
                uploadBox.classList.remove('hidden');
                imageError.classList.add('hidden');
            }

            // Handle drag and drop
            uploadBox.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('border-indigo-500', 'bg-gray-100');
                this.classList.remove('border-gray-300');
            });

            uploadBox.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('border-indigo-500', 'bg-gray-100');
                this.classList.add('border-gray-300');
            });

            uploadBox.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('border-indigo-500', 'bg-gray-100');
                this.classList.add('border-gray-300');

                if (e.dataTransfer.files.length) {
                    imageInput.files = e.dataTransfer.files;
                    const event = new Event('change');
                    imageInput.dispatchEvent(event);
                }
            });
        });
</script>

{{-- Multi Product Image --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const dropzone = document.getElementById('multiple-images-dropzone');
    const fileInput = document.getElementById('additional_images');
    const previewsContainer = document.getElementById('image-previews-container');
    const errorElement = document.getElementById('multiple-images-error');
    const MAX_IMAGES = 5;
    const MAX_FILE_SIZE = 2048 * 1024; // 2MB in bytes
    let selectedFiles = [];

    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        handleFiles(e.target.files);
    });

    // Drag and drop handling
    dropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-indigo-500', 'bg-gray-50');
        this.classList.remove('border-gray-300');
    });

    dropzone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-indigo-500', 'bg-gray-50');
        this.classList.add('border-gray-300');
    });

    dropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-indigo-500', 'bg-gray-50');
        this.classList.add('border-gray-300');
        
        if (e.dataTransfer.files.length) {
            handleFiles(e.dataTransfer.files);
        }
    });

    // Process selected files
    function handleFiles(files) {
        errorElement.classList.add('hidden');

        // Merge current files with new ones
        let allFiles = [...selectedFiles, ...files];

        // Keep only first MAX_IMAGES files
        if (allFiles.length > MAX_IMAGES) {
            allFiles = allFiles.slice(0, MAX_IMAGES);
        }

        selectedFiles = [];

        // Process each file
        for (let i = 0; i < allFiles.length; i++) {
            const file = allFiles[i];

            // Validate file size
            if (file.size > MAX_FILE_SIZE) {
                errorElement.textContent = `"${file.name}" exceeds the 500KB size limit`;
                errorElement.classList.remove('hidden');
                continue;
            }

            // Validate file type
            if (!file.type.match('image.*')) {
                errorElement.textContent = `"${file.name}" is not a valid image file`;
                errorElement.classList.remove('hidden');
                continue;
            }

            selectedFiles.push(file);
        }

        // Refresh previews (so that max 5 shows)
        refreshPreviews();

        // Show previews container if we have images
        if (selectedFiles.length > 0) {
            previewsContainer.classList.remove('hidden');
        }

        // Update the file input with the selected files
        updateFileInput();
    }

    // Create preview for a file
    function createPreview(file, index) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.createElement('div');
            preview.className = 'relative group';
            preview.innerHTML = `
                <img src="${e.target.result}" alt="Preview" class="w-full h-32 object-cover">
                <button type="button" data-index="${index}" class="remove-image-btn absolute top-1 right-1 bg-red-600 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            `;
            
            previewsContainer.appendChild(preview);
            
            // Add event listener to remove button
            const removeBtn = preview.querySelector('.remove-image-btn');
            removeBtn.addEventListener('click', function() {
                const indexToRemove = parseInt(this.getAttribute('data-index'));
                removeImage(indexToRemove);
            });
        };
        
        reader.readAsDataURL(file);
    }
    
    // Remove an image
    function removeImage(index) {
        selectedFiles.splice(index, 1);
        refreshPreviews();
    }
    
    // Refresh all previews
    function refreshPreviews() {
        // Clear previews container
        previewsContainer.innerHTML = '';
        
        // Recreate all previews
        selectedFiles.forEach((file, index) => {
            createPreview(file, index);
        });
        
        // Update the file input
        updateFileInput();
        
        // Hide previews container if no images
        if (selectedFiles.length === 0) {
            previewsContainer.classList.add('hidden');
        }
    }
    
    // Update the file input with selected files
    function updateFileInput() {
        // Create a new DataTransfer object
        const dataTransfer = new DataTransfer();
        
        // Add each file to the transfer object
        selectedFiles.forEach(file => {
            dataTransfer.items.add(file);
        });
        
        // Update the files in the input
        fileInput.files = dataTransfer.files;
    }
});
</script>

{{-- Product Tags Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
            const tagContainer = document.getElementById('tag-container');
            const tagInput = document.getElementById('tag-input');
            const tagSuggestions = document.getElementById('tag-suggestions');
            const tagOptions = document.querySelectorAll('.tag-option');

            // Track selected tags
            const selectedTags = new Set();
            document.querySelectorAll('.tag-chip input[type="hidden"]').forEach(input => {
                selectedTags.add(input.value);
            });

            // Utility: update suggestion visibility
            function updateSuggestions() {
                tagOptions.forEach(option => {
                    const tagId = option.getAttribute('data-value');
                    if (selectedTags.has(tagId)) {
                        option.classList.add('hidden');
                    } else {
                        option.classList.remove('hidden');
                    }
                });
            }
            updateSuggestions();

            // Create tag chip function
            function createTagChip(tagId, tagName) {
                if (selectedTags.has(tagId)) return;

                const tagChip = document.createElement('span');
                tagChip.className =
                    'tag-chip inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800';
                tagChip.innerHTML = `
                ${tagName}
                <input type="hidden" name="tags[]" value="${tagId}">
                <button type="button" class="ml-1.5 inline-flex text-indigo-600 hover:text-indigo-900 focus:outline-none">×</button>
            `;

                // Insert before input
                tagContainer.insertBefore(tagChip, tagInput);
                selectedTags.add(tagId);
                updateSuggestions();

                // Remove button logic
                tagChip.querySelector('button').addEventListener('click', function() {
                    tagChip.remove();
                    selectedTags.delete(tagId);
                    updateSuggestions();
                });
            }

            // Show suggestions on focus
            tagInput.addEventListener('focus', () => {
                tagSuggestions.classList.remove('hidden');
            });

            // Hide suggestions on outside click
            document.addEventListener('click', function(e) {
                if (!tagContainer.contains(e.target) && !tagSuggestions.contains(e.target)) {
                    tagSuggestions.classList.add('hidden');
                }
            });

            // Add tag on suggestion click
            tagOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const tagId = this.getAttribute('data-value');
                    const tagName = this.textContent;
                    createTagChip(tagId, tagName);
                    tagInput.value = '';
                    tagSuggestions.classList.add('hidden');
                });
            });

            // Add tag on Enter
            tagInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const value = this.value.trim();
                    if (!value) return;

                    // Find matching tag
                    let matchingTag = null;
                    tagOptions.forEach(option => {
                        if (option.textContent.toLowerCase() === value.toLowerCase()) {
                            matchingTag = {
                                id: option.getAttribute('data-value'),
                                name: option.textContent
                            };
                        }
                    });

                    const tagId = matchingTag ? matchingTag.id : 'new-' + Date.now();
                    const tagName = matchingTag ? matchingTag.name : value;
                    createTagChip(tagId, tagName);
                    this.value = '';
                    tagSuggestions.classList.add('hidden');
                }
            });

            // Filter suggestions
            tagInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                tagOptions.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    if (text.includes(searchTerm) && !selectedTags.has(option.getAttribute(
                            'data-value'))) {
                        option.classList.remove('hidden');
                    } else {
                        option.classList.add('hidden');
                    }
                });
            });

            // Initialize remove buttons for old tags
            document.querySelectorAll('.tag-chip button').forEach(btn => {
                btn.addEventListener('click', function() {
                    const chip = this.closest('.tag-chip');
                    const tagId = chip.querySelector('input').value;
                    chip.remove();
                    selectedTags.delete(tagId);
                    updateSuggestions();
                });
            });
        });
</script>

@endsection

@push('styles')
<style>
    #tag-container {
        min-height: 40px;
        transition: all 0.2s;
    }

    .tag-chip {
        transition: all 0.2s;
    }

    .tag-chip:hover {
        background-color: #e0e7ff !important;
    }

    #tag-suggestions {
        scrollbar-width: thin;
    }

    #tag-input:focus {
        outline: none;
        box-shadow: none;
    }

    .tag-option.hidden {
        display: none;
    }

    [x-cloak] {
        display: none !important;
    }

    .image-field {
        transition: all 0.3s ease;
    }

    .image-field:hover {
        border-color: #a0aec0;
    }

    #multiple-images-dropzone {
        transition: all 0.3s ease;
    }

    #multiple-images-dropzone:hover {
        border-color: #6366f1;
        background-color: #f9fafb;
    }

    .remove-image-btn {
        transition: all 0.2s ease;
    }

    .remove-image-btn:hover {
        background-color: #dc2625;
        transform: scale(1.1);
    }
</style>
@endpush