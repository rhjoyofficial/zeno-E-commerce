@extends('layouts.admin')
@section('title', 'Manage Variants - ' . $product->title)
@section('content')
    <div class="container mx-auto p-4">
        <div class="bg-white shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">Manage Variants</h1>
                    <p class="text-gray-600">Product: {{ $product->title }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.products.index') }}"
                        class="px-4 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-colors text-base">
                        Back to Products
                    </a>
                    <a href="{{ route('admin.products.variants.create', $product) }}"
                        class="px-4 py-2 bg-black text-white hover:bg-gray-800 transition-colors text-base">
                        Add New Variant
                    </a>
                </div>
            </div>

            @if ($variants->isEmpty())
                <div class="bg-white border border-gray-200 p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No variants found</h3>
                    <p class="mt-1 text-gray-500">This product doesn't have any variants yet.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.products.variants.create', $product) }}"
                            class="inline-flex items-center px-4 py-2 bg-black text-white hover:bg-gray-800 transition-colors text-base">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add New Variant
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Color</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Size</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($variants as $variant)
                                    @php
                                        $alert = $variant->stock_alert ?? 5;
                                        $stockClass = $variant->stock_quantity <= $alert
                                            ? 'bg-red-100 text-red-800'
                                            : 'bg-green-100 text-green-800';
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($variant->color)
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-6 w-6 rounded-full border border-gray-300"
                                                        style="background-color: {{ $variant->color->hex_code }}"></div>
                                                    <div class="ml-2 text-base text-gray-900">{{ $variant->color->name }}</div>
                                                </div>
                                            @else
                                                <span class="text-base text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-base text-gray-900">
                                            {{ $variant->size->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-base text-gray-900">
                                            {{ config('app.currency_symbol') }}{{ number_format($variant->price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button type="button" class="w-full text-left focus:outline-none"
                                                onclick="openVariantStockModal({{ $variant->id }}, {{ $variant->stock_quantity }}, {{ $alert }})">
                                                <div class="text-base">
                                                    <span id="variant-stock-badge-{{ $variant->id }}"
                                                        class="px-2 py-1 inline-flex text-sm font-medium rounded-full {{ $stockClass }}">
                                                        {{ $variant->stock_quantity }}
                                                    </span>
                                                </div>
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-base text-gray-900">
                                            {{ $variant->sku ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-sm font-medium rounded-full {{ $variant->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($variant->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-base font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('admin.products.variants.edit', [$product, $variant]) }}"
                                                    class="text-blue-600 hover:text-blue-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <form
                                                    action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Are you sure you want to delete this variant?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Shared Variant Stock Modal -->
    <div id="variant-stock-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        role="dialog" aria-modal="true">
        <div class="bg-white p-6 w-80 max-w-[90vw]">
            <h2 class="text-lg font-semibold mb-4">Update Stock Quantity</h2>
            <form id="variant-stock-form">
                <input id="variant-stock-qty" type="number"
                    class="w-full px-3 py-2 text-base font-medium border border-gray-300 focus:outline-none focus:ring focus:border-black"
                    min="0" required>
                <div class="mt-4 flex justify-end space-x-2">
                    <button type="button" id="variant-stock-cancel"
                        class="px-4 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" id="variant-stock-submit" class="px-6 py-2 bg-black text-white">
                        <span id="variant-stock-idle">Save</span>
                        <span id="variant-stock-busy" class="hidden">
                            <span class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-1"></span>
                            Saving...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    (function () {
        let currentVariantId = null;
        let currentStockAlert = 5;

        const modal     = document.getElementById('variant-stock-modal');
        const form      = document.getElementById('variant-stock-form');
        const qtyInput  = document.getElementById('variant-stock-qty');
        const cancelBtn = document.getElementById('variant-stock-cancel');
        const submitBtn = document.getElementById('variant-stock-submit');
        const idleSpan  = document.getElementById('variant-stock-idle');
        const busySpan  = document.getElementById('variant-stock-busy');

        function closeModal() {
            modal.classList.add('hidden');
            currentVariantId = null;
        }

        cancelBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!currentVariantId) return;

            const newQty = parseInt(qtyInput.value, 10);
            submitBtn.disabled = true;
            idleSpan.classList.add('hidden');
            busySpan.classList.remove('hidden');

            try {
                const res = await fetch(`/admin/products/variants/${currentVariantId}/update-stock`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ stock_quantity: newQty })
                });

                const data = await res.json();
                if (!res.ok || !data.success) throw new Error(data.message || 'Update failed.');

                const badge = document.getElementById(`variant-stock-badge-${currentVariantId}`);
                if (badge) {
                    badge.textContent = newQty;
                    badge.className = 'px-2 py-1 inline-flex text-sm font-medium rounded-full';
                    badge.classList.add(...(newQty <= currentStockAlert
                        ? ['bg-red-100', 'text-red-800']
                        : ['bg-green-100', 'text-green-800']));
                }

                closeModal();
            } catch (err) {
                alert(err.message || 'Something went wrong.');
            } finally {
                submitBtn.disabled = false;
                idleSpan.classList.remove('hidden');
                busySpan.classList.add('hidden');
            }
        });

        window.openVariantStockModal = function (variantId, qty, alert) {
            currentVariantId = variantId;
            currentStockAlert = alert;
            qtyInput.value = qty;
            modal.classList.remove('hidden');
            qtyInput.focus();
        };
    })();
    </script>
@endsection
