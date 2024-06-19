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

        // Filter goods by active status
        $goods->where('status', 'Aktīvs');

        // Always eager-load group and attribute information
        $goods->with(['group', 'attribute']);

        if ($request->has('attribute_id')) {
            $goods->where('attribute_id', $request->attribute_id);
        }

        if ($request->has('group_id')) {
            $goods->where('group_id', $request->group_id);
        }

        // Apply pagination with query parameters
        $filteredGoods = $goods->paginate(20)->appends($request->query());

        return Inertia::render('GroupGoods', [
            'filteredGoods' => $filteredGoods,
            'query' => $request->query(), // Pass the query parameters to the view
        ]);
    }
}

