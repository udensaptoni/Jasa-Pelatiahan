<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutUs;

class AboutUsSeeder extends Seeder
{
    public function run(): void
    {
        AboutUs::updateOrCreate([
            'id' => 1
        ], [
            'title' => 'Tentang Kami',
            'content' => 'Jasa Pelatihan adalah penyedia kursus profesional yang berfokus pada keterampilan praktis: pemrograman, desain, dan pemasaran digital. Misi kami adalah membantu peserta memenangkan kesempatan karir melalui pembelajaran yang aplikatif dan mentor berpengalaman.'
        ]);
    }
}
