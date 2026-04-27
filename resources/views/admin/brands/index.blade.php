@extends('layouts.admin')
@section('title', 'Brands Management')
@section('content')

<div class="container mx-auto px-4 py-8">
    <div class="bg-white border border-gray-200 rounded-lg">
        <!-- Header Section -->
        <div class="bg-white p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Brands Management</h1>
                    <p class="text-gray-600 mt-1">Manage your product brands</p>
                </div>
                <button id="brand-open-create"
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
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logo</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($brands as $brand)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="{{ asset('storage/' . $brand->brand_image) }}"
                                class="h-12 w-12 object-cover rounded-lg border border-gray-200 shadow-sm"
                                alt="{{ $brand->brand_name }}"
                                onerror="this.src='https://via.placeholder.com/48?text=LOGO'">
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $brand->brand_name }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <select class="status-dropdown w-full px-3 py-2 text-sm border rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1
                                @if ($brand->status == 'active') border-green-200 bg-green-50 text-green-700 focus:ring-green-500
                                @else border-red-200 bg-red-50 text-red-700 focus:ring-red-500 @endif"
                                onchange="updateBrandStatus(this.value, {{ $brand->id }})"
                                data-brand-id="{{ $brand->id }}" data-current-status="{{ $brand->status }}">
                                <option value="active" {{ $brand->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $brand->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <button
                                    onclick="openBrandEditModal({{ $brand->id }}, '{{ addslashes($brand->brand_name) }}', '{{ asset('storage/' . $brand->brand_image) }}')"
                                    class="px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors rounded-md flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </button>
                                <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" class="inline">
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
(function () {
    const createModal = document.getElementById('brand-create-modal');
    const editModal   = document.getElementById('brand-edit-modal');

    const $ = (id) => document.getElementById(id);

    // Open / close create modal
    $('brand-open-create').addEventListener('click', () => createModal.classList.remove('hidden'));
    $('brand-create-close').addEventListener('click', () => createModal.classList.add('hidden'));
    $('brand-create-cancel').addEventListener('click', () => createModal.classList.add('hidden'));

    // Open / close edit modal
    $('brand-edit-close').addEventListener('click', () => editModal.classList.add('hidden'));
    $('brand-edit-cancel').addEventListener('click', () => editModal.classList.add('hidden'));

    // Close on backdrop click
    [createModal, editModal].forEach(modal => {
        modal.addEventListener('click', (e) => { if (e.target === modal) modal.classList.add('hidden'); });
    });

    // Create form submit
    $('brand-create-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn  = $('brand-create-submit');
        const idleSpan   = $('brand-create-idle');
        const busySpan   = $('brand-create-busy');
        const errorEl    = $('brand-create-error');
        const nameInput  = $('brand-create-name');
        const logoInput  = $('brand-create-logo');

        submitBtn.disabled = true;
        idleSpan.classList.add('hidden');
        busySpan.classList.remove('hidden');
        errorEl.classList.add('hidden');

        try {
            const formData = new FormData();
            formData.append('brand_name', nameInput.value);
            formData.append('brandImg', logoInput.files[0]);
            formData.append('status', 'active');

            const res = await fetch('{{ route('admin.brands.store') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: formData
            });

            if (!res.ok) throw new Error((await res.json()).message || 'Failed to create brand');
            window.location.reload();
        } catch (err) {
            errorEl.textContent = err.message;
            errorEl.classList.remove('hidden');
            submitBtn.disabled = false;
            idleSpan.classList.remove('hidden');
            busySpan.classList.add('hidden');
        }
    });

    // Edit form submit
    $('brand-edit-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn  = $('brand-edit-submit');
        const idleSpan   = $('brand-edit-idle');
        const busySpan   = $('brand-edit-busy');
        const errorEl    = $('brand-edit-error');
        const id         = $('brand-edit-id').value;
        const nameInput  = $('brand-edit-name');
        const logoInput  = $('brand-edit-logo');

        submitBtn.disabled = true;
        idleSpan.classList.add('hidden');
        busySpan.classList.remove('hidden');
        errorEl.classList.add('hidden');

        try {
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('brand_name', nameInput.value);
            formData.append('status', 'active');
            if (logoInput.files[0]) formData.append('brandImg', logoInput.files[0]);

            const res = await fetch(`/admin/brands/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: formData
            });

            if (!res.ok) throw new Error((await res.json()).message || 'Failed to update brand');
            window.location.reload();
        } catch (err) {
            errorEl.textContent = err.message;
            errorEl.classList.remove('hidden');
            submitBtn.disabled = false;
            idleSpan.classList.remove('hidden');
            busySpan.classList.add('hidden');
        }
    });
})();

function openBrandEditModal(id, name, logo) {
    document.getElementById('brand-edit-id').value = id;
    document.getElementById('brand-edit-name').value = name;
    document.getElementById('brand-edit-logo-preview').src = logo;
    document.getElementById('brand-edit-logo').value = '';
    document.getElementById('brand-edit-error').classList.add('hidden');
    document.getElementById('brand-edit-modal').classList.remove('hidden');
}

async function updateBrandStatus(newStatus, brandId) {
    const select = document.querySelector(`select[data-brand-id="${brandId}"]`);
    const originalStatus = select.getAttribute('data-current-status');

    select.disabled = true;
    select.classList.add('opacity-50', 'cursor-not-allowed');

    try {
        const res = await fetch(`/admin/brands/${brandId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        });

        if (!res.ok) throw new Error('Failed to update status');

        const data = await res.json();
        if (data.success) {
            select.className = 'status-dropdown w-full px-3 py-2 text-sm border rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1';
            select.classList.add(
                ...(newStatus === 'active'
                    ? ['border-green-200', 'bg-green-50', 'text-green-700', 'focus:ring-green-500']
                    : ['border-red-200', 'bg-red-50', 'text-red-700', 'focus:ring-red-500'])
            );
            select.setAttribute('data-current-status', newStatus);
        } else {
            throw new Error(data.message || 'Failed to update status');
        }
    } catch (err) {
        select.value = originalStatus;
        alert(err.message);
    } finally {
        select.disabled = false;
        select.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}
</script>
@endsection
