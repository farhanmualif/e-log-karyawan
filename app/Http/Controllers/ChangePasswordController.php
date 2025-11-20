<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function showChangeForm()
    {
        return view('auth.passwords.change');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed'
        ]);

        $user = auth()->user();

        $user->password = Hash::make($request->password);
        $user->password_changed = true;
        $user->save();

        return redirect('/home')->with('success', 'Password berhasil diubah!');
    }
}
