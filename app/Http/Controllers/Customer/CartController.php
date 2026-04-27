<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\CartService;
use App\Models\Order;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'qty'        => 'required|integer|min:1',
        ]);

        $response = $this->cartService->addToCart($request->all());

        if ($request->expectsJson()) {
            return response()->json($response);
        }

        if ($response['success']) {
            return redirect()->back()->with('success', $response['message']);
        }

        return redirect()->back()->with('error', $response['message']);
    }

    public function index()
    {
        $data       = $this->cartService->getCartItems();
        $cartItems  = $data['cartItems'];
        $totalItems = $data['totalItems'];

        return view('customer.cart-item', compact('cartItems', 'totalItems'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['qty' => 'required|integer|min:1']);

        $response = $this->cartService->updateCart($id, $request->qty);

        if ($request->expectsJson()) {
            return response()->json($response);
        }

        if ($response['success']) {
            return redirect()->back()->with('success', $response['message']);
        }

        return redirect()->back()->with('error', $response['message']);
    }

    public function remove($id)
    {
        $response = $this->cartService->removeCart($id);

        if (request()->expectsJson()) {
            return response()->json($response);
        }

        return redirect()->back()->with('success', $response['message']);
    }

    public function syncCart()
    {
        $this->cartService->syncCart();

        return redirect()->back();
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

}
