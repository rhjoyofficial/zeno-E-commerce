<!-- Create Modal -->
<div id="category-create-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white w-full max-w-md mx-4">
        <form id="category-create-form" enctype="multipart/form-data" class="p-6">
            <div class="flex justify-between items-center border-b pb-4">
                <h3 class="text-lg font-semibold">Add New Category</h3>
                <button class="text-2xl" id="category-create-close" type="button">×</button>
            </div>

            <div class="mt-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Category Name</label>
                    <input id="category-create-name" type="text" required class="w-full px-3 py-2 border" placeholder="Enter category name">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Parent Category</label>
                    <select id="category-create-parent" class="w-full px-3 py-2 border">
                        <option value="">None</option>
                        @if(!empty($parentCategories))
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->category_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <select id="category-create-status" class="w-full px-3 py-2 border">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Category Logo</label>
                    <input id="category-create-logo" type="file" class="w-full" accept="image/*" required>
                </div>

                <p id="category-create-error" class="hidden text-sm text-red-600"></p>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button id="category-create-cancel" type="button" class="px-4 py-2 border">Cancel</button>
                <button id="category-create-submit" type="submit" class="px-4 py-2 bg-black text-white disabled:opacity-50">
                    <span id="category-create-idle">Save Category</span>
                    <span id="category-create-busy" class="hidden">Processing...</span>
                </button>
            </div>
        </form>
    </div>
</div>
