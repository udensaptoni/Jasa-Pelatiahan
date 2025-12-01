<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Product;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        foreach ($products as $product) {
            // create some approved reviews
            Review::create(['product_id' => $product->id, 'name' => 'Budi', 'message' => 'Sangat membantu, materi jelas dan aplikatif.', 'rating' => 5, 'approved' => true]);
            Review::create(['product_id' => $product->id, 'name' => 'Sinta', 'message' => 'Instruktur ramah tapi butuh lebih banyak latihan.', 'rating' => 4, 'approved' => true]);
            // create one pending review
            Review::create(['product_id' => $product->id, 'name' => 'Spammer', 'message' => 'Beli ini sekarang!!!', 'rating' => 1, 'approved' => false]);
        }
    }
}
