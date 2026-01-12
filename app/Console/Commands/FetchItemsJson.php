<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\Item;

class FetchItemsJson extends Command
{
    protected $signature = 'dota:fetch-items-json';
    protected $description = 'Import Items exclusively from local items.json';

    public function handle()
    {
        $this->info('📦 Membaca items.json (Mode Bersih)...');

        $path = storage_path('app/items.json');
        if (!File::exists($path)) {
            $this->error('❌ File items.json tidak ditemukan di storage/app/!');
            return;
        }

        $itemsData = json_decode(File::get($path), true);
        $bar = $this->output->createProgressBar(count($itemsData));
        $bar->start();

        foreach ($itemsData as $key => $data) {
            // Skip item yang tidak valid (tanpa nama/gambar)
            if (empty($data['dname']) || empty($data['img'])) continue;

            // 1. OLAH STATS (Flatten Array)
            $formattedStats = [];
            if (isset($data['attrib']) && is_array($data['attrib'])) {
                foreach ($data['attrib'] as $attr) {
                    $val = $attr['value'] ?? '';
                    // Gabungkan footer (biasanya tanda %)
                    if (isset($attr['footer'])) {
                        $val .= $attr['footer'];
                    }
                    
                    if (isset($attr['key'])) {
                        // Ubah 'bonus_movement_speed' jadi 'Bonus Movement Speed'
                        $cleanKey = ucwords(str_replace('_', ' ', $attr['key']));
                        $formattedStats[$cleanKey] = $val;
                    }
                }
            }

            // 2. OLAH DESKRIPSI (Gabung Hint, CD, Mana)
            $descArray = $data['hint'] ?? [];
            
            // Tambah Cooldown & Mana jika ada (Data dari JSON-mu ada 'cd' dan 'mc')
            $extras = [];
            if (isset($data['mc']) && $data['mc']) $extras[] = "💧 Mana: " . $data['mc'];
            if (isset($data['cd']) && $data['cd']) $extras[] = "⏳ Cooldown: " . $data['cd'] . "s";
            
            if (!empty($extras)) {
                $descArray[] = implode(" | ", $extras);
            }

            $fullDesc = is_array($descArray) ? implode("\n\n", $descArray) : $descArray;

            // 3. SIMPAN KE DB
            Item::create([
                'name'        => $key,
                'dname'       => $data['dname'],
                'cost'        => $data['cost'] ?? 0,
                'img_url'     => 'https://cdn.cloudflare.steamstatic.com' . $data['img'],
                'desc'        => $fullDesc,
                'lore'        => $data['lore'] ?? null,
                'stats'       => !empty($formattedStats) ? $formattedStats : null, // Model akan otomatis ubah ke JSON
                'components'  => $data['components'] ?? null,
                'recipe_cost' => str_contains($key, 'recipe') ? 1 : 0,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('💎 Selesai! Tabel Item sudah diisi ulang dengan data JSON bersih.');
    }
}