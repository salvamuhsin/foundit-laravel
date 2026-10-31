<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email or password is incorrect.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        $user = $request->user();
        ActivityLog::record($user, 'login', 'Signed in.');

        // Admins always land in the admin panel; members go back to where they were headed.
        return $user->isAdmin()
            ? redirect()->route('admin.index')
            : redirect()->intended(route('dashboard.index'));
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'email.unique' => 'An account with this email already exists. Try signing in.',
        ]);

        // role defaults to "user" in the database; it can never be set from this form.
        $user = User::create($data);

        Auth::login($user);
        $request->session()->regenerate();
        ActivityLog::record($user, 'register', 'Created an account.');

        return redirect()->route('dashboard.index');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index');
    }
}
