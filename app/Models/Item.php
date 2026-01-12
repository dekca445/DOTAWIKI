<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    // Kita pakai guarded kosong agar SEMUA kolom boleh diisi (Anti Ribet)
    protected $guarded = [];

    // Pastikan kolom JSON otomatis jadi Array saat diambil
    protected $casts = [
        'stats' => 'array',
        'components' => 'array',
        'recipe_cost' => 'boolean',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}