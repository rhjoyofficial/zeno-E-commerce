<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Tag;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = Product::with(['category', 'brand', 'images' => function ($q) {
            $q->where('is_primary', true);
        }])->latest()->paginate(20);

        $categories = Category::active()->get();
        $brands     = Brand::active()->get();
        $tags       = Tag::all();

        return view('admin.products.index', compact('products', 'categories', 'brands', 'tags'));
    }

    public function create()
    {
        return view('admin.products.modals.create', [
            'categories' => Category::active()->hasParent()->get(),
            'brands' => Brand::active()->get(),
            'tags' => Tag::all(),
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $this->productService->createProduct(
                $request->validated(),
                $request->file('primary_image'),
                $request->file('additional_images')
            );

            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        return view('admin.products.modals.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.modals.edit', [
            'product' => $product,
            'categories' => Category::active()->get(),
            'brands' => Brand::active()->get(),
            'tags' => Tag::all(),
            'selectedTags' => $product->tags->pluck('id')->toArray()
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $this->productService->updateProduct(
                $product,
                $request->validated(),
                $request->file('primary_image'),
                $request->file('additional_images'),
                $request->input('existing_images', [])
            );

            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            $this->productService->deleteProduct($product);

            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Product $product)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive,discontinued',
        ]);

        try {
            $product->update(['status' => $validated['status']]);
            return response()->json([
                'success' => true,
                'message' => 'Product status updated to ' . $validated['status'] . '.',
                'status' => $validated['status']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'stock_quantity' => 'required|integer|min:0'
        ]);

        try {
            $product->update(['stock_quantity' => $validated['stock_quantity']]);
            return response()->json([
                'success' => true,
                'message' => 'Stock quantity updated to ' . $validated['stock_quantity'] . '.',
                'stock_quantity' => $validated['stock_quantity']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update stock: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkSku(Request $request)
    {
        $request->validate([
            'sku' => 'required|string|max:255',
        ]);

        $isAvailable = !Product::where('sku', $request->input('sku'))->exists();
        return response()->json([
            'isAvailable' => $isAvailable
        ]);
    }
}
