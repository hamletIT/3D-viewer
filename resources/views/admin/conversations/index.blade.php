@extends('admin.layouts.app')

@section('title', 'Conversations')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Conversations</h1>
    </div>

    <div class="rounded-xl bg-gray-900/60 border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500 border-b border-gray-800">
                        <th class="text-left py-4 px-4 font-medium">User</th>
                        <th class="text-left py-4 px-4 font-medium">Subject</th>
                        <th class="text-left py-4 px-4 font-medium">Last Message</th>
                        <th class="text-center py-4 px-4 font-medium">Status</th>
                        <th class="text-left py-4 px-4 font-medium">Started</th>
                        <th class="text-right py-4 px-4 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($conversations as $c)
                        <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition-colors {{ $c->latestMessage?->sender_type === 'user' ? 'bg-amber-900/10' : '' }}">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    @if ($c->latestMessage?->sender_type === 'user')
                                        <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0" title="New message from user"></span>
                                    @endif
                                    @if ($c->user->photo)
                                        <img src="{{ Storage::url($c->user->photo) }}" alt="" class="w-7 h-7 rounded-full object-cover" loading="lazy">
                                    @else
                                        <div class="w-7 h-7 rounded-full bg-indigo-600/30 flex items-center justify-center text-xs font-medium">{{ substr($c->user->name, 0, 2) }}</div>
                                    @endif
                                    <span>{{ $c->user->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 font-medium">{{ $c->subject }}</td>
                            <td class="py-3 px-4 text-gray-400 text-xs max-w-[200px] truncate">{{ $c->latestMessage?->message ?? '—' }}</td>
                            <td class="py-3 px-4 text-center">
                                @if ($c->status === 'open')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-900/40 text-emerald-400 border border-emerald-700/50">Open</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-gray-800 text-gray-500 border border-gray-700">Closed</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-gray-500 text-xs">{{ $c->created_at->format('M j, Y') }}</td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.conversations.show', $c) }}" class="px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-xs font-medium transition-colors">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-500">No conversations yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $conversations->links() }}
    </div>
@endsection
