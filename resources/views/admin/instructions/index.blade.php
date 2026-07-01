@extends('admin.layouts.app')

@section('title', 'Instructions')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Instructions</h1>
        @if (Auth::user()->role !== 'moderator')
            <a href="{{ route('admin.instructions.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Instruction
            </a>
        @endif
    </div>

    <div class="rounded-xl bg-gray-900/60 border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500 border-b border-gray-800">
                        <th class="text-left py-4 px-4 font-medium">Image</th>
                        <th class="text-left py-4 px-4 font-medium">Title</th>
                        <th class="text-center py-4 px-4 font-medium">Order</th>
                        <th class="text-center py-4 px-4 font-medium">Active</th>
                        <th class="text-left py-4 px-4 font-medium">Updated</th>
                        @if (Auth::user()->role !== 'moderator')
                            <th class="text-right py-4 px-4 font-medium">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($instructions as $i)
                        <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition-colors">
                            <td class="py-3 px-4">
                                @if ($i->image_path)
                                    <img src="/storage/{{ $i->image_path }}" alt="{{ $i->title }}" class="w-16 h-12 rounded object-cover" loading="lazy">
                                @else
                                    <span class="text-gray-600 text-xs">—</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.instructions.show', $i) }}" class="text-indigo-400 hover:text-indigo-300">{{ $i->title }}</a>
                            </td>
                            <td class="py-3 px-4 text-center text-gray-500">{{ $i->sort_order }}</td>
                            <td class="py-3 px-4 text-center">
                                @if ($i->active)
                                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                                @else
                                    <span class="inline-block w-2 h-2 rounded-full bg-gray-600"></span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-gray-500 text-xs">{{ $i->updated_at->format('M j, Y') }}</td>
                            @if (Auth::user()->role !== 'moderator')
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.instructions.edit', $i) }}" class="px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-xs font-medium transition-colors">Edit</a>
                                        <form method="POST" action="{{ route('admin.instructions.destroy', $i) }}" onsubmit="return confirm('Delete instruction {{ $i->title }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-900/50 hover:bg-red-800/50 text-red-300 text-xs font-medium transition-colors">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-500">No instructions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $instructions->links() }}
    </div>
@endsection
