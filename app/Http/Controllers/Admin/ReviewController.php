<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        // show newest first
        $reviews = Review::with('product')->latest()->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->approved = true;
        $review->save();
        return redirect()->back()->with('success', 'Ulasan disetujui');
    }

    public function reject(Review $review)
    {
        $review->delete();
        return redirect()->back()->with('success', 'Ulasan ditolak dan dihapus');
    }
}
