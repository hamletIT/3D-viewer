@extends('admin.layouts.app')

@section('title', 'Social Posts')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold tracking-tight">User Social Posts</h1>
    </div>

    <div class="rounded-xl bg-gray-900/60 border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500 border-b border-gray-800">
                        <th class="text-left py-4 px-4 font-medium">User</th>
                        <th class="text-left py-4 px-4 font-medium">Platform</th>
                        <th class="text-left py-4 px-4 font-medium">Post URL</th>
                        <th class="text-center py-4 px-4 font-medium">Discount</th>
                        <th class="text-center py-4 px-4 font-medium">Status</th>
                        <th class="text-center py-4 px-4 font-medium">Submitted</th>
                        <th class="text-center py-4 px-4 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition-colors">
                            <td class="py-3 px-4">
                                <div class="text-gray-300">{{ $post->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $post->user->email }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-xl align-middle">{{ $post->socialDiscount->icon }}</span>
                                <span class="ml-1 text-gray-300">{{ $post->socialDiscount->label }}</span>
                            </td>
                            <td class="py-3 px-4 max-w-xs truncate">
                                @if ($post->post_url)
                                    <a href="{{ $post->post_url }}" target="_blank" class="text-indigo-400 hover:text-indigo-300 underline">{{ $post->post_url }}</a>
                                @else
                                    <span class="text-gray-500 italic">No URL</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-block px-2 py-0.5 rounded-full bg-emerald-900/40 text-emerald-300 text-xs font-medium">{{ $post->socialDiscount->discount_percent }}%</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if ($post->verified)
                                    <span class="inline-flex items-center gap-1 text-emerald-400 text-xs font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-amber-400 text-xs font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</td>
                            <td class="py-3 px-4 text-center">
                                @unless ($post->verified)
                                    <div class="flex items-center justify-center gap-2">
                                        <form method="POST" action="{{ route('admin.social-posts.verify', $post) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-900/50 hover:bg-emerald-800/50 text-emerald-300 text-xs font-medium transition-colors">Verify</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.social-posts.destroy', $post) }}" onsubmit="return confirm('Reject this post?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-900/50 hover:bg-red-800/50 text-red-300 text-xs font-medium transition-colors">Reject</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-500">{{ $post->verified_at ? $post->verified_at->diffForHumans() : '' }}</span>
                                @endunless
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-500">No social posts submitted yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $posts->links() }}
    </div>
@endsection