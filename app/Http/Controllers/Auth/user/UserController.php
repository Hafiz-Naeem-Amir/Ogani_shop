<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Flasher\Laravel\Facade\Flasher;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function get()
    {
        return view('welcome');
    }

    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        $user->assignRole('Customer');
        Auth::login($user);

        Flasher::addSuccess('Registration successful!');
        return redirect()->route('dashboard');
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            // $request->session()->regenerate();
            Flasher::addSuccess('Login successful!');

        }

        // Flasher::addError('Invalid credentials');
        return back()->withInput();
    }

    public function logout(Request $request, FlasherInterface $flasher)
    {
        // Auth::logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        Flasher::addInfo('You have been logged out successfully');
        return redirect()->route('custom.login');
    }



}
