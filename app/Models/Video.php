<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'thumbnail_path', 'category_id']; // Kolom yang bisa diisi

    // Relasi: Satu video milik satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi: Satu video bisa punya banyak part
    public function videoParts()
    {
        return $this->hasMany(VideoPart::class);
    }
}
