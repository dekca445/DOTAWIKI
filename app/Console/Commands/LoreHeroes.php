<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\Hero;

class LoreHeroes extends Command
{
    protected $signature = 'dota:lore-heroes';
    protected $description = 'Inject Lore from local JSON to existing Heroes';

    public function handle()
    {
        $this->info('📖 Membaca Lore dari hero_lore.json...');

        // 1. Cek File
        $path = storage_path('app/hero_lore.json');
        if (!File::exists($path)) {
            $this->error('❌ File hero_lore.json tidak ditemukan di storage/app/!');
            return;
        }

        // 2. Decode JSON
        $loreData = json_decode(File::get($path), true);
        
        // 3. Ambil Semua Hero
        $heroes = Hero::all();
        $bar = $this->output->createProgressBar($heroes->count());
        $bar->start();

        foreach ($heroes as $hero) {
            // DATA BASE: npc_dota_hero_antimage
            // JSON KEY: antimage
            // FIX: Kita buang "npc_dota_hero_" agar cocok
            $shortName = str_replace('npc_dota_hero_', '', $hero->code_name);

            // Cek apakah ada lore untuk nama pendek tersebut
            if (isset($loreData[$shortName])) {
                // Update kolom 'lore' (sesuai tabel heroes kamu)
                $hero->update([
                    'lore' => $loreData[$shortName]
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('✅ Lore Hero berhasil disuntikkan!');
    }
}