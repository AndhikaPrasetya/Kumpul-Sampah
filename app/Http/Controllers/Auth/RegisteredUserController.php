<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Saldo;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use App\Models\BsuDetail;
use App\Models\KelurahanDetails;
use App\Models\NasabahDetail;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $bsu = User::with('roles')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'bsu');
            })
            ->get();

        return view('auth.register', compact('bsu'));
    }
    public function createBsu(): View
    {
        $kelurahans = KelurahanDetails::with('user')->get();

        return view('auth.register_bsu', compact('kelurahans'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'alamat' => 'nullable',
        ]);

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $user->syncRoles('nasabah');
            //nasabah detail 
            $nasabahDetail = new NasabahDetail();
            $nasabahDetail->user_id = $user->id;
            $nasabahDetail->bsu_id = $request->bsu_id;
            $nasabahDetail->save();
            //buatkan saldo 
            $saldo = new Saldo();
            $saldo->user_id = $user->id;
            $saldo->bsu_id = $request->bsu_id;
            $saldo->balance = 0;
            $saldo->points = 0;
            $saldo->save();
            DB::commit();

            event(new Registered($user));

            // Auth::login($user);

            return redirect(route('login', absolute: false))->with('success', 'Registrasi Berhasil, Silahkan Login.');
        } catch (Exception $e) {
            DB::rollBack();
            //log error
            Log::error($e->getMessage());

            return redirect()->back()->with('error', 'Failed to register, please try again.');
        }
    }
    public function store_bsu(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'alamat' => 'required',
            'rw' => 'required',
            'rt' => 'required',
            'kelurahan_id' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $user->syncRoles('bsu');
            //bsu detail 
            $bsuDetail = new BsuDetail();
            $bsuDetail->user_id = $user->id;
            $bsuDetail->rt = $request->rt;
            $bsuDetail->rw = $request->rw;
            $bsuDetail->alamat = $request->alamat;
            $bsuDetail->status = 'hold';
            $bsuDetail->kelurahan_id = $request->kelurahan_id;
            $bsuDetail->save();

            DB::commit();

            event(new Registered($user));

            return redirect(route('waiting-bsu', absolute: false));
        } catch (Exception $e) {
            DB::rollBack();
            //log error
            Log::error($e->getMessage());

            return redirect()->back()->with('error', 'Failed to register, please try again.');
        }
    }
}
