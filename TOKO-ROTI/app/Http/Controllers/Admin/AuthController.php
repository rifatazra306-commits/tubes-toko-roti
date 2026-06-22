<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('admin')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'user' => 'required',
            'pass' => 'required'
        ]);

        $admin = Admin::where('username', $request->user)->first();

        if ($admin && $request->pass === $admin->password) {
            session([
                'admin' => $admin->username
            ]);
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('admin.login')->with('error', 'USERNAME/PASSWORD SALAH');
    }

    public function logout()
    {
        session()->forget('admin');
        return redirect()->route('admin.login');
    }
}
