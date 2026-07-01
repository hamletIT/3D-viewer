@extends('admin.layouts.app')

@section('title', 'Edit Plan')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.plans.index') }}" class="text-sm text-gray-400 hover:text-gray-200 transition-colors">&larr; Back to Plans</a>
        <h1 class="text-2xl font-bold tracking-tight mt-2">Edit Plan</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $plan->name }}</p>
    </div>

    <div class="max-w-2xl rounded-xl bg-gray-900/60 border border-gray-800 p-6">
        <form method="POST" action="{{ route('admin.plans.update', $plan) }}">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label for="name" class="block text-sm font-medium mb-2">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $plan->name) }}" required
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    @error('name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="slug" class="block text-sm font-medium mb-2">Slug</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $plan->slug) }}" required
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    @error('slug') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label for="max_sessions" class="block text-sm font-medium mb-2">Max Sessions</label>
                    <input type="number" id="max_sessions" name="max_sessions" value="{{ old('max_sessions', $plan->max_sessions) }}" required
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    <p class="mt-1 text-xs text-gray-500">Use -1 for unlimited</p>
                    @error('max_sessions') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="max_objects_per_scene" class="block text-sm font-medium mb-2">Max Objects Per Scene</label>
                    <input type="number" id="max_objects_per_scene" name="max_objects_per_scene" value="{{ old('max_objects_per_scene', $plan->max_objects_per_scene) }}" required
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    <p class="mt-1 text-xs text-gray-500">Use -1 for unlimited</p>
                    @error('max_objects_per_scene') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-5">
                <div>
                    <label for="price" class="block text-sm font-medium mb-2">Price ($)</label>
                    <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $plan->price) }}" required
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    @error('price') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="icon" class="block text-sm font-medium mb-2">Icon (emoji)</label>
                    <input type="text" id="icon" name="icon" value="{{ old('icon', $plan->icon) }}" required
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    @error('icon') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="duration_days" class="block text-sm font-medium mb-2">Duration (days)</label>
                    <input type="number" id="duration_days" name="duration_days" value="{{ old('duration_days', $plan->duration_days) }}"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    <p class="mt-1 text-xs text-gray-500">Leave empty for lifetime</p>
                    @error('duration_days') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-5 flex items-center gap-6">
                <div>
                    <label for="sort_order" class="block text-sm font-medium mb-2">Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $plan->sort_order) }}" min="0"
                        class="w-24 rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    @error('sort_order') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div class="pt-6">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="active" value="1" {{ old('active', $plan->active) ? 'checked' : '' }}
                            class="rounded bg-gray-800 border-gray-600 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm font-medium">Active</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="px-6 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">Update Plan</button>
        </form>
    </div>
@endsection
