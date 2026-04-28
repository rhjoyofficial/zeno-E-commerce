<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CartService;
use App\Services\OtpService;
use App\Events\UserRegistered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class OTPVerificationController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function showOtpForm(string $token)
    {
        $user = User::where('otp_verification_token', $token)
            ->select('id', 'email', 'otp_expires_at', 'otp_blocked_until', 'email_verified_at')
            ->first();

        if (!$user) {
            Log::warning('Invalid OTP verification token: ' . $token);
            return redirect()->route('login')->with('error', 'Invalid verification token.');
        }

        if ($user->email_verified_at) {
            return redirect()->route('login')->with('error', 'Email already verified.');
        }

        if ($this->otpService->isUserBlocked($user)) {
            $remaining = now()->diffInMinutes($user->otp_blocked_until);
            return redirect()->route('login')
                ->with('error', "Too many attempts. Try again in {$remaining} minutes.");
        }

        return view('auth.verify-otp', [
            'user'             => $user,
            'token'            => $token,
            'remainingSeconds' => $user->otp_expires_at ? now()->diffInSeconds($user->otp_expires_at) : 0,
        ]);
    }

    public function resendOtp(Request $request, string $token)
    {
        $user = User::where('otp_verification_token', $token)->first();

        if (!$user) {
            Log::warning('Invalid OTP resend token: ' . $token);
            return redirect()->route('register')->with('error', 'Invalid verification token.');
        }

        try {
            $newToken = $this->otpService->generateAndSendOtp($user);
            return redirect()->route('otp.verify', ['token' => $newToken])
                ->with('success', 'OTP resent to your email!');
        } catch (\Exception $e) {
            Log::error('Failed to resend OTP for token ' . $token . ': ' . $e->getMessage());
            return redirect()->route('otp.verify', ['token' => $token])
                ->withErrors(['otp' => $e->getMessage()]);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp'   => 'required|digits:6',
            'token' => 'required|string',
        ]);

        $user = User::where('otp_verification_token', $request->token)->first();

        if (!$user) {
            Log::warning('Invalid OTP verification token: ' . $request->token);
            return back()->withInput()->withErrors(['otp' => 'Invalid verification token.']);
        }

        try {
            $this->otpService->verifyOtp($user, $request->otp, $request->token);
            Auth::login($user, $request->boolean('remember'));

            if (Session::has('cart')) {
                app(CartService::class)->syncCart();
            }

            event(new UserRegistered($user));
            return redirect()->route('home')->with('success', 'Registration successful! Welcome!');
        } catch (\Exception $e) {
            Log::error('OTP verification failed for token ' . $request->token . ': ' . $e->getMessage());
            return back()->withInput()->withErrors(['otp' => $e->getMessage()]);
        }
    }
}
