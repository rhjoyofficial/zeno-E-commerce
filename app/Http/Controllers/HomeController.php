<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductCart;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Get authenticated user
        $user = Auth::check() ? [
            'id' => Auth::user()->id,
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
        ] : null;

        $products = Product::with(['primaryImage', 'category', 'tags'])
            ->active()
            ->latest()
            ->get()
            ->map(function ($product) {                
                return [
                    'id'            => $product->id,
                    'image'         => $product->image_path,
                    'title'         => $product->title,
                    'price'         => $product->price,
                    'discountPrice'=> $product->discount_price ? $product->discount_price : null,
                    'badge'         => $product->discount ? 'Sale' : 'New',
                    'stock'         => $product->stock_quantity > 0,
                    'categories'    => $product->category ? [$product->category->categoryName] : [],
                    'tags'          => $product->tags->pluck('tag')->toArray(),
                    'avg_rating'    => $product->avg_rating,
                    'slug'          => $product->slug,
                ];
            });


        // Pass categories for filtering if you want
        $categories = Category::all();
        // dd($products, $categories, $user);
        return view('home', compact('products', 'categories', 'user'));
    }

    public function aboutUs()
    {
        $fashionCategories = [
            [
                'title' => 'Premium in Feel, Not in Price.We craft every piece in our clothing line to look and feel premium—from the cut and fabric to the details that elevate your everyday wear. But we price with purpose: so that every student, creator, and dreamer in Bangladesh can wear style with pride.',
                'image' => asset('images/fashion1.jpg'),
            ],
            [
                'title' => 'Built for 2025 and Beyond.We craft every piece in our clothing line to look and feel premium—from the cut and fabric to the details that elevate your everyday wear. But we price with purpose: so that every student, creator, and dreamer in Bangladesh can wear style with pride.',
                'image' => asset('images/fashion2.jpg'),
            ],
            [
                'title' => 'Premium in Feel, Not in Price.We craft every piece in our clothing line to look and feel premium—from the cut and fabric to the details that elevate your everyday wear. But we price with purpose: so that every student, creator, and dreamer in Bangladesh can wear style with pride.',
                'image' => asset('images/women.jpg'),
            ],
            [
                'title' => 'Built for 2025 and Beyond.We craft every piece in our clothing line to look and feel premium—from the cut and fabric to the details that elevate your everyday wear. But we price with purpose: so that every student, creator, and dreamer in Bangladesh can wear style with pride.',
                'image' => asset('images/watch.jpg'),
            ],
        ];

        return view('frontend.about-us', compact('fashionCategories'));
    }
    public function contactUs()
    {
        return view('frontend.contact-us');
    }
    public function sendContactMessage(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // Here you would typically send the message via email or store it in the database
        // For demonstration, we'll just return a success message

        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
}
