@extends('layouts.admin')
@section('title', 'Edit Home Section')
@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white border border-gray-200">
        <!-- Page Header -->
        <div class="p-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Edit Home Section</h2>
        </div>

        <!-- Form Section -->
        <div class="p-4">
            <form action="{{ route('admin.home-sections.update', $homeSection) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Display Validation Errors -->
                @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded">
                    <h3 class="text-sm font-medium text-red-800 mb-2">Please fix the following errors:</h3>
                    <ul class="text-sm text-red-600 list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="space-y-4">
                    <!-- Type Selection - FIXED -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Section Type</label>
                        <input type="text" value="{{ ucfirst(str_replace('_', ' ', $homeSection->type)) }}"
                            class="w-full border border-gray-300 p-2 bg-gray-50 text-gray-600" readonly>
                        <input type="hidden" name="type" value="{{ $homeSection->type }}">
                        <p class="text-xs text-gray-500 mt-1">Type cannot be changed</p>
                    </div>

                    <!-- Title & Subtitle -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" name="title" id="title"
                                class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0"
                                value="{{ old('title', $homeSection->title) }}" required>
                        </div>
                        <div>
                            <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                            <input type="text" name="subtitle" id="subtitle"
                                class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0"
                                value="{{ old('subtitle', $homeSection->subtitle) }}">
                        </div>
                    </div>

                    <!-- Banner Image -->
                    <div>
                        <label for="banner_image" class="block text-sm font-medium text-gray-700 mb-1">Banner
                            Image</label>
                        @if($homeSection->banner_image)
                        <div class="mb-2 flex items-center space-x-3">
                            <img src="{{ Storage::url($homeSection->banner_image) }}" alt="Current banner"
                                class="h-12 w-auto">
                            <span class="text-sm text-gray-600">Current image</span>
                        </div>
                        @endif
                        <input type="file" name="banner_image" id="banner_image"
                            class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0" accept="image/*">
                    </div>

                    <!-- Category Selection -->
                    <div class="{{ $homeSection->type == 'fashion' ? '' : 'hidden' }}" id="category_id_div">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Linked
                            Category</label>
                        <select name="category_id" id="category_id"
                            class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0">
                            <option value="">Select Category</option>
                            @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $homeSection->category_id) == $cat->id
                                ? 'selected' : '' }}>
                                {{ $cat->category_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status & Order -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status"
                                class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0" required>
                                <option value="active" {{ old('status', $homeSection->status) == 'active' ? 'selected' :
                                    '' }}>Active</option>
                                <option value="inactive" {{ old('status', $homeSection->status) == 'inactive' ?
                                    'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-1">Display
                                Order</label>
                            <input type="number" name="order" id="order"
                                class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0"
                                value="{{ old('order', $homeSection->order) }}" min="0">
                        </div>
                    </div>
                </div>

                <!-- Slider Items Section -->
                <div id="slider_items"
                    class="{{ $homeSection->type == 'fashion' ? 'mt-6 border-t border-gray-200 pt-6' : 'hidden' }}">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Slider Items</h3>
                        <button type="button" id="add_slider_item"
                            class="inline-flex items-center px-3 py-1.5 bg-black text-white hover:bg-gray-800 text-sm">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Item
                        </button>
                    </div>

                    <div id="slider_items_container" class="space-y-4">
                        @if($homeSection->type == 'fashion')
                        @php
                        $oldItems = old('items', []);
                        $items = $homeSection->items;
                        $itemCount = count($oldItems) > 0 ? count($oldItems) : count($items);
                        @endphp

                        @for($key = 0; $key < $itemCount; $key++) @php $itemData=isset($oldItems[$key]) ?
                            $oldItems[$key] : ($items[$key] ?? null); $existingImage=$items[$key]->image ??
                            ($itemData['existing_image'] ?? null);
                            $itemId = $items[$key]->id ?? ($itemData['id'] ?? null);
                            @endphp

                            @if($itemData)
                            <div class="border border-gray-200 p-4 bg-gray-50 rounded" id="item_{{ $key }}">
                                <div class="flex justify-between items-center mb-3 pb-3 border-b border-gray-200">
                                    <h4 class="font-medium text-gray-900">Item {{ $key + 1 }}</h4>
                                    <button type="button" onclick="removeItem('item_{{ $key }}')"
                                        class="text-red-600 hover:text-red-800 p-1 {{ $itemCount <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $itemCount <=1 ? 'disabled' : '' }}>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>

                                @if($existingImage)
                                <div class="mb-3 flex items-center space-x-3">
                                    <img src="{{ Storage::url($existingImage) }}" alt="Current image"
                                        class="h-12 w-12 object-cover rounded">
                                    <span class="text-sm text-gray-600">Current image</span>
                                </div>
                                @endif

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    @if($itemId)
                                    <input type="hidden" name="items[{{ $key }}][id]" value="{{ $itemId }}">
                                    @endif

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                        <input type="text" name="items[{{ $key }}][title]"
                                            class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0 bg-white"
                                            value="{{ $itemData['title'] ?? $itemData->title ?? '' }}" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                                        <input type="text" name="items[{{ $key }}][subtitle]"
                                            class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0 bg-white"
                                            value="{{ $itemData['subtitle'] ?? $itemData->subtitle ?? '' }}">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                                        <input type="file" name="items[{{ $key }}][image]"
                                            class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0 bg-white"
                                            accept="image/*">
                                        @if($existingImage)
                                        <input type="hidden" name="items[{{ $key }}][existing_image]"
                                            value="{{ $existingImage }}">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endfor
                            @endif
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
                    <a href="{{ route('admin.home-sections.index') }}"
                        class="px-4 py-2 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 text-sm">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-black text-white hover:bg-gray-800 text-sm">
                        Update Section
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let itemIndex = {{ $itemCount }};

    function addItem() {
        if (itemIndex >= 4) {
            alert('Maximum 4 slider items allowed');
            return;
        }

        const container = document.getElementById('slider_items_container');
        const itemDiv = document.createElement('div');
        itemDiv.className = 'border border-gray-200 p-4 bg-gray-50 rounded';
        itemDiv.id = `item_${itemIndex}`;

        const isOnlyItem = container.children.length === 0;
        
        itemDiv.innerHTML = `
            <div class="flex justify-between items-center mb-3 pb-3 border-b border-gray-200">
                <h4 class="font-medium text-gray-900">Item ${itemIndex + 1}</h4>
                <button type="button" onclick="removeItem('item_${itemIndex}')" 
                        class="text-red-600 hover:text-red-800 p-1 ${isOnlyItem ? 'opacity-50 cursor-not-allowed' : ''}"
                        ${isOnlyItem ? 'disabled' : ''}>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="items[${itemIndex}][title]" 
                           class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0 bg-white" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                    <input type="text" name="items[${itemIndex}][subtitle]" 
                           class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0 bg-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                    <input type="file" name="items[${itemIndex}][image]" 
                           class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0 bg-white" 
                           accept="image/*">
                </div>
            </div>
        `;

        container.appendChild(itemDiv);
        itemIndex++;
        updateDeleteButtons();
    }

    function removeItem(id) {
        const container = document.getElementById('slider_items_container');
        if (container.children.length <= 1) {
            alert('At least one slider item is required');
            return;
        }

        document.getElementById(id).remove();
        itemIndex--;
        
        const items = container.children;
        for (let i = 0; i < items.length; i++) {
            const item = items[i];
            item.id = `item_${i}`;
            
            const inputs = item.querySelectorAll('input');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    if (name.includes('[id]')) {
                        input.name = `items[${i}][id]`;
                    } else if (name.includes('[title]')) {
                        input.name = `items[${i}][title]`;
                    } else if (name.includes('[subtitle]')) {
                        input.name = `items[${i}][subtitle]`;
                    } else if (name.includes('[image]')) {
                        input.name = `items[${i}][image]`;
                    } else if (name.includes('[existing_image]')) {
                        input.name = `items[${i}][existing_image]`;
                    }
                }
            });
            
            item.querySelector('h4').textContent = `Item ${i + 1}`;
            item.querySelector('button').onclick = () => removeItem(item.id);
        }
        
        updateDeleteButtons();
    }

    function updateDeleteButtons() {
        const container = document.getElementById('slider_items_container');
        const items = container.children;
        const hasMultipleItems = items.length > 1;
        
        for (let i = 0; i < items.length; i++) {
            const deleteBtn = items[i].querySelector('button');
            if (hasMultipleItems) {
                deleteBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                deleteBtn.disabled = false;
            } else {
                deleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
                deleteBtn.disabled = true;
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const addItemBtn = document.getElementById('add_slider_item');
        if (addItemBtn) addItemBtn.addEventListener('click', addItem);
        updateDeleteButtons();
    });
</script>
@endpush