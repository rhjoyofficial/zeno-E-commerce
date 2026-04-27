@extends('layouts.app')
@section('title', 'Order Confirmed')
@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    {{-- Success Banner --}}
    <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 uppercase font-megumi">Order Confirmed!</h1>
        <p class="mt-2 text-gray-500">Thank you for your purchase. We've received your order.</p>
    </div>

    {{-- Order Meta --}}
    <div class="bg-white border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
            <div>
                <p class="text-gray-500 uppercase text-xs font-semibold tracking-wider mb-1">Order Number</p>
                <p class="font-semibold text-gray-900">{{ $order->order_number }}</p>
            </div>
            @if($order->formatted_invoice_number)
            <div>
                <p class="text-gray-500 uppercase text-xs font-semibold tracking-wider mb-1">Invoice</p>
                <p class="font-semibold text-gray-900">{{ $order->formatted_invoice_number }}</p>
            </div>
            @endif
            <div>
                <p class="text-gray-500 uppercase text-xs font-semibold tracking-wider mb-1">Payment</p>
                <p class="font-semibold text-gray-900">{{ str_replace('_', ' ', ucfirst($order->payment_method)) }}</p>
            </div>
            <div>
                <p class="text-gray-500 uppercase text-xs font-semibold tracking-wider mb-1">Status</p>
                <p class="font-semibold text-green-700">{{ str_replace('_', ' ', ucfirst($order->status)) }}</p>
            </div>
        </div>
    </div>

    {{-- Items --}}
    <div class="bg-white border border-gray-200 mb-6">
        <div class="p-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Items Ordered</h2>
        </div>
        <table class="w-full text-sm">
            <tbody class="divide-y divide-gray-100">
                @foreach($order->orderItems as $item)
                <tr>
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-900">{{ $item->name }}</p>
                        @if($item->variant_color || $item->variant_size)
                            <p class="text-xs text-gray-400">{{ implode(' / ', array_filter([$item->variant_color, $item->variant_size])) }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-right">× {{ $item->quantity }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900 text-right">{{ number_format($item->row_total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-100 bg-gray-50 text-sm space-y-1 text-right">
            <p class="text-gray-500">Subtotal: <span class="text-gray-800 font-medium">{{ number_format($order->subtotal, 2) }}</span></p>
            @if($order->discount_amount > 0)
                <p class="text-gray-500">Discount: <span class="text-red-600 font-medium">−{{ number_format($order->discount_amount, 2) }}</span></p>
            @endif
            @if($order->tax_amount > 0)
                <p class="text-gray-500">Tax: <span class="text-gray-800 font-medium">{{ number_format($order->tax_amount, 2) }}</span></p>
            @endif
            <p class="text-base font-bold text-gray-900 border-t border-gray-200 pt-2">
                Grand Total: {{ $order->currency }} {{ number_format($order->total, 2) }}
            </p>
        </div>
    </div>

    {{-- Delivery address --}}
    @if($order->shippingAddress)
    <div class="bg-white border border-gray-200 p-6 mb-8">
        <h2 class="font-semibold text-gray-800 mb-3">Delivery Address</h2>
        <address class="text-sm text-gray-600 not-italic space-y-0.5">
            <p class="font-medium text-gray-900">{{ $order->shippingAddress->name }}</p>
            <p>{{ $order->shippingAddress->address }}</p>
            <p>{{ $order->shippingAddress->city }}@if($order->shippingAddress->postal_code), {{ $order->shippingAddress->postal_code }}@endif</p>
            <p>{{ $order->shippingAddress->phone }}</p>
        </address>
    </div>
    @endif

    {{-- CTA --}}
    <div class="flex flex-col sm:flex-row gap-4 justify-center text-center">
        @auth
            <a href="{{ route('customer.orders') }}"
                class="px-6 py-3 bg-black text-white text-sm uppercase tracking-wider hover:bg-gray-800 transition">
                View My Orders
            </a>
        @endauth
        <a href="{{ route('products.list') }}"
            class="px-6 py-3 border border-black text-black text-sm uppercase tracking-wider hover:bg-gray-50 transition">
            Continue Shopping
        </a>
    </div>

</div>
@endsection
