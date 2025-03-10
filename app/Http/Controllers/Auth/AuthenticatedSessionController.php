<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function createLoginAdmin(): View
    {
        if(Auth::check()){
            if(Auth::user()->hasRole('super admin')){
                return redirect()->route('dashboard');
            }
        }
        return view('auth.login');
    }

    public function createLoginUser(): View
    {
        if(Auth::check()){
            if(Auth::user()->hasRole('nasabah')){
                return redirect()->route('welcome');
            }
        }
        return view('auth.user_login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if(Auth::user()->hasRole(['super admin','bsu','nasabah'])){
            return redirect()->to('/admin/dashboard');
        }elseif(Auth::user()->hasRole('nasabah')){
            return redirect()->to('/');
        }

        return redirect()->intended(route('welcome'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {

        $user = Auth::user();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if($user && $user->hasRole(['super admin','bsu'])){
            return redirect('/admin/login');
        }
        
        return redirect('/login');
    }
}
