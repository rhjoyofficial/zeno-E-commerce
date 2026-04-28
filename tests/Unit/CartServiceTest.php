<?php

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;

uses(Tests\TestCase::class, RefreshDatabase::class);

// G-1: CartService::addToCart()

function makeProduct(array $attrs = []): Product
{
    return Product::create(array_merge([
        'title'          => 'Test Product',
        'price'          => 100.00,
        'stock_quantity' => 10,
        'sku'            => 'SKU-' . uniqid(),
        'slug'           => 'test-product-' . uniqid(),
        'status'         => 'active',
        'has_variants'   => false,
        'discount'       => false,
    ], $attrs));
}

function makeVariant(int $productId, array $attrs = []): ProductVariant
{
    return ProductVariant::create(array_merge([
        'product_id'     => $productId,
        'price'          => 120.00,
        'stock_quantity' => 5,
        'sku'            => 'VAR-' . uniqid(),
        'status'         => 'active',
    ], $attrs));
}

beforeEach(function () {
    Session::flush();
    $this->service = new CartService();
});

test('addToCart adds product to session cart for guest', function () {
    $product = makeProduct();

    $result = $this->service->addToCart([
        'product_id' => $product->id,
        'qty'        => 2,
    ]);

    expect($result['success'])->toBeTrue();
    expect($result['cart_count'])->toBe(2);
    expect(Session::get('cart'))->toHaveKey($product->id . ':0');
});

test('addToCart fails when requested qty exceeds available stock', function () {
    $product = makeProduct(['stock_quantity' => 3]);

    $result = $this->service->addToCart([
        'product_id' => $product->id,
        'qty'        => 5,
    ]);

    expect($result['success'])->toBeFalse();
    expect($result['message'])->toContain('stock');
});

test('addToCart fails when variant does not belong to the product', function () {
    $product = makeProduct(['has_variants' => true]);
    $other   = makeProduct();
    $variant = makeVariant($other->id);

    $result = $this->service->addToCart([
        'product_id' => $product->id,
        'variant_id' => $variant->id,
        'qty'        => 1,
    ]);

    expect($result['success'])->toBeFalse();
    expect($result['message'])->toContain('not available');
});

test('addToCart fails when variant is inactive', function () {
    $product = makeProduct(['has_variants' => true]);
    $variant = makeVariant($product->id, ['status' => 'inactive']);

    $result = $this->service->addToCart([
        'product_id' => $product->id,
        'variant_id' => $variant->id,
        'qty'        => 1,
    ]);

    expect($result['success'])->toBeFalse();
});

test('addToCart increments qty when same item already in session cart', function () {
    $product = makeProduct(['stock_quantity' => 10]);

    $this->service->addToCart(['product_id' => $product->id, 'qty' => 2]);
    $result = $this->service->addToCart(['product_id' => $product->id, 'qty' => 3]);

    expect($result['success'])->toBeTrue();
    expect($result['cart_count'])->toBe(5);
});

test('addToCart uses variant stock when variant_id is provided', function () {
    $product = makeProduct(['has_variants' => true, 'stock_quantity' => 100]);
    $variant = makeVariant($product->id, ['stock_quantity' => 2]);

    $result = $this->service->addToCart([
        'product_id' => $product->id,
        'variant_id' => $variant->id,
        'qty'        => 3,
    ]);

    expect($result['success'])->toBeFalse();
    expect($result['message'])->toContain('stock');
});
