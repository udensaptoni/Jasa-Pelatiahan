<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Review;

class ReviewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_approved_reviews_are_visible_on_product_page()
    {
        $product = Product::create(["name" => "Test Prod", "description" => "Desc", "price" => 10000]);

        // approved review
        Review::create(["product_id" => $product->id, "name" => "A", "message" => "Good", "rating" => 5, "approved" => true]);

        // unapproved review
        Review::create(["product_id" => $product->id, "name" => "B", "message" => "Spam", "rating" => 1, "approved" => false]);

        $response = $this->get(route('produk.detail', $product->id));
        $response->assertStatus(200);
        $response->assertSee('Good');
        $response->assertDontSee('Spam');
    }
}
