<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    
    public function banners(){
        $banners = Banner::where('is_active', Banner::ACTIVE)->orderBy('created_at', 'asc')->get();
        $banners = BannerResource::collection($banners);
        return response()->json([
            'data' => $banners
        ]);
    }
}
