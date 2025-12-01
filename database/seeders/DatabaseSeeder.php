<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Core seeders for demo content
        $this->call([
            \Database\Seeders\AdminSeeder::class,
            \Database\Seeders\ProductSeeder::class,
            \Database\Seeders\AboutUsSeeder::class,
            \Database\Seeders\ArticleSeeder::class,
            \Database\Seeders\TestimonialSeeder::class,
            \Database\Seeders\ReviewSeeder::class,
            \Database\Seeders\RegistrationSeeder::class,
        ]);
    }
}
