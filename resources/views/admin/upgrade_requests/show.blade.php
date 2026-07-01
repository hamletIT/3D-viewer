@extends('admin.layouts.app')

@section('title', 'Upgrade Request')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.upgrade-requests.index') }}" class="text-sm text-gray-400 hover:text-gray-200 transition-colors">&larr; Back to Requests</a>
        <h1 class="text-2xl font-bold tracking-tight mt-2">Upgrade Request</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="rounded-xl bg-gray-900/60 border border-gray-800 p-6">
            <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">User Details</h2>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Name</dt>
                    <dd class="text-white">{{ $upgradeRequest->user->name }}</dd>
                </div>
                <div class="flex justify-between border-t border-gray-800 pt-3">
                    <dt class="text-gray-500">Email</dt>
                    <dd class="text-white">{{ $upgradeRequest->user->email }}</dd>
                </div>
                <div class="flex justify-between border-t border-gray-800 pt-3">
                    <dt class="text-gray-500">Role</dt>
                    <dd class="text-white capitalize">{{ $upgradeRequest->user->role }}</dd>
                </div>
                <div class="flex justify-between border-t border-gray-800 pt-3">
                    <dt class="text-gray-500">Registered</dt>
                    <dd class="text-white text-xs">{{ $upgradeRequest->user->created_at->format('M j, Y') }}</dd>
                </div>
            </dl>
        </div>

        <div class="rounded-xl bg-gray-900/60 border border-gray-800 p-6">
            <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Request Details</h2>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Plan</dt>
                    <dd class="text-white">
                        <span class="text-lg mr-1">{{ $upgradeRequest->plan->icon }}</span>
                        {{ $upgradeRequest->plan->name }}
                        (${{ number_format($upgradeRequest->plan->price, 2) }})
                    </dd>
                </div>
                <div class="flex justify-between border-t border-gray-800 pt-3">
                    <dt class="text-gray-500">Contact Name</dt>
                    <dd class="text-white">{{ $upgradeRequest->name }}</dd>
                </div>
                <div class="flex justify-between border-t border-gray-800 pt-3">
                    <dt class="text-gray-500">Contact Email</dt>
                    <dd class="text-white">{{ $upgradeRequest->email }}</dd>
                </div>
                <div class="flex justify-between border-t border-gray-800 pt-3">
                    <dt class="text-gray-500">Contact Phone</dt>
                    <dd class="text-white">{{ $upgradeRequest->phone }}</dd>
                </div>
                <div class="flex justify-between border-t border-gray-800 pt-3">
                    <dt class="text-gray-500">Status</dt>
                    <dd class="text-white">
                        @if ($upgradeRequest->status === 'pending')
                            <span class="text-amber-400">Pending</span>
                        @elseif ($upgradeRequest->status === 'approved')
                            <span class="text-emerald-400">Approved</span>
                        @else
                            <span class="text-red-400">Rejected</span>
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between border-t border-gray-800 pt-3">
                    <dt class="text-gray-500">Submitted</dt>
                    <dd class="text-white text-xs">{{ $upgradeRequest->created_at->format('M j, Y g:i A') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    @if ($upgradeRequest->status === 'pending' && Auth::user()->role !== 'moderator')
        <div class="mt-6 flex items-center gap-4">
            <form method="POST" action="{{ route('admin.upgrade-requests.approve', $upgradeRequest) }}">
                @csrf @method('PATCH')
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-sm font-medium transition-colors">Approve & Upgrade</button>
            </form>
            <form method="POST" action="{{ route('admin.upgrade-requests.reject', $upgradeRequest) }}" class="inline-flex items-center gap-3">
                @csrf @method('PATCH')
                <input type="text" name="admin_notes" placeholder="Rejection reason (optional)"
                    class="rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors w-64">
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-red-900/50 hover:bg-red-800/50 text-red-300 text-sm font-medium transition-colors">Reject</button>
            </form>
        </div>
    @endif

    @if ($upgradeRequest->admin_notes)
        <div class="mt-6 max-w-2xl rounded-xl bg-gray-900/60 border border-gray-800 p-6">
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Admin Notes</h3>
            <p class="text-sm text-gray-300">{{ $upgradeRequest->admin_notes }}</p>
        </div>
    @endif
@endsection
