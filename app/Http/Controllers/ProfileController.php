<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        // Hanya akun yang sedang login; password di-hash otomatis oleh cast 'hashed'
        Auth::user()->update(['password' => $request->password]);

        return back()->with('success', 'Password berhasil diganti.');
    }
}
