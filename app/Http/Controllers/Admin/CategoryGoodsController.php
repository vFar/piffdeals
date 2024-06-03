<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Models\Category;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;


class CategoryGoodsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $categories = Category::withCount(['groups as goods_count' => function ($query) {
            $query->selectRaw('sum(goods_count) as goods_count')->from('goods')
                ->join('groups', 'goods.group_id', '=', 'groups.id')
                ->whereColumn('groups.category_id', 'categories.id');
        }])
        ->when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(15);
        
        return Inertia::render('Admin/Categories', [
            'categories' => $categories,
            'totalCategories' => $categories->total(),
            'filters' => $request->only('search'),
        ]);
    }
    
    /**
     * Fetch categories with optional search.
     */
    public function fetchCategories($search = '')
    {
        $categories = Category::when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Adjust pagination as needed

        return [
            'categories' => $categories,
            'totalCategories' => $categories->total(),

        ];
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktīvs,Deaktivizēts',
        ]);
    
        Category::create($validated);
    
        return redirect()->back()->with([
            'title' => 'Jauna kategorija',
            'message' => 'Jaunas kategorijas izveide noritējusi veiksmīgi',
            'type' => 'success'
        ]);
    }
    

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktīvs,Deaktivizēts',
        ]);
    
        $category->update($validated);
    
        return redirect()->back()->with([
            'title' => 'Kategorijas atjaunināšana',
            'message' => 'Kategorija veiksmīgi atjaunināta',
            'type' => 'success'
        ]);    
    }

    
    public function getActiveCategories()
    {
        $categories = Category::where('status', 'Aktīvs')->get();
        return $categories;
    }

    public function activeCategories()
    {
        $categories = Category::where('status', 'Aktīvs')->get();
        return Inertia::render('Navbar', [
            'activeCategories' => $categories
        ]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Set linked groups to null
        Group::where('category_id', $id)->update(['category_id' => null]);
        
        // Delete the category
        $category->delete();
    
        return redirect()->back()->with([
            'title' => 'Kategorijas dzēšana',
            'message' => 'Kategorija veiksmīgi dzēsta',
            'type' => 'success'
        ]);
    }
}
