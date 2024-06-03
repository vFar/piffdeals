<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Good;
use App\Models\Attribute;
use App\Models\Group;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class GoodsController extends Controller
{
    private function getActiveData() {
        return [
            'activeCategories' => Category::where('status', 'Aktīvs')->get(),
            'activeGroups' => Group::where('status', 'Aktīvs')->get(),
            'activeAttributes' => Attribute::where('status', 'Aktīvs')->get(),
        ];
    }

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $status = $request->input('status');
    
        $query = Good::with(['group.category', 'attribute']);
    
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhereHas('attribute', function ($subQuery) use ($search) {
                      $subQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
    
        if (!empty($status)) {
            $query->where('status', $status);
        }
    
        $goods = $query->orderBy('created_at', 'desc')->paginate(15);
        $activeData = $this->getActiveData();
    
        return Inertia::render('Admin/Goods', array_merge([
            'goods' => $goods,
            'filters' => $request->only('search', 'status'),
            'totalGoods' => $goods->total(),
        ], $activeData));
    }
    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:255|unique:goods,sku',
            'status' => 'required|in:Aktīvs,Deaktivizēts',
            'group_id' => 'nullable|exists:groups,id',
            'attribute_id' => 'nullable|exists:attributes,id',
        ]);

        $validated['name'] = $request->input('name', 'Nenosaukta prece');
        $validated['description'] = $request->input('description', '');
        $validated['image'] = $request->input('image', '');
        $validated['stock_quantity'] = $request->input('stock_quantity', 0);
        $validated['price'] = $request->input('price', 0.00);

        $good = Good::create($validated);

        // Make sure this line runs and check the logs if it does not redirect
        return redirect()->route('admin.goods.edit', $good->id);
    }


    public function edit($id)
    {
        $good = Good::with(['group.category', 'attribute'])->findOrFail($id);
        $activeData = $this->getActiveData();
    
        return Inertia::render('Admin/GoodDetail', array_merge([
            'good' => $good,
        ], $activeData));
    }
    

    public function show($id)
    {
        $good = Good::with(['group.category', 'attribute'])->findOrFail($id);
        
        // Fetching active categories and groups as they might be needed in the edit form
        $activeCategories = Category::where('status', 'Aktīvs')->get();
        $activeGroups = Group::where('status', 'Aktīvs')->get();

        return Inertia::render('Admin/GoodDetail', [
            'good' => $good,
            'activeCategories' => $activeCategories,
            'activeGroups' => $activeGroups,
        ]);
    }

    

// app/Http/Controllers/Admin/GoodsController.php

    public function update(Request $request, $id)
    {
        $good = Good::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'sometimes|required|string',
            'stock_quantity' => 'required|integer',
            'status' => 'required|in:Aktīvs,Deaktivizēts',
            'group_id' => 'nullable',
            'attribute_id' => 'nullable',
        ]);

        if ($request->has('image') && $request->image !== $good->image) {
            $validated['image'] = $request->input('image'); // Use the new image filename
        } else {
            unset($validated['image']); // Keep the existing image if no new image was uploaded
        }

        $good->update($validated);

        return redirect()->route('admin.goods.index')->with('message', 'Product successfully updated!');
    }


    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,png,webp|max:2048', 
        ]);
    
        $filename = $request->file('image')->getClientOriginalName(); // Get filename only
        $path = $request->file('image')->storeAs('images/goods', $filename, 'public'); // Save to 'public' disk
    
        // Store ONLY the filename in the database
        return response()->json(['url' => $filename], 200); // Return only the filename
    }

    public function destroy($id)
    {
        $good = Good::findOrFail($id);
    
        // Retrieve the image URL and extract the filename
        $imageUrl = $good->image; // Get the full URL from the database
        $imageName = basename($imageUrl); // Extract the filename
    
        // Delete the record
        $good->delete();
    
        // Delete the image file from the disk (if it existed)
        if ($imageName) {
            $imagePath = "images/goods/{$imageName}"; // Relative path
            // Log::info("Image Name: " . $imageName);
            // Log::info("Image Path: " . $imagePath);
    
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
                // Log::info('Deleting image', ['path' => $imagePath]);
            }
        }
    
        return redirect()->route('admin.goods.index')->with('message', 'Prece veiksmīgi dzēsta!');
    }
    
    
    

    
}
