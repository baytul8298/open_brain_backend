<?php
namespace App\Http\Controllers\Admin;

use App\Models\NavMenu;
use App\Models\NavSubmenu;
use App\Models\NavButton;
use App\Models\NavParentMenu;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MenuController extends Controller
{
    public function index()
    {
        $parentMenus = NavParentMenu::orderBy('sort_order')->orderBy('id')->get();
        $menus = NavMenu::orderBy('sort_order')->orderBy('id')->get();
        return Inertia::render('Admin/Menus/Index', [
            'parentMenus' => $parentMenus,
            'menus' => $menus,
        ]);
    }

    // -- NAV PARENT MENU CRUD --
    public function storeParent(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'type'       => 'required|in:admin,teacher,student',
            'guard'      => 'required|in:web,admin',
            'sort_order' => 'integer|min:0',
            'is_active'  => 'boolean',
        ]);
        NavParentMenu::create($validated);
        return redirect()->back()->with('success', 'Parent menu created.');
    }

    public function updateParent(Request $request, int $id)
    {
        $parent = NavParentMenu::findOrFail($id);
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'type'       => 'required|in:admin,teacher,student',
            'guard'      => 'required|in:web,admin',
            'sort_order' => 'integer|min:0',
            'is_active'  => 'boolean',
        ]);
        $parent->update($validated);
        return redirect()->back()->with('success', 'Parent menu updated.');
    }

    public function destroyParent(int $id)
    {
        NavParentMenu::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Parent menu deleted.');
    }

    public function reorderParentBatch(Request $request)
    {
        $validated = $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:nav_parent_menus,id',
        ]);
        foreach ($validated['ids'] as $index => $id) {
            NavParentMenu::where('id', $id)->update(['sort_order' => $index]);
        }
        return response()->json(['success' => true]);
    }

    // -- NAV MENU CRUD --
    public function storeMenu(Request $request)
    {
        $validated = $request->validate([
            'parent_menu_id' => 'nullable|integer|exists:nav_parent_menus,id',
            'name'           => 'required|string|max:255',
            'type'           => 'required|in:admin,teacher,student',
            'key'            => 'required|string|max:100|unique:nav_menus,key',
            'icon_key'       => 'nullable|string|max:255',
            'to'             => 'nullable|string|max:255',
            'search_key'     => 'nullable|string|max:255',
            'guard'          => 'required|in:web,admin',
            'sort_order'     => 'integer|min:0',
            'is_active'      => 'boolean',
        ]);
        NavMenu::create($validated);
        return redirect()->back()->with('success', 'Menu created successfully.');
    }

    public function updateMenu(Request $request, int $id)
    {
        $menu = NavMenu::findOrFail($id);
        $validated = $request->validate([
            'parent_menu_id' => 'nullable|integer|exists:nav_parent_menus,id',
            'name'           => 'required|string|max:255',
            'type'           => 'required|in:admin,teacher,student',
            'key'            => 'required|string|max:100|unique:nav_menus,key,' . $id,
            'icon_key'       => 'nullable|string|max:255',
            'to'             => 'nullable|string|max:255',
            'search_key'     => 'nullable|string|max:255',
            'guard'          => 'required|in:web,admin',
            'sort_order'     => 'integer|min:0',
            'is_active'      => 'boolean',
        ]);
        $menu->update($validated);
        return redirect()->back()->with('success', 'Menu updated successfully.');
    }

    public function destroyMenu(int $id)
    {
        NavMenu::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Menu deleted successfully.');
    }

    public function reorderMenuBatch(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:nav_menus,id',
        ]);
        foreach ($validated['ids'] as $index => $id) {
            NavMenu::where('id', $id)->update(['sort_order' => $index]);
        }
        return response()->json(['success' => true]);
    }

    // -- NAV SUBMENU CRUD --
    public function getSubmenus(int $menuId)
    {
        $submenus = NavSubmenu::where('menu_id', $menuId)->orderBy('sort_order')->orderBy('id')->get();
        return response()->json($submenus);
    }

    public function storeSubmenu(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|integer|exists:nav_menus,id',
            'name' => 'required|string|max:255',
            'search_key' => 'nullable|string|max:255',
            'to' => 'nullable|string|max:255',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);
        NavSubmenu::create($validated);
        return redirect()->back()->with('success', 'Submenu created successfully.');
    }

    public function updateSubmenu(Request $request, int $id)
    {
        $submenu = NavSubmenu::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'search_key' => 'nullable|string|max:255',
            'to' => 'nullable|string|max:255',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);
        $submenu->update($validated);
        return redirect()->back()->with('success', 'Submenu updated successfully.');
    }

    public function destroySubmenu(int $id)
    {
        NavSubmenu::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Submenu deleted successfully.');
    }

    public function reorderSubmenuBatch(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:nav_submenus,id',
        ]);
        foreach ($validated['ids'] as $index => $id) {
            NavSubmenu::where('id', $id)->update(['sort_order' => $index]);
        }
        return response()->json(['success' => true]);
    }

    // -- NAV BUTTON CRUD --
    public function getButtons(int $submenuId)
    {
        $buttons = NavButton::where('submenu_id', $submenuId)->orderBy('sort_order')->orderBy('id')->get();
        return response()->json($buttons);
    }

    public function storeButton(Request $request)
    {
        $validated = $request->validate([
            'submenu_id' => 'required|integer|exists:nav_submenus,id',
            'name' => 'required|string|max:255',
            'key_value' => 'required|string|max:255',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);
        // Unique name within submenu
        $exists = NavButton::where('submenu_id', $validated['submenu_id'])
            ->where('name', $validated['name'])->exists();
        if ($exists) return redirect()->back()->withErrors(['name' => 'Button name must be unique inside this submenu.']);
        $existsKey = NavButton::where('submenu_id', $validated['submenu_id'])
            ->where('key_value', $validated['key_value'])->exists();
        if ($existsKey) return redirect()->back()->withErrors(['key_value' => 'Key value must be unique inside this submenu.']);
        NavButton::create($validated);
        return redirect()->back()->with('success', 'Button created successfully.');
    }

    public function updateButton(Request $request, int $id)
    {
        $button = NavButton::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key_value' => 'required|string|max:255',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);
        $exists = NavButton::where('submenu_id', $button->submenu_id)
            ->where('name', $validated['name'])->where('id', '!=', $id)->exists();
        if ($exists) return redirect()->back()->withErrors(['name' => 'Button name must be unique inside this submenu.']);
        $existsKey = NavButton::where('submenu_id', $button->submenu_id)
            ->where('key_value', $validated['key_value'])->where('id', '!=', $id)->exists();
        if ($existsKey) return redirect()->back()->withErrors(['key_value' => 'Key value must be unique inside this submenu.']);
        $button->update($validated);
        return redirect()->back()->with('success', 'Button updated successfully.');
    }

    public function destroyButton(int $id)
    {
        NavButton::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Button deleted successfully.');
    }

    public function reorderButtonBatch(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:nav_buttons,id',
        ]);
        foreach ($validated['ids'] as $index => $id) {
            NavButton::where('id', $id)->update(['sort_order' => $index]);
        }
        return response()->json(['success' => true]);
    }
}
