@extends('layouts.admin')
@section('title', 'Edit Home Section')
@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white border border-gray-200">
        <!-- Page Header -->
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Edit Home Section</h2>
        </div>

        <!-- Form Section -->
        <div class="p-6">
            <form action="{{ route('admin.home-sections.update', $homeSection->id) }}" method="POST"
                enctype="multipart/form-data" id="homeSectionForm">
                @csrf
                @method('PUT')

                <!-- Display Validation Errors -->
                @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="flex flex-wrap -mx-2">
                    <!-- Type Field (Readonly) -->
                    <div class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                        <input type="text" id="type_display"
                            value="{{ $homeSection->type === 'fashion' ? 'Fashion' : 'New Arrivals' }}"
                            class="w-full border border-gray-300 p-3 bg-gray-100 text-gray-600" readonly>
                        <input type="hidden" name="type" value="{{ $homeSection->type }}">
                        <p class="text-xs text-gray-500 mt-1">Type cannot be changed after creation</p>
                    </div>

                    <!-- Title Field -->
                    <div class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $homeSection->title) }}"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0" required>
                    </div>

                    <!-- Subtitle Field -->
                    <div class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4">
                        <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                        <input type="text" name="subtitle" id="subtitle"
                            value="{{ old('subtitle', $homeSection->subtitle) }}"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0">
                    </div>

                    <!-- Banner Image Field (Only for Fashion) -->
                    <div
                        class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4 fashion-field {{ $homeSection->type !== 'fashion' ? 'hidden' : '' }}">
                        <label for="banner_image" class="block text-sm font-medium text-gray-700 mb-1">Banner
                            Image</label>
                        <input type="file" name="banner_image" id="banner_image"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0">
                        @if($homeSection->banner_image)
                        <div class="mt-2">
                            <p class="text-sm text-gray-600 mb-1">Current Banner Image:</p>
                            <img src="{{ Storage::disk('public')->url($homeSection->banner_image) }}" alt="Banner Image"
                                class="h-20 w-auto object-cover rounded">
                        </div>
                        @endif
                        <p class="text-xs text-gray-500 mt-1">Recommended size: 1200x400px or larger</p>
                    </div>

                    <!-- Section Title Field (Only for Fashion) -->
                    <div
                        class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4 fashion-field {{ $homeSection->type !== 'fashion' ? 'hidden' : '' }}">
                        <label for="section_title" class="block text-sm font-medium text-gray-700 mb-1">Section
                            Title</label>
                        <input type="text" name="section_title" id="section_title"
                            value="{{ old('section_title', $homeSection->section_title) }}"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0">
                    </div>

                    <!-- Section Subtitle Field (Only for Fashion) -->
                    <div
                        class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4 fashion-field {{ $homeSection->type !== 'fashion' ? 'hidden' : '' }}">
                        <label for="section_subtitle" class="block text-sm font-medium text-gray-700 mb-1">Section
                            Subtitle</label>
                        <input type="text" name="section_subtitle" id="section_subtitle"
                            value="{{ old('section_subtitle', $homeSection->section_subtitle) }}"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0">
                    </div>

                    <!-- Parent Category Field (Only for Fashion) -->
                    <div
                        class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4 fashion-field {{ $homeSection->type !== 'fashion' ? 'hidden' : '' }}">
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Parent Category
                            *</label>
                        <select name="category_id" id="category_id"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0">
                            <option value="">Select Parent Category</option>
                            @foreach ($parentCategories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $homeSection->category_id) ==
                                $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Field -->
                    <div class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status" id="status"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0" required>
                            <option value="active" {{ old('status', $homeSection->status) == 'active' ? 'selected' : ''
                                }}>Active</option>
                            <option value="inactive" {{ old('status', $homeSection->status) == 'inactive' ? 'selected' :
                                '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- Order Field -->
                    <div class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4">
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-1">Order *</label>
                        <input type="number" name="order" id="order" value="{{ old('order', $homeSection->order) }}"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0" min="0" required>
                    </div>
                </div>

                <!-- Slider Items Section (Only for Fashion) -->
                <div id="slider_items"
                    class="mt-8 border-t border-gray-200 pt-6 {{ $homeSection->type !== 'fashion' ? 'hidden' : '' }}">
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

                    <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded hidden" id="duplicateWarning">
                        <p class="text-yellow-700 text-sm">Warning: You have selected the same child category multiple
                            times.</p>
                    </div>

                    <div id="slider_items_container" class="space-y-2">
                        <!-- Existing items will be loaded here by JavaScript -->
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
                    <a href="{{ route('admin.home-sections.index') }}"
                        class="px-6 py-3 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 text-sm font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 bg-black text-white hover:bg-gray-800 text-sm font-medium">
                        Update Home Section
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
let selectedChildCategories = new Set();
let childCategories = @json($childCategories);
let oldItems = @json(old('items', []));
let existingItems = @json($existingItems);

function addItem(itemData = {}) {
    const container = document.getElementById('slider_items_container');
    const itemDiv = document.createElement('div');
    itemDiv.classList.add('bg-white', 'item-row');
    itemDiv.dataset.index = itemIndex;

    // Get child categories for selected parent
    const parentCategoryId = document.getElementById('category_id').value;
    const filteredChildCategories = childCategories.filter(cat => cat.parent_id == parentCategoryId);

    const childCategoryOptions = filteredChildCategories.map(cat => 
        `<option value="${cat.id}" ${itemData.category_id == cat.id ? 'selected' : ''}>${cat.category_name}</option>`
    ).join('');

    itemDiv.innerHTML = `
        <div class="flex flex-wrap items-end gap-2">
            
            <!-- Child Category -->
            <div class="w-48">
                <label class="block text-sm font-medium text-gray-700 mb-1">Child Category *</label>
                <select name="items[${itemIndex}][category_id]" 
                        class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0 child-category-select" required>
                    <option value="">Select</option>
                    ${childCategoryOptions}
                </select>
                <div class="text-red-500 text-xs mt-1 duplicate-warning hidden">This category is already selected</div>
            </div>

            <!-- Title -->
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                <input type="text" name="items[${itemIndex}][title]" 
                    value="${itemData.title || ''}"
                    class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0 item-title" required>
            </div>

            <!-- Subtitle -->
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                <input type="text" name="items[${itemIndex}][subtitle]" 
                    value="${itemData.subtitle || ''}"
                    class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0">
            </div>

            <!-- Image -->
            <div class="w-48">
                <label class="block text-sm font-medium text-gray-700 mb-1">Image ${itemData.item_id ? '' : '*'}</label>
                ${itemData.image_url ? `<img src="${itemData.image_url}" alt="Item Image" class="h-10 w-10 object-cover">` : ''}
                <input type="file" name="items[${itemIndex}][image]" 
                    class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0" accept="image/*" ${itemData.item_id ? '' : 'required'}>
                
            </div>

            <!-- Remove Button and Item ID -->
            <div class="flex items-center space-x-2">
                ${itemData.item_id ? `<input type="hidden" name="items[${itemIndex}][item_id]" value="${itemData.item_id}">` : ''}
                <button type="button" onclick="removeItem(${itemIndex})" 
                        class="text-red-600 hover:text-red-800 ${container.children.length === 0 ? 'hidden' : ''}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    `;

    container.appendChild(itemDiv);
    
    // Initialize category validation
    const categorySelect = itemDiv.querySelector('.child-category-select');
    const titleInput = itemDiv.querySelector('.item-title');

    if (categorySelect.value) {
        selectedChildCategories.add(categorySelect.value);
        validateChildCategories();
    }
    
    // Auto-set Title based on selected child category
    categorySelect.addEventListener('change', function() {
        const selectedCat = childCategories.find(cat => cat.id == this.value);
        if (selectedCat && !titleInput.value) {
            titleInput.value = selectedCat.category_name;
        }
        validateChildCategories();
    });

    itemIndex++;
    updateRemoveButtons();
}

function removeItem(index) {
    const item = document.querySelector(`.item-row[data-index="${index}"]`);
    if (item) {
        const categorySelect = item.querySelector('.child-category-select');
        if (categorySelect && categorySelect.value) {
            selectedChildCategories.delete(categorySelect.value);
        }
        item.remove();
        validateChildCategories();
        updateRemoveButtons();
    }
}

function updateRemoveButtons() {
    const items = document.querySelectorAll('.item-row');
    const removeButtons = document.querySelectorAll('button[onclick^="removeItem"]');
    
    if (items.length <= 1) {
        removeButtons.forEach(btn => btn.classList.add('hidden'));
    } else {
        removeButtons.forEach(btn => btn.classList.remove('hidden'));
    }
}

function validateChildCategories() {
    const allSelects = document.querySelectorAll('.child-category-select');
    const selectedValues = new Set();
    let hasDuplicates = false;

    // Reset all warnings
    document.querySelectorAll('.duplicate-warning').forEach(warning => {
        warning.classList.add('hidden');
    });
    document.getElementById('duplicateWarning').classList.add('hidden');

    // Check for duplicates
    allSelects.forEach(select => {
        const value = select.value;
        if (value) {
            if (selectedValues.has(value)) {
                hasDuplicates = true;
                const warning = select.parentNode.querySelector('.duplicate-warning');
                warning.classList.remove('hidden');
            } else {
                selectedValues.add(value);
            }
        }
    });

    // Show global warning
    if (hasDuplicates) {
        document.getElementById('duplicateWarning').classList.remove('hidden');
    }

    return !hasDuplicates;
}

function updateChildCategories() {
    const parentCategoryId = document.getElementById('category_id').value;
    const filteredChildCategories = childCategories.filter(cat => cat.parent_id == parentCategoryId);
    
    document.querySelectorAll('.child-category-select').forEach(select => {
        const currentValue = select.value;
        const options = filteredChildCategories.map(cat => 
            `<option value="${cat.id}" ${currentValue == cat.id ? 'selected' : ''}>${cat.category_name}</option>`
        ).join('');
        
        select.innerHTML = '<option value="">Select Child Category</option>' + options;

        // Auto update title if selected and title is empty
        const titleInput = select.closest('.item-row').querySelector('.item-title');
        const selectedCat = childCategories.find(cat => cat.id == select.value);
        if (selectedCat && !titleInput.value) {
            titleInput.value = selectedCat.category_name;
        }
    });
    
    validateChildCategories();
}

// Event Listeners
document.getElementById('category_id').addEventListener('change', function() {
    if (document.getElementById('type').value === 'fashion') {
        updateChildCategories();
    }
});

document.getElementById('add_item').addEventListener('click', function() {
    if (validateChildCategories()) {
        addItem();
    } else {
        alert('Please resolve duplicate category selections before adding new items.');
    }
});

document.getElementById('homeSectionForm').addEventListener('submit', function(e) {
    @if($homeSection->type === 'fashion')
        if (document.getElementById('slider_items_container').children.length === 0) {
            e.preventDefault();
            alert('At least one slider item is required for fashion sections.');
            return;
        }
        
        if (!validateChildCategories()) {
            e.preventDefault();
            alert('Please resolve duplicate child category selections before submitting.');
            return;
        }
    @endif
});

// Initialize form
document.addEventListener('DOMContentLoaded', function() {
    @if($homeSection->type === 'fashion')
        // Load existing items or old form data
        if (oldItems.length > 0) {
            oldItems.forEach(item => addItem(item));
        } else if (existingItems.length > 0) {
            existingItems.forEach(item => addItem(item));
        } else {
            addItem();
        }
        
        updateChildCategories();
        validateChildCategories();
        updateRemoveButtons();
    @endif
});
</script>
@endpush