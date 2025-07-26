<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */

    protected $fillable = ['judul', 'penulis', 'tahun_terbit', 'jumlah_halaman', 'image', 'category_id'];
    use HasFactory;
    protected $appends = ['image_url'];
    // Define the relationship with category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }
}
