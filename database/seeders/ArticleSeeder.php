<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['title' => '5 Tips Belajar Pemrograman', 'content' => 'Mulailah dengan dasar, praktik setiap hari, dan bangun proyek kecil.'],
            ['title' => 'Kenapa Laravel Populer?', 'content' => 'Laravel menyediakan ekosistem lengkap yang mempercepat pengembangan aplikasi web.'],
            ['title' => 'Dasar-dasar SEO yang Perlu Anda Ketahui', 'content' => 'Optimasi judul, meta description, dan performa situs.'],
        ];

        foreach ($items as $i) {
            Article::create($i);
        }
    }
}
