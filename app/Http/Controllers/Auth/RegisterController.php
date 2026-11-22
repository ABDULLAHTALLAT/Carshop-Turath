<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

/** Car owners create an account to list their cars; they are never admins */
class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email:rfc', 'max:120', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:25', 'regex:/^[0-9+\-\s()]+$/'],
            'password' => ['required', 'confirmed', Password::min(10)->letters()->numbers()],
            'website' => ['max:0'], // honeypot
        ]);

        $user = User::create(collect($data)->only(['name', 'email', 'phone', 'password'])->all());

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('owner.dashboard')->with('status', 'Your account is ready. Add your first car.');
    }
}
