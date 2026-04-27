<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (Auth::check()) {
            return Auth::user()->status === 'active';
        }

        return $this->session()->has('checkout');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'phone'     => 'required|string|max:30',
            'address'   => 'required|string|max:255',
            'apartment' => 'nullable|string|max:255',
            'city'      => 'required|string|max:255',
            'postcode'  => 'nullable|string|max:50',
            'email'     => [Auth::check() ? 'nullable' : 'required', 'email', 'max:255'],
            'payment'     => 'required|string|in:cod,bkash,mobile-banking,card',
            'coupon_code' => 'nullable|string|max:50',
        ];
    }
}
