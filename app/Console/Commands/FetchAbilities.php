<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Hero;
use App\Models\Ability;

class FetchAbilities extends Command
{
    protected $signature = 'dota:fetch-abilities';
    protected $description = 'Ambil skill hero + Generate Video URL';

    public function handle()
    {
        $this->info('Mengambil database skill...');

        $allAbilities = Http::timeout(300)
                            ->retry(3, 1000) 
                            ->get('https://api.opendota.com/api/constants/abilities')
                            ->json();
        
        $heroAbilitiesMap = Http::timeout(300)
                                ->retry(3, 1000)
                                ->get('https://api.opendota.com/api/constants/hero_abilities')
                                ->json();

        if (!$allAbilities || !$heroAbilitiesMap) {
            $this->error('Gagal mendownload data dari OpenDota. Cek koneksi internet.');
            return;
        }

        $heroes = Hero::whereNotNull('code_name')->get();
        $bar = $this->output->createProgressBar($heroes->count());

        foreach ($heroes as $hero) {
            $rawHeroData = $heroAbilitiesMap[$hero->code_name] ?? [];

            $targetSkills = [];
            if (isset($rawHeroData['abilities'])) {
                $targetSkills = $rawHeroData['abilities'];
            } elseif (is_array($rawHeroData)) {
                $targetSkills = $rawHeroData;
            }

            $heroShortName = str_replace('npc_dota_hero_', '', $hero->code_name);
            
            switch ($heroShortName) {
                case 'nevermore': $heroShortName = 'shadow_fiend'; break;
                case 'rattletrap': $heroShortName = 'clockwerk'; break;
                case 'magnataur': $heroShortName = 'magnus'; break;
                case 'wisp': $heroShortName = 'io'; break;
                case 'obsidian_destroyer': $heroShortName = 'outworld_destroyer'; break;
                case 'skeleton_king': $heroShortName = 'wraith_king'; break;
                case 'zuus': $heroShortName = 'zeus'; break;
                case 'doom_bringer': $heroShortName = 'doom'; break;
                case 'treant': $heroShortName = 'treant_protector'; break;
            }

            foreach ($targetSkills as $skillName) {
                if (!is_string($skillName)) continue;
                if (str_starts_with($skillName, 'special_bonus')) continue;
                if ($skillName == 'generic_hidden') continue;

                $skillData = $allAbilities[$skillName] ?? null;

                if ($skillData && !empty($skillData['dname'])) {
                    
                    $behavior = isset($skillData['behavior']) 
                        ? (is_array($skillData['behavior']) ? implode(', ', $skillData['behavior']) : $skillData['behavior'])
                        : 'Passive';

                    $videoUrl = "https://cdn.steamstatic.com/apps/dota2/videos/dota_react/abilities/{$heroShortName}/{$skillName}.mp4";

                    Ability::updateOrCreate(
                        [
                            'hero_id' => $hero->id,
                            'name'    => $skillData['dname']
                        ],
                        [
                            'desc'      => $skillData['desc'] ?? null,
                            'img_url'   => 'https://cdn.cloudflare.steamstatic.com' . ($skillData['img'] ?? ''),
                            'mana_cost' => $skillData['mc'] ?? null,
                            'cooldown'  => $skillData['cd'] ?? null,
                            'behavior'  => $behavior,
                            'video_url' => $videoUrl, 
                        ]
                    );
                }
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Sukses! Data Skill & Video Link sudah masuk.');
    }
}