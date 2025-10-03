<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HomeSection;
use App\Models\HomeSectionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\HomeSectionRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeSectionController extends Controller
{
    public function index()
    {
        $homeSections = HomeSection::orderBy('order')->get();
        return view('admin.home-sections.index', compact('homeSections'));
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        $childCategories = Category::whereNotNull('parent_id')->get();

        return view('admin.home-sections.create', compact('parentCategories', 'childCategories'));
    }

    public function store(HomeSectionRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['created_by'] = Auth::id();

            // Handle banner image upload for fashion type
            if ($request->type === 'fashion' && $request->hasFile('banner_image')) {
                $data['banner_image'] = $request->file('banner_image')->store('home-sections', 'public');
            }

            $homeSection = HomeSection::create($data);

            // Create slider items for fashion type
            if ($request->type === 'fashion' && $request->has('items')) {
                foreach ($request->items as $itemData) {
                    if (isset($itemData['image'])) {
                        $itemData['image'] = $itemData['image']->store('home-section-items', 'public');
                    }
                    $itemData['home_section_id'] = $homeSection->id;
                    $itemData['created_by'] = Auth::id();

                    HomeSectionItem::create($itemData);
                }
            }

            DB::commit();

            return redirect()->route('admin.home-sections.index')
                ->with('success', 'Home section created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating home section: ' . $e->getMessage());
        }
    }

    public function edit(HomeSection $homeSection)
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        $childCategories = Category::whereNotNull('parent_id')->get();

        // Prepare existing items data for JavaScript
        $existingItems = [];
        if ($homeSection->type === 'fashion') {
            $existingItems = $homeSection->items->map(function ($item) {
                return [
                    'item_id' => $item->id,
                    'title' => $item->title,
                    'subtitle' => $item->subtitle,
                    'category_id' => $item->category_id,
                    'image_url' => $item->image ? asset('storage/' . $item->image) : null,
                    'status' => $item->status,
                    'order' => $item->order
                ];
            })->toArray();
        }

        return view('admin.home-sections.edit', compact('homeSection', 'parentCategories', 'childCategories', 'existingItems'));
    }

    public function update(HomeSectionRequest $request, HomeSection $homeSection)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['updated_by'] = Auth::id();

            // Handle banner image upload
            if ($request->hasFile('banner_image')) {
                // Delete old banner image if exists
                if ($homeSection->banner_image) {
                    Storage::disk('public')->delete($homeSection->banner_image);
                }
                $data['banner_image'] = $request->file('banner_image')->store('home-sections', 'public');
            } elseif ($request->type === 'new_arrivals' && $homeSection->banner_image) {
                // Remove banner image if switching from fashion to new arrivals
                Storage::disk('public')->delete($homeSection->banner_image);
                $data['banner_image'] = null;
            }

            $homeSection->update($data);

            // Handle slider items for fashion type
            if ($homeSection->type === 'fashion' && $request->has('items')) {
                $existingItemIds = [];

                foreach ($request->items as $itemData) {
                    $itemData['home_section_id'] = $homeSection->id;

                    // Check if this is an existing item (has item_id)
                    if (isset($itemData['item_id']) && $itemData['item_id']) {
                        $item = HomeSectionItem::where('home_section_id', $homeSection->id)
                            ->where('id', $itemData['item_id'])
                            ->first();

                        if ($item) {
                            // Handle image update
                            if (isset($itemData['image']) && $itemData['image'] instanceof \Illuminate\Http\UploadedFile) {
                                // Delete old image
                                if ($item->image) {
                                    Storage::disk('public')->delete($item->image);
                                }
                                $itemData['image'] = $itemData['image']->store('home-section-items', 'public');
                            } else {
                                // Keep existing image
                                $itemData['image'] = $item->image;
                            }

                            $itemData['updated_by'] = Auth::id();
                            $item->update($itemData);
                            $existingItemIds[] = $item->id;
                        }
                    } else {
                        // New item
                        if (isset($itemData['image'])) {
                            $itemData['image'] = $itemData['image']->store('home-section-items', 'public');
                        }
                        $itemData['created_by'] = Auth::id();

                        $newItem = HomeSectionItem::create($itemData);
                        $existingItemIds[] = $newItem->id;
                    }
                }

                // Delete items that were removed
                $itemsToDelete = HomeSectionItem::where('home_section_id', $homeSection->id)
                    ->whereNotIn('id', $existingItemIds)
                    ->get();

                foreach ($itemsToDelete as $itemToDelete) {
                    if ($itemToDelete->image) {
                        Storage::disk('public')->delete($itemToDelete->image);
                    }
                    $itemToDelete->delete();
                }
            } elseif ($homeSection->type === 'new_arrivals') {
                // Delete all items if switching from fashion to new arrivals
                $itemsToDelete = HomeSectionItem::where('home_section_id', $homeSection->id)->get();
                foreach ($itemsToDelete as $itemToDelete) {
                    if ($itemToDelete->image) {
                        Storage::disk('public')->delete($itemToDelete->image);
                    }
                    $itemToDelete->delete();
                }
            }

            DB::commit();

            return redirect()->route('admin.home-sections.index')
                ->with('success', 'Home section updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating home section: ' . $e->getMessage());
        }
    }


    public function destroy(HomeSection $homeSection)
    {
        if ($homeSection->banner_image) {
            Storage::delete('public/' . $homeSection->banner_image);
        }
        $homeSection->items()->delete();
        $homeSection->delete();
        return redirect()->route('admin.home-sections.index')->with('success', 'Section deleted.');
    }

    public function toggleStatus(HomeSection $homeSection)
    {
        try {
            $homeSection->update([
                'status' => $homeSection->status === 'active' ? 'inactive' : 'active'
            ]);

            return response()->json([
                'success' => true,
                'new_status' => $homeSection->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status'
            ], 500);
        }
    }
}
