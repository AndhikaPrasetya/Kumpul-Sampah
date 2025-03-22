<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Models\User;
use App\Models\Saldo;
use App\Models\Rewards;
use Illuminate\Http\Request;
use App\Models\NasabahDetail;
use App\Models\PenukaranPoints;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PenukaranPoinFeController extends Controller
{
    public function detailReward($id)
    {
        $user = Auth::user();
        $rewards = Rewards::findOrFail($id);

        $currentPoints = Saldo::where('user_id', $user->id)->value('points') ?? 0;
        $needPoints = $rewards->points - $currentPoints;

        return view('frontend.rewards.detail', [
            'rewards' => $rewards,
            'needPoints' => $needPoints,
            'currentPoints' => $currentPoints,
            'route' => route('listRewards')
        ]);
    }

    public function rewardStore(Request $request)
    {
        $user = $request->user()->id;
        $validator = Validator::make($request->all(), [
            'reward_id' => 'required|exists:rewards,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            $nasabahDetail = NasabahDetail::where('user_id',$user)->first();
            $bsuid = $nasabahDetail ? $nasabahDetail->bsu_id : null;
            $reward = Rewards::where('bsu_id', $bsuid)->findOrFail($request->reward_id);

            //ambil points di saldo user 
            $saldo = Saldo::where('user_id', $user)->first();
            if (!$saldo || $saldo->points < $reward->points) {
                return response()->json([
                    'success' => false,
                    'error' => 'Saldo poin anda tidak cukup'
                ], 400);
            }
        
            $saldo->points -= $reward->points;
            $saldo->save(); 
        
            PenukaranPoints::create([
                'user_id' => $user,
                'bsu_id'=>$bsuid,
                'reward_id' => $request->reward_id,
                'total_points' => $reward->points,
            ]);

           
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Penukaran poin berhasil',
                'data' => $user
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::info([
               
               'message' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat melakukan penukaran poin',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function waitingReward(){
        return view('frontend.rewards.waiting');
    }
}
