<!-- Create Modal -->
<div id="brand-create-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white w-full max-w-md mx-4">
        <form id="brand-create-form" enctype="multipart/form-data" class="p-6">
            <div class="flex justify-between items-center border-b pb-4">
                <h3 class="text-lg font-semibold">Add New Brand</h3>
                <button class="text-2xl" id="brand-create-close" type="button">×</button>
            </div>

            <div class="mt-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Brand Name</label>
                    <input id="brand-create-name" type="text" required class="w-full px-3 py-2 border" placeholder="Enter brand name">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Brand Logo</label>
                    <input id="brand-create-logo" type="file" class="w-full" accept="image/*" required>
                </div>

                <p id="brand-create-error" class="hidden text-sm text-red-600"></p>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button id="brand-create-cancel" type="button" class="px-4 py-2 border">Cancel</button>
                <button id="brand-create-submit" type="submit" class="px-4 py-2 bg-black text-white disabled:opacity-50">
                    <span id="brand-create-idle">Save Brand</span>
                    <span id="brand-create-busy" class="hidden">Processing...</span>
                </button>
            </div>
        </form>
    </div>
</div>
