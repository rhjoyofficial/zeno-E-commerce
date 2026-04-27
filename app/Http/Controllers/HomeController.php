<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\HomeSectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $sectionProducts[$section->id] = $this->homeSectionService->getProductsForSection($section);
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
