<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;

class ReviewsController extends Controller
{
    public function store(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'message' => 'required|string|max:1000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        $data['product_id'] = $product->id;
    $data['rating'] = $data['rating'] ?? 5;
    // New reviews require admin moderation
    $data['approved'] = false;

        Review::create($data);

        return redirect()->back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
