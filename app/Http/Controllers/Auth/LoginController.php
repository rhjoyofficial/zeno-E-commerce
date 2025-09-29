<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $remember = $request->has('remember');

            if (Auth::attempt($validated, $remember)) {
                $request->session()->regenerate();
                // Log the login attempt
                if (Gate::allows('isAdmin')) {
                    return redirect()->route('admin.dashboard')
                        ->with('success', 'Login successful! Welcome back, Admin.');
                }

                if (Auth::check() && Session::has('cart')) {
                    // Get the cart controller instance
                    $cartController = app()->make('App\Http\Controllers\Customer\CartController');
                    // Sync the cart
                    $cartController->syncCart();
                }

                return redirect()
                    ->intended($this->redirectPath())
                    ->with('success', 'Login successful! Welcome back.');
            }

            return back()
                ->withInput()
                ->withErrors(['email' => 'Invalid credentials.']);
        } catch (\Exception $e) {
            Log::error('Login Error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Login service unavailable. Please try again later.');
        }
    }
    protected function redirectPath()
    {
        return route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Logout successful! See you next time.');
    }
}
