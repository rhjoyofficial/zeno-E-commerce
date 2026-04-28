<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Code <span class="text-red-500">*</span></label>
    <input type="text" name="code" value="{{ old('code') }}"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase"
        placeholder="e.g. SAVE20" required>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
        <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500" required>
            <option value="percentage">Percentage (%)</option>
            <option value="fixed">Fixed Amount</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Value <span class="text-red-500">*</span></label>
        <input type="number" name="value" value="{{ old('value') }}" step="0.01" min="0"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500" required>
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Valid From <span class="text-red-500">*</span></label>
        <input type="date" name="valid_from" value="{{ old('valid_from') }}"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Valid To <span class="text-red-500">*</span></label>
        <input type="date" name="valid_to" value="{{ old('valid_to') }}"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500" required>
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Min Order Amount</label>
        <input type="number" name="min_order_amount" value="{{ old('min_order_amount') }}" step="0.01" min="0"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500"
            placeholder="Leave blank for no minimum">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Usage Limit</label>
        <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" min="1"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500"
            placeholder="Leave blank for unlimited">
    </div>
</div>

<div class="flex items-center gap-2">
    <input type="checkbox" name="is_active" id="{{ isset($editing) ? 'edit_is_active' : 'is_active' }}" value="1"
        class="h-4 w-4 text-indigo-600 border-gray-300 rounded"
        {{ old('is_active', true) ? 'checked' : '' }}>
    <label for="{{ isset($editing) ? 'edit_is_active' : 'is_active' }}" class="text-sm font-medium text-gray-700">Active</label>
</div>
