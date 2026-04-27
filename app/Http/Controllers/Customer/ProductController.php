<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::active()
            ->with(['primaryImage', 'images', 'category', 'activeVariants.color', 'activeVariants.size'])
            ->latest()
            ->paginate(12);

        $products->setCollection(
            collect($this->formatForCard($products->items()))
        );

        return view('frontend.product-list', [
            'products' => $products,
        ]);
    }

    public function show(Product $product)
    {
        $product->load(['images', 'category', 'activeVariants.color', 'activeVariants.size']);

        $related = Product::active()
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn($q) => $q->where('category_id', $product->category_id))
            ->with(['primaryImage', 'images', 'category', 'activeVariants.color', 'activeVariants.size'])
            ->limit(4)
            ->get();

        $formattedRelated = $this->formatForCard($related);

        return view('frontend.product-list', [
            'product' => $product,
            'products' => $formattedRelated,
        ]);
    }

    public function getVariants(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);

        try {
            $productId = $request->input('product_id');

            $product = Product::with(['images'])
                ->byId($productId)
                ->firstOrFail();

            $response = [
                'id' => $product->id,
                'title' => $product->title,
                'price' => $product->price,
                'has_variants' => $product->has_variants,
                'short_description' => $product->short_description,
                'images' => $product->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'path' => Storage::url($image->image_path),
                        'is_primary' => $image->is_primary
                    ];
                }),
            ];

            if ($product->has_variants) {
                $product->load(['activeVariants.color', 'activeVariants.size']);

                $response['variants'] = $product->activeVariants->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'color_id' => $variant->color_id,
                        'color_name' => $variant->color ? $variant->color->name : null,
                        'color_hex' => $variant->color ? $variant->color->hex_code : null,
                        'size_id' => $variant->size_id,
                        'size_name' => $variant->size ? $variant->size->name : null,
                        'price' => $variant->price,
                        'stock_quantity' => $variant->stock_quantity,
                        'sku' => $variant->sku
                    ];
                });
            } else {
                $response['variants'] = [];
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Product not found',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    private function formatForCard($products): array
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
