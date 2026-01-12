<footer class="bg-black border-top border-secondary mt-5 py-5 position-relative overflow-hidden" style="background: #050b14;">
    <div class="container position-relative z-2">
        <div class="row g-4 justify-content-center text-center text-md-start">
            
            <div class="col-12 col-md-4 mb-3 mb-md-0">
                <h3 class="frozen-text mb-3" data-text="DOTA WIKI">DOTA WIKI</h3>
                <p class="text-secondary small mx-auto mx-md-0" style="max-width: 300px;">
                    The ultimate compendium for the Ancient. Detailed stats, mechanics, and strategies.
                </p>
                <p class="text-secondary small mt-4">&copy; {{ date('Y') }} Frozen Wiki.</p>
            </div>
            
            <div class="col-12 col-md-4">
                <div class="row">
                    <div class="col-6 mb-3">
                        <h6 class="text-ice font-cinzel mb-3">DATABASE</h6>
                        <ul class="list-unstyled small gap-2 d-flex flex-column">
                            <li><a href="/heroes" class="text-secondary text-decoration-none footer-link">Heroes</a></li>
                            <li><a href="/items" class="text-secondary text-decoration-none footer-link">Items</a></li>
                            <li><a href="/meta" class="text-secondary text-decoration-none footer-link">Meta</a></li>
                        </ul>
                    </div>

                    <div class="col-6 mb-3">
                        <h6 class="text-ice font-cinzel mb-3">COMMUNITY</h6>
                        <ul class="list-unstyled small gap-2 d-flex flex-column">
                            @auth
                                <li><a href="/dashboard" class="text-secondary text-decoration-none footer-link">Dashboard</a></li>
                            @else
                                <li><a href="{{ route('login') }}" class="text-secondary text-decoration-none footer-link">Login</a></li>
                                <li><a href="{{ route('register') }}" class="text-secondary text-decoration-none footer-link">Register</a></li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <h6 class="text-ice font-cinzel mb-3">DISCLAIMER</h6>
                <p class="text-secondary small mx-auto mx-md-0" style="font-size: 0.7rem; line-height: 1.6; max-width: 300px;">
                    Dota 2 is a registered trademark of Valve Corporation. This website is not affiliated with, endorsed, or sponsored by Valve Corporation. All game images and names are property of Valve Corporation.
                </p>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer-link:hover { color: var(--ice-blue) !important; text-shadow: 0 0 5px var(--ice-blue); }
    /* Fix tambahan agar footer tidak terlalu mepet di HP */
    @media (max-width: 768px) {
        footer { padding-top: 2rem !important; padding-bottom: 2rem !important; }
    }
</style>