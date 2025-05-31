<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Video;
use App\Models\Category;

class VideoSeeder extends Seeder
{
    public function run()
    {
        $category = Category::first(); // Ambil kategori pertama sebagai contoh

        Video::create([
            'title' => 'Memahami Arti Cinta Sejati',
            'description' => 'Video pembuka tentang cinta.',
            'thumbnail' => 'path_to_thumbnail',
            'category_id' => $category->id,
        ]);
    }
}
