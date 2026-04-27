<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageService;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct(private ImageService $imageService) {}

    public function index(Request $request)
    {
        $categories = Category::with('parent')
            ->when(request('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->when(request('search'), function ($query) {
                $query->where('category_name', 'like', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(20);

        // Get only parent categories for dropdowns
        $parentCategories = Category::whereNull('parent_id')
            ->when(request('status') === 'active', function ($query) {
                $query->where('status', 'active');
            })
            ->get();

        return view('admin.categories.index', [
            'categories' => $categories,
            'parentCategories' => $parentCategories
        ]);
    }

    public function show($id)
    {
        $category = Category::with('products')->findOrFail($id);
        return view('admin.categories.category-products', compact('category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name'  => 'required|string|max:50|unique:categories,category_name',
            'parent_id'      => 'nullable|integer|exists:categories,id',
            'status'         => 'required|in:active,inactive',
            'category_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        Category::create([
            'category_name'  => $request->category_name,
            'parent_id'      => $request->parent_id,
            'status'         => $request->status ?? 'active',
            'category_image' => $this->imageService->store($request->file('category_image'), 'images/categories'),
        ]);

        return redirect()->back()->with('success', 'Category created successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_name'  => 'required|string|max:50|unique:categories,category_name,' . $id,
            'parent_id'      => 'nullable|integer|exists:categories,id',
            'status'         => 'required|in:active,inactive',
            'category_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $category = Category::findOrFail($id);
        $data     = [
            'category_name' => $request->category_name,
            'parent_id'     => $request->parent_id,
            'status'        => $request->status ?? 'active',
        ];

        if ($request->hasFile('category_image')) {
            $data['category_image'] = $this->imageService->replace($category->category_image, $request->file('category_image'), 'images/categories');
        }

        $category->update($data);
        return redirect()->back()->with('success', 'Category updated successfully!');
    }
    // Update Category Status
    public function updateStatus(Request $request, Category $category)
    {
        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $category->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }


    // Destroy - Delete Category
    public function destroy($id)
    {
        $Category = Category::findOrFail($id);

        // Check if Category has products
        if ($Category->products()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete Category with associated products');
        }

        $Category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully');
    }
}
