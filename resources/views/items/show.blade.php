<x-layout>
    <x-slot:title>{{ $item->dname }} - Item Details</x-slot>

    <div class="container py-5 mt-5 fade-in-anim">
        <div class="row align-items-center mb-5">
            <div class="col-md-3 text-center">
                <div class="position-relative d-inline-block shadow-lg border border-info rounded p-2 bg-black">
                    <img src="{{ $item->img_url }}" class="img-fluid rounded" alt="{{ $item->dname }}" style="width: 128px; height:auto;">
                    
                    <div class="position-absolute top-0 end-0 translate-middle badge rounded-pill bg-black border border-warning text-warning shadow d-flex align-items-center px-2 py-1">
                        🟡 {{ $item->cost }}
                    </div>
                </div>
            </div>
            <div class="col-md-9 text-center text-md-start">
                <h1 class="display-3 frozen-text mb-2" data-text="{{ $item->dname }}">{{ $item->dname }}</h1>
                <p class="text-ice fs-5 font-cinzel text-uppercase letter-spacing-2">
                    {{ !empty($item->components) ? 'Assembled Artifact' : 'Base Component' }}
                </p>
                <a href="/items" class="btn btn-sm btn-outline-secondary mt-2">&larr; Back to Archive</a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <x-ice-card>
                    <h4 class="text-ice font-cinzel mb-4 border-bottom border-secondary pb-2 opacity-75">ATTRIBUTES & ABILITIES</h4>
                    
                    @if(is_array($item->stats) && count($item->stats) > 0)
                        <div class="row mb-4">
                            @foreach($item->stats as $key => $val)
                            <div class="col-md-6 mb-2">
                                <div class="d-flex justify-content-between align-items-center p-2 rounded bg-black bg-opacity-25 border border-secondary border-opacity-25">
                                    <span class="text-secondary small text-uppercase fw-bold">{{ $key }}</span>
                                    <span class="text-white fw-bold">{{ $val }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif

                    @if($item->desc)
                        <div class="p-3 rounded bg-black border border-secondary mb-4">
                            <p class="text-light mb-0 small" style="line-height: 1.8; white-space: pre-wrap;">{!! nl2br(e($item->desc)) !!}</p>
                        </div>
                    @else
                         <p class="text-muted fst-italic">No active/passive ability description.</p>
                    @endif

                    @if($item->lore)
                        <div class="text-center fst-italic text-secondary mt-4 px-4 border-top border-secondary border-opacity-25 pt-3">
                            "{{ $item->lore }}"
                        </div>
                    @endif
                </x-ice-card>
            </div>

            @if(is_array($item->components) && count($item->components) > 0)
            <div class="col-lg-5">
                <x-ice-card>
                    <h4 class="text-warning font-cinzel mb-4 border-bottom border-warning pb-2 opacity-75">CRAFTING RECIPE</h4>
                    
                    <div class="d-flex flex-column align-items-center pt-3">
                        <div class="mb-4 position-relative z-2">
                            <img src="{{ $item->img_url }}" class="rounded border border-warning shadow-lg" width="64">
                            <div class="text-warning small mt-1 fw-bold text-center">{{ $item->cost }}</div>
                            <div class="position-absolute start-50 translate-middle-x bg-secondary border-start border-warning" style="width: 2px; height: 30px; bottom: -30px;"></div>
                        </div>

                        <div class="d-flex flex-wrap gap-3 justify-content-center pt-4 border-top border-warning border-opacity-25 w-100 position-relative">
                            @foreach($item->components as $compName)
                                @php 
                                    // Cari item anak di DB berdasarkan nama internal
                                    $comp = \App\Models\Item::where('name', $compName)->first(); 
                                @endphp

                                @if($comp)
                                    <div class="text-center transition-hover">
                                        <a href="{{ route('items.show', $comp->id) }}">
                                            <img src="{{ $comp->img_url }}" class="rounded border border-secondary mb-1" width="48" title="{{ $comp->dname }}">
                                        </a>
                                        <div class="text-muted small" style="font-size: 10px;">{{ $comp->cost }}</div>
                                    </div>
                                @elseif(str_contains($compName, 'recipe'))
                                    <div class="text-center">
                                        <div class="rounded border border-secondary mb-1 bg-secondary d-flex align-items-center justify-content-center" style="width: 48px; height: 35px;">📜</div>
                                        <div class="text-muted small" style="font-size: 10px;">Recipe</div>
                                    </div>
                                @endif
                            @endforeach
                            
                            @if($item->recipe_cost && !collect($item->components)->contains(fn($c) => str_contains($c, 'recipe')))
                                 <div class="text-center">
                                    <div class="rounded border border-secondary mb-1 bg-secondary d-flex align-items-center justify-content-center" style="width: 48px; height: 35px;">📜</div>
                                    <div class="text-muted small" style="font-size: 10px;">Recipe</div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mt-4 pt-3 w-100 text-center border-top border-secondary border-opacity-25">
                            <small class="text-secondary text-uppercase">Total Cost</small>
                            <h2 class="text-warning fw-bold text-shadow mb-0">
                                🟡 {{ $item->cost }}
                            </h2>
                        </div>
                    </div>
                </x-ice-card>
            </div>
            @endif
        </div>
    </div>
    
    <style>
        .transition-hover img { transition: transform 0.2s; }
        .transition-hover:hover img { transform: scale(1.1); border-color: white !important; }
    </style>
</x-layout>