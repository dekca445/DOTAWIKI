<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class Hero extends Model
{
    use HasFactory; 
    protected $guarded = ['id']; 

    protected $casts = [
        'roles' => 'array',
        'item_builds' => 'array',
        'pros' => 'array',
        'cons' => 'array',
    ];

    public function abilities() {
        return $this->hasMany(Ability::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}