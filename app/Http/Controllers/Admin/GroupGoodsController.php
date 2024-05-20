<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;


class GroupGoodsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $groups = Group::query()
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return Inertia::render('Admin/Groups', [
            'groups' => $groups,
            'filters' => $request->only('search'),
            'totalGroups' => $groups->total(),
        ]);
    }

    public function fetchGroups($search = '')
    {
        $groups = Group::when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return [
            'groups' => $groups,
            'totalGroups' => $groups->total(),
        ];
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktīvs,Deaktivizēts',
            'category_id' => 'nullable|exists:categories,id'
        ]);
    
        Group::create($validated);
    
        return redirect()->back()->with([
            'title' => 'Jauna grupa',
            'message' => 'Jaunas grupas izveide noritējusi veiksmīgi',
            'type' => 'success'
        ]);
    }

    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Aktīvs,Deaktivizēts',
            'category_id' => 'nullable|exists:categories,id'
        ]);
    
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
