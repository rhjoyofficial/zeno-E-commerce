@extends('layouts.admin')
@section('title', 'Orders Management')
@section('content')
    <div class="container mx-auto p-4">

        <div class=" bg-white border border-gray-200">
            <!-- Page Header -->
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Orders Management</h2>
                </div>
                <div>
                    <button
                        class="inline-flex items-center px-4 py-2 bg-black text-white hover:bg-gray-800 transition-colors text-sm"
                        id="filterToggle">
                        Filters
                    </button>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="mb-6 p-4 bg-gray-50 hidden border border-gray-200" id="filtersSection">
                <form id="filterForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Order Status</label>
                        <select name="status" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Statuses</option>
                            @foreach(['pending','confirmed','processing','shipped','delivered','cancelled','refunded','partially_refunded','on_hold'] as $s)
                                <option value="{{ $s }}" @selected(request('status') === $s)>{{ str_replace('_',' ',ucfirst($s)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                        <select name="payment_status"
                            class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Payment Statuses</option>
                            @foreach(['pending','paid','partially_paid','refunded','failed'] as $ps)
                                <option value="{{ $ps }}" @selected(request('payment_status') === $ps)>{{ str_replace('_',' ',ucfirst($ps)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-4 flex justify-end space-x-2">
                        <button type="button" id="applyFilters"
                            class="px-4 py-2 bg-black text-white hover:bg-gray-900 transition">
                            Apply Filters
                        </button>
                        <button type="reset" id="resetFilters"
                            class="px-4 py-2 bg-red-600 text-gray-50 hover:bg-red-700 transition">
                            Reset
                        </button>
                    </div>
                </form>
            </div>

            <!-- Orders Table -->
            <div class="overflow-x-auto ">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Order Number
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Customer
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Payment
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->order_number }}</div>
                                    @if ($order->formatted_invoice_number)
                                        <div class="text-sm text-gray-500">Invoice: {{ $order->formatted_invoice_number }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->customer_email ?? '—' }}</div>
                                    @if ($order->user)
                                        <div class="text-sm text-gray-500">{{ $order->user->name }}</div>
                                    @else
                                        <div class="text-sm text-gray-400 italic">Guest</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if ($order->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status == 'confirmed') bg-blue-100 text-blue-800
                                        @elseif($order->status == 'processing') bg-indigo-100 text-indigo-800
                                        @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                                        @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                        @elseif($order->status == 'refunded' || $order->status == 'partially_refunded') bg-gray-100 text-gray-800
                                        @elseif($order->status == 'on_hold') bg-orange-100 text-orange-800 @endif">
                                        {{ str_replace('_', ' ', ucfirst($order->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if ($order->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->payment_status == 'paid') bg-green-100 text-green-800
                                        @elseif($order->payment_status == 'partially_paid') bg-blue-100 text-blue-800
                                        @elseif($order->payment_status == 'refunded') bg-gray-100 text-gray-800
                                        @elseif($order->payment_status == 'failed') bg-red-100 text-red-800 @endif">
                                        {{ str_replace('_', ' ', ucfirst($order->payment_status)) }}
                                    </span>
                                    @if ($order->payment_method)
                                        <div class="text-sm text-gray-500 mt-1">{{ $order->payment_method }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->currency }}
                                        {{ number_format($order->total, 2) }}</div>
                                    @if ($order->total_paid > 0)
                                        <div class="text-sm text-gray-500">Paid: {{ $order->currency }}
                                            {{ number_format($order->total_paid, 2) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-3">
                                        {{-- Quick view --}}
                                        <button type="button"
                                            class="quick-view text-black hover:text-blue-900"
                                            data-order-id="{{ $order->id }}"
                                            title="Quick View">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        {{-- Full detail --}}
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                            title="View Order"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </a>
                                        {{-- Inline status --}}
                                        <select class="status-select text-xs border-gray-300 py-1 pl-1 pr-6"
                                            data-order-id="{{ $order->id }}"
                                            data-current-status="{{ $order->status }}">
                                            @foreach(['pending','confirmed','processing','shipped','delivered','cancelled','refunded','partially_refunded','on_hold'] as $s)
                                                <option value="{{ $s }}" @selected($order->status === $s)>{{ str_replace('_',' ',ucfirst($s)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($orders->hasPages())
                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>

        <!-- Order Details Modal (will be populated via JavaScript) -->
        <div id="orderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
            <div
                class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 bg-white max-h-screen overflow-y-auto">
                <div class="mt-3">
                    <div class="flex justify-between items-center pb-3 border-b">
                        <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Order Details</h3>
                        <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div id="modalContent" class="py-4">
                        <!-- Content will be loaded via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle filters section
            const filterToggle = document.getElementById('filterToggle');
            const filtersSection = document.getElementById('filtersSection');

            if (filterToggle && filtersSection) {
                filterToggle.addEventListener('click', function() {
                    filtersSection.classList.toggle('hidden');
                });
            }

            // Apply filters
            const applyFilters = document.getElementById('applyFilters');
            const filterForm = document.getElementById('filterForm');

            if (applyFilters && filterForm) {
                applyFilters.addEventListener('click', function() {
                    const formData = new FormData(filterForm);
                    const params = new URLSearchParams();

                    for (const [key, value] of formData.entries()) {
                        if (value) {
                            params.append(key, value);
                        }
                    }

                    window.location.href = '{{ route('admin.orders.index') }}?' + params.toString();
                });
            }

            // Reset filters
            const resetFilters = document.getElementById('resetFilters');

            if (resetFilters) {
                resetFilters.addEventListener('click', function() {
                    window.location.href = '{{ route('admin.orders.index') }}';
                });
            }

            // Quick view modal functionality
            const quickViewButtons = document.querySelectorAll('.quick-view');
            const orderModal = document.getElementById('orderModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');
            const closeModal = document.getElementById('closeModal');

            if (quickViewButtons.length && orderModal) {
                quickViewButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const orderId = this.getAttribute('data-order-id');

                        // Show loading state
                        modalContent.innerHTML =
                            '<div class="flex justify-center items-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-black"></div></div>';

                        // Fetch order details
                        fetch(`/admin/orders/${orderId}/quick-view`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    modalTitle.textContent =
                                        `Order #${data.order.order_number}`;
                                    modalContent.innerHTML = data.html;
                                    orderModal.classList.remove('hidden');
                                } else {
                                    modalContent.innerHTML =
                                        '<div class="text-red-600 py-4">Error loading order details.</div>';
                                }
                            })
                            .catch(error => {
                                modalContent.innerHTML =
                                    '<div class="text-red-600 py-4">Error loading order details.</div>';
                            });
                    });
                });
            }

            if (closeModal && orderModal) {
                closeModal.addEventListener('click', function() {
                    orderModal.classList.add('hidden');
                });

                // Close modal when clicking outside
                window.addEventListener('click', function(event) {
                    if (event.target === orderModal) {
                        orderModal.classList.add('hidden');
                    }
                });
            }

            // Update order status
            const statusSelects = document.querySelectorAll('.status-select');

            statusSelects.forEach(select => {
                select.addEventListener('change', function() {
                    const orderId = this.getAttribute('data-order-id');
                    const newStatus = this.value;

                    fetch(`/admin/orders/${orderId}/status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                status: newStatus
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message
                                alert('Status updated successfully');
                                // Optionally reload the page or update the UI
                                window.location.reload();
                            } else {
                                alert('Error updating status: ' + data.message);
                                // Revert the select value
                                this.value = this.getAttribute('data-current-status');
                            }
                        })
                        .catch(error => {
                            alert('Error updating status');
                            this.value = this.getAttribute('data-current-status');
                        });
                });
            });
        });
    </script>
@endpush
