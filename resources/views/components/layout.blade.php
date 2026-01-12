<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dota 2 Wiki' }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <link href="{{ asset('css/frozen-theme.css') }}" rel="stylesheet">
    
    {{ $styles ?? '' }}
</head>
<body>

    <x-navbar />

    <main>
        {{ $slot }}
    </main>

    <x-footer />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <div id="snow-container" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 99999;"></div>
    
    <script>
        const snowContainer = document.getElementById('snow-container');
        if(snowContainer) {
            for(let i=0; i<50; i++) {
                const div = document.createElement('div');
                div.classList.add('snowflake');
                const size = Math.random() * 3 + 2 + 'px'; // Ukuran acak
                div.style.width = size; 
                div.style.height = size;
                div.style.left = Math.random() * 100 + 'vw';
                div.style.animationDuration = (Math.random() * 5 + 10) + 's'; // Kecepatan acak
                div.style.animationDelay = (Math.random() * 5) + 's';
                snowContainer.appendChild(div);
            }
        }
    </script>

    {{ $scripts ?? '' }}
</body>
</html>