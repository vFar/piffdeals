<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Group;
use App\Models\Good;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class AttributesGoodsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $groupId = $request->input('group_id');

        $query = Attribute::with(['group.category', 'goods']);
        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%");
        }

        if (!empty($groupId)) {
            $query->where('group_id', $groupId);
        }

        $attributes = $query->orderBy('created_at', 'desc')->paginate(15);
        $activeGroups = Group::where('status', 'Aktīvs')->with('category')->get();
        $totalUnlinkedAttributes = Attribute::whereNull('group_id')->count();
        

        return Inertia::render('Admin/Attributes', [
            'attributes' => $attributes,
            'filters' => $request->only('search', 'group_id'),
            'totalAttributes' => $attributes->total(),
            'activeGroups' => $activeGroups,
            'totalUnlinkedAttributes' => $totalUnlinkedAttributes,
        ]);
    }

    public function destroy($id)
    {
        try {
            $attribute = Attribute::findOrFail($id);
            
            // Unlink goods linked to this attribute
            Good::where('attribute_id', $id)->update(['attribute_id' => null]);
            
            // Delete the attribute
            $attribute->delete();
            
            return redirect()->back()->with([
                'title' => 'Kategorijas dzēšana',
                'message' => 'Kategorija veiksmīgi dzēsta',
                'type' => 'success'
            ]);        
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting attribute: ' . $e->getMessage()], 500);
        }
    }
    
    
    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'group_id' => 'nullable|exists:groups,id'  // Changed from 'required' to 'nullable'
        ]);

        Attribute::create($validated);

        return redirect()->back()->with([
            'title' => 'New Attribute',
            'message' => 'New attribute successfully created!',
            'type' => 'success'
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'group_id' => 'nullable|exists:groups,id',
            'status' => 'required|in:Aktīvs,Deaktivizēts' // Ensure 'status' is validated
        ]);
    
        $attribute = Attribute::findOrFail($id);
        $attribute->update($validated);
    
        return redirect()->back()->with([
            'title' => 'Attribute Update',
            'message' => 'Attribute successfully updated',
            'type' => 'success'
        ]);
    }
    
}
