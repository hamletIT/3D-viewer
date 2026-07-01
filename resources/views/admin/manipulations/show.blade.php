@extends('admin.layouts.app')

@section('title', $manipulation->model_name)

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.manipulations.index') }}" class="text-sm text-gray-400 hover:text-gray-200 transition-colors">&larr; Back to Manipulations</a>
        <h1 class="text-2xl font-bold tracking-tight mt-2">{{ $manipulation->model_name }}</h1>
    </div>

    <div class="max-w-2xl rounded-xl bg-gray-900/60 border border-gray-800 p-6">
        <dl class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-xs text-gray-500 uppercase tracking-wider mb-1">Model Name</dt>
                <dd class="text-sm">{{ $manipulation->model_name }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 uppercase tracking-wider mb-1">Color</dt>
                <dd class="text-sm flex items-center gap-2">
                    @if ($manipulation->random_color)
                        <span class="inline-flex items-center gap-1.5 text-indigo-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                            Random
                        </span>
                    @else
                        <span class="inline-block w-5 h-5 rounded" style="background:{{ $manipulation->color }}"></span>
                        <span class="font-mono">{{ $manipulation->color }}</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 uppercase tracking-wider mb-1">Scale</dt>
                <dd class="text-sm">{{ $manipulation->scale }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 uppercase tracking-wider mb-1">Roughness</dt>
                <dd class="text-sm">{{ $manipulation->roughness }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 uppercase tracking-wider mb-1">Metalness</dt>
                <dd class="text-sm">{{ $manipulation->metalness }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 uppercase tracking-wider mb-1">Style</dt>
                <dd class="text-sm">
                    <span class="px-2 py-0.5 rounded text-xs bg-gray-800 text-gray-300">{{ $manipulation->style }}</span>
                </dd>
            </div>
            <div class="col-span-2">
                <dt class="text-xs text-gray-500 uppercase tracking-wider mb-1">Position</dt>
                <dd class="text-sm font-mono">X: {{ $manipulation->position_x }}, Y: {{ $manipulation->position_y }}, Z: {{ $manipulation->position_z }}</dd>
            </div>
            <div class="col-span-2">
                <dt class="text-xs text-gray-500 uppercase tracking-wider mb-1">Rotation</dt>
                <dd class="text-sm font-mono">X: {{ $manipulation->rotation_x }}°, Y: {{ $manipulation->rotation_y }}°, Z: {{ $manipulation->rotation_z }}°</dd>
            </div>
            @if ($manipulation->session_id)
                <div>
                    <dt class="text-xs text-gray-500 uppercase tracking-wider mb-1">Session</dt>
                    <dd class="text-sm font-mono text-xs">{{ $manipulation->session_id }}</dd>
                </div>
            @endif
            @if ($manipulation->user_id)
                <div>
                    <dt class="text-xs text-gray-500 uppercase tracking-wider mb-1">User ID</dt>
                    <dd class="text-sm">{{ $manipulation->user_id }}</dd>
                </div>
            @endif
            <div>
                <dt class="text-xs text-gray-500 uppercase tracking-wider mb-1">Created</dt>
                <dd class="text-sm">{{ $manipulation->created_at->format('M j, Y g:i A') }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 uppercase tracking-wider mb-1">Updated</dt>
                <dd class="text-sm">{{ $manipulation->updated_at->format('M j, Y g:i A') }}</dd>
            </div>
        </dl>

        @if (Auth::user()->role !== 'moderator')
            <div class="mt-6 flex gap-3">
                <a href="{{ route('admin.manipulations.edit', $manipulation) }}" class="px-4 py-2 rounded-lg bg-gray-800 hover:bg-gray-700 text-sm font-medium transition-colors">Edit</a>
                <form method="POST" action="{{ route('admin.manipulations.destroy', $manipulation) }}" onsubmit="return confirm('Delete manipulation for {{ $manipulation->model_name }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-900/50 hover:bg-red-800/50 text-red-300 text-sm font-medium transition-colors">Delete</button>
                </form>
            </div>
        @endif
    </div>
@endsection
