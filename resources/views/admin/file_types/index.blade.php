@extends('admin.layouts.app')

@section('title', 'Uploaded Files')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Uploaded Files</h1>
    </div>

    <div class="rounded-xl bg-gray-900/60 border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500 border-b border-gray-800">
                        <th class="text-left py-4 px-4 font-medium">Original Name</th>
                        <th class="text-left py-4 px-4 font-medium">Model Name</th>
                        <th class="text-left py-4 px-4 font-medium">User</th>
                        <th class="text-left py-4 px-4 font-medium">Type</th>
                        <th class="text-left py-4 px-4 font-medium">Size</th>
                        <th class="text-left py-4 px-4 font-medium">Date</th>
                        @if (Auth::user()->role !== 'moderator')
                            <th class="text-right py-4 px-4 font-medium">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fileTypes as $fileType)
                        <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition-colors">
                            <td class="py-3 px-4 font-medium">{{ $fileType->original_name }}</td>
                            <td class="py-3 px-4 text-gray-400">{{ $fileType->model_name ?? '—' }}</td>
                            <td class="py-3 px-4">{{ $fileType->user->name }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-xs bg-gray-800 text-gray-300">{{ $fileType->mime_type ?? 'N/A' }}</span>
                            </td>
                            <td class="py-3 px-4 text-gray-500">
                                @if ($fileType->file_size)
                                    {{ round($fileType->file_size / 1024, 1) }} KB
                                @else
                                    —
                                @endif
                            </td>
                            <td class="py-3 px-4 text-gray-500">{{ $fileType->created_at->format('M j, Y g:i A') }}</td>
                            @if (Auth::user()->role !== 'moderator')
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.file-types.show', $fileType) }}" class="px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-xs font-medium transition-colors">View</a>
                                        <form method="POST" action="{{ route('admin.file-types.destroy', $fileType) }}" onsubmit="return confirm('Delete file {{ $fileType->original_name }}? This will also delete the file from storage.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-900/50 hover:bg-red-800/50 text-red-300 text-xs font-medium transition-colors">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-500">No files uploaded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $fileTypes->links() }}
    </div>
@endsection
