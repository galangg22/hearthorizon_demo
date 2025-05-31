<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoPart extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'video_link', 'thumbnail_path', 'video_id', 'part_number', 'reference_url'];

    // Relasi: Satu part milik satu video
    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
