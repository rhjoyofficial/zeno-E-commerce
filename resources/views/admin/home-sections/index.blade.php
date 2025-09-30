@extends('layouts.admin')
@section('title', 'Home Section Management')
@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white border border-gray-200">
        <!-- Page Header -->
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Home Sections</h2>
            </div>
            <div>
                <a href="{{ route('admin.home-sections.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-black text-white hover:bg-gray-800 transition-colors text-sm">
                    Create New Section
                </a>
            </div>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                            Title
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                            Order
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($homeSections as $section)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ ucfirst(str_replace('_', ' ', $section->type)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $section->title }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button type="button" onclick="toggleStatus({{ $section->id }})" class="status-toggle px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full transition-colors cursor-pointer
                                           @if($section->status == 'active') 
                                               bg-green-100 text-green-800 hover:bg-green-200 
                                           @else 
                                               bg-red-100 text-red-800 hover:bg-red-200 
                                           @endif" data-section-id="{{ $section->id }}"
                                data-current-status="{{ $section->status }}">
                                {{ ucfirst($section->status) }}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $section->order }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-3">
                            <!-- Edit link -->
                            <a href="{{ route('admin.home-sections.edit', $section) }}"
                                class="text-black hover:text-blue-900" aria-label="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            <!-- Delete form button -->
                            <form action="{{ route('admin.home-sections.destroy', $section) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" aria-label="Delete"
                                    onclick="return confirm('Are you sure you want to delete this section?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleStatus(sectionId) {
       const button = document.querySelector(`button[data-section-id="${sectionId}"]`);
       const currentStatus = button.getAttribute('data-current-status');
       const newStatus = currentStatus === 'active' ? 'inactive' : 'active';

       // Show loading state
       const originalText = button.textContent;
       button.disabled = true;
       button.innerHTML = '<svg class="animate-spin h-3 w-3 mx-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

       // Make AJAX request
       fetch(`/admin/home-sections/${sectionId}/toggle-status`, {
           method: 'PATCH',
           headers: {
               'Content-Type': 'application/json',
               'X-CSRF-TOKEN': '{{ csrf_token() }}',
               'Accept': 'application/json'
           }
       })
       .then(response => response.json())
       .then(data => {
           if (data.success) {
               // Update button appearance and data
               button.setAttribute('data-current-status', newStatus);

               if (newStatus === 'active') {
                   button.className = 'status-toggle px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full transition-colors cursor-pointer bg-green-100 text-green-800 hover:bg-green-200';
                   button.textContent = 'Active';
               } else {
                   button.className = 'status-toggle px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full transition-colors cursor-pointer bg-red-100 text-red-800 hover:bg-red-200';
                   button.textContent = 'Inactive';
               }

               // Show success notification
               NotificationSystem.show({
                   type: 'success',
                   title: 'Status Updated',
                   message: `Section status changed to ${newStatus}.`,
                   duration: 5000
               });
           } else {
               throw new Error(data.message || 'Failed to update status');
           }
       })
       .catch(error => {
           console.error('Error:', error);
           button.textContent = originalText;
           NotificationSystem.show({
               type: 'error',
               title: 'Error',
               message: error.message || 'Failed to update status.',
               duration: 5000
           });
       })
       .finally(() => {
           button.disabled = false;
       });
   }
</script>
@endpush