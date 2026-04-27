@extends('layouts.app')
@section('title', 'My Wishlist')
@section('content')
<div class="min-h-screen bg-gray-100">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900">My Wishlist</h1>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 text-sm">{{ session('success') }}</div>
        @endif

        @if($wishes->isEmpty())
            <div class="bg-white shadow p-10 text-center text-gray-500">
                <p class="text-lg">Your wishlist is empty.</p>
                <a href="{{ route('products.list') }}"
                    class="mt-4 inline-block bg-black text-white px-6 py-2 text-sm hover:bg-gray-800">
                    Browse Products
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($wishes as $wish)
                    <div class="bg-white shadow flex flex-col">
                        @if($wish->product->primaryImage)
                            <img src="{{ Storage::url($wish->product->primaryImage->image_path) }}"
                                alt="{{ $wish->product->title }}"
                                class="w-full h-52 object-cover">
                        @endif
                        <div class="p-4 flex flex-col flex-1">
                            <p class="font-semibold text-gray-900 text-sm">{{ $wish->product->title }}</p>
                            <p class="text-gray-700 text-sm mt-1">
                                @if($wish->product->discount_price)
                                    <span class="line-through text-gray-400">${{ number_format($wish->product->price, 2) }}</span>
                                    <span class="ml-1 font-bold">${{ number_format($wish->product->discount_price, 2) }}</span>
                                @else
                                    ${{ number_format($wish->product->price, 2) }}
                                @endif
                            </p>
                            <div class="mt-auto pt-4 flex gap-2">
                                <a href="{{ route('products.show', $wish->product) }}"
                                    class="flex-1 text-center bg-black text-white text-xs py-2 hover:bg-gray-800">
                                    View
                                </a>
                                <form action="{{ route('customer.wishlist.remove', $wish->product) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-2 border border-red-300 text-red-600 text-xs hover:bg-red-50">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</div>
@endsection
