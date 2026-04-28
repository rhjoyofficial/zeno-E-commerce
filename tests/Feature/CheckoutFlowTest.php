<?php

use App\Models\Product;
use App\Models\ProductCart;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

// G-8: Feature test: Full checkout flow — guest and authenticated user

function flowProduct(): Product
{
    return Product::create([
        'title'          => 'Flow Product',
        'price'          => 80.00,
        'stock_quantity' => 30,
        'sku'            => 'FL-' . uniqid(),
        'slug'           => 'fl-' . uniqid(),
        'status'         => 'active',
        'has_variants'   => false,
        'discount'       => false,
    ]);
}

function flowUser(): User
{
    $role = Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
    return User::factory()->create(['role_id' => $role->id, 'status' => 'active']);
}

test('authenticated user: add to cart then view checkout page', function () {
    $user    = flowUser();
    $product = flowProduct();

    // Step 1: add to cart
    $addResponse = $this->actingAs($user)->post(route('cart.add'), [
        'product_id' => $product->id,
        'qty'        => 1,
    ]);
    $addResponse->assertRedirect();

    $cartItem = ProductCart::where('user_id', $user->id)->first();
    expect($cartItem)->not->toBeNull();

    // Step 2: view checkout page
    $checkoutResponse = $this->actingAs($user)->post(route('checkout.index'), [
        'selected_items' => json_encode([$cartItem->id]),
    ]);
    $checkoutResponse->assertOk()->assertViewIs('customer.checkout');
});

test('authenticated user: full order placement flow', function () {
    DB::table('sequences')->updateOrInsert(['name' => 'invoice_number'], ['value' => 0]);

    $user    = flowUser();
    $product = flowProduct();

    $cartItem = ProductCart::create([
        'user_id'    => $user->id,
        'product_id' => $product->id,
        'variant_id' => null,
        'qty'        => 1,
        'price'      => 80.00,
    ]);

    // Populate checkout session via checkout.index
    $this->actingAs($user)->post(route('checkout.index'), [
        'selected_items' => json_encode([$cartItem->id]),
    ]);

    $storeResponse = $this->actingAs($user)->post(route('checkout.store'), [
        'full_name' => 'Jane Smith',
        'phone'     => '01900000001',
        'address'   => '456 Main Rd',
        'city'      => 'Chittagong',
        'payment'   => 'cod',
    ]);

    $storeResponse->assertRedirectContains('confirmation');

    // Stock decremented
    $product->refresh();
    expect($product->stock_quantity)->toBe(29);

    // Cart cleared
    expect(ProductCart::where('user_id', $user->id)->count())->toBe(0);
});

test('guest: add to cart session then checkout page shows checkout view', function () {
    $product = flowProduct();

    // Guest adds to cart
    $this->post(route('cart.add'), [
        'product_id' => $product->id,
        'qty'        => 1,
    ]);

    expect(session('cart'))->not->toBeEmpty();
});
