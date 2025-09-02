<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categories(){
        $categories = Category::mainCategories()->get();
        return response()->json([
            'data' => CategoryResource::collection($categories)
        ]);
    }

    public function subCategories(Category $category)
    {
        $subcategories =  $category->subcategories()->get();
        return response()->json([
            'data' => CategoryResource::collection($subcategories)
        ]);
    }
}
