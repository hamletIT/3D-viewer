@extends('admin.layouts.app')

@section('title', 'Plans')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Plans</h1>
        @if (Auth::user()->role !== 'moderator')
            <a href="{{ route('admin.plans.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Plan
            </a>
        @endif
    </div>

    <div class="rounded-xl bg-gray-900/60 border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500 border-b border-gray-800">
                        <th class="text-left py-4 px-4 font-medium">Icon</th>
                        <th class="text-left py-4 px-4 font-medium">Name</th>
                        <th class="text-center py-4 px-4 font-medium">Max Sessions</th>
                        <th class="text-center py-4 px-4 font-medium">Max Objects</th>
                        <th class="text-left py-4 px-4 font-medium">Price</th>
                        <th class="text-center py-4 px-4 font-medium">Duration</th>
                        <th class="text-center py-4 px-4 font-medium">Active</th>
                        <th class="text-center py-4 px-4 font-medium">Order</th>
                        @if (Auth::user()->role !== 'moderator')
                            <th class="text-right py-4 px-4 font-medium">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($plans as $plan)
                        <tr class="border-b border-gray-800/50 hover:bg-gray-800/30 transition-colors">
                            <td class="py-3 px-4 text-xl">{{ $plan->icon }}</td>
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.plans.show', $plan) }}" class="text-indigo-400 hover:text-indigo-300">{{ $plan->name }}</a>
                            </td>
                            <td class="py-3 px-4 text-center text-gray-400">
                                {{ $plan->max_sessions === -1 ? '∞' : $plan->max_sessions }}
                            </td>
                            <td class="py-3 px-4 text-center text-gray-400">
                                {{ $plan->max_objects_per_scene === -1 ? '∞' : $plan->max_objects_per_scene }}
                            </td>
                            <td class="py-3 px-4">
                                @if ($plan->price > 0)
                                    ${{ number_format($plan->price, 2) }}
                                @else
                                    <span class="text-gray-600">Free</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center text-gray-500">
                                @if ($plan->duration_days)
                                    {{ $plan->duration_days }} days
                                @elseif ($plan->max_sessions === -1)
                                    <span class="text-amber-400">Lifetime</span>
                                @else
                                    <span class="text-gray-600">—</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if ($plan->active)
                                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                                @else
                                    <span class="inline-block w-2 h-2 rounded-full bg-gray-600"></span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center text-gray-500">{{ $plan->sort_order }}</td>
                            @if (Auth::user()->role !== 'moderator')
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.plans.edit', $plan) }}" class="px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-xs font-medium transition-colors">Edit</a>
                                        @if ($plan->slug !== 'free')
                                            <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('Delete plan {{ $plan->name }}?')">
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
                            <td colspan="9" class="py-12 text-center text-gray-500">No plans found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $plans->links() }}
    </div>
@endsection
