<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategorySampahResource;
use App\Models\CategorySampah;
use Illuminate\Http\Request;

class CategorySampahController extends Controller
{
    public function index (){
        $category = CategorySampah::all();
        return response()->json([
            'status' => true,
            'message' => 'success get data',
            'data' => CategorySampahResource::collection($category)
        ],200);
    }
}
