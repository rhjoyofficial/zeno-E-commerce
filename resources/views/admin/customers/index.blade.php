@extends('layouts.admin')
@section('title', 'Customer Management')
@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Filters Section -->
        <div class="bg-white p-4 mb-6">
            <form method="GET" action="{{ route('admin.customers.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <!-- Search Input -->
                    <div class="lg:col-span-1">
                        <label for="search" class="block text-base font-medium text-gray-700 mb-1">Search</label>
                        <div class="relative">
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="pl-10 pr-4 py-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 w-full"
                                placeholder="Name, email, phone...">
                            <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-base font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" name="status"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
                        </select>
                    </div>

                    <!-- Registration Date Filter -->
                    <div>
                        <label for="date" class="block text-base font-medium text-gray-700 mb-1">Registered</label>
                        <select id="date" name="date"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Any Time</option>
                            <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="year" {{ request('date') == 'year' ? 'selected' : '' }}>This Year</option>
                        </select>
                    </div>

                    <!-- Orders Filter -->
                    <div>
                        <label for="orders" class="block text-base font-medium text-gray-700 mb-1">Orders</label>
                        <select id="orders" name="orders"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Any</option>
                            <option value="1" {{ request('orders') == '1' ? 'selected' : '' }}>1+ Orders</option>
                            <option value="5" {{ request('orders') == '5' ? 'selected' : '' }}>5+ Orders</option>
                            <option value="10" {{ request('orders') == '10' ? 'selected' : '' }}>10+ Orders</option>
                        </select>
                    </div>

                    <!-- Sort Filter -->
                    <div>
                        <label for="sort" class="block text-base font-medium text-gray-700 mb-1">Sort By</label>
                        <select id="sort" name="sort"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Default</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end mt-4 space-x-3">
                    <a href="{{ route('admin.customers.index') }}"
                        class="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors">
                        Reset Filters
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-black text-white hover:bg-gray-800 transition-colors">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        @if ($customers->count() > 0)
            <!-- Customers Table -->
            <div class="bg-white overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Customer Management</h1>
                        <p class="text-gray-600">Manage all registered customers</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.customers.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-base font-medium text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add New Customer
                        </a>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-base font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th scope="col" class="px-6 py-3 text-left text-base font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th scope="col" class="px-6 py-3 text-left text-base font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th scope="col" class="px-6 py-3 text-left text-base font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                                <th scope="col" class="px-6 py-3 text-left text-base font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-base font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($customers as $customer)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <!-- Customer Info -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-blue-600 font-medium">
                                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-base font-medium text-gray-900">{{ $customer->name }}</div>
                                                <div class="text-base text-gray-500">ID: {{ $customer->id }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Contact Info -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-base text-gray-900">{{ $customer->email }}</div>
                                        <div class="text-base text-gray-500">
                                            @if ($customer->profile && $customer->profile->phone)
                                                {{ $customer->profile->phone }}
                                            @else
                                                <span class="text-gray-400">Not provided</span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Location Info -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($customer->profile)
                                            <div class="text-base text-gray-900">{{ $customer->profile->city ?? 'N/A' }}</div>
                                            <div class="text-base text-gray-500">{{ Str::limit($customer->profile->address ?? 'N/A', 20) }}</div>
                                        @else
                                            <span class="text-gray-400">No profile</span>
                                        @endif
                                    </td>

                                    <!-- Orders Info -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-base font-medium text-gray-900">{{ $customer->orders_count ?? 0 }} orders</div>
                                        <div class="text-base text-gray-500">
                                            {{ $customer->total_spent ? '$' . number_format($customer->total_spent, 2) : 'No purchases' }}
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClasses = [
                                                'active'   => 'bg-green-100 text-green-800',
                                                'inactive' => 'bg-yellow-100 text-yellow-800',
                                                'banned'   => 'bg-red-100 text-red-800',
                                            ];
                                            $cls = $statusClasses[$customer->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <button type="button"
                                            onclick="openStatusModal({{ $customer->id }}, '{{ $customer->status }}')"
                                            class="focus:outline-none">
                                            <span id="customer-status-badge-{{ $customer->id }}"
                                                class="px-3 py-1 rounded-full text-sm font-medium {{ $cls }}">
                                                {{ ucfirst($customer->status) }}
                                            </span>
                                        </button>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-base font-medium">
                                        <div class="flex justify-end space-x-3">
                                            <a href="{{ route('admin.customers.show', $customer->id) }}"
                                                class="text-green-600 hover:text-green-900 p-1 rounded-full hover:bg-green-50" title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.customers.edit', $customer->id) }}"
                                                class="text-blue-600 hover:text-blue-900 p-1 rounded-full hover:bg-blue-50" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <button type="button"
                                                onclick="confirmBan({{ $customer->id }}, '{{ addslashes($customer->name) }}')"
                                                class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-50" title="Ban">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $customers->links() }}
                </div>
            </div>
        @else
            <div class="p-12 text-center bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No customers found</h3>
                <p class="mt-1 text-gray-500">Try adjusting your search or filter to find what you're looking for.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.customers.create') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-base font-medium text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add New Customer
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Shared Status Modal -->
    <div id="customer-status-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 w-80">
            <h2 class="text-lg font-semibold mb-4">Update Customer Status</h2>
            <input type="hidden" id="status-modal-customer-id">

            <div class="space-y-3">
                <label class="flex items-center space-x-3">
                    <input type="radio" name="customer-new-status" value="active" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                    <span class="text-gray-700">Active</span>
                </label>
                <label class="flex items-center space-x-3">
                    <input type="radio" name="customer-new-status" value="inactive" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                    <span class="text-gray-700">Inactive</span>
                </label>
                <label class="flex items-center space-x-3">
                    <input type="radio" name="customer-new-status" value="banned" class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                    <span class="text-gray-700">Banned</span>
                </label>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" id="status-modal-cancel"
                    class="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="button" id="status-modal-submit"
                    class="px-4 py-2 bg-black text-white hover:bg-gray-800">
                    <span id="status-modal-idle">Update</span>
                    <span id="status-modal-busy" class="hidden">Updating...</span>
                </button>
            </div>
        </div>
    </div>

    <script>
    const statusClasses = {
        active:   'bg-green-100 text-green-800',
        inactive: 'bg-yellow-100 text-yellow-800',
        banned:   'bg-red-100 text-red-800'
    };

    function openStatusModal(customerId, currentStatus) {
        document.getElementById('status-modal-customer-id').value = customerId;
        document.querySelectorAll('input[name="customer-new-status"]').forEach(r => {
            r.checked = r.value === currentStatus;
        });
        document.getElementById('customer-status-modal').classList.remove('hidden');
    }

    document.getElementById('status-modal-cancel').addEventListener('click', () => {
        document.getElementById('customer-status-modal').classList.add('hidden');
    });

    document.getElementById('customer-status-modal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('customer-status-modal')) {
            document.getElementById('customer-status-modal').classList.add('hidden');
        }
    });

    document.getElementById('status-modal-submit').addEventListener('click', async () => {
        const customerId = document.getElementById('status-modal-customer-id').value;
        const newStatus  = document.querySelector('input[name="customer-new-status"]:checked')?.value;
        if (!newStatus) return;

        const submitBtn = document.getElementById('status-modal-submit');
        submitBtn.disabled = true;
        document.getElementById('status-modal-idle').classList.add('hidden');
        document.getElementById('status-modal-busy').classList.remove('hidden');

        try {
            const res = await fetch(`/admin/customers/${customerId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: newStatus })
            });

            const data = await res.json();
            if (data.success) {
                const badge = document.getElementById(`customer-status-badge-${customerId}`);
                if (badge) {
                    badge.className = `px-3 py-1 rounded-full text-sm font-medium ${statusClasses[newStatus] || 'bg-gray-100 text-gray-800'}`;
                    badge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                }
                document.getElementById('customer-status-modal').classList.add('hidden');
            } else {
                alert('Failed to update status: ' + (data.message || 'Unknown error'));
            }
        } catch (err) {
            alert('An error occurred while updating status');
        } finally {
            submitBtn.disabled = false;
            document.getElementById('status-modal-idle').classList.remove('hidden');
            document.getElementById('status-modal-busy').classList.add('hidden');
        }
    });

    function confirmBan(customerId, customerName) {
        if (!confirm(`Are you sure you want to ban ${customerName}? This will prevent them from logging in.`)) return;

        fetch(`/admin/customers/${customerId}/ban`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) window.location.reload();
            else alert('Failed to ban customer: ' + (data.message || 'Unknown error'));
        })
        .catch(() => alert('An error occurred while banning customer'));
    }
    </script>
@endsection
