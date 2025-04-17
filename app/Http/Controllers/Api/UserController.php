<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\BsuResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index (){
        $bsu = User::with(['bsu'])->get();
        return response()->json([
            'status' =>true,
            'message' => 'success get data',
            'data' => UserResource::collection($bsu)
        ],200);
    }

    public function show(User $user){
        return response()->json([
            'status'=>true,
            'message' => 'succes get data',
            'data' =>new UserResource($user)
        ],200);
    }
}
