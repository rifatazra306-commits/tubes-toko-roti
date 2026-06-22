<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('user')) {
            return redirect()->route('home');
        }
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'pass' => 'required'
        ]);

        $customer = Customer::where('username', $request->username)->first();

        if ($customer && $request->pass === $customer->password) {
            session([
                'user' => $customer->nama,
                'kd_cs' => $customer->kode_customer
            ]);
            return redirect()->route('home');
        }

        return redirect()->route('login')->with('error', 'USERNAME/PASSWORD SALAH');
    }

    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'telp' => 'required',
            'password' => 'required',
            'konfirmasi' => 'required'
        ]);

        if ($request->password !== $request->konfirmasi) {
            return redirect()->route('register')->with('error', 'KONFIRMASI PASSWORD TIDAK SAMA');
        }

        // Cek username
        $exists = Customer::where('username', $request->username)->exists();
        if ($exists) {
            return redirect()->route('register')->with('error', 'USERNAME SUDAH DIGUNAKAN');
        }

        // Generate Customer Code (C0001, C0002, etc.)
        $lastCustomer = Customer::orderBy('kode_customer', 'desc')->first();
        if ($lastCustomer) {
            $num = (int) substr($lastCustomer->kode_customer, 1, 4);
            $add = $num + 1;
        } else {
            $add = 1;
        }

        if (strlen($add) == 1) {
            $format = "C000" . $add;
        } elseif (strlen($add) == 2) {
            $format = "C00" . $add;
        } elseif (strlen($add) == 3) {
            $format = "C0" . $add;
        } else {
            $format = "C" . $add;
        }

        Customer::create([
            'kode_customer' => $format,
            'nama' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'password' => $request->password,
            'telp' => $request->telp
        ]);

        return redirect()->route('login')->with('success', 'REGISTER BERHASIL. SILAKAN LOGIN.');
    }

    public function logout()
    {
        session()->forget(['user', 'kd_cs']);
        return redirect()->route('home');
    }
}
