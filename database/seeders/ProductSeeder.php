<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Pelatihan Web Dasar', 'description' => 'Belajar HTML, CSS, dan dasar JavaScript dalam 2 hari intensif.', 'price' => 250000],
            ['name' => 'Pelatihan Laravel Lanjutan', 'description' => 'Mendalami framework Laravel, Eloquent, dan testing.', 'price' => 450000],
            ['name' => 'Desain UI/UX untuk Pemula', 'description' => 'Prinsip desain, Figma, dan prototyping.', 'price' => 300000],
            ['name' => 'Kursus Digital Marketing', 'description' => 'SEO, SEM, dan strategi konten untuk bisnis.', 'price' => 350000],
            ['name' => 'Keterampilan Presentasi Profesional', 'description' => 'Meningkatkan kemampuan berbicara di depan umum dan pitching.', 'price' => 200000],
        ];

        $images = ['products/placeholder1.svg', 'products/placeholder2.svg', 'products/placeholder3.svg', null, null];
        foreach (array_values($items) as $idx => $i) {
            $i['image'] = $images[$idx] ?? null;
            Product::create($i);
        }
    }
}
