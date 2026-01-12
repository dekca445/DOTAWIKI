<x-layout>
    <x-slot:title>Welcome to Frozen Wiki</x-slot>

    <div class="position-relative d-flex align-items-center justify-content-center text-center"
        style="height: 100vh; background: #000;">
        <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden" style="z-index: 0;">
            <video autoplay muted loop playsinline class="w-100 h-100 object-fit-cover" style="opacity: 0.6;">
                <source
                    src="https://cdn.cloudflare.steamstatic.com/apps/dota2/videos/dota_react/homepage/dota_montage_webm.webm"
                    type="video/webm">
            </video>
            <div class="position-absolute top-0 start-0 w-100 h-100"
                style="background: radial-gradient(circle, rgba(0,0,0,0.2) 0%, #050b14 100%);"></div>
        </div>

        <div class="position-relative z-2 container fade-in-anim">
            <h1 class="display-1 frozen-text mb-4" data-text="FROZEN WIKI">FROZEN WIKI</h1>
            <p class="lead text-light mb-5" style="max-width: 700px; margin: 0 auto; text-shadow: 0 2px 4px black;">
                The ultimate database for the Ancient.
            </p>

            @auth
                <a href="/dashboard" class="btn btn-lg btn-outline-info px-5 py-3 font-cinzel fw-bold box-neon">
                    GO TO DASHBOARD
                </a>
            @else
                <div class="d-flex gap-3 justify-content-center">
                    <a href="{{ route('login') }}" class="btn btn-lg btn-info px-5 py-3 font-cinzel fw-bold shadow-lg">
                        LOGIN
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-lg btn-outline-light px-5 py-3 font-cinzel fw-bold">
                        REGISTER
                    </a>
                </div>
            @endauth
        </div>
    </div>

    {{-- <div class="container py-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <h4 class="frozen-text mb-0" data-text="CURRENT META">CURRENT META</h4>
            <a href="/meta" class="text-ice small text-decoration-none fw-bold">VIEW FULL META &rarr;</a>
        </div>

        <div class="card bg-black border border-secondary p-4 shadow-lg">
            <div class="row text-secondary small fw-bold mb-3 text-uppercase border-bottom border-secondary pb-2">
                <div class="col-4">Hero</div>
                <div class="col-4">Pro Pick Rate</div>
                <div class="col-4">Win Rate</div>
            </div>

            @foreach ($topWinrate as $h)
                <div class="row align-items-center mb-3 hero-meta-row p-2 rounded">
                    <div class="col-4 d-flex align-items-center">
                        <img src="{{ $h->icon_url }}" width="32" class="me-2 rounded shadow-sm">
                        <span class="text-white fw-bold small">{{ $h->name_localized }}</span>
                    </div>

                    <div class="col-4">
                        <div class="d-flex align-items-center">
                            <span class="text-white small me-2" style="width: 40px;">{{ $h->pro_pick }}</span>
                            <div class="progress flex-grow-1" style="height: 6px; background: #222;">
                                <div class="progress-bar bg-secondary"
                                    style="width: {{ min($h->pro_pick / 20, 100) }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        @php $wr = $h->pro_pick > 0 ? ($h->pro_win / $h->pro_pick)*100 : 0; @endphp
                        <div class="d-flex align-items-center">
                            <span class="{{ $wr >= 50 ? 'text-success' : 'text-danger' }} small me-2"
                                style="width: 40px;">{{ number_format($wr, 1) }}%</span>
                            <div class="progress flex-grow-1" style="height: 6px; background: #222;">
                                <div class="progress-bar {{ $wr >= 50 ? 'bg-success' : 'bg-danger' }}"
                                    style="width: {{ $wr }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div> --}}

    <div class="container-fluid py-5" style="background: #050b14; border-top: 1px solid var(--ice-border);">
        <div class="container">
            <h2 class="text-center frozen-text mb-5" data-text="LATEST NEWS">LATEST NEWS</h2>

            <div class="row g-4 justify-content-center">

                <div class="col-md-6 col-lg-5">
                    <x-ice-card :interactive="true">
                        <span class="badge bg-info mb-3">
                            UPDATE {{ $latestPatch['name'] ?? 'UNKNOWN' }}
                        </span>

                        <h4 class="text-white font-cinzel mb-3">
                            Dota Patch {{ $latestPatch['name'] ?? 'Latest' }} Released
                        </h4>

                        <p class="text-secondary small mb-4">
                            @if ($latestPatch && isset($latestPatch['date']))
                                Gameplay update {{ $latestPatch['name'] }} is now live. Released on
                                {{ date('F j, Y', strtotime($latestPatch['date'])) }}. Check the full changelog for
                                details.
                            @else
                                New gameplay updates are live. Check the latest patch notes for hero balancing details.
                            @endif
                        </p>

                        <a href="{{ route('patch.show', ['version' => $latestPatch['name'] ?? '7.37']) }}"
                            class="text-ice small fw-bold text-decoration-none">
                            READ PATCH NOTES &rarr;
                        </a>
                    </x-ice-card>
                </div>

                <div class="col-md-6 col-lg-5">
                    <x-ice-card :interactive="true">
                        <span class="badge bg-warning text-dark mb-3">ESPORTS RESULT</span>

                        @if ($esportsMatch)
                            <h4 class="text-white font-cinzel mb-3 text-truncate"
                                title="{{ $esportsMatch['league_name'] ?? 'Pro Circuit' }}">
                                {{ $esportsMatch['league_name'] ?? 'Professional Match' }}
                            </h4>

                            <p class="text-secondary small mb-4">
                                <span
                                    class="{{ $esportsMatch['radiant_win'] ? 'text-success fw-bold' : 'text-danger' }}">
                                    {{ $esportsMatch['radiant_name'] ?? 'Radiant' }}
                                </span>
                                <span class="mx-2 text-white">VS</span>
                                <span
                                    class="{{ !$esportsMatch['radiant_win'] ? 'text-success fw-bold' : 'text-danger' }}">
                                    {{ $esportsMatch['dire_name'] ?? 'Dire' }}
                                </span>
                                <br>
                                <span class="d-block mt-2 text-white-50">
                                    Winner: <strong
                                        class="text-warning">{{ $esportsMatch['radiant_win'] ? $esportsMatch['radiant_name'] ?? 'Radiant' : $esportsMatch['dire_name'] ?? 'Dire' }}</strong>
                                </span>
                            </p>

                            <a href="{{ route('match.show', ['match_id' => $esportsMatch['match_id']]) }}"
                                class="text-ice small fw-bold text-decoration-none">
                                VIEW MATCH DETAILS &rarr;
                            </a>
                        @else
                            <h4 class="text-white font-cinzel mb-3">Professional Circuit</h4>
                            <p class="text-secondary small mb-4">
                                No live match data available at the moment. Check back later for tournament updates.
                            </p>
                            <a href="#" class="text-muted small fw-bold text-decoration-none">VIEW BRACKETS
                                &rarr;</a>
                        @endif
                    </x-ice-card>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hero-meta-row:hover {
            background: rgba(255, 255, 255, 0.05);
            transition: background 0.2s;
        }
    </style>
</x-layout>
