@extends('layouts.app')
@section('title', 'Order ' . $order->order_number)
@section('content')
<div class="min-h-screen bg-gray-100">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Order {{ $order->order_number }}</h1>
            <a href="{{ route('customer.orders') }}" class="text-sm text-indigo-600 hover:text-indigo-800">← Back to Orders</a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Items --}}
            <div class="lg:col-span-2 bg-white shadow overflow-hidden">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-lg font-semibold text-gray-800">Items Ordered</h2>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($order->orderItems as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $item->name }}</p>
                                    @if($item->variant_color || $item->variant_size)
                                        <p class="text-xs text-gray-400">{{ implode(' / ', array_filter([$item->variant_color, $item->variant_size])) }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400">SKU: {{ $item->sku }}</p>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right text-sm">{{ number_format($item->price, 2) }}</td>
                                <td class="px-6 py-4 text-right text-sm font-medium">{{ number_format($item->row_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t bg-gray-50 text-sm space-y-1 text-right">
                    <p class="text-gray-500">Subtotal: <span class="font-medium text-gray-800">{{ number_format($order->subtotal, 2) }}</span></p>
                    @if($order->discount_amount > 0)
                        <p class="text-gray-500">Discount: <span class="font-medium text-red-600">−{{ number_format($order->discount_amount, 2) }}</span></p>
                    @endif
                    @if($order->tax_amount > 0)
                        <p class="text-gray-500">Tax: <span class="font-medium text-gray-800">{{ number_format($order->tax_amount, 2) }}</span></p>
                    @endif
                    <p class="text-base font-bold text-gray-900 pt-1 border-t border-gray-200">
                        Grand Total: {{ $order->currency }} {{ number_format($order->total, 2) }}
                    </p>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-4">
                <div class="bg-white shadow p-6">
                    <h3 class="font-semibold text-gray-800 mb-3 border-b pb-2">Order Summary</h3>
                    <div class="text-sm space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status</span>
                            <span class="font-medium">{{ str_replace('_', ' ', ucfirst($order->status)) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Payment</span>
                            <span class="font-medium">{{ str_replace('_', ' ', ucfirst($order->payment_status)) }}</span>
                        </div>
                        @if($order->payment_method)
                            <div class="flex justify-between">
                                <span class="text-gray-500">Method</span>
                                <span class="font-medium">{{ $order->payment_method }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-500">Placed</span>
                            <span class="font-medium">{{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                @if($order->shippingAddress)
                    <div class="bg-white shadow p-6">
                        <h3 class="font-semibold text-gray-800 mb-3 border-b pb-2">Delivery Address</h3>
                        <address class="text-sm text-gray-600 not-italic space-y-0.5">
                            <p class="font-medium text-gray-900">{{ $order->shippingAddress->name }}</p>
                            <p>{{ $order->shippingAddress->address }}</p>
                            <p>{{ $order->shippingAddress->city }}@if($order->shippingAddress->postal_code), {{ $order->shippingAddress->postal_code }}@endif</p>
                            <p>{{ $order->shippingAddress->phone }}</p>
                        </address>
                    </div>
                @endif
            </div>

        </div>
    </main>
</div>
@endsection
