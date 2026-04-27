<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class OtpService
{
    const MAX_ATTEMPTS = 5;
    const BLOCK_MINUTES = 5;
    const MAX_DAILY_OTP = 5;
    const OTP_EXPIRE_MINUTES = 5;
    const RESEND_COOLDOWN_SECONDS = 30;

    public function generateAndSendOtp(User $user): string
    {
        $this->checkDailyOtpLimit($user);

        if ($this->isUserBlocked($user)) {
            $remaining = now()->diffInMinutes($user->otp_blocked_until);
            throw new Exception("Try again in {$remaining} minutes.");
        }

        if ($this->hasActiveOtp($user)) {
            $remaining = now()->diffInSeconds($user->otp_expires_at);
            throw new Exception("Please wait {$remaining} seconds before requesting a new OTP.");
        }

        if ($user->last_otp_request_at && now()->diffInSeconds($user->last_otp_request_at) < self::RESEND_COOLDOWN_SECONDS) {
            $remaining = self::RESEND_COOLDOWN_SECONDS - now()->diffInSeconds($user->last_otp_request_at);
            throw new Exception("Please wait {$remaining} seconds before requesting a new OTP.");
        }

        $otp = $this->generateSecureOtp();
        $token = Str::uuid()->toString();
        // dd($user, $otp, $token);
        try {
            Mail::to($user->email)->send(new OtpMail($otp, $user, $token));
            $user->update([
                'otp' => $otp,
                'otp_expires_at' => now()->addMinutes(self::OTP_EXPIRE_MINUTES),
                'otp_attempts' => 0,
                'otp_requests_today' => $user->otp_requests_today + 1,
                'last_otp_request_date' => now()->toDateString(),
                'last_otp_request_at' => now(),
                'otp_verification_token' => $token,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email for user ' . $user->id . ': ' . $e->getMessage());
            throw new Exception('Failed to send OTP. Please try again.');
        }

        return $token;
    }

    protected function generateSecureOtp(): string
    {
        return str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }

    protected function checkDailyOtpLimit(User $user): void
    {
        if ($user->last_otp_request_date != today()->toDateString()) {
            $user->update([
                'otp_requests_today' => 0,
                'last_otp_request_date' => today(),
                'otp_blocked_until' => null,
            ]);
        }

        if ($user->otp_requests_today >= self::MAX_DAILY_OTP) {
            $this->blockUser($user);
            throw new Exception("Maximum OTP requests reached for today.");
        }
    }

    protected function hasActiveOtp(User $user): bool
    {
        return $user->otp_expires_at && now()->lt($user->otp_expires_at);
    }

    public function verifyOtp(User $user, string $enteredOtp, string $token): bool
    {
        try {
            if ($user->otp_verification_token !== $token) {
                throw new Exception("Invalid verification token.");
            }

            if ($this->isUserBlocked($user)) {
                throw new Exception("Account temporarily blocked. Try again later.");
            }

            if ($user->otp_attempts >= self::MAX_ATTEMPTS) {
                $this->blockUser($user);
                throw new Exception("Too many attempts. Account blocked for " . self::BLOCK_MINUTES . " minutes.");
            }
            
            $isValid = $user->otp === $enteredOtp && now()->lt($user->otp_expires_at);

            if (!$isValid) {
                $user->increment('otp_attempts');
                $user->refresh();
                $remaining = self::MAX_ATTEMPTS - $user->otp_attempts;

                throw new Exception("Invalid OTP. {$remaining} attempts left.");
            }

            $this->resetAttempts($user);
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    protected function isUserBlocked(User $user): bool
    {
        return $user->otp_blocked_until && now()->lt($user->otp_blocked_until);
    }

    protected function blockUser(User $user): void
    {
        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
            'otp_attempts' => 0,
            'otp_blocked_until' => now()->addMinutes(self::BLOCK_MINUTES),
        ]);
    }

    protected function resetAttempts(User $user): void
    {
        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
            'otp_attempts' => 0,
            'otp_blocked_until' => null,
            'otp_verification_token' => null,
            'email_verified_at' => now(),
        ]);
    }
}
