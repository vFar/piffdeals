<?php

namespace App\Http\Controllers;

use App\Models\Good;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FilterGoodsController extends Controller
{
    public function index(Request $request)
    {
        $goods = Good::query();

        // Filter by attribute_id if present
        if ($request->has('attribute_id')) {
            $goods->where('attribute_id', $request->attribute_id);
            $filteredGoods = $goods->get();
            return Inertia::render('AttributeGoods', [ // Render the correct view
                'filteredGoods' => $filteredGoods,
            ]);
        }

        // Filter by group_id if present
        if ($request->has('group_id')) {
            $goods->where('group_id', $request->group_id);
            $filteredGoods = $goods->get();
            return Inertia::render('GroupGoods', [ // Render the correct view
                'filteredGoods' => $filteredGoods,
            ]);
        }

        // If no filter is present, redirect to the homepage (or any other appropriate page)
        return redirect('/'); 
    }
}