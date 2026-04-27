<!-- Edit Modal -->
<div id="brand-edit-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white w-full max-w-md mx-4">
        <form id="brand-edit-form" enctype="multipart/form-data" class="p-6">
            <input type="hidden" id="brand-edit-id">
            <div class="flex justify-between items-center border-b pb-4">
                <h3 class="text-lg font-semibold">Edit Brand</h3>
                <button class="text-2xl" id="brand-edit-close" type="button">×</button>
            </div>

            <div class="mt-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Brand Name</label>
                    <input id="brand-edit-name" type="text" required class="w-full px-3 py-2 border">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Current Logo</label>
                    <img id="brand-edit-logo-preview" src="" class="h-24 w-24 border border-gray-200 mb-2" alt="Brand logo">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Update Logo (Optional)</label>
                    <input id="brand-edit-logo" type="file" class="w-full" accept="image/*">
                </div>

                <p id="brand-edit-error" class="hidden text-sm text-red-600"></p>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button id="brand-edit-cancel" type="button" class="px-4 py-2 border">Close</button>
                <button id="brand-edit-submit" type="submit" class="px-4 py-2 bg-black text-white disabled:opacity-50">
                    <span id="brand-edit-idle">Update Brand</span>
                    <span id="brand-edit-busy" class="hidden">Processing...</span>
                </button>
            </div>
        </form>
    </div>
</div>
