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
    private function getActiveData() { // Funkcija, lai iegūtu aktīvos datus kategorijām, grupām un atribūtiem
        return [
            'activeCategories' => Category::where('status', 'Aktīvs')->get(),
            'activeGroups' => Group::where('status', 'Aktīvs')->get(),
            'activeAttributes' => Attribute::where('status', 'Aktīvs')->get(),
        ];
    }

    public function index(Request $request) // Parāda preču sarakstu
    {
        $search = $request->input('search', ''); // Iegūst meklēšanas ievadi
        $status = $request->input('status'); // Iegūst statusa ievadi
    
        $query = Good::with(['group.category', 'attribute']); // Veido jautājumu ar grupas un atribūta attiecībām
    
        if (!empty($search)) { // Ja meklēšanas lauks nav tukšs
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%") // Meklē pēc nosaukuma
                  ->orWhere('sku', 'like', "%{$search}%") // Vai pēc SKU
                  ->orWhereHas('attribute', function ($subQuery) use ($search) {
                      $subQuery->where('name', 'like', "%{$search}%"); // Vai pēc atribūta nosaukuma
                  });
            });
        }
    
        if (!empty($status)) { // Ja statusa lauks nav tukšs
            $query->where('status', $status); // Pievieno statusa filtru
        }
    
        $goods = $query->orderBy('created_at', 'desc')->paginate(15); // Sakārto pēc izveides datuma un sadala lapās
        $activeData = $this->getActiveData(); // Iegūst aktīvos datus
        return Inertia::render('Admin/Goods', array_merge([
            'goods' => $goods,
            'filters' => $request->only('search', 'status'),
            'totalGoods' => $goods->total(),
        ], $activeData)); // Atgriež skatu ar datiem
    }

    public function store(Request $request) // Veido jaunu preci
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:255|unique:goods,sku', // Pārbauda, vai SKU ir unikāls
            'status' => 'required|in:Aktīvs,Deaktivizēts', // Pārbauda, vai statuss ir derīgs
            'group_id' => 'nullable|exists:groups,id', // Pārbauda, vai grupas ID eksistē
            'attribute_id' => 'nullable|exists:attributes,id', // Pārbauda, vai atribūta ID eksistē
        ]);

        $validated['name'] = $request->input('name', 'Nenosaukta prece'); // Ja nosaukums nav norādīts, piešķir "Nenosaukta prece"
        $validated['description'] = $request->input('description', ''); // Apraksts
        $validated['image'] = $request->input('image', ''); // Attēls
        $validated['stock_quantity'] = $request->input('stock_quantity', 0); // Noliktavas daudzums
        $validated['price'] = $request->input('price', 0.00); // Cena

        $good = Good::create($validated); // Izveido preci ar validētajiem datiem

        return redirect()->route('admin.goods.edit', $good->id); // Pāradresē uz preces rediģēšanas lapu
    }

    public function edit($id) // Parāda preces rediģēšanas skatu
    {
        $good = Good::with(['group.category', 'attribute'])->findOrFail($id); // Atrod preci ar attiecībām
        $activeData = $this->getActiveData(); // Iegūst aktīvos datus
    
        return Inertia::render('Admin/GoodDetail', array_merge([
            'good' => $good,
        ], $activeData)); // Atgriež skatu ar datiem
    }

    public function show($id) // Parāda preces detaļas
    {
        $good = Good::with(['group.category', 'attribute'])->findOrFail($id); // Atrod preci ar attiecībām
        
        $activeCategories = Category::where('status', 'Aktīvs')->get(); // Iegūst aktīvās kategorijas
        $activeGroups = Group::where('status', 'Aktīvs')->get(); // Iegūst aktīvās grupas

        return Inertia::render('Admin/GoodDetail', [
            'good' => $good,
            'activeCategories' => $activeCategories,
            'activeGroups' => $activeGroups,
        ]); // Atgriež skatu ar datiem
    }

    public function update(Request $request, $id) // Atjaunina preces informāciju
    {
        $good = Good::findOrFail($id); // Atrod preci

        $validated = $request->validate([
            'name' => 'required|string|max:255', // Pārbauda nosaukumu
            'description' => 'required|string', // Pārbauda aprakstu
            'image' => 'sometimes|required|string', // Pārbauda attēlu, ja tas ir sniegts
            'stock_quantity' => 'required|integer', // Pārbauda noliktavas daudzumu
            'price' => 'required|numeric', // Pārbauda cenu
            'status' => 'required|in:Aktīvs,Deaktivizēts', // Pārbauda statusu
            'group_id' => 'nullable', // Grupas ID var būt tukšs
            'attribute_id' => 'nullable', // Atribūta ID var būt tukšs
        ]);

        if ($request->has('image') && $request->image !== $good->image) {
            $validated['image'] = $request->input('image'); // Ja attēls ir sniegts un tas atšķiras no pašreizējā
        } else {
            unset($validated['image']); // Pretējā gadījumā, izņem attēlu no validētajiem datiem
        }

        $good->update($validated); // Atjaunina preci ar validētajiem datiem
        return redirect()->route('admin.goods.index')->with('message', 'Product successfully updated!'); // Pāradresē un paziņo par veiksmīgu atjaunināšanu
    }

    public function uploadImage(Request $request) // Augšupielādē attēlu
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpg,png,webp|max:2048', // Pārbauda attēlu
            ]);
    
            $filename = $request->file('image')->getClientOriginalName(); // Iegūst faila nosaukumu
            $path = $request->file('image')->storeAs('images/goods', $filename, 'public'); // Saglabā attēlu publiskajā diskā
    
            Log::info("Image uploaded successfully", [
                'filename' => $filename,
                'path' => $path,
            ]);

            return response()->json(['url' => $filename], 200); // Atgriež veiksmīgu atbildi
        } catch (\Exception $e) {
            Log::error("Image upload error: " . $e->getMessage(), [
                'exception' => $e,
            ]);
    
            return response()->json(['error' => $e->getMessage()], 500); // Atgriež kļūdas atbildi
        }
    }

    public function destroy($id) // Dzēš preci
    {
        $good = Good::findOrFail($id); // Atrod preci
    
        $imageUrl = $good->image; // Iegūst attēla URL
        $imageName = basename($imageUrl); // Iegūst attēla nosaukumu
    
        $good->delete(); // Dzēš preci
    
        if ($imageName) {
            $imagePath = "images/goods/{$imageName}"; // Iegūst attēla ceļu
    
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath); // Dzēš attēlu no diska
            }
        }
        return redirect()->route('admin.goods.index')->with('message', 'Prece veiksmīgi dzēsta!'); // Pāradresē un paziņo par veiksmīgu dzēšanu
    }
}
