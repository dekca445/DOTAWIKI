<x-layout>
    <x-slot:title>Patch {{ $version }} - Frozen Wiki</x-slot>

    <div class="container py-5 fade-in-anim">
        <div class="text-center mb-5">
            <span class="badge bg-info mb-2">GAMEPLAY UPDATE</span>
            <h1 class="display-3 frozen-text" data-text="PATCH {{ $version }}">PATCH {{ $version }}</h1>
            <p class="text-secondary">Official Changelog</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                @if(isset($patchData['general']) && count($patchData['general']) > 0)
                <div class="mb-5">
                    <h3 class="text-warning font-cinzel border-bottom border-secondary pb-2 mb-4">GENERAL UPDATES</h3>
                    <ul class="list-group list-group-flush bg-transparent">
                        @foreach($patchData['general'] as $change)
                            <li class="list-group-item bg-transparent text-light border-secondary">
                                <span class="text-info">✦</span> 
                                @if(is_array($change))
                                    {{-- Jika change adalah array, ambil elemen pertamanya atau gabungkan --}}
                                    {!! implode('<br>', $change) !!}
                                @else
                                    {!! $change !!}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(isset($patchData['heroes']) && count($patchData['heroes']) > 0)
                <div class="mb-5">
                    <h3 class="text-success font-cinzel border-bottom border-secondary pb-2 mb-4">HERO UPDATES</h3>
                    
                    @foreach($patchData['heroes'] as $heroName => $changes)
                        @php 
                            // Cari hero di DB (support code_name dan npc_dota_hero_)
                            $hero = \App\Models\Hero::where('code_name', $heroName)
                                    ->orWhere('code_name', 'npc_dota_hero_'.$heroName)
                                    ->first(); 
                        @endphp
                        
                        <div class="mb-4 p-4 bg-black bg-opacity-50 border border-secondary rounded">
                            <div class="d-flex align-items-center mb-3">
                                @if($hero)
                                    <img src="{{ $hero->icon_url }}" class="rounded-circle me-3 border border-success" width="40">
                                    <h4 class="text-white mb-0 text-uppercase">{{ $hero->name_localized }}</h4>
                                @else
                                    <h4 class="text-white mb-0 text-uppercase">{{ str_replace('npc_dota_hero_', '', $heroName) }}</h4>
                                @endif
                            </div>
                            
                            <ul class="list-unstyled mb-0 ps-3">
                                @foreach($changes as $change)
                                    <li class="text-secondary small mb-2 text-justify">
                                        • 
                                        @if(is_array($change))
                                            {{-- FIX: Handle jika change berupa Array --}}
                                            <ul class="d-inline-block align-top ps-0 mb-0" style="list-style: none;">
                                                @foreach($change as $subChange)
                                                    <li>- {!! $subChange !!}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            {!! $change !!}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
                @endif

                @if(isset($patchData['items']) && count($patchData['items']) > 0)
                <div class="mb-5">
                    <h3 class="text-warning font-cinzel border-bottom border-secondary pb-2 mb-4">ITEM UPDATES</h3>
                    
                    <div class="row">
                        @foreach($patchData['items'] as $itemName => $changes)
                            @php 
                                $item = \App\Models\Item::where('name', $itemName)
                                        ->orWhere('name', 'item_'.$itemName)
                                        ->first(); 
                            @endphp
                            <div class="col-md-6 mb-4">
                                <div class="p-3 bg-dark border border-secondary rounded h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        @if($item)
                                            <img src="{{ $item->img_url }}" class="rounded me-2 border border-warning" width="35">
                                            <h5 class="text-white mb-0 small">{{ $item->dname }}</h5>
                                        @else
                                            <h5 class="text-white mb-0 small">{{ $itemName }}</h5>
                                        @endif
                                    </div>
                                    <ul class="list-unstyled mb-0 ps-2">
                                        @foreach($changes as $change)
                                            <li class="text-secondary x-small mb-1">
                                                • 
                                                @if(is_array($change))
                                                    {{-- FIX: Handle Array di Item --}}
                                                    {!! implode('<br> &nbsp; &nbsp; - ', $change) !!}
                                                @else
                                                    {!! $change !!}
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</x-layout>