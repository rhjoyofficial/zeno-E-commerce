<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Services\ImageService;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct(private ImageService $imageService) {}

    public function index()
    {
        $brands = Brand::latest()->paginate(20);
        return view('admin.brands.index', compact('brands'));
    }

    public function show($id)
    {
        $brand = Brand::with('products')->findOrFail($id);
        return view('admin.brands.brand-products', compact('brand'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|max:50',
            'brandImg'   => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        Brand::create([
            'brand_name'  => $request->brand_name,
            'brand_image' => $this->imageService->store($request->file('brandImg'), 'images/brands'),
        ]);

        return redirect()->back()->with('success', 'Brand created successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'brand_name' => 'required|string|max:50',
            'brandImg'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $brand = Brand::findOrFail($id);
        $data  = ['brand_name' => $request->brand_name];

        if ($request->hasFile('brandImg')) {
            $data['brand_image'] = $this->imageService->replace($brand->brand_image, $request->file('brandImg'), 'images/brands');
        }

        $brand->update($data);
        return redirect()->back()->with('success', 'Brand updated successfully!');
    }

    // Destroy - Delete brand
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        // Check if brand has products
        if ($brand->products()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete brand with associated products');
        }

        $brand->delete();
        return redirect()->back()->with('success', 'Brand deleted successfully');
    }

    public function updateStatus(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $brand->update(['status' => $validated['status']]);
            return response()->json([
                'success' => true,
                'message' => 'Brand status updated to ' . $validated['status'] . '.',
                'status' => $validated['status']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }
}
