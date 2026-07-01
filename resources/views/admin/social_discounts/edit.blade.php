@extends('admin.layouts.app')

@section('title', 'Edit Social Discount')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.social-discounts.index') }}" class="text-sm text-gray-400 hover:text-gray-200 transition-colors">&larr; Back to Social Discounts</a>
        <h1 class="text-2xl font-bold tracking-tight mt-2">Edit Social Discount</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $socialDiscount->platform }} — {{ $socialDiscount->label }}</p>
    </div>

    <div class="max-w-2xl rounded-xl bg-gray-900/60 border border-gray-800 p-6">
        <form method="POST" action="{{ route('admin.social-discounts.update', $socialDiscount) }}">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label for="platform" class="block text-sm font-medium mb-2">Platform</label>
                    <input type="text" id="platform" name="platform" value="{{ old('platform', $socialDiscount->platform) }}" required
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    @error('platform') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="label" class="block text-sm font-medium mb-2">Label</label>
                    <input type="text" id="label" name="label" value="{{ old('label', $socialDiscount->label) }}" required
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    @error('label') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label for="icon" class="block text-sm font-medium mb-2">Icon</label>
                    <input type="text" id="icon" name="icon" value="{{ old('icon', $socialDiscount->icon) }}"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    @error('icon') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="discount_percent" class="block text-sm font-medium mb-2">Discount (%)</label>
                    <input type="number" id="discount_percent" name="discount_percent" value="{{ old('discount_percent', $socialDiscount->discount_percent) }}" required min="0" max="100"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    @error('discount_percent') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-5">
                <label for="description" class="block text-sm font-medium mb-2">Description</label>
                <textarea id="description" name="description" rows="2"
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">{{ old('description', $socialDiscount->description) }}</textarea>
                @error('description') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label for="share_url" class="block text-sm font-medium mb-2">Share URL (optional)</label>
                <input type="url" id="share_url" name="share_url" value="{{ old('share_url', $socialDiscount->share_url) }}"
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                @php $urlPlaceholder = '{{URL}}'; @endphp
                <p class="mt-1 text-xs text-gray-500">Use <code>{{ urlencode($urlPlaceholder) }}</code> as placeholder for the app URL.</p>
                @error('share_url') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5 flex items-center gap-6">
                <div>
                    <label for="sort_order" class="block text-sm font-medium mb-2">Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $socialDiscount->sort_order) }}" min="0"
                        class="w-24 rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    @error('sort_order') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div class="pt-6">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $socialDiscount->is_active) ? 'checked' : '' }}
                            class="rounded bg-gray-800 border-gray-600 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm font-medium">Active</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="px-6 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">Update Discount</button>
        </form>
    </div>
@endsection
