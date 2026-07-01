@extends('admin.layouts.app')

@section('title', 'Create Setting')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.settings.index') }}" class="text-sm text-gray-400 hover:text-gray-200 transition-colors">&larr; Back to Settings</a>
        <h1 class="text-2xl font-bold tracking-tight mt-2">Create Setting</h1>
    </div>

    <div class="max-w-2xl rounded-xl bg-gray-900/60 border border-gray-800 p-6">
        <form method="POST" action="{{ route('admin.settings.store') }}">
            @csrf

            <div class="mb-5">
                <label for="key" class="block text-sm font-medium mb-2">Key</label>
                <input type="text" id="key" name="key" value="{{ old('key') }}" required
                    placeholder="e.g. app_name, max_file_size"
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors placeholder-gray-500 font-mono">
                <p class="mt-1 text-xs text-gray-500">Must start with a letter, and may contain letters, numbers, dots, and underscores.</p>
                @error('key') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label for="value" class="block text-sm font-medium mb-2">Value</label>
                <textarea id="value" name="value" rows="3"
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors placeholder-gray-500 font-mono">{{ old('value') }}</textarea>
                @error('value') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label for="type" class="block text-sm font-medium mb-2">Type</label>
                <select id="type" name="type" required
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors">
                    <option value="string" @selected(old('type') === 'string')>String</option>
                    <option value="text" @selected(old('type') === 'text')>Text</option>
                    <option value="boolean" @selected(old('type') === 'boolean')>Boolean</option>
                    <option value="integer" @selected(old('type') === 'integer')>Integer</option>
                    <option value="json" @selected(old('type') === 'json')>JSON</option>
                </select>
                @error('type') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label for="description" class="block text-sm font-medium mb-2">Description <span class="text-gray-500 font-normal">(optional)</span></label>
                <textarea id="description" name="description" rows="2"
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-colors placeholder-gray-500">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="px-6 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">Create Setting</button>
        </form>
    </div>
@endsection
