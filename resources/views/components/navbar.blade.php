<nav class="navbar navbar-expand-lg fixed-top navbar-dota">
    <div class="container">
        <a class="navbar-brand frozen-text text-white" href="/" data-text="DOTA WIKI">
            DOTA WIKI
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-4">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('heroes*') ? 'active' : '' }}" href="/heroes">HEROES</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('items') ? 'active' : '' }}" href="/items">ITEMS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/meta">META</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">COMMUNITY</a>
                </li>
            </ul>

        </div>
    </div>
</nav>
{{-- <div style="height: 80px;"></div> --}}
