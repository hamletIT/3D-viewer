@extends('admin.layouts.app')

@section('title', 'Edit Landing Section')

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.landing-sections.index') }}" class="text-sm text-gray-400 hover:text-gray-200 transition-colors">&larr; Back to Sections</a>
        <h1 class="text-2xl font-bold tracking-tight mt-2">Edit Section: <span class="text-indigo-400">{{ $landingSection->slug }}</span></h1>
    </div>

    <div class="max-w-2xl rounded-xl bg-gray-900/60 border border-gray-800 p-6 mb-8">
        <form method="POST" action="{{ route('admin.landing-sections.update', $landingSection) }}" enctype="multipart/form-data">
            @csrf @method('PATCH')

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Slug *</label>
                    <input type="text" name="slug" required value="{{ old('slug', $landingSection->slug) }}"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none transition-colors">
                    @error('slug') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Type *</label>
                    <select name="type" required class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none transition-colors">
                        @foreach (['section', 'hero', 'features', 'scan', 'footer', 'brand'] as $t)
                            <option value="{{ $t }}" {{ old('type', $landingSection->type) === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Icon</label>
                    <input type="text" name="icon" value="{{ old('icon', $landingSection->icon) }}" maxlength="50"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $landingSection->sort_order) }}" min="0"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none transition-colors">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-400 mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title', $landingSection->title) }}"
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none transition-colors">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-400 mb-1">Subtitle</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $landingSection->subtitle) }}"
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none transition-colors">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-400 mb-1">Content</label>
                <textarea name="content" rows="5"
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none transition-colors">{{ old('content', $landingSection->content) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Link URL</label>
                    <input type="text" name="link_url" value="{{ old('link_url', $landingSection->link_url) }}"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Link Text</label>
                    <input type="text" name="link_text" value="{{ old('link_text', $landingSection->link_text) }}"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm focus:border-indigo-500 outline-none transition-colors">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-400 mb-1">Image</label>
                @if ($landingSection->image_path)
                    <div class="mb-2">
                        <img src="/storage/{{ $landingSection->image_path }}" alt="" class="h-24 rounded border border-gray-700" loading="lazy">
                    </div>
                @endif
                <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp"
                    class="text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-indigo-600 file:text-white file:text-xs file:font-medium hover:file:bg-indigo-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-400 mb-1">Extra Data (JSON)</label>
                <textarea name="data" rows="3"
                    class="w-full rounded-lg bg-gray-800 border border-gray-700 px-4 py-2.5 text-sm font-mono focus:border-indigo-500 outline-none transition-colors">{{ old('data', $landingSection->data ? json_encode($landingSection->data, JSON_PRETTY_PRINT) : '') }}</textarea>
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="active" value="1" {{ old('active', $landingSection->active) ? 'checked' : '' }}
                        class="rounded bg-gray-800 border-gray-700 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-sm text-gray-400">Active</span>
                </label>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">Update Section</button>
                <a href="{{ route('admin.landing-sections.index') }}" class="px-6 py-2.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-sm font-medium transition-colors">Cancel</a>
            </div>
        </form>
    </div>

    {{-- Features --}}
    @if ($landingSection->type === 'features')
        <div class="max-w-2xl rounded-xl bg-gray-900/60 border border-gray-800 p-6">
            <h2 class="text-lg font-semibold mb-4">Feature Items</h2>

            @if ($landingSection->features->count())
                <div class="space-y-3 mb-6">
                    @foreach ($landingSection->features as $f)
                        <div class="flex items-start gap-3 bg-gray-800/40 rounded-lg p-3">
                            <div class="text-2xl">{{ $f->icon }}</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium">{{ $f->title }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $f->description }}</p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <form method="POST" action="{{ route('admin.landing-features.update', $f) }}" class="flex items-center gap-1">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="active" value="0">
                                    <input type="checkbox" name="active" value="1" onchange="this.form.submit()" {{ $f->active ? 'checked' : '' }}
                                        class="rounded bg-gray-800 border-gray-700 text-indigo-600 focus:ring-indigo-500">
                                </form>
                                <form method="POST" action="{{ route('admin.landing-features.destroy', $f) }}" onsubmit="return confirm('Delete this feature?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 text-xs">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <h3 class="text-sm font-medium text-gray-400 mb-3">Add Feature</h3>
            <form method="POST" action="{{ route('admin.landing-sections.features.store', $landingSection) }}">
                @csrf
                <div class="grid grid-cols-3 gap-3 mb-3">
                    <div>
                        <input type="text" name="icon" placeholder="🎮" maxlength="50"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2 text-sm focus:border-indigo-500 outline-none">
                    </div>
                    <div>
                        <input type="text" name="title" required placeholder="Feature title"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2 text-sm focus:border-indigo-500 outline-none">
                    </div>
                    <div>
                        <input type="number" name="sort_order" placeholder="Order" min="0" value="0"
                            class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2 text-sm focus:border-indigo-500 outline-none">
                    </div>
                </div>
                <div class="mb-3">
                    <textarea name="description" rows="2" placeholder="Feature description…"
                        class="w-full rounded-lg bg-gray-800 border border-gray-700 px-3 py-2 text-sm focus:border-indigo-500 outline-none"></textarea>
                </div>
                <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">Add Feature</button>
            </form>
        </div>
    @endif
@endsection
