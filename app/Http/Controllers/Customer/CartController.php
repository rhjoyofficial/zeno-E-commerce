<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CartController extends Controller
{
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

        $product = Product::findOrFail($request->product_id);
        // Initialize variables 
        $price = $product->price;
        $size_id = null;
        $color_id = null;
        if ($request->variant_id) {
            $variant = ProductVariant::findOrFail($request->variant_id);
            $price = $variant->price;
            $size_id = $variant->size_id;
            $color_id = $variant->color_id;
        }

        $request->merge(['price' => $price, 'size_id' => $size_id, 'color_id' => $color_id]);

        if (Auth::check()) {
            return $this->addToDatabaseCart($request);
        }

        return $this->addToSessionCart($request);
    }

    /**
     * Store product in database cart.
     */
    protected function addToDatabaseCart(Request $request)
    {
        $userId = Auth::id();
        // Duplicate check
        $existingItem = ProductCart::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->where('variant_id', $request->variant_id)
            ->first();

        // Update quantity if item exists 
        if ($existingItem) {
            $existingItem->qty += $request->qty;
            $existingItem->save();
            // return $existingItem;
        } else {
            ProductCart::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'variant_id' => $request->variant_id,
                'color' => $request->input('color_id'),
                'size' => $request->input('size_id'),
                'qty' => $request->qty,
                'price' => $request->input('price')
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => $this->getCartCount()
        ]);
    }

    /**
     * Store product in session cart.
     */
    protected function addToSessionCart(Request $request)
    {
        $cart = Session::get('cart', []);

        $uniqueId = md5($request->product_id . '_' . ($request->variant_id ?? '0'));
        if (isset($cart[$uniqueId])) {
            $cart[$uniqueId]['qty'] += $request->qty;
        } else {
            $cart[$uniqueId] = [
                'uniqueId'   => $uniqueId,
                'product_id' => $request->product_id,
                'variant_id' => $request->variant_id,
                'color' => $request->input('color_id'),
                'size' => $request->input('size_id'),
                'qty' => $request->qty,
                'price' => $request->input('price')
            ];
        }

        Session::put('cart', $cart);
        Session::save();

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => $this->getCartCount()
        ]);
    }

    /**
     * Show cart items (DB for Auth, Session for Guest).
     */
    public function index()
    {
        $cartItems = [];
        $totalItems = 0;
        if (Auth::check()) {
            $cartItems = ProductCart::with(['product', 'variant'])
                ->where('user_id', Auth::id())
                ->get();

            $totalItems = $cartItems->sum('qty');
        } else {
            $cart = Session::get('cart', []);
            $cartItems = collect();
            // dd($cart);
            foreach ($cart as $item) {
                $product = Product::find($item['product_id']);
                $variant = $item['variant_id'] ? ProductVariant::find($item['variant_id']) : null;

                $cartItems->push((object) [
                    'id'       => $item['uniqueId'],
                    'product_id' => $item['product_id'],
                    'product'  => $product,
                    'variant'  => $variant,
                    'variant_id'  => $item['variant_id'],
                    'qty' => $item['qty'],
                ]);
                $totalItems += $item['qty'];
            }
        }

        return view('customer.cart-item', compact('cartItems', 'totalItems'));
    }

    /**
     * Update qty of a cart item.
     */
    public function update(Request $request, $id)
    {
        $request->validate(['qty' => 'required|integer|min:1']);

        if (Auth::check()) {
            $cartItem = ProductCart::where('user_id', Auth::id())->findOrFail($id);
            $cartItem->update(['qty' => $request->qty]);
        } else {
            $cart = Session::get('cart', []);
            if (isset($cart[$id])) {
                $cart[$id]['qty'] = $request->qty;
                Session::put('cart', $cart);
                Session::save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
            'cart_count' => $this->getCartCount(),
        ]);
    }

    /**
     * Remove item from cart.
     */
    public function remove($id)
    {
        if (Auth::check()) {
            $cartItem = ProductCart::where('user_id', Auth::id())->findOrFail($id);
            $cartItem->delete();
        } else {
            $cart = Session::get('cart', []);
            if (isset($cart[$id])) {
                unset($cart[$id]);
                empty($cart) ? Session::forget('cart') : Session::put('cart', $cart);
                Session::save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart successfully!',
            'cart_count' => $this->getCartCount()
        ]);
    }

    /**
     * Sync session cart to DB after login.
     */
    public function syncCart()
    {
        if (!Auth::check() || !Session::has('cart')) {
            return;
        }

        $cart = Session::get('cart', []);
        DB::transaction(function () use ($cart) {
            foreach ($cart as $item) {
                $existingItem = ProductCart::where([
                    'user_id'    => Auth::id(),
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'],
                ])->first();

                if ($existingItem) {
                    $existingItem->increment('qty', $item['qty']);
                } else {
                    ProductCart::create([
                        'user_id'    => Auth::id(),
                        'product_id' => $item['product_id'],
                        'variant_id' => $item['variant_id'] ?? null,
                        'qty'        => $item['qty'],
                        'color'      => $item['color_id'] ?? null,
                        'size'       => $item['size_id'] ?? null,
                        'price'      => $item['price'],
                    ]);
                }
            }
        });

        Session::forget('cart');
        // return response()->json(['success' => true, 'cart_count' => $this->getCartCount()]);
    }

    public function getVariantPrice(Request $request)
    {
        $productId = $request->product_id;
        $colorId   = $request->color_id;
        $sizeId    = $request->size_id;

        $variant = ProductVariant::with(['size', 'color'])
            ->where('product_id', $productId)
            ->when($colorId, fn($q) => $q->where('color_id', $colorId))
            ->when($sizeId, fn($q) => $q->where('size_id', $sizeId))
            ->first();

        if (!$variant) {
            return response()->json(['error' => 'Variant not found'], 404);
        }

        return response()->json([
            'id'             => $variant->id,
            'price'          => $variant->price,
            'stock_quantity' => $variant->stock_quantity,
            'sku'            => $variant->sku,
            'color'          => $variant->color?->name,
            'size'           => $variant->size?->name,
        ]);
    }

    public function getCartCount()
    {
        if (Auth::check()) {
            return ProductCart::where('user_id', Auth::id())->sum('qty');
        }

        $cart = Session::get('cart', []);
        $count = 0;
        foreach ($cart as $item) {
            $count += $item['qty'];
        }
        if ($count == 0 && empty($cart)) {
            Session::forget('cart');
        }

        return $count;
    }

    public static function getCartCountStatic()
    {
        if (Auth::check()) {
            return ProductCart::with(['product' => function ($q) {
                $q->where('status', 'active');
            }])->where('user_id', Auth::id())->sum('qty');
        }

        $cart = Session::get('cart', []);
        $count = 0;
        foreach ($cart as $item) {
            $count += $item['qty'];
        }
        return $count;
    }
}
