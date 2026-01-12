<x-layout>
    <x-slot:title>{{ $hero->name_localized }} - Frozen Wiki</x-slot>

    <div class="hero-header-container bg-black position-relative overflow-hidden d-flex align-items-center justify-content-center fade-in-anim"
        style="height: 600px; border-bottom: 2px solid var(--ice-border);">
        @if ($hero->video_url)
            <div class="video-wrapper">
                <video autoplay muted loop playsinline disablePictureInPicture class="hero-video no-interaction">
                    <source src="{{ $hero->video_url }}" type="video/webm">
                </video>
                <div class="video-overlay-ice"></div>
            </div>
        @endif

        <div class="container position-relative z-2 text-center header-content" style="margin-top: 150px;">
            <h1 class="display-1 fw-bold text-uppercase frozen-text mb-0" data-text="{{ $hero->name_localized }}">
                {{ $hero->name_localized }}
            </h1>
            <p class="text-ice lead fw-bold text-uppercase mt-2"
                style="letter-spacing: 4px; font-size: 0.9rem; text-shadow: 0 2px 5px black;">
                {{ $hero->attack_type }} &bull; {{ $hero->primary_attr }}
            </p>
        </div>
    </div>

    <div class="container position-relative z-3 fade-in-anim mobile-reset-margin"
        style="margin-top: -60px; animation-delay: 0.2s;">

        <div class="mb-5">
            <x-ice-card>
                <div class="row align-items-center">
                    <div class="col-lg-3 text-center border-end border-secondary border-opacity-25 pb-3 pb-lg-0">
                        <img src="{{ $hero->img_url }}" class="img-fluid border border-secondary shadow-lg mb-2 rounded"
                            style="max-height: 120px;">
                        <div class="progress rounded-0 bg-dark border border-secondary position-relative mx-auto"
                            style="height: 16px; max-width: 180px;">
                            <div class="progress-bar bg-success" style="width: 100%;"></div>
                            <span class="position-absolute w-100 text-center text-white fw-bold"
                                style="font-size: 9px; line-height: 16px;">{{ $hero->base_health }} HP</span>
                        </div>
                        <div class="progress rounded-0 mt-1 bg-dark border border-secondary position-relative mx-auto"
                            style="height: 16px; max-width: 180px;">
                            <div class="progress-bar bg-primary" style="width: 100%;"></div>
                            <span class="position-absolute w-100 text-center text-white fw-bold"
                                style="font-size: 9px; line-height: 16px;">{{ $hero->base_mana }} MP</span>
                        </div>
                    </div>

                    <div class="col-lg-5 px-4 border-end border-secondary border-opacity-25 py-3 py-lg-0">
                        <div class="row text-center align-items-center">
                            <div class="col-4">
                                <h5 class="text-danger font-cinzel mb-0">STR</h5>
                                <div class="fs-2 fw-bold text-white text-shadow">{{ $hero->base_str }}</div>
                                <small class="text-white-50 fw-bold">+{{ $hero->str_gain }}</small>
                            </div>
                            <div class="col-4 border-start border-end border-secondary border-opacity-25">
                                <h5 class="text-success font-cinzel mb-0">AGI</h5>
                                <div class="fs-2 fw-bold text-white text-shadow">{{ $hero->base_agi }}</div>
                                <small class="text-white-50 fw-bold">+{{ $hero->agi_gain }}</small>
                            </div>
                            <div class="col-4">
                                <h5 class="text-info font-cinzel mb-0">INT</h5>
                                <div class="fs-2 fw-bold text-white text-shadow">{{ $hero->base_int }}</div>
                                <small class="text-white-50 fw-bold">+{{ $hero->int_gain }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 ps-lg-4 text-center text-lg-start pt-3 pt-lg-0">
                        <h6 class="text-ice font-cinzel mb-2 small">ROLES</h6>
                        <div class="d-flex flex-wrap gap-1 mb-3 justify-content-center justify-content-lg-start">
                            @foreach ($hero->roles ?? [] as $role)
                                <span class="badge bg-secondary bg-opacity-25 border border-secondary text-light"
                                    style="font-size: 9px;">{{ $role }}</span>
                            @endforeach
                        </div>
                        <div class="row g-2">
                            <div class="col-4">
                                <div class="p-1 border border-dark rounded bg-black bg-opacity-25 text-center"><small
                                        class="text-muted d-block" style="font-size: 8px;">RANGE</small><span
                                        class="fw-bold text-white small">{{ $hero->attack_range ?? '-' }}</span></div>
                            </div>
                            <div class="col-4">
                                <div class="p-1 border border-dark rounded bg-black bg-opacity-25 text-center"><small
                                        class="text-muted d-block" style="font-size: 8px;">VISION</small><span
                                        class="fw-bold text-white small">{{ $hero->day_vision ?? '-' }}</span></div>
                            </div>
                            <div class="col-4">
                                <div class="p-1 border border-dark rounded bg-black bg-opacity-25 text-center"><small
                                        class="text-muted d-block" style="font-size: 8px;">SPEED</small><span
                                        class="fw-bold text-white small">{{ $hero->move_speed ?? '-' }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-ice-card>
        </div>

        @if($hero->lore)
            <div class="mb-5 fade-in-anim" style="animation-delay: 0.3s;">
                <x-ice-card :interactive="true">
                    <h4 class="text-ice mb-3 font-cinzel border-bottom border-secondary border-opacity-25 pb-2">
                        Hero Lore
                    </h4>
                    
                    <div class="text-light text-center small" style="line-height: 1.8;">
                        {!! nl2br(e($hero->lore)) !!}
                    </div>
                </x-ice-card>
            </div>
        @endif

        @if ($hero->playstyle)
            <div class="mb-4 fade-in-anim" style="animation-delay: 0.35s;">
                <x-ice-card :interactive="true">
                    <h4 class="text-warning mb-3 font-cinzel border-bottom border-secondary border-opacity-25 pb-2">
                        STRATEGY</h4>

                    <div class="playstyle-container position-relative">
                        <div class="playstyle-text text-light text-justify small" id="playstyleContent">
                            {{ $hero->playstyle }}
                        </div>
                        <button id="readMoreBtn" class="btn btn-link text-ice p-0 mt-2 fw-bold small d-none"
                            onclick="togglePlaystyle()">READ MORE ▼</button>
                    </div>
                </x-ice-card>
            </div>
        @endif

        @if ($hero->pros || $hero->cons)
            <div class="mb-5 fade-in-anim" style="animation-delay: 0.4s;">
                <x-ice-card :interactive="true">
                    <div class="row">
                        <div class="col-md-6 border-end border-secondary border-opacity-25 mb-3 mb-md-0">
                            <h6 class="text-success font-cinzel mb-3 text-center">THE STRENGTHS</h6>
                            <ul class="list-unstyled text-light px-2 small">
                                @foreach ($hero->pros ?? [] as $p)
                                    <li class="mb-2 d-flex"><span class="me-2 text-success">✦</span>
                                        {{ $p }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-danger font-cinzel mb-3 text-center">THE WEAKNESSES</h6>
                            <ul class="list-unstyled text-light px-2 small">
                                @foreach ($hero->cons ?? [] as $c)
                                    <li class="mb-2 d-flex"><span class="me-2 text-danger">✦</span> {{ $c }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </x-ice-card>
            </div>
        @endif
    </div>

    <div class="container-fluid py-5 position-relative fade-in-anim"
        style="background: linear-gradient(to bottom, #050b14, #0b1622); animation-delay: 0.6s;">
        <div class="container">
            <h3 class="text-center text-ice mb-5 font-cinzel" style="letter-spacing: 5px;">ABILITIES OF THE FROZEN</h3>

            <div id="abilityCarousel" class="carousel slide" data-bs-ride="false">
                <div class="carousel-inner">
                    @foreach ($hero->abilities as $index => $ability)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <div class="row justify-content-center align-items-center" style="min-height: 300px;">
                                @php
                                    $isPassive = str_contains(strtolower($ability->behavior), 'passive');
                                    $isHidden = str_contains(strtolower($ability->behavior), 'hidden');
                                    $hasVideo = $ability->video_url && !$isPassive && !$isHidden;
                                @endphp

                                @if ($hasVideo)
                                    <div class="col-lg-6 mb-3 mb-lg-0">
                                        <div
                                            class="ratio ratio-16x9 border border-ice shadow-lg rounded overflow-hidden">
                                            <video autoplay muted loop playsinline disablePictureInPicture
                                                class="object-fit-cover no-interaction">
                                                <source src="{{ $ability->video_url }}" type="video/mp4">
                                            </video>
                                        </div>
                                    </div>
                                    <div class="col-lg-5"><x-ability-card :ability="$ability" :isActive="true" /></div>
                                @else
                                    <div class="col-lg-5">
                                        <div class="shadow-lg transform-scale-105"><x-ability-card :ability="$ability"
                                                :isActive="true" /></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#abilityCarousel"
                    data-bs-slide="prev" style="width: 5%;"><span class="carousel-control-prev-icon"></span></button>
                <button class="carousel-control-next" type="button" data-bs-target="#abilityCarousel"
                    data-bs-slide="next" style="width: 5%;"><span class="carousel-control-next-icon"></span></button>
            </div>
        </div>
    </div>
    <div class="hero-navigation d-none d-md-block">
        <a href="{{ route('hero.show', $prevHero->id) }}" class="nav-arrow prev-arrow text-decoration-none">
            <div class="nav-content">
                <span class="font-cinzel text-ice small mb-1">PREVIOUS</span>
                <img src="{{ $prevHero->img_url }}" class="nav-img border border-info">
                <span class="text-white small fw-bold mt-1 text-uppercase">{{ $prevHero->name_localized }}</span>
            </div>
        </a>

        <a href="{{ route('hero.show', $nextHero->id) }}" class="nav-arrow next-arrow text-decoration-none">
            <div class="nav-content">
                <span class="font-cinzel text-ice small mb-1">NEXT</span>
                <img src="{{ $nextHero->img_url }}" class="nav-img border border-info">
                <span class="text-white small fw-bold mt-1 text-uppercase">{{ $nextHero->name_localized }}</span>
            </div>
        </a>
    </div>


    <x-slot:scripts>
        <style>
            /* CSS PENTING UNTUK READ MORE 3 BARIS */
            .playstyle-text {
                transition: all 0.3s ease;
            }
            
            /* Kondisi saat text dilipat (Collapsed) */
            .playstyle-text.collapsed {
                display: -webkit-box;
                -webkit-line-clamp: 3; /* BATASI HANYA 3 BARIS */
                -webkit-box-orient: vertical;
                overflow: hidden;
                text-overflow: ellipsis;
                max-height: 4.8em; /* Fallback height (sekitar 3 baris x 1.6 line-height) */
            }
        </style>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const text = document.getElementById('playstyleContent');
                const btn = document.getElementById('readMoreBtn');

                if (text) {
                    // Cek tinggi asli teks
                    // Jika tinggi > 80px (kira-kira lebih dari 3 baris), maka lipat.
                    if (text.scrollHeight > 80) { 
                        text.classList.add('collapsed'); // Tambah class pembatas
                        btn.classList.remove('d-none');  // Munculkan tombol
                    } else {
                        btn.classList.add('d-none');     // Sembunyikan tombol
                    }
                }
            });

            function togglePlaystyle() {
                const text = document.getElementById('playstyleContent');
                const btn = document.getElementById('readMoreBtn');
                
                // Toggle Logic
                if (text.classList.contains('collapsed')) {
                    // BUKA (EXPAND)
                    text.classList.remove('collapsed');
                    btn.innerText = 'SHOW LESS ▲';
                } else {
                    // TUTUP (COLLAPSE)
                    text.classList.add('collapsed');
                    btn.innerText = 'READ MORE ▼';
                }
            }
        </script>
    </x-slot>
</x-layout>