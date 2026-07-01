@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-2xl font-bold tracking-tight mb-8">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="rounded-xl bg-gray-900/60 border border-gray-800 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-indigo-600/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total Users</p>
                    <p class="text-2xl font-bold">{{ \App\Models\User::count() }}</p>
                </div>
            </div>
        </div>
        <div class="rounded-xl bg-gray-900/60 border border-gray-800 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-amber-600/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Settings</p>
                    <p class="text-2xl font-bold">{{ \App\Models\Setting::count() }}</p>
                </div>
            </div>
        </div>
        <div class="rounded-xl bg-gray-900/60 border border-gray-800 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-emerald-600/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Uploaded Files</p>
                    <p class="text-2xl font-bold">{{ \App\Models\FileType::count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="rounded-xl bg-gray-900/60 border border-gray-800 p-6">
            <h2 class="text-lg font-semibold mb-2">Landing Page</h2>
            <p class="text-sm text-gray-500 mb-4">Control what guests see on the homepage. Edit hero text, features, and visibility.</p>
            <a href="{{ route('admin.settings.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600/20 text-indigo-300 hover:bg-indigo-600/30 text-sm font-medium transition-colors">
                Manage Landing Settings &rarr;
            </a>
        </div>

        <div class="rounded-xl bg-gray-900/60 border border-gray-800 p-6">
        <h2 class="text-lg font-semibold mb-4">Recent File Uploads</h2>
        @php $recentFiles = \App\Models\FileType::with('user')->latest()->take(5)->get(); @endphp
        @if ($recentFiles->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-gray-500 border-b border-gray-800">
                            <th class="text-left py-3 px-2 font-medium">Name</th>
                            <th class="text-left py-3 px-2 font-medium">User</th>
                            <th class="text-left py-3 px-2 font-medium">Type</th>
                            <th class="text-right py-3 px-2 font-medium">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentFiles as $file)
                            <tr class="border-b border-gray-800/50 hover:bg-gray-800/30">
                                <td class="py-3 px-2">{{ $file->original_name }}</td>
                                <td class="py-3 px-2 text-gray-400">{{ $file->user->name }}</td>
                                <td class="py-3 px-2">
                                    <span class="px-2 py-0.5 rounded text-xs bg-gray-800 text-gray-300">{{ $file->mime_type ?? 'N/A' }}</span>
                                </td>
                                <td class="py-3 px-2 text-right text-gray-500">{{ $file->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-sm">No files uploaded yet.</p>
        @endif
    </div>
@endsection
