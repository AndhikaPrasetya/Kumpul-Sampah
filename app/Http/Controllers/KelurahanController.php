<?php

namespace App\Http\Controllers;

use App\Models\KelurahanDetails;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KelurahanController extends Controller
{
    public function index(){
        $title = "Data Kelurahan";
        $data = KelurahanDetails::with('user')->get();
        return view('dashboard.kelurahan.index',compact('title','data'));
    }

    public function create(){
        return view('dashboard.kelurahan.create');
    }

  public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' =>'required|string|max:255',
            'email' =>'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'no_phone' =>'required|string|max:15',
            'kecamatan' =>'required|string',
            'kota' =>'required|string',
            'provinsi' =>'required|string',
            'alamat' =>'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try{
            DB::beginTransaction();
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password =  Hash::make($request->password);
            $user->no_phone = $request->no_phone;
            $user->save();
            $user->assignRole('kelurahan');

            $kelurahanDetails =new KelurahanDetails();
            $kelurahanDetails->user_id = $user->id;
            $kelurahanDetails->kecamatan = $request->kecamatan;
            $kelurahanDetails->kota = $request->kota;
            $kelurahanDetails->provinsi = $request->provinsi;
            $kelurahanDetails->alamat = $request->alamat;
            $kelurahanDetails->save();

            DB::commit();
            return response()->json([
               'status' => true,
               'message' => 'kelurahan added successfully'
            ],200);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
               'status' => false,
               'message' => 'Failed to add kelurahan',
               'error' => $e->getMessage()
            ],500);
        }
    }

 public function edit($id){
    // 1. Find the KelurahanDetail record by its ID
    $kelurahanDetail = KelurahanDetails::where('id', $id)->first();

    // 2. Check if KelurahanDetail exists. If not, return 404.
    if (!$kelurahanDetail) {
        return response()->json([
           'status' => false,
           'message' => 'Kelurahan details not found', // Changed message for clarity
        ], 404);
    }

    // 3. Find the associated User using the user_id from kelurahanDetail
    //    And ensure that user has the 'kelurahan' role.
    $kelurahan = User::role('kelurahan')
                     ->where('id', $kelurahanDetail->user_id)
                     ->first(); // Use first() instead of findOrFail to handle cases where user might not have the role

    // 4. Check if the User with the 'kelurahan' role was found. If not, return 404.
    if (!$kelurahan) {
        return response()->json([
           'status' => false,
           'message' => 'Kelurahan user not found or does not have the "kelurahan" role',
        ], 404);
    }

    // If both kelurahanDetail and kelurahan user are found, return the view
    return view('dashboard.kelurahan.edit', compact('kelurahan', 'kelurahanDetail'));
}

    public function update(Request $request, $id){
 $validator = Validator::make($request->all(),[
            'name' =>'nullable|string|max:255',
            'email' =>['nullable', 'email', Rule::unique('users', 'email')->ignore($id)],
            'password' => 'nullable|string|min:8',
            'no_phone' =>'nullable|string|max:15',
            'kecamatan' =>'nullable|string',
            'kota' =>'nullable|string',
            'provinsi' =>'nullable|string',
            'alamat' =>'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try{
            DB::beginTransaction();
            $user = User::find($id);
            $user->name = $request->name;
            $user->no_phone = $request->no_phone;

            // Update password jika diisi
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }

            if ($request->has('email')) {
                $user->email = $request->email;
            }
            $user->save();
            $kelurahanDetails = KelurahanDetails::where('user_id', $id)->first();
            $kelurahanDetails->user_id = $user->id;
            $kelurahanDetails->kecamatan = $request->kecamatan;
            $kelurahanDetails->kota = $request->kota;
            $kelurahanDetails->provinsi = $request->provinsi;
            $kelurahanDetails->alamat = $request->alamat;
            $kelurahanDetails->save();

            DB::commit();
            return response()->json([
               'success' => true,
               'message' => 'kelurahan updated successfully'
            ],200);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
               'status' => false,
               'message' => 'Failed to update kelurahan',
               'error' => $e->getMessage()
            ],500);
        }
        
        return redirect()->route('kelurahan.index');
    }

   public function delete($id){
        try {
            DB::beginTransaction();
        
            $kelurahan = User::role('kelurahan')->findOrFail($id);
            $kelurahan->roles()->detach(); // Hapus relasi roles
            $kelurahan->delete(); 
        
            DB::commit();
            return response()->json([
               'status' => true,
               'message' => 'kelurahan deleted successfully'
            ],200);
        } catch(\Exception $e){
            DB::rollback();
            return response()->json([
               'status' => false,
               'message' => 'Failed to delete kelurahan',
               'error' => $e->getMessage()
            ],500);
        }
    }
}
