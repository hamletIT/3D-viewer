@extends('admin.layouts.app')

@section('title', 'Manipulations')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Manipulations</h1>
        @if (Auth::user()->role !== 'moderator')
            <a href="{{ route('admin.manipulations.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Manipulation
            </a>
        @endif
    </div>

    <div class="rounded-xl bg-gray-900/60 border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500 border-b border-gray-800">
                        <th class="text-left py-4 px-4 font-medium">Model</th>
                        <th class="text-left py-4 px-4 font-medium">Color</th>
                        <th class="text-left py-4 px-4 font-medium">Random</th>
                        <th class="text-left py-4 px-4 font-medium">Scale</th>
                        <th class="text-left py-4 px-4 font-medium">Roughness</th>
                        <th class="text-left py-4 px-4 font-medium">Metalness</th>
                        <th class="text-left py-4 px-4 font-medium">Style</th>
                        <th class="text-left py-4 px-4 font-medium">User / Session</th>
                        @if (Auth::user()->role !== 'moderator')
                            <th class="text-right py-4 px-4 font-medium">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($manipulations as $m)
                        <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition-colors">
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.manipulations.show', $m) }}" class="text-indigo-400 hover:text-indigo-300">{{ $m->model_name }}</a>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-block w-5 h-5 rounded align-middle mr-1.5" style="background:{{ $m->color }}"></span>
                                <span class="font-mono text-xs">{{ $m->color }}</span>
                            </td>
                            <td class="py-3 px-4">
                                @if ($m->random_color)
                                    <span class="text-indigo-400 text-xs font-medium">Yes</span>
                                @else
                                    <span class="text-gray-600 text-xs">No</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">{{ $m->scale }}</td>
                            <td class="py-3 px-4">{{ $m->roughness }}</td>
                            <td class="py-3 px-4">{{ $m->metalness }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-xs bg-gray-800 text-gray-300">{{ $m->style }}</span>
                            </td>
                            <td class="py-3 px-4 text-gray-500 text-xs">
                                @if ($m->user_id) user:{{ $m->user_id }} @endif
                                @if ($m->session_id) / {{ substr($m->session_id, 0, 8) }}... @endif
                            </td>
                            @if (Auth::user()->role !== 'moderator')
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.manipulations.edit', $m) }}" class="px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-xs font-medium transition-colors">Edit</a>
                                        <form method="POST" action="{{ route('admin.manipulations.destroy', $m) }}" onsubmit="return confirm('Delete manipulation for {{ $m->model_name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-900/50 hover:bg-red-800/50 text-red-300 text-xs font-medium transition-colors">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-gray-500">No manipulations found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $manipulations->links() }}
    </div>
@endsection
