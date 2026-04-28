<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductWish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function getWishList()
    {
        $wishes = ProductWish::where('user_id', Auth::id())
            ->with(['product.primaryImage'])
            ->latest()
            ->get();

        return view('customer.wishlist', compact('wishes'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $wish          = ProductWish::firstOrCreate(['user_id' => Auth::id(), 'product_id' => $request->product_id]);
        $alreadyExists = !$wish->wasRecentlyCreated;

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $alreadyExists ? 'Already in wishlist.' : 'Added to wishlist.',
            ]);
        }

        return redirect()->back()->with('success', 'Added to wishlist.');
    }

    public function remove(Product $product)
    {
        ProductWish::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Removed from wishlist.']);
        }

        return redirect()->route('customer.wishlist')->with('success', 'Removed from wishlist.');
    }
}
