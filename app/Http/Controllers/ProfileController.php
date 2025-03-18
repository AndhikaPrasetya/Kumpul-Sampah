<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load('nasabahs');
     
        return view('profile.edit', [
            'user' => $user,
            'nasabah' => $user->nasabahs->first()
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
    
        // Perbarui data user langsung
        $user->update($request->validated());
    
        // Pastikan user memiliki nasabah sebelum update
        if ($user->nasabahs()->exists()) {
            foreach ($user->nasabahs as $nasabah) {
                // Menyiapkan data update
                $nasabahData = [
                    'alamat' => $request->input('alamat'),
                ];
    
                // Jika ada file photo yang diunggah
                if ($request->hasFile('photo')) {
                    // Hapus foto lama jika ada
                    if ($nasabah->photo && Storage::exists('public/' . $nasabah->photo)) {
                        Storage::delete('public/' . $nasabah->photo);
                    }
    
                    // Simpan foto baru
                    $photoPath = $request->file('photo')->store('profile_photos', 'public');
                    $nasabahData['photo'] = $photoPath;
                }
    
                // Update data nasabah
                $nasabah->update($nasabahData);
            }
        }
    
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
