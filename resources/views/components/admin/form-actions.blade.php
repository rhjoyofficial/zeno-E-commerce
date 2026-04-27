@props([
    'cancelRoute' => '#',
    'submitText' => 'Save',
    'confirmationTitle' => 'Confirm Submission',
    'confirmationMessage' => 'Are you sure you want to submit this form? These changes will be saved immediately.'
])

<div class="pt-8" data-form-tracker>
    <div class="flex justify-end space-x-3">
        <button type="button" id="cancelBtn" data-cancel-btn data-cancel-route="{{ $cancelRoute }}"
            class="border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Cancel
        </button>
        <button type="button" id="submitBtn" data-submit-btn
            class="inline-flex justify-center border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            {{ $submitText }}
        </button>
    </div>

    <!-- Confirmation Modal -->
    <div data-confirm-modal
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $confirmationTitle }}</h3>
            <p class="text-sm text-gray-500 mb-6">{{ $confirmationMessage }}</p>
            <div class="flex justify-end space-x-3">
                <button type="button" data-confirm-cancel
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>
