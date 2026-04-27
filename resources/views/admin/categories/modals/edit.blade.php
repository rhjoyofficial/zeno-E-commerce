<!-- Edit Modal -->
<div id="category-edit-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white w-full max-w-md mx-4">
        <form id="category-edit-form" enctype="multipart/form-data" class="p-6">
            <input type="hidden" id="category-edit-id">
            <div class="flex justify-between items-center border-b pb-4">
                <h3 class="text-lg font-semibold">Edit Category</h3>
                <button class="text-2xl" id="category-edit-close" type="button">×</button>
            </div>

            <div class="mt-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Category Name</label>
                    <input id="category-edit-name" type="text" required class="w-full px-3 py-2 border">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Parent Category</label>
                    <select id="category-edit-parent" class="w-full px-3 py-2 border">
                        <option value="">None</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <select id="category-edit-status" class="w-full px-3 py-2 border">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Current Logo</label>
                    <img id="category-edit-logo-preview" src="" class="h-24 w-24 border border-gray-200 mb-2" alt="Category logo">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Update Logo (Optional)</label>
                    <input id="category-edit-logo" type="file" class="w-full" accept="image/*">
                </div>

                <p id="category-edit-error" class="hidden text-sm text-red-600"></p>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button id="category-edit-cancel" type="button" class="px-4 py-2 border">Close</button>
                <button id="category-edit-submit" type="submit" class="px-4 py-2 bg-black text-white disabled:opacity-50">
                    <span id="category-edit-idle">Update Category</span>
                    <span id="category-edit-busy" class="hidden">Processing...</span>
                </button>
            </div>
        </form>
    </div>
</div>
