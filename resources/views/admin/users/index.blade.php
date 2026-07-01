@extends('admin.layouts.app')

@section('title', 'Users')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Users</h1>
        @if (Auth::user()->role !== 'moderator')
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add User
            </a>
        @endif
    </div>

    <div class="rounded-xl bg-gray-900/60 border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500 border-b border-gray-800">
                        <th class="text-left py-4 px-4 font-medium">Photo</th>
                        <th class="text-left py-4 px-4 font-medium">Name</th>
                        <th class="text-left py-4 px-4 font-medium">Email</th>
                        <th class="text-left py-4 px-4 font-medium">Role</th>
                        <th class="text-left py-4 px-4 font-medium">Joined</th>
                        @if (Auth::user()->role !== 'moderator')
                            <th class="text-right py-4 px-4 font-medium">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition-colors">
                            <td class="py-3 px-4">
                                @if ($user->photo)
                                    <img src="{{ Storage::url($user->photo) }}" alt="" class="w-9 h-9 rounded-full object-cover" loading="lazy">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-indigo-600/30 flex items-center justify-center text-sm font-medium">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-medium">{{ $user->name }}</td>
                            <td class="py-3 px-4 text-gray-400">{{ $user->email }}</td>
                            <td class="py-3 px-4">
                                @if ($user->role === 'admin')
                                    <span class="px-2 py-0.5 rounded text-xs bg-indigo-600/30 text-indigo-300">admin</span>
                                @elseif ($user->role === 'moderator')
                                    <span class="px-2 py-0.5 rounded text-xs bg-amber-600/30 text-amber-300">moderator</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-xs bg-gray-700 text-gray-400">user</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-gray-500">{{ $user->created_at->format('M j, Y') }}</td>
                            @if (Auth::user()->role !== 'moderator')
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-xs font-medium transition-colors">Edit</a>
                                        @if ($user->id !== Auth::id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete user {{ $user->name }}?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-900/50 hover:bg-red-800/50 text-red-300 text-xs font-medium transition-colors">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
@endsection
