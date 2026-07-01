@extends('admin.layouts.app')

@section('title', 'File Details')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.file-types.index') }}" class="text-sm text-gray-400 hover:text-gray-200 transition-colors">&larr; Back to Files</a>
        <h1 class="text-2xl font-bold tracking-tight mt-2">File Details</h1>
    </div>

    <div class="max-w-2xl rounded-xl bg-gray-900/60 border border-gray-800 p-6">
        <dl class="space-y-5">
            <div>
                <dt class="text-sm text-gray-500">Original Name</dt>
                <dd class="text-sm font-medium mt-1">{{ $fileType->original_name }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Model Name</dt>
                <dd class="text-sm font-medium mt-1">{{ $fileType->model_name ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Uploaded By</dt>
                <dd class="text-sm font-medium mt-1">{{ $fileType->user->name }} <span class="text-gray-500">({{ $fileType->user->email }})</span></dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">MIME Type</dt>
                <dd class="text-sm font-medium mt-1">{{ $fileType->mime_type ?? 'N/A' }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">File Size</dt>
                <dd class="text-sm font-medium mt-1">
                    @if ($fileType->file_size)
                        {{ round($fileType->file_size / 1024, 1) }} KB ({{ $fileType->file_size }} bytes)
                    @else
                        —
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Storage Path</dt>
                <dd class="text-sm font-mono mt-1 text-indigo-400 break-all">{{ $fileType->file_path }}</dd>
            </div>
            <div>
                <dt class="text-sm text-gray-500">Uploaded At</dt>
                <dd class="text-sm font-medium mt-1">{{ $fileType->created_at->format('F j, Y g:i:s A') }}</dd>
            </div>
        </dl>

        <div class="mt-8 pt-6 border-t border-gray-800">
            <form method="POST" action="{{ route('admin.file-types.destroy', $fileType) }}" onsubmit="return confirm('Delete this file permanently?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-lg bg-red-900/50 hover:bg-red-800/50 text-red-300 text-sm font-medium transition-colors">Delete File</button>
            </form>
        </div>
    </div>
@endsection
