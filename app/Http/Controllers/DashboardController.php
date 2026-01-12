<?php

namespace App\Http\Controllers;

use App\Models\Post; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $myPosts = Post::where('user_id', Auth::id())
                       ->withCount('likes')
                       ->latest()
                       ->get();

        return view('dashboard', compact('myPosts'));
    }

    public function community()
    {
        return view('community', [
            'posts' => []
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required',
        ]);

        return redirect()->back()->with('success', 'Postingan berhasil dikirim (simulasi)!');
    }
}