<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HomeSection;
use App\Models\HomeSectionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:new_arrivals,fashion',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'category_id' => 'nullable|required_if:type,fashion|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'order' => 'integer|min:0',
        ]);

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('home_sections', 'public');
        }

        $section = HomeSection::create($validated);

        // Handle slider items if fashion
        if ($request->type === 'fashion' && $request->has('items')) {
            foreach ($request->input('items') as $item) {
                if (isset($item['title'], $item['image'])) {
                    $item['image'] = $item['image']->store('home_section_items', 'public'); // Assume file upload for each
                    $item['home_section_id'] = $section->id;
                    HomeSectionItem::create($item);
                }
            }
        }

        return redirect()->route('admin.home-sections.index')->with('success', 'Section created.');
    }

    public function edit(HomeSection $homeSection)
    {
        $categories = Category::whereNull('parent_id')->get();
        return view('admin.home-sections.edit', compact('homeSection', 'categories'));
    }

    public function update(Request $request, HomeSection $homeSection)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'banner_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'category_id' => 'nullable|required_if:type,fashion|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'order' => 'integer|min:0',
        ]);

        if ($request->hasFile('banner_image')) {
            if ($homeSection->banner_image) {
                Storage::delete('public/' . $homeSection->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('home_sections', 'public');
        }

        $homeSection->update($validated);

        // Update slider items (simplified: delete old, add new)
        if ($homeSection->type === 'fashion') {
            $homeSection->items()->delete();
            if ($request->has('items')) {
                foreach ($request->input('items') as $item) {
                    if (isset($item['title'], $item['image'])) {
                        $item['image'] = $item['image']->store('home_section_items', 'public');
                        $item['home_section_id'] = $homeSection->id;
                        HomeSectionItem::create($item);
                    }
                }
            }
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
}
