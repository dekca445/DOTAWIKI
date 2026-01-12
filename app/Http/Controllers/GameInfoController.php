<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use App\Models\Hero;
use App\Models\Item;

class GameInfoController extends Controller
{
    public function showPatch($version)
    {
        $path = storage_path('app/patchnotes.json');
        
        if (!File::exists($path)) {
            abort(404, 'Database Error: File patchnotes.json tidak ditemukan di storage/app/.');
        }

        $allPatches = json_decode(File::get($path), true);
        
        $patchData = $allPatches[$version] ?? null;

        if (!$patchData) {
            $underscoreVersion = str_replace('.', '_', $version);
            $patchData = $allPatches[$underscoreVersion] ?? null;
            
            if ($patchData) {
            }
        }

        if (!$patchData) {
            $cleanVersion = preg_replace('/[a-z]/i', '', $version);
            
            if (isset($allPatches[$cleanVersion])) {
                $patchData = $allPatches[$cleanVersion];
                $version = $cleanVersion;
            } 
            else {
                $cleanUnderscore = str_replace('.', '_', $cleanVersion);
                if (isset($allPatches[$cleanUnderscore])) {
                    $patchData = $allPatches[$cleanUnderscore];
                    $version = $cleanVersion;
                }
            }
        }

        if (!$patchData) {
            abort(404, "Patch Note versi '$version' (atau variasinya) tidak ditemukan di database.");
        }

        return view('game.patch', compact('patchData', 'version'));
    }

    public function showMatch($match_id)
    {
        $response = Http::get("https://api.opendota.com/api/matches/{$match_id}");

        if ($response->failed()) {
            abort(404, 'Match data not found on OpenDota.');
        }

        $match = $response->json();
        
        $heroes = Hero::all()->keyBy('hero_id'); 

        return view('game.match', compact('match', 'heroes'));
    }
}