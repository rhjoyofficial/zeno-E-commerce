<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\HomeSectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function __construct(private HomeSectionService $homeSectionService) {}

    public function index(Request $request)
    {
        $user = Auth::check() ? [
            'id'    => Auth::user()->id,
            'name'  => Auth::user()->name,
            'email' => Auth::user()->email,
        ] : null;

        $homeSections = $this->homeSectionService->getActiveSections();

        $sectionProducts = [];
        foreach ($homeSections as $section) {
            $rawProducts = $this->homeSectionService->getProductsForSection($section, 8);

            $sectionProducts[$section->id] = $this->formatForCard($rawProducts);
        }

        $topCategories = Category::whereNull('parent_id')->active()->get();

        return view('home', compact('user', 'homeSections', 'sectionProducts', 'topCategories'));
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000',
        ]);
        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }

    private function formatForCard($products): array
    {
        return collect($products)->map(fn($product) => [
            'id'            => $product->id,
            'image'         => $product->primaryImage?->image_path ?? 'images/products/default.jpg',
            'title'         => $product->title,
            'description'   => $product->short_description ?? '',
            'price'         => $product->price,
            'discountPrice' => $product->discount_price ?: null,
            'badge'         => $product->discount_price ? 'Sale' : 'New',
            'stock'         => $product->stock_quantity > 0,
            'hasVariants'   => (bool) $product->has_variants,
            'categories'    => $product->category ? [$product->category->category_name] : [],
            'images'        => ($product->images ?? collect())->map(fn($img) => [
                'path'       => Storage::url($img->image_path),
                'is_primary' => (bool) $img->is_primary,
            ])->values()->all(),
            'variants'      => $product->has_variants
                ? ($product->activeVariants ?? collect())->map(fn($v) => [
                    'id'             => $v->id,
                    'color_id'       => $v->color_id,
                    'color_name'     => $v->color?->name,
                    'color_hex'      => $v->color?->hex_code,
                    'size_id'        => $v->size_id,
                    'size_name'      => $v->size?->name,
                    'price'          => $v->price,
                    'stock_quantity' => $v->stock_quantity,
                ])->values()->all()
                : [],
        ])->all();
    }
}
