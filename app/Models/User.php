<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'role_id',
        'otp',
        'otp_expires_at',
        'otp_attempts',
        'otp_last_attempt',
        'otp_blocked_until',
        'otp_requests_today',
        'last_otp_request_date',
        'last_otp_request_at',
        'status',
        'entry_user_id',
        'otp_verification_token',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'otp_last_attempt' => 'datetime',
        'otp_blocked_until' => 'datetime',
        'last_otp_request_date' => 'date',
        'last_otp_request_at' => 'datetime',
        'status' => 'string',
    ];
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function profile()
    {
        return $this->hasOne(CustomerProfile::class);
    }
    public function productWishes()
    {
        return $this->hasMany(ProductWish::class);
    }
    public function productCarts()
    {
        return $this->hasMany(ProductCart::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
