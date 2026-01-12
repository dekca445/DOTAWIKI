<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Item;
use App\Models\Hero;

class FetchItems extends Command
{
    protected $signature = 'dota:fetch-items';
    protected $description = 'Fetch Items: Details, Stats, and Popular Heroes';

    public function handle()
    {
        $this->info('📦 Memulai Scraping Item Premium (OpenDota API)...');

        $itemsData = Http::get('https://api.opendota.com/api/constants/items')->json();

        if (!$itemsData) {
            $this->error('❌ Gagal koneksi ke OpenDota.');
            return;
        }

        $bar = $this->output->createProgressBar(count($itemsData));
        $bar->start();

        foreach ($itemsData as $key => $data) {
            if (empty($data['dname']) || empty($data['img'])) continue;

            // --- A. FORMAT DATA STATS (FIX: Flatten Array) ---
            // OpenDota: [{key: "bonus_str", value: "10"}, ...]
            // Kita ubah jadi: {"bonus_str": "10", ...}
            $formattedStats = [];
            if (isset($data['attrib']) && is_array($data['attrib'])) {
                foreach ($data['attrib'] as $attr) {
                    if (isset($attr['key']) && isset($attr['value'])) {
                        // Bersihkan key (contoh: "bonus_damage" -> "Damage") nanti di View
                        $formattedStats[$attr['key']] = $attr['value'];
                    }
                }
            }
            $stats = !empty($formattedStats) ? json_encode($formattedStats) : null;
            
            // Description
            $desc = $data['hint'] ?? null;
            if (is_array($desc)) $desc = implode("\n", $desc);

            // --- B. AMBIL HERO POPULER ---
            $popularHeroes = [];
            
            // Filter: Hanya fetch untuk item > 1500 gold & bukan resep
            if (($data['cost'] ?? 0) > 1500 && !str_contains($key, 'recipe')) {
                try {
                    usleep(150000); // Jeda sedikit lebih lama (0.15s) biar aman
                    
                    $timings = Http::get("https://api.opendota.com/api/scenarios/itemTimings", [
                        'item' => $key
                    ])->json();

                    if (!empty($timings) && is_array($timings) && isset($timings[0]) && is_array($timings[0])) {
                        // Urutkan win terbanyak
                        usort($timings, fn($a, $b) => ($b['wins'] ?? 0) <=> ($a['wins'] ?? 0));
                        $top5 = array_slice($timings, 0, 5);

                        foreach ($top5 as $t) {
                            if (!isset($t['hero_id'])) continue;
                            
                            $hero = Hero::find($t['hero_id']);
                            if ($hero) {
                                $popularHeroes[] = [
                                    'id' => $hero->id,
                                    'name' => $hero->name_localized,
                                    'img' => $hero->img_url,
                                    'winrate' => $t['games'] > 0 ? round(($t['wins'] / $t['games']) * 100, 1) : 0
                                ];
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Skip error
                }
            }

            // --- C. SIMPAN ---
            Item::updateOrCreate(
                ['name' => $key],
                [
                    'dname'       => $data['dname'],
                    'cost'        => $data['cost'] ?? 0,
                    'img_url'     => 'https://cdn.cloudflare.steamstatic.com' . $data['img'],
                    'components'  => isset($data['components']) ? $data['components'] : null,
                    'recipe_cost' => (str_contains($key, 'recipe')) ? 1 : 0,
                    'desc'        => $desc,
                    'stats'       => $stats,
                    'popular_heroes' => !empty($popularHeroes) ? json_encode($popularHeroes) : null
                ]
            );

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('💎 Selesai! Data sudah diperbaiki.');
    }
}