@extends('admin.layouts.app')

@section('title', $landingSection->slug)

@section('content')
    <div class="mb-8">
        <a href="{{ route('admin.landing-sections.index') }}" class="text-sm text-gray-400 hover:text-gray-200 transition-colors">&larr; Back to Sections</a>
        <h1 class="text-2xl font-bold tracking-tight mt-2">{{ $landingSection->slug }}</h1>
        <p class="text-sm text-gray-500 mt-1">Type: {{ $landingSection->type }}</p>
    </div>

    <div class="max-w-2xl space-y-6">
        <div class="rounded-xl bg-gray-900/60 border border-gray-800 p-6">
            <h2 class="text-sm font-medium text-gray-400 mb-4 uppercase tracking-wider">Section Details</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500">Icon</p>
                    <p class="text-2xl">{{ $landingSection->icon ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Active</p>
                    <p>@if ($landingSection->active)<span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span> Yes @else No @endif</p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs text-gray-500">Title</p>
                    <p class="text-lg font-semibold">{{ $landingSection->title ?? '—' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs text-gray-500">Subtitle</p>
                    <p>{{ $landingSection->subtitle ?? '—' }}</p>
                </div>
                @if ($landingSection->content)
                    <div class="col-span-2">
                        <p class="text-xs text-gray-500">Content</p>
                        <div class="mt-1 p-3 rounded-lg bg-gray-800/40 text-sm text-gray-400 whitespace-pre-wrap">{{ $landingSection->content }}</div>
                    </div>
                @endif
                @if ($landingSection->image_path)
                    <div class="col-span-2">
                        <p class="text-xs text-gray-500">Image</p>
                        <img src="/storage/{{ $landingSection->image_path }}" alt="" class="mt-1 h-32 rounded border border-gray-700" loading="lazy">
                    </div>
                @endif
                @if ($landingSection->link_url)
                    <div class="col-span-2">
                        <p class="text-xs text-gray-500">Link</p>
                        <a href="{{ $landingSection->link_url }}" class="text-indigo-400 hover:text-indigo-300 text-sm" target="_blank">{{ $landingSection->link_text ?: $landingSection->link_url }}</a>
                    </div>
                @endif
                @if ($landingSection->data)
                    <div class="col-span-2">
                        <p class="text-xs text-gray-500">Extra Data</p>
                        <pre class="mt-1 p-3 rounded-lg bg-gray-800/40 text-xs text-gray-500 overflow-x-auto">{{ json_encode($landingSection->data, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                @endif
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.landing-sections.edit', $landingSection) }}" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-sm font-medium transition-colors">Edit Section</a>
            </div>
        </div>

        @if ($landingSection->features->count())
            <div class="rounded-xl bg-gray-900/60 border border-gray-800 p-6">
                <h2 class="text-sm font-medium text-gray-400 mb-4 uppercase tracking-wider">Features ({{ $landingSection->features->count() }})</h2>
                <div class="space-y-3">
                    @foreach ($landingSection->features as $f)
                        <div class="flex items-start gap-3 bg-gray-800/40 rounded-lg p-3">
                            <div class="text-2xl">{{ $f->icon }}</div>
                            <div>
                                <p class="text-sm font-medium">{{ $f->title }}</p>
                                <p class="text-xs text-gray-500">{{ $f->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
