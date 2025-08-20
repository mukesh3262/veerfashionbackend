<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function filters(){

        
        dd('here');
        $categories = Category::mainCategories()->paginate(config('utility.pagination.per_page'));
        return response()->json([
            'data' => [
                'categories' => CategoryResource::collection($categories),
                'pagination' => [
                    'total' => $categories->total(),
                    'per_page' => $categories->perPage(),
                    'current_page' => $categories->currentPage(),
                    'last_page' => $categories->lastPage(),
                ]
            ]
        ]);
    }
}
