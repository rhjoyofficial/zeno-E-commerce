@extends('layouts.admin')
@section('title', 'Category Products Management')
@section('content')



<div class="container mx-auto px-4 py-8">
    <!-- Category Header Section -->
    <div class="bg-white -xl shadow-sm p-6 mb-8">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                @if($category->categoryImg)
                <div class="flex-shrink-0">
                    <img src="{{ asset('storage/' . $category->categoryImg) }}" alt="{{ $category->name }} logo"
                        class="w-20 h-20 object-contain -lg border border-gray-200 p-1">
                </div>
                @endif
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $category->categoryName }}</h1>
                    @if($category->description)
                    <div class="prose text-gray-600 max-w-3xl">
                        {!! nl2br(e($category->description)) !!}
                    </div>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.categories.index') }}"
                    class="flex items-center px-4 py-2 bg-white border border-gray-300 -lg text-gray-700 hover: transition-colors shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Categorys
                </a>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <div class="bg-white -xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Products by {{ $category->name }}</h2>
                <p class="text-gray-500 mt-1">{{ $category->products->count() }} products found</p>
            </div>
            <div>
                <a href="{{ route('admin.categories.products.create', $category->id) }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium -md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Product
                </a>
            </div>
        </div>

        @if($category->products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
            @foreach($category->products as $product)
            <div class="bg-white -lg border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                <div class="relative">
                    <a href="#" class="block">
                        <img src="{{ $product->image }}" alt="{{ $product->title }}" class="w-full h-48 object-cover">

                        <!-- Discount Badge -->
                        @if($product->discount > 0)
                        <span
                            class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-2 py-1 -full">
                            -{{ $product->discount }}%
                        </span>
                        @endif

                        <!-- Stock Status -->
                        <span
                            class="absolute bottom-3 left-3 bg-{{ $product->stock ? 'indigo' : 'red' }}-500 text-white text-xs font-bold px-2 py-1 -full">
                            {{ $product->stock ? 'In Stock' : 'Out of Stock' }}
                        </span>
                    </a>
                </div>

                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-semibold text-lg text-gray-800">{{ $product->title }}</h3>
                        <!-- Star Rating -->
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <span class="text-sm text-gray-600 ml-1">{{ $product->star }}</span>
                        </div>
                    </div>

                    <p class="text-gray-600 text-sm mb-3">{{ $product->short_description }}</p>

                    <div class="flex items-center justify-between">
                        <div>
                            @if($product->discount_price > 0)
                            <span class="text-lg font-bold text-gray-900">{{ config('app.currency_symbol') }}{{ number_format($product->discount_price, 2)
                                }}</span>
                            <span class="text-sm text-gray-500 line-through ml-2">{{ config('app.currency_symbol') }}{{ number_format($product->price, 2)
                                }}</span>
                            @else
                            <span class="text-lg font-bold text-gray-900">{{ config('app.currency_symbol') }}{{ number_format($product->price, 2)
                                }}</span>
                            @endif
                        </div>
                        <span
                            class="text-xs px-2 py-1 -full {{ $product->remark === 'popular' ? 'bg-purple-100 text-purple-800' : ($product->remark === 'new' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($product->remark) }}
                        </span>
                    </div>
                </div>

                <div class="px-4 py-3  border-t border-gray-200 flex justify-between">
                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Details</a>
                    <a href="#" class="text-green-600 hover:text-green-800 text-sm font-medium">Edit</a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No products found</h3>
            <p class="mt-1 text-gray-500">This category doesn't have any products yet.</p>
            <div class="mt-6">
                <a href="{{ route('admin.categories.products.create', $category->id) }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium -md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Product
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection