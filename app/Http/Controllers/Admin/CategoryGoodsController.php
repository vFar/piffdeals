<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;


class CategoryGoodsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $categories = Category::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc') // Order by created_at in descending order
            ->paginate(10);
        
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

    public function activeCategories()
    {
        $categories = Category::where('status', 'Aktīvs')->get();
        return Inertia::render('Navbar', [
            'activeCategories' => $categories
        ]);
    }
}
