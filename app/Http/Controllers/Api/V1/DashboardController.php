<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $categories = Category::mainCategories()->get();
        return response()->json([
            'message' => __('label.ok'),
            'data' => [
                'categories' => CategoryResource::collection($categories),
            ],
        ]);
    }
}
