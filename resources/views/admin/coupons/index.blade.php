@extends('layouts.admin')
@section('title', 'Coupons Management')
@section('content')

<div class="container mx-auto px-4 py-8">
    <div class="bg-white border border-gray-200 rounded-lg">
        <!-- Header -->
        <div class="bg-white p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Coupons Management</h1>
                    <p class="text-gray-600 mt-1">Create and manage discount coupons</p>
                </div>
                <button id="open-create-coupon"
                    class="px-5 py-2.5 bg-gray-900 text-white hover:bg-gray-800 transition-colors text-sm font-medium rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Coupon
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type / Value</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validity</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usage</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($coupons as $coupon)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono font-semibold text-gray-900">{{ $coupon->code }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            @if ($coupon->type === 'percentage')
                                {{ $coupon->value }}% off
                            @else
                                {{ config('app.currency_symbol') }}{{ number_format($coupon->value, 2) }} off
                            @endif
                            @if ($coupon->min_order_amount)
                                <span class="text-gray-400 text-xs block">Min order: {{ config('app.currency_symbol') }}{{ number_format($coupon->min_order_amount, 2) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $coupon->valid_from->format('d M Y') }} –<br>{{ $coupon->valid_to->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $coupon->used_count }}{{ $coupon->usage_limit ? ' / ' . $coupon->usage_limit : '' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $coupon->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm flex items-center gap-3">
                            <button data-edit-coupon="{{ $coupon->id }}"
                                data-coupon="{{ json_encode($coupon) }}"
                                class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</button>
                            <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}"
                                onsubmit="return confirm('Delete this coupon?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">No coupons found. Create your first coupon.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($coupons->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $coupons->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Create Modal -->
<div id="create-coupon-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4 max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Create Coupon</h2>
            <button id="close-create-coupon" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.coupons.store') }}" class="p-6 space-y-4">
            @csrf
            @include('admin.coupons.partials.form')
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" id="close-create-coupon-2"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800">Create Coupon</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="edit-coupon-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4 max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center p-6 border-b">
            <h2 class="text-lg font-semibold text-gray-900">Edit Coupon</h2>
            <button id="close-edit-coupon" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="edit-coupon-form" method="POST" action="" class="p-6 space-y-4">
            @csrf @method('PUT')
            @include('admin.coupons.partials.form', ['editing' => true])
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" id="close-edit-coupon-2"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800">Update Coupon</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const createModal = document.getElementById('create-coupon-modal');
    const editModal   = document.getElementById('edit-coupon-modal');
    const editForm    = document.getElementById('edit-coupon-form');

    document.getElementById('open-create-coupon').addEventListener('click', () => createModal.classList.remove('hidden'));
    ['close-create-coupon', 'close-create-coupon-2'].forEach(id =>
        document.getElementById(id).addEventListener('click', () => createModal.classList.add('hidden'))
    );
    ['close-edit-coupon', 'close-edit-coupon-2'].forEach(id =>
        document.getElementById(id).addEventListener('click', () => editModal.classList.add('hidden'))
    );

    document.querySelectorAll('[data-edit-coupon]').forEach(btn => {
        btn.addEventListener('click', () => {
            const coupon = JSON.parse(btn.dataset.coupon);
            const id     = btn.dataset.editCoupon;

            editForm.action = `/admin/coupons/${id}`;

            const fields = ['code', 'type', 'value', 'min_order_amount', 'valid_from', 'valid_to', 'usage_limit'];
            fields.forEach(f => {
                const el = editForm.querySelector(`[name="${f}"]`);
                if (!el) return;
                if (f === 'valid_from' || f === 'valid_to') {
                    el.value = coupon[f] ? coupon[f].substring(0, 10) : '';
                } else {
                    el.value = coupon[f] ?? '';
                }
            });

            const activeEl = editForm.querySelector('[name="is_active"]');
            if (activeEl) activeEl.checked = !!coupon.is_active;

            editModal.classList.remove('hidden');
        });
    });
})();
</script>
@endpush

@endsection
