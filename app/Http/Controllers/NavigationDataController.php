<?php

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class NavigationDataController extends Controller
{
    public function getActiveCategories()
    {
        $activeCategories = Category::where('status', 'Aktīvs')->select('id', 'name')->get();
        return response()->json(['activeCategories' => $activeCategories]);
    }
}
