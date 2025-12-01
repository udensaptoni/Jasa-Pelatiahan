<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // =================== ADMIN ===================
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        $path = $request->file('image')?->store('products', 'public');

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $path,
        ]);

    return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        $path = $product->image;
        if ($request->hasFile('image')) {
            if ($path) Storage::disk('public')->delete($path);
            $path = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $path,
        ]);

    return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        if ($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();
    return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus');
    }

    // =================== FRONTEND DETAIL PRODUK ===================
    /**
     * Menampilkan halaman detail produk + rekomendasi produk lain
     */
    public function show($id)
    {
        // Load product with only approved reviews and compute rating stats
        $product = Product::with(['reviews' => function ($q) {
            $q->where('approved', true)->latest();
        }])
        ->withCount(['reviews as approved_reviews_count' => function ($q) {
            $q->where('approved', true);
        }])
        ->withAvg(['reviews as average_rating' => function ($q) {
            $q->where('approved', true);
        }], 'rating')
        ->findOrFail($id);

        // Ambil produk lain sebagai rekomendasi (tidak termasuk yang sedang dilihat)
        $relatedProducts = Product::where('id', '!=', $id)->take(6)->get();

        // Normalize average rating to a float (could be null)
        $averageRating = $product->average_rating ? round($product->average_rating, 2) : 0;
        $reviewCount = $product->approved_reviews_count ?? 0;

        return view('produk-detail', compact('product', 'relatedProducts', 'averageRating', 'reviewCount'));
    }
}
