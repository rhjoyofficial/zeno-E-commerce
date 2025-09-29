<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function privacyPolicy()
    {
        return view('frontend.pages.policies.privacy-policy');
    }

    public function shippingPolicy()
    {
        return view('frontend.pages.policies.shipping-policy');
    }

    public function exchangePolicy()
    {
        return view('frontend.pages.policies.exchange-policy');
    }

    public function termsConditions()
    {
        return view('frontend.pages.policies.terms-and-conditions');
    }
}
