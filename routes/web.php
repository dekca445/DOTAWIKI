<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HeroController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GameInfoController;
// use App\Http\Controllers\MetaControllerController;
use App\Models\Hero;

/*
|--------------------------------------------------------------------------
| Public Routes (Bisa diakses tanpa login)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Pastikan ini ada agar menu Heroes dan Items berfungsi
Route::get('/heroes', [HeroController::class, 'index'])->name('heroes.index');
Route::get('/hero/{id}', [HeroController::class, 'show'])->name('hero.show');
Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');
Route::get('/patch/{version}', [GameInfoController::class, 'showPatch'])->name('patch.show');
Route::get('/match/{match_id}', [GameInfoController::class, 'showMatch'])->name('match.show');
// Route::get('/meta', [MetaController::class, 'index'])->name('meta.index');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Harus Login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    // Di dalam Route::middleware('auth')->group(function () { ...
Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');


  // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // COMMUNITY & GUIDES (Halaman Kategori Panduan)
    Route::get('/community', [CommunityController::class, 'index'])->name('community.index');
    Route::get('/community/newbie-guide', [CommunityController::class, 'newbie'])->name('community.newbie');
    Route::get('/community/hero-guide', [CommunityController::class, 'heroGuide'])->name('community.hero');
    Route::get('/community/item-guide', [CommunityController::class, 'itemGuide'])->name('community.item');

    // POSTING (Support Media Gambar & Video)
    Route::post('/community/post', [CommunityController::class, 'store'])->name('community.store');

    // INTERAKSI (Like & Comment)
    Route::post('/post/{post}/like', [CommunityController::class, 'toggleLike'])->name('post.like');
    Route::post('/post/{post}/comment', [CommunityController::class, 'storeComment'])->name('post.comment');

    // FOLLOW SYSTEM
    Route::post('/follow/{user}', [CommunityController::class, 'follow'])->name('community.follow');
    Route::post('/unfollow/{user}', [CommunityController::class, 'unfollow'])->name('community.unfollow');
});

require __DIR__.'/auth.php';