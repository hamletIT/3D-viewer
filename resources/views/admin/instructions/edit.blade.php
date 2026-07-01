@extends('admin.layouts.app')

@section('title', 'Edit Instruction')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.instructions.index') }}" class="text-sm text-gray-400 hover:text-gray-200 transition-colors">&larr; Back to Instructions</a>
        <h1 class="text-2xl font-bold tracking-tight mt-2">Edit Instruction</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $instruction->title }}</p>
    </div>

    <div class="max-w-2xl rounded-xl bg-gray-900/60 border border-gray-800 p-6">
        <form method="POST" action="{{ route('admin.instructions.update', $instruction) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-5">
                <label for="title" class="block text-sm font-medium mb-2">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $instruction->title) }}" required
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                @error('title') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label for="content" class="block text-sm font-medium mb-2">Content</label>
                <textarea id="content" name="content" rows="8" required
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">{{ old('content', $instruction->content) }}</textarea>
                <p class="mt-1 text-xs text-gray-500">HTML supported — use &lt;strong&gt;, &lt;em&gt;, &lt;ul&gt;, &lt;ol&gt;, &lt;p&gt;, etc.</p>
                @error('content') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            @if ($instruction->image_path)
                <div class="mb-5">
                    <label class="block text-sm font-medium mb-2">Current Image</label>
                    <img src="/storage/{{ $instruction->image_path }}" alt="{{ $instruction->title }}" class="w-48 h-auto rounded border border-gray-700" loading="lazy">
                </div>
            @endif

            <div class="mb-5">
                <label for="image" class="block text-sm font-medium mb-2">{{ $instruction->image_path ? 'Replace Image (leave empty to keep)' : 'Image / Schema (optional)' }}</label>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp"
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-indigo-600 file:text-white file:text-xs file:font-medium hover:file:bg-indigo-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                <p class="mt-1 text-xs text-gray-500">JPEG, PNG, GIF, or WebP — max 5MB</p>
                @error('image') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5 flex items-center gap-6">
                <div>
                    <label for="sort_order" class="block text-sm font-medium mb-2">Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $instruction->sort_order) }}" min="0"
                        class="w-24 rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    @error('sort_order') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div class="pt-6">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="active" value="1" {{ old('active', $instruction->active) ? 'checked' : '' }}
                            class="rounded bg-gray-800 border-gray-600 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm font-medium">Active</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="px-6 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">Update Instruction</button>
        </form>
    </div>
@endsection
