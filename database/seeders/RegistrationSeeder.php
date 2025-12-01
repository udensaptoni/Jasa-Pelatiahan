<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Registration;
use App\Models\Product;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $product = Product::first();
        if (!$product) return;

        Registration::create([
            'product_id' => $product->id,
            'nama' => 'Calon Peserta',
            'email' => 'peserta@example.com',
            'telepon' => '08123456789',
            'catatan' => 'Ingin ikut sesi malam',
        ]);
    }
}
