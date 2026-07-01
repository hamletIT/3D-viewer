<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Figtree', system-ui, sans-serif; background: #03030a; color: #e2e8f0; }
        .bg-auth { background: radial-gradient(ellipse at 50% 0%, #0f0f2a 0%, #03030a 60%); min-height: 100vh; }
        .auth-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.04) 0%, rgba(255,255,255,0.01) 100%);
            border: 1px solid rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
        }
        .auth-input {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            color: #e2e8f0;
            transition: all 0.2s;
        }
        .auth-input:focus {
            border-color: rgba(99,102,241,0.5);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
            outline: none;
            background: rgba(255,255,255,0.06);
        }
        .auth-input::placeholder { color: rgba(255,255,255,0.2); }
        .auth-label { color: rgba(255,255,255,0.6); font-size: 0.875rem; font-weight: 500; }
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            transition: all 0.2s;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 30px -8px rgba(99,102,241,0.5); }
        .auth-link { color: rgba(255,255,255,0.4); transition: color 0.2s; }
        .auth-link:hover { color: rgba(255,255,255,0.7); }
        .auth-error { color: #f87171; font-size: 0.8rem; margin-top: 0.3rem; }
        .sparkle {
            position: fixed;
            width: 3px; height: 3px;
            background: #818cf8;
            border-radius: 50%;
            animation: sparkle 3s infinite;
            pointer-events: none;
        }
        @keyframes sparkle {
            0%, 100% { opacity: 0; transform: scale(0); }
            50% { opacity: 1; transform: scale(1); }
        }
        input[type="checkbox"] {
            accent-color: #6366f1;
        }
    </style>
</head>
<body>
    <div class="sparkle" style="top:15%;left:10%;animation-delay:0s;"></div>
    <div class="sparkle" style="top:25%;right:15%;animation-delay:1s;"></div>
    <div class="sparkle" style="top:70%;left:8%;animation-delay:2s;"></div>
    <div class="sparkle" style="top:60%;right:12%;animation-delay:0.5s;"></div>
    <div class="sparkle" style="top:40%;left:5%;animation-delay:1.5s;"></div>
    <div class="sparkle" style="top:80%;right:20%;animation-delay:2.5s;"></div>

    <div class="bg-auth flex flex-col items-center justify-center px-4 py-12">
        <a href="{{ url('/') }}" class="flex items-center gap-2 text-lg font-semibold tracking-wide mb-8">
            <span class="text-3xl">⚒️</span>
            <span class="text-white/80">3D Workshop Explorer</span>
        </a>

        <div class="w-full max-w-md auth-card rounded-2xl p-8">
            @yield('content')
        </div>

        <p class="mt-8 text-xs text-white/20">&copy; {{ date('Y') }} {{ config('app.name', 'Workshop') }}</p>
    </div>
</body>
</html>
