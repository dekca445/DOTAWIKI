<nav class="navbar navbar-expand-lg fixed-top navbar-dota" style="background-color: #0b0f19; border-bottom: 1px solid rgba(255,255,255,0.1);">
    <div class="container">
        <a class="navbar-brand frozen-text text-white font-cinzel" href="{{ route('home') }}" data-text="DOTA WIKI">
            DOTA WIKI
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-4 align-items-center">
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active text-info' : 'text-secondary' }}" href="{{ route('home') }}">HOME</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('heroes.*') ? 'active text-info' : 'text-secondary' }}" href="{{ route('heroes.index') }}">HEROES</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('items.*') ? 'active text-info' : 'text-secondary' }}" href="{{ route('items.index') }}">ITEMS</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('community.*') ? 'active text-info' : 'text-secondary' }}" href="{{ route('community.index') }}">COMMUNITY</a>
                </li>

                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle p-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ Auth::user()->name }}" 
                                 class="rounded-circle border border-info" width="30" height="30" alt="Avatar">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark shadow-lg">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Log Out</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link text-white fw-bold border border-secondary px-3 rounded-pill" href="{{ route('login') }}">LOGIN</a>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>