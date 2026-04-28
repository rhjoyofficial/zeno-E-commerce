<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'             => 'required|string|max:50|unique:coupons,code',
            'type'             => 'required|in:percentage,fixed',
            'value'            => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'valid_from'       => 'required|date',
            'valid_to'         => 'required|date|after:valid_from',
            'usage_limit'      => 'nullable|integer|min:1',
            'is_active'        => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'code'             => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type'             => 'required|in:percentage,fixed',
            'value'            => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'valid_from'       => 'required|date',
            'valid_to'         => 'required|date|after:valid_from',
            'usage_limit'      => 'nullable|integer|min:1',
            'is_active'        => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        if ($coupon->orders()->exists()) {
            return redirect()->route('admin.coupons.index')
                ->with('error', 'Cannot delete a coupon that has been used in orders.');
        }

        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted successfully.');
    }
}
