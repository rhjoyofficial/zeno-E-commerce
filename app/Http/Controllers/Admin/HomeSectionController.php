<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HomeSection;
use App\Models\HomeSectionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\HomeSectionRequest;

class HomeSectionController extends Controller
{
    public function index()
    {
        $homeSections = HomeSection::orderBy('order')->get();
        return view('admin.home-sections.index', compact('homeSections'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->get();
        // dd($categories);
        return view('admin.home-sections.create', compact('categories'));
    }

    public function store(HomeSectionRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('home_sections', 'public');
        }

        $section = HomeSection::create($validated);

        // Handle slider items if fashion
        if ($request->type === 'fashion') {
            foreach ($request->input('items', []) as $index => $itemData) {
                $imagePath = $request->file("items.$index.image")->store('home_section_items', 'public');
                HomeSectionItem::create([
                    'home_section_id' => $section->id,
                    'title' => $itemData['title'],
                    'subtitle' => $itemData['subtitle'] ?? null,
                    'image' => $imagePath,
                ]);
            }
        }

        return redirect()->route('admin.home-sections.index')->with('success', 'Section created.');
    }

    public function edit(HomeSection $homeSection)
    {
        $categories = Category::whereNull('parent_id')->get();
        $itemCount = $homeSection->items->count();
        return view('admin.home-sections.edit', compact('homeSection', 'categories', 'itemCount'));
    }

    public function update(HomeSectionRequest $request, HomeSection $homeSection)
    {
        $validated = $request->validated();
        // dd($validated);
        // Handle banner image replacement
        if ($request->hasFile('banner_image')) {
            if ($homeSection->banner_image) {
                Storage::delete('public/' . $homeSection->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('home_sections', 'public');
        }

        $homeSection->update($validated);

        // Handle slider items if fashion
        if ($homeSection->type === 'fashion') {
            $submittedItems = $request->input('items', []);
            $existingItems = $homeSection->items->keyBy('id');

            $keepIds = [];

            foreach ($submittedItems as $index => $itemData) {
                $itemId = $itemData['id'] ?? null;

                if ($itemId && $existingItems->has($itemId)) {
                    // Update existing item
                    $item = $existingItems[$itemId];

                    // Replace image only if new one uploaded
                    if ($request->hasFile("items.$index.image")) {
                        if ($item->image) {
                            Storage::delete('public/' . $item->image);
                        }
                        $itemData['image'] = $request->file("items.$index.image")->store('home_section_items', 'public');
                    } else {
                        // Keep existing image
                        $itemData['image'] = $item->image;
                    }

                    $item->update([
                        'title' => $itemData['title'],
                        'subtitle' => $itemData['subtitle'] ?? null,
                        'image' => $itemData['image'],
                    ]);

                    $keepIds[] = $itemId;
                } else {
                    // Create new item
                    if ($request->hasFile("items.$index.image")) {
                        $imagePath = $request->file("items.$index.image")->store('home_section_items', 'public');
                        $newItem = HomeSectionItem::create([
                            'home_section_id' => $homeSection->id,
                            'title' => $itemData['title'],
                            'subtitle' => $itemData['subtitle'] ?? null,
                            'image' => $imagePath,
                        ]);
                        $keepIds[] = $newItem->id;
                    }
                }
            }

            // Delete items not included in request
            $homeSection->items()->whereNotIn('id', $keepIds)->each(function ($item) {
                if ($item->image) {
                    Storage::delete('public/' . $item->image);
                }
                $item->delete();
            });
        }

        return redirect()->route('admin.home-sections.index')->with('success', 'Section updated.');
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
