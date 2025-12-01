<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['nama' => 'Agus', 'pesan' => 'Pelatihan sangat membantu karier saya.', 'status' => true],
            ['nama' => 'Sari', 'pesan' => 'Materinya lengkap dan mudah diikuti.', 'status' => true],
            ['nama' => 'Anon', 'pesan' => 'Mantap bro!', 'status' => false],
        ];

        foreach ($items as $i) {
            Testimonial::create($i);
        }
    }
}
