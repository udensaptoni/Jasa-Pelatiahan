<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Article;
use App\Models\AboutUs;
use App\Models\Testimonial;

class FrontendController extends Controller
{
    public function index()
    {
        $products = Product::latest()->take(3)->get();
        $articles = Article::latest()->take(3)->get();
        $about = AboutUs::first();
        $testimonials = Testimonial::where('status', true)->latest()->take(3)->get();

        return view('frontend.home', compact('products', 'articles', 'about', 'testimonials'));
    }

    public function about()
    {
        $about = AboutUs::first();
        return view('frontend.about', compact('about'));
    }
}
