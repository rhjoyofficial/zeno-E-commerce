@extends('layouts.admin')
@section('title', 'Create Home Section')
@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white border border-gray-200">
        <!-- Page Header -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Create Home Section</h2>
        </div>
        <!-- Form Section -->
        <div class="p-6">
            <form action="{{ route('admin.home-sections.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex flex-wrap -mx-2">
                    <div class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" id="type"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0" required>
                            <option value="new_arrivals" {{ old('type')=='new_arrivals' ? 'selected' : '' }}>New
                                Arrivals</option>
                            <option value="fashion" {{ old('type')=='fashion' ? 'selected' : '' }}>Fashion (e.g., Men's,
                                Women's)</option>
                        </select>
                    </div>
                    <div class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0" required>
                    </div>
                    <div class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4">
                        <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                        <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle') }}"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0">
                    </div>
                    <div class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4">
                        <label for="banner_image" class="block text-sm font-medium text-gray-700 mb-1">Banner
                            Image</label>
                        <input type="file" name="banner_image" id="banner_image"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0">
                    </div>
                    <div class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4 hidden" id="category_id_div">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Linked Category
                            (for product filtering)</label>
                        <select name="category_id" id="category_id"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0">
                            @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id ? 'selected' : '' }}>{{
                                $cat->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0" required>
                            <option value="active" {{ old('status')=='active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', 'active' )=='inactive' ? 'selected' : '' }}>
                                Inactive</option>
                        </select>
                    </div>
                    <div class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4">
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                        <input type="number" name="order" id="order" value="{{ old('order', 0) }}"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0" min="0">
                    </div>
                </div>

                <!-- Slider Items Section -->
                <div id="slider_items" class="hidden mt-8 border-t border-gray-200 pt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Slider Items</h3>
                        <button type="button" id="add_item"
                            class="inline-flex items-center px-4 py-2 bg-black text-white hover:bg-gray-800 text-sm font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Item
                        </button>
                    </div>
                    <div id="slider_items_container" class="space-y-4"></div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
                    <a href="{{ route('admin.home-sections.index') }}"
                        class="px-6 py-3 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 text-sm font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 bg-black text-white hover:bg-gray-800 text-sm font-medium">
                        Create Home Section
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let itemIndex = 0;
    let oldItems = @json(old('items', []));

    function addItem(itemData = {}) {
        if (itemIndex >= 4) {
            alert('Maximum 4 slider items allowed');
            return;
        }

        const container = document.getElementById('slider_items_container');
        const itemDiv = document.createElement('div');
        itemDiv.classList.add('border', 'border-gray-200', 'p-4', 'relative', 'bg-gray-50');
        itemDiv.id = `item_${itemIndex}`;

        // Check if this is the only item
        const isOnlyItem = container.children.length === 0;
        
        // Header with item number and delete button
        const headerDiv = document.createElement('div');
        headerDiv.classList.add('flex', 'justify-between', 'items-center', 'mb-4', 'pb-2', 'border-b', 'border-gray-200');
        headerDiv.innerHTML = `
            <h4 class="font-medium text-gray-900">Slider Item ${itemIndex + 1}</h4>
            <button type="button" onclick="removeItem('${itemDiv.id}')" 
                    class="text-red-600 hover:text-red-800 p-1 ${isOnlyItem ? 'opacity-50 cursor-not-allowed' : ''}"
                    ${isOnlyItem ? 'disabled' : ''}>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        `;
        itemDiv.appendChild(headerDiv);

        // Form fields
        const flexDiv = document.createElement('div');
        flexDiv.classList.add('flex', 'flex-wrap', '-mx-2');

        // Title
        const titleDiv = document.createElement('div');
        titleDiv.classList.add('w-full', 'sm:w-1/2', 'lg:w-1/3', 'px-2', 'mb-4');
        titleDiv.innerHTML = `
            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" name="items[${itemIndex}][title]" 
                   class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0" 
                   placeholder="Enter item title" 
                   value="${itemData.title || ''}"
                   required>
        `;
        flexDiv.appendChild(titleDiv);

        // Subtitle
        const subDiv = document.createElement('div');
        subDiv.classList.add('w-full', 'sm:w-1/2', 'lg:w-1/3', 'px-2', 'mb-4');
        subDiv.innerHTML = `
            <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
            <input type="text" name="items[${itemIndex}][subtitle]" 
                   class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0" 
                   placeholder="Enter item subtitle"
                   value="${itemData.subtitle || ''}">
        `;
        flexDiv.appendChild(subDiv);

        // Image
        const imgDiv = document.createElement('div');
        imgDiv.classList.add('w-full', 'sm:w-1/2', 'lg:w-1/3', 'px-2', 'mb-4');
        imgDiv.innerHTML = `
            <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
            <input type="file" name="items[${itemIndex}][image]" 
                   class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0" 
                   accept="image/*" ${itemIndex === 0 && !itemData.title ? 'required' : ''}>
            <p class="text-xs text-gray-500 mt-1">Recommended: 400x400px or larger</p>
        `;
        flexDiv.appendChild(imgDiv);

        itemDiv.appendChild(flexDiv);
        container.appendChild(itemDiv);
        itemIndex++;
        
        updateDeleteButtons();
    }

    function removeItem(id) {
        const container = document.getElementById('slider_items_container');
        if (container.children.length <= 1) {
            alert('At least one slider item is required for fashion sections');
            return;
        }

        const element = document.getElementById(id);
        if (element) {
            element.remove();
            itemIndex--;
            
            // Re-index remaining items
            const items = container.children;
            for (let i = 0; i < items.length; i++) {
                const item = items[i];
                const newIndex = i;
                item.id = `item_${newIndex}`;
                
                // Update input names
                const inputs = item.querySelectorAll('input');
                inputs[0].name = `items[${newIndex}][title]`;
                inputs[1].name = `items[${newIndex}][subtitle]`;
                inputs[2].name = `items[${newIndex}][image]`;
                
                // Update header title
                const header = item.querySelector('h4');
                header.textContent = `Slider Item ${newIndex + 1}`;
                
                // Update delete button onclick
                const deleteBtn = item.querySelector('button');
                deleteBtn.onclick = () => removeItem(item.id);
            }
            
            updateDeleteButtons();
        }
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

    function resetSliderItems() {
        const container = document.getElementById('slider_items_container');
        container.innerHTML = '';
        itemIndex = 0;
        oldItems = [];
    }

    document.getElementById('type').addEventListener('change', function() {
        const isFashion = this.value === 'fashion';
        const categoryDiv = document.getElementById('category_id_div');
        const sliderItems = document.getElementById('slider_items');

        if (isFashion) {
            categoryDiv.classList.remove('hidden');
            sliderItems.classList.remove('hidden');
            
            // Add items from old data or create one default
            if (oldItems.length > 0) {
                oldItems.forEach(item => addItem(item));
            } else if (document.getElementById('slider_items_container').children.length === 0) {
                addItem();
            }
        } else {
            categoryDiv.classList.add('hidden');
            sliderItems.classList.add('hidden');
            resetSliderItems();
        }
    });

    document.getElementById('add_item').addEventListener('click', () => addItem());

    // Initialize form based on current type and old data
    document.addEventListener('DOMContentLoaded', function() {
        const currentType = document.getElementById('type').value;
        const isFashion = currentType === 'fashion';
        
        if (isFashion) {
            document.getElementById('category_id_div').classList.remove('hidden');
            document.getElementById('slider_items').classList.remove('hidden');
            
            if (oldItems.length > 0) {
                oldItems.forEach(item => addItem(item));
            } else {
                addItem();
            }
        }
    });
</script>
@endpush