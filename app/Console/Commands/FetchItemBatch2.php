<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\Item;

class FetchItemBatch2 extends Command
{
    protected $signature = 'dota:fetch-items-batch2';
    protected $description = 'Import Complete Item Details (Batch 2) from Local JSON';

    public function handle()
    {
        $this->info('📦 Memulai Import Item Batch 2 (Detail Lengkap)...');

        $path = storage_path('app/items.json');
        if (!File::exists($path)) {
            $this->error('❌ File items.json tidak ditemukan di storage/app/!');
            return;
        }

        $itemsData = json_decode(File::get($path), true);
        $bar = $this->output->createProgressBar(count($itemsData));
        $bar->start();

        foreach ($itemsData as $key => $data) {
            // Skip item event/sampah
            if (empty($data['dname']) || empty($data['img'])) continue;

            // --- 1. FORMAT STATS (Attributes) ---
            $formattedStats = [];
            if (isset($data['attrib']) && is_array($data['attrib'])) {
                foreach ($data['attrib'] as $attr) {
                    $val = $attr['value'] ?? '';
                    // Tambahkan % jika ada di footer
                    if (isset($attr['footer']) && str_contains($attr['footer'], '%')) {
                        $val .= '%';
                    }
                    
                    if (isset($attr['key'])) {
                        // Bersihkan nama key (bonus_damage -> Bonus Damage)
                        $cleanKey = ucwords(str_replace('_', ' ', $attr['key']));
                        $formattedStats[$cleanKey] = $val;
                    }
                }
            }
            $statsJSON = !empty($formattedStats) ? json_encode($formattedStats) : null;

            // --- 2. FORMAT DESCRIPTION (Passive, Active, Desc) ---
            $descArray = $data['hint'] ?? [];
            
            // Tambahkan CD/Mana cost jika ada
            if (isset($data['mc']) && $data['mc'] !== false) {
                $descArray[] = "Mana Cost: " . $data['mc'];
            }
            if (isset($data['cd']) && $data['cd'] !== false) {
                $descArray[] = "Cooldown: " . $data['cd'] . "s";
            }

            // Jika ada Lore item, tambahkan juga di bawah
            if (isset($data['lore'])) {
                $descArray[] = "\n_" . $data['lore'] . "_";
            }

            $fullDesc = is_array($descArray) ? implode("\n", $descArray) : $descArray;

            // --- 3. SIMPAN KE DATABASE ---
            // Kita pakai updateOrCreate agar data lama terupdate
            Item::updateOrCreate(
                ['name' => $key], 
                [
                    'dname'       => $data['dname'],
                    'cost'        => $data['cost'] ?? 0,
                    'img_url'     => 'https://cdn.cloudflare.steamstatic.com' . $data['img'],
                    'components'  => isset($data['components']) ? $data['components'] : null,
                    'recipe_cost' => (str_contains($key, 'recipe')) ? 1 : 0,
                    
                    // Kolom Detail Baru
                    'desc'        => $fullDesc,
                    'stats'       => $statsJSON,
                ]
            );

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('💎 Item Batch 2 Selesai! Data stats & deskripsi lengkap tersimpan.');
    }
}