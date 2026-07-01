@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Settings</h1>
        @if (Auth::user()->role !== 'moderator')
            <a href="{{ route('admin.settings.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Setting
            </a>
        @endif
    </div>

    <div class="rounded-xl bg-gray-900/60 border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500 border-b border-gray-800">
                        <th class="text-left py-4 px-4 font-medium">Key</th>
                        <th class="text-left py-4 px-4 font-medium">Value</th>
                        <th class="text-left py-4 px-4 font-medium">Type</th>
                        <th class="text-left py-4 px-4 font-medium">Description</th>
                        @if (Auth::user()->role !== 'moderator')
                            <th class="text-right py-4 px-4 font-medium">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($settings as $setting)
                        <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition-colors">
                            <td class="py-3 px-4 font-mono text-sm">{{ $setting->key }}</td>
                            <td class="py-3 px-4 text-gray-400 max-w-xs truncate">{{ $setting->value ?? '—' }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-xs bg-gray-800 text-gray-300">{{ $setting->type }}</span>
                            </td>
                            <td class="py-3 px-4 text-gray-500 max-w-xs truncate">{{ $setting->description ?? '—' }}</td>
                            @if (Auth::user()->role !== 'moderator')
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.settings.edit', $setting) }}" class="px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-xs font-medium transition-colors">Edit</a>
                                        <form method="POST" action="{{ route('admin.settings.destroy', $setting) }}" onsubmit="return confirm('Delete setting {{ $setting->key }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-900/50 hover:bg-red-800/50 text-red-300 text-xs font-medium transition-colors">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-500">No settings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $settings->links() }}
    </div>
@endsection
