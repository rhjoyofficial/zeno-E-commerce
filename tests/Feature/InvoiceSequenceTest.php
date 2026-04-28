<?php

use App\Models\Product;
use App\Models\ProductCart;
use App\Models\Role;
use App\Models\User;
use App\Services\CheckoutService;
use Illuminate\Support\Facades\DB;

// G-13: Database test: invoice_number uniqueness under simulated concurrent orders

function seqUser(): User
{
    $role = Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
    return User::factory()->create(['role_id' => $role->id, 'status' => 'active']);
}

function seqProduct(): Product
{
    return Product::create([
        'title'          => 'Seq Product',
        'price'          => 50.00,
        'stock_quantity' => 100,
        'sku'            => 'SQ-' . uniqid(),
        'slug'           => 'sq-' . uniqid(),
        'status'         => 'active',
        'has_variants'   => false,
        'discount'       => false,
    ]);
}

function placeOrder(User $user, Product $product, int $qty = 1): array
{
    $cartItem = ProductCart::create([
        'user_id'    => $user->id,
        'product_id' => $product->id,
        'variant_id' => null,
        'qty'        => $qty,
        'price'      => $product->price,
    ]);

    session(['checkout' => []]);

    return (new CheckoutService())->processOrder(
        validatedData: [
            'full_name' => 'Seq Customer',
            'phone'     => '01700000000',
            'address'   => '1 Sequence St',
            'city'      => 'Dhaka',
            'payment'   => 'cod',
        ],
        selectedIds: [$cartItem->id],
        customerIp:  '127.0.0.1',
        sessionId:   null,
    );
}

test('sequential orders receive incrementing invoice numbers', function () {
    DB::table('sequences')->updateOrInsert(['name' => 'invoice_number'], ['value' => 0]);

    $product = seqProduct();
    $user1   = seqUser();
    $user2   = seqUser();

    $this->actingAs($user1);
    $result1 = placeOrder($user1, $product);

    $this->actingAs($user2);
    $result2 = placeOrder($user2, $product);

    expect($result1['order']->invoice_number)->toBe(1);
    expect($result2['order']->invoice_number)->toBe(2);
});

test('all invoice numbers are unique across multiple orders', function () {
    DB::table('sequences')->updateOrInsert(['name' => 'invoice_number'], ['value' => 0]);

    $product = seqProduct();
    $invoices = [];

    for ($i = 0; $i < 5; $i++) {
        $user = seqUser();
        $this->actingAs($user);
        $result    = placeOrder($user, $product);
        $invoices[] = $result['order']->invoice_number;
    }

    expect(array_unique($invoices))->toHaveCount(5);
    expect($invoices)->toBe([1, 2, 3, 4, 5]);
});

test('invoice numbers pick up from existing sequence value', function () {
    DB::table('sequences')->updateOrInsert(['name' => 'invoice_number'], ['value' => 99]);

    $product = seqProduct();
    $user    = seqUser();

    $this->actingAs($user);
    $result = placeOrder($user, $product);

    expect($result['order']->invoice_number)->toBe(100);
});
