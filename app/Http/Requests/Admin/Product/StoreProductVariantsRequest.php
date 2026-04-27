<?php

namespace App\Http\Requests\Admin\Product;
use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductVariantsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'variants' => 'required|array|min:1',
            'variants.*.color_id' => 'required|exists:colors,id',
            'variants.*.size_id' => 'required|exists:product_sizes,id',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.discount_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.stock_alert' => 'nullable|integer|min:0',
            'variants.*.sku' => 'required|string|unique:product_variants,sku',
            'variants.*.status' => 'required|in:active,inactive'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'variants.required' => 'At least one variant is required.',
            'variants.min' => 'At least one variant is required.',
            'variants.*.color_id.required' => 'Color is required for all variants.',
            'variants.*.size_id.required' => 'Size is required for all variants.',
            'variants.*.price.required' => 'Price is required for all variants.',
            'variants.*.stock_quantity.required' => 'Stock quantity is required for all variants.',
            'variants.*.sku.required' => 'SKU is required for all variants.',
            'variants.*.sku.unique' => 'The SKU has already been taken.',
        ];
    }
}
