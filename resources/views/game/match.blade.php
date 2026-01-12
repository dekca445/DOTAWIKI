<x-layout>
    <x-slot:title>Match {{ $match['match_id'] }} - Frozen Wiki</x-slot>

    <div class="container py-5 fade-in-anim">
        
        <div class="text-center mb-5">
            <h5 class="text-muted text-uppercase mb-2">{{ $match['league']['name'] ?? 'Public Match' }}</h5>
            <h1 class="display-4 font-cinzel text-white">
                <span class="{{ $match['radiant_win'] ? 'text-success' : 'text-danger' }}">{{ $match['radiant_name'] ?? 'Radiant' }}</span>
                <span class="mx-3 text-secondary fs-6">VS</span>
                <span class="{{ !$match['radiant_win'] ? 'text-success' : 'text-danger' }}">{{ $match['dire_name'] ?? 'Dire' }}</span>
            </h1>
            <div class="badge bg-secondary mt-2 fs-6">
                Winner: {{ $match['radiant_win'] ? ($match['radiant_name'] ?? 'Radiant') : ($match['dire_name'] ?? 'Dire') }}
            </div>
            <p class="text-secondary mt-2 small">
                Match ID: {{ $match['match_id'] }} &bull; Duration: {{ gmdate("H:i:s", $match['duration']) }}
            </p>
        </div>

        <div class="table-responsive">
            <table class="table table-dark table-hover border border-secondary align-middle">
                <thead>
                    <tr class="text-center small text-secondary text-uppercase">
                        <th class="text-start ps-4">Player / Hero</th>
                        <th>Lvl</th>
                        <th>K</th>
                        <th>D</th>
                        <th>A</th>
                        <th>Net Worth</th>
                        <th>Items</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($match['players'] as $p)
                        @php 
                            $hero = $heroes[$p['hero_id']] ?? null; 
                            $isRadiant = $p['player_slot'] < 128;
                        @endphp
                        
                        @if($loop->index == 5)
                            <tr><td colspan="7" class="bg-secondary p-1"></td></tr>
                        @endif

                        <tr class="{{ $isRadiant ? 'border-success' : 'border-danger' }} border-start border-3">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="position-relative me-3">
                                        <img src="{{ $hero->icon_url ?? '' }}" width="40" class="rounded-circle border border-secondary">
                                    </div>
                                    <div>
                                        <div class="fw-bold text-white small">
                                            {{ $p['personaname'] ?? $p['name'] ?? 'Anonymous' }}
                                        </div>
                                        <div class="x-small {{ $isRadiant ? 'text-success' : 'text-danger' }}">
                                            {{ $isRadiant ? 'Radiant' : 'Dire' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center fw-bold text-warning">{{ $p['level'] }}</td>
                            <td class="text-center text-success fw-bold">{{ $p['kills'] }}</td>
                            <td class="text-center text-danger fw-bold">{{ $p['deaths'] }}</td>
                            <td class="text-center text-info fw-bold">{{ $p['assists'] }}</td>
                            <td class="text-center text-warning small">
                                {{ number_format($p['net_worth'] ?? 0) }} <img src="https://cdn.cloudflare.steamstatic.com/apps/dota2/images/tooltips/gold.png" width="12">
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    @for($i=0; $i<=5; $i++)
                                        @php 
                                            $itemId = $p['item_'.$i]; 
                                            // Mapping ID ke Item Name agak tricky tanpa file item_ids.json, 
                                            // tapi anggap saja kita punya logic/helper atau biarkan gambar kosong kalau ID tidak ketemu.
                                            // Untuk sekarang kita skip gambar item jika mapping ID belum ada, 
                                            // atau gunakan placeholder.
                                            // (Opsional: Jika kamu punya item_ids.json, load itu di controller)
                                        @endphp
                                        @if($itemId != 0)
                                            <div class="bg-black border border-secondary rounded" style="width:30px; height:22px;">
                                                <div class="w-100 h-100 bg-secondary bg-opacity-25"></div>
                                            </div>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="text-center mt-4">
            <a href="/" class="btn btn-outline-secondary">&larr; Back to Home</a>
        </div>
    </div>
</x-layout>