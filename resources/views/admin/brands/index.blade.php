@extends('layouts.admin')
@section('title', 'Brands Management')
@section('content')

<div class="container mx-auto px-4 py-8" x-data="brandManagement()">
    <div class="bg-white border border-gray-200 rounded-lg">
        <!-- Header Section -->
        <div class="bg-white p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Brands Management</h1>
                    <p class="text-gray-600 mt-1">Manage your product brands</p>
                </div>
                <button @click="showCreateModal = true"
                    class="px-5 py-2.5 bg-gray-900 text-white hover:bg-gray-800 transition-colors text-sm font-medium rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Brand
                </button>
            </div>
        </div>

        <!-- Brands Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logo
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($brands as $brand)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <!-- Logo Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <img src="{{ asset('storage/' . $brand->brand_image) }}"
                                    class="h-12 w-12 object-cover rounded-lg border border-gray-200 shadow-sm"
                                    alt="{{ $brand->brand_name }}"
                                    onerror="this.src=`https://via.placeholder.com/48?text=LOGO`">
                            </div>
                        </td>

                        <!-- Name Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $brand->brand_name }}</div>
                        </td>

                        <!-- Status Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select class="status-dropdown w-full px-3 py-2 text-sm border rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1
                                @if ($brand->status == 'active')
                                    border-green-200 bg-green-50 text-green-700 focus:ring-green-500
                                @else
                                    border-red-200 bg-red-50 text-red-700 focus:ring-red-500
                                @endif" onchange="updateBrandStatus(this.value, {{ $brand->id }})"
                                data-brand-id="{{ $brand->id }}" data-current-status="{{ $brand->status }}">
                                <option value="active" {{ $brand->status == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ $brand->status == 'inactive' ? 'selected' : '' }}>
                                    Inactive</option>
                            </select>
                        </td>

                        <!-- Actions Column -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <button
                                    @click="openEditModal({{ $brand->id }}, '{{ $brand->brand_name }}', '{{ asset('storage/' . $brand->brand_image) }}', '{{ $brand->status }}')"
                                    class="px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors rounded-md flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </button>
                                <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST"
                                    class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-colors rounded-md flex items-center"
                                        onclick="return confirm('Are you sure you want to delete this brand?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($brands->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $brands->links() }}
        </div>
        @endif
    </div>

    @include('admin.brands.modals.create')
    @include('admin.brands.modals.edit')
</div>

<script>
    // Brand Status Update Function
async function updateBrandStatus(newStatus, brandId) {
    const select = document.querySelector(`select[data-brand-id="${brandId}"]`);
    const originalStatus = select.getAttribute('data-current-status');
    
    // Show loading state
    select.disabled = true;
    select.classList.add('opacity-50', 'cursor-not-allowed');

    try {
        const response = await fetch(`/admin/brands/${brandId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();
        
        if (data.success) {
            // Update styling
            select.className = 'status-dropdown w-full px-3 py-2 text-sm border rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1';
            
            if (newStatus === 'active') {
                select.classList.add('border-green-200', 'bg-green-50', 'text-green-700', 'focus:ring-green-500');
            } else {
                select.classList.add('border-red-200', 'bg-red-50', 'text-red-700', 'focus:ring-red-500');
            }

            // Update data attribute
            select.setAttribute('data-current-status', newStatus);

            // Show success message
            showToast(`Brand status updated to ${newStatus}`, 'success');
        } else {
            throw new Error(data.message || 'Failed to update status');
        }
    } catch (error) {
        // Revert to original status
        select.value = originalStatus;
        showToast(error.message || 'Failed to update status', 'error');
    } finally {
        select.disabled = false;
        select.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

// Simple toast notification
function showToast(message, type = 'info') {
    // Remove existing toast
    const existingToast = document.getElementById('status-toast');
    if (existingToast) existingToast.remove();
    
    const toast = document.createElement('div');
    toast.id = 'status-toast';
    toast.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg text-white font-medium text-sm z-50 transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Alpine.js for modal management
document.addEventListener('alpine:init', () => {
    Alpine.data('brandManagement', () => ({
        showCreateModal: false,
        showEditModal: false,
        isSubmitting: false,
        errorMessage: '',
        brandForm: {
            name: '',
            logo: null,
            status: 'active'
        },
        editForm: {
            id: null,
            name: '',
            currentLogo: '',
            newLogo: null,
            status: 'active'
        },

        openEditModal(id, name, logo, status) {
            this.editForm = {
                id,
                name,
                currentLogo: logo,
                newLogo: null,
                status
            };
            this.showEditModal = true;
        },

        async submitBrandForm() {
            this.isSubmitting = true;
            this.errorMessage = '';

            try {
                const formData = new FormData();
                formData.append('brand_name', this.brandForm.name);
                formData.append('brandImg', this.brandForm.logo);
                formData.append('status', this.brandForm.status);

                await axios.post('{{ route('admin.brands.store') }}', formData);
                window.location.reload();
            } catch (error) {
                this.handleError(error, 'Error creating brand');
            } finally {
                this.isSubmitting = false;
            }
        },

        async submitEditForm() {
            this.isSubmitting = true;
            this.errorMessage = '';

            try {
                const formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('brand_name', this.editForm.name);
                formData.append('status', this.editForm.status);
                if (this.editForm.newLogo) {
                    formData.append('brandImg', this.editForm.newLogo);
                }

                await axios.post(`/admin/brands/${this.editForm.id}`, formData);
                window.location.reload();
            } catch (error) {
                this.handleError(error, 'Error updating brand');
            } finally {
                this.isSubmitting = false;
            }
        },

        handleError(error, defaultMessage) {
            let errorMessage = defaultMessage;

            if (error.response) {
                if (error.response.data && error.response.data.message) {
                    errorMessage = error.response.data.message;
                } else if (error.response.status === 422) {
                    errorMessage = 'Validation error: ' +
                        Object.values(error.response.data.errors).flat().join(', ');
                }
            } else if (error.request) {
                errorMessage = 'No response received from server';
            }

            this.errorMessage = errorMessage;
        }
    }));
});
</script>
@endsection