<?php

use App\Models\Product;
use App\Models\ProductCart;
use Illuminate\Support\Facades\Session;

// G-11: Feature test: Cart add/update/remove (SSR redirect flow)

function cartProduct(array $attrs = []): Product
{
    return Product::create(array_merge([
        'title'          => 'Cart Product',
        'price'          => 60.00,
        'stock_quantity' => 15,
        'sku'            => 'C-' . uniqid(),
        'slug'           => 'c-' . uniqid(),
        'status'         => 'active',
        'has_variants'   => false,
        'discount'       => false,
    ], $attrs));
}

test('guest can add a product to cart and receives redirect', function () {
    $product = cartProduct();

    $response = $this->from('/')->post(route('cart.add'), [
        'product_id' => $product->id,
        'qty'        => 2,
    ]);

    $response->assertRedirect('/');
    expect(session('cart'))->toHaveKey($product->id . ':0');
});

test('AJAX cart add returns JSON success', function () {
    $product = cartProduct();

    $response = $this->postJson(route('cart.add'), [
        'product_id' => $product->id,
        'qty'        => 1,
    ]);

    $response->assertOk()
        ->assertJsonFragment(['success' => true]);
});

test('cart add returns error redirect when out of stock', function () {
    $product = cartProduct(['stock_quantity' => 0]);

    $response = $this->from('/')->post(route('cart.add'), [
        'product_id' => $product->id,
        'qty'        => 1,
    ]);

    $response->assertRedirect('/');
    $response->assertSessionHas('error');
});

test('authenticated user can update cart quantity via AJAX', function () {
    $user    = \App\Models\User::factory()->create();
    $product = cartProduct(['stock_quantity' => 20]);

    $cartItem = ProductCart::create([
        'user_id'    => $user->id,
        'product_id' => $product->id,
        'variant_id' => null,
        'qty'        => 1,
        'price'      => 60.00,
    ]);

    $response = $this->actingAs($user)->postJson(route('cart.update', $cartItem->id), [
        'qty' => 3,
    ]);

    $response->assertOk()->assertJsonFragment(['success' => true]);
    expect($cartItem->fresh()->qty)->toBe(3);
});

test('authenticated user can remove cart item', function () {
    $user    = \App\Models\User::factory()->create();
    $product = cartProduct();

    $cartItem = ProductCart::create([
        'user_id'    => $user->id,
        'product_id' => $product->id,
        'variant_id' => null,
        'qty'        => 1,
        'price'      => 60.00,
    ]);

    $response = $this->actingAs($user)->postJson(route('cart.remove', $cartItem->id));

    $response->assertOk()->assertJsonFragment(['success' => true]);
    expect(ProductCart::find($cartItem->id))->toBeNull();
});
