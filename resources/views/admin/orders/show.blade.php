@extends('layouts.admin')
@section('title', 'Order ' . $order->order_number)
@section('content')
<div class="container mx-auto p-4 max-w-5xl">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Order {{ $order->order_number }}</h2>
            @if($order->formatted_invoice_number)
                <p class="text-sm text-gray-500">Invoice: {{ $order->formatted_invoice_number }}</p>
            @endif
        </div>
        <a href="{{ route('admin.orders.index') }}"
            class="px-4 py-2 bg-gray-100 text-gray-700 text-sm hover:bg-gray-200 transition">
            ← Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Items + Totals --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Order Items --}}
            <div class="bg-white border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800">Items</h3>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($order->orderItems as $item)
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-900">{{ $item->name }}</p>
                                    <p class="text-gray-500 text-xs">SKU: {{ $item->sku }}</p>
                                    @if($item->variant_color || $item->variant_size)
                                        <p class="text-gray-400 text-xs">
                                            {{ implode(' / ', array_filter([$item->variant_color, $item->variant_size])) }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($item->price, 2) }}</td>
                                <td class="px-4 py-3 text-right font-medium">{{ number_format($item->row_total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-center text-gray-400">No items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Totals --}}
                <div class="p-4 border-t border-gray-100 bg-gray-50 space-y-1 text-sm text-right">
                    <p class="text-gray-500">Subtotal: <span class="font-medium text-gray-800">{{ $order->currency }} {{ number_format($order->subtotal, 2) }}</span></p>
                    @if($order->discount_amount > 0)
                        <p class="text-gray-500">Discount: <span class="font-medium text-red-600">−{{ number_format($order->discount_amount, 2) }}</span></p>
                    @endif
                    @if($order->tax_amount > 0)
                        <p class="text-gray-500">Tax: <span class="font-medium text-gray-800">{{ number_format($order->tax_amount, 2) }}</span></p>
                    @endif
                    <p class="text-base font-bold text-gray-900 pt-1 border-t border-gray-200">Grand Total: {{ $order->currency }} {{ number_format($order->total, 2) }}</p>
                </div>
            </div>
        </div>

        {{-- Right: Info Sidebar --}}
        <div class="space-y-4">

            {{-- Status Card --}}
            <div class="bg-white border border-gray-200 p-4 space-y-3">
                <h3 class="font-semibold text-gray-800 border-b pb-2">Order Info</h3>
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

                {{-- Inline status update --}}
                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="pt-2 border-t">
                    @csrf
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Update Status</label>
                    <div class="flex gap-2">
                        <select name="status" class="flex-1 text-sm border-gray-300">
                            @foreach(['pending','confirmed','processing','shipped','delivered','cancelled','refunded','partially_refunded','on_hold'] as $s)
                                <option value="{{ $s }}" @selected($order->status === $s)>{{ str_replace('_', ' ', ucfirst($s)) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-3 py-1 bg-black text-white text-sm hover:bg-gray-800">Save</button>
                    </div>
                </form>
            </div>

            {{-- Customer --}}
            <div class="bg-white border border-gray-200 p-4">
                <h3 class="font-semibold text-gray-800 border-b pb-2 mb-3">Customer</h3>
                <div class="text-sm space-y-1">
                    @if($order->user)
                        <p class="font-medium text-gray-900">{{ $order->user->name }}</p>
                    @endif
                    <p class="text-gray-600">{{ $order->customer_email ?? '—' }}</p>
                    <p class="text-gray-600">{{ $order->customer_phone ?? '—' }}</p>
                    @if(!$order->user_id)
                        <p class="text-xs text-gray-400 italic">Guest order</p>
                    @endif
                </div>
            </div>

            {{-- Shipping --}}
            @if($order->shippingAddress)
                <div class="bg-white border border-gray-200 p-4">
                    <h3 class="font-semibold text-gray-800 border-b pb-2 mb-3">Shipping Address</h3>
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
</div>
@endsection
