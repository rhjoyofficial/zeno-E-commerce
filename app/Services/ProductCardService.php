<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class ProductCardService
{
    public function format($products): array
    {
        return collect($products)->map(fn($product) => [
            'id'            => $product->id,
            'image'         => $product->primaryImage?->image_path ?? 'images/products/default.jpg',
            'title'         => $product->title,
            'description'   => $product->short_description ?? '',
            'price'         => $product->price,
            'discountPrice' => $product->discount_price ?: null,
            'badge'         => $product->discount_price ? 'Sale' : 'New',
            'stock'         => $product->stock_quantity > 0,
            'hasVariants'   => (bool) $product->has_variants,
            'categories'    => $product->category ? [$product->category->category_name] : [],
            'images'        => ($product->images ?? collect())->map(fn($img) => [
                'path'       => Storage::url($img->image_path),
                'is_primary' => (bool) $img->is_primary,
            ])->values()->all(),
            'variants'      => $product->has_variants
                ? ($product->activeVariants ?? collect())->map(fn($v) => [
                    'id'             => $v->id,
                    'color_id'       => $v->color_id,
                    'color_name'     => $v->color?->name,
                    'color_hex'      => $v->color?->hex_code,
                    'size_id'        => $v->size_id,
                    'size_name'      => $v->size?->name,
                    'price'          => $v->price,
                    'stock_quantity' => $v->stock_quantity,
                ])->values()->all()
                : [],
        ])->all();
    }
}
