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
            <form action="{{ route('admin.home-sections.update', $homeSection) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" id="type"
                        class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0" required disabled>
                        <option value="new_arrivals" {{ $homeSection->type == 'new_arrivals' ? 'selected' : '' }}>New
                            Arrivals</option>
                        <option value="fashion" {{ $homeSection->type == 'fashion' ? 'selected' : '' }}>Fashion (e.g.,
                            Men's, Women's)</option>
                    </select>
                    <p class="text-sm text-gray-500 mt-1">Type cannot be changed after creation</p>
                </div>
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" id="title"
                        class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0"
                        value="{{ old('title', $homeSection->title) }}" required>
                </div>
                <div class="mb-4">
                    <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                    <input type="text" name="subtitle" id="subtitle"
                        class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0"
                        value="{{ old('subtitle', $homeSection->subtitle) }}">
                </div>
                <div class="mb-4">
                    <label for="banner_image" class="block text-sm font-medium text-gray-700 mb-1">Banner Image</label>
                    @if($homeSection->banner_image)
                    <div class="mb-2">
                        <img src="{{ Storage::url($homeSection->banner_image) }}" alt="Current banner"
                            class="h-20 w-auto">
                        <p class="text-sm text-gray-500 mt-1">Current image</p>
                    </div>
                    @endif
                    <input type="file" name="banner_image" id="banner_image"
                        class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0">
                </div>
                <div class="mb-4 {{ $homeSection->type == 'fashion' ? '' : 'hidden' }}" id="category_id_div">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Linked Category (for
                        product filtering)</label>
                    <select name="category_id" id="category_id"
                        class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0">
                        <option value="">Select Category</option>
                        @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $homeSection->category_id == $cat->id ? 'selected' : '' }}>{{
                            $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status"
                        class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0" required>
                        <option value="active" {{ $homeSection->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $homeSection->status == 'inactive' ? 'selected' : '' }}>Inactive
                        </option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="order" class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                    <input type="number" name="order" id="order"
                        class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0"
                        value="{{ old('order', $homeSection->order) }}" min="0">
                </div>

                <!-- Slider Items (for fashion sections) -->
                <div id="slider_items" class="{{ $homeSection->type == 'fashion' ? '' : 'hidden' }}">
                    <h2 class="text-xl mb-4">Slider Items</h2>

                    @if($homeSection->type == 'fashion')
                    @foreach($homeSection->items as $key => $item)
                    <div class="mb-6 border border-gray-200 p-4">
                        <h4 class="font-medium text-gray-900 mb-3">Slider Item {{ $key + 1 }}</h4>

                        @if($item->image)
                        <div class="mb-3">
                            <img src="{{ Storage::url($item->image) }}" alt="Current image" class="h-20 w-auto">
                            <p class="text-sm text-gray-500 mt-1">Current image</p>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                <input type="text" name="items[{{ $key }}][title]"
                                    class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0"
                                    value="{{ old('items.'.$key.'.title', $item->title) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                                <input type="text" name="items[{{ $key }}][subtitle]"
                                    class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0"
                                    value="{{ old('items.'.$key.'.subtitle', $item->subtitle) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                                <input type="file" name="items[{{ $key }}][image]"
                                    class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0">
                                <input type="hidden" name="items[{{ $key }}][existing_image]"
                                    value="{{ $item->image }}">
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif

                    <!-- Add new item button -->
                    <button type="button" id="add_slider_item"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add Another Item
                    </button>
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
    document.addEventListener('DOMContentLoaded', function() {
        const addItemBtn = document.getElementById('add_slider_item');
        const sliderContainer = document.querySelector('#slider_items');
        let itemCount = {{ $homeSection->items->count() }};

        // Add new slider item
        if (addItemBtn) {
            addItemBtn.addEventListener('click', function() {
                if (itemCount >= 4) {
                    alert('Maximum 4 slider items allowed');
                    return;
                }

                const newItem = document.createElement('div');
                newItem.className = 'mb-6 border border-gray-200 p-4';
                newItem.innerHTML = `
                    <h4 class="font-medium text-gray-900 mb-3">Slider Item ${itemCount + 1}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" name="items[${itemCount}][title]" class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0" 
                                   placeholder="Item title">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                            <input type="text" name="items[${itemCount}][subtitle]" class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0" 
                                   placeholder="Item subtitle">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                            <input type="file" name="items[${itemCount}][image]" class="w-full border border-gray-300 p-2 focus:border-black focus:ring-0" 
                                   accept="image/*">
                        </div>
                    </div>
                `;
                
                // Insert before the add button
                addItemBtn.parentNode.insertBefore(newItem, addItemBtn);
                itemCount++;
            });
        }
    });
</script>
@endpush