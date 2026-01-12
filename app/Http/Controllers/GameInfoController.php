<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use App\Models\Hero;
use App\Models\Item;

class GameInfoController extends Controller
{
    // 1. HALAMAN PATCH NOTE DETAIL
    public function showPatch($version)
    {
        // Path ke file JSON
        $path = storage_path('app/patchnotes.json');
        
        if (!File::exists($path)) {
            abort(404, 'Database Error: File patchnotes.json tidak ditemukan di storage/app/.');
        }

        $allPatches = json_decode(File::get($path), true);
        
        // --- LOGIKA PENCARIAN CERDAS (SMART LOOKUP) ---
        
        // OPSI 1: Cari persis sesuai request (Contoh: "7.37e")
        $patchData = $allPatches[$version] ?? null;

        // OPSI 2: Cari dengan format Underscore (Contoh: "7.40" -> "7_40")
        // Ini solusi untuk masalah yang kamu temukan tadi!
        if (!$patchData) {
            $underscoreVersion = str_replace('.', '_', $version);
            $patchData = $allPatches[$underscoreVersion] ?? null;
            
            // Jika ketemu, update $version agar tampilan di layar pakai format underscore (atau tetap titik, opsional)
            if ($patchData) {
                // Kita biarkan $version tetap pakai titik untuk judul, tapi datanya sudah ketemu.
            }
        }

        // OPSI 3: Cari versi utama (Contoh: "7.37e" -> "7.37" atau "7_37")
        if (!$patchData) {
            // Hapus huruf di belakang (7.37e -> 7.37)
            $cleanVersion = preg_replace('/[a-z]/i', '', $version);
            
            // Cek format titik ("7.37")
            if (isset($allPatches[$cleanVersion])) {
                $patchData = $allPatches[$cleanVersion];
                $version = $cleanVersion;
            } 
            // Cek format underscore ("7_37")
            else {
                $cleanUnderscore = str_replace('.', '_', $cleanVersion);
                if (isset($allPatches[$cleanUnderscore])) {
                    $patchData = $allPatches[$cleanUnderscore];
                    $version = $cleanVersion;
                }
            }
        }

        // Jika tetap tidak ketemu, baru tampilkan 404
        if (!$patchData) {
            abort(404, "Patch Note versi '$version' (atau variasinya) tidak ditemukan di database.");
        }

        return view('game.patch', compact('patchData', 'version'));
    }

    // 2. HALAMAN MATCH DETAIL (ESPORTS)
    public function showMatch($match_id)
    {
        $response = Http::get("https://api.opendota.com/api/matches/{$match_id}");

        if ($response->failed()) {
            abort(404, 'Match data not found on OpenDota.');
        }

        $match = $response->json();
        
        // Ambil data hero dari DB lokal untuk icon
        $heroes = Hero::all()->keyBy('hero_id'); 

        return view('game.match', compact('match', 'heroes'));
    }
}