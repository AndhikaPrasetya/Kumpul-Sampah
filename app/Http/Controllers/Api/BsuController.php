<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BsuResource;
use App\Models\BsuDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class BsuController extends Controller
{
    public function index (){
        $bsu = BsuDetail::with(['user'])->get();
        return response()->json([
            'status' =>true,
            'message' => 'success get data',
            'data' => BsuResource::collection($bsu)
        ],200);
    }
}
