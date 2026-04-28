<?php

use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\ShippingAddress;
use App\Services\CheckoutService;
use App\Services\SequenceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(Tests\TestCase::class, RefreshDatabase::class);

// G-2: CheckoutService::processOrder() and G-4: CouponService (applyCoupon)

function makeCheckoutProduct(array $attrs = []): Product
{
    return Product::create(array_merge([
        'title'          => 'Checkout Test Product',
        'price'          => 50.00,
        'stock_quantity' => 20,
        'sku'            => 'CHK-' . uniqid(),
        'slug'           => 'chk-' . uniqid(),
        'status'         => 'active',
        'has_variants'   => false,
        'discount'       => false,
    ], $attrs));
}

function makeCoupon(array $attrs = []): Coupon
{
    return Coupon::withoutEvents(fn () => Coupon::create(array_merge([
        'code'      => 'TEST' . strtoupper(uniqid()),
        'type'      => 'percentage',
        'value'     => 10.00,
        'is_active' => true,
        'valid_from' => now()->subDay(),
        'valid_to'   => now()->addDay(),
    ], $attrs)));
}

function makeCheckoutUser(): \App\Models\User
{
    return \App\Models\User::factory()->create();
}

// G-4: applyCoupon happy path — percentage coupon
test('applyCoupon returns correct percentage discount', function () {
    $coupon  = makeCoupon(['type' => 'percentage', 'value' => 20]);
    $service = new CheckoutService();

    $discount = $service->applyCoupon($coupon->code, 100.00);

    expect($discount)->toEqual(20.00);
});

// G-4: applyCoupon happy path — fixed coupon
test('applyCoupon returns correct fixed discount', function () {
    $coupon  = makeCoupon(['type' => 'fixed', 'value' => 15]);
    $service = new CheckoutService();

    $discount = $service->applyCoupon($coupon->code, 100.00);

    expect($discount)->toEqual(15.00);
});

// G-4: Fixed coupon capped at subtotal
test('applyCoupon caps fixed discount at subtotal', function () {
    $coupon  = makeCoupon(['type' => 'fixed', 'value' => 200]);
    $service = new CheckoutService();

    $discount = $service->applyCoupon($coupon->code, 50.00);

    expect($discount)->toEqual(50.00);
});

// G-4: Expired coupon
test('applyCoupon throws for expired coupon', function () {
    $coupon = makeCoupon(['valid_to' => now()->subDay()]);
    $service = new CheckoutService();

    expect(fn () => $service->applyCoupon($coupon->code, 100.00))
        ->toThrow(Exception::class, 'Invalid or expired coupon code.');
});

// G-4: Usage limit exceeded
test('applyCoupon throws when usage limit is reached', function () {
    $coupon = makeCoupon(['usage_limit' => 1]);
    DB::table('coupons')->where('id', $coupon->id)->update(['used_count' => 1]);
    $service = new CheckoutService();

    expect(fn () => $service->applyCoupon($coupon->code, 100.00))
        ->toThrow(Exception::class, 'Invalid or expired coupon code.');
});

// G-4: Minimum order not met
test('applyCoupon throws when minimum order amount is not met', function () {
    $coupon = makeCoupon(['min_order_amount' => 200.00]);
    $service = new CheckoutService();

    expect(fn () => $service->applyCoupon($coupon->code, 50.00))
        ->toThrow(Exception::class, 'minimum order');
});

// G-2: processOrder happy path
test('processOrder creates an order and decrements stock', function () {
    DB::table('sequences')->updateOrInsert(['name' => 'invoice_number'], ['value' => 0]);

    $user    = makeCheckoutUser();
    $product = makeCheckoutProduct(['stock_quantity' => 10, 'price' => 50.00]);

    $this->actingAs($user);

    $cartItem = ProductCart::create([
        'user_id'    => $user->id,
        'product_id' => $product->id,
        'variant_id' => null,
        'qty'        => 2,
        'price'      => 50.00,
    ]);

    session(['checkout' => []]);

    $result = (new CheckoutService())->processOrder(
        validatedData: [
            'full_name'   => 'Test User',
            'phone'       => '01700000000',
            'address'     => '123 Test Street',
            'city'        => 'Dhaka',
            'payment'     => 'cod',
        ],
        selectedIds: [$cartItem->id],
        customerIp:  '127.0.0.1',
        sessionId:   null,
    );

    expect($result['success'])->toBeTrue();
    expect($result['order']->invoice_number)->toBe(1);

    $product->refresh();
    expect($product->stock_quantity)->toBe(8);
});

// G-2: Stock failure
test('processOrder throws when stock is insufficient', function () {
    DB::table('sequences')->updateOrInsert(['name' => 'invoice_number'], ['value' => 0]);

    $user    = makeCheckoutUser();
    $product = makeCheckoutProduct(['stock_quantity' => 1]);

    $this->actingAs($user);

    $cartItem = ProductCart::create([
        'user_id'    => $user->id,
        'product_id' => $product->id,
        'variant_id' => null,
        'qty'        => 5,
        'price'      => 50.00,
    ]);

    session(['checkout' => []]);

    expect(fn () =>
        (new CheckoutService())->processOrder(
            validatedData: [
                'full_name' => 'Test',
                'phone'     => '01700000000',
                'address'   => '123 St',
                'city'      => 'Dhaka',
                'payment'   => 'cod',
            ],
            selectedIds: [$cartItem->id],
            customerIp:  '127.0.0.1',
            sessionId:   null,
        )
    )->toThrow(Exception::class, 'Insufficient stock');
});

// G-2: Price change guard
test('processOrder throws PRICE_CHANGED when session total differs', function () {
    DB::table('sequences')->updateOrInsert(['name' => 'invoice_number'], ['value' => 0]);

    $user    = makeCheckoutUser();
    $product = makeCheckoutProduct(['stock_quantity' => 10, 'price' => 50.00]);

    $this->actingAs($user);

    $cartItem = ProductCart::create([
        'user_id'    => $user->id,
        'product_id' => $product->id,
        'variant_id' => null,
        'qty'        => 1,
        'price'      => 50.00,
    ]);

    // Put a stale total in session (50 + 5% VAT = 52.50, but session says 99.00)
    session(['checkout' => ['grand_total' => 99.00]]);

    expect(fn () =>
        (new CheckoutService())->processOrder(
            validatedData: [
                'full_name' => 'Test',
                'phone'     => '01700000000',
                'address'   => '123 St',
                'city'      => 'Dhaka',
                'payment'   => 'cod',
            ],
            selectedIds: [$cartItem->id],
            customerIp:  '127.0.0.1',
            sessionId:   null,
        )
    )->toThrow(Exception::class, 'PRICE_CHANGED');
});
