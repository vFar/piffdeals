<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;


class GroupGoodsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $categoryId = $request->input('category_id');

        $query = Group::with('category');

        $totalUnlinkedGroups = Group::whereNull('category_id')->count();

        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%");
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        $groups = $query->orderBy('created_at', 'desc')->paginate(15);

        $activeCategories = $this->getActiveCategories();

        return Inertia::render('Admin/Groups', [
            'groups' => $groups,
            'filters' => $request->only('search', 'category_id'),
            'totalGroups' => $groups->total(),
            'activeCategories' => $activeCategories,
            'totalUnlinkedGroups' => $totalUnlinkedGroups,
        ]);
    }

    

    public function fetchGroups($search = '')
    {
        $groups = Group::with('category')  // Eager load the category
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return [
            'groups' => $groups,
            'totalGroups' => $groups->total(),
        ];
    }

    public function getActiveCategories()
    {
        return Category::where('status', 'Aktīvs')->get();
    }

   public function store(Request $request)
    {
        \Log::info('Received data:', $request->all());  // Log all incoming request data

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktīvs,Deaktivizēts',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        // Directly create the group with validated data.
        Group::create($validated);

        return redirect()->back()->with([
            'title' => 'Jauna grupa',
            'message' => 'Jaunas grupas izveide noritējusi veiksmīgi',
            'type' => 'success'
        ]);
    }

    public function update(Request $request, $id)
    {
        \Log::info('Update Request:', $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktīvs,Deaktivizēts',
            'category_id' => 'nullable|exists:categories,id'
        ]);
    
        $group = Group::findOrFail($id);
        $group->update($validated);
    
        return redirect()->back()->with([
            'title' => 'Grupas atjaunināšana',
            'message' => 'Grupa veiksmīgi atjaunināta',
            'type' => 'success'
        ]);
    }

    public function activeGroups()
    {
        $groups = Group::where('status', 'Aktīvs')->get();
        return Inertia::render('Navbar', [
            'activeGroups' => $groups
        ]);
    }
}
