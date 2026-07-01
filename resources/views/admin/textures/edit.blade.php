@extends('admin.layouts.app')

@section('title', 'Edit Texture')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.textures.index') }}" class="text-sm text-gray-400 hover:text-gray-200 transition-colors">&larr; Back to Textures</a>
        <h1 class="text-2xl font-bold tracking-tight mt-2">Edit Texture</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $texture->name }}</p>
    </div>

    <div class="max-w-2xl rounded-xl bg-gray-900/60 border border-gray-800 p-6">
        <form method="POST" action="{{ route('admin.textures.update', $texture) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="mb-5">
                <label for="name" class="block text-sm font-medium mb-2">Texture Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $texture->name) }}" required
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                @error('name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium mb-2">Current Image</label>
                <img src="/storage/{{ $texture->file_path }}" alt="{{ $texture->name }}" class="w-32 h-32 rounded object-cover border border-gray-700" loading="lazy">
            </div>

            <div class="mb-5">
                <label for="image" class="block text-sm font-medium mb-2">Replace Image (leave empty to keep current)</label>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp"
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-indigo-600 file:text-white file:text-xs file:font-medium hover:file:bg-indigo-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                <p class="mt-1 text-xs text-gray-500">JPEG, PNG, GIF, or WebP — max 5MB</p>
                @error('image') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="px-6 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">Update Texture</button>
        </form>
    </div>
@endsection
