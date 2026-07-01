@extends('admin.layouts.app')

@section('title', $plan->name)

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.plans.index') }}" class="text-sm text-gray-400 hover:text-gray-200 transition-colors">&larr; Back to Plans</a>
        <div class="flex items-center justify-between mt-2">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">
                    <span class="mr-2">{{ $plan->icon }}</span>
                    {{ $plan->name }}
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Slug: <code class="text-indigo-400">{{ $plan->slug }}</code>
                    &middot;
                    Order: {{ $plan->sort_order }}
                    &middot;
                    @if ($plan->active)
                        <span class="text-emerald-400">Active</span>
                    @else
                        <span class="text-gray-500">Inactive</span>
                    @endif
                </p>
            </div>
            @if (Auth::user()->role !== 'moderator')
                <a href="{{ route('admin.plans.edit', $plan) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">Edit</a>
            @endif
        </div>
    </div>

    <div class="max-w-2xl">
        <div class="rounded-xl bg-gray-900/60 border border-gray-800 p-6">
            <dl class="space-y-4 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Price</dt>
                    <dd class="text-white">
                        @if ($plan->price > 0)
                            ${{ number_format($plan->price, 2) }}
                        @else
                            <span class="text-gray-400">Free</span>
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between border-t border-gray-800 pt-4">
                    <dt class="text-gray-500">Max Sessions</dt>
                    <dd class="text-white">{{ $plan->max_sessions === -1 ? '∞ Unlimited' : $plan->max_sessions }}</dd>
                </div>
                <div class="flex justify-between border-t border-gray-800 pt-4">
                    <dt class="text-gray-500">Max Objects Per Scene</dt>
                    <dd class="text-white">{{ $plan->max_objects_per_scene === -1 ? '∞ Unlimited' : $plan->max_objects_per_scene }}</dd>
                </div>
                <div class="flex justify-between border-t border-gray-800 pt-4">
                    <dt class="text-gray-500">Duration</dt>
                    <dd class="text-white">
                        @if ($plan->duration_days)
                            {{ $plan->duration_days }} days
                        @elseif ($plan->max_sessions === -1)
                            <span class="text-amber-400">Lifetime</span>
                        @else
                            <span class="text-gray-400">No expiration</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        @if (Auth::user()->role !== 'moderator' && $plan->slug !== 'free')
            <div class="mt-6">
                <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('Delete plan {{ $plan->name }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-900/50 hover:bg-red-800/50 text-red-300 text-sm font-medium transition-colors">Delete Plan</button>
                </form>
            </div>
        @endif
    </div>
@endsection
