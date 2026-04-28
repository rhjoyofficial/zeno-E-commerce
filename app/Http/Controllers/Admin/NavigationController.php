<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavigationMenu;
use App\Models\NavigationMenuItem;
use App\Models\MegaMenuContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NavigationController extends Controller
{
    public function index()
    {
        $menus = NavigationMenu::with('items')->orderBy('position')->get();
        return view('admin.navigation.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.navigation.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:navigation_menus',
            'position' => 'required|integer',
            'status' => 'required|in:active,inactive',
            'is_mega_menu' => 'boolean',
            'mega_menu_type' => 'nullable|string',
        ]);

        $menu = NavigationMenu::create($validated);
        $this->bustNavCache();

        return redirect()->route('admin.navigation.edit', $menu->id)
            ->with('success', 'Navigation menu created successfully.');
    }

    public function edit($id)
    {
        $menu = NavigationMenu::with(['items.children', 'megaMenuContents'])->findOrFail($id);
        return view('admin.navigation.edit', compact('menu'));
    }

    public function update(Request $request, $id)
    {
        $menu = NavigationMenu::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:navigation_menus,slug,' . $id,
            'position' => 'required|integer',
            'status' => 'required|in:active,inactive',
            'is_mega_menu' => 'boolean',
            'mega_menu_type' => 'nullable|string',
        ]);

        $menu->update($validated);
        $this->bustNavCache();

        return redirect()->back()->with('success', 'Navigation menu updated successfully.');
    }

    public function destroy($id)
    {
        $menu = NavigationMenu::findOrFail($id);
        $menu->delete();
        $this->bustNavCache();

        return redirect()->route('admin.navigation.index')
            ->with('success', 'Navigation menu deleted successfully.');
    }

    public function storeItem(Request $request, $menuId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:navigation_menu_items,id',
            'url' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'order' => 'required|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['navigation_menu_id'] = $menuId;

        NavigationMenuItem::create($validated);
        $this->bustNavCache();

        return redirect()->back()->with('success', 'Menu item added successfully.');
    }

    public function updateItem(Request $request, $menuId, $itemId)
    {
        $item = NavigationMenuItem::where('navigation_menu_id', $menuId)->findOrFail($itemId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:navigation_menu_items,id',
            'url' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'order' => 'required|integer',
            'status' => 'required|in:active,inactive',
        ]);

        $item->update($validated);
        $this->bustNavCache();

        return redirect()->back()->with('success', 'Menu item updated successfully.');
    }

    public function destroyItem($menuId, $itemId)
    {
        $item = NavigationMenuItem::where('navigation_menu_id', $menuId)->findOrFail($itemId);
        $item->delete();
        $this->bustNavCache();

        return redirect()->back()->with('success', 'Menu item deleted successfully.');
    }

    public function storeMegaContent(Request $request, $menuId)
    {
        $validated = $request->validate([
            'type' => 'required|in:categories,featured_collections,brands,promo_banner',
            'title' => 'required|string|max:255',
            'content' => 'required|json',
            'columns' => 'required|integer|min:1|max:4',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $validated['navigation_menu_id'] = $menuId;

        MegaMenuContent::create($validated);
        $this->bustNavCache();

        return redirect()->back()->with('success', 'Mega menu content added successfully.');
    }

    public function updateMegaContent(Request $request, $menuId, $contentId)
    {
        $content = MegaMenuContent::where('navigation_menu_id', $menuId)->findOrFail($contentId);

        $validated = $request->validate([
            'type' => 'required|in:categories,featured_collections,brands,promo_banner',
            'title' => 'required|string|max:255',
            'content' => 'required|json',
            'columns' => 'required|integer|min:1|max:4',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $content->update($validated);
        $this->bustNavCache();

        return redirect()->back()->with('success', 'Mega menu content updated successfully.');
    }

    public function destroyMegaContent($menuId, $contentId)
    {
        $content = MegaMenuContent::where('navigation_menu_id', $menuId)->findOrFail($contentId);
        $content->delete();
        $this->bustNavCache();

        return redirect()->back()->with('success', 'Mega menu content deleted successfully.');
    }

    private function bustNavCache(): void
    {
        Cache::forget('navigation_menus');
    }
}
