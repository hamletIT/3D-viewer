@php
    $landingSettings = \App\Models\Setting::where('key', 'like', 'landing_%')->pluck('value', 'key');
    $heroTitle = $landingSettings['landing_hero_title'] ?? '3D Workshop Explorer';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How to Use — {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Figtree', system-ui, sans-serif; background: #03030a; color: #e2e8f0; overflow-x: hidden; }
        .prose-custom h2 { font-size: 1.5rem; font-weight: 700; margin-top: 2rem; margin-bottom: 0.75rem; color: #f1f5f9; }
        .prose-custom h3 { font-size: 1.2rem; font-weight: 600; margin-top: 1.5rem; margin-bottom: 0.5rem; color: #e2e8f0; }
        .prose-custom p { margin-bottom: 1rem; line-height: 1.7; color: #94a3b8; }
        .prose-custom ul, .prose-custom ol { margin-bottom: 1rem; padding-left: 1.5rem; color: #94a3b8; }
        .prose-custom li { margin-bottom: 0.25rem; line-height: 1.6; }
        .prose-custom strong { color: #e2e8f0; }
        .prose-custom img { max-width: 100%; height: auto; border-radius: 0.75rem; margin: 1.5rem 0; border: 1px solid rgba(255,255,255,0.06); }
        .instruction-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.03) 0%, rgba(255,255,255,0.01) 100%);
            border: 1px solid rgba(255,255,255,0.06);
            transition: border-color 0.3s ease;
        }
        .instruction-card:hover { border-color: rgba(99,102,241,0.3); }
        .prose-custom kbd {
            display: inline-block;
            padding: 0.15rem 0.45rem;
            font-size: 0.75rem;
            font-family: monospace;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 3px;
            color: #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="min-h-screen bg-radial" style="background: radial-gradient(ellipse at 50% 0%, #0f0f2a 0%, #03030a 60%);">
        <header class="flex items-center justify-between px-6 py-5 max-w-6xl mx-auto">
            <div class="flex items-center gap-2 text-lg font-semibold tracking-wide">
                <span class="text-2xl">⚒️</span>
                <span class="text-white/90">{{ $heroTitle }}</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('landing') }}" class="px-5 py-2 rounded-lg text-sm font-medium text-white/70 hover:text-white transition-colors">&larr; Back</a>
                @auth
                    <a href="{{ route('app') }}" class="px-5 py-2 rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-500 transition-colors">Launch App</a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2 rounded-lg text-sm font-medium text-white/70 border border-white/15 hover:border-indigo-500/50 transition-colors">Log in</a>
                @endauth
            </div>
        </header>

        <section class="px-6 py-16 max-w-4xl mx-auto">
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white glow-text text-center mb-4">How to Use</h1>
            <p class="text-white/40 text-center mb-12 max-w-lg mx-auto">Step-by-step guide to get the most out of the 3D Workshop.</p>

            @forelse ($instructions as $i)
                <div class="instruction-card rounded-2xl p-8 mb-8">
                    @if ($i->image_path)
                        <img src="/storage/{{ $i->image_path }}" alt="{{ $i->title }}" class="w-full rounded-xl mb-6" loading="lazy">
                    @endif
                    <h2 class="text-xl font-bold text-white/90 mb-4">{{ $i->title }}</h2>
                    <div class="prose-custom">
                        {!! $i->content !!}
                    </div>
                </div>
            @empty
                <div class="text-center py-16">
                    <div class="text-5xl mb-4 opacity-30">📖</div>
                    <p class="text-white/30">No instructions available yet.</p>
                </div>
            @endforelse
        </section>

        <footer class="border-t border-white/5 px-6 py-8">
            <div class="max-w-6xl mx-auto text-center text-sm text-white/30">
                <a href="{{ route('landing') }}" class="hover:text-white/50 transition-colors">&larr; Back to home</a>
            </div>
        </footer>
    </div>
</body>
</html>
