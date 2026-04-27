<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:200',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|boolean',
            'discount_price' => 'nullable|required_if:discount,true|numeric|min:0|lt:price',
            'is_new_arrival' => 'nullable|boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'stock_alert' => 'nullable|integer|min:0|lte:stock_quantity',
            'status' => 'required|in:active,inactive,discontinued',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'has_variants' => 'nullable|in:0,1',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'sku' => 'required|string|max:255|unique:products,sku',
            'primary_image' => 'required|image|max:2048',
            'additional_images' => 'nullable|array',
            'additional_images.*' => 'image|max:2048',
        ];
    }

    public function messages()
    {
        return [
            // Title
            'title.required' => 'The product title is required.',
            'title.max' => 'The product title cannot be more than :max characters.',

            // Short Description
            'short_description.max' => 'The short description cannot be more than :max characters.',

            // Price
            'price.required' => 'The product price is required.',
            'price.numeric' => 'The product price must be a number.',
            'price.min' => 'The product price must be a positive value.',

            // Discount Price
            'discount_price.required_if' => 'The discount price is required when the product is on sale.',
            'discount_price.numeric' => 'The discount price must be a number.',
            'discount_price.min' => 'The discount price must be a positive value.',
            'discount_price.lt' => 'The discount price must be less than the regular price.',

            // Stock Quantity
            'stock_quantity.integer' => 'The stock quantity must be a whole number.',
            'stock_quantity.min' => 'The stock quantity must be zero or more.',

            // Stock Alert
            'stock_alert.integer' => 'The stock alert must be a whole number.',
            'stock_alert.min' => 'The stock alert must be zero or more.',
            'stock_alert.lte' => 'The stock alert cannot be greater than the stock quantity.',

            // Status
            'status.required' => 'The product status is required.',
            'status.in' => 'The selected status is invalid.',

            // Category & Brand
            'category_id.required' => 'A product category is required.',
            'category_id.exists' => 'The selected category does not exist.',
            'brand_id.exists' => 'The selected brand does not exist.',

            // Variants
            'has_variants.in' => 'The value for "has variants" is invalid.',

            // Tags
            'tags.array' => 'The tags must be a list of tags.',
            'tags.*.exists' => 'One or more selected tags do not exist.',

            // SKU
            'sku.required' => 'The product SKU is required.',
            'sku.string' => 'The product SKU must be a string.',
            'sku.max' => 'The product SKU cannot be more than :max characters.',
            'sku.unique' => 'The product SKU must be unique.',

            // Images
            'primary_image.required' => 'A primary image is required.',
            'primary_image.image' => 'The primary image must be an image file.',
            'primary_image.max' => 'The primary image size cannot exceed 2MB.',
            'additional_images.array' => 'Additional images must be a list of images.',
            'additional_images.*.image' => 'One or more of the additional files is not an image.',
            'additional_images.*.max' => 'One or more additional images exceeds the 2MB file size limit.',
        ];
    }
}
