<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function __construct(protected OtpService $otpService) {}

    public function showRegistrationForm()
    {
        if (!view()->exists('auth.register')) {
            Log::error('Registration view auth.register not found.');
            abort(404, 'Registration form not available.');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        try {
            DB::beginTransaction();

            $customerRole = Role::where('slug', 'customer')->first();
            if (!$customerRole) {
                throw new \Exception('Customer role not found. Please contact support.');
            }

            $user = User::create([
                'name' => trim($validated['name']),
                'email' => strtolower(trim($validated['email'])),
                'password' => Hash::make($validated['password']),
                'role_id' => $customerRole->id,
                'status' => 'active',
                'otp_attempts' => 0,
                'otp_requests_today' => 0,
                'entry_user_id' => null,
                'last_otp_request_at' => null,
                'otp_verification_token' => null,
            ]);

            $token = $this->otpService->generateAndSendOtp($user);
            DB::commit();

            return redirect()->route('otp.verify', ['token' => $token])
                ->with('success', 'OTP sent to your email!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Registration failed. Please try again.');
        }
    }
    protected function redirectPath()
    {
        return route('home');
    }
}
