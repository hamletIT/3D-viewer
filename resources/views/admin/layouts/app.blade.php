<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-950 text-gray-100">
    <div class="flex h-screen overflow-hidden">

        <aside class="w-64 bg-gray-900/80 border-r border-gray-800 flex flex-col shrink-0">
            <div class="h-16 flex items-center gap-3 px-6 border-b border-gray-800">
                <span class="text-2xl">⚒️</span>
                <span class="font-bold text-lg tracking-wide">Workshop Admin</span>
            </div>
            <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600/20 text-indigo-300' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-150 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600/20 text-indigo-300' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                    Users
                </a>
                <a href="{{ route('admin.settings.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-150 {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600/20 text-indigo-300' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Settings
                </a>
                    <a href="{{ route('admin.file-types.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-150 {{ request()->routeIs('admin.file-types.*') ? 'bg-indigo-600/20 text-indigo-300' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                        Files
                    </a>
                    <a href="{{ route('admin.manipulations.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-150 {{ request()->routeIs('admin.manipulations.*') ? 'bg-indigo-600/20 text-indigo-300' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                        Manipulations
                    </a>
                    <a href="{{ route('admin.textures.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-150 {{ request()->routeIs('admin.textures.*') ? 'bg-indigo-600/20 text-indigo-300' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Textures
                    </a>
                    <a href="{{ route('admin.keybindings.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-150 {{ request()->routeIs('admin.keybindings.*') ? 'bg-indigo-600/20 text-indigo-300' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        Shortcuts
                    </a>
                    <a href="{{ route('admin.plans.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-150 {{ request()->routeIs('admin.plans.*') ? 'bg-indigo-600/20 text-indigo-300' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Plans
                    </a>
                    <a href="{{ route('admin.social-discounts.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-150 {{ request()->routeIs('admin.social-discounts.*') ? 'bg-indigo-600/20 text-indigo-300' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        Social Discounts
                    </a>
                    <a href="{{ route('admin.social-posts.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-150 {{ request()->routeIs('admin.social-posts.*') ? 'bg-indigo-600/20 text-indigo-300' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        User Posts
                    </a>
                    <a href="{{ route('admin.instructions.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-150 {{ request()->routeIs('admin.instructions.*') ? 'bg-indigo-600/20 text-indigo-300' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Instructions
                    </a>
                    <a href="{{ route('admin.upgrade-requests.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-150 {{ request()->routeIs('admin.upgrade-requests.*') ? 'bg-indigo-600/20 text-indigo-300' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Upgrade Requests
                    </a>
                    <a href="{{ route('admin.conversations.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-150 {{ request()->routeIs('admin.conversations.*') ? 'bg-indigo-600/20 text-indigo-300' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Conversations
                    </a>
                    <a href="{{ route('admin.landing-sections.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-150 {{ request()->routeIs('admin.landing-sections.*') ? 'bg-indigo-600/20 text-indigo-300' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm0 8a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2zm0 8a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2z"/></svg>
                        Landing Page
                    </a>
                    <div class="border-t border-gray-800 my-2"></div>
                    <a href="{{ route('landing') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-150 text-gray-500 hover:text-gray-200 hover:bg-gray-800/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Landing Page
                    </a>
                </nav>
            <div class="p-4 border-t border-gray-800">
                <div class="flex items-center gap-3 px-3 py-2">
                    <div class="w-8 h-8 rounded-full bg-indigo-600/30 flex items-center justify-center text-sm font-medium">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        <span class="inline-block mt-0.5 text-[10px] uppercase tracking-wider font-semibold
                            {{ Auth::user()->role === 'admin' ? 'text-indigo-400' : (Auth::user()->role === 'moderator' ? 'text-amber-400' : 'text-gray-500') }}">
                            {{ Auth::user()->role }}
                        </span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-gray-300 transition-colors" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto">
            <div class="p-6 lg:p-8">
                @if (session('success'))
                    <div class="mb-6 rounded-lg bg-emerald-900/40 border border-emerald-700/50 px-4 py-3 text-sm text-emerald-300 flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-6 rounded-lg bg-red-900/40 border border-red-700/50 px-4 py-3 text-sm text-red-300 flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-6 rounded-lg bg-red-900/40 border border-red-700/50 px-4 py-3 text-sm text-red-300">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
