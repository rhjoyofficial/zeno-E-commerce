<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Models\Order;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Add product to cart (DB or Session based on Auth).
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'qty'   => 'required|integer|min:1',
        ]);

        $response = $this->cartService->addToCart($request->all());

        return response()->json($response);
    }

    /**
     * Show cart items (DB for Auth, Session for Guest).
     */
    public function index()
    {
        $data = $this->cartService->getCartItems();
        $cartItems = $data['cartItems'];
        $totalItems = $data['totalItems'];

        return view('customer.cart-item', compact('cartItems', 'totalItems'));
    }

    /**
     * Update qty of a cart item.
     */
    public function update(Request $request, $id)
    {
        $request->validate(['qty' => 'required|integer|min:1']);

        $response = $this->cartService->updateCart($id, $request->qty);

        return response()->json($response);
    }

    /**
     * Remove item from cart.
     */
    public function remove($id)
    {
        $response = $this->cartService->removeCart($id);

        return response()->json($response);
    }

    /**
     * Sync session cart to DB after login.
     */
    public function syncCart()
    {
        $this->cartService->syncCart();
    }

    public function getVariantPrice(Request $request)
    {
        $variantData = $this->cartService->getVariantPrice(
            $request->product_id,
            $request->color_id,
            $request->size_id
        );

        if (!$variantData) {
            return response()->json(['error' => 'Variant not found'], 404);
        }

        return response()->json($variantData);
    }

    public function getCartCount()
    {
        return $this->cartService->getCartCount();
    }

    public static function getCartCountStatic()
    {
        // Using dependency injection static workaround or new instance since this is static
        return app(CartService::class)->getCartCount();
    }
}
