<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hero;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $heroes = Hero::inRandomOrder()->limit(12)->get();
        
        $topWinrate = Hero::where('pro_pick', '>', 10)->get()
            ->sortByDesc(fn($hero) => $hero->pro_pick > 0 ? ($hero->pro_win / $hero->pro_pick) : 0)
            ->take(5);
            
        $topPicked = Hero::orderByDesc('pro_pick')->take(5)->get();

        $latestPatch = null;
        $patchPath = storage_path('app/patch.json'); 
        
        if (File::exists($patchPath)) {
            $patches = json_decode(File::get($patchPath), true);
            if (is_array($patches) && count($patches) > 0) {
                $latestPatch = end($patches); 
            }
        }

        $esportsMatch = Cache::remember('home_esports_match', 300, function () {
            try {
                $response = Http::timeout(2)->get('https://api.opendota.com/api/proMatches');
                if ($response->successful()) {
                    return $response->json()[0] ?? null;
                }
            } catch (\Exception $e) {
                return null;
            }
            return null;
        });

        return view('welcome', compact('heroes', 'topWinrate', 'topPicked', 'latestPatch', 'esportsMatch'));
    }
}