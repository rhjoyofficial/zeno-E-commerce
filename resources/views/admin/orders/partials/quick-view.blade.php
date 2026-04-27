<div class="space-y-4 text-sm">

    {{-- Order & Payment Summary --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="text-gray-500 uppercase text-xs font-semibold tracking-wider">Order Number</p>
            <p class="font-medium text-gray-900">{{ $order->order_number }}</p>
        </div>
        <div>
            <p class="text-gray-500 uppercase text-xs font-semibold tracking-wider">Invoice</p>
            <p class="font-medium text-gray-900">{{ $order->formatted_invoice_number ?? '—' }}</p>
        </div>
        <div>
            <p class="text-gray-500 uppercase text-xs font-semibold tracking-wider">Status</p>
            <p class="font-medium text-gray-900">{{ str_replace('_', ' ', ucfirst($order->status)) }}</p>
        </div>
        <div>
            <p class="text-gray-500 uppercase text-xs font-semibold tracking-wider">Payment</p>
            <p class="font-medium text-gray-900">{{ str_replace('_', ' ', ucfirst($order->payment_status)) }}
                @if($order->payment_method) <span class="text-gray-500">({{ $order->payment_method }})</span> @endif
            </p>
        </div>
        <div>
            <p class="text-gray-500 uppercase text-xs font-semibold tracking-wider">Total</p>
            <p class="font-medium text-gray-900">{{ $order->currency }} {{ number_format($order->total, 2) }}</p>
        </div>
        <div>
            <p class="text-gray-500 uppercase text-xs font-semibold tracking-wider">Placed At</p>
            <p class="font-medium text-gray-900">{{ $order->created_at->format('M d, Y h:i A') }}</p>
        </div>
    </div>

    {{-- Customer --}}
    <div class="border-t pt-4">
        <p class="text-gray-500 uppercase text-xs font-semibold tracking-wider mb-1">Customer</p>
        <p class="font-medium text-gray-900">{{ $order->customer_email ?? '—' }}</p>
        @if($order->shippingAddress)
            <p class="text-gray-600 mt-1">
                {{ $order->shippingAddress->name }},
                {{ $order->shippingAddress->address }},
                {{ $order->shippingAddress->city }}
                @if($order->shippingAddress->postal_code) — {{ $order->shippingAddress->postal_code }} @endif
            </p>
        @endif
    </div>

    {{-- Order Items --}}
    @if($order->orderItems->isNotEmpty())
        <div class="border-t pt-4">
            <p class="text-gray-500 uppercase text-xs font-semibold tracking-wider mb-2">Items</p>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-500 border-b">
                        <th class="pb-1 font-medium">Product</th>
                        <th class="pb-1 font-medium text-right">Qty</th>
                        <th class="pb-1 font-medium text-right">Price</th>
                        <th class="pb-1 font-medium text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td class="py-1">
                                {{ $item->name }}
                                @if($item->variant_color || $item->variant_size)
                                    <span class="text-gray-400 text-xs">
                                        ({{ implode(', ', array_filter([$item->variant_color, $item->variant_size])) }})
                                    </span>
                                @endif
                            </td>
                            <td class="py-1 text-right">{{ $item->quantity }}</td>
                            <td class="py-1 text-right">{{ number_format($item->price, 2) }}</td>
                            <td class="py-1 text-right font-medium">{{ number_format($item->row_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Totals --}}
    <div class="border-t pt-3 space-y-1 text-right">
        <p class="text-gray-500">Subtotal: <span class="text-gray-900 font-medium">{{ number_format($order->subtotal, 2) }}</span></p>
        @if($order->discount_amount > 0)
            <p class="text-gray-500">Discount: <span class="text-red-600 font-medium">-{{ number_format($order->discount_amount, 2) }}</span></p>
        @endif
        @if($order->tax_amount > 0)
            <p class="text-gray-500">Tax: <span class="text-gray-900 font-medium">{{ number_format($order->tax_amount, 2) }}</span></p>
        @endif
        <p class="text-base font-bold text-gray-900">Grand Total: {{ $order->currency }} {{ number_format($order->total, 2) }}</p>
    </div>
</div>
