<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'category',
        'image',
        'video',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function comments() { return $this->hasMany(Comment::class)->latest(); }
    public function likes() { return $this->hasMany(Like::class); }

    public function isLikedByAuthUser()
    {
        if (!Auth::check()) return false;
        return $this->likes()->where('user_id', Auth::id())->exists();
    }
}