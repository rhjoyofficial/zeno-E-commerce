@extends('layouts.app')
@section('title', 'Product Page')
@section('content')

<div class="max-w-7xl mx-auto sm:px-6 md:px-10 lg:px-8 py-16">
    <!-- Breadcrumb -->
    @php
    $breadcrumbs = [
    ['title' => 'Home', 'url' => '/'],
    ['title' => 'Product Page']
    ];
    @endphp

    <x-breadcrumbs :breadcrumbs="$breadcrumbs" />
    {{-- End Breadcrumbs --}}
    <div class="space-y-6">
        <!-- Heading -->
        <h1 class="text-[40px] font-bold tracking-tight text-black font-megumi uppercase leading-10">MEN’S FASHION</h1>
        <p class="text-base text-black max-w-xl leading-6 tracking-tight">From streetwear to formals, shop the latest
            styles</p>
    </div>
    <!-- Filter Tabs and Button -->
    <div class="flex justify-between items-end border-b-2 border-gray-200 my-10">
        <!-- Tabs -->
        <div class="flex space-x-6 overflow-x-auto text-base font-medium text-black">
            <button
                class="relative pb-3 after:content-[''] after:absolute after:bottom-0 after:left-0 after:right-0 after:h-0.5 after:bg-black"
                onclick="filterProducts('all', event)">
                All Clothing
            </button>
            <button class="pb-3" onclick="filterProducts('tshirts', event)">T-Shirts</button>
            <button class="pb-3" onclick="filterProducts('shirts_polo', event)">Shirts & Polo</button>
            <button class="pb-3" onclick="filterProducts('denim_jeans', event)">Denim Jeans</button>
            <button class="pb-3" onclick="filterProducts('trousers', event)">Trousers</button>
            <button class="pb-3" onclick="filterProducts('sweatshirts', event)">Sweatshirts</button>
            <button class="pb-3" onclick="filterProducts('jackets_coats', event)">Jackets & Coats</button>
            <button class="pb-3" onclick="filterProducts('hoodies', event)">Hoodies</button>
        </div>

        <!-- Filter Icon and Text -->
        <button class="flex items-end gap-1 pb-3 text-sm font-semibold text-black uppercase">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 16 14" fill="none">
                <path d="M14.6668 1H1.3335L6.66683 7.30667V11.6667L9.3335 13V7.30667L14.6668 1Z" stroke="black"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            FILTER
        </button>
    </div>


    {{-- Product Grid --}}
    @include('products.index', ['products' => $products])

    <!-- Show More Button -->
    <div class="my-16 text-center">
        <button class="bg-black text-white px-10 py-3 text-xl transition-colors tracking-[2px] font-semibold uppercase">
            Load More
        </button>
    </div>
</div>
@endsection
