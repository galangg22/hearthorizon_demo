<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VideoPart;
use App\Models\Video;

class VideoPartSeeder extends Seeder
{
    public function run()
    {
        $video = Video::first(); // Ambil video pertama sebagai contoh

        VideoPart::create([
            'title' => 'Cinta dalam Keluarga',
            'description' => 'Part tentang cinta dalam keluarga.',
            'video_link' => 'link_to_video',
            'thumbnail' => 'path_to_thumbnail',
            'video_id' => $video->id,
        ]);
    }
}
