<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Good; // Ensure this model is imported
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class NavigationDataController extends Controller
{
    public function getActiveCategories()
    {
        $activeCategories = Category::where('status', 'Aktīvs')
            ->whereHas('groups', function ($query) {
                $query->where('status', 'Aktīvs');
            })
            ->with(['groups' => function ($query) {
                $query->where('status', 'Aktīvs')
                      ->with(['attributes' => function ($subQuery) {
                          $subQuery->where('status', 'Aktīvs');
                      }]);
            }])
            ->get();

        \Log::info('Fetched Categories:', ['categories' => $activeCategories]);

        return response()->json([
            'activeCategories' => $activeCategories,
        ]);
    }

    public function getActiveGoodsCount()
    {
        $activeGoodsCount = Good::where('status', 'Aktīvs')->count();

        \Log::info('Fetched Active Goods Count:', ['count' => $activeGoodsCount]);

        return response()->json([
            'activeGoodsCount' => $activeGoodsCount,
        ]);
    }

    public function getActiveGoods()
    {
        $activeGoods = Good::where('status', 'Aktīvs')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'goods' => $activeGoods,
        ]);
    }
}

