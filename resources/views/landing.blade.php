@php
    $allSections = \App\Models\LandingSection::where('active', true)->orderBy('sort_order')->get();
    $sections = $allSections->keyBy('slug');
    $featuresSection = $sections->get('features');
    $features = $featuresSection ? $featuresSection->features : collect();
    $brandSection = $sections->get('brand');
    $heroSection = $sections->get('hero');
    $scanSection = $sections->get('scan');
    $footerSection = $sections->get('footer');
    $knownSlugs = ['brand', 'hero', 'features', 'scan', 'footer'];
    $extraSections = $allSections->reject(fn($s) => in_array($s->slug, $knownSlugs));
    $brandTitle = $brandSection?->title ?? '3D Workshop Explorer';
    $heroTitle = $heroSection?->title ?? '3D Workshop Explorer';
    $heroSubtitle = $heroSection?->subtitle ?? 'Upload. Explore. Create. A powerful 3D model viewer with real-time object manipulation.';
    $ctaText = $heroSection?->link_text ?? 'Get Started';
    $heroIcon = $heroSection?->icon ?? '⚒️';
    $showLogin = $heroSection?->data['show_login'] ?? true;
    $showRegister = $heroSection?->data['show_register'] ?? true;
    $featuresTitle = $featuresSection?->title ?? 'Everything you need';
    $featuresSubtitle = $featuresSection?->subtitle ?? 'Explore, manipulate, and create with powerful 3D tools.';
    $scanIcon = $scanSection?->icon ?? '📱';
    $scanTitle = $scanSection?->title ?? 'Scan Real Objects with 3D Snap';
    $scanContent = $scanSection?->content;
    $appName = config('app.name', 'Workshop');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $heroTitle }} — {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Figtree', system-ui, sans-serif; background: #03030a; color: #e2e8f0; overflow-x: hidden; }
        .bg-radial { background: radial-gradient(ellipse at 50% 0%, #0f0f2a 0%, #03030a 60%); }
        .bg-radial-2 { background: radial-gradient(ellipse at 50% 100%, #0f0f1a 0%, transparent 60%); }
        .glow { box-shadow: 0 0 60px -20px rgba(99,102,241,0.3); }
        .glow-text { text-shadow: 0 0 40px rgba(99,102,241,0.15); }
        .feature-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.03) 0%, rgba(255,255,255,0.01) 100%);
            border: 1px solid rgba(255,255,255,0.06);
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            border-color: rgba(99,102,241,0.3);
            transform: translateY(-2px);
        }
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            transition: all 0.2s ease;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 30px -8px rgba(99,102,241,0.5); }
        .btn-outline {
            border: 1px solid rgba(255,255,255,0.15);
            transition: all 0.2s ease;
        }
        .btn-outline:hover { border-color: rgba(99,102,241,0.5); background: rgba(99,102,241,0.1); }
        .hero-icon { animation: float 3s ease-in-out infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(-5deg); }
            50% { transform: translateY(-10px) rotate(5deg); }
        }
        .sparkle {
            position: absolute;
            width: 3px; height: 3px;
            background: #818cf8;
            border-radius: 50%;
            animation: sparkle 3s infinite;
        }
        @keyframes sparkle {
            0%, 100% { opacity: 0; transform: scale(0); }
            50% { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="min-h-screen bg-radial">
        <header class="flex items-center justify-between px-6 py-5 max-w-6xl mx-auto">
            <a href="{{ $brandSection?->link_url ?: '/' }}" class="flex items-center gap-2 text-lg font-semibold tracking-wide no-underline">
                <span class="text-2xl">{{ $brandSection?->icon ?? '⚒️' }}</span>
                <span class="text-white/90">{{ $brandTitle }}</span>
            </a>
            <div class="flex items-center gap-3">
                @auth
                    @if (in_array(auth()->user()->role, ['admin', 'moderator']))
                        <a href="{{ route('admin.dashboard') }}" class="btn-outline px-5 py-2 rounded-lg text-sm font-medium text-white/80 transition-colors">Admin Panel</a>
                    @endif
                    <a href="{{ route('user.dashboard') }}" class="btn-outline px-5 py-2 rounded-lg text-sm font-medium text-white/80 transition-colors">Dashboard</a>
                    <a href="{{ route('app') }}" class="btn-primary px-5 py-2 rounded-lg text-sm font-medium text-white shadow-lg">Launch App</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-white/40 hover:text-white/70 transition-colors">Log out</button>
                    </form>
                @else
                    @if ($showLogin)
                        <a href="{{ route('login') }}" class="btn-outline px-5 py-2 rounded-lg text-sm font-medium text-white/80 transition-colors">Log in</a>
                    @endif
                    @if ($showRegister)
                        <a href="{{ route('register') }}" class="btn-primary px-5 py-2 rounded-lg text-sm font-medium text-white shadow-lg">Register</a>
                    @endif
                @endauth
            </div>
        </header>

        <section class="relative flex flex-col items-center justify-center px-6 pt-24 pb-20 text-center">
            <div class="sparkle" style="top:20%;left:15%;animation-delay:0s;"></div>
            <div class="sparkle" style="top:30%;right:20%;animation-delay:1s;"></div>
            <div class="sparkle" style="top:60%;left:10%;animation-delay:2s;"></div>
            <div class="sparkle" style="top:50%;right:15%;animation-delay:0.5s;"></div>

            @if ($heroSection?->link_url)
                <a href="{{ $heroSection->link_url }}" class="hero-icon text-7xl mb-8 inline-block no-underline">{{ $heroIcon }}</a>
            @else
                <div class="hero-icon text-7xl mb-8">{{ $heroIcon }}</div>
            @endif

            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-white glow-text max-w-3xl leading-tight">
                {{ $heroTitle }}
            </h1>
            <p class="mt-5 text-lg md:text-xl text-white/50 max-w-xl leading-relaxed">
                {{ $heroSubtitle }}
            </p>

            <div class="flex items-center gap-4 mt-10">
                @auth
                    <a href="{{ route('app') }}" class="btn-primary px-8 py-3 rounded-xl text-base font-semibold text-white shadow-lg">Launch App</a>
                @else
                    @if ($showRegister)
                        <a href="{{ route('register') }}" class="btn-primary px-8 py-3 rounded-xl text-base font-semibold text-white shadow-lg">{{ $ctaText }}</a>
                    @endif
                    @if ($showLogin)
                        <a href="{{ route('login') }}" class="btn-outline px-8 py-3 rounded-xl text-base font-semibold text-white/70">{{ $heroSection?->data['login_text'] ?? 'I already have an account' }}</a>
                    @endif
                @endauth
            </div>

            @if ($heroSection?->data['show_how_to'] ?? true)
                <div class="mt-6">
                    <a href="{{ route('how-to-use') }}" class="inline-flex items-center gap-2 text-sm text-white/40 hover:text-white/70 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        How to Use
                    </a>
                </div>
            @endif

            @auth
                <div class="mt-12 flex items-center gap-2 text-white/40 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    You're logged in as <span class="text-white/60 font-medium">{{ auth()->user()->name }}</span>
                </div>
            @endauth
        </section>

        @if ($features->count() > 0)
            <section class="bg-radial-2 px-6 py-20">
                <div class="max-w-6xl mx-auto">
                    @if ($featuresSection?->link_url)
                        <a href="{{ $featuresSection->link_url }}" class="no-underline"><h2 class="text-2xl md:text-3xl font-bold text-center text-white/90 mb-3">{{ $featuresTitle }}</h2></a>
                    @else
                        <h2 class="text-2xl md:text-3xl font-bold text-center text-white/90 mb-3">{{ $featuresTitle }}</h2>
                    @endif
                    <p class="text-white/40 text-center mb-14 max-w-lg mx-auto">{{ $featuresSubtitle }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($features as $feature)
                            <div class="feature-card rounded-2xl p-8 glow">
                                <div class="text-4xl mb-5">{{ $feature->icon ?? '🔧' }}</div>
                                <h3 class="text-lg font-semibold text-white/90 mb-3">{{ $feature->title }}</h3>
                                <p class="text-sm text-white/40 leading-relaxed">{{ $feature->description }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if ($scanSection?->active)
        <section class="px-6 py-20 border-t border-white/5">
            <div class="max-w-4xl mx-auto text-center">
                @if ($scanSection->link_url)
                    <a href="{{ $scanSection->link_url }}" class="text-5xl mb-6 inline-block no-underline">{{ $scanIcon }}</a>
                @else
                    <div class="text-5xl mb-6">{{ $scanIcon }}</div>
                @endif
                @if ($scanSection->link_url)
                    <a href="{{ $scanSection->link_url }}" class="no-underline"><h2 class="text-2xl md:text-3xl font-bold text-white/90 mb-3">{{ $scanTitle }}</h2></a>
                @else
                    <h2 class="text-2xl md:text-3xl font-bold text-white/90 mb-3">{{ $scanTitle }}</h2>
                @endif
                @if ($scanContent)
                    <div class="text-white/40 mb-8 max-w-xl mx-auto leading-relaxed">{!! $scanContent !!}</div>
                @endif
                @if ($scanSection->features->count())
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        @foreach ($scanSection->features as $link)
                            <a href="{{ $link->description ?? '#' }}" class="inline-flex items-center gap-3 btn-outline rounded-xl px-6 py-3 text-sm font-medium text-white/70 hover:text-white transition-colors" target="_blank">
                                <span>{{ $link->icon }}</span>
                                <span>{{ $link->title }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
                @if ($scanSection->subtitle)
                    <p class="mt-6 text-xs text-white/20">{{ $scanSection->subtitle }}</p>
                @endif
            </div>
        </section>
        @endif

        @foreach ($extraSections as $section)
        <section class="px-6 py-20 border-t border-white/5">
            <div class="max-w-4xl mx-auto text-center">
                @if ($section->icon)
                    @if ($section->link_url)
                        <a href="{{ $section->link_url }}" class="text-5xl mb-6 inline-block no-underline">{{ $section->icon }}</a>
                    @else
                        <div class="text-5xl mb-6">{{ $section->icon }}</div>
                    @endif
                @endif
                @if ($section->title)
                    @if ($section->link_url)
                        <a href="{{ $section->link_url }}" class="no-underline"><h2 class="text-2xl md:text-3xl font-bold text-white/90 mb-3">{{ $section->title }}</h2></a>
                    @else
                        <h2 class="text-2xl md:text-3xl font-bold text-white/90 mb-3">{{ $section->title }}</h2>
                    @endif
                @endif
                @if ($section->subtitle)
                    <p class="text-white/40 text-center mb-6 max-w-lg mx-auto">{{ $section->subtitle }}</p>
                @endif
                @if ($section->content)
                    <div class="text-white/40 mb-8 max-w-xl mx-auto leading-relaxed">{!! $section->content !!}</div>
                @endif
                @if ($section->features->count())
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($section->features as $feature)
                            <div class="feature-card rounded-2xl p-8 glow">
                                @if ($feature->icon)<div class="text-4xl mb-5">{{ $feature->icon }}</div>@endif
                                <h3 class="text-lg font-semibold text-white/90 mb-3">{{ $feature->title }}</h3>
                                @if ($feature->description)<p class="text-sm text-white/40 leading-relaxed">{{ $feature->description }}</p>@endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
        @endforeach

        <footer class="border-t border-white/5 px-6 py-8">
            <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
                <a href="{{ $footerSection?->link_url ?: '/' }}" class="flex items-center gap-2 text-sm text-white/30 no-underline hover:text-white/50 transition-colors">
                    <span class="text-lg">{{ $brandSection?->icon ?? '⚒️' }}</span>
                    <span>&copy; {{ date('Y') }} {{ $footerSection?->title ?: $appName }}. {{ $footerSection?->subtitle ?: 'All rights reserved.' }}</span>
                </a>
                <div class="flex items-center gap-6 text-sm text-white/30">
                    @auth
                        @if (in_array(auth()->user()->role, ['admin', 'moderator']))
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-white/50 transition-colors">Admin</a>
                        @endif
                        <a href="{{ route('app') }}" class="hover:text-white/50 transition-colors">Launch App</a>
                    @else
                        @if ($showLogin)
                            <a href="{{ route('login') }}" class="hover:text-white/50 transition-colors">Log in</a>
                        @endif
                        @if ($showRegister)
                            <a href="{{ route('register') }}" class="hover:text-white/50 transition-colors">Register</a>
                        @endif
                    @endauth
                    @if ($footerSection?->link_url)
                        <a href="{{ $footerSection->link_url }}" class="hover:text-white/50 transition-colors">{{ $footerSection->link_text ?: 'Link' }}</a>
                    @endif
                </div>
            </div>
            @if ($footerSection?->content)
                <div class="max-w-6xl mx-auto mt-4 text-center text-xs text-white/20">{!! $footerSection->content !!}</div>
            @endif
        </footer>
    </div>
</body>
</html>
