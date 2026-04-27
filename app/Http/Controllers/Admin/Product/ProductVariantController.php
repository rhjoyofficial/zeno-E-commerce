<?php

namespace App\Http\Controllers\Admin\Product;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Color;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreProductVariantsRequest;
use Illuminate\Support\Facades\Auth;

class ProductVariantController extends Controller
{
    public function index(Product $product)
    {
        if (!$product->has_variants) {
            abort(404, 'This product does not have variants enabled.');
        }

        $variants = $product->variants()->with(['color', 'size'])->get();
        $colors = Color::all();
        $sizes = ProductSize::all();

        return view('admin.products.variants.index', compact('product', 'variants', 'colors', 'sizes'));
    }

    public function create(Product $product)
    {
        $colors = Color::all();
        $sizes = ProductSize::all();

        return view('admin.products.variants.create', compact('product', 'colors', 'sizes'));
    }

    public function store(StoreProductVariantsRequest $request, Product $product)
    {
        $validated = $request->validated();
        $createdCount = 0;
        $errors = [];

        foreach ($validated['variants'] as $index => $variantData) {
            // Check if combination already exists
            $exists = $product->variants()
                ->where('color_id', $variantData['color_id'])
                ->where('size_id', $variantData['size_id'])
                ->exists();

            if ($exists) {
                $errors[] = "Variant #" . ($index + 1) . ": A variant with the same color and size already exists.";
                continue;
            }

            try {
                $product->variants()->create($variantData + ['created_by' => Auth::id()]);
                $createdCount++;
            } catch (\Exception $e) {
                $errors[] = "Variant #" . ($index + 1) . ": " . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['variants' => $errors]);
        }

        return redirect()->route('admin.products.variants.index', $product)
            ->with('success', $createdCount . ' variant(s) created successfully.');
    }

    public function edit(Product $product, ProductVariant $variant)
    {
        $colors = Color::all();
        $sizes = ProductSize::all();

        return view('admin.products.variants.edit', compact('product', 'variant', 'colors', 'sizes'));
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $validated = $request->validate([
            'color_id' => 'required|exists:colors,id',
            'size_id' => 'required|exists:product_sizes,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'stock_alert' => 'nullable|integer|min:0',
            'sku' => 'required|string|unique:product_variants,sku,' . $variant->id,
            'status' => 'required|in:active,inactive'
        ]);

        // Check if combination already exists (excluding current variant)
        $exists = $product->variants()
            ->where('color_id', $validated['color_id'])
            ->where('size_id', $validated['size_id'])
            ->where('id', '!=', $variant->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['variant' => 'A variant with the same color and size already exists.'])->withInput();
        }

        $variant->update($validated + ['updated_by' => Auth::id()]);

        return redirect()->route('admin.products.variants.index', $product)
            ->with('success', 'Variant updated successfully');
    }

    public function destroy(Product $product, ProductVariant $variant)
    {
        $variant->delete();

        return redirect()->route('admin.products.variants.index', $product)
            ->with('success', 'Variant deleted successfully');
    }

    protected function generateSku($title)
    {
        $prefix = strtoupper(substr(preg_replace('/[^a-z]/i', '', $title), 0, 3));
        $random = mt_rand(10000, 99999);
        return $prefix . '-' . $random;
    }

    public function checkSku(Request $request, Product $product)
    {
        $request->validate([
            'sku' => 'required|string|max:255',
        ]);

        $sku = $request->input('sku');
        $isAvailable = !ProductVariant::where('sku', $sku)->exists();

        return response()->json([
            'isAvailable' => $isAvailable,
            'productID' => $product->id,
            'sku' => $sku,
            'message' => $isAvailable ? 'SKU is available' : 'SKU is already taken'
        ]);
    }


    public function checkCombination(Request $request, Product $product)
    {
        $request->validate([
            'color_id' => 'required|exists:colors,id',
            'size_id' => 'required|exists:product_sizes,id',
        ]);

        $exists = $product->variants()
            ->where('color_id', $request->color_id)
            ->where('size_id', $request->size_id)
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function checkCombinationEdit(Request $request, Product $product, ProductVariant $variant)
    {
        $request->validate([
            'color_id' => 'required|exists:colors,id',
            'size_id' => 'required|exists:product_sizes,id',
        ]);

        $exists = $product->variants()
            ->where('color_id', $request->color_id)
            ->where('size_id', $request->size_id)
            ->where('id', '!=', $variant->id)
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function checkSkuEdit(Request $request, Product $product, ProductVariant $variant)
    {
        $request->validate([
            'sku' => 'required|string',
        ]);

        $exists = ProductVariant::where('sku', $request->sku)
            ->where('id', '!=', $variant->id)
            ->exists();

        return response()->json([
            'isAvailable' => !$exists,
            'message' => $exists ? 'This SKU is already in use by another variant.' : 'SKU is available.'
        ]);
    }
}
