<?php

use App\Models\Product;
use App\Models\ProductVariant;

// G-5: Product::getFinalPriceAttribute()
test('product final_price returns base price when discount flag is false', function () {
    $product = new Product(['price' => 100.00, 'discount' => false, 'discount_price' => 80.00]);

    expect((float) $product->final_price)->toEqual(100.00);
});

test('product final_price returns base price when discount_price is null', function () {
    $product = new Product(['price' => 100.00, 'discount' => true, 'discount_price' => null]);

    expect((float) $product->final_price)->toEqual(100.00);
});

test('product final_price returns discount_price when discount is true and discount_price is set', function () {
    $product = new Product(['price' => 100.00, 'discount' => true, 'discount_price' => 75.00]);

    expect((float) $product->final_price)->toEqual(75.00);
});

// G-6: ProductVariant::getFinalPriceAttribute()
test('variant final_price returns base price when discount_price is null', function () {
    $variant = new ProductVariant(['price' => 150.00, 'discount_price' => null]);

    expect((float) $variant->final_price)->toEqual(150.00);
});

test('variant final_price returns zero when discount_price is explicitly zero', function () {
    $variant = new ProductVariant(['price' => 150.00, 'discount_price' => 0.00]);

    // null-coalescing: 0.00 cast to '0.00' is not null, so it wins
    expect((float) $variant->final_price)->toEqual(0.00);
});

test('variant final_price returns discount_price when set', function () {
    $variant = new ProductVariant(['price' => 150.00, 'discount_price' => 120.00]);

    expect((float) $variant->final_price)->toEqual(120.00);
});
