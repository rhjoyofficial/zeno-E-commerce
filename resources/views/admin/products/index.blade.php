@extends('layouts.admin')
@section('title', 'Products Management')
@section('content')
<div class="container mx-auto p-4">
    <!-- Filters Section -->
    <div class="bg-white p-6 mb-4 border border-gray-200">
        <form method="GET" action="{{ route('admin.products.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <!-- Search Input -->
                <div class="lg:col-span-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative">
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            class="pl-10 pr-4 py-2 border border-gray-300 focus:ring-black focus:border-black w-full text-sm"
                            placeholder="Product name, description...">
                        <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select id="category" name="category"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-sm border border-gray-300 focus:outline-none focus:ring-black focus:border-black">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Brand Filter -->
                <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                    <select id="brand" name="brand"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-sm border border-gray-300 focus:outline-none focus:ring-black focus:border-black">
                        <option value="">All Brands</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->brand_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Stock Filter -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <select id="stock" name="stock"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-sm border border-gray-300 focus:outline-none focus:ring-black focus:border-black">
                        <option value="">All</option>
                        <option value="in_stock" {{ request('stock') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="out_of_stock" {{ request('stock') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>

                <!-- Rating Filter -->
                <div>
                    <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                    <select id="rating" name="rating"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-sm border border-gray-300 focus:outline-none focus:ring-black focus:border-black">
                        <option value="">Any</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3+ Stars</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4+ Stars</option>
                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                    </select>
                </div>

                <!-- Price Filter -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By Price</label>
                    <select id="sort" name="sort"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-sm border border-gray-300 focus:outline-none focus:ring-black focus:border-black">
                        <option value="">Price</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price (High to Low)</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price (Low to High)</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end mt-4 space-x-3">
                <a href="{{ route('admin.products.index') }}"
                    class="px-4 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-colors text-sm">
                    Reset Filters
                </a>
                <button type="submit" class="px-4 py-2 bg-black text-white hover:bg-gray-800 transition-colors text-sm">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    @if ($products->count() > 0)
        <!-- Products Table -->
        <div class="bg-white border border-gray-200">
            <!-- Page Header -->
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h1 class="text-xl font-semibold text-gray-800">Products Management</h1>
                    <p class="text-gray-600 text-sm">Manage all products in your store</p>
                </div>
                <div>
                    <a href="{{ route('admin.products.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-black text-white hover:bg-gray-800 transition-colors text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add New Product
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Product
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Price
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Stock
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Rating
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($products as $product)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <img class="h-12 w-12 object-cover"
                                                src="{{ $product->images->first()?->image_path ? asset('storage/' . $product->images->first()->image_path) : asset('images/default.png') }}"
                                                alt="{{ $product->title }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $product->title }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($product->short_des, 30) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if ($product->discount > 0 && $product->discount_price < $product->price)
                                            <span class="font-bold">
                                                {{ number_format($product->discount_price, 2) }}৳
                                            </span>
                                            <span class="text-sm text-gray-500 line-through ml-1">
                                                {{ number_format($product->price, 2) }}৳
                                            </span>
                                        @else
                                            <span class="font-bold">
                                                {{ number_format($product->price, 2) }}৳
                                            </span>
                                        @endif
                                    </div>
                                    @if ($product->discount > 0 && $product->discount_price < $product->price)
                                        @php
                                            $discountPercent = (($product->price - $product->discount_price) / $product->price) * 100;
                                        @endphp
                                        <span class="mt-1 inline-block px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                            {{ number_format($discountPercent, 2) }}% OFF
                                        </span>
                                    @endif
                                </td>

                                <!-- Stock Quantity Cell -->
                                <td class="px-6 py-4 whitespace-nowrap" x-data="stockModal({{ $product->id }}, {{ $product->stock_quantity }})">
                                    <button type="button" class="w-full text-left focus:outline-none" @click="open = true">
                                        <div class="text-sm">
                                            <span class="px-2 py-1 inline-flex text-sm font-medium rounded-full {{ $product->stock_quantity <= $product->stock_alert ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $product->stock_quantity }}
                                            </span>
                                        </div>
                                    </button>

                                    <!-- Modal -->
                                    <div x-show="open" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-transition>
                                        <div class="bg-white p-6 w-80" @click.away="open = false">
                                            <h2 class="text-lg font-semibold mb-4">Update Stock Quantity</h2>
                                            <form @submit.prevent="submit">
                                                <input type="number" x-model="newQty"
                                                    class="w-full px-3 py-2 text-sm font-medium border border-gray-300 focus:outline-none focus:ring focus:border-black"
                                                    min="0" required>
                                                <div class="mt-4 flex justify-end space-x-2">
                                                    <button type="button" @click="open = false"
                                                        class="px-4 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" class="px-6 py-2 bg-black text-white"
                                                        :disabled="isSubmitting"
                                                        :class="isSubmitting ? 'opacity-50 cursor-not-allowed' : ''">
                                                        <span x-show="isSubmitting"
                                                            class="animate-spin mr-1 inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full"></span>
                                                        <span x-show="!isSubmitting">Save</span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        <span class="ml-1 text-sm text-gray-600">{{ number_format($product->avg_rating, 1) ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <select class="px-2 py-1 text-xs font-medium rounded-full focus:outline-none focus:ring 
                                        {{ $product->status == 'active' ? 'bg-purple-100 text-purple-800' : 
                                           ($product->status == 'inactive' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}"
                                        onchange="updateStatus(this.value, {{ $product->id }})">
                                        <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="discontinued" {{ $product->status == 'discontinued' ? 'selected' : '' }}>Discontinued</option>
                                    </select>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        @if ($product->has_variants)
                                            <a href="{{ route('admin.products.variants.index', $product->id) }}"
                                                class="text-purple-600 hover:text-purple-900" title="Manage Variants">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                                </svg>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-600 hover:text-blue-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Are you sure you want to delete this product?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.products.show', $product->id) }}" class="text-green-600 hover:text-green-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $products->links() }}
            </div>
        </div>
    @else
        <div class="p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No products found</h3>
            <p class="mt-1 text-gray-500">This brand doesn't have any products yet.</p>
            <div class="mt-6">
                <a href="{{ route('admin.products.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium text-white bg-black hover:bg-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Product
                </a>
            </div>
        </div>
    @endif
</div>

<script>
    function updateStatus(newStatus, productId) {
        fetch(`/admin/products/${productId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    status: newStatus
                })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert('Failed to update status.');
                }
            });
    }

    function stockModal(productId, initialQty) {
        return {
            open: false,
            newQty: initialQty,
            isSubmitting: false,
            submit() {
                this.isSubmitting = true;
                fetch(`/admin/products/${productId}/update-stock`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            stock_quantity: this.newQty
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Update failed.');
                            this.isSubmitting = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Something went wrong.');
                        this.isSubmitting = false;
                    });
            }
        }
    }
</script>
@endsection