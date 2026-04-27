@extends('layouts.admin')
@section('title', 'Category Management')
@section('content')

<div class="container mx-auto px-4 py-8">

    <div class="bg-white border border-gray-200">
        <!-- Header Section -->
        <div class="bg-white p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Category Management</h1>
                    <p class="text-gray-600 mt-1">Manage your product categories</p>
                </div>
                <button id="category-open-create"
                    class="px-5 py-2.5 bg-gray-900 text-white hover:bg-gray-800 transition-colors text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Category
                </button>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr class="border-b border-gray-200">
                        <th class="w-1/4 px-6 py-4 text-left text-sm font-medium text-gray-700 uppercase tracking-wider">Logo</th>
                        <th class="w-1/4 px-6 py-4 text-left text-sm font-medium text-gray-700 uppercase tracking-wider">Name</th>
                        <th class="w-1/4 px-6 py-4 text-left text-sm font-medium text-gray-700 uppercase tracking-wider">Parent</th>
                        <th class="w-1/4 px-6 py-4 text-left text-sm font-medium text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="w-1/4 px-6 py-4 text-left text-sm font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($categories as $category)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="{{ asset('storage/' . $category->category_image) }}"
                                class="h-12 w-12 object-cover border border-gray-200"
                                alt="{{ $category->category_name }}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-base font-medium text-gray-900">{{ $category->category_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-base text-gray-700">
                                {{ $category->parent ? $category->parent->category_name : '—' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select
                                class="px-5 py-1.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-gray-300 border border-gray-300
                                    {{ $category->status == 'active' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-700' }}"
                                onchange="updateStatus(this.value, {{ $category->id }})">
                                <option value="active" {{ $category->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $category->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-2">
                                <button
                                    onclick="openCategoryEditModal({{ $category->id }}, '{{ addslashes($category->category_name) }}', '{{ asset('storage/' . $category->category_image) }}', {{ $category->parent_id ?? 'null' }}, '{{ $category->status }}')"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </button>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-colors"
                                        onclick="return confirm('Are you sure you want to delete this category?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none"
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

        @if ($categories->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $categories->links() }}
        </div>
        @endif
    </div>

    @include('admin.categories.modals.create')
    @include('admin.categories.modals.edit')
</div>

<script>
const categoryStoreURL = @json(route('admin.categories.store'));

(function () {
    const createModal = document.getElementById('category-create-modal');
    const editModal   = document.getElementById('category-edit-modal');
    const $ = (id) => document.getElementById(id);

    $('category-open-create').addEventListener('click', () => createModal.classList.remove('hidden'));
    $('category-create-close').addEventListener('click', () => createModal.classList.add('hidden'));
    $('category-create-cancel').addEventListener('click', () => createModal.classList.add('hidden'));

    $('category-edit-close').addEventListener('click', () => editModal.classList.add('hidden'));
    $('category-edit-cancel').addEventListener('click', () => editModal.classList.add('hidden'));

    [createModal, editModal].forEach(modal => {
        modal.addEventListener('click', (e) => { if (e.target === modal) modal.classList.add('hidden'); });
    });

    $('category-create-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn = $('category-create-submit');
        const errorEl  = $('category-create-error');

        submitBtn.disabled = true;
        $('category-create-idle').classList.add('hidden');
        $('category-create-busy').classList.remove('hidden');
        errorEl.classList.add('hidden');

        try {
            const formData = new FormData();
            formData.append('category_name', $('category-create-name').value);
            formData.append('category_image', $('category-create-logo').files[0]);
            formData.append('parent_id', $('category-create-parent').value || '');
            formData.append('status', $('category-create-status').value);

            const res = await fetch(categoryStoreURL, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: formData
            });

            if (!res.ok) throw new Error((await res.json()).message || 'Failed to create category');
            window.location.reload();
        } catch (err) {
            errorEl.textContent = err.message;
            errorEl.classList.remove('hidden');
            submitBtn.disabled = false;
            $('category-create-idle').classList.remove('hidden');
            $('category-create-busy').classList.add('hidden');
        }
    });

    $('category-edit-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn = $('category-edit-submit');
        const errorEl  = $('category-edit-error');
        const id       = $('category-edit-id').value;

        submitBtn.disabled = true;
        $('category-edit-idle').classList.add('hidden');
        $('category-edit-busy').classList.remove('hidden');
        errorEl.classList.add('hidden');

        try {
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('category_name', $('category-edit-name').value);
            formData.append('parent_id', $('category-edit-parent').value || '');
            formData.append('status', $('category-edit-status').value);
            const logoFile = $('category-edit-logo').files[0];
            if (logoFile) formData.append('category_image', logoFile);

            const res = await fetch(`/admin/categories/${id}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: formData
            });

            if (!res.ok) throw new Error((await res.json()).message || 'Failed to update category');
            window.location.reload();
        } catch (err) {
            errorEl.textContent = err.message;
            errorEl.classList.remove('hidden');
            submitBtn.disabled = false;
            $('category-edit-idle').classList.remove('hidden');
            $('category-edit-busy').classList.add('hidden');
        }
    });
})();

function openCategoryEditModal(id, name, logo, parentId, status) {
    document.getElementById('category-edit-id').value = id;
    document.getElementById('category-edit-name').value = name;
    document.getElementById('category-edit-logo-preview').src = logo;
    document.getElementById('category-edit-logo').value = '';
    document.getElementById('category-edit-error').classList.add('hidden');

    const parentSelect = document.getElementById('category-edit-parent');
    parentSelect.value = parentId !== null ? parentId : '';

    const statusSelect = document.getElementById('category-edit-status');
    statusSelect.value = status;

    document.getElementById('category-edit-modal').classList.remove('hidden');
}

function updateStatus(newStatus, categoryId) {
    fetch(`/admin/categories/${categoryId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) alert('Failed to update status.');
    })
    .catch(() => alert('Something went wrong.'));
}
</script>
@endsection
