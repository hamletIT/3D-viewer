@extends('admin.layouts.app')

@section('title', $socialDiscount->label)

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.social-discounts.index') }}" class="text-sm text-gray-400 hover:text-gray-200 transition-colors">&larr; Back to Social Discounts</a>
        <h1 class="text-2xl font-bold tracking-tight mt-2">{{ $socialDiscount->icon }} {{ $socialDiscount->label }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $socialDiscount->platform }}</p>
    </div>

    <div class="max-w-2xl space-y-4">
        <div class="rounded-xl bg-gray-900/60 border border-gray-800 p-6">
            <dl class="space-y-4">
                <div class="flex justify-between">
                    <dt class="text-gray-500 text-sm">Platform</dt>
                    <dd class="text-sm font-medium">{{ $socialDiscount->platform }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 text-sm">Label</dt>
                    <dd class="text-sm font-medium">{{ $socialDiscount->label }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 text-sm">Icon</dt>
                    <dd class="text-xl">{{ $socialDiscount->icon }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 text-sm">Discount</dt>
                    <dd class="text-sm font-medium text-emerald-400">{{ $socialDiscount->discount_percent }}%</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 text-sm">Description</dt>
                    <dd class="text-sm text-gray-300 text-right max-w-xs">{{ $socialDiscount->description ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 text-sm">Status</dt>
                    <dd class="text-sm">
                        @if ($socialDiscount->is_active)
                            <span class="text-emerald-400">Active</span>
                        @else
                            <span class="text-gray-500">Inactive</span>
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500 text-sm">Sort Order</dt>
                    <dd class="text-sm text-gray-300">{{ $socialDiscount->sort_order }}</dd>
                </div>
                @if ($socialDiscount->share_url)
                    <div class="flex justify-between">
                        <dt class="text-gray-500 text-sm">Share URL</dt>
                        <dd class="text-sm text-indigo-400 text-right max-w-xs truncate">{{ $socialDiscount->share_url }}</dd>
                    </div>
                @endif
                <div class="flex justify-between">
                    <dt class="text-gray-500 text-sm">Created</dt>
                    <dd class="text-sm text-gray-300">{{ $socialDiscount->created_at->format('M j, Y g:i A') }}</dd>
                </div>
            </dl>
        </div>

        @if (Auth::user()->role !== 'moderator')
            <div class="flex gap-3">
                <a href="{{ route('admin.social-discounts.edit', $socialDiscount) }}" class="px-4 py-2 rounded-lg bg-gray-800 hover:bg-gray-700 text-sm font-medium transition-colors">Edit</a>
                <form method="POST" action="{{ route('admin.social-discounts.destroy', $socialDiscount) }}" onsubmit="return confirm('Delete this discount?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-900/50 hover:bg-red-800/50 text-red-300 text-sm font-medium transition-colors">Delete</button>
                </form>
            </div>
        @endif
    </div>
@endsection
