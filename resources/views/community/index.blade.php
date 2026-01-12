@php
    use Illuminate\Support\Facades\File;
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Cache;

    $latestPatch = null;
    $patchPath = storage_path('app/patch.json');
    if (File::exists($patchPath)) {
        $patches = json_decode(File::get($patchPath), true);
        if (is_array($patches) && count($patches) > 0) {
            $latestPatch = end($patches);
        }
    }
    $esportsMatch = Cache::remember('community_esports_match', 300, function () {
        try {
            $response = Http::timeout(2)->get('https://api.opendota.com/api/proMatches');
            return $response->json()[0] ?? null;
        } catch (\Exception $e) {
            return null;
        }
    });
@endphp

<x-layout>
    <x-slot:title>Community - Frozen Wiki</x-slot>

    <div class="container py-5 mt-4 fade-in-anim">
        <div class="row g-4">
            
            <div class="col-lg-4">
                
                <div class="mb-4">
                    <x-ice-card>
                        <h5 class="font-cinzel text-ice border-bottom border-secondary pb-2 mb-3">
                            GUIDE CATEGORIES
                        </h5>
                        <div class="d-grid gap-2">
                            <a href="{{ route('community.newbie') }}" class="btn btn-outline-info text-start btn-sm">
                                🔰 Newbie Guide
                            </a>
                            <a href="{{ route('community.hero') }}" class="btn btn-outline-info text-start btn-sm">
                                ⚔️ Hero Guide
                            </a>
                            <a href="{{ route('community.item') }}" class="btn btn-outline-info text-start btn-sm">
                                💎 Item Guide
                            </a>
                        </div>
                    </x-ice-card>
                </div>

                <div class="mb-4">
                    <x-ice-card :interactive="true">
                        <span class="badge bg-info mb-2">GAME UPDATE</span>
                        <h5 class="text-white font-cinzel mb-2">
                            Patch {{ $latestPatch['name'] ?? 'Latest' }}
                        </h5>
                        <p class="text-secondary x-small mb-3">
                            Gameplay update is live. Check the full changelog.
                        </p>
                        <a href="{{ route('patch.show', ['version' => $latestPatch['name'] ?? '7.37']) }}" class="btn btn-sm btn-dark w-100 border-secondary text-ice">
                            READ PATCH NOTES
                        </a>
                    </x-ice-card>
                </div>

                <div class="mb-4">
                    <x-ice-card :interactive="true">
                        <span class="badge bg-warning text-dark mb-2">LIVE RESULT</span>
                        @if($esportsMatch)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="{{ $esportsMatch['radiant_win'] ? 'text-success fw-bold' : 'text-danger' }}">
                                    {{ $esportsMatch['radiant_name'] ?? 'Radiant' }}
                                </span>
                                <span class="text-muted small">VS</span>
                                <span class="{{ !$esportsMatch['radiant_win'] ? 'text-success fw-bold' : 'text-danger' }}">
                                    {{ $esportsMatch['dire_name'] ?? 'Dire' }}
                                </span>
                            </div>
                            <a href="{{ route('match.show', ['match_id' => $esportsMatch['match_id']]) }}" class="btn btn-sm btn-dark w-100 border-secondary text-warning">
                                VIEW MATCH
                            </a>
                        @else
                            <p class="text-secondary x-small">No live match data.</p>
                        @endif
                    </x-ice-card>
                </div>

            </div>

            <div class="col-lg-8">
                
                <div class="mb-5">
                    <x-ice-card>
                        <form action="{{ route('community.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="d-flex gap-3 mb-3">
                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ Auth::user()->name }}" 
                                     class="rounded-circle border border-secondary" width="45" height="45">
                                <textarea name="content" rows="2" class="form-control bg-black text-white border-secondary" 
                                          placeholder="Share your strategy or ask something, {{ Auth::user()->name }}..."></textarea>
                            </div>
                            
                            <div class="row g-2 align-items-center">
                                <div class="col-md-4">
                                    <select name="category" class="form-select form-select-sm bg-dark text-white border-secondary">
                                        <option value="general">General Discussion</option>
                                        <option value="hero_guide">Hero Guide</option>
                                        <option value="item_build">Item Build</option>
                                        <option value="newbie_help">Newbie Help</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <input type="file" name="media" class="form-control form-control-sm bg-dark text-secondary border-secondary">
                                </div>
                                <div class="col-md-3 text-end">
                                    <button type="submit" class="btn btn-info btn-sm w-100 fw-bold">POST</button>
                                </div>
                            </div>
                        </form>
                    </x-ice-card>
                </div>

                <h4 class="text-white font-cinzel mb-4">RECENT DISCUSSIONS</h4>

                @forelse($posts as $post)
                    <div class="mb-4">
                        <x-ice-card :interactive="true">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex gap-3">
                                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ $post->user->name }}" 
                                         class="rounded-circle border border-info" width="40" height="40">
                                    <div>
                                        <h6 class="text-white mb-0 fw-bold">{{ $post->user->name }}</h6>
                                        <small class="text-secondary" style="font-size: 0.75rem;">
                                            {{ $post->created_at->diffForHumans() }} &bull; 
                                            <span class="text-info">{{ ucwords(str_replace('_', ' ', $post->category)) }}</span>
                                        </small>
                                    </div>
                                </div>
                                
                                @if(Auth::id() === $post->user_id)
                                    <button class="btn btn-link text-secondary p-0"><i class="fas fa-ellipsis-v"></i></button>
                                @endif
                            </div>

                            <p class="text-light mb-3" style="white-space: pre-line;">{{ $post->content }}</p>

                            @if($post->image)
                                <div class="mb-3 rounded overflow-hidden border border-secondary">
                                    <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid w-100">
                                </div>
                            @endif

                            @if($post->video)
                                <div class="mb-3 rounded overflow-hidden border border-secondary">
                                    <video controls class="w-100">
                                        <source src="{{ asset('storage/' . $post->video) }}" type="video/mp4">
                                        Browser not supporting video.
                                    </video>
                                </div>
                            @endif

                            <div class="d-flex gap-4 border-top border-secondary border-opacity-25 pt-3 mt-2">
                                <form action="{{ route('post.like', $post) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-decoration-none p-0 {{ $post->isLikedByAuthUser() ? 'text-info fw-bold' : 'text-secondary' }}">
                                        👍 {{ $post->likes->count() }} Likes
                                    </button>
                                </form>
                                
                                <button class="btn btn-link text-decoration-none p-0 text-secondary">
                                    💬 {{ $post->comments->count() }} Comments
                                </button>
                            </div>

                        </x-ice-card>
                    </div>
                @empty
                    <div class="text-center py-5 border border-secondary border-dashed rounded bg-black bg-opacity-25">
                        <h1 class="text-secondary opacity-25 display-1">📭</h1>
                        <p class="text-secondary">No discussions yet. Be the first to start!</p>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</x-layout>