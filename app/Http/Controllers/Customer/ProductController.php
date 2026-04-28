<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductCardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct(private ProductCardService $productCardService) {}

    public function index(Request $request)
    {
        $topCategories  = Category::whereNull('parent_id')->active()->get();
        $activeCategory = null;

        $query = Product::active()
            ->with(['primaryImage', 'images', 'category', 'activeVariants.color', 'activeVariants.size'])
            ->latest();

        if ($request->filled('category')) {
            $activeCategory = Category::find((int) $request->category);

            if ($activeCategory) {
                $ids = $activeCategory->getDescendantIds();
                $query->whereIn('category_id', $ids);
            }
        }

        $products = $query->paginate(12)->withQueryString();

        $products->setCollection(
            collect($this->productCardService->format($products->items()))
        );

        return view('frontend.product-list', [
            'products'       => $products,
            'topCategories'  => $topCategories,
            'activeCategory' => $activeCategory,
        ]);
    }

    public function show(Product $product)
    {
        $product->load(['images', 'category', 'details', 'activeVariants.color', 'activeVariants.size']);

        // Build image list for the slider
        $images = $product->images->map(fn($img) => [
            'url'        => Storage::url($img->image_path),
            'is_primary' => (bool) $img->is_primary,
            'variant_id' => $img->variant_id,
        ])->sortByDesc('is_primary')->values()->all();

        if (empty($images)) {
            $images = [['url' => asset('images/products/default.jpg'), 'is_primary' => true, 'variant_id' => null]];
        }

        // Unique colors and sizes from active variants
        $variantColors = $product->activeVariants
            ->filter(fn($v) => $v->color)
            ->unique('color_id')
            ->map(fn($v) => ['id' => $v->color_id, 'name' => $v->color->name, 'hex' => $v->color->hex_code])
            ->values()->all();

        $variantSizes = $product->activeVariants
            ->filter(fn($v) => $v->size)
            ->unique('size_id')
            ->map(fn($v) => ['id' => $v->size_id, 'name' => $v->size->name])
            ->values()->all();

        // Full variant map for JS matching
        $variantsForJs = $product->activeVariants->map(fn($v) => [
            'id'             => $v->id,
            'color_id'       => $v->color_id,
            'size_id'        => $v->size_id,
            'price'          => (float) $v->price,
            'final_price'    => (float) $v->final_price,
            'stock_quantity' => $v->stock_quantity,
            'sku'            => $v->sku,
        ])->values()->all();

        $related = Product::active()
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn($q) => $q->where('category_id', $product->category_id))
            ->with(['primaryImage', 'category', 'activeVariants.color', 'activeVariants.size'])
            ->limit(4)
            ->get();

        return view('frontend.product-details', [
            'product'       => $product,
            'images'        => $images,
            'variantColors' => $variantColors,
            'variantSizes'  => $variantSizes,
            'variantsForJs' => $variantsForJs,
            'products'      => $this->productCardService->format($related),
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

}
