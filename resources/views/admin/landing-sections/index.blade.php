@extends('admin.layouts.app')

@section('title', 'Landing Sections')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Landing Sections</h1>
        @if (Auth::user()->role !== 'moderator')
            <a href="{{ route('admin.landing-sections.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Section
            </a>
        @endif
    </div>

    <div class="rounded-xl bg-gray-900/60 border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500 border-b border-gray-800">
                        <th class="text-left py-4 px-4 font-medium">Order</th>
                        <th class="text-left py-4 px-4 font-medium">Slug</th>
                        <th class="text-left py-4 px-4 font-medium">Icon</th>
                        <th class="text-left py-4 px-4 font-medium">Title</th>
                        <th class="text-left py-4 px-4 font-medium">Type</th>
                        <th class="text-center py-4 px-4 font-medium">Active</th>
                        <th class="text-right py-4 px-4 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sections as $s)
                        <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition-colors">
                            <td class="py-3 px-4 text-gray-500">{{ $s->sort_order }}</td>
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.landing-sections.show', $s) }}" class="text-indigo-400 hover:text-indigo-300 font-mono text-xs">{{ $s->slug }}</a>
                            </td>
                            <td class="py-3 px-4 text-xl">{{ $s->icon }}</td>
                            <td class="py-3 px-4 text-gray-300 max-w-[200px] truncate">{{ $s->title ?? '—' }}</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-800 text-gray-400 border border-gray-700">{{ $s->type }}</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if ($s->active)
                                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                                @else
                                    <span class="inline-block w-2 h-2 rounded-full bg-gray-600"></span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.landing-sections.edit', $s) }}" class="px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-xs font-medium transition-colors">Edit</a>
                                    @if (Auth::user()->role !== 'moderator')
                                        <form method="POST" action="{{ route('admin.landing-sections.destroy', $s) }}" onsubmit="return confirm('Delete section {{ $s->slug }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-900/50 hover:bg-red-800/50 text-red-300 text-xs font-medium transition-colors">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-500">No sections found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $sections->links() }}
    </div>
@endsection
