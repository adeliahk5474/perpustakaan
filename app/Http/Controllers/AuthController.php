<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->isAdmin() ? 'admin.dashboard' : 'member.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('member.dashboard');
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'member_id' => 'required|string|unique:users,member_id',
            'password'  => ['required', 'confirmed', Password::min(8)],
            'terms'     => 'accepted',
        ], [
            'email.unique'     => 'Email ini sudah terdaftar.',
            'member_id.unique' => 'Member ID ini sudah digunakan.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'terms.accepted'   => 'Kamu harus menyetujui syarat dan ketentuan.',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'member_id' => $request->member_id,
            'password'  => Hash::make($request->password),
            'role'      => 'member',
        ]);

        Auth::login($user);

        return redirect()->route('member.dashboard')->with('success', 'Selamat datang di PerpusKu, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
