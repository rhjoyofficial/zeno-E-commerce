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
                            <option value="new_arrivals">New Arrivals</option>
                            <option value="fashion">Fashion (e.g., Men's, Women's)</option>
                        </select>
                    </div>
                    <div class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" id="title"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0" required>
                    </div>
                    <div class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4">
                        <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                        <input type="text" name="subtitle" id="subtitle"
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
                            (for
                            product filtering)</label>
                        <select name="category_id" id="category_id"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0">
                            @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="w-full sm:w-1/2 lg:w-1/3 px-2 mb-4">
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                        <input type="number" name="order" id="order"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0" value="0" min="0">
                    </div>
                </div>

                <div id="slider_items" class="hidden">
                    <h2 class="text-xl mb-2">Slider Items</h2>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Item 1 Title</label>
                        <input type="text" name="items[0][title]"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Item 1 Subtitle</label>
                        <input type="text" name="items[0][subtitle]"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0">
                        <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Item 1 Image</label>
                        <input type="file" name="items[0][image]"
                            class="w-full border border-gray-300 p-3 focus:border-black focus:ring-0">
                    </div>
                </div>

                <div class="flex flex-row-reverse">
                    <button type="submit" class="bg-black text-white px-6 py-3 hover:bg-gray-800">Save</button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('type').addEventListener('change', function() {
        const isFashion = this.value === 'fashion';
        const categoryDiv = document.getElementById('category_id_div');
        const sliderItems = document.getElementById('slider_items');
        
        if (isFashion) {
            categoryDiv.classList.remove('hidden');
            sliderItems.classList.remove('hidden');
        } else {
            categoryDiv.classList.add('hidden');
            sliderItems.classList.add('hidden');
        }
    });
</script>
@endpush