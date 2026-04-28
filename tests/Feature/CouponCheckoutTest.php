<?php

use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

// G-12: Feature test: Coupon application at checkout

function couponTestUser(): User
{
    $role = Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
    return User::factory()->create(['role_id' => $role->id, 'status' => 'active']);
}

function validCoupon(array $attrs = []): Coupon
{
    return Coupon::withoutEvents(fn () => Coupon::create(array_merge([
        'code'       => 'SAVE' . strtoupper(uniqid()),
        'type'       => 'percentage',
        'value'      => 10.00,
        'is_active'  => true,
        'valid_from' => now()->subDay(),
        'valid_to'   => now()->addDay(),
    ], $attrs)));
}

function couponProduct(): Product
{
    return Product::create([
        'title'          => 'Coupon Test Product',
        'price'          => 100.00,
        'stock_quantity' => 20,
        'sku'            => 'CPN-' . uniqid(),
        'slug'           => 'cpn-' . uniqid(),
        'status'         => 'active',
        'has_variants'   => false,
        'discount'       => false,
    ]);
}

test('checkout index page renders for authenticated user with cart items', function () {
    $user    = couponTestUser();
    $product = couponProduct();

    $cartItem = ProductCart::create([
        'user_id'    => $user->id,
        'product_id' => $product->id,
        'variant_id' => null,
        'qty'        => 1,
        'price'      => 100.00,
    ]);

    $response = $this->actingAs($user)->post(route('checkout.index'), [
        'selected_items' => json_encode([$cartItem->id]),
    ]);

    $response->assertOk()->assertViewIs('customer.checkout');
});

test('checkout with valid coupon code processes and creates order', function () {
    DB::table('sequences')->updateOrInsert(['name' => 'invoice_number'], ['value' => 0]);

    $user    = couponTestUser();
    $product = couponProduct();
    $coupon  = validCoupon();

    $cartItem = ProductCart::create([
        'user_id'    => $user->id,
        'product_id' => $product->id,
        'variant_id' => null,
        'qty'        => 2,
        'price'      => 100.00,
    ]);

    // Populate checkout session with no-coupon totals
    $this->actingAs($user)->post(route('checkout.index'), [
        'selected_items' => json_encode([$cartItem->id]),
    ]);

    $orderData = [
        'full_name'   => 'Test Customer',
        'phone'       => '01700000000',
        'address'     => '123 Test Ave',
        'city'        => 'Dhaka',
        'payment'     => 'cod',
        'coupon_code' => $coupon->code,
    ];

    // First submit: coupon discount changes grand_total → PRICE_CHANGED guard updates session
    $this->actingAs($user)->post(route('checkout.store'), $orderData);

    // Second submit: session grand_total matches coupon-adjusted total → order is placed
    $response = $this->actingAs($user)->post(route('checkout.store'), $orderData);

    $response->assertRedirectContains('confirmation');
    expect(DB::table('orders')->count())->toBe(1);
});

test('checkout with expired coupon shows error', function () {
    $user    = couponTestUser();
    $product = couponProduct();
    $coupon  = validCoupon(['valid_to' => now()->subDay()]);

    $cartItem = ProductCart::create([
        'user_id'    => $user->id,
        'product_id' => $product->id,
        'variant_id' => null,
        'qty'        => 1,
        'price'      => 100.00,
    ]);

    // Populate checkout session via checkout.index
    $this->actingAs($user)->post(route('checkout.index'), [
        'selected_items' => json_encode([$cartItem->id]),
    ]);

    $response = $this->actingAs($user)->post(route('checkout.store'), [
        'full_name'   => 'Test Customer',
        'phone'       => '01700000000',
        'address'     => '123 Test Ave',
        'city'        => 'Dhaka',
        'payment'     => 'cod',
        'coupon_code' => $coupon->code,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');
});
