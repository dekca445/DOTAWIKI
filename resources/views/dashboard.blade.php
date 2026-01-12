<x-layout>
    <x-slot:title>Dashboard - {{ Auth::user()->name }}</x-slot>

    <div class="container py-5 mt-5 fade-in-anim">
        <div class="row mb-4 align-items-center">
            <div class="col">
                <h2 class="frozen-text text-uppercase mb-0" data-text="COMMAND CENTER">COMMAND CENTER</h2>
                <p class="text-secondary small">Welcome back, {{ Auth::user()->name }}</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-info btn-sm">
                    ⚙️ SETTINGS
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-3">
                <x-ice-card>
                    <div class="text-center">
                        <div class="position-relative d-inline-block mb-3">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ Auth::user()->name }}" 
                                 class="rounded-circle border border-info shadow-lg p-1 bg-black" 
                                 width="100" height="100" alt="Avatar">
                        </div>
                        
                        <h4 class="text-white font-cinzel mb-1">{{ Auth::user()->name }}</h4>
                        <p class="text-secondary x-small mb-3">{{ Auth::user()->email }}</p>

                        <div class="d-flex justify-content-around border-top border-bottom border-secondary border-opacity-25 py-2 mb-3">
                            <div class="text-center">
                                <div class="h5 text-white fw-bold mb-0">{{ Auth::user()->followers()->count() ?? 0 }}</div>
                                <small class="text-muted x-small text-uppercase">Followers</small>
                            </div>
                            <div class="text-center border-start border-secondary border-opacity-25"></div>
                            <div class="text-center">
                                <div class="h5 text-white fw-bold mb-0">{{ Auth::user()->following()->count() ?? 0 }}</div>
                                <small class="text-muted x-small text-uppercase">Following</small>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-dark border-secondary text-secondary">
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </x-ice-card>
                
                {{-- <div class="mt-4">
                    <x-ice-card>
                        <h6 class="text-ice font-cinzel mb-3 small border-bottom border-secondary pb-2">QUICK ACCESS</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><a href="{{ route('community.index') }}" class="text-decoration-none text-light hover-ice">Example: My Community Posts</a></li>
                            <li class="mb-2"><a href="#" class="text-decoration-none text-light hover-ice">Example: Saved Guides</a></li>
                        </ul>
                    </x-ice-card>
                </div> --}}
            </div>

            <div class="col-lg-6">
                <div class="mb-4">
                    <x-ice-card>
                        <div class="d-flex gap-3 align-items-center">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ Auth::user()->name }}" 
                                 class="rounded-circle" width="40">
                            <a href="{{ route('community.index') }}" class="form-control bg-black border-secondary text-secondary rounded-pill" style="text-decoration: none; padding-top: 10px;">
                                Share your strategy, {{ Auth::user()->name }}?
                            </a>
                        </div>
                    </x-ice-card>
                </div>

                <h5 class="text-secondary font-cinzel mb-3 small">YOUR RECENT ACTIVITY</h5>

                @forelse($myPosts as $post)
                    <div class="mb-3">
                        <x-ice-card :interactive="true">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="badge bg-secondary bg-opacity-25 border border-secondary text-info">
                                        {{ ucwords(str_replace('_', ' ', $post->category)) }}
                                    </span>
                                    <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                </div>
                                
                                <button class="btn btn-link text-secondary p-0 btn-sm">•••</button>
                            </div>

                            <p class="text-white mb-3" style="font-size: 0.95rem;">
                                {{ Str::limit($post->content, 150) }}
                            </p>

                            @if($post->image)
                                <div class="mb-3 rounded overflow-hidden border border-secondary">
                                    <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid w-100 object-fit-cover" style="max-height: 200px;">
                                </div>
                            @endif

                            <div class="d-flex gap-4 border-top border-secondary border-opacity-25 pt-2 mt-2">
                                <div class="text-secondary small">
                                    <span class="text-info">▲</span> {{ $post->likes()->count() }} Likes
                                </div>
                                <div class="text-secondary small">
                                    <span>💬</span> {{ $post->comments()->count() }} Comments
                                </div>
                            </div>
                        </x-ice-card>
                    </div>
                @empty
                    <div class="text-center py-5 border border-secondary border-dashed rounded bg-black bg-opacity-25">
                        <h1 class="text-secondary opacity-25 display-4">📝</h1>
                        <p class="text-secondary">You haven't posted anything yet.</p>
                        <a href="{{ route('community.index') }}" class="btn btn-outline-info btn-sm mt-2">Create First Post</a>
                    </div>
                @endforelse
            </div>

            <div class="col-lg-3">
                <x-ice-card>
                    <h6 class="text-warning font-cinzel mb-3 small border-bottom border-secondary pb-2">BATTLE STATS</h6>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small">Total Posts</span>
                        <span class="text-white fw-bold">{{ $myPosts->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small">Total Likes</span>
                        <span class="text-white fw-bold">0</span> </div>
                    
                    <div class="mt-4 pt-3 border-top border-secondary border-opacity-25 text-center">
                        <small class="text-muted d-block mb-1">Account Status</small>
                        <span class="badge bg-success bg-opacity-25 text-success border border-success">ACTIVE AGENT</span>
                    </div>
                </x-ice-card>
            </div>
        </div>
    </div>

    <style>
        .hover-ice:hover { color: #00d9ff !important; text-shadow: 0 0 5px #00d9ff; }
        .text-decoration-none { text-decoration: none; }
    </style>
</x-layout>