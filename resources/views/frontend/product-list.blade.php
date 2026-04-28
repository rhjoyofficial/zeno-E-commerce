@extends('layouts.app')
@section('title', ($activeCategory ? $activeCategory->category_name : 'All Products') . ' | Shop')
@section('content')

<div class="max-w-7xl mx-auto sm:px-6 md:px-10 lg:px-8 py-16">
    @php
    $breadcrumbs = [
        ['title' => 'Home', 'url' => route('home')],
        ['title' => $activeCategory ? $activeCategory->category_name : 'All Products'],
    ];
    @endphp

    <x-breadcrumbs :breadcrumbs="$breadcrumbs" />

    <div class="space-y-6">
        <h1 class="text-[40px] font-bold tracking-tight text-black font-megumi uppercase leading-10">
            {{ $activeCategory ? $activeCategory->category_name : 'All Products' }}
        </h1>
        @if($activeCategory && $activeCategory->description)
            <p class="text-base text-black max-w-xl leading-6 tracking-tight">{{ $activeCategory->description }}</p>
        @else
            <p class="text-base text-black max-w-xl leading-6 tracking-tight">Browse our full collection of styles</p>
        @endif
    </div>

    <!-- Category Filter Tabs -->
    @if($topCategories->isNotEmpty())
    <div class="flex justify-between items-end border-b-2 border-gray-200 my-10">
        <div class="flex space-x-6 overflow-x-auto text-base font-medium text-black">
            <a href="{{ route('products.list') }}"
                class="relative pb-3 whitespace-nowrap {{ !$activeCategory ? 'after:content-[\'\'] after:absolute after:bottom-0 after:left-0 after:right-0 after:h-0.5 after:bg-black font-semibold' : '' }}">
                All
            </a>
            @foreach($topCategories as $cat)
            <a href="{{ route('products.list', ['category' => $cat->id]) }}"
                class="relative pb-3 whitespace-nowrap {{ $activeCategory?->id === $cat->id ? 'after:content-[\'\'] after:absolute after:bottom-0 after:left-0 after:right-0 after:h-0.5 after:bg-black font-semibold' : '' }}">
                {{ $cat->category_name }}
            </a>
            @endforeach
        </div>

        <button class="flex items-end gap-1 pb-3 text-sm font-semibold text-black uppercase">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 16 14" fill="none">
                <path d="M14.6668 1H1.3335L6.66683 7.30667V11.6667L9.3335 13V7.30667L14.6668 1Z" stroke="black"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            FILTER
        </button>
    </div>
    @endif

    {{-- Product Grid --}}
    @if($products->isNotEmpty())
        @include('products.index', ['products' => $products])

        <div class="my-8">
            {{ $products->links() }}
        </div>
    @else
        <div class="py-24 text-center text-gray-500">
            <p class="text-lg">No products found{{ $activeCategory ? ' in this category' : '' }}.</p>
            <a href="{{ route('products.list') }}" class="mt-4 inline-block text-black underline text-sm">View all products</a>
        </div>
    @endif
</div>
@endsection
