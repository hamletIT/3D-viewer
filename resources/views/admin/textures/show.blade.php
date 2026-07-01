@extends('admin.layouts.app')

@section('title', $texture->name)

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.textures.index') }}" class="text-sm text-gray-400 hover:text-gray-200 transition-colors">&larr; Back to Textures</a>
        <h1 class="text-2xl font-bold tracking-tight mt-2">{{ $texture->name }}</h1>
    </div>

    <div class="max-w-2xl rounded-xl bg-gray-900/60 border border-gray-800 p-6">
        <div class="mb-6">
            <img src="/storage/{{ $texture->file_path }}" alt="{{ $texture->name }}" class="w-64 h-64 rounded-lg object-cover border border-gray-700" loading="lazy">
        </div>

        <dl class="grid grid-cols-2 gap-4">
            <div>
                <dt class="text-xs text-gray-500 uppercase tracking-wider mb-1">Name</dt>
                <dd class="text-sm">{{ $texture->name }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 uppercase tracking-wider mb-1">File</dt>
                <dd class="text-sm font-mono text-xs">{{ $texture->original_name }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 uppercase tracking-wider mb-1">Path</dt>
                <dd class="text-sm font-mono text-xs">{{ $texture->file_path }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 uppercase tracking-wider mb-1">URL</dt>
                <dd class="text-sm"><a href="/storage/{{ $texture->file_path }}" target="_blank" class="text-indigo-400 hover:text-indigo-300 text-xs">/storage/{{ $texture->file_path }}</a></dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 uppercase tracking-wider mb-1">Created</dt>
                <dd class="text-sm">{{ $texture->created_at->format('M j, Y g:i A') }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-500 uppercase tracking-wider mb-1">Updated</dt>
                <dd class="text-sm">{{ $texture->updated_at->format('M j, Y g:i A') }}</dd>
            </div>
        </dl>

        @if (Auth::user()->role !== 'moderator')
            <div class="mt-6 flex gap-3">
                <a href="{{ route('admin.textures.edit', $texture) }}" class="px-4 py-2 rounded-lg bg-gray-800 hover:bg-gray-700 text-sm font-medium transition-colors">Edit</a>
                <form method="POST" action="{{ route('admin.textures.destroy', $texture) }}" onsubmit="return confirm('Delete texture {{ $texture->name }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-900/50 hover:bg-red-800/50 text-red-300 text-sm font-medium transition-colors">Delete</button>
                </form>
            </div>
        @endif
    </div>
@endsection
