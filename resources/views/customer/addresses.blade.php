@extends('layouts.app')
@section('title', 'My Addresses')
@section('content')
<div class="min-h-screen bg-gray-100">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900">My Addresses</h1>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @if($addresses->isEmpty())
            <div class="bg-white shadow p-8 text-center text-gray-500">
                <p>No saved addresses found.</p>
                <p class="text-sm mt-1">Addresses are saved automatically when you place an order.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($addresses as $address)
                    <div class="bg-white shadow p-6">
                        @if($address->is_default)
                            <span class="inline-block mb-2 px-2 py-0.5 text-xs font-semibold bg-green-100 text-green-800">Default</span>
                        @endif
                        <address class="text-sm text-gray-700 not-italic space-y-0.5">
                            <p class="font-semibold text-gray-900">{{ $address->name }}</p>
                            <p>{{ $address->address }}</p>
                            <p>{{ $address->city }}@if($address->postal_code), {{ $address->postal_code }}@endif</p>
                            <p>{{ $address->phone }}</p>
                        </address>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</div>
@endsection
