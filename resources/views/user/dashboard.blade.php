@php
    $tab = request('tab', 'overview');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile — {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Figtree', system-ui, sans-serif; background: #03030a; color: #e2e8f0; overflow-x: hidden; }
        .sidebar { width: 240px; background: rgba(15,15,30,0.95); border-right: 1px solid rgba(255,255,255,0.06); display: flex; flex-direction: column; position: fixed; inset: 0; }
        .sidebar .brand { padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.06); font-size: 1.1rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; }
        .sidebar nav { flex: 1; padding: 1rem; overflow-y: auto; }
        .sidebar nav a { display: flex; align-items: center; gap: 0.75rem; padding: 0.6rem 0.8rem; border-radius: 8px; font-size: 0.85rem; color: rgba(255,255,255,0.5); text-decoration: none; transition: all 0.15s; margin-bottom: 0.15rem; }
        .sidebar nav a:hover, .sidebar nav a.active { background: rgba(99,102,241,0.12); color: #e2e8f0; }
        .sidebar nav a.active { color: #a5b4fc; }
        .sidebar .footer { padding: 1rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.06); }
        .main-content { margin-left: 240px; padding: 2rem; max-width: 1000px; }
        .card { background: linear-gradient(135deg, rgba(255,255,255,0.03) 0%, rgba(255,255,255,0.01) 100%); border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 1.5rem; }
        .stat-box { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 1rem; text-align: center; }
        .stat-value { font-size: 1.5rem; font-weight: 700; color: #f1f5f9; }
        .stat-label { font-size: 0.7rem; color: rgba(255,255,255,0.35); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 0.15rem; }
        .msg-bubble { padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 0.5rem; font-size: 0.85rem; }
        .msg-bubble.user { background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.15); margin-right: 2rem; }
        .msg-bubble.admin { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); margin-left: 2rem; }
        .conversation-item { cursor: pointer; transition: all 0.15s; }
        .conversation-item:hover { background: rgba(99,102,241,0.06); }
        .file-row { display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 0; border-bottom: 1px solid rgba(255,255,255,0.04); font-size: 0.8rem; }
        .file-row:last-child { border-bottom: none; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="brand">
        <span class="text-xl">⚒️</span>
        <span>{{ config('app.name', 'Workshop') }}</span>
    </div>
    <nav>
        <a href="{{ route('user.dashboard', ['tab' => 'overview']) }}" class="{{ $tab === 'overview' ? 'active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Overview
        </a>
        <a href="{{ route('user.dashboard', ['tab' => 'plan']) }}" class="{{ $tab === 'plan' ? 'active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Plan & Billing
        </a>
        <a href="{{ route('user.dashboard', ['tab' => 'files']) }}" class="{{ $tab === 'files' ? 'active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
            My Files
        </a>
        <a href="{{ route('user.dashboard', ['tab' => 'sessions']) }}" class="{{ $tab === 'sessions' ? 'active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            Sessions
        </a>
        <a href="{{ route('user.dashboard', ['tab' => 'conversations']) }}" class="{{ $tab === 'conversations' ? 'active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            Questions
        </a>
        <a href="{{ route('app') }}" style="margin-top:1rem;border-top:1px solid rgba(255,255,255,0.06);padding-top:0.75rem;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Launch App
        </a>
        <a href="{{ route('landing') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-150 text-gray-500 hover:text-gray-200 hover:bg-gray-800/50">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Landing Page
        </a>
    </nav>
    <div class="footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background:none;border:none;color:rgba(255,255,255,0.3);font-size:0.8rem;cursor:pointer;padding:0;display:flex;align-items:center;gap:0.5rem;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Log out
            </button>
        </form>
    </div>
</div>

<div class="main-content">

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-emerald-900/40 border border-emerald-700/50 px-4 py-3 text-sm text-emerald-300">{{ session('success') }}</div>
    @endif

    {{-- OVERVIEW --}}
    @if ($tab === 'overview')
        <div class="flex items-center gap-4 mb-8">
            @if ($user->photo)
                <img src="{{ Storage::url($user->photo) }}" alt="" class="w-14 h-14 rounded-full object-cover border-2 border-indigo-600/30">
            @else
                <div class="w-14 h-14 rounded-full bg-indigo-600/30 flex items-center justify-center text-xl font-bold text-indigo-300">{{ substr($user->name, 0, 2) }}</div>
            @endif
            <div>
                <h1 class="text-2xl font-bold tracking-tight">{{ $user->name }}</h1>
                <p class="text-sm text-gray-500">{{ $user->email }} &middot; <span class="capitalize">{{ $user->role }}</span></p>
            </div>
            <div class="ml-auto text-right">
                <span class="text-lg">{{ $plan->icon }}</span>
                <span class="text-sm font-medium {{ $isExpired ? 'text-amber-400' : 'text-emerald-400' }}">{{ $plan->name }}</span>
                @if ($isExpired)
                    <span class="block text-xs text-amber-500">Expired</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-4 gap-4 mb-8">
            <div class="stat-box">
                <div class="stat-value">{{ $sessions->count() }}</div>
                <div class="stat-label">Sessions</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $files->count() }}</div>
                <div class="stat-label">Files</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $plan->max_sessions === -1 ? '∞' : $plan->max_sessions }}</div>
                <div class="stat-label">Max Sessions</div>
            </div>
            <div class="stat-box">
                <div class="stat-value">{{ $plan->max_objects_per_scene === -1 ? '∞' : $plan->max_objects_per_scene }}</div>
                <div class="stat-label">Max Objects/Scene</div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div class="card">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Account</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Name</span><span>{{ $user->name }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Email</span><span>{{ $user->email }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Role</span><span class="capitalize">{{ $user->role }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Joined</span><span>{{ $user->created_at->format('M j, Y') }}</span></div>
                </div>
            </div>
            <div class="card">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Plan Details</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Plan</span><span>{{ $plan->icon }} {{ $plan->name }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Sessions Limit</span><span>{{ $plan->max_sessions === -1 ? 'Unlimited' : $plan->max_sessions }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Objects Limit</span><span>{{ $plan->max_objects_per_scene === -1 ? 'Unlimited' : $plan->max_objects_per_scene }}</span></div>
                    @if ($planHistory->count() > 0)
                        <div class="flex justify-between"><span class="text-gray-500">Active Since</span><span>{{ $planHistory->first()->created_at->format('M j, Y') }}</span></div>
                    @endif
                    @if ($isExpired)
                        <div class="flex justify-between"><span class="text-amber-400">Status</span><span class="text-amber-400">Expired</span></div>
                    @endif
                </div>
            </div>
        </div>

    {{-- PLAN & BILLING --}}
    @elseif ($tab === 'plan')
        <h1 class="text-2xl font-bold tracking-tight mb-6">Plan & Billing</h1>

        <div class="card mb-8">
            <div class="flex items-center gap-4">
                <span class="text-4xl">{{ $plan->icon }}</span>
                <div>
                    <h2 class="text-xl font-bold">{{ $plan->name }} Plan</h2>
                    <p class="text-sm text-gray-500">
                        {{ $plan->max_sessions === -1 ? 'Unlimited' : $plan->max_sessions }} sessions &middot;
                        {{ $plan->max_objects_per_scene === -1 ? 'Unlimited' : $plan->max_objects_per_scene }} objects per scene
                        @if ($plan->duration_days) &middot; {{ $plan->duration_days }} days @endif
                        @if ($isExpired) &middot; <span class="text-amber-400">Expired</span> @endif
                    </p>
                </div>
            </div>
        </div>

        @if ($plan->slug === 'free' || $isExpired)
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Available Plans</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                @foreach ($plans as $p)
                    <div class="card flex items-center gap-4">
                        <span class="text-3xl">{{ $p->icon }}</span>
                        <div class="flex-1">
                            <h4 class="font-semibold">{{ $p->name }}</h4>
                            <p class="text-xs text-gray-500">
                                {{ $p->max_sessions === -1 ? 'Unlimited' : $p->max_sessions }} sessions &middot;
                                {{ $p->max_objects_per_scene === -1 ? 'Unlimited' : $p->max_objects_per_scene }} objects
                                @if ($p->duration_days) &middot; {{ $p->duration_days }} days @endif
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold text-indigo-400">${{ number_format($p->price, 2) }}</div>
                            <button onclick="openUpgradeModal({{ $p->id }}, '{{ $p->name }}', '{{ $p->icon }}', {{ $p->price }})"
                                class="mt-1 px-3 py-1 rounded bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 text-xs font-medium transition-colors border border-indigo-700/30">Request</button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($planHistory->count() > 0)
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Plan History</h3>
            <div class="card">
                @foreach ($planHistory as $up)
                    <div class="flex items-center justify-between py-2 border-b border-gray-800/50 last:border-0 text-sm">
                        <div>
                            <span class="text-lg mr-2">{{ $up->plan->icon }}</span>
                            <span>{{ $up->plan->name }}</span>
                        </div>
                        <div class="text-gray-500 text-xs">
                            {{ $up->starts_at->format('M j, Y') }}
                            @if ($up->expires_at)
                                → {{ $up->expires_at->format('M j, Y') }}
                                @if ($up->expires_at->isPast())
                                    <span class="text-amber-500 ml-1">(expired)</span>
                                @endif
                            @else
                                <span class="text-emerald-500 ml-1">(active)</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if ($upgradeRequests->count() > 0)
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mt-8 mb-4">Upgrade Requests</h3>
            <div class="card">
                @foreach ($upgradeRequests as $req)
                    <div class="flex items-center justify-between py-2 border-b border-gray-800/50 last:border-0 text-sm">
                        <div>
                            <span class="text-lg mr-2">{{ $req->plan->icon }}</span>
                            <span>{{ $req->plan->name }}</span>
                        </div>
                        <div>
                            @if ($req->status === 'pending')
                                <span class="text-amber-400 text-xs">Pending</span>
                            @elseif ($req->status === 'approved')
                                <span class="text-emerald-400 text-xs">Approved</span>
                            @else
                                <span class="text-red-400 text-xs">Rejected</span>
                            @endif
                            <span class="text-gray-600 text-xs ml-2">{{ $req->created_at->format('M j, Y') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    {{-- FILES --}}
    @elseif ($tab === 'files')
        <h1 class="text-2xl font-bold tracking-tight mb-6">My Files</h1>
        <div class="card">
            @forelse ($files as $f)
                <div class="file-row">
                    <span class="text-xs px-1.5 py-0.5 rounded bg-gray-800 text-gray-500 uppercase">{{ pathinfo($f->original_name, PATHINFO_EXTENSION) }}</span>
                    <span class="flex-1">{{ $f->original_name }}</span>
                    <span class="text-gray-600 text-xs">{{ number_format($f->file_size / 1024, 1) }} KB</span>
                    <span class="text-gray-600 text-xs">{{ $f->created_at->format('M j, Y') }}</span>
                </div>
            @empty
                <p class="text-gray-500 text-sm text-center py-8">No files uploaded yet.</p>
            @endforelse
        </div>

    {{-- SESSIONS --}}
    @elseif ($tab === 'sessions')
        <h1 class="text-2xl font-bold tracking-tight mb-6">Sessions</h1>
        <div class="card">
            @forelse ($sessions as $s)
                <a href="{{ route('app', ['session' => $s->session_id]) }}" class="flex items-center justify-between py-2 border-b border-gray-800/50 last:border-0 text-sm hover:bg-gray-800/30 transition-colors no-underline rounded px-2 -mx-2">
                    <div>
                        <span class="text-gray-300 font-mono text-xs">{{ $s->session_id ? (strlen($s->session_id) > 12 ? substr($s->session_id, 0, 12) . '…' : $s->session_id) : '—' }}</span>
                    </div>
                    <span class="text-gray-600 text-xs">{{ \Carbon\Carbon::parse($s->last_upload)->format('M j, Y g:i A') }}</span>
                </a>
            @empty
                <p class="text-gray-500 text-sm text-center py-8">No sessions created yet.</p>
            @endforelse
        </div>

    {{-- CONVERSATIONS --}}
    @elseif ($tab === 'conversations')
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold tracking-tight">Questions</h1>
            <button onclick="document.getElementById('newConvModal').style.display='flex'"
                class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">New Question</button>
        </div>

        @forelse ($conversations as $c)
            <div class="card mb-3 conversation-item {{ $c->latestMessage?->sender_type === 'admin' ? 'ring-1 ring-indigo-500/20' : '' }}" onclick="toggleConv({{ $c->id }})">
                <div class="flex items-center justify-between">
                    <div>
                        @if ($c->latestMessage?->sender_type === 'admin')
                            <span class="w-2 h-2 rounded-full bg-indigo-400 inline-block mr-1.5" title="Admin replied"></span>
                        @endif
                        <span class="font-medium">{{ $c->subject }}</span>
                        @if ($c->status === 'open')
                            <span class="ml-2 inline-block w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        @else
                            <span class="ml-2 inline-block w-1.5 h-1.5 rounded-full bg-gray-600"></span>
                        @endif
                    </div>
                    <span class="text-xs text-gray-600">{{ $c->created_at->format('M j, Y') }}</span>
                </div>
                <div class="text-xs text-gray-500 mt-1">{{ $c->latestMessage?->message ? Str::limit($c->latestMessage->message, 80) : '' }}</div>

                <div id="conv-{{ $c->id }}" class="mt-3" style="display:none;">
                    @foreach ($c->messages()->with('sender')->oldest()->get() as $msg)
                        <div class="msg-bubble {{ $msg->sender_type }}">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-medium {{ $msg->sender_type === 'admin' ? 'text-indigo-300' : 'text-white' }}">{{ $msg->sender->name }}</span>
                                <span class="text-xs text-gray-600">{{ $msg->created_at->format('M j, Y g:i A') }}</span>
                            </div>
                            <p class="text-sm whitespace-pre-wrap">{{ $msg->message }}</p>
                            @if ($msg->image_path)
                                <img src="/storage/{{ $msg->image_path }}" alt="" class="mt-2 max-w-xs rounded border border-gray-700">
                            @endif
                        </div>
                    @endforeach
                    @if ($c->status === 'open')
                        <form method="POST" action="{{ route('user.conversations.message', $c) }}" enctype="multipart/form-data" class="mt-3">
                            @csrf
                            <div class="flex gap-2 mb-2">
                                <input type="text" name="message" required placeholder="Type your reply…" class="flex-1 rounded-lg bg-gray-800 border border-gray-700 px-3 py-2 text-sm outline-none focus:border-indigo-500">
                                <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 text-sm font-medium transition-colors">Send</button>
                            </div>
                            <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp" class="text-xs file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-gray-700 file:text-gray-300 file:text-xs file:font-medium hover:file:bg-gray-600">
                        </form>
                    @else
                        <p class="text-xs text-gray-600 mt-2">This conversation is closed.</p>
                    @endif
                </div>
            </div>
        @empty
            <div class="card text-center py-8">
                <p class="text-gray-500 text-sm">No questions yet. Ask the admin anything!</p>
            </div>
        @endforelse

        {{-- New Conversation Modal --}}
        <div id="newConvModal" style="display:none;position:fixed;inset:0;z-index:60;background:rgba(0,0,0,0.7);backdrop-filter:blur(6px);align-items:center;justify-content:center;">
            <div style="background:rgba(18,18,32,0.96);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:2rem;max-width:440px;width:90%;">
                <h3 class="text-lg font-semibold mb-4">Ask a Question</h3>
                <form method="POST" action="{{ route('user.conversations.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <input type="text" name="subject" required placeholder="Subject" class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm outline-none focus:border-indigo-500">
                    </div>
                    <div class="mb-4">
                        <textarea name="message" rows="5" required placeholder="Describe your question or request…" class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm outline-none focus:border-indigo-500"></textarea>
                    </div>
                    <div class="mb-4">
                        <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp" class="text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-gray-700 file:text-gray-300 file:text-xs file:font-medium hover:file:bg-gray-600">
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="document.getElementById('newConvModal').style.display='none'" class="px-4 py-2 rounded-lg bg-gray-800 hover:bg-gray-700 text-sm transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">Send</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>

<script>
function toggleConv(id) {
    const el = document.getElementById('conv-' + id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
function openUpgradeModal(planId, name, icon, price) {
    const plans = @json($plans);
    const p = plans.find(x => x.id === planId);
    if (!p) return;
    const msg = 'I want to upgrade to ' + icon + ' ' + name + ' ($' + price.toFixed(2) + '). Please activate this plan.';
    document.getElementById('reqSubject').value = 'Upgrade to ' + name;
    document.getElementById('reqMessage').value = msg;
    document.getElementById('reqPlanId').value = planId;
    document.getElementById('upgradeRequestModal').style.display = 'flex';
}
function closeUpgradeModal() {
    document.getElementById('upgradeRequestModal').style.display = 'none';
}
</script>

{{-- Upgrade request modal (from profile) --}}
<div id="upgradeRequestModal" style="display:none;position:fixed;inset:0;z-index:60;background:rgba(0,0,0,0.7);backdrop-filter:blur(6px);align-items:center;justify-content:center;">
    <div style="background:rgba(18,18,32,0.96);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:2rem;max-width:440px;width:90%;">
        <h3 class="text-lg font-semibold mb-4">Request Upgrade</h3>
        <form method="POST" action="{{ route('user.conversations.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="reqPlanId" name="plan_id">
            <div class="mb-4">
                <input type="text" id="reqSubject" name="subject" required placeholder="Subject" class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm outline-none focus:border-indigo-500">
            </div>
            <div class="mb-4">
                <textarea id="reqMessage" name="message" rows="4" required placeholder="Message…" class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm outline-none focus:border-indigo-500"></textarea>
            </div>
            <div class="mb-4">
                <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp" class="text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-gray-700 file:text-gray-300 file:text-xs file:font-medium hover:file:bg-gray-600">
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeUpgradeModal()" class="px-4 py-2 rounded-lg bg-gray-800 hover:bg-gray-700 text-sm transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">Send Request</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
