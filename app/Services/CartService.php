<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductCart;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CartService
{
    /**
     * Add product to cart.
     */
    public function addToCart(array $data): array
    {
        $product = Product::findOrFail($data['product_id']);
        $price = $product->final_price;

        if (!empty($data['variant_id'])) {
            $variant = ProductVariant::findOrFail($data['variant_id']);

            if ((int) $variant->product_id !== (int) $product->id || $variant->status !== 'active') {
                return ['success' => false, 'message' => 'The selected product variant is not available.'];
            }

            $price = $variant->final_price;
        }

        $data['price'] = $price;

        $stockAvailable = $this->getAvailableStock($data['product_id'], $data['variant_id'] ?? null);
        $currentQty = $this->getCurrentCartQty($data['product_id'], $data['variant_id'] ?? null);
        
        if (($currentQty + $data['qty']) > $stockAvailable) {
            return ['success' => false, 'message' => 'Requested quantity exceeds available stock.'];
        }

        if (Auth::check()) {
            return $this->addToDatabaseCart($data);
        }

        return $this->addToSessionCart($data);
    }

    /**
     * Store product in database cart.
     */
    protected function addToDatabaseCart(array $data): array
    {
        $userId = Auth::id();
        $existingItem = ProductCart::where('user_id', $userId)
            ->where('product_id', $data['product_id'])
            ->where('variant_id', $data['variant_id'] ?? null)
            ->first();

        if ($existingItem) {
            $existingItem->qty += $data['qty'];
            $existingItem->save();
        } else {
            ProductCart::create([
                'user_id'    => $userId,
                'product_id' => $data['product_id'],
                'variant_id' => $data['variant_id'] ?? null,
                'qty'        => $data['qty'],
                'price'      => $data['price'],
            ]);
        }

        return [
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => $this->getCartCount()
        ];
    }

    /**
     * Store product in session cart.
     */
    protected function addToSessionCart(array $data): array
    {
        $cart = Session::get('cart', []);
        $variantId = $data['variant_id'] ?? '0';
        $uniqueId = $data['product_id'] . ':' . $variantId;

        if (isset($cart[$uniqueId])) {
            $cart[$uniqueId]['qty'] += $data['qty'];
        } else {
            $cart[$uniqueId] = [
                'uniqueId'   => $uniqueId,
                'product_id' => $data['product_id'],
                'variant_id' => $data['variant_id'] ?? null,
                'qty'        => $data['qty'],
                'price'      => $data['price'],
            ];
        }

        Session::put('cart', $cart);
        Session::save();

        return [
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => $this->getCartCount()
        ];
    }

    /**
     * Get all cart items.
     */
    public function getCartItems(): array
    {
        $cartItems = collect();
        $totalItems = 0;

        if (Auth::check()) {
            $cartItems = ProductCart::with(['product', 'variant'])
                ->where('user_id', Auth::id())
                ->get();
            $totalItems = $cartItems->sum('qty');
        } else {
            $cart = Session::get('cart', []);
            
            if (!empty($cart)) {
                $productIds = collect($cart)->pluck('product_id')->unique()->toArray();
                $variantIds = collect($cart)->pluck('variant_id')->filter()->unique()->toArray();

                $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
                $variants = !empty($variantIds) ? ProductVariant::whereIn('id', $variantIds)->get()->keyBy('id') : collect();

                foreach ($cart as $item) {
                    $product = $products->get($item['product_id']);
                    $variant = $item['variant_id'] ? $variants->get($item['variant_id']) : null;

                    if ($product) {
                        $cartItems->push((object) [
                            'id'         => $item['uniqueId'],
                            'product_id' => $item['product_id'],
                            'product'    => $product,
                            'variant'    => $variant,
                            'variant_id' => $item['variant_id'],
                            'qty'        => $item['qty'],
                        ]);
                        $totalItems += $item['qty'];
                    }
                }
            }
        }

        return ['cartItems' => $cartItems, 'totalItems' => $totalItems];
    }

    /**
     * Update quantity of a cart item.
     */
    public function updateCart(string $id, int $qty): array
    {
        if (Auth::check()) {
            $cartItem = ProductCart::where('user_id', Auth::id())->findOrFail($id);
            $stockAvailable = $this->getAvailableStock($cartItem->product_id, $cartItem->variant_id);
            if ($qty > $stockAvailable) {
                return ['success' => false, 'message' => 'Requested quantity exceeds available stock.'];
            }
            $cartItem->update(['qty' => $qty]);
        } else {
            $cart = Session::get('cart', []);
            if (isset($cart[$id])) {
                $stockAvailable = $this->getAvailableStock($cart[$id]['product_id'], $cart[$id]['variant_id']);
                if ($qty > $stockAvailable) {
                    return ['success' => false, 'message' => 'Requested quantity exceeds available stock.'];
                }
                $cart[$id]['qty'] = $qty;
                Session::put('cart', $cart);
                Session::save();
            } else {
                return ['success' => false, 'message' => 'Cart item not found.'];
            }
        }

        return [
            'success' => true,
            'message' => 'Cart updated successfully!',
            'cart_count' => $this->getCartCount(),
        ];
    }

    /**
     * Remove item from cart.
     */
    public function removeCart(string $id): array
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

        return [
            'success' => true,
            'message' => 'Product removed from cart successfully!',
            'cart_count' => $this->getCartCount()
        ];
    }

    /**
     * Sync session cart to DB after login.
     */
    public function syncCart(): void
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
                        'price'      => $item['price'],
                    ]);
                }
            }
        });

        Session::forget('cart');
    }

    /**
     * Get variant price.
     */
    public function getVariantPrice(int $productId, ?int $colorId, ?int $sizeId): ?array
    {
        $variant = ProductVariant::with(['size', 'color'])
            ->where('product_id', $productId)
            ->when($colorId, fn($q) => $q->where('color_id', $colorId))
            ->when($sizeId, fn($q) => $q->where('size_id', $sizeId))
            ->first();

        if (!$variant) {
            return null;
        }

        return [
            'id'             => $variant->id,
            'price'          => $variant->final_price,
            'stock_quantity' => $variant->stock_quantity,
            'sku'            => $variant->sku,
            'color'          => $variant->color?->name,
            'size'           => $variant->size?->name,
        ];
    }

    /**
     * Get cart count.
     */
    public function getCartCount(): int
    {
        if (Auth::check()) {
            return (int) ProductCart::where('user_id', Auth::id())->sum('qty');
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

    /**
     * Get available stock for a product or variant.
     */
    protected function getAvailableStock($productId, $variantId = null): int
    {
        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            return $variant ? (int) $variant->stock_quantity : 0;
        }
        $product = Product::find($productId);
        return $product ? (int) $product->stock_quantity : 0;
    }

    /**
     * Get current quantity of a product/variant in the cart.
     */
    protected function getCurrentCartQty($productId, $variantId = null): int
    {
        if (Auth::check()) {
            $item = ProductCart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->where('variant_id', $variantId)
                ->first();
            return $item ? (int) $item->qty : 0;
        }

        $cart = Session::get('cart', []);
        $variantIdStr = $variantId ?? '0';
        $uniqueId = $productId . ':' . $variantIdStr;
        
        return isset($cart[$uniqueId]) ? (int) $cart[$uniqueId]['qty'] : 0;
    }
}
