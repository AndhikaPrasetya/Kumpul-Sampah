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

        if (Auth::check()) {
            if (Auth::user()->hasRole('super admin')) {
                return redirect()->route('dashboard');
            }
        }
        return view('auth.login');
    }

    public function createLoginUser(): View
    {
        if (Auth::check()) {
            if (Auth::user()->hasRole('nasabah')) {
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

        $user = Auth::user(); // Dapatkan pengguna yang baru saja login
        if ($user->hasRole('super admin') || $user->hasRole('kelurahan')) {
            // Super Admin dan Kelurahan selalu ke dashboard admin
            return redirect()->to('/admin/dashboard');
        } elseif ($user->hasRole('bsu')) {
            if ($user->bsu && $user->bsu->status === 'approved') {
                return redirect()->to('/admin/dashboard');
            } else {
                Auth::logout();
                return redirect()->to('/admin/login')->with('error', 'Akun BSU Anda belum disetujui atau tidak aktif.');
            }
        } elseif ($user->hasRole('nasabah')) {
            // Nasabah selalu ke halaman utama
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

        if ($user && $user->hasRole(['super admin', 'bsu', 'kelurahan'])) {
            return redirect('/admin/login');
        }

        return redirect('/login');
    }
}
