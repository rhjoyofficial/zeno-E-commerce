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
            ->with(['primaryImage', 'category'])
            ->latest()
            ->paginate(12);

        return view('frontend.product-list', [
            'products' => $this->formatForCard($products),
        ]);
    }

    public function show(Product $product)
    {
        $product->load(['images', 'category', 'activeVariants.color', 'activeVariants.size']);

        $related = Product::active()
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn($q) => $q->where('category_id', $product->category_id))
            ->with(['primaryImage', 'category'])
            ->limit(4)
            ->get();

        return view('frontend.product-details', [
            'product'  => $product,
            'products' => $this->formatForCard($related),
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

    /**
     * Transform a collection or paginator of Product models into the
     * flat array shape expected by the x-product-card component.
     */
    private function formatForCard($products): array
    {
        return collect($products)->map(fn($product) => [
            'id'            => $product->id,
            'image'         => $product->primaryImage?->image_path ?? 'images/products/default.jpg',
            'title'         => $product->title,
            'price'         => $product->price,
            'discountPrice' => $product->discount_price ?: null,
            'badge'         => $product->discount_price ? 'Sale' : 'New',
            'stock'         => $product->stock_quantity > 0,
            'categories'    => $product->category ? [$product->category->category_name] : [],
        ])->all();
    }
}
