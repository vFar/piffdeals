<?php

namespace App\Http\Controllers;

use App\Models\Good;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GoodsPreviewController extends Controller
{
    public function show($id)
    {
        $good = Good::findOrFail($id);

        return Inertia::render('GoodsPreview', [
            'good' => $good,
            'currentPage' => $good->name, // Pass good.name as 'currentPage'
        ]);
    }
}