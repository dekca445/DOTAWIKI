<x-layout>
    <x-slot:title>Artifacts Archive - Frozen Wiki</x-slot>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>

    <div class="container py-5 text-center fade-in-anim">
        <h1 class="display-1 mb-3 frozen-text text-uppercase" data-text="Item List">Item List</h1>
        {{-- <p class="text-ice mb-5 fs-5 font-cinzel">Ancient relics and mystical equipment to enhance your champion.</p> --}}

        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <div class="input-group input-group-lg shadow" style="border: 1px solid #444; border-radius: 5px;">
                    <span class="input-group-text bg-dark border-0 text-secondary">🔍</span>
                    <input type="text" id="itemSearch" class="form-control bg-dark text-white border-0"
                        placeholder="Search for an item... (e.g. Blink Dagger)">
                </div>
            </div>
        </div>

        <div id="itemGrid">
            @foreach($groupedItems as $category => $items)
                <div class="category-section mb-5">
                    <div class="d-flex align-items-center mb-4">
                        <h3 class="text-ice font-cinzel mb-0 text-uppercase tracking-widest">{{ $category }}</h3>
                        <div class="ms-3 flex-grow-1" style="height: 1px; background: linear-gradient(to right, var(--ice-blue), transparent);"></div>
                    </div>

                    <div class="row g-2 justify-content-start">
                        @foreach($items as $item)
                            <div class="col-auto item-card-wrapper" data-name="{{ strtolower($item->dname) }}">
                                <div class="item-canvas border border-secondary bg-dark position-relative overflow-hidden" 
                                     style="width: 85px; height: 64px; cursor: pointer;"
                                     onclick="window.location='{{ route('items.show', $item->id) }}'"
                                     data-tippy-html="#tooltip-{{ $item->id }}">
                                    
                                    <img src="{{ $item->img_url }}" class="img-fluid w-100 h-100 object-fit-cover shadow">
                                    <div class="holo-sheen"></div>
                                    
                                    <div class="position-absolute bottom-0 end-0 bg-black px-1 border-top border-start border-secondary" style="opacity: 0.8;">
                                        <small class="text-warning fw-bold" style="font-size: 10px;">{{ $item->cost }}</small>
                                    </div>
                                </div>

                                <div id="tooltip-{{ $item->id }}" style="display: none;">
                                    <div class="p-3 bg-[#1c242d] text-white border border-[#444] text-start" style="min-width: 320px; box-shadow: 0 10px 30px rgba(0,0,0,0.9); background: #151921;">
                                        <div class="d-flex justify-content-between align-items-start mb-3 border-bottom border-secondary pb-2">
                                            <div class="d-flex gap-2">
                                                <img src="{{ $item->img_url }}" width="45" height="34" class="border border-secondary">
                                                <div>
                                                    <h5 class="m-0 fw-bold text-white font-cinzel" style="font-size: 16px;">{{ $item->dname }}</h5>
                                                    <span class="text-secondary small text-uppercase" style="font-size: 10px; letter-spacing: 1px;">
                                                        {{ !empty($item->components) ? 'Legendary' : 'Common' }} Artifact
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-bold text-warning d-flex align-items-center justify-content-end gap-1">
                                                    <img src="https://cdn.cloudflare.steamstatic.com/apps/dota2/images/tooltips/gold.png" width="14">
                                                    {{ number_format($item->cost) }}
                                                </div>
                                            </div>
                                        </div>

                                        @if(is_array($item->stats) && count($item->stats) > 0)
                                            <div class="py-2 mb-2">
                                                @foreach($item->stats as $key => $val)
                                                    <div class="d-flex justify-content-between" style="font-size: 12px;">
                                                        <span class="text-secondary">{{ $key }}</span>
                                                        <span class="text-white fw-bold">{{ $val }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if($item->desc)
                                        <div class="bg-black bg-opacity-50 p-2 rounded border border-secondary border-opacity-25 mb-2">
                                            <div class="fw-bold text-white text-uppercase mb-1" style="font-size: 10px; letter-spacing: 1px; color: #64748b;">Passive / Active</div>
                                            <div class="small text-white-50 fst-italic" style="font-size: 11px; line-height: 1.4;">
                                                {!! nl2br(e(Str::limit($item->desc, 150))) !!}
                                            </div>
                                        </div>
                                        @endif

                                        @if(!empty($item->components))
                                        <div class="mt-2 pt-2 border-top border-secondary border-opacity-50 text-center">
                                            <div class="text-secondary mb-1" style="font-size: 9px; letter-spacing: 1px;">REQUIRES</div>
                                            <div class="d-flex justify-content-center flex-wrap gap-1">
                                                @foreach($item->components as $compName)
                                                    <img src="https://cdn.cloudflare.steamstatic.com/apps/dota2/images/dota_react/items/{{ $compName }}.png" width="28" class="border border-secondary" title="{{ $compName }}">
                                                @endforeach
                                                @if($item->recipe_cost)
                                                    <div class="border border-secondary bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 28px; height: 21px; font-size: 10px;">📜</div>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <div class="text-center mt-2 text-info text-uppercase" style="font-size: 9px; letter-spacing: 1px; opacity: 0.7;">
                                            Click for full details
                                        </div>
                                    </div>
                                </div>
                                </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div id="noItemResults" class="text-center mt-5 d-none">
            <h3 class="text-muted">No items found matching your search.</h3>
        </div>
    </div>

    <x-slot:scripts>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // ... (Script Search tetap sama, tidak perlu diubah) ...
                const itemSearch = document.getElementById('itemSearch');
                const categories = document.querySelectorAll('.category-section');
                const noResults = document.getElementById('noItemResults');

                if(itemSearch) {
                    itemSearch.addEventListener('input', function(e) {
                        const term = e.target.value.toLowerCase();
                        let totalVisible = 0;

                        categories.forEach(section => {
                            let sectionHasVisible = 0;
                            const items = section.querySelectorAll('.item-card-wrapper');
                            
                            items.forEach(item => {
                                const name = item.getAttribute('data-name');
                                if (name.includes(term)) {
                                    item.style.display = 'block';
                                    sectionHasVisible++;
                                    totalVisible++;
                                } else {
                                    item.style.display = 'none';
                                }
                            });
                            section.style.display = sectionHasVisible > 0 ? 'block' : 'none';
                        });

                        noResults.classList.toggle('d-none', totalVisible > 0);
                    });
                }

                // --- SETTING TIPPY (PERBAIKAN DELAY) ---
                tippy('.item-canvas', {
                    content(reference) {
                        const id = reference.getAttribute('data-tippy-html');
                        const template = document.querySelector(id);
                        return template.innerHTML;
                    },
                    allowHTML: true,
                    theme: 'custom-dota',
                    placement: 'right', // Muncul di kanan
                    arrow: false,       // Hilangkan panah kecil biar bersih
                    offset: [15, 0],    // Jarak tooltip dari item
                    maxWidth: 350,
                    
                    // --- TUNING ANIMASI AGAR TIDAK DELAY ---
                    animation: 'fade',  // Ganti 'shift-away' jadi 'fade' biasa biar ringan
                    duration: [100, 50], // [Show, Hide] dalam ms. 100ms muncul, 50ms hilang (Cepat!)
                    delay: [0, 0],      // 0 delay saat masuk, 0 delay saat keluar
                    interactive: false, // Tooltip tidak bisa diklik (biar gak nyangkut)
                    appendTo: document.body, // Pastikan render di body agar z-index aman
                });
            });
        </script>
        
        <style>
            /* Jarak Antar Item diperlebar (Gap) */
            .row.g-2 { 
                --bs-gutter-x: 1.5rem; /* Jarak Horizontal diperlebar */
                --bs-gutter-y: 1.5rem; /* Jarak Vertikal diperlebar */
            }

            /* Efek Kilau Holo */
            .holo-sheen {
                position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
                background: linear-gradient(to right, transparent, rgba(255,255,255,0.2), transparent);
                transform: skewX(-25deg);
                transition: 0.5s;
            }
            .item-canvas:hover .holo-sheen { left: 150%; transition: 0.7s; }
            
            /* Hover Effect */
            .item-canvas:hover { 
                border-color: #00d9ff !important; 
                box-shadow: 0 0 10px #00d9ff; 
                transform: scale(1.1); /* Zoom sedikit lebih besar */
                transition: 0.1s; /* Transisi super cepat */
                z-index: 10; 
            }
            
            /* Style Tooltip Custom ala Dota */
            .tippy-box[data-theme~='custom-dota'] {
                background-color: transparent;
                color: white;
            }
            
            /* Fix layout grid agar item tidak terlalu mepet */
            .item-card-wrapper {
                margin-bottom: 10px; /* Tambahan jarak aman bawah */
            }
        </style>
    </x-slot:scripts>
</x-layout>