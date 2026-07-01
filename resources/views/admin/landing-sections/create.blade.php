@extends('admin.layouts.app')

@section('title', 'Create Landing Section')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.landing-sections.index') }}" class="text-sm text-gray-400 hover:text-gray-200 transition-colors">&larr; Back to Sections</a>
        <h1 class="text-2xl font-bold tracking-tight mt-2">Create Landing Section</h1>
    </div>

    <div class="max-w-2xl rounded-xl bg-gray-900/60 border border-gray-800 p-6">
        <form method="POST" action="{{ route('admin.landing-sections.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Slug *</label>
                    <input type="text" name="slug" required value="{{ old('slug') }}" placeholder="hero"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none transition-colors">
                    @error('slug') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Type *</label>
                    <select name="type" required class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none transition-colors">
                        <option value="section">Section</option>
                        <option value="hero">Hero</option>
                        <option value="features">Features</option>
                        <option value="scan">Scan</option>
                        <option value="footer">Footer</option>
                        <option value="brand">Brand</option>
                    </select>
                    @error('type') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Icon</label>
                    <input type="text" name="icon" value="{{ old('icon') }}" placeholder="⚒️" maxlength="50"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none transition-colors">
                    @error('icon') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none transition-colors">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-400 mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Section heading"
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none transition-colors">
                @error('title') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-400 mb-1">Subtitle</label>
                <input type="text" name="subtitle" value="{{ old('subtitle') }}" placeholder="Section subheading"
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none transition-colors">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-400 mb-1">Content</label>
                <textarea name="content" rows="5" placeholder="HTML content or description…"
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none transition-colors">{{ old('content') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Link URL</label>
                    <input type="text" name="link_url" value="{{ old('link_url') }}" placeholder="https://..."
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Link Text</label>
                    <input type="text" name="link_text" value="{{ old('link_text') }}" placeholder="Learn more"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none transition-colors">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-400 mb-1">Image</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp"
                    class="text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-indigo-600 file:text-white file:text-xs file:font-medium hover:file:bg-indigo-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-400 mb-1">Extra Data (JSON)</label>
                <textarea name="data" rows="3" placeholder='{"key": "value"}'
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm font-mono focus:border-indigo-500 outline-none transition-colors">{{ old('data') }}</textarea>
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="active" value="1" checked
                        class="rounded bg-gray-800 border-gray-700 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm text-gray-400">Active</span>
                </label>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">Create Section</button>
                <a href="{{ route('admin.landing-sections.index') }}" class="px-6 py-2.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-sm font-medium transition-colors">Cancel</a>
            </div>
        </form>
    </div>
@endsection
