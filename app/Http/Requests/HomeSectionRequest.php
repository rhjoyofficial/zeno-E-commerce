<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HomeSectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Return true to authorize the request
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $rules = [
            'type' => 'required|in:new_arrivals,fashion',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'category_id' => 'nullable|required_if:type,fashion|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'order' => 'integer|min:0',
        ];

        // Additional rules only when type = fashion
        if ($this->input('type') === 'fashion') {
            $rules['items'] = 'required|array|min:1';
            $rules['items.*.id'] = 'nullable|exists:home_section_items,id';
            $rules['items.*.title'] = 'required|string|max:255';
            $rules['items.*.subtitle'] = 'nullable|string|max:255';

            // For store → image required, for update → image optional
            if ($this->isMethod('post')) {
                $rules['items.*.image'] = 'required|image|mimes:jpg,png,jpeg|max:2048';
            } else {
                $rules['items.*.image'] = 'nullable|image|mimes:jpg,png,jpeg|max:2048';
            }
        }

        return $rules;
    }

    /**
     * Custom validation messages
     */
    public function messages()
    {
        return [
            'items.required' => 'At least one slider item is required for fashion sections.',
            'items.*.title.required' => 'Each slider item must have a title.',
            'items.*.image.required' => 'Each slider item must have an image.',
        ];
    }
}
