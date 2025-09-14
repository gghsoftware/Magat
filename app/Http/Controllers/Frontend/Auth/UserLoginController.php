<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // If a safe 'next' is provided, go there; else intended; else home
            $next = $request->input('next');
            if ($next && str_starts_with($next, url('/'))) {
                return redirect()->to($next)->with('success', 'Logged in successfully. Welcome back!');
            }

            return redirect()->intended(route('frontend.home.index'))
                ->with('success', 'Logged in successfully. Welcome back!');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('frontend.login');
    }

    public function showRegisterForm()
    {
        return view('frontend.auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'max:100', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ]);

        // Determine a default role_id (e.g., "Customer")
        $roleId = DB::table('roles')->where('role_name', 'Customer')->value('id')
            ?? DB::table('roles')->orderBy('id')->value('id') // fallback to first role
            ?? 1; // final fallback if table is empty

        $user = User::create([
            'role_id'  => $roleId,
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'status'   => 'active',
        ]);

        Auth::login($user);                 // auto-login on register
        $request->session()->regenerate();  // rotate session

        return redirect()->intended(route('frontend.home.index'))
            ->with('success', 'Welcome! Your account was created successfully.');
    }

    public function showForgotForm()
    {
        return view('frontend.auth.forgot'); // optional placeholder
    }
}
