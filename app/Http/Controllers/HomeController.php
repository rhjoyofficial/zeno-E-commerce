<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ContactInquiry;
use App\Services\HomeSectionService;
use App\Services\ProductCardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct(
        private HomeSectionService $homeSectionService,
        private ProductCardService $productCardService,
    ) {}

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

            $sectionProducts[$section->id] = $this->productCardService->format($rawProducts);
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
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'message' => 'required|string|max:1000',
        ]);

        ContactInquiry::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'message'    => $request->message,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }

}
