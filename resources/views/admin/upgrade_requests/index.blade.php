@extends('admin.layouts.app')

@section('title', 'Upgrade Requests')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Upgrade Requests</h1>
    </div>

    <div class="rounded-xl bg-gray-900/60 border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500 border-b border-gray-800">
                        <th class="text-left py-4 px-4 font-medium">User</th>
                        <th class="text-left py-4 px-4 font-medium">Plan</th>
                        <th class="text-left py-4 px-4 font-medium">Contact</th>
                        <th class="text-center py-4 px-4 font-medium">Status</th>
                        <th class="text-left py-4 px-4 font-medium">Date</th>
                        <th class="text-right py-4 px-4 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $req)
                        <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition-colors">
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.upgrade-requests.show', $req) }}" class="text-indigo-400 hover:text-indigo-300">{{ $req->user->name }}</a>
                                <div class="text-xs text-gray-600">{{ $req->user->email }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-lg">{{ $req->plan->icon }}</span>
                                <span class="ml-1">{{ $req->plan->name }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <div>{{ $req->name }}</div>
                                <div class="text-xs text-gray-500">{{ $req->phone }}</div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if ($req->status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-900/40 text-amber-400 border border-amber-700/50">
                                        Pending
                                    </span>
                                @elseif ($req->status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-900/40 text-emerald-400 border border-emerald-700/50">
                                        Approved
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-900/40 text-red-400 border border-red-700/50">
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-gray-500 text-xs">{{ $req->created_at->format('M j, Y g:i A') }}</td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.upgrade-requests.show', $req) }}" class="px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-xs font-medium transition-colors">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-500">No upgrade requests yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $requests->links() }}
    </div>
@endsection
