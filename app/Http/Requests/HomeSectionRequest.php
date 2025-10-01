<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HomeSectionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'type' => 'required|in:new_arrivals,fashion',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'order' => 'required|integer|min:0',
        ];

        if ($this->input('type') === 'fashion') {
            $rules['category_id'] = 'required|exists:categories,id';
            $rules['section_title'] = 'nullable|string|max:255';
            $rules['section_subtitle'] = 'nullable|string|max:255';
            $rules['banner_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120';

            // Validation for slider items
            $rules['items'] = 'required|array|min:1';
            $rules['items.*.title'] = 'required|string|max:255';
            $rules['items.*.subtitle'] = 'nullable|string|max:255';
            $rules['items.*.category_id'] = 'required|exists:categories,id';
            $rules['items.*.status'] = 'nullable|in:active,inactive';
            $rules['items.*.order'] = 'nullable|integer|min:0';

            // Different image rules for create vs update
            if ($this->isMethod('POST')) {
                // For create - image is required
                $rules['items.*.image'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
            } else {
                // For update - image is optional (can keep existing)
                $rules['items.*.image'] = 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048';
            }
        } else {
            // For new arrivals, hide banner image
            $rules['banner_image'] = 'nullable';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'category_id.required' => 'The parent category is required for fashion sections.',
            'items.required' => 'At least one slider item is required for fashion sections.',
            'items.*.title.required' => 'Each slider item must have a title.',
            'items.*.image.required' => 'Each slider item must have an image.',
            'items.*.category_id.required' => 'Each slider item must have a child category selected.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->input('type') === 'fashion') {
                $items = $this->input('items', []);
                $childCategoryIds = [];

                foreach ($items as $index => $item) {
                    $categoryId = $item['category_id'] ?? null;

                    if ($categoryId) {
                        if (in_array($categoryId, $childCategoryIds)) {
                            $validator->errors()->add(
                                "items.$index.category_id",
                                "The same child category cannot be selected multiple times."
                            );
                        } else {
                            $childCategoryIds[] = $categoryId;
                        }
                    }
                }
            }
        });
    }
}